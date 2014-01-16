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
/**
 * To prevent sql-injection
 */
$_POST = array_map('stripslashes', $_POST);
$action = $_POST['action'];
//$action = $_POST['param']



if     ($action == 'getPlansByUserId') {


	$userId = $_POST['userId'];
	$plans = $sql->select()
				->from('plan')
				->where('userid = '. $userId)
				->execute();
	echo json_encode($plans);


}
elseif ($action == "addNewPlan") {
/*INSERT NewValue*/

	$planName = $_POST['name'];
	$userId   = $_POST['userId'];

	$insertArr  = array(
		'name'        => $planName,
		'userid'        => $userId,
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
}
elseif ($action == "exc") {


	$kvId = $_POST['id'];
	$result = $sql  ->select('kv.id, kv.value')
					->from('keyvalue', 'kv')
					->innerJoin('attr', 'a', 'kv.id = a.childId')
					->innerJoin('key', 'k', 'kv.keyId = k.id')
					->where('a.parentId = '.$kvId)
					->execute();
	var_dump($result);
	echo json_encode($result);


}
elseif ($action == 'getExercisesByPlanId') {


	$userId = $_POST['userId'];
	$planId = $_POST['planId'];
	$data = $sql->select()
				->from('exercise')
				->where('planid = '. $planId)
				->execute();
	echo json_encode($data);


}
elseif ($action == 'insertSets') {
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

	$setString = getStringFromSetsArray($sets);


	// insert sets to get the set Id
	$setId = $sql->insert(array('values' => $setsString ))
				 ->into('set')
				 ->execute(true);


	// Verbindung des Sets mit der Übung und dem dazugehörigen User
	$insertArr  = array(
		'userId'        => $userId,
		'exerciseId'    => $exercId,
		'setId'         => $setId,
		'date'          => 'NOW()'
	);
	$sql->insert($insertArr)
		->into('user_exerc_sets')
		->execute(true);
	// gibt nur die abfrage aus


}
elseif ($action == 'getSetsByExerciseId') {


	$exercId = $_POST['exerciseId'];

	// db abfrage
	$date       = date('Y-m-d H:i:s');
	$setString  = '15 - 30 | 16 - 32.25 | 15 - 35';
	$setArr     = getArrayFromSetsString($setString);
	echo json_encode(array(
		'date' => $date,
		'sets' => $setArr
	));


}




/**
 * Global Functions
 */
function getStringFromSetsArray($sets) {
	$setsString = '';
	foreach($sets as $set){
		$reps   = $set['replications'];
		$weight = $set['weight'];
		$setsString .= $reps. ' - ' .$weight. ' | ';
	}
	//$setsString  = '15 - 30 | 16 - 32.25 | 15 - 35 | '
	$setsString =  substr($setsString, 0, -3);
	//$setsString  = '15 - 30 | 16 - 32.25 | 15 - 35'
	return $setsString;
}
function getArrayFromSetsString($setsString) {
	$sets = explode(' | ', $setsString);
	$setsArr = array();
	foreach($sets as $set){
		$setSplitted = explode(' - ', $set);
		$setsArr[] = array(
			'replications'  => $setSplitted[0],
			'weight'        => $setSplitted[1]
		);
	}
	return $setsArr;
}