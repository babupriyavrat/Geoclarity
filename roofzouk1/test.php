<?php
echo "test";
$url = "https://maps.googleapis.com/maps/api/geocode/json?address=3526+HIGH+ST+SACRAMENTO&key=AIzaSyDrCLxnDOiaKMPzg5tF0yfsMpRMXWC0X7c";
		            	$apiResult = file_get_contents($url);
		            	echo $apiResult;
		            	$apiJson = json_decode($apiResult);
		            	print_r($apiJson);
		            	
		            	print_r($apiJson->results[0]->geometry->location); // 