<?php

class App_Sql
{
	/**
	 * @var Mysqli
	 */
	private $db;

	/**
	 * @var string
	 */
	private $table = null;

	/**
	 * @var string
	 */
	private $joins = null;

	/**
	 * @var string
	 */
	private $where = null;

	/**
	 * @var string
	 */
	private $order = null;

	/**
	 * @var string
	 */
	private $group = null;

	/**
	 * @var string
	 */
	private $having = null;

	/**
	 * @var int|null
	 */
	private $limit = null;

	/**
	 * @var string
	 */
	private $action = null;

	/**
	 * @var string
	 */
	private $queryString = null;

	/**
	 * @var array
	 */
	private $sqlCommands = array(
		//- Converts a field to upper case
		'UCASE()',
		//	- Converts a field to lower case
		'LCASE()',
		//- Extract characters from a text field
		'MID()',
		//	 - Returns the length of a text field
		'LEN()',
		//	 - Rounds a numeric field to the number of decimals specified
		'ROUND()',
		//	 - Returns the current system date and time
		'NOW()',
		//	 - Formats how a field is to be displayed
		'FORMAT()',
		//	- Returns the average value
		'AVG()',
		//	- Returns the number of rows
		'COUNT()',
		//	- Returns the first value
		'FIRST()',
		//	- Returns the last value
		'LAST()',
		//	- Returns the largest value
		'MAX()',
		//	- Returns the smallest value
		'MIN()',
		//	- Returns the sum
		'SUM()'
	);

	/**
	 * Instance implementation
	 *
	 * @var App_Sql
	 */
	private static $_instance   = null;


	/**
	 * Single pattern implementation
	 *
	 * @return Instance of App_Request
	 */
	public static function getInstance()
	{
		if (null === self::$_instance)
			self::$_instance = new self();

		return self::$_instance;
	}

	/**
	 * @param array $db_info_arr  db connection info
	 * @throws exception  the mysql Error
	 */
	public function __construct($db_info_arr = null)
	{
		if($db_info_arr)
			$db     = $db_info_arr;
		else
			$db     = App_Ini::get('db');
		$this->db   = new mysqli(
			$db['host'],
			$db['username'],
			$db['password'],
			$db['database']
		);
		if($this->db->connect_error)
			throw new App_Exception('Error: '.$this->db->connect_error, 3333);
	}



	/**
	 * @param  string
	 * @param  string
	 * @return class $this(App_Sql)
	 */
	public function from($table, $tableAlias = null)
	{
		if(strpos($this->action , 'SELECT') !== FALSE){
			$string = 'FROM '.$table;
			if($tableAlias)
				$string .= ' AS '.$tableAlias;
			$this->table = $string;
		} else {
			$this->table = $table;
		}
		return $this;
	}



	#region Joins
	/**
	 * @param String $table         the table name of the join
	 * @param String $tableAlias    the table alias of the joining table
	 * @param String $on which      keys the tables are going to join
	 * @param String $join          which join is used
	 * @return null
	 */
	private function join($table, $tableAlias, $on, $join)
	{
		$this->joins .= $join. ' `' .$table. '` AS ' .$tableAlias .' ON '. $on .' ';
	}

	/**
	 * add inner join to query
	 */
	public function innerJoin($table, $tableAlias, $on)
	{
		$this->join($table, $tableAlias, $on, 'INNER JOIN');
		return $this;
	}

	/**
	 * add outer join to query
	 */
	public function outerJoin($table, $tableAlias, $on)
	{
		$this->join($table, $tableAlias, $on, 'OUTER JOIN');
		return $this;
	}

	/**
	 * add left join to query
	 */
	public function leftJoin($table, $tableAlias, $on)
	{
		$this->join($table, $tableAlias, $on, 'LEFT JOIN');
		return $this;
	}

	/**
	 * add right join to query
	 */
	public function rightJoin($table, $tableAlias, $on)
	{
		$this->join($table, $tableAlias, $on, 'RIGHT JOIN');
		return $this;
	}
	#endregion


	public function where($string)
	{
		if($string != '')
			$this->where   .= 'WHERE '.$string;
		return $this;
	}

	public function group($group)
	{
		$this->group    = 'GROUP BY ';
		if (is_array($group)) {
			foreach ($group as $o) {
				if($group == 'GROUP BY '){
					$this->group .= $o;
				} else {
					$this->group .= ', '.$o;
				}
			}
		} else {
			$this->group .= $group;
		}
		return $this;
	}

