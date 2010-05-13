<?php
/**************************************************************************
    FILENAME        :   db.php
    PURPOSE OF FILE :   Database class
    LAST UPDATED    :   15 January 2005
    COPYRIGHT       :   � 2005 CMScout Group
    WWW             :   www.cmscout.za.org
    LICENSE         :   GPL vs2.0
    
    

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/
?>
<?php
 class database {
 	var $dbname;
	var $dbhost;
	var $dbusername;
	var $dbpassword;
    var $dbprefix;
    var $dbport;
	var $busy = false; 
	var $connection;
	var $queryresult = 0;
    var $counter = 0;
    
    function reset_counter()
    {
        $this->counter = 0;
    }

    function get_counter()
    {
        return $this->counter;
    }
	
	function database($dbname, $dbhost, $dbusername, $dbpassword, $dbprefix, $dbport=0) 
    {
		$this->dbname = $dbname;
		$this->dbhost = $dbhost;
		$this->dbusername = $dbusername;
		$this->dbpassword = $dbpassword;
        $this->dbprefix = $dbprefix;
        $this->dbport = $dbport;
        if ($this->dbport != "" && $this->dbport != 0)
        {
            $server = $this->dbhost . ":" . $this->dbport;
        }
        else
        {
            $server = $this->dbhost;
        }
		$this->connection = mysql_connect($server, $this->dbusername, $this->dbpassword);
		$this->SelectedDB = mysql_select_db($this->dbname);

		if ($this->connection && $this->SelectedDB) 
        {
			return $this->connection;
		} 
        else 
        {
			return 0;
		}
	} //database
	
	function closedatabase() 
    {
	}//closedatabase
	
	function select_query($tablename="", $special = false, $field="*", $join = array())
    {
		global $check, $config, $debug;
        
		if ($this->queryresult != 0) 
        {
			$this->queryresult = 0;
		}
        
        
		$sql = "SELECT $field FROM {$this->dbprefix}$tablename AS $tablename ";
		if(!empty($join)) {
			foreach($join as $joinType => $joinDetails) {
				$joinTable = end(array_keys($joinDetails));
				$joinConditions = end($joinDetails);
				$sql .= $joinType . ' join ' . $this->dbprefix.$joinTable . ' as ' . $joinTable . ' on (' . $joinConditions . ') ';
			}
		}

		if ($special) 
        {
			$sql = $sql . $special;
		}
		if ($config["debug"] == "true") 
        {
			$debug .= $sql . "<br>";
		}

		$query = mysql_query($sql);

		if ($query) 
        {
			$this->queryresult = $query;
            $this->counter++;
			return $this->queryresult;
		} 
        else 
        {
            $error = mysql_error();
            if ($error == "Table '{$this->dbname}.{$this->dbprefix}$tablename' doesn't exist")
            {              
               trigger_error($error, E_USER_ERROR);
            }
            error_message("Database Error, please try again or contact the administrator", "Database error with statement $sql. Error was: $error");
			return false;
		}
	}//select_query
	
    function select_fetch_one_row() 
    {
        global $check, $config, $debug;

		$query = call_user_func_array(array($this, 'select_query'), func_get_args());

		if ($query) 
        {
			$this->queryresult = $query;
            $this->counter++;
			return mysql_fetch_array($this->queryresult);
		} 
        else 
        {
            error_message("Database Error, please try again or contact the administrator", "Database error with statement $sql. Error was: " . mysql_error());
			return false;
		}
    }//select_fetch
    
    function select_fetch_all_rows(&$numrows) 
    {
        global $check, $config, $debug;
        
		$args = func_get_args();
		unset($args[0]);
		$query = call_user_func_array(array($this, 'select_query'), $args);
		
		if ($query) 
        {
			$this->queryresult = $query;
            $this->counter++;
            $numrows = $this->num_rows($query);
            $return = array();
            while ($return[] = $this->fetch_array($query));

			return $return;
		} 
        else 
        {
            error_message("Database Error, please try again or contact the administrator", "Database error with statement $sql. Error was: " . mysql_error());
			return false;
		}
    }//select_fetch
    
	function insert_query($tablename, $values, $page=false, $desc=false, $log=false) 
    {
		global $tree, $check, $config, $debug;
        
		$query = "";
		if ($this->queryresult != 0) 
        {
			$this->queryresult = 0;
		}	
		$sql = "INSERT INTO {$this->dbprefix}$tablename VALUES ($values)";
		if ($config["debug"] == "true") 
        {
			$debug =  $sql . '<br>';
		}
		$query .= mysql_query($sql);

		if ($query) 
        {
			$this->queryresult = $query;
            $this->counter++;
			return mysql_insert_id();
		} 
        else
        {
            error_message("Database Error, please try again or contact the administrator", "Database error with statement $sql. Error was: " . mysql_error());
			return false;
		}
	}//insert_query
	
	function delete_query($tablename, $where=false, $page=false, $desc=false, $log=false) 
    {
		global $tree, $check, $config, $debug;
		
		if ($this->queryresult != 0) 
        {
			$this->queryresult = 0;
		}
		$sql = "DELETE FROM {$this->dbprefix}$tablename";
		if ($where) {
			$sql = $sql . " WHERE " . $where;
		}
		if ($config["debug"] == "true") 
        {
			$debug .=  $sql . "<br>";
		}
		$query = mysql_query($sql);
		if ($query) 
        {
			$this->queryresult = $query;
            $this->counter++;
			return $this->queryresult;
		} 
        else 
        {
            error_message("Database Error, please try again or contact the administrator", "Database error with statement $sql. Error was: " . mysql_error());
			return false;
		}
	}//delete_query
	
	function update_query($tablename, $set, $where=false, $page=false, $desc=false, $log=false) 
    {
		global $tree, $check, $config, $debug;
		
		if ($this->queryresult != null) 
        {
			$this->queryresult = null;
		}
		$sql = "UPDATE {$this->dbprefix}$tablename SET $set";
		if ($where) 
        {
			$sql = $sql . " WHERE " . $where;
		}
		if ($config["debug"] == "true")
        {
			$debug .=  $sql . "<br>";
		}
		$query = mysql_query($sql);
        if ($query) 
        {
			$this->queryresult = $query;
            $this->counter++;
			return $this->queryresult;
		} 
        else 
        {
            error_message("Database Error, please try again or contact the administrator", "Database error with statement $sql. Error was: " . mysql_error());
			return false;
		}
	}//update_query
	
	function fetch_array($query=false) 
    {
		if ($query) 
        {
			$array = mysql_fetch_array($query);
			if (isset($array)) 
            {
				return $array;
			}
            else 
            {
                error_message("Database Error, please try again or contact the administrator", "Database error with fetch. Error was: " . mysql_error());
				return false;
			}
		} 
        else 
        {
			$array = mysql_fetch_array($this->queryresult);
			if (isset($array)) 
            {
				return $array;
			} 
            else 
            {
                error_message("Database Error, please try again or contact the administrator", "Database error with fetch. Error was: " . mysql_error());
				return false;
			}
		}
	}//fetchassoc
	
	function num_rows($query=null) 
    {
		if($query === false) {
			$query = null;
		}
		
		if ($query) {
			$number = mysql_num_rows($query);
			if (isset($number)) 
            {
				return $number;
			} 
            else
            {
    			error_message("Database Error, please try again or contact the administrator", "Database error with number of rows. Error was: " . mysql_error());
				return false;
			}
		} elseif ($this->queryresult) {
			$number =  mysql_num_rows($this->queryresult);
			if (isset($number)) 
            {
				return $number;
			} 
            else 
            {
    			error_message("Database Error, please try again or contact the administrator", "Database error with number of rows. Error was: " . mysql_error());
				return false;
			}
		}
		
		return 0;
	}//numrows
	
	function free_result($query=false) 
    {
		if ($query) 
        {
			$free = mysql_free_result($query);
			if ($free) 
            {
				return true;
			} 
            else 
            {
				return false;
			}
		} 
        else
        {
			$free =  mysql_free_result($this->queryresult);
			if ($free) 
            {
				return true;
			} 
            else 
            {
				return false;
			}
		}
	}//free_result
	
}
?>