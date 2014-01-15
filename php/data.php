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
	'password' => "",
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
//	$userId = $_POST['userId'];
	$userId = 1;
	$plans = $sql->select()
				->from('plan')
				->where('userid = '. $userId)
				->execute();
	echo json_encode($plans);
}
elseif ($action == "Plan") {
/*INSERT NewValue*/
	/*Aktuellen Input Wert auslesen*/
	$value = $_POST['value'];

	$insertArr  = array(
		'name'        => $value,
	);
	$planId = $sql->insert($insertArr)
				->into('plan')
				->execute();
	echo json_encode($planId);


//	/*auslesen der Aktuellen ID von "PLAN"*/
//	$query = "SELECT id FROM `key` WHERE value = '" . $param . "'";
//	if ($result = $mysqli->query($query)) {
//		$data = $result->fetch_row();
//		$keyId = $data[0];
//	} else {
//		printf("SELECT STATEMENT FAILED - INSERT NewValue");
//	}
//
//
//	/*INSERT in entsprechende Kategorie*/
//	$query = "INSERT INTO keyvalue (`keyId`, `value`) VALUES ('" . $keyId . "', '" . $value . "')";
//
//	/*Überprüfen, ob INSERT Erfolgreich war ($check = true) sonst printf*/
//	if($check = $mysqli->query($query)){
//
////		$return = array();
////		$return[]  = $value;
//		echo json_encode(array('id' =>$mysqli->insert_id, 'value'=>$value));
//	} else {
//		printf("INSERT STATEMENT FAILED - INSERT NewValue");
//	}
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
/* INSERT alle gemachten sets */
	// test
	$sets = array(
		array(
			'replications' => 15,
			'weight' => 32.5,
		),
		array(
			'replications' => 15,
			'weight' => 32.5,
		),
		array(
			'replications' => 15,
			'weight' => 32.5,
		)
	);
	$userId = 1;
	$exercId = 1;

//	$sets       = $_POST['sets'];
//	$userId     = $_POST['userId'];
//	$exercId    = $_POST['exercId'];
	$setsString = '';
	foreach($sets as $set){
		$reps   = $set['replications'];
		$weight = $set['weight'];
		$setsString .= $reps. ' - ' .$weight. ' | ';
	}
	//$setsString  = '15 - 30 | 16 - 32.25 | 15 - 35 | '
	$setsString =  substr($setsString, 0, -3);
	//$setsString  = '15 - 30 | 16 - 32.25 | 15 - 35'


	$setId = $sql->insert(array('values' => $setsString ))
				 ->into('set')
				 ->execute(true);


	$insertArr  = array(
		'userId'        => $userId,
		'exerciseId'    => $exercId,
		'setId'         => $setId,
		'date'          => 'NOW()'
	);
	$sql->insert($insertArr)
		->into('user_exerc_sets')
		->execute(true);
	return true;
}