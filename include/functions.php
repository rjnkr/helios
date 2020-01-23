<?php

// Common definitions
define('phpCrLf', "\r\n");
define('phpTab', "\t");

	// Instanteer een class, maar laad het php bestand eerst
	if (!function_exists('MaakObject'))
	{
		function MaakObject($className)
		{
			$includedChk = sprintf('%s_PHP_INCLUDED', strtoupper($className));
			if (!IsSet($GLOBALS[$includedChk]))
			{
				include_once('lib/class.' . $className . '.inc.php');
				$GLOBALS[$includedChk] = 1;
			}
			$obj = new $className;
			return $obj;
		}
	}

	// De debug functie, schrijft niets als de globale setting UIT staat
	if (!function_exists('Debug'))
	{
		function Debug($file, $line, $text)
		{
			global $app_settings;
						
			if ($app_settings['Debug'])
			{
				$arrStr = explode("/", $file); 
				$arrStr = array_reverse($arrStr );
				$arrStr = explode("\\", $arrStr[0]);
				$arrStr = array_reverse($arrStr );
				
				if ($app_settings['LogDir'] == "syslog")
				{
					error_log(sprintf("%s: %s (%d), %s\n", date("Y-m-d H:i:s"), $arrStr[0], $line, $text));
				}
				else
				{	
					error_log(sprintf("%s: %s (%d), %s\n", date("Y-m-d H:i:s"), $arrStr[0], $line, $text), 3, $app_settings['LogDir'] . "debug.txt");
				}
			}
		}
	}

	// Is de waarde een CSV string met integers
	function isCSV($value)
	{
		if (strpos($value, ',') !== false)
		{
			foreach (explode(",", $value) as $field)
			{
				if (filter_var($field, FILTER_VALIDATE_INT) === false)
					return false;
			}
		}
		else
		{
			if (filter_var($value, FILTER_VALIDATE_INT) === false)
				return false;
		}
		return $value;
	}

	// Is de waarde een integer
	function isINT($value)
	{
		if (filter_var($value, FILTER_VALIDATE_INT) === false)
			return false;

		return intval($value);
	}

	// Is de waarde een datetime
	function isDATETIME($value)
	{
		try 
		{
			$datetime = new DateTime($value);
		}
		catch (Exception $ex) 
		{ 
			return false; 
		}
			
		$datetime->setTimeZone(new DateTimeZone('Europe/Amsterdam'));  
		return $datetime;
	}

	// Is de waarde een date
	function isDATE($value)
	{			
		if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$value))
			return false;
		
		$checkdate = explode('-', $value);

		if (checkdate($checkdate[1], $checkdate[2], $checkdate[0]) === false)
			return false;
	
		return DateTime::createFromFormat("Y-m-d", $value);
	}

	// Is de waarde een time
	function isTIME($value)
	{			
		if (!preg_match("/^([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/",$value))
			return false;
		
		return $value;
	}

	// Is de waarde een boolean
	function isBOOL($value)
	{
		if (($value != "0") && ($value != "1") && ($value != "false") && ($value != "true"))
			return false;

		if (($value == "0") || ($value == "false"))
			return 0; 
		else
			return 1; 
	}
?>
