<?php

namespace NinjaForms\ExcelExport\Handlers;

use NinjaForms\ExcelExport\Contracts\QuerySubmissions as ContractsQuerySubmissions;

class QuerySubmissions implements ContractsQuerySubmissions
{
    /** 
     * Form ID as string
     * 
     * @var string 
     */
    protected $formId;

    /**
     * Submissions per page
     *
     * @var int
     */
    protected $subsPerPage;

    /**
     * Current iteration
     *
     * @var int
     */
    protected $iteration;


    public function querySubmissions(string $formId, int $subsPerPage, int $iteration, array $filters): array
    {
        $query_args = array(
            'post_type'         => 'nf_sub',
            'posts_per_page'    => $subsPerPage,
            'offset'            => $subsPerPage * $iteration,
            'date_query'        => array(
                'inclusive'     => true,
            ),
            'meta_query'        => array(
                array(
                    'key' => '_form_id',
                    'value' => $formId,
                )
            )
        );

        if ($filters) {
            $query_args = $this->constructQueryArgs($query_args,$filters);
        }

        $subs = new \WP_Query($query_args);

        $sub_objects = array();
        $sub_index = 0;

        if (is_array($subs->posts) && !empty($subs->posts)) {
            foreach ($subs->posts as $sub) {
                $sub_objects[$sub_index] = Ninja_Forms()->form($formId)->get_sub($sub->ID)->get_field_values();
                $sub_objects[$sub_index]['date_submitted'] = \get_the_date('', $sub->ID);
                $sub_index++;
            }
        }

        return $sub_objects;
    }

    private function constructQueryArgs($query_args, $filters)
    {
        foreach ($filters as $filter) {
            if ($filter->field_key == 'submission_date') {
                $date = $filter->value;
                if ($filter->condition == 'GT')
                    $query_args['date_query']['after'] = $date . ' 23:59:59';
                elseif ($filter->condition == 'GE')
                    $query_args['date_query']['after'] = $date . ' 00:00:00';
                elseif ($filter->condition == 'LT')
                    $query_args['date_query']['before'] = $date . ' 00:00:00';
                elseif ($filter->condition == 'LE')
                    $query_args['date_query']['before'] = $date . ' 23:59:59';
                elseif ($filter->condition == 'EQUAL') {
                    $query_args['date_query']['after'] = $date . ' 00:00:00';
                    $query_args['date_query']['before'] = $date . ' 23:59:59';
                }
                // ignore EMPTY and NOTEMPTY
            } elseif ($filter->field_type == 'date') {
                $query_args = $this->apply_query_filter_date($query_args, $filter);
            } elseif (in_array($filter->field_type, array('number', 'starrating', 'quantity', 'shipping', 'total'))) {
                $query_args = $this->apply_query_filter_numeric($query_args, $filter);
            } else {
                $query_args = $this->apply_query_filter_general($query_args, $filter);
            }
        }

        return $query_args;
    }

    private function apply_query_filter_date($query_args, $filter)
    {
        global $wpdb;
        $date = $filter->value;
        $meta_key = '_field_' . $filter->field_id;

        //convert NinjaForm date format string to mysql date format string
        $dateformat = $filter->dateformat;
        $dateformat = str_replace(array('DD', 'MM', 'YYYY', 'dddd', 'MMMM', 'D'), array('%d', '%m', '%Y', '%W', '%M', '%e'), $dateformat);

        if (in_array($filter->condition, array('GT', 'GE', 'LT', 'LE', 'EQUAL', 'NE'))) {
            if ($filter->condition == 'GT')
                $condition = '>';
            elseif ($filter->condition == 'GE')
                $condition = '>=';
            elseif ($filter->condition == 'LT')
                $condition = '<';
            elseif ($filter->condition == 'LE')
                $condition = '<=';
            elseif ($filter->condition == 'EQUAL')
            $condition = '=';
            elseif ($filter->condition == 'NE')
                $condition = '<>';

            $where_filter = $wpdb->prepare(
                "   
                        AND post_id IN(
                            SELECT post_id
                            FROM {$wpdb->postmeta}
                            WHERE 
                                {$wpdb->postmeta}.meta_key = %s
                                AND STR_TO_DATE({$wpdb->postmeta}.meta_value, %s) $condition %s
                        )
                        ",
                $meta_key,
                $dateformat,
                $filter->value
            );
            add_filter('posts_where', function ($where) use (&$where_filter) {
                return $where . $where_filter;
            });
        } elseif ($filter->condition == 'EMPTY') {
            // empty could also mean "not existing" when a new field was added to a form after a submission
            $where_filter = $wpdb->prepare(
                "   
                        AND 
                            (
                                post_id IN(
                                    SELECT post_id
                                    FROM {$wpdb->postmeta}
                                    WHERE 
                                        {$wpdb->postmeta}.meta_key = %s
                                        AND {$wpdb->postmeta}.meta_value = ''
                                )
                            OR 
                            post_id NOT IN(
                                    SELECT post_id
                                    FROM {$wpdb->postmeta}
                                    WHERE 
                                        {$wpdb->postmeta}.meta_key = %s
                                )
                        )
                        ",
                $meta_key,
                $meta_key
            );
            add_filter('posts_where', function ($where) use (&$where_filter) {
                return $where . $where_filter;
            });
        } elseif ($filter->condition == 'NOTEMPTY') {
            $where_filter = $wpdb->prepare(
                "   
                        AND post_id IN(
                            SELECT post_id
                            FROM {$wpdb->postmeta}
                            WHERE 
                                {$wpdb->postmeta}.meta_key = %s
                                AND {$wpdb->postmeta}.meta_value <> ''
                        )
                        ",
                $meta_key
            );
            add_filter('posts_where', function ($where) use (&$where_filter) {
                return $where . $where_filter;
            });
        }

        return $query_args;
    }

