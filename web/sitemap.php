<!doctype html>
<html lang=en>
<head>
<meta charset=utf-8>
<title>Site Map for Site Improve</title>
</head>
<body>
<?php

try {
	$dbh = new PDO ('mysql:host=' .  getenv('WORDPRESS_DB_HOST') . ';dbname=' .  getenv('WORDPRESS_DB_NAME') . ';', getenv('WORDPRESS_DB_USER'), getenv('WORDPRESS_DB_PASSWORD'));

	// Throw exceptions for any errors
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$select = $dbh->prepare("
		SELECT `domain`, `path`
		FROM " . getenv('WORDPRESS_TABLE_PREFIX') . "site
		ORDER BY `domain`, `path`;
	");

	if($select->execute()) {
		$row = $select->fetch();
		$url = 'https://' . $row['domain'] . $row['path'];
		echo '<h1><a href="' . $url . '">' . $url. '</a></h1>';
	}

	$select = $dbh->prepare("
		SELECT `domain`, `path`
		FROM " . getenv('WORDPRESS_TABLE_PREFIX') . "blogs
		ORDER BY `domain`, `path`;
	");

	if($select->execute()) {
		$count = $select->rowCount();
		echo '<h2>Number of Sites: ' . $count . '</h2>';
		
		while($row = $select->fetch()) {
			$url = 'https://' . $row['domain'] . $row['path'];
			echo '<a href="' . $url . '">' . $url. '</a><br />';
		}
	}

} catch(Exception $e) {
	echo 'Caught exception: ' .  $e->getMessage();
}
