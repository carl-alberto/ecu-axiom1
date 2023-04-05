<?php
/**
 * PDF Included With Form Submission
 *
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>
<html><head>
	<link type="text/css" href="<?php echo $css_path; ?>" rel="stylesheet" />
</head><body>
	<?php if (strlen($header) > 0) { ?>
		<div><?php echo $header;?></div>
	<?php } ?>
	<h1 class="document_title"><?php echo $title; ?></h1>
	<?php echo $table; ?>
</body></html>
