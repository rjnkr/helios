<?php
	class Vliegtuigen extends StartAdmin
	{
		function __construct() 
		{
			parent::__construct();
			$this->dbTable = "ref_vliegtuigen";
		}
		
		/*
		Aanmaken van de database tabel. Indien FILLDATA == true, dan worden er ook voorbeeld records toegevoegd 
		*/		
		function CreateTable($FillData)
		{
			$query = sprintf ("
				CREATE TABLE `%s` (
					`ID` mediumint UNSIGNED NOT NULL AUTO_INCREMENT,
					`REGISTRATIE` varchar(8) NOT NULL,
					`CALLSIGN` varchar(6) DEFAULT NULL,
					`ZITPLAATSEN` tinyint UNSIGNED NOT NULL DEFAULT '1',
					`CLUBKIST` tinyint UNSIGNED NOT NULL DEFAULT '0',
					`FLARMCODE` varchar(6) DEFAULT NULL,
					`TYPE_ID` mediumint UNSIGNED DEFAULT NULL,
					`TMG` tinyint UNSIGNED NOT NULL DEFAULT '0',
					`ZELFSTART` tinyint UNSIGNED NOT NULL DEFAULT '0',
					`SLEEPKIST` tinyint UNSIGNED NOT NULL DEFAULT '0',
					`VOLGORDE` tinyint UNSIGNED DEFAULT NULL,
					`VERWIJDERD` tinyint UNSIGNED NOT NULL DEFAULT '0',
					`LAATSTE_AANPASSING` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					
					CONSTRAINT ID_PK PRIMARY KEY (ID),	
						INDEX (`REGISTRATIE`), 
						INDEX (`CALLSIGN`), 
						INDEX (`CLUBKIST`), 
						INDEX (`TYPE_ID`), 
						INDEX (`VOLGORDE`), 
						INDEX (`VERWIJDERD`),
						
					FOREIGN KEY (TYPE_ID) REFERENCES ref_types(ID)
				)", $this->dbTable);
			parent::DbUitvoeren($query);
			

			if (boolval($FillData))
			{
				$query = sprintf("
					INSERT INTO 
						`%s` 
							(`ID`, 
							`REGISTRATIE`, 
							`CALLSIGN`, 
							`ZITPLAATSEN`, 
							`CLUBKIST`, 
							`FLARMCODE`, 
							`TYPE_ID`, 
							`TMG`, 
							`ZELFSTART`, 
							`SLEEPKIST`, 
							`VOLGORDE`, 
							`VERWIJDERD`) 
						VALUES
							(200, 'PH-1529', 	'E12', 	2, 1, '485069', 405,  0, 0, 0, 9, 0),
							(201, 'PH-1623', 	'E8', 	1, 1, NULL, 	404,  0, 0, 0, 8, 0),
							(208, 'D-KARC', 	'BRC', 	2, 0, NULL, 	NULL, 0, 0, 0, 0, 0),
							(209, 'D-KDIX', 	'IIX', 	2, 0, NULL, 	NULL, 0, 0, 0, 0, 0),
							(210, 'PH-614', 	'WM', 	1, 0, 'DDE299', NULL, 0, 0, 0, 0, 0),
							(211, 'D-KLUU', 	'7U', 	1, 0, 'DDBBBE', NULL, 0, 0, 0, 0, 0),
							(212, 'PH-KPZP', 	'ZP', 	2, 0, NULL, 	NULL, 0, 0, 0, 0, 0),
							(213, 'D-KRHT', 	'HT', 	1, 0, 'DD1534', NULL, 0, 1, 0, 0, 0),
							(214, 'D-KLLA', 	'LL', 	2, 0, '3EC9F2', NULL, 0, 0, 0, 0, 0),
							(215, 'D-KTXO', 	'X0', 	1, 0, NULL, 	NULL, 0, 0, 0, 0, 1),
							(216, 'PH-YLB', 	'YLB', 	2, 0, NULL, 	NULL, 0, 0, 0, 0, 0),
							(217, 'D-KNWW', 	'KW', 	1, 0, 'DDBC8A', NULL, 0, 0, 0, 0, 0),
							(218, 'PH-ELT', 	'ELT', 	2, 0, '484728', NULL, 0, 0, 1, 0, 0);", $this->dbTable);
				parent::DbUitvoeren($query);
			}
		}

		/*
		Maak database views, als view al bestaat wordt deze overschreven
		*/		
		function CreateViews()
		{
			parent::DbUitvoeren("DROP VIEW IF EXISTS vliegtuigen_view");
			$query =  sprintf("CREATE VIEW `vliegtuigen_view` AS
				SELECT 
					v.*,
					CONCAT(IFNULL(`v`.`REGISTRATIE`,''),' (',IFNULL(`v`.`CALLSIGN`,''),')') AS `REGCALL`,
					`t`.`OMSCHRIJVING` AS `VLIEGTUIGTYPE`
				FROM
					`%s` `v`    
					LEFT JOIN `ref_types` `t` ON (`v`.`TYPE_ID` = `t`.`ID`)
				WHERE
					`v`.`VERWIJDERD` = 0;", $this->dbTable);			
			parent::DbUitvoeren($query);
		}

		/*
		Haal een enkel record op uit de database
		*/
		function GetObject($ID,  $heeftVerwijderd = true)
		{
			Debug(__FILE__, __LINE__, sprintf("Vliegtuigen.GetObject(%s)", $ID));	

			if ($ID == null)
				throw new Exception("406;Geen ID in aanroep;");

			if (isINT($ID) === false)
				throw new Exception("405;ID moet een integer zijn;");

			$conditie = array();
			$conditie['ID'] = $ID;

			if ($heeftVerwijderd == false)
				$conditie['VERWIJDERD'] = 0;		// Dus geen verwijderd record

			$obj = parent::GetSingleObject($conditie);
			if ($obj == null)
				throw new Exception("404;Record niet gevonden;");
			
			return $obj;
		}

		/*
		Haal een enkel record op uit de database
		*/
		function GetObjectByRegistratie($Registratie)
		{
			Debug(__FILE__, __LINE__, sprintf("Vliegtuigen.GetObject(%s)", $ID));	

			if ($LidNr == null)
				throw new Exception("406;Geen Registratie in aanroep;");

			$query = sprintf("
				SELECT
					*
				FROM
					%s
				WHERE
					REGISTRATIE = :REGISTRATIE AND VERWIJDERD=0", $this->dbTable);

			$conditie = array();
			$conditie[':REGISTRATIE'] = $Registratie;

			$obj = parent::DbOpvraag($query, $conditie);

			if ($obj == null)
				throw new Exception("404;Record niet gevonden;");
			
			return $obj[0];				
		}			
	
		/*
		Haal een dataset op met records als een array uit de database. 
		*/		
		function GetObjects($params)
		{
			$functie = "Vliegtuigen.GetObjects";
			Debug(__FILE__, __LINE__, sprintf("%s(%s)", $functie, print_r($params, true)));		
			
			$where = ' WHERE 1=1 ';
			$orderby = " ORDER BY CLUBKIST DESC, VOLGORDE, REGISTRATIE";
			$alleenLaatsteAanpassing = false;
			$limit = -1;
			$start = -1;
			$velden = "*";
			$query_params = null;

			foreach ($params as $key => $value)
			{
				switch ($key)
				{
					case "LAATSTE_AANPASSING" : 
						{
							if (false === $alleenLaatsteAanpassing = isBOOL($value))
								throw new Exception("405;LAATSTE_AANPASSING moet een boolean zijn;");

							$alleenLaatsteAanpassing = ($alleenLaatsteAanpassing === 0) ? false : true;	
							Debug(__FILE__, __LINE__, sprintf("%s: LAATSTE_AANPASSING='%s'", $functie, $alleenLaatsteAanpassing));
							break;
						}	
					case "VELDEN" : 	
						{
							if (strpos($value,';') !== false)
								throw new Exception("405;VELDEN is onjuist;");

							$velden = $value;
							Debug(__FILE__, __LINE__, sprintf("%s: VELDEN='%s'", $functie, $velden));
							break;
						}											
					case "SORT" : 
						{
							if (strpos($value,';') !== false)
								throw new Exception("405;SORT is onjuist;");
							
							$orderby = sprintf(" ORDER BY %s ", $value);
							Debug(__FILE__, __LINE__, sprintf("%s: SORT='%s'", $functie, $value));
							break;
						}
					case "START" : 
						{
							if (false === $s = isINT($value))
								throw new Exception("405;START moet een integer zijn;");
							
							if ($s < 1)
								throw new Exception("405;START groter of gelijk zijn dan 1;");
							
							$start = $s;

							Debug(__FILE__, __LINE__, sprintf("%s: START='%s'", $functie, $start));
							break;
						}	
					case "MAX" : 
						{
							if (false === $l = isINT($value))
								throw new Exception("405;LIMIT moet een integer zijn;");

							if ($l > 0)
							{
								$limit = $l;
								Debug(__FILE__, __LINE__, sprintf("%s: LIMIT='%s'", $functie, $limit));
							}
							break;
						}					
					case "SELECTIE" : 
						{
							$where .= " AND ((REGISTRATIE LIKE ?) ";
							$where .= "  OR (CALLSIGN LIKE ?) ";
							$where .= "  OR (FLARMCODE LIKE ?)) ";

							$s = "%" . trim($value) . "%";
							$query_params = array($s, $s, $s);

							Debug(__FILE__, __LINE__, sprintf("%s: SELECTIE='%s'", $functie, $value));	
							break;
						}
					case "IN" : 
						{
							if (isCSV($value) === false)
								throw new Exception("405;ID moet een integer zijn in de IN parameter;");
							
							$where .= sprintf(" AND ID IN(%s)", trim($value));

							Debug(__FILE__, __LINE__, sprintf("%s: IN='%s'", $functie, $value));
							break;
						}
					case "TYPES" : 
						{
							if (isCSV($value) === false)
								throw new Exception("405;ID moet een integer zijn in de TYPES parameter;");
							
							$where .= sprintf(" AND TYPE_ID IN(%s)", trim($value));

							Debug(__FILE__, __LINE__, sprintf("%s: TYPES='%s'", $functie, $value));
							break;
						}	
					case "ZITPLAATSEN" : 
						{
							if (false === $zitplaatsen = isINT($value))
								throw new Exception("405;ZITPLAATSEN moet een integer zijn;");

							if (($zitplaatsen < 1) || ($zitplaatsen > 2))
								throw new Exception("405;ZITPLAATSEN moet een 1 of 2 zijn;");
							
							$where .= sprintf(" AND ZITPLAATSEN=%d",  $zitplaatsen);	
							
							Debug(__FILE__, __LINE__, sprintf("%s: ZITPLAATSEN='%s'", $functie, $value));
							break;
						}	
					case "CLUBKIST" : 
						{
							if (isBOOL($value) === false)
								throw new Exception("405;CLUBKIST is geen boolean;");

							$where .= sprintf(" AND CLUBKIST=%d", boolval($value));

							Debug(__FILE__, __LINE__, sprintf("%s: CLUBKIST='%s'", $functie, $value));
							break;
						}		
					case "ZELFSTART" : 
						{
							if (isBOOL($value) === false)
								throw new Exception("405;ZELFSTART is geen boolean;");
							
							$where .= sprintf(" AND ZELFSTART=%d", boolval($value));

							Debug(__FILE__, __LINE__, sprintf("%s: ZELFSTART='%s'", $functie, $value));
							break;
						}			
					case "SLEEPKIST" : 
						{
							if (isBOOL($value) === false)
								throw new Exception("405;SLEEPKIST is geen boolean;");
							
							$where .= sprintf(" AND SLEEPKIST=%d", boolval($value));

							Debug(__FILE__, __LINE__, sprintf("%s: SLEEPKIST='%s'", $functie, $value));
							break;
						}		
					case "TMG" : 
						{
							if (isBOOL($value) === false)
								throw new Exception("405;TMG is een boolean;");
							
							$where .= sprintf(" AND TMG=%d", boolval($value));

							Debug(__FILE__, __LINE__, sprintf("%s: TMG='%s'", $functie, $value));
							break;
						}																													
				}
			}
				
			$query = "
				SELECT 
					%s
				FROM
					`vliegtuigen_view`" . $where . $orderby;
			
			$retVal = array();

			$retVal['totaal'] = $this->Count($query, $query_params);		// total amount of records in the database
			$retVal['laatste_aanpassing']=  $this->LaatsteAanpassing($query, $query_params);
			Debug(__FILE__, __LINE__, sprintf("TOTAAL=%d, LAATSTE_AANPASSING=%s", $retVal['totaal'], $retVal['laatste_aanpassing']));	

			if ($alleenLaatsteAanpassing)
			{
				$retVal['dataset'] = null;
				return $retVal;
			}
			else
			{			
				if ($limit > 0)
				{
					if ($start < 0)				// Is niet meegegeven, dus start op 0
						$start = 0;

					$query .= sprintf(" LIMIT %d , %d ", $start, $limit);
				}			
				$rquery = sprintf($query, $velden);
				parent::DbOpvraag($rquery, $query_params);
				$retVal['dataset'] = parent::DbData();

				return $retVal;
			}
			return null;  // Hier komen we nooit :-)
		}	

		/*
		Markeer een record in de database als verwijderd. Het record wordt niet fysiek verwijderd om er een link kan zijn naar andere tabellen.
		Het veld VERWIJDERD wordt op "1" gezet.
		*/
		function VerwijderObject($ID)
		{
			Debug(__FILE__, __LINE__, sprintf("Vliegtuigen.VerwijderObject(%s)", $ID));				
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)
				throw new Exception("401;Geen schrijfrechten;");

			if ($ID == null)
				throw new Exception("406;Geen ID in aanroep;");
			
			if (isINT($ID) === false)
				throw new Exception("405;ID moet een integer zijn;");
						
			parent::MarkeerAlsVerwijderd($ID);
			if (parent::NumRows() === 0)
				throw new Exception("404;Record niet gevonden;");	
		}		
		
		/*
		Toevoegen van een record. Het is niet noodzakelijk om alle velden op te nemen in het verzoek
		*/		
		function AddObject($VliegtuigData)
		{
			Debug(__FILE__, __LINE__, sprintf("Vliegtuigen.AddObject(%s)", print_r($VliegtuigData, true)));
			
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)	
				throw new Exception("401;Geen schrijfrechten;");

			if ($VliegtuigData == null)
				throw new Exception("406;Vliegtuig data moet ingevuld zijn;");			

			if (array_key_exists('ID', $VliegtuigData))
			{
				if (false === $id = isINT($VliegtuigData['ID']))
					throw new Exception("405;ID moet een integer zijn;");
				
				// ID is opgegeven, maar bestaat record?
				try 	// Als record niet bestaat, krijgen we een exception
				{	
					$this->GetObject($id, false);
				}
				catch (Exception $e) {}	

				if (parent::NumRows() > 0)
					throw new Exception(sprintf("409;Record met ID=%s bestaat al;", $id));									
			}
			
			// Voorkom het dezelfde lidnr meerdere keren voorkomt in de tabel
			if (array_key_exists('REGISTRATIE', $VliegtuigData))
			{
				if ($VliegtuigData['REGISTRATIE'] != null)	// null is altijd goed
				{
					try 	// Als record niet bestaat, krijgen we een exception
					{				
						$this->GetObjectByRegistratie($VliegtuigData['REGISTRATIE']);
					}
					catch (Exception $e) {}	

					if (parent::NumRows() > 0)
						throw new Exception(sprintf("409;Vliegtuig met Registratie=%s bestaat al;", $VliegtuigData['REGISTRATIE']));
				}
			}

			if (!array_key_exists('REGISTRATIE', $VliegtuigData))
				throw new Exception("406;REGISTRATIE is verplicht;");
							
			// Neem data over uit aanvraag
			$v = $this->RequestToRecord($VliegtuigData);
						
			$id = parent::DbToevoegen($v);
			Debug(__FILE__, __LINE__, sprintf("Vliegtuig toegevoegd id=%d", $id));

			return $this->GetObject($id);
		}

		/*
		Toevoegen van een record. Het is niet noodzakelijk om alle velden op te nemen in het verzoek
		*/		
		function UpdateObject($VliegtuigData)
		{
			Debug(__FILE__, __LINE__, sprintf("Vliegtuigen.UpdateObject(%s)", print_r($VliegtuigData, true)));
			
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)	
				throw new Exception("401;Geen schrijfrechten;");

			if ($VliegtuigData == null)
				throw new Exception("406;Vliegtuig data moet ingevuld zijn;");			

			if (!array_key_exists('ID', $VliegtuigData))
				throw new Exception("406;ID moet ingevuld zijn;");

			if (false === $id = ($VliegtuigData['ID']))
				throw new Exception("405;ID moet een integer zijn;");
				
            // Voorkom dat datum meerdere keren voorkomt in de tabel
			if (array_key_exists('REGISTRATIE', $VliegtuigData))
			{
				try 	// Als record niet bestaat, krijgen we een exception
				{
					$l = $this->GetObjectByRegistratie($VliegtuigData['REGISTRATIE']);
				}
				catch (Exception $e) {}

				if (parent::NumRows() > 0)
				{
					if ($VliegtuigData['ID'] != $l['ID'])
						throw new Exception("409;Registratie bestaat reeds;");
				}					
			}

			// Neem data over uit aanvraag
			$v = $this->RequestToRecord($VliegtuigData);

			parent::DbAanpassen($id, $v);
			if (parent::NumRows() === 0)
				throw new Exception("404;Record niet gevonden;");				
			
			return $this->GetObject($id);
		}

		/*
		Copieer data van request naar velden van het record 
		*/
		function RequestToRecord($input)
		{
			$record = array();

			if (array_key_exists('ID', $input))
				$record['ID'] = $input['ID']; 

			if (array_key_exists('REGISTRATIE', $input))
				$record['REGISTRATIE'] = $input['REGISTRATIE']; 

			if (array_key_exists('ZITPLAATSEN', $input))
			{
				if (false === $record['ZITPLAATSEN'] = isINT($input['ZITPLAATSEN']))
					throw new Exception("405;ZITPLAATSEN moet een integer zijn;");

				if (($record['ZITPLAATSEN'] < 1) || ($record['ZITPLAATSEN'] > 2))
					throw new Exception("405;ZITPLAATSEN moet 1 of 2 zijn;");	
			}

			if (array_key_exists('ZELFSTART', $input))
			{
				if (false === $record['ZELFSTART'] = isBOOL($input['ZELFSTART']))
					throw new Exception("405;ZELFSTART moet een boolean zijn;");
			}

			if (array_key_exists('SLEEPKIST', $input))
			{
				if (false === $record['SLEEPKIST'] = isBOOL($input['SLEEPKIST']))
					throw new Exception("405;SLEEPKIST moet een boolean zijn;");
			}

			if (array_key_exists('CLUBKIST', $input))
			{
				if (false === $record['CLUBKIST'] = isBOOL($input['CLUBKIST']))
					throw new Exception("405;CLUBKIST moet een boolean zijn;");
			}

			if (array_key_exists('TMG', $input))
			{
				if (false === $record['TMG'] = isBOOL($input['TMG']))
					throw new Exception("405;TMG moet een boolean zijn;");
			}

			if (array_key_exists('FLARMCODE', $input))
				$record['FLARMCODE'] = $input['FLARMCODE'];

			if (array_key_exists('CALLSIGN', $input))
				$record['CALLSIGN'] = $input['CALLSIGN'];

			if (array_key_exists('TYPE_ID', $input))
			{
				if (false === $record['TYPE_ID'] = isINT($input['TYPE_ID']))
					throw new Exception("405;TYPE_ID moet een integer zijn;");
			}

			if (array_key_exists('VOLGORDE', $input))
			{
				if (false === $record['VOLGORDE'] = isINT($input['VOLGORDE']))
					throw new Exception("405;VOLGORDE moet een integer zijn;");
			}

			return $record;
		}
	}
?>