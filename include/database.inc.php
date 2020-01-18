<?php

if (!IsSet($GLOBALS['DATABASE_INCLUDED']))
{
	$GLOBALS['DATABASE_INCLUDED'] = 1;
		
	class DB
	{
		var $conn = null;
		var $data_retrieved;
		var $rows;
		var $row;
		
		function ClearCache()
		{
			$data_retrieved = null;
			$rows = null;
			$row = null;
		}
		
		function ShowError()
		{
			$l = MaakObject('Login');
			if ($l->isLocal())		return true;
			if ($l->isBeheerder())	return true;
			if ($l->isSync())		return true;
			
			return false;
		}

		function Connect($conn = '')
		{
			global $db_info;

			if ($conn == '')
			{
				if (!IsSet($GLOBALS['__DB_CONN__']))
				{
					$dsn = sprintf("%s:host=%s;dbname=%s", 			$db_info['dbType'],
																	$db_info['dbHost'],
																	$db_info['dbName']);
					$this->conn = new PDO(
						$dsn, $db_info['dbUser'], $db_info['dbPassword'], 
						array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
					$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					
					$GLOBALS['__DB_CONN__'] = $this->conn;
				}
				else
					$this->conn = $GLOBALS['__DB_CONN__'];
			}
			else
				$this->conn = $conn;

			return $this->conn;
		}
		
		function DbOpvraag($query, $params = null)
		{
			global $app_settings;
			
			if ($app_settings['DbLogging'])
			{
				if ($app_settings['LogDir'] == "syslog")
				{
					error_log($query);
					if ($params != null)
						error_log(print_r($params, true), 3);				
				}
				else
				{
					error_log($query  . "\n", 3, $app_settings['LogDir'] . "sql.txt");	
					if ($params != null)
						error_log(print_r($params, true), 3, $app_settings['LogDir'] . "sql.txt");
					
				}
			}

			$this->Record = array();

			if (!$this->conn)
				$this->Connect();
			
			if ($this->conn)
			{
				try
				{
					$sth = $this->conn->prepare($query);
					if ($params == null)
						$sth->execute();
					else
					{
						$sth->execute($params);
					}
						
					$this->data_retrieved = $sth->fetchAll(PDO::FETCH_ASSOC);
					$this->rows = $sth->rowCount();
					$this->row = -1;
				}
				catch (PDOException $e) 
				{
					header('X-Error-Message: Incorrect query', true, 500);
					header("Content-Type: text/plain");
					if ($this->ShowError())
					{	
						echo 'PDO exception: ' . $e->getMessage();
						echo '\n';
						echo $query;
					}
					else
					{
						echo "Fatale fout opgetreden bij opvragen informatie.";
					}
					
					if ($app_settings['DbError'])
					{
						if ($app_settings['LogDir'] == "syslog")
						{
							error_log(date("Y-m-d H:i:s") . ":" . $query  . "\n" . $e->getMessage());
						}
						else
						{
							error_log(date("Y-m-d H:i:s") . ":" . $query  . "\n" . $e->getMessage() . "\n", 3, $app_settings['LogDir'] . "error.txt");
						}
					}
					die;
				}
			}
		}
	
		
		function DbUitvoeren($query)
		{
			global $app_settings;
			$rowcount = -1;
			
			if (!$this->conn)
				$this->Connect();
				
			if ($this->conn)
			{
				if ($app_settings['DbLogging'])
				{
					if ($app_settings['LogDir'] == "syslog")
					{
						error_log($query);
					}
					else
					{
						error_log($query  . "\n", 3, $app_settings['LogDir'] . "exec.txt");
					}
				}
				
				try
				{
					$rowcount = $this->conn->exec($query);		// return number of rows affected
				}
				catch (PDOException $e) 
				{
					header('X-Error-Message: Incorrect query', true, 500);
					header("Content-Type: text/plain");
					if ($this->ShowError())
					{	
						echo 'PDO exception: ' . $e->getMessage();
						echo '\n';
						echo $query;
					}
					else
					{
						echo "Fatale fout opgetreden bij uitvoeren opdracht.";
					}
					
					if ($app_settings['DbError'])
					{
						if ($app_settings['LogDir'] == "syslog")
						{
							error_log(date("Y-m-d H:i:s") . ":" . $query  . "\n" . $e->getMessage());
						}
						else
						{
							error_log(date("Y-m-d H:i:s") . ":" . $query  . "\n" . $e->getMessage() . "\n", 3, $app_settings['LogDir'] . "error.txt");	
						}
					}						
					die;
				}
				$this->ClearCache();
				return $rowcount;
			}
			else
				return -1;
		}
		
		function Data()
		{
			return $this->data_retrieved;
		}
	
		function DbToevoegen($table, $array)
		{
			global $app_settings;
			
			$fields = "";
			$values = "";
			
			foreach ($array as $field => $value)
			{
				$field = str_replace("'","''", $field);
				
				if ($fields == "")
					$fields = sprintf("%s", $field);
				else
					$fields = sprintf("%s,%s", $fields, $field);
					
				if ($values == "")
				{
					if ($value == null)
						$values = sprintf("NULL");
					elseif (is_numeric($value))
						$values = sprintf("%s", $value);
					else
						$values = sprintf("'%s'", $value);
				}
				else
				{
					if (is_numeric($value))
						$values = sprintf("%s,%s", $values, $value);
					elseif ($value == null)
						$values = sprintf("%s,NULL", $values, $value);
					else
						$values = sprintf("%s,'%s'", $values, str_replace("'","\'",$value));
				}
			}
			$query = sprintf("INSERT INTO %s (%s) VALUES (%s);", $table, $fields, $values);

			if ($app_settings['DbLogging'])
			{
				if ($app_settings['LogDir'] == "syslog")
				{
					error_log($query);
				}
				else
				{				
					error_log($query  . "\n", 3, $app_settings['LogDir'] . "insert.txt");
				}
			}


			if (!$this->conn)
				$this->Connect();
				
			if ($this->conn)
			{
				try
				{
					$sth = $this->conn->prepare($query);
					$sth->execute();
				}
				catch (PDOException $e) 
				{
					header('X-Error-Message: Incorrect query', true, 500);
					header("Content-Type: text/plain");
					if ($this->ShowError())
					{		
						echo 'PDO exception: ' . $e->getMessage();
						echo '\n';
						echo $query;
					}
					else
					{
						echo "Fatale fout opgetreden bij toevoegen data.";
					}
					if ($app_settings['DbError'])
					{
						if ($app_settings['LogDir'] == "syslog")
						{
							error_log(date("Y-m-d H:i:s") . ":" . $query  . "\n" . $e->getMessage());
						}
						else
						{						
							error_log(date("Y-m-d H:i:s") . ":" . $query  . "\n" . $e->getMessage() . "\n", 3, $app_settings['LogDir'] . "error.txt");
						}
					}						
					die;
				}
				
				$lastid = $this->conn->lastInsertId();		// return inserted ID
				
				if ($app_settings['DbLogging'])
				{
					if ($app_settings['LogDir'] == "syslog")
					{
						error_log("ID=" . $lastid);
					}
					else
					{
						error_log("ID=" . $lastid  . "\n", 3, $app_settings['LogDir'] . "insert.txt");
					}
				}
								
				$this->ClearCache();
				return $lastid;
			}
		}

		function DbAanpassen($table, $ID, $array)
		{
			global $app_settings;
		
			$fields = "";
			$retval = false;
			
			foreach ($array as $field => $value)
			{
				$field = str_replace("'","''", $field);
			
				if ($fields == "")
				{
					if ($value == null)
						$fields = sprintf("%s=NULL", $field);
					elseif (is_numeric($value))
						$fields = sprintf("%s=%s", $field, $value);
					else
						$fields = sprintf("%s='%s'", $field, str_replace("'","\'",$value));
				}
				else
				{
					if (is_numeric($value))
							$fields = sprintf("%s,%s=%s", $fields, $field, $value);
					elseif ($value == null)
						$fields = sprintf("%s,%s=NULL", $fields, $field);
					else
						$fields = sprintf("%s,%s='%s'", $fields, $field, str_replace("'","\'",$value));
				}
				
			}
			if (is_numeric($ID))
				$query = sprintf("UPDATE %s SET %s WHERE ID=%s;", $table, $fields, $ID);
			else
				$query = sprintf("UPDATE %s SET %s WHERE ID='%s';", $table, $fields, $ID);
			
			if ($app_settings['DbLogging'])
			{
				if ($app_settings['LogDir'] == "syslog")
				{
					error_log($query);
				}
				else
				{
					error_log($query  . "\n", 3, $app_settings['LogDir'] . "update.txt");
				}
			}

			if (!$this->conn)
				$this->Connect();
				
			if ($this->conn)
			{
				try
				{
					$sth = $this->conn->prepare($query);
					$retval = $sth->execute();
					$this->rows = $sth->rowCount();
				}
				catch (PDOException $e) 
				{
					header('X-Error-Message: Incorrect query', true, 500);
					header("Content-Type: text/plain");
					if ($this->ShowError())
					{	
						echo 'PDO exception: ' . $e->getMessage();
						echo '\n';
						echo $query;
					}
					else
					{
						echo "Fatale fout opgetreden bij aanpassen data.";
					}
					
					if ($app_settings['DbError'])
					{
						if ($app_settings['LogDir'] == "syslog")
						{
							error_log(date("Y-m-d H:i:s") . ":" . $query  . "\n" . $e->getMessage());
						}
						else
						{	
							error_log(date("Y-m-d H:i:s") . ":" . $query  . "\n" . $e->getMessage() . "\n", 3, $app_settings['LogDir'] . "error.txt");	
						}
					}						
					die;
				}
				return $retval;
			}
		}

		
		function BeginTransaction()
		{
			if (!$this->conn)
				$this->Connect();
				
			return $this->conn->beginTransaction();
		}

		function EndTransaction()
		{
			if (!$this->conn)
				$this->Connect();
				
			return $this->conn->commit();
		}

		function RollbackTransaction()
		{
			if (!$this->conn)
				$this->Connect();
				
			return $this->conn->rollBack();
		}

		function NumRows()
		{
			return $this->rows;
		}

		function NumFields()
		{
			if (!$this->data_retrieved)
				return 0;
			if (!$this->data_retrieved[0])
				return 0;
				
			return count($this->data_retrieved[0]);
		}
	}
}
?>
