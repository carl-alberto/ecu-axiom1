<?php

/**
 * Class Ecu_Widget_Field_Multilevel_Select
 */
class Ecu_Widget_Field_Multilevel_Select extends SiteOrigin_Widget_Field_Base {
	/**
	 * The list of options which may be selected.
	 *
	 * @access protected
	 * @var array
	 */
	protected $options;
	/**
	 * If present, this string is included as a disabled (not selectable) value at the top of the list of options. If
	 * there is no default value, it is selected by default. You might even want to leave the label value blank when
	 * you use this.
	 *
	 * @access protected
	 * @var string
	 */
	protected $prompt;
	/**
	 * Determines whether this is a single or multiple select field.
	 *
	 * @access protected
	 * @var bool
	 */
	protected $multiple;
	/**
	 * Determines what field options should be sorted by
	 *
	 * @access protected
	 * @var string
	 */
	protected $sort;

	protected function render_field( $value, $instance ) {
		if (!(is_null($this->sort))){
			usort($this->options, array($this, 'cmp'));
		}

		$values = explode(",", $value);

		echo "<select name='" . esc_attr($this->element_name) . "' id='" . esc_attr($this->element_id) . "' class='siteorigin-widget-input' " . (! empty( $this->multiple ) ? "multiple" : '') . ">";
			if (!(is_null($this->prompt))){
				echo "<option value='' " . ($value=='default' ? "selected='selected'>" : ">") . esc_html($this->prompt) . "</option>";
			}
			foreach ($this->options as $option){
				if (is_null($option->parent_id)){
					//echo "<optgroup label='" . $option->name . "'>";
					echo "<option value='" . $option->id . "'" . (in_array($option->id, $values) ? "selected='selected'" : "") . ">" . $option->name . "</option>";
					foreach ($this->options as $childoption){
						if ($childoption->parent_id === $option->id){
							echo "<option value='" . $childoption->id . "'" . (in_array($childoption->id, $values) ? "selected='selected'" : "") . ">&nbsp;&nbsp;&nbsp;&nbsp;" . $childoption->name . "</option>";
						}
					}
				} elseif ($option->parent_id == 0) {
					echo "<option value='" . $option->id . "'>" . $option->name . "</option>";
				}
			}

		echo "</select>";
	}

	protected function sanitize_field_input( $value, $instance ) {
		return $value;
	}

	protected function cmp($a, $b)
	{
	    $sortField = $this->sort;
    	return strcmp($a->$sortField, $b->$sortField);
	}
}