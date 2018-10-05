<?php
//--------------------------------------------------------------------------------------------------//
//-------------------------------           COPYRIGHT 2009           -------------------------------//
//-------------------------------       Coding by: A.Spencer         -------------------------------//
//-------------------------------            For: PalSoc             -------------------------------//
//--------------------------------------------------------------------------------------------------//

/*
$dbhost = "localhost";
$dbuname = "root";
$dbpass = "gointo1983";
$dbname = "palsoc";
*/
$dbhost = "localhost";
$dbuname = "palsoc_site";
$dbpass = "YzP3q2009";
$dbname = "palsoc_website";

// Start DEFINE SQL_LAYER
if(!defined("SQL_LAYER")) {

	define("SQL_LAYER","mysql");
	
	// Start DATABASE CLASS sql_db
	class sql_db {
		var $db_connect_id;
		var $query_result;
		var $row = array();
		var $rowset = array();
		var $num_queries = 0;
	
		// CONSTRUCTOR
		function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true) {
	
			$this->persistency = $persistency;
			$this->user = $sqluser;
			$this->password = $sqlpassword;
			$this->server = $sqlserver;
			$this->dbname = $database;
	
			if($this->persistency) {
				$this->db_connect_id = @mysql_pconnect($this->server, $this->user, $this->password);
			} else {
				$this->db_connect_id = @mysql_connect($this->server, $this->user, $this->password);
			}
			if($this->db_connect_id) {
				if($database != "") {
					$this->dbname = $database;
					$dbselect = @mysql_select_db($this->dbname);
					if(!$dbselect) {
						@mysql_close($this->db_connect_id);
						$this->db_connect_id = $dbselect;
					}
				}
				return $this->db_connect_id;
			} else {
				return false;
			}
		}
	
		// OTHER BASE METHODS
		function sql_close() {
			if($this->db_connect_id) {
				if($this->query_result) {
					@mysql_free_result($this->query_result);
				}
				$result = @mysql_close($this->db_connect_id);
				return $result;
			} else {
				return false;
			}
		}
	
		// BASE QUERY METHOD
		function sql_query($query = "", $transaction = FALSE) {
			// Remove any pre-existing queries
			unset($this->query_result);
			if($query != "") {
				$this->query_result = @mysql_query($query, $this->db_connect_id);
			}
			if($this->query_result) {
				unset($this->row[$this->query_result]);
				unset($this->rowset[$this->query_result]);
				return $this->query_result;
			} else {
				return ( $transaction == 'END_TRANSACTION' ) ? true : false;
			}
		}
	
		// OTHER QUERY METHODS
		function sql_numrows($query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}
			if($query_id) {
				$result = @mysql_num_rows($query_id);
				return $result;
			} else {
				return false;
			}
		}
		function sql_affectedrows() {
			if($this->db_connect_id) {
				$result = @mysql_affected_rows($this->db_connect_id);
				return $result;
			} else {
				return false;
			}
		}
		function sql_numfields($query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}
			if($query_id) {
				$result = @mysql_num_fields($query_id);
				return $result;
			} else {
				return false;
			}
		}
		function sql_fieldname($offset, $query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}
			if($query_id) {
				$result = @mysql_field_name($query_id, $offset);
				return $result;
			} else {
				return false;
			}
		}
		function sql_fieldtype($offset, $query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}
			if($query_id) {
				$result = @mysql_field_type($query_id, $offset);
				return $result;
			} else {
				return false;
			}
		}
		function sql_fetchrow($query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}
			if($query_id) {      
        // Turn Resouce ID into Interger
        $resource_id = $query_id;
        $query_row_id = str_replace("Resource id #", "", $query_id);
        $this->row[$query_row_id] = mysql_fetch_array($resource_id);
				return $this->row[$query_row_id];
			} else {
				return false;
			}
		}
		function sql_fetchrowset($query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}
			if($query_id) {
				unset($this->rowset[$query_id]);
				unset($this->row[$query_id]);
				while($this->rowset[$query_id] = @mysql_fetch_array($query_id)) {
					$result[] = $this->rowset[$query_id];
				}
				return $result;
			} else {
				return false;
			}
		}
		function sql_fetchfield($field, $rownum = -1, $query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}
			if($query_id) {
				if($rownum > -1) {
					$result = @mysql_result($query_id, $rownum, $field);
				} else {
					if(empty($this->row[$query_id]) && empty($this->rowset[$query_id])) {
						if($this->sql_fetchrow()) {
							$result = $this->row[$query_id][$field];
						}
					} else {
						if($this->rowset[$query_id]) {
							$result = $this->rowset[$query_id][$field];
						} else if($this->row[$query_id]) {
							$result = $this->row[$query_id][$field];
						}
					}
				}
				return $result;
			} else {
				return false;
			}
		}
		function sql_rowseek($rownum, $query_id = 0){
			if(!$query_id) {
				$query_id = $this->query_result;
			}
			if($query_id) {
				$result = @mysql_data_seek($query_id, $rownum);
				return $result;
			} else {
				return false;
			}
		}
		function sql_nextid(){
			if($this->db_connect_id) {
				$result = @mysql_insert_id($this->db_connect_id);
				return $result;
			} else {
				return false;
			}
		}
		function sql_freeresult($query_id = 0) {
			if(!$query_id) {
				$query_id = $this->query_result;
			}
	
			if ( $query_id ) {
				unset($this->row[$query_id]);
				unset($this->rowset[$query_id]);
	
				@mysql_free_result($query_id);
	
				return true;
			} else {
				return false;
			}
		}
		function sql_error($query_id = 0) {
			$result["message"] = @mysql_error($this->db_connect_id);
			$result["code"] = @mysql_errno($this->db_connect_id);
	
			return $result;
		}
	
	} 
	// End DATABASE CLASS sql_db
} 
// End DEFINED SQL_LAYER

$db = new sql_db($dbhost, $dbuname, $dbpass, $dbname, false);
!$db->db_connect_id ? $MYSQLDB_ERROR = true : $MYSQLDB_ERROR = false;

?>
