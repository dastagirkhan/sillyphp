<?php
class cache {
	
	public function write($key, $data) {
		return file_put_contents ( CACHE . '/' . $key, $data );
	}
	
	public function read($key) {
		if ($this->exists ( $key )) {
			return file_get_contents ( CACHE . '/' . $key );
		} else
			return false;
	}
	
	public function exists($key) {
		return file_exists ( CACHE . '/' . $key );
	}
	
	public function delete($key) {
		if ($this->exists ( $key ))
			return unlink ( CACHE . '/' . $key );
	}

}