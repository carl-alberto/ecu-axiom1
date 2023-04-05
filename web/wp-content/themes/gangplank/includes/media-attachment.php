<?php
/**
 * @since 1.0.0
 * @author  ITCSWebDev
 */
class Media_Attachment {

	public $url;

	public $post = false;

	protected $size;

	protected $ext;

	/**
     * Returns if size is greater then 15 MB.   The pdf previewer
     * will not work with pdfs bigger then that.  Couldn't find any
     * documentation for the limits imposed by the google doc viewer, but
     * this is learned from experience.
     *
     * @since 1.0.0
     * @access public
     *
     * @return bool True if previewable, false otherwise.
     */
	public function isPdfPreviewable() {
		return ($this->size < 15728640);
	}

	/**
     * Returns if size is greater then 5 MB.   The excel previewer
     * will not work with files bigger then that.
     *
     * @link http://wopi.readthedocs.io/en/latest/faq/file_sizes.html File Size Limit
     * @since 1.0.0
     * @access public
     *
     * @return bool True if previewable, false otherwise.
     */
	public function isExcelPreviewable() {
		return ($this->size < 5242880);
	}



    /**
     * Constructor. Defaults maximum time for transients to 16 mins.
     *
     * @since 1.0.0
     * @access public
     *
     * @global string id The option the url was stored with.
     * @param string $calendar_url Optional. The calendar URL for the API requests.
     */
    public function __construct( $id = NULL ) {

        if( isset( $id ) ) {

        	$this->url = wp_get_attachment_url(  $id );
        	$this->post = get_post( $id );

           	$file = get_attached_file( $id );
			$this->size = filesize( $file );
			$this->ext = pathinfo($file, PATHINFO_EXTENSION);
        }
	}

	/**
	 * Converts the size in bytes to human readable form.
	 *
	 * @since 1.0.0
	 */
	public function get_human_readable_filesize() {
	    if( $this->size >= 1<<30 ) {
	        return number_format($this->size/(1<<30))."GB";
	    }
	    if( $this->size >= 1<<20 ) {
	        return number_format($this->size/(1<<20))."MB";
	    }
	    if( $this->size >= 1<<10 ) {
	        return number_format($this->size/(1<<10))."KB";
	    }
	    return number_format($this->size)." bytes";
	}

	public function get_icon($size = '24x24') {
	    return plugins_url('ecu-plugins/includes/plugins/media-attachments/images/' . $this->ext .'-icon-' . $size . '.png');
	}

	public function get_embed($options) {
	    // Previewable extensions
	    // jpg jpeg png gif pdf doc ppt pptx docx pps ppsx xls xlsx mp3 ogg wma m4a wav mp4 m4v webm ogv wmv flv xlsm
	    // Non Preview Extensions
	    // mov avi mpg 3gp 3g2 midi mid odt key
	    $embed = '';

	    switch ( $this->ext ) {

	        case 'mp4':
	        case 'mv4':
	        case 'webm':
	        case 'ogv':
	        case 'wmv':
	        case 'flv':
	            $embed = '[video ' . $this->ext .'="' . esc_url( $this->url ) . '" width="' . esc_attr( $options['width'] ) . '" height="' . esc_attr( $options['height'] ) . '"]';
	            break;

	        case 'wav':
	        case 'wma':
	        case 'ogg':
	        case 'mp3':
	        case 'm4a':
	            $embed = '[audio ' . $this->ext .'="' . esc_url( $this->url ) . '"]';
	            break;

	        case 'xls':
	        case 'xlsx':
	        case 'xlsm':
	        	if($this->isExcelPreviewable()) {
	        	    $embed = '<iframe title="Preview ' . esc_attr($this->post->post_title) . '" src="https://view.officeapps.live.com/op/embed.aspx?src=' . esc_url( $this->url ) . '" style="width:' . esc_attr( $options['width'] ) . '; height:' . esc_attr( $options['height'] ) . ';"></iframe>';
	            }
	            break;

	        case 'doc':
	        case 'docx':
	        case 'pps':
	        case 'ppsx':
	        case 'pptx':
					case 'potx':
	        case 'ppt':
	            $embed = '<iframe title="Preview ' . esc_attr($this->post->post_title) . '" src="https://view.officeapps.live.com/op/embed.aspx?src=' . esc_url( $this->url ) . '" style="width:' . esc_attr( $options['width'] ) . '; height:' . esc_attr( $options['height'] ) . ';"></iframe>';
	            break;

	        case 'pdf':
	        	if($this->isPdfPreviewable()) {
	            	$embed = '<iframe title="Preview ' . esc_attr($this->post->post_title) . '" src="https://docs.google.com/viewer?url=' . esc_url( $this->url ) . '&embedded=true" style="width:' . esc_attr( $options['width'] ) . '; height:' . esc_attr( $options['height'] ) . ';"></iframe>';
	            }
	            break;

	        case 'gif':
	        case 'png':
	        case 'jpg':
	        case 'jpeg':
	            $embed = '<img src="' . $this->url . '"/>';
	            break;
	    }

	    return $embed;
	}

	public function get_download() {

		$download = '';

		if (strpos($this->post->post_mime_type, 'image') !== false) {

	 		$images = array();
	        $image_sizes = get_intermediate_image_sizes();
	        array_unshift( $image_sizes, 'full' );
	        foreach( $image_sizes as $image_size ) {
	            $image = wp_get_attachment_image_src( get_the_ID(), $image_size );
	            $name = $image_size . ' (' . $image[1] . 'x' . $image[2] . ')';
	            $images[] = '<a href="' . $image[0] . '">' . $name . '</a>';
	        }
	        $download = 'Downloads:  ' . implode( ' | ', $images );

		} else {
	    	$download = '<a href="' . esc_url( $this->url ) . '" class="btn btn-default" download>Download ( ' . $this->get_human_readable_filesize() . ' )</a>';
		}

        return $download;
	}
}
