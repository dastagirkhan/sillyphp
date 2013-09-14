<?php
/*******************************************************************
  This file is part of Silly.
 
  Silly is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Silly is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with Silly.  If not, see <http://www.gnu.org/licenses/>.
  
  @copyright     Copyright 20012-2013, Silly PHP Framework
  @link          silly.gianstocks.com
  @package       silly
  @since         Silly(tm) v 0.9
  @license       http://www.gnu.org/licenses/
 *******************************************************************/
class Database {
	
	// debug flag for showing error messages 
	public $debug = true;
	
	// Store the single instance of Database 
	private static $instance;
	
	private $server = ""; //database server 
	private $user = ""; //database login name 
	private $pass = ""; //database login password 
	private $database = ""; //database name 
	

	private $error = "";
	
	####################### 
	//number of rows affected by SQL query 
	public $affected_rows = 0;
	
	private $link_id = 0;
	private $query_id = 0;
	var $colSort = array ();
	
	#-############################################# 
	# desc: constructor 
	private function __construct($server = null, $user = null, $pass = null, $database = null) {
		// error catching if not passed in 
		if ($server == null || $user == null || $database == null) {
			$this->oops ( "Database information must be passed in when the object is first created." );
		}
		
		$this->server = $server;
		$this->user = $user;
		$this->pass = $pass;
		$this->database = $database;
	} #-#constructor() 
	

	#-############################################# 
	# desc: singleton declaration 
	public static function obtain($server = null, $user = null, $pass = null, $database = null) {
		if (! self::$instance) {
			self::$instance = new Database ( $server, $user, $pass, $database );
		}
		
		return self::$instance;
	} #-#obtain() 
	

	#-############################################# 
	# desc: connect and select database using vars above 
	# Param: $new_link can force connect() to open a new link, even if mysql_connect() was called before with the same parameters 
	public function connect($new_link = false) {
		$this->link_id = @mysql_connect ( $this->server, $this->user, $this->pass, $new_link );
		
		if (! $this->link_id) { //open failed 
			$this->oops ( "Could not connect to server: <b>$this->server</b>." );
		}
		
		if (! @mysql_select_db ( $this->database, $this->link_id )) { //no database 
			$this->oops ( "Could not open database: <b>$this->database</b>." );
		}
		@mysql_set_charset ( 'utf8', $this->link_id );
		// unset the data so it can't be dumped 
		$this->server = '';
		$this->user = '';
		$this->pass = '';
		$this->database = '';
	} #-#connect() 
	

	#-############################################# 
	# desc: close the connection 
	public function close() {
		if (! @mysql_close ( $this->link_id )) {
			$this->oops ( "Connection close failed." );
		}
	} #-#close() 
	

	#-############################################# 
	# Desc: escapes characters to be mysql ready 
	# Param: string 
	# returns: string 
	public function escape($string) {
		if (get_magic_quotes_runtime ())
			$string = stripslashes ( $string );
		return @mysql_real_escape_string ( $string, $this->link_id );
	} #-#escape() 
	

	#-############################################# 
	# Desc: executes SQL query to an open connection 
	# Param: (MySQL query) to execute 
	# returns: (query_id) for fetching results etc 
	public function query($sql) {
		// do query 
		$this->query_id = @mysql_query ( $sql, $this->link_id );
		
		if (! $this->query_id) {
			$this->oops ( "<b>MySQL Query fail:</b> $sql" );
			return 0;
		}
		
		$this->affected_rows = @mysql_affected_rows ( $this->link_id );
		
		return $this->query_id;
	} #-#query() 
	

	#-############################################# 
	# desc: does a query, fetches the first row only, frees resultset 
	# param: (MySQL query) the query to run on server 
	# returns: array of fetched results 
	public function query_first($query_string) {
		$query_id = $this->query ( $query_string );
		$out = $this->fetch ( $query_id );
		$this->free_result ( $query_id );
		return $out;
	} #-#query_first() 
	

	#-############################################# 
	# desc: fetches and returns results one line at a time 
	# param: query_id for mysql run. if none specified, last used 
	# return: (array) fetched record(s) 
	public function fetch($query_id = -1) {
		// retrieve row 
		if ($query_id != - 1) {
			$this->query_id = $query_id;
		}
		
		if (isset ( $this->query_id )) {
			$record = @mysql_fetch_assoc ( $this->query_id );
		} else {
			$this->oops ( "Invalid query_id: <b>$this->query_id</b>. Records could not be fetched." );
		}
		
		return $record;
	} #-#fetch() 
	

