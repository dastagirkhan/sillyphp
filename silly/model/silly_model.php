<?php
/***************************************
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
 ***************************************/
class sillyModel extends Database {
	protected $dbmysql;
	protected $dbmongo;
	protected $dboracle;
	protected $dbsqlite;
	var $driver = '';
	
	protected $dbconfig = array ('default' => array ('driver' => '', 'host' => '', 'login' => '', 'password' => '', 'database' => '' ) );
	public function __construct() {
		global $config;
		$this->dbconfig = array_merge ( $this->dbconfig, $config ['dbconfig'] );
		$this->connect ();
	}
	public function connect() {
		foreach ( $this->dbconfig as $type => $dbconfig ) {
			switch ($dbconfig ['driver']) {
				case 'mysql' :
					$this->driver = 'mysql';
					if ($dbconfig ['host'] != '' && $dbconfig ['login'] != '' && $dbconfig ['database'] != '') {
						$this->dbmysql = Database::obtain ( $dbconfig ['host'], $dbconfig ['login'], $dbconfig ['password'], $dbconfig ['database'] );
						$this->dbmysql->connect ();						
					} else
						return false;
					break;
			}
		}
	}
	public function connected() {			
		if ($this->driver != '')
			return $this->{"db" . $this->driver}->connected ();
	}
	public function find($options = array() ) {			
		if ($this->driver != '')
			return $this->{"db" . $this->driver}->find ( get_called_class (), $options  );
	}
	public function query($sql) {
		if ($this->driver != '')
			return $this->{"db" . $this->driver}->query ( $sql );
	}
	public function query_first($sql) {
		if ($this->driver != '')
			return $this->{"db" . $this->driver}->query_first ( $sql );
	}
	public function fetch_array($sql) {
		if ($this->driver != '')
			return $this->{"db" . $this->driver}->fetch_array ( $sql );
	}
	public function update($data, $where = '1', $message = true) {
		if ($this->driver != '')
			return $this->{"db" . $this->driver}->update ( get_called_class (), $data, $where, $message );
	}
	public function save($data, $message = '') {			
		if ($this->driver != '')
			return $this->{"db" . $this->driver}->insert ( get_called_class (), $data, $message );
	}
}