<?php
/********** MODEL NAMES & FILE NAME SHOULD BE PLURAL****************/

/** 
 * Start creating models once database configuration file (/config/database.php) is all set
 * Please make sure if you create a 'model' you have to set database with the 'same table name' like 'model name'
 * find,query,query_first,fetch_array,save & update are the default methods of any model
 * for more info look into the silly docs..
 */
class frameworks extends sillyModel{
	var $name = 'frameworks';
	public function __construct() {		
		parent::__construct ();		
	}
	public function call(){
		return 'Hey this is '.$this->name.' model';
	}		
}