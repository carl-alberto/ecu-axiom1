<?php function geog_weather( $atts ){
    $a = shortcode_atts( array(
		'station' => 'belk',
	), $atts );
    $station = $a['station'];
    if(($station == 'belk') || ($station == 'wrc')){
        $dbh = new PDO ('mysql:host='.getenv('TOOLS_DB_HOST').';dbname='.getenv('TOOLS_DB_NAME').';', getenv('TOOLS_DB_USER'), getenv('TOOLS_DB_PASSWORD'));
    	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$select = $dbh->prepare("SELECT * FROM homepage_tools.geog_weather WHERE station = '{$station}'");
        if($select->execute()) {
            if($select->rowCount() > 0){
                $row =  $select->fetch();
                ob_start();?>
                <table class="table table-striped table-bordered table-sm">
                    <tbody>
                        <thead>
                            <tr>
                                <th><?php echo date('F jS, Y', strtotime($row['date'])); ?></td>
                                <th><?php echo date('h:i:s A', strtotime($row['date'])); ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Temp / Humidity</td>
                                <td><?php echo $row['temp']; ?> F / <?php echo $row['humidity']; ?>%</td>
                            </tr>
                            <tr>
                                <td>Wind Spd / Dir</td>
                                <td><?php echo $row['windspeed']; ?> mph / <?php echo $row['winddir']; ?></td>
                            </tr>
                            <tr>
                                <td>Pressure</td>
                                <td><?php echo $row['pressure']; ?> in</td>
                            </tr>
                            <tr>
                                <td>Solar Radiation</td>
                                <td><?php echo $row['solarrad']; ?> w/m<sup>2</sup></td>
                            </tr>
                        </tbody>
                    </tbody>
                </table>
                <?php $output = ob_get_contents();
            	ob_end_clean();
            }
        }

    	return $output;
    }
}
add_shortcode( 'weather', 'geog_weather' );