    /**
     * Add numerical filter to query args
     *
     * @param array $query_args
     * @param object $filter
     * @return array
     */
    private function apply_query_filter_numeric($query_args, $filter)
    {
        global $wpdb;
        $value = $filter->value;
        $meta_key = '_field_' . $filter->field_id;

        if ($filter->condition == 'EMPTY') {
            // empty could also mean "not existing" when a new field was added to a form after a submission
            $where_filter = $wpdb->prepare(
                "   
                    AND 
                        (
                            post_id IN(
                                SELECT post_id
                                FROM {$wpdb->postmeta}
                                WHERE 
                                    {$wpdb->postmeta}.meta_key = %s
                                    AND {$wpdb->postmeta}.meta_value = ''
                            )
                        OR 
                        post_id NOT IN(
                                SELECT post_id
                                FROM {$wpdb->postmeta}
                                WHERE 
                                    {$wpdb->postmeta}.meta_key = %s
                            )
                    )
                    ",
                $meta_key,
                $meta_key
            );
            add_filter('posts_where', function ($where) use (&$where_filter) {
                return $where . $where_filter;
            });
        } else {
            if ($filter->condition == 'GT')
                $condition = '>';
            elseif ($filter->condition == 'GE')
                $condition = '>=';
            elseif ($filter->condition == 'LT')
                $condition = '<';
            elseif ($filter->condition == 'LE')
                $condition = '<=';
            elseif ($filter->condition == 'EQUAL')
                $condition = '=';
            elseif ($filter->condition == 'NE')
                $condition = '<>';
            elseif ($filter->condition == 'NOTEMPTY') {
                $condition = '<>';
                $value = '';
            }

            $where_filter = $wpdb->prepare(
                "   
                    AND post_id IN(
                        SELECT post_id
                        FROM {$wpdb->postmeta}
                        WHERE 
                            {$wpdb->postmeta}.meta_key = %s
                            AND {$wpdb->postmeta}.meta_value $condition " . ($value == '' ? '%s' : '%d') . "
                    )
                    ",
                $meta_key,
                $value
            );
            add_filter('posts_where', function ($where) use (&$where_filter) {
                return $where . $where_filter;
            });
        }

        return $query_args;
    }


    /**
     * Add general filter to query args
     *
     * @param array $query_args
     * @param object $filter
     * @return array
     */
    private function apply_query_filter_general($query_args, $filter)
    {
        global $wpdb;
        $value = $filter->value;
        if (!property_exists($filter, 'field_id'))
            return $query_args;

        $meta_key = '_field_' . $filter->field_id;

        if ($filter->condition == 'EMPTY') {
            // empty could also mean "not existing" when a new field was added to a form after a submission
            $where_filter = $wpdb->prepare(
                "   
                    AND 
                        (
                            post_id IN(
                                SELECT post_id
                                FROM {$wpdb->postmeta}
                                WHERE 
                                    {$wpdb->postmeta}.meta_key = %s
                                    AND {$wpdb->postmeta}.meta_value = ''
                            )
                        OR 
                        post_id NOT IN(
                                SELECT post_id
                                FROM {$wpdb->postmeta}
                                WHERE 
                                    {$wpdb->postmeta}.meta_key = %s
                            )
                    )
                    ",
                $meta_key,
                $meta_key
            );
            add_filter('posts_where', function ($where) use (&$where_filter) {
                return $where . $where_filter;
            });
        } else {
            if ($filter->condition == 'GT')
                $condition = '>';
            elseif ($filter->condition == 'GE')
                $condition = '>=';
            elseif ($filter->condition == 'LT')
                $condition = '<';
            elseif ($filter->condition == 'LE')
                $condition = '<=';
            elseif ($filter->condition == 'EQUAL')
                $condition = '=';
            elseif ($filter->condition == 'NE')
                $condition = '<>';
            elseif ($filter->condition == 'NOTEMPTY') {
                $condition = '<>';
                $value = '';
            } elseif ($filter->condition == 'CONTAINS') {
                $condition = 'LIKE';
                $value = '%' . $value . '%';
            } elseif ($filter->condition == 'LIKE') {
                $condition = 'LIKE';
                $value = str_replace('*', '%', $value);
            }


            $where_filter = $wpdb->prepare(
                "   
                    AND post_id IN(
                        SELECT post_id
                        FROM {$wpdb->postmeta}
                        WHERE 
                            {$wpdb->postmeta}.meta_key = %s
                            AND {$wpdb->postmeta}.meta_value $condition %s
                    )
                    ",
                $meta_key,
                $value
            );
            add_filter('posts_where', function ($where) use (&$where_filter) {
                return $where . $where_filter;
            });
        }

        return $query_args;
    }
}
