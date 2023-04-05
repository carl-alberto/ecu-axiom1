<?php
if (!defined('ABSPATH')) {
    exit;
}

return [
    'pdf_submissions' => [
        /*
        * Document Title Toggle
        */
        'use_document_title' => [
            'name' => 'use_document_title',
            'type' => 'toggle',
            'group' => 'primary',
            'label' => __('Custom Document Title?', 'ninja-forms'),
            'width' => 'full',
            'use_merge_tags' => true,
        ],

        /*
        * Document Title
        */
        'document_title' => [
            'name' => 'document_title',
            'type' => 'textbox',
            'group' => 'primary',
            'label' => __('Document Title', 'ninja-forms'),
            'placeholder' => __('Document Title', 'ninja-forms'),
            'width' => 'full',
            'deps' => [
                'use_document_title' => 1
            ],
            'use_merge_tags' => true,
        ],

        /*
        * Document Body Toggle
        */
        'use_document_body' => [
            'name' => 'use_document_body',
            'type' => 'toggle',
            'group' => 'primary',
            'label' => __('Custom Document Body?', 'ninja-forms'),
            'width' => 'full',
            'use_merge_tags' => false,
        ],

        /*
        * Document Body
        */
        'document_body' => [
            'name' => 'document_body',
            'type' => 'rte',
            'group' => 'primary',
            'label' => __('Document Body', 'ninja-forms'),
            'placeholder' => __('Document Body', 'ninja-forms'),
            'width' => 'full',
            'deps' => [
                'use_document_body' => 1
            ],
            'use_merge_tags' => true,
        ],


        'header_settings' => [
            'name' => 'header_settings',
            'type'                  => 'fieldset',
            'label'                 => __('Document Header', 'ninja-forms'),
            'width' => 'full',
            'group' => 'primary',
            'settings' => [
                [
                    'name' => 'toggle_header_settings',
                    'type' => 'toggle',
                    'group' => 'primary',
                    'label' => __('Header Settings', 'ninja-forms'),
                    'width' => 'full',
                ],
                [
                    'name' => 'header_position',
                    'type' => 'select',
                        'options' => [
                            ['label' => __('Left', 'ninja-forms'), 'value' => 'left'],
                            ['label' => __('Center', 'ninja-forms'), 'value' => 'center'],
                            ['label' => __('Right', 'ninja-forms'), 'value' => 'right'],
                        ],
                    'group' => 'primary',
                    'label' => __('Header Position', 'ninja-forms'),
                    'value' => 'left',
                    'deps' => [
                        'toggle_header_settings' => 1
                    ]
                ],
                [
                    'name' => 'company_name',
                    'type' => 'textbox',
                    'group' => 'primary',
                    'width' => 'full',
                    'label' => __('Company Name', 'ninja-forms'),
                    'placeholder' => __('Acme, Inc.', 'ninja-forms'),
                    'use_merge_tags' => true,
                    'deps' => [
                        'toggle_header_settings' => 1
                    ]
                ],
                [
                    'name' => 'company_logo',
                    'type' => 'media',
                    'group' => 'primary',
                    'width' => 'full',
                    'label' => __('Company Logo', 'ninja-forms'),
                    'deps' => [
                        'toggle_header_settings' => 1
                    ]
                ],
                [
                    'name' => 'header_address_1',
                    'type' => 'textbox',
                    'group' => 'primary',
                    'width' => 'full',
                    'label' => __('Address', 'ninja-forms'),
                    'placeholder' => __('123 Test St.', 'ninja-forms'),
                    'use_merge_tags' => true,
                    'deps' => [
                        'toggle_header_settings' => 1
                    ]
                ],
                [
                    'name' => 'header_address_2',
                    'type' => 'textbox',
                    'group' => 'primary',
                    'width' => 'full',
                    'label' => __('Suite/P.O. Box', 'ninja-forms'),
                    'placeholder' => __('Suite 100', 'ninja-forms'),
                    'use_merge_tags' => true,
                    'deps' => [
                        'toggle_header_settings' => 1
                    ]
                ],
                [
                    'name' => 'header_city_state_province',
                    'type' => 'textbox',
                    'group' => 'primary',
                    'width' => 'full',
                    'label' => __('City/State/Province', 'ninja-forms'),
                    'use_merge_tags' => true,
                    'deps' => [
                        'toggle_header_settings' => 1
                    ]
                ],
                [
                    'name' => 'header_phone',
                    'type' => 'textbox',
                    'group' => 'primary',
                    'width' => 'full',
                    'label' => __('Phone', 'ninja-forms'),
                    'use_merge_tags' => true,
                    'deps' => [
                        'toggle_header_settings' => 1
                    ]
                ],
                [
                    'name' => 'header_email',
                    'type' => 'textbox',
                    'group' => 'primary',
                    'width' => 'full',
                    'label' => __('Email', 'ninja-forms'),
                    'use_merge_tags' => true,
                    'deps' => [
                        'toggle_header_settings' => 1
                    ]
                ],
                [
                    'name' => 'header_date',
                    'type' => 'textbox',
                    'group' => 'primary',
                    'width' => 'full',
                    'label' => __('Date', 'ninja-forms'),
                    'use_merge_tags' => true,
                    'deps' => [
                        'toggle_header_settings' => 1
                    ]
                ],
            ]
        ],
        'footer_settings' => [
            'name' => 'footer_settings',
            'type'                  => 'fieldset',
            'label'                 => __('Document Footer', 'ninja-forms'),
            'width' => 'full',
            'group' => 'primary',
            'settings' => [
                [
                    'name' => 'toggle_footer_settings',
                    'type' => 'toggle',
                    'group' => 'primary',
                    'label' => __('Footer Settings', 'ninja-forms'),
                    'width' => 'full',
                ],
                [
                    'name' => 'footer_position',
                    'type' => 'select',
                        'options' => [
                            ['label' => __('Left', 'ninja-forms'), 'value' => 'left'],
                            ['label' => __('Center', 'ninja-forms'), 'value' => 'center'],
                            ['label' => __('Right', 'ninja-forms'), 'value' => 'right'],
                        ],
                    'group' => 'primary',
                    'label' => __('Footer Position', 'ninja-forms'),
                    'value' => 'left',
                    'deps' => [
                        'toggle_footer_settings' => 1
                    ]
                ],
                [
                    'name' => 'pagination',
                    'type' => 'toggle',
                    'group' => 'primary',
                    'width' => 'full',
                    'label' => __('Pagination', 'ninja-forms'),
                    'deps' => [
                        'toggle_footer_settings' => 1
                    ]
                ],
                [
                    'name' => 'additional_info',
                    'type' => 'textarea',
                    'group' => 'primary',
                    'width' => 'full',
                    'label' => __('Additional Info.', 'ninja-forms'),
                    'use_merge_tags' => true,
                    'deps' => [
                        'toggle_footer_settings' => 1
                    ]
                ],
            ],
        ],
        /*
        * Document Filename Toggle
        */
        'use_document_filename' => [
            'name' => 'use_document_filename',
            'type' => 'toggle',
            'group' => 'primary',
            'label' => __('Custom Document Filename?', 'ninja-forms'),
            'width' => 'full',
            'use_merge_tags' => true,
        ],

        /*
        * Document Filename
        */
        'document_filename' => [
            'name' => 'document_filename',
            'type' => 'textbox',
            'group' => 'primary',
            'label' => __('Document Filename', 'ninja-forms'),
            'placeholder' => __('Document Filename', 'ninja-forms'),
            'width' => 'full',
            'deps' => [
                'use_document_filename' => 1
            ],
            'use_merge_tags' => true,
        ],
    ],
];
