<?php

namespace OUR\FINDAPROGRAM;

/*
Plugin Name: Our Find A Program
Description: Shortcode to display a table of ECU Graduate School Programs. Data is managed in ECU Tools. [ecu_find_a_program /] and [ecu_find_a_program no_exams=1 /]
Version: 1.0.1
Author: ECU ITCS Web Services
*/

if (!empty($_GET['id'])) {
    // render a single program
    add_filter('the_content', 'OUR\FINDAPROGRAM\render_program', 1);
    add_action('template_redirect', 'OUR\FINDAPROGRAM\single_template',10,0);
} else {
    // define shortcode
    add_shortcode('ecu_find_a_program', 'OUR\FINDAPROGRAM\render_table');
}
// load specific scripts and styles
add_action( 'wp_enqueue_scripts', 'OUR\FINDAPROGRAM\load_plugin_assets',10,0);

function load_plugin_assets() {
    global $post;
    if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ecu_find_a_program') ) {
        // load specific scripts and styles
        adding_scripts();
    }
    if (empty($_GET['id'])) {
        // load specific scripts and styles
        adding_scripts();
    }
    adding_styles();
}

function adding_scripts()
{
    wp_register_script('our_findaprogram_js', plugins_url( 'assets/js/findaprogram.js', __FILE__ ) , array('jquery'), '1.1', true);

    wp_register_script('our_findaprogram_datatables_js', plugins_url( 'assets/js/jquery.dataTables.min.js', __FILE__ ), array('jquery'), '1.1', true);
    wp_register_script('our_findaprogram_datatables_responsive_js', plugins_url( 'assets/js/dataTables.responsive.min.js', __FILE__ ), array('jquery'), '1.1', true);
    wp_register_script('our_findaprogram_bootstrap4', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js', array('jquery'));
    wp_enqueue_script('our_findaprogram_bootstrap4');
    wp_enqueue_script('our_findaprogram_datatables_js');
    wp_enqueue_script('our_findaprogram_datatables_responsive_js');
}
function adding_styles()
{
    wp_register_style('our_findaprogram_css', plugins_url( 'assets/css/findaprogram.css', __FILE__ )  );
    wp_enqueue_style('our_findaprogram_css');
    wp_register_style('our_findaprogram_spinner_css', plugins_url( 'assets/css/spinner.css', __FILE__) );
    wp_enqueue_style('our_findaprogram_spinner_css');

    wp_register_style('our_findaprogram_datatables_css',plugins_url( 'assets/css/jquery.dataTables.min.css', __FILE__ ) );
    wp_register_style('our_findaprogram_datatables_bootstrap_css', plugins_url( 'assets/css/dataTables.bootstrap.min.css', __FILE__ ) );
    wp_register_style('our_findaprogram_datatables_custom_css', plugins_url( 'assets/css/ecu_datatables_custom.css', __FILE__ ));
    wp_enqueue_style('our_findaprogram_datatables_css');
    wp_enqueue_style('our_findaprogram_datatables_bootstrap_css');
    wp_enqueue_style('our_findaprogram_datatables_custom_css');
}

// render output of a single program
function render_program($content)
{
    // Check if we're inside the main loop in a single Post.
    if ( !is_singular() && !in_the_loop() && !is_main_query() ) {
        return $content;
    }

    $id = intval($_GET['id']);

    // look for potential to set options to ensure no paginated results, re: wpdb default functionalitys
    // $query = new \wpdb(TOOLS_DB_USER, TOOLS_DB_PASSWORD, TOOLS_DB_NAME, TOOLS_DB_HOST);

    if ($id) {

        // $program = $query->get_results('
        //     SELECT
        //         find_program_program.*,
        //         find_program_program.cip_code,
        //         find_program_delivery_methods.delivery_method as delivery_method,
        //         find_program_degree_lvl.degree_lvl as degree_lvl,
        //         find_program_college.college as college
        //     FROM find_program_program
        //     LEFT JOIN find_program_delivery_methods ON find_program_program.delivery_method_id = find_program_delivery_methods.id
        //     LEFT JOIN find_program_degree_lvl ON find_program_program.degree_lvl_id = find_program_degree_lvl.id
        //     LEFT JOIN find_program_college ON find_program_program.college_id = find_program_college.id
        //     WHERE ' . $conditional . '
        //     LIMIT 1');

        $program = \Database\Tools::query("
            SELECT
                find_program_program.*,
                find_program_program.cip_code,
                find_program_delivery_methods.delivery_method as delivery_method,
                find_program_degree_lvl.degree_lvl as degree_lvl,
                find_program_college.college as college
            FROM find_program_program
            LEFT JOIN find_program_delivery_methods ON find_program_program.delivery_method_id = find_program_delivery_methods.id
            LEFT JOIN find_program_degree_lvl ON find_program_program.degree_lvl_id = find_program_degree_lvl.id
            LEFT JOIN find_program_college ON find_program_program.college_id = find_program_college.id
            WHERE find_program_program.id = ?
            LIMIT 1
        ", array($id));

        $program = $program[0];
    }

    global $wp;
    if(!$program) {
        return '<a href="' . home_url( $wp->request ) . '" class="btn btn-default my-3">Back to Find Your Program Search</a>';
    } else {
       return render_program_template($program);
    }
}

// Yoast caused the header to be placed below the program content.   Attempting to use ob_start did not work to use the template file.
// I believe that it was an issue with Yoast and Redis as I could not replicate on local.
// Had to move it into a fucntion so I could use it.  I am leaving the template file in case it can be used when this tool
// is moved to a custom post type.
function render_program_template($program) {
    global $wp;
     $content ='
        <div class="ecu_findaprogram_wrapper container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <h1 class="py-2">' . esc_html($program->program) . '</h1>';

        if(!empty($program->college)) {
            $content .= '<h3>' . esc_html($program->college) .'</h3>';
        }

        $content .= '
        </div>
        <div class="col-md-4 text-md-right">
            <a href="' . home_url( $wp->request ) .'" class="btn btn-default my-3">Back to Find Your Program Search</a>
        </div>
        </div>';

        $content .= '
        <div class="card bg-default p-4 my-4">
        <div class="row">
            <div class="col-sm-12 col-md-6">';

        if($program->degree) {
            $content .= '
            <h4>Program Information</h4>
            <div class="row">
                <div class="col-md-5 text-md-right py-1">
                    <strong>Degrees or Certificates Offered:</strong>
                </div>
                <div class="col-md-7">
                    <h4>' . esc_html($program->degree) . '</h4>
                </div>
            </div>';
        }

        if($program->delivery_method) {
            $content .= '
            <div class="row">
                <div class="col-md-5 text-md-right py-1">
                    <strong>Delivery Method:</strong>
                </div>
                <div class="col-md-7 my-1">
                    <h4>' . esc_html($program->delivery_method) . '</h4>
                </div>
            </div>';
        }      

        if(!empty($program->prog_website)) {
            $content .= '
            <div class="row">
                <div class="col-md-5 text-md-right">
                    <strong>Program Website:</strong>
                </div>
                <div class="col-md-7 my-1">
                    <a class="btn btn-primary" href="' . esc_url(trim($program->prog_website)). '" target="_blank">Program Website</a>
                </div>
            </div>';
        }

        if(!empty($program->apply_url)) {
            $content .= '
            <div class="row">
                <div class="col-md-5 text-md-right">
                </div>
                <div class="col-md-7 my-2 px-3">
                    <a id="ecu-fp-apply" class="btn btn-ecu-gold" href="' . esc_url(trim($program->apply_url)) . '" target="_blank">Apply Now</a>
                </div>
            </div>';
        }

        if(!$program->require_entrance_exam) {
            $content .= '
            <div class="row">
                <div class="col-md-5 text-md-right">
                </div>
                <div class="col-md-7 my-2 px-3">
                    <span id="ecu-fp-gre" class="badge badge-warning">GRE (or other entrance exam) Not Required!</span>
                </div>
            </div>';
        }
       
        if(!empty($program->gainful_employment)) {
            $content .= '
            <div class="row">
                <div class="col-md-5 text-md-right">
                    <strong>
                        Gainful Employment:
                        <!-- TODO integrate tooltip -->
                        <a href="" data-toggle="tooltip" id="tooltip1" data-placement="top" title="" data-original-title="The U.S. Department of Education requires colleges and universities to disclose certain information for any financial aid eligible program that, \'prepares students for gainful employment in a recognized occupation.\' This information includes typical program costs; financing options; the median debt incurred by program graduates; on-time completion rates; job placement rates; and possible occupations for which program graduates are prepared."><i class="fa fa-sign"></i></a>
                    </strong>
                </div>
                <div class="col-md-7 my-1">
                    <a class="btn btn-primary" href="' . esc_url($program->gainful_employment). '" target="_blank">Gainful Employment Information</a>
                </div>
            </div>';
        }
        $content .= '
    </div>
    <div class="col-sm-12 col-md-6">';

    
    if($program->director) {
        $content .= '
        <h4>Graduate Program Contact</h4>
        <div class="row">
            <div class="col-md-5 text-md-right">
                <strong>Name:</strong>
            </div>
            <div class="col-md-7">
                ' . esc_html($program->director) . '
            </div>
        </div>';
    }

    if($program->email) {
        $content .= '
        <div class="row">
            <div class="col-md-5 text-md-right py-1">
                <strong>Email:</strong>
            </div>
            <div class="col-md-7 my-1">
                <a class="btn btn-primary" href="mailto:' . esc_attr($program->email). '">' . esc_attr($program->email) . '</a>
            </div>
        </div>';
        
    }

    if($program->dept) {
        $content .= '
        <div class="row">
            <div class="col-md-5 text-md-right">
                <strong>Mailing Address:</strong>
            </div>
            <div class="col-md-7">
                ' . esc_html($program->dept) .'
            </div>
        </div>';
    }

    if($program->phone) {
        $content .= '
        <div class="row">
            <div class="col-md-5 text-md-right">
                <strong>Phone:</strong>
            </div>
            <div class="col-md-7"><a href="tel:+1' . esc_attr(preg_replace('/\D+/', '', $program->phone)) . '">' . esc_html($program->phone) . '</a></div>            
        </div>';
    }

    if($program->fax) {
        $content .= '
        <div class="row">
            <div class="col-md-5 text-md-right">
                <strong>Fax:</strong>
            </div>
            <div class="col-md-7">
                ' . esc_html($program->fax) .'
            </div>
        </div>';
    }
     
    $content .= '
    </div>
    </div>
    </div>';

    $content .= '
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-primary">
                <h4>Application Deadlines</h4>
                <div class="table-responsive bg-white">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Fall Semester</th>
                            <th>Spring Semester</th>
                            <th>1st Summer Session</th>
                            <th>2nd Summer Session</th>
                            <th>11-week Summer Session</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-left">Priority</td>
                            <td>';
                            if(!empty($program->fall_priority_date)) $content .= esc_html($program->fall_priority_date);
                            $content .= '</td>
                            <td>';
                            if(!empty($program->spring_priority_date)) $content .= esc_html($program->spring_priority_date);
                            $content .= '</td>
                            <td>';
                            if(!empty($program->first_sem_priority_date)) $content .= esc_html($program->first_sem_priority_date);
                            $content .= '</td>
                            <td>';
                            if(!empty($program->second_sem_priority_date)) $content .= esc_html($program->second_sem_priority_date);
                            $content .= '</td>
                            <td>';
                            if(!empty($program->eleven_week_priority_date)) $content .= esc_html($program->eleven_week_priority_date);
                            $content .= '</td>
                        </tr>
                        <tr>
                            <td class="text-left">Graduate School</td>
                            <td>';
                            if(!empty($program->fall_graduate_date)) $content .= esc_html($program->fall_graduate_date);
                            $content .= '
                            </td>
                            <td>';
                            if(!empty($program->spring_graduate_date)) $content .= esc_html($program->spring_graduate_date);
                            $content .= '
                            </td>
                            <td>';
                            if(!empty($program->first_sem_graduate_date)) $content .= esc_html($program->first_sem_graduate_date);
                            $content .= '
                            </td>
                            <td>';
                            if(!empty($program->second_sem_graduate_date)) $content .= esc_html($program->second_sem_graduate_date);
                            $content .= '
                            </td>
                            <td>';
                            if(!empty($program->eleven_week_graduate_date)) $content .= esc_html($program->eleven_week_graduate_date);
                            $content .= '
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
                <span class="text-white"><em>* Applications not considered for admission in this term</em></span>
            </div>
        </div>
    </div>';

    if($program->deadline_notes) {
    $content .= '
        <div class="card bg-default p-4 my-4">
            <div class="row">
                <div class="col-md-12">
                    <h4>Application/Deadline Notes</h4>
                    ' . $program->deadline_notes . '
                </div>
            </div>
        </div>';
    }

    $content .= '
    <div class="row">
        <div class="col-md-12">
            <h4>Additional Information</h4>
        </div>
    </div>';

    if($program->addition) {
    $content .= '
        <div class="row">
            <div class="col-md-3 text-md-right py-1">
                <strong>Application Materials Needed:</strong>
            </div>
            <div class="col-md-9">' .
                $program->addition . '
            </div>
        </div>';
    }

    if($program->concentration) {
        $content .= '
        <div class="row">
            <div class="col-md-3 text-md-right py-1">
                <strong>Concentrations:</strong>
            </div>
            <div class="col-md-9">
                ' . $program->concentration . '
            </div>
        </div>';
    }

    $content .= '</div>';

    return $content;
}

// use a plugin-provided template to render a single program
function single_template()
{
    require_once(dirname(__FILE__) . '/findaprogram-single.tpl.php');
    exit;
}

/**
 * Shortcode Function.
 *
 * @since 1.0.0
 *
 * @param array $atts  {
 *      Optional. The settings for the shortcode instance.
 *
 *      @type boolean $no_exams         Only return programs with no entrance exam requirement when true.
 *                                      Default False. Accepts boolean.
 * }
 */
function render_table($atts)
{
    wp_enqueue_script('our_findaprogram_js');
    ob_start();

    // $query = new \wpdb(TOOLS_DB_USER, TOOLS_DB_PASSWORD, TOOLS_DB_NAME, TOOLS_DB_HOST);

    // $programs = $query->get_results('
    //     SELECT
    //         find_program_program.id,
    //         find_program_program.college_id,
    //         find_program_program.delivery_method_id,
    //         find_program_program.degree_lvl_id,
    //         find_program_program.program as name,
    //         find_program_delivery_methods.delivery_method as delivery_method,
    //         find_program_degree_lvl.degree_lvl as degree_lvl,
    //         find_program_college.college as college,
    //         find_program_program.is_new as is_new
    //     FROM
    //         find_program_program
    //     LEFT JOIN find_program_delivery_methods ON find_program_program.delivery_method_id = find_program_delivery_methods.id
    //     LEFT JOIN find_program_degree_lvl ON find_program_program.degree_lvl_id = find_program_degree_lvl.id
    //     LEFT JOIN find_program_college ON find_program_program.college_id = find_program_college.id
    //     GROUP BY find_program_program.id
    //     ORDER BY name ASC
    // ');

    // Attributes
	$atts = shortcode_atts(
		array(
            'no_exams' => 0,
		),
		$atts
    );

    if($atts['no_exams']) {
        // no exams only
        $programs = \Database\Tools::query("
            SELECT
                find_program_program.id,
                find_program_program.college_id,
                find_program_program.delivery_method_id,
                find_program_program.degree_lvl_id,
                find_program_program.program as name,
                find_program_delivery_methods.delivery_method as delivery_method,
                find_program_degree_lvl.degree_lvl as degree_lvl,
                find_program_college.college as college,
                find_program_program.is_new as is_new
            FROM
                find_program_program
            LEFT JOIN find_program_delivery_methods ON find_program_program.delivery_method_id = find_program_delivery_methods.id
            LEFT JOIN find_program_degree_lvl ON find_program_program.degree_lvl_id = find_program_degree_lvl.id
            LEFT JOIN find_program_college ON find_program_program.college_id = find_program_college.id
            WHERE find_program_program.require_entrance_exam = 0
            GROUP BY find_program_program.id
            ORDER BY name ASC
        ");
    } else {
        // all programs
        $programs = \Database\Tools::query("
            SELECT
                find_program_program.id,
                find_program_program.college_id,
                find_program_program.delivery_method_id,
                find_program_program.degree_lvl_id,
                find_program_program.program as name,
                find_program_delivery_methods.delivery_method as delivery_method,
                find_program_degree_lvl.degree_lvl as degree_lvl,
                find_program_college.college as college,
                find_program_program.is_new as is_new
            FROM
                find_program_program
            LEFT JOIN find_program_delivery_methods ON find_program_program.delivery_method_id = find_program_delivery_methods.id
            LEFT JOIN find_program_degree_lvl ON find_program_program.degree_lvl_id = find_program_degree_lvl.id
            LEFT JOIN find_program_college ON find_program_program.college_id = find_program_college.id
            GROUP BY find_program_program.id
            ORDER BY name ASC
        ");
    }

    require_once(dirname(__FILE__) . '/findaprogram-table.tpl.php');

    return ob_get_clean();
}