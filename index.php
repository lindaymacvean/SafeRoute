<?php
require_once "inc/Polyline.php";
include "Cache/cache_functions.php";
include "inc/functions.php";

// Coventry: lat = 52.40681980, long =-1.51970930
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body, #map-canvas {
        margin: 0;
        padding: 0;
        height: 100%;
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
	<script src="ratchet/ratchet.js"></script>
	<link rel="stylesheet" type="text/css" href="ratchet/ratchet.css" />
    <script>
	// Create an object containing LatLng, population.
	var citymap = {};
		<?php
		foreach($current_lat as $count => $current_lat_counted)
		{
			echo "citymap[" . $count . "] = {\n";
			echo "center: new google.maps.LatLng(" . $current_lat[$count] . ", " . $current_long[$count] . "),\n";
			echo "population: " . $current_crime[$count] . "\n";
			echo "};\n\n";
		}
		?>
	var cityCircle;

	function initialize() {
	  var mapOptions = {
		zoom: 6,
		center: new google.maps.LatLng(53.1142, 2.5771),
		mapTypeId: google.maps.MapTypeId.TERRAIN
	  };

	  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

	  var flightPlanCoordinates = [
	  <?php
		$count = 1; // 1=lat, 2=long
		$inc = 0; // 
	  
		foreach($current_polyline_array as $b)
		{
			foreach($b as $current_polyline_array_foreach)
			{
				if($count == 1) 
				{
					$current_polyline_lat[$inc] = $current_polyline_array_foreach;
					$count = 2;
				}
				elseif($count == 2)
				{
					$current_polyline_long[$inc] = $current_polyline_array_foreach;
					$count = 1;
					$inc++;
				}
				//var_dump($current_polyline_array_foreach);
			}
		}
		
		for($a = 0; $a < $inc; $a++)
		{
			echo "new google.maps.LatLng(" . $current_polyline_lat[$a] . ", " . $current_polyline_long[$a] . ")";
			if($a != $inc - 1) echo ",";
			echo "\n";
			//echo $current_polyline_lat[$a];
			//new google.maps.LatLng(37.772323, -122.214897),
		}
	  ?>
      
  ];
  var flightPath = new google.maps.Polyline({
    path: flightPlanCoordinates,
    strokeColor: '#000000',
    strokeOpacity: 1.0,
    strokeWeight: 2
  });

  flightPath.setMap(map);

	  for (var city in citymap) {
		// Construct the circle for each value in citymap. We scale population by 20.
		var populationOptions = {
		  strokeColor: '#FF0000',
		  strokeOpacity: 0.8,
		  strokeWeight: 2,
		  fillColor: '#FF0000',
		  fillOpacity: 0.1,
		  map: map,
		  center: citymap[city].center,
		  radius:  3000
		};
		cityCircle = new google.maps.Circle(populationOptions);
	  }
	}

	google.maps.event.addDomListener(window, 'load', initialize);
    </script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
  </head>
  <body style="margin-top: 43px;">
	<header class="bar-title">
	  <a href="#myPopover">
		<h1 class="title">Awesome Map Thing</h1>
	  </a>
	</header>
	<div id="myPopover" class="popover">
	  <header class="popover-header">
		<a class="button" href="#">
		  Left
		</a>
		<h3 class="title">Popover title</h3>
		<a class="button" href="#">
		  Right
		</a>
	  </header>
	  <ul class="list">
		<li>Item1</li>
		<li>Item2</li>
		<li>Item3</li>
		<li>Item4</li>
	  </ul>
	</div>
    <div id="map-canvas"></div>
  </body>
</html>