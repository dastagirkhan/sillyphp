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
class Session {
	/**
	 * Starts new or resumes existing session
	 * 
	 * @access  public
	 * @return  bool
	 */
	
	public function start() {
		if (session_start ()) {
			return true;
		}
		return false;
	}
	
	/**
	 * End existing session, destroy, unset and delete session cookie
	 * 
	 * @access  public
	 * @return  void
	 */
	
	public function end() {
		if ($this->status != true) {
			$this->start ();
		}
		
		session_destroy ();
		session_unset ();
		setcookie ( session_name (), null, 0, "/" );
	}
	
	/**
	 * Set new session item
	 * 
	 * @access  public
	 * @param   mixed
	 * @param   mixed
	 * @return  mixed
	 */
	
	public function set($key, $value) {
		return $_SESSION [$key] = $value;
	}
	
	/**
	 * Checks if session key is already set
	 * 
	 * @access  public
	 * @param   mixed  - session key
	 * @return  bool 
	 */
	
	public function has($key) {
		if (isset ( $_SESSION [$key] )) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Get session item
	 * 
	 * @access  public
	 * @param   mixed
	 * @return  mixed
	 */
	
	public function get($key) {
		if (! isset ( $_SESSION [$key] )) {
			return false;
		}
		
		return $_SESSION [$key];
	}
}

?>