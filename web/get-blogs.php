<?php

try {
	$dbh = new PDO ('mysql:host=' .  getenv('WORDPRESS_DB_HOST') . ';dbname=' .  getenv('WORDPRESS_DB_NAME') . ';', getenv('WORDPRESS_DB_USER'), getenv('WORDPRESS_DB_PASSWORD'));

	// Throw exceptions for any errors
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$select = $dbh->prepare("
		SELECT `domain`, `path`
		FROM " . getenv('WORDPRESS_TABLE_PREFIX') . "blogs
		WHERE `deleted` = 0 AND `archived` = 0
		ORDER BY `domain`, `path`;
	");
	$url = '';
	if($select->execute()) {
		while($row = $select->fetch()) {
			$url.=  $row['domain'] . $row['path'] . ',';
		}
		echo json_encode($url);
	}
} catch(Exception $e) {
	echo 'Caught exception: ' .  $e->getMessage();
}
