<?php
/********** CONTROLLER NAME & FILE NAME SHOULD BE SINGULAR ****************/

class framework extends app {
	public $name = 'framework';       #controller name
	public $action = 'home';          #default action to land
		  
	/****************Optional settings*****************************************************************
	 * #Associating with users model this will unlink with default model i.e frameworks *
	 * public $uses  = array('users');			  										*	
	 * #But still you can keep this association by passing frameworks in array			*
	 * public $uses  = array('users','frameworks);										*
	 ************************************************************************************/  
	public function __construct() {
		parent::__construct ();
	}
	public function home() {	
		#echo $this->frameworks->call(); #model call if uses not set
		#echo $this->users->call();      #model call if uses set to users		 
		$var1 = 'Hello';
		$var2 = 'World';
		#if there  are any 'local varables' it must compact and assign to class variable 'vars' in order to use them in views
		$this->vars = compact('var1','var2');		
	}

}

?>