<?php
namespace OUR_PLUGINS;

/**
 * Shortcode class for the featured block.
 */
class Ecu_Degrees extends Abstract_Ecu_Shortcode {

	/**
	 * Returns the shortcode
	 *
	 * @return string $shortcode The shortcode.
	 */
	public function get_shortcode() {
		return "ecu_degrees";
	}

	/**
	 * Initialize
	 */
	public function initialize(){

		if ( is_admin() ) {
			add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
		} else {
			add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
		}

		parent::initialize();
	}

	/**
     * Enqueueues the necessary CSS and JS
	 */
	public function admin_enqueue_scripts() {
		add_editor_style( plugins_url('/our-plugins/includes/plugins/degrees/css/style.css') );
	}

	/**
     * Enqueueues the necessary CSS and JS
	 */
	public function wp_enqueue_scripts() {
		wp_register_style( 'ecu-shortcode-degrees', plugins_url('/our-plugins/includes/plugins/degrees/css/style.css') );
	}

	/**
	 * Shortcode Function.
	 *
	 * @link https://codex.wordpress.org/Shortcode_API Shortcode UI
	 *
	 * @param array $atts  {
	 *      Optional. The settings for the shortcode instance.
	 *
	 *      @type string  $id 			The CIP code of the degree
	 *      @type string  $hide_name  	Hide the degree name
	 *      @type string  $hide_info    Hide the degree info (the aboutMajor field in the DB)
	 * }
	 * @param string $content 		The enclosed content (if the shortcode is used in its enclosing form)
	 * @param string $shortcode_tag The shortcode tag, useful for shared callback functions.
	 * @return string The output for the shortcode.
	 */
	public function callback($attrs, $content = '', $shortcode_tag){
		wp_enqueue_style('ecu-shortcode-degrees');
		//Get Values and Set any unset values.
		$attrs = shortcode_atts(array( //creates varaibales from your attrs
				'id' => '',
				'hide_name' => '',
				'hide_info' => '',
		),array_map('rawurldecode',$attrs), $shortcode_tag);

		$degree = $this->get_degree_info($attrs['id']);

		if($attrs['hide_name'] && $attrs['hide_info']) {
			if(is_admin()) {
				return 'Degree Shortcode.  Nothing will be shown.  Try unchecking one of the options.';
			} else {
				return '';
			}
		}
		$str = '<span class="degree-info">';
		if ($attrs['hide_name'] != 'true') {
			$str .= '<span class="degree-info-degreename">' . $degree['degreeName'] . ' - ' . $degree['type'] . '</span>';
			$str .= '<span class="degree-info-degreelink"><a href="http://' . getenv('TOPSITE_ENV') . 'degrees/' . urlencode($degree['type']) . '/' . urlencode($degree['degreeName']) . '">view full degree page</a></span>';
		}
		if ($attrs['hide_info'] != 'true') {
			$str .= '<span class="degree-info-aboutmajor">' . $degree['aboutMajor'] . '</span>';
		}
		$str .= '</span>';

		return do_shortcode($str);
	}

	/**
	 * Registers the UI of the shortcode with shortcake
	 *
	 * @link https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI Registering Shortcode UI
	 */
	function register_with_shortcake(){
		$options = is_admin() ? $this->get_degree_options() : array();

		if (function_exists("shortcode_ui_register_for_shortcode")){
			shortcode_ui_register_for_shortcode(
					$this->get_shortcode(),
					array(
							'label'         => 'Degree Information',
							'listItemImage' => $this->get_font_awesome_html('fa-mortar-board'),
							//'listItemImage' => '',
							'attrs'         => array(
									array(
											'label'  => esc_html__( 'Degree Information', $this->get_shortcode() ),
											'attr'   => 'header',
											'type'   => 'ecu-shortcode-information',
											'description' => 'Displays brief information about a degree'
									),
									array(
											'label'  => esc_html__( 'Degree', $this->get_shortcode() ),
											'attr'      => 'id',
											'type'      => 'select',
											'options'   => $options
									),
									array(
											'label'  => esc_html__( 'Hide Degree Name', $this->get_shortcode() ),
											'attr'      => 'hide_name',
											'type'      => 'checkbox',
									),
									array(
											'label'  => esc_html__( 'Hide Degree Info', $this->get_shortcode() ),
											'attr'      => 'hide_info',
											'type'      => 'checkbox',
									),

							)
					)
					);
		}
	}

	private function get_degree_options() {

		$results = \Database\Tools::query("
			SELECT de.id,dedn.name as degreeName,dedt.type
			FROM homepage_tools.degree_explorer as de
			LEFT JOIN homepage_tools.degree_explorer_degree_types as dedt
				ON de.degreeType = dedt.id
			LEFT JOIN homepage_tools.degree_explorer_names as dedn
				ON de.name_id = dedn.id
			WHERE published = 1
			ORDER BY degreeName, degreeType
		");

		$options = array();

		if(is_array($results)) {
			foreach($results as $degree) {
				$options[] = array(
					'value' => $degree->id,
					'label' => $degree->degreeName . ' - ' . $degree->type,
				);
			}
		}

		return $options;
	}

	private function get_degree_info($id) {

		$result = \Database\Tools::query("
			SELECT dedn.name as degreeName,dedt.type,de.aboutMajor
			FROM homepage_tools.degree_explorer as de
			LEFT JOIN homepage_tools.degree_explorer_degree_types as dedt
				ON de.degreeType = dedt.id
			LEFT JOIN homepage_tools.degree_explorer_names as dedn
				ON de.name_id = dedn.id
			WHERE de.id = ? and published = 1
		", array($id));

		if (count($result) > 0) {
			$options['degreeName'] = $result[0]->degreeName;
			$options['type'] = $result[0]->type;
			$options['aboutMajor'] = $result[0]->aboutMajor;
		}

		return $options;
	}
}
new Ecu_Degrees;