	#-############################################# 
	# desc: returns all the results (not one row) 
	# param: (MySQL query) the query to run on server 
	# returns: assoc array of ALL fetched results 
	public function fetch_array($sql) {
		$query_id = $this->query ( $sql );
		$out = array ();
		
		while ( $row = $this->fetch ( $query_id ) ) {
			$out [] = $row;
		}
		
		$this->free_result ( $query_id );
		return $out;
	} #-#fetch_array() 
	

	#-############################################# 
	# desc: does an update query with an array 
	# param: table, assoc array with data (not escaped), where condition (optional. if none given, all records updated) 
	# returns: (query_id) for fetching results etc 
	public function update($table, $data, $where = '1', $message = true) {
		$q = "UPDATE `$table` SET ";
		
		foreach ( $data as $key => $val ) {
			if (strtolower ( $val ) == 'null')
				$q .= "`$key` = NULL, ";
			elseif (strtolower ( $val ) == 'now()')
				$q .= "`$key` = NOW(), ";
			elseif (preg_match ( "/^increment\((\-?[\d\.]+)\)$/i", $val, $m ))
				$q .= "`$key` = `$key` + $m[1], ";
			elseif (preg_match ( "/^INET_ATON\(((?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3})\)/", $val, $m ))
				$q .= "`$key`=  INET_ATON('{$m[1]}'), ";
			else
				$q .= "`$key`='" . $this->escape ( $val ) . "', ";
		}
		$q = rtrim ( $q, ', ' ) . ' WHERE ' . $where . ';';
		return $this->query ( $q );
	} #-#update() 
	

	#-############################################# 
	# desc: does an insert query with an array 
	# param: table, assoc array with data (not escaped) 
	# returns: id of inserted record, false if error 
	public function insert($table, $data, $message = true) {
		$q = "INSERT INTO `$table` ";
		$v = '';
		$n = '';
		
		foreach ( $data as $key => $val ) {
			$n .= "`$key`, ";
			if (strtolower ( $val ) == 'null')
				$v .= "NULL, ";
			elseif (strtolower ( $val ) == 'now()')
				$v .= "NOW(), ";
			else
				$v .= "'" . $this->escape ( $val ) . "', ";
		}
		
		$q .= "(" . rtrim ( $n, ', ' ) . ") VALUES (" . rtrim ( $v, ', ' ) . ");";
		
		if ($this->query ( $q )) {
			if ($message)
				$_SESSION ['flashMessage'] = "<div class = 'success-knobs'>" . ucfirst ( $table ) . " created sucessfully</div>";
			return true;
		} else
			return false;
	
	} #-#insert() 
	

	#-############################################# 
	# desc: frees the resultset 
	# param: query_id for mysql run. if none specified, last used 
	private function free_result($query_id = -1) {
		if ($query_id != - 1) {
			$this->query_id = $query_id;
		}
		if ($this->query_id != 0 && ! @mysql_free_result ( $this->query_id )) {
			$this->oops ( "Result ID: <b>$this->query_id</b> could not be freed." );
		}
	} #-#free_result() 
	

	#-#############################################
	# desc: return whether there is a connection to the db
	public function connected() {
		if (! $this->link_id)
			return FALSE;
		else
			return TRUE;
	} #-#connected()
	

	#-############################################# 
	# desc: throw an error message 
	# param: [optional] any custom error to display 
	private function oops($msg = '') {
		if (! empty ( $this->link_id )) {
			$this->error = mysql_error ( $this->link_id );
		} else {
			$this->error = mysql_error ();
			$msg = "<b>WARNING:</b> No link_id found. Likely not be connected to database.<br />$msg";
		}
		
		// if no debug, done here 
		if (! $this->debug)
			return;
		?>
<table align="center" border="1" cellspacing="0"
	style="background: white; color: black; width: 80%;">
	<tr>
		<th colspan=2>Database Error</th>
	</tr>
	<tr>
		<td align="right" valign="top">Message:</td>
		<td><?php
		echo $msg;
		?></td>
	</tr> 
        <?php
		if (! empty ( $this->error ))
			echo '<tr><td align="right" valign="top" nowrap>MySQL Error:</td><td>' . $this->error . '</td></tr>';
		?> 
        <tr>
		<td align="right">Date:</td>
		<td><?php
		echo date ( "l, F j, Y \a\\t g:i:s A" );
		?></td>
	</tr> 
        <?php
		if (! empty ( $_SERVER ['REQUEST_URI'] ))
			echo '<tr><td align="right">Script:</td><td><a href="' . $_SERVER ['REQUEST_URI'] . '">' . $_SERVER ['REQUEST_URI'] . '</a></td></tr>';
		?> 
        <?php
		if (! empty ( $_SERVER ['HTTP_REFERER'] ))
			echo '<tr><td align="right">Referer:</td><td><a href="' . $_SERVER ['HTTP_REFERER'] . '">' . $_SERVER ['HTTP_REFERER'] . '</a></td></tr>';
		?> 
        </table>
<?php
	} #-#oops() 
	

