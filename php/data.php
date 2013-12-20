<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dbartuschat
 * Date: 20.12.13
 * Time: 09:55
 * To change this template use File | Settings | File Templates.
 */

//TODO
//SQL SICHERHEIT!!!!


$param = $_POST['param'];

/* Open a connection */
$mysqli = new mysqli("localhost", "root", "isa99#", "trainday");

/* check connection */
if ($mysqli->connect_error) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

if($param == 'plan'){
	$query = "SELECT kv.value FROM `key` AS k
				INNER JOIN `keyvalue` AS kv ON k.id = kv.keyId
				WHERE k.value = '" . $param . "'";
	if ($result = $mysqli->query( $query)) {

		$return = array();
		$datas = $result->fetch_all();
		foreach($datas as $data){
			$return[] = $data[0];
		}
		echo json_encode($return);
	}
}