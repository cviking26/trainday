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
//$db['host'],
//			$db['username'],
//			$db['password'],
//			$db['database']
require_once('Sql.php');
$sql = new App_Sql(array(
	'host' => "localhost",
	'username' => "root",
	'password' => "isa99#",
	'database' => "trainday"
));

$param = $_POST['param'];
//$param = 'exc';

/* Open a connection */
$mysqli = new mysqli("localhost", "root", "isa99#", "trainday");

/* check connection */
if ($mysqli->connect_error) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

if($param == 'plan'){
	$query = "SELECT kv.id, kv.value FROM `key` AS k
				INNER JOIN `keyvalue` AS kv ON k.id = kv.keyId
				WHERE k.value = '" . $param . "'";
	if ($result = $mysqli->query($query)) {
		$return = array();
		$datas = $result->fetch_all();
		foreach($datas as $data){
			$return[] = array('id'=> $data[0], 'name'=> $data[1]);
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

//		$return = array();
//		$return[]  = $value;
		echo json_encode(array('id' =>$mysqli->insert_id, 'value'=>$value));
	} else {
		printf("INSERT STATEMENT FAILED - INSERT NewValue");
	}
}elseif($param == "exc"){
	$kvId = $_POST['id'];
	$result = $sql  ->select('kv.id, kv.value')
					->from('keyvalue', 'kv')
					->innerJoin('attr', 'a', 'kv.id = a.childId')
					->innerJoin('key', 'k', 'kv.keyId = k.id')
					->where('a.parentId = '.$kvId)
					->execute();
	var_dump($result);
	echo json_encode($result);
	// blabla

//	if ($stmt = $mysqli->prepare("SELECT password FROM tbl_users WHERE name=?")) {
//
//		// Bind a variable to the parameter as a string.
//		$stmt->bind_param("s", $name);
//
//		// Execute the statement.
//		$stmt->execute();
//
//		// Get the variables from the query.
//		$stmt->bind_result($pass);
//
//		// Fetch the data.
//		$stmt->fetch();
//
//		// Display the data.
//		printf("Password for user %s is %s\n", $name, $pass);
//
//		// Close the prepared statement.
//		$stmt->close();
//
//	}
}elseif($param == 'planDetail')
{
//	$planId = $_POST['planId'];
//	$result = $sql  ->select('kv.id, kv.value')
//		->from('keyvalue', 'kv')
//		->innerJoin('attr', 'a', 'kv.id = a.childId')
//		->innerJoin('key', 'k', 'kv.keyId = k.id')
//		->where('a.parentId = '.$kvId)
//		->execute();
	// get exercises
	return true;
}
/*
 *
 *
 *
 * */