	#-############################################# 
	# desc : find will build sql and executes
	# param: string $type   
	# param: array $options   
	# param: mixed $model
	public function find($model, $options = array()) {
		$conditions = $order = $limit = "";
		$fields = "*";
		foreach ( $options as $key => $option ) {
			switch ($key) {
				case "conditions" :
					$seperator = "";
					$conditions = "where ";
					foreach ( $option as $field => $value ) {
						$comparisonOperator = (stripos ( $field, "=" )) ? "" : "=";
						$conditions .= "$seperator $field $comparisonOperator '$value' ";
						$seperator = "&&";
					}
					break;
				
				case "fields" :
					$seperator = "";
					$fields = "";
					foreach ( $option as $field ) {
						$fields .= "$seperator $field ";
						$seperator = ",";
					}
					break;
				case "group" :
					break;
				
				case "order" :
					$seperator = " ORDER by ";
					foreach ( $option as $field ) {
						$order .= "$seperator $field ";
						$seperator = ",";
					}
					break;
				
				case "limit" :
					$limit = " LIMIT " . $option;
					break;
			}
		}
		
		$sql = "select $fields from $model $conditions $order $limit";
		return $this->fetch_array ( $sql );
	} #-#find() 
	

	#-################################################################################
	# desc: Build adds the additional options to the sql				                     
	# like order by field name,filter by given field value,pagination by given limit 
	public function build($where = "where", $limit = true) {
		#Ordering
		$sOrder = "";
		if (isset ( $_POST ['iSortCol_0'] )) {
			$sOrder = "ORDER BY  ";
			for($i = 0; $i < intval ( $_POST ['iSortingCols'] ); $i ++) {
				if ($_POST ['bSortable_' . intval ( $_POST ['iSortCol_' . $i] )] == "true") {
					$sOrder .= $this->colSort [intval ( $_POST ['iSortCol_' . $i] )] . " " . mysql_real_escape_string ( $_POST ['sSortDir_' . $i] ) . ", ";
				}
			}
			
			$sOrder = substr_replace ( $sOrder, "", - 2 );
			if ($sOrder == "ORDER BY") {
				$sOrder = "";
			}
		}
		
		# Filtering
		# NOTE this does not match the built-in DataTables filtering which does it
		# word by word on any field. It's possible to do here, but concerned about efficiency
		# on very large tables, and MySQL's regex functionality is very limited		 
		$sWhere = "";
		if (isset ( $_POST ['sSearch'] ) && $_POST ['sSearch'] != "") {
			$_POST ['sSearch'] = trim ( $_POST ['sSearch'] );
			$sSearch = preg_split ( '/\s+/', $_POST ['sSearch'] );
			$sWhere = " $where (";
			for($i = 0; $i < count ( $this->colSort ); $i ++) {
				$sWhere .= $this->colSort [$i] . " LIKE '%" . mysql_real_escape_string ( $sSearch [0] ) . "%' OR ";
			}
			$sWhere = substr_replace ( $sWhere, "", - 3 );
			$sWhere .= ')';
		}
		
		#Pagination			
		$sLimit = "";
		
	/*	if (isset ( $_POST ['iDisplayStart'] ) && $_POST ['iDisplayLength'] != '-1' && $limit) {
			$sLimit = " LIMIT " . mysql_real_escape_string ( $_POST ['iDisplayStart'] ) . ", " . mysql_real_escape_string ( $_POST ['iDisplayLength'] );
		}
		*/
		return $sWhere . " " . $sOrder . " " . $sLimit;
		
	#have to implement the below commented statements
	/* Data set length after filtering */
	/*$sQuery = "
		SELECT FOUND_ROWS()
	";
		$rResultFilterTotal = mysql_query ( $sQuery, $gaSql ['link'] ) or die ( mysql_error () );
		$aResultFilterTotal = mysql_fetch_array ( $rResultFilterTotal );
		$iFilteredTotal = $aResultFilterTotal [0];*/
	
	/* Total data set length */
	/*	$sQuery = "
		SELECT COUNT(" . $sIndexColumn . ")
		FROM   $sTable
	";
		$rResultTotal = mysql_query ( $sQuery, $gaSql ['link'] ) or die ( mysql_error () );
		$aResultTotal = mysql_fetch_array ( $rResultTotal );
		$iTotal = $aResultTotal [0];
		*/
	/*
	 * Output
	 */
	//$output = array ("sEcho" => intval ( $_GET ['sEcho'] ), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array () );
	

	} #-#build()
} //CLASS Database 
################################################################################################### 
?>