	public function having($string)
	{
		$this->having   = 'HAVING '.$string;
		return $this;
	}

	public function order($order)
	{
		$this->order    = 'ORDER BY '.$order;
		return $this;
	}

	public function limit($limit)
	{
		$this->limit    = 'LIMIT '.$limit;
		return $this;
	}

	public function reset()
	{
		$this->action       = null;
		$this->table        = null;
		$this->joins        = null;
		$this->where        = null;
		$this->group        = null;
		$this->having       = null;
		$this->limit        = null;
		$this->order        = null;
		$this->queryString  = null;
		return $this;
	}



	public function execute()
	{
		$queryString    = $this->action.' ';
		$queryString   .= $this->table.' ';
		if(!strpos($this->action , 'SELECT'))
			$queryString .= $this->queryString.' ';
		if($this->joins)
			$queryString .= $this->joins.' ';
		if($this->group)
			$queryString .= $this->group.' ';
		if($this->having)
			$queryString .= $this->having.' ';
		if($this->order)
			$queryString .= $this->order.' ';
		if($this->where)
			$queryString .= $this->where.' ';

		$queryString    = substr($queryString, 0, -1);
		$statement      = $this->db->prepare($queryString);
//		var_dump($queryString);
		$this->db->set_charset('utf8');
		$statement->execute();
		$return = null;
		if(is_int(strpos($this->action , 'SELECT'))){
			$result         = $statement->get_result();
			$returnArr      = array();
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$returnArr[] = $row;
			}
			$return = $returnArr;
//            if(count($returnArr) == 1)
//                $return = $returnArr[0];
		}
		if(is_int(strpos($this->action , 'INSERT')))
			$return = $this->db->insert_id;
		if(is_int(strpos($this->action , 'UPDATE')))
			$return = true;
		if(is_int(strpos($this->action , 'DELETE')))
			$return = true;
		$this->reset();
		return $return;
	}


	public function select($select = null)
	{
		if(!$select)
			$select = '*';
		$this->action = 'SELECT '.$select;
		return $this;
	}


	/*
	 *
	 * Example for valuesArr:
	$insertArray = array(
		'Vorname'
		'nachname'
	)
	$valuesArr = array(
		array(
			'marlon'
			'rüscher'
		)
		array(
			'dennis'
			'bast'
		)
	)
	 *
	 * */
	public function insert($insertArray, $valuesArr = null)
	{
		$columns            = '';
		$values             = '';
		if($valuesArr){
			foreach($insertArray as $key => $value)
			{
				$columns .= '`'. $value .'`, ';

			}
			foreach($valuesArr as $valueArr)
			{
				$values .= '(';
				foreach($valueArr as $value)
				{
					$values .= '\''. $value .'\', ';
				}
				$values = substr($values , 0, -2);
				$values .= '), ';
			}
		} else {
			foreach ($insertArray as $column => $value){
				if($value != null && $value != '')
				{
					$columns .= '`'. $column .'`, ';
					$values .= '\''. $value .'\', ';
				}
			}

		}
		$values             = substr($values , 0, -2);
		$columns            = substr($columns, 0, -2);
		$this->action       = 'INSERT INTO';

		if($valuesArr){
			$this->queryString  = '('. $columns .') VALUES '.  $values;
		} else {
			$this->queryString  = '('. $columns .') VALUES ('. $values .')';
		}
		return $this;
	}


	/* Example:
	 * $updateArray = array(
	 *      'idColumn' => 'id',
	 *      'column2'  => 'value2'
	 *      'column3'  => 'value3'
	 * );
	 */
	public function update($updateArray)
	{
		$string             = '';
		foreach ($updateArray as $column => $value){
			if($this->isSqlCommand($value)){
				$string .= $column .'='. $value .', ';
			} else {
				$string .= $column .'=\''. $value .'\', ';
			}
		}
		$string             = substr($string, 0, -2);
		$this->action       = 'UPDATE';
		$this->queryString  = 'SET '. $string;
		return $this;
	}

	public function delete()
	{
		$this->action       = 'DELETE';
		return $this;
	}


	private function isSqlCommand($string)
	{
		if(in_array($string, $this->sqlCommands)){
			return true;
		} elseif($string === true){
			return true;
		} elseif($string === false) {
			return false;
		}
	}
}