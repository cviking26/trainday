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
	if ($result = $mysqli->query($query)) {

		$return = array();
		$datas = $result->fetch_all();
		foreach($datas as $data){
			$return[] = $data[0];
		}
		echo json_encode($return);
	}
}

/*INSERT NewValue*/
if($param == "Plan"){
	/*auslesen der Aktuellen ID von "PLAN"*/
	$query = "SELECT id FROM `key` WHERE value = '" . $param . "'";
	if ($result = $mysqli->query($query)) {
		$data = $result->fetch_row();
		$keyId = $data[0];
	} else {
		printf("SELECT STATEMENT FAILED - INSERT NewValue");
	}

	/*Aktuellen Input Wert auslesen*/
	$value = $_POST['value'];

	/*INSERT in entsprechende Kategorie*/
	$query = "INSERT INTO keyvalue (`keyId`, `value`) VALUES ('" . $keyId . "', '" . $value . "')";

	/*Überprüfen, ob INSERT Erfolgreich war ($check = true) sonst printf*/
	if($check = $mysqli->query($query)){
		$return = array();
		$return[]  = $value;
		echo json_encode($return);
	} else {
		printf("INSERT STATEMENT FAILED - INSERT NewValue");
	}
}