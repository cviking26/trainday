<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dbartuschat
 * Date: 20.12.13
 * Time: 09:55
 * To change this template use File | Settings | File Templates.
 */


require_once('Sql.php');
$sql = new App_Sql(array(
	'host' => "localhost",
	'username' => "root",
	'password' => "isa99#",
	'database' => "trainday"
));

$action = $_POST['param'];
//$param = 'exc';

/* Open a connection */
//$mysqli = new mysqli("localhost", "root", "isa99#", "trainday");

/* check connection */
//if ($mysqli->connect_error) {
//	printf("Connect failed: %s\n", mysqli_connect_error());
//	exit();
//}

if($action == 'plan') {
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
} elseif ($action == "Plan") {
/*INSERT NewValue*/
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
} elseif ($action == "exc") {
	$kvId = $_POST['id'];
	$result = $sql  ->select('kv.id, kv.value')
					->from('keyvalue', 'kv')
					->innerJoin('attr', 'a', 'kv.id = a.childId')
					->innerJoin('key', 'k', 'kv.keyId = k.id')
					->where('a.parentId = '.$kvId)
					->execute();
	var_dump($result);
	echo json_encode($result);


} elseif ($action == 'planDetail') {
//	$planId = $_POST['planId'];
//	$result = $sql  ->select('kv.id, kv.value')
//		->from('keyvalue', 'kv')
//		->innerJoin('attr', 'a', 'kv.id = a.childId')
//		->innerJoin('key', 'k', 'kv.keyId = k.id')
//		->where('a.parentId = '.$kvId)
//		->execute();
	// get exercises
	return true;
} elseif ($action == 'insertSets') {
	$sets = $_POST['sets'];

	$sets[''];


//	--sets--
//id      = 3
//values  = '15 - 30 | 16 - 32.25 | 15 - 35'


}