
<!doctype html>
<html lang=en>
<head>
<meta charset=utf-8>
<title>Site Map for Site Improve</title>
</head>
<body>

<?php
// $regex = '#^[1-9a-cA-C]#'; echo '<h1>Sites beginning with 1-C</h1>';
//$regex = '#^[d-hD-H]#'; echo '<h1>Sites beginning with D-H</h1>';
$regex = '#^[i-nI-N]#'; echo '<h1>Sites beginning with I-N</h1>';
//$regex = '#^[o-zO-Z]#'; echo '<h1>Sites beginning with O-Z</h1>';
echo '<hr />';


$dbh = new PDO ('mysql:host=' .  getenv('TOOLS_DB_HOST') . ';dbname=' .  getenv('TOOLS_DB_NAME') . ';', getenv('TOOLS_DB_USER'), getenv('TOOLS_DB_PASSWORD'));
// Throw exceptions for any errors
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$select = $dbh->prepare("
	SELECT `env`
	FROM wp_envs
");

if($select->execute()) {
	while($row = $select->fetch()) {
		$envs[] =  $row['env'];
	}
}
if (isset($envs)) {
	echo "<table>";
	foreach($envs as $env)  {
		if ($response = @file_get_contents('https://' . $env . '/get-blogs.php')) {
			$response = json_decode($response);
			$sites[$env] = explode( ',', $response);
		}
	}
	if (isset($sites)) {
		foreach ($sites as $env => $s) {
			if ($current_sites = preg_grep($regex, $s)) {
				foreach ($current_sites as $site) {
					$sorted[$site] = $env;
				}
			}
		}

		ksort($sorted);

		foreach ($sorted as $site => $env) {
			$url = 'https://' . $site;
			echo '<tr><td>';
			echo $env;
			echo '</td><td>';
			echo '<a href="' . $url . '">' . $url. '</a><br />';
			echo '</td><tr>';
		}

	}   else   {
		echo 'No sites returned.';
		die();
	}
	echo "</table>";
}   else  {
	echo 'No environments to check.';
	die();
}

?>
</body>