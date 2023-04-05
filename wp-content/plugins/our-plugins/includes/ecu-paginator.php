<?php
namespace OUR_PLUGINS;

/**
 * PHP Pagination Class
 *
 * Will let you paginate sql / api queries and display common pagination ui elements on a page.
 * See the connect ecu-plugin ( SQL ) / acalog prefix ecu-plugin ( API ) for an example of how to use it.
 *
 * This pager assumes ajax will be used to refresh the content so you will have to write a js function to do this and provide the
 * function name to the pager.  You can also provide an array of data to pass into the function.  In the example below I am passing a
 * dynamic id for the content div to be refreshed.  Don't forget to register the ajax function with wordpress.
 *
 * 	$pager = new Ecu_Pager($total);
 *	$pager->ajax_function = 'refresh_acalog_prefix';
 *	$pager->ajax_function_data = array(
 *		'div_id' => "'$catalog_id$prefix'" // Note that you have to include qoutes for javascript if string data
 *	);
 */
class Ecu_Pager {
	public $ajax_function = '';
	public $ajax_function_data = '';
	protected $current_page;
	protected $number_of_pages;
	protected $number_of_items;
	protected $page_size_interval = 25;
	protected $current_page_size;
	protected $page_size_select_options;
	protected $navigation_pages_shown = 7;
	protected $navigation_pages;
	protected $limit_end;
	protected $limit_start;

	// The number of navigation pages shown should always be odd.
	public function __construct($total_items=0,$number_of_navigation_page_shown=7, $size_interval = 25) {

		$this->number_of_items = (int) $total_items;

		$this->page_size_interval = (int) $size_interval;

		$this->number_of_pages = ceil($this->number_of_items/$this->page_size_interval);

		$this->navigation_pages_shown = (int) $number_of_navigation_page_shown;

		if(isset($_POST["ecu_pager_current"])) {
			$this->current_page = (int) $_POST["ecu_pager_current"];
			if($this->current_page > $this->number_of_pages) {
				$this->current_page = 1;
			}
		} else {
			$this->current_page =  1; // must be numeric > 0
		}

		$this->current_page_size = (isset($_POST['ecu_pager_page_size'])) ? (int) $_POST['ecu_pager_page_size'] : $this->page_size_interval;

		if($this->number_of_pages > $this->navigation_pages_shown) {
			$navigation_page_start = $this->current_page - floor($this->navigation_pages_shown/2);
			$navigation_page_end = $this->current_page + floor($this->navigation_pages_shown/2);
			if($navigation_page_start <= 0) {
				$navigation_page_end += abs($navigation_page_start)+1;
				$navigation_page_start = 1;
			}
			if($navigation_page_end > $this->number_of_pages) {
				$navigation_page_start -= $navigation_page_end-$this->number_of_pages;
				$navigation_page_end = $this->number_of_pages;
			}
		} else {
			$navigation_page_start = 1;
			$navigation_page_end = $this->number_of_pages;
		}
		$this->navigation_pages = range($navigation_page_start,$navigation_page_end);

		$this->page_size_select_options = range($this->page_size_interval, ($this->page_size_interval*4), $this->page_size_interval);

		$this->limit_start = ($this->current_page <= 0) ? 0:($this->current_page-1) * $this->current_page_size;
		if($this->current_page <= 0) $this->current_page_size = 0;
		$this->limit_end = (int) $this->current_page_size;
	}

	public function get_limit_start() {
		return $this->limit_start;
	}

	public function get_limit_end() {
		return $this->limit_end;
	}

	public function get_current_page() {
		return $this->current_page;
	}

	public function page_summary() {
		$page_total = $this->limit_start + $this->limit_end;
		if($page_total > $this->number_of_items) {
			$page_total = $this->number_of_items;
		}

		return 'Displaying ' . ($this->limit_start + 1) .' - ' . ($page_total). ' of ' . $this->number_of_items;
	}

	// Has the current page passed to the ajax function.
	public function page_size_control() {
		$str = '
		<form method="post">
		<div class="form-group">
		<label for="ecu_pager_page_size">Page Size</label>
		<select onchange="' . $this->ajax_function . '(' . $this->current_page . ', ' . $this->current_page_size;

		if(!empty($this->ajax_function_data)) {
			if(is_array($this->ajax_function_data)) {
				$this->ajax_function_data = implode(', ', $this->ajax_function_data);
			}
			$str .= ', ' . $this->ajax_function_data;
		}

		$str .= ');" class="form-control" name="ecu_pager_page_size">';

		foreach($this->page_size_select_options as $size) {
			$str .= '<option value="' . $size . '" ';
			if($this->current_page_size == $size) {
				$str .= 'selected';
			}
			$str .= '>' . $size . '</option>';
		}

		$str .= '
		</select>
		</div>
		</form>';
		return $str;
	}

	public function page_count() {
		return 'Page: ' . $this->current_page . ' of ' . $this->number_of_pages;
	}

	// The ajax function will have the selected page and the page size passed to it on click.
	public function page_navigation_control() {

		if(is_array($this->ajax_function_data)) {
			$this->ajax_function_data = implode(', ', $this->ajax_function_data);
		}

		$str = '
			<nav aria-label="Pager navigation">
				<div class="pagination-wrap">
				 <ul class="pagination">';

		if($this->number_of_pages > $this->navigation_pages_shown) {
			if($this->current_page > 1 && $this->number_of_items >= $this->page_size_interval) {
				$str .= '<li><a style="cursor: pointer;" onClick="' . esc_attr($this->ajax_function) . '(' . ($this->current_page-1) . ', ' . $this->current_page_size ;

				if(!empty($this->ajax_function_data)) {
					$str .= ', ' . esc_attr($this->ajax_function_data);
				}

				$str .= ');" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
			} else {
				$str .= '<li class="disabled"><a href="" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
			}
		}

		foreach($this->navigation_pages as $page) {
			if ($page == $this->current_page) {
				$str .= '<li class="active"><a aria-label="On ' . $page . ' of ' . $this->number_of_pages .'" href="">' . $page . '</a></li>';
			} else {
				$str .= '<li><a  aria-label="Go to page ' . $page . ' of ' . $this->number_of_pages .'" style="cursor: pointer;" onClick="' . esc_attr($this->ajax_function) . '(' . $page . ', ' . $this->current_page_size;

				if(!empty($this->ajax_function_data)) {
					$str .= ', ' . esc_attr($this->ajax_function_data);
				}

				$str .= ');">' . $page . '</a></li>';
			}
		}

		if($this->number_of_pages > $this->navigation_pages_shown) {
			if (($this->current_page < $this->number_of_pages && $this->number_of_items >= $this->page_size_interval) && $this->current_page > 0) {
				$str .= '<li><a  style="cursor: pointer;" onClick="' . esc_attr($this->ajax_function) . '(' . ($this->current_page+1) . ', ' . $this->current_page_size;

				if(!empty($this->ajax_function_data)) {
					$str .= ', ' . esc_attr($this->ajax_function_data);
				}

				$str .= ');" aria-label="Next"><span aria-hidden="true">&raquo;</span></a><li>';
			} else {
				$str .= '<li class="disabled"><a aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
			}
		}
		$str .= '</ul></div></nav>';

		return $str;
	}
}
