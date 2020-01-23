<?php
	class AanwezigVliegtuigen extends StartAdmin
	{
		function __construct() 
		{
			parent::__construct();
			$this->dbTable = "oper_aanwezig_vliegtuigen";
		}
		
		/*
		Aanmaken van de database tabel. Indien FILLDATA == true, dan worden er ook voorbeeld records toegevoegd 
		*/        
        function CreateTable($FillData)
		{
			$query = sprintf ("
                CREATE TABLE `%s` (
                    `ID` mediumint UNSIGNED NOT NULL AUTO_INCREMENT,
                    `DATUM` date NOT NULL,
                    `VLIEGTUIG_ID` mediumint UNSIGNED NOT NULL,
                    `AANKOMST` time DEFAULT NULL,
                    `VERTREK` time DEFAULT NULL,
                    `POSITIE` point DEFAULT NULL,
                    `HOOGTE` smallint DEFAULT NULL,
                    `SNELHEID` smallint DEFAULT NULL,
					`VERWIJDERD` tinyint NOT NULL DEFAULT '0',
					`LAATSTE_AANPASSING` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					
					CONSTRAINT ID_PK PRIMARY KEY (ID),
                        INDEX (`DATUM`), 
                        INDEX (`VLIEGTUIG_ID`), 
						INDEX (`VERWIJDERD`),

					FOREIGN KEY (VLIEGTUIG_ID) REFERENCES ref_vliegtuigen(ID)
				)", $this->dbTable);
			parent::DbUitvoeren($query);

            if (boolval($FillData))
            {
                $inject = array(
                    "1, '1999-01-01', 201, '07:58:00'   , NULL ",
                    "2, '1999-01-01', 208, '09:01:00'   , '19:01:00' ",
                    "3, '1999-01-01', 200, '11:03:00'   , '16:01:00' ",
					"4, '1999-01-01', 211, '13:18:00'   , NULL ",
					
					"5, '1999-01-02', 211, '09:43:00'  	, NULL ",
					"6, '1999-01-02', 218, '10:22:00'  	, NULL ",

					"7, '1999-01-03', 220, '09:57:00'  	, NULL ",
					
					"8, '1999-01-04', 201, '12:03:00'  	, NULL ",
					"9, '1999-01-04', 200, '12:45:00'  	, NULL ",
					"10, '1999-01-04', 216,'11:57:00'   , NULL ");


                $i = 0;    
                foreach ($inject as $record)
                {                   
                    $query = sprintf("
                            INSERT INTO `%s` (
                                `ID`, 
                                `DATUM`, 
                                `VLIEGTUIG_ID`, 
                                `AANKOMST`, 
                                `VERTREK`) 
                            VALUES
                                (%s);", $this->dbTable, $record);
                    $i++;
                    parent::DbUitvoeren($query);
                }
            }
		}

		/*
		Maak database views, als view al bestaat wordt deze overschreven
		*/		
		function CreateViews()
		{
			parent::DbUitvoeren("DROP VIEW IF EXISTS aanwezig_vliegtuigen_view");
			$query =  sprintf("CREATE VIEW `aanwezig_vliegtuigen_view` AS
				SELECT 
					av.*,
					CONCAT(IFNULL(`v`.`REGISTRATIE`,''),' (',IFNULL(`v`.`CALLSIGN`,''),')') AS `REGCALL`
				FROM
					`%s` `av`
				LEFT JOIN `ref_vliegtuigen` `v` ON (`av`.`VLIEGTUIG_ID` = `v`.`ID`)
			WHERE
					`av`.`VERWIJDERD` = 0;", $this->dbTable);				
			parent::DbUitvoeren($query);
		}

		/*
		Haal een enkel record op uit de database
		*/		
		function GetObject($ID = null, $VLIEGTUIG_ID = null, $DATUM = null, $heeftVerwijderd = true)
		{
			Debug(__FILE__, __LINE__, sprintf("AanwezigVliegtuigen.GetObject(%s,%s,%s,%s)", $ID, $VLIEGTUIG_ID, $DATUM, $heeftVerwijderd));	

			if ($ID == null)
			{
				if (($DATUM == null) || ($VLIEGTUIG_ID == null))
					throw new Exception("406;Geen ID en VLIEGTUIG_ID/DATUM in aanroep;");
			}

			if (($ID != null) && (isINT($ID)) === false)
				throw new Exception("405;ID moet een integer zijn;");

			if (($VLIEGTUIG_ID != null) && (isINT($VLIEGTUIG_ID) === false))
				throw new Exception("405;VLIEGTUIG_ID moet een integer zijn;");				

			if (($DATUM != null) && (isDATE($DATUM) === false))
				throw new Exception("405;DATUM heeft onjuist formaat;");

			$conditie = array();
			if ($ID != null)
			{
				$conditie['ID'] = $ID;
			}
			else
			{
				$conditie['VLIEGTUIG_ID'] =  $VLIEGTUIG_ID ;
				$conditie['DATUM'] = $DATUM;
			}

			if ($heeftVerwijderd == false)
				$conditie['VERWIJDERD'] = 0;		// Dus geen verwijderd record

			$obj = parent::GetSingleObject($conditie);
			if ($obj == null)
				throw new Exception("404;Record niet gevonden;");
			
			return $obj;	
		}
	
		/*
		Haal een dataset op met records als een array uit de database. 
		*/		
		function GetObjects($params)
		{
			$functie = "AanwezigVliegtuigen.GetObjects";
			Debug(__FILE__, __LINE__, sprintf("%s(%s)", $functie, print_r($params, true)));		
			
			$where = ' WHERE 1=1 ';
			$orderby = " ORDER BY DATUM DESC, POSITIE, ID";
			$alleenLaatsteAanpassing = false;
			$limit = -1;
			$start = -1;
			$velden = "*";
			$query_params = array();

			foreach ($params as $key => $value)
			{
				switch ($key)
				{
					case "LAATSTE_AANPASSING" : 
						{
							if (false === $alleenLaatsteAanpassing = isBOOL($value))
								throw new Exception("405;LAATSTE_AANPASSING moet een boolean zijn;");

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
							$where .= " AND (REGCALL LIKE ?) ";

							$s = "%" . trim($value) . "%";
							array_push($query_params, $s);

							Debug(__FILE__, __LINE__, sprintf("%s: SELECTIE='%s'", $functie, $start));
							break;
						}
					case "IN" : 
						{
							if (isCSV($value) === false)
								throw new Exception("405;ID moet een integer zijn in de IN parameter;");
							
							$where .= sprintf(" AND VLIEGTUIG_ID IN(%s)", trim($value));

							Debug(__FILE__, __LINE__, sprintf("%s: IN='%s'", $functie, $start));
							break;
						}
					case "BEGIN_DATUM" : 
						{
							if (isDATE($value) === false)
								throw new Exception("405;BEGIN_DATUM heeft onjuist formaat;");

							$where .= " AND DATUM >= ? ";
							array_push($query_params, $value);

							Debug(__FILE__, __LINE__, sprintf("%s: BEGIN_DATUM='%s'", $functie, $start));
							break;
						}
					case "EIND_DATUM" : 
						{
							if (isDATE($value) === false)
								throw new Exception("405;EIND_DATUM heeft onjuist formaat;");

							$where .= " AND DATUM <= ? ";
							array_push($query_params, $value);

							Debug(__FILE__, __LINE__, sprintf("%s: EIND_DATUM='%s'", $functie, $start));
							break;
						}
					case "VLIEGTUIG_ID" : 
						{
							if (isINT($value) === false)
								throw new Exception("405;VLIEGTUIG_ID moet integer zijn;");

							$where .= " AND VLIEGTUIG_ID = ? ";
							array_push($query_params, $value);

							Debug(__FILE__, __LINE__, sprintf("%s: VLIEGTUIG_ID='%s'", $functie, $start));
							break;
						}			
				}
			}
				
			$query = "
				SELECT 
					%s
				FROM
					`aanwezig_vliegtuigen_view`" . $where . $orderby;
			
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
		function VerwijderObject($ID = null, $VLIEGTUIG_ID = null, $DATUM = null)
		{
			Debug(__FILE__, __LINE__, sprintf("AanwezigVliegtuigen.VerwijderObject(%s, %s)", $ID, $DATUM));					
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)
				throw new Exception("401;Geen schrijfrechten;");

			if ($ID == null)
			{
				if (($DATUM == null) || ($VLIEGTUIG_ID == null))
					throw new Exception("406;Geen ID en VLIEGTUIG_ID/DATUM in aanroep;");
			}

			if (($ID != null) && (isINT($ID) === false))
				throw new Exception("405;ID moet een integer zijn;");

			if (($VLIEGTUIG_ID != null) && (isINT($VLIEGTUIG_ID) === false))
				throw new Exception("405;VLIEGTUIG_ID moet een integer zijn;");				

			if (($DATUM != null) && (isDATE($DATUM) === false))
				throw new Exception("405;DATUM heeft onjuist formaat;");
			
			if ($ID == null)
			{
				$vObj = $this->GetObject(null, $VLIEGTUIG_ID, $DATUM);
				$ID = $vObj["ID"];
			}
			
			parent::MarkeerAlsVerwijderd($ID);
			if (parent::NumRows() === 0)
				throw new Exception("404;Record niet gevonden;");			
		}		

		/*
		Toevoegen van een record. Het is niet noodzakelijk om alle velden op te nemen in het verzoek
		*/		
		function AddObject($AanwezigVliegtuigData)
		{
			Debug(__FILE__, __LINE__, sprintf("AanwezigVliegtuigen.AddObject(%s)", print_r($AanwezigLedenData, true)));
			
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)	
				throw new Exception("401;Geen schrijfrechten;");

			if ($AanwezigVliegtuigData == null)
				throw new Exception("406;AanwezigLeden data moet ingevuld zijn;");	

			$where = "";
			$nieuw = true;
			if (array_key_exists('ID', $AanwezigVliegtuigData))
			{
				if ($false == $id = isINT($AanwezigVliegtuigData['ID']))
					throw new Exception("405;ID moet een integer zijn;");
				
				// ID is opgegeven, maar bestaat record?
				try 	// Als record niet bestaat, krijgen we een exception
				{		
					$this->GetObject($id, null, null, $false);
				}
				catch (Exception $e) {}	

				if (parent::NumRows() > 0)
					throw new Exception(sprintf("409;Record met ID=%s bestaat al;", $id));				
			}

			if (!array_key_exists('VLIEGTUIG_ID', $AanwezigVliegtuigData))
				throw new Exception("406;VLIEGTUIG_ID is verplicht;");		

			if (!array_key_exists('DATUM', $AanwezigVliegtuigData))
				throw new Exception("406;DATUM is verplicht;");			

			// Voorkom dat datum meerdere keren voorkomt in de tabel
			try 	// Als record niet bestaat, krijgen we een exception
			{				
				$this->GetObject(null, $AanwezigVliegtuigData['VLIEGTUIG_ID'], $AanwezigVliegtuigData['DATUM'], $false);
			}
			catch (Exception $e) {}		

			if (parent::NumRows() > 0)
				throw new Exception("409;Aanmelding bestaat al;");
	
			// Neem data over uit aanvraag
			$a = $this->RequestToRecord($AanwezigVliegtuigData);
								
			$id = parent::DbToevoegen($a);
			Debug(__FILE__, __LINE__, sprintf("AanwezigVliegtuigen toegevoegd id=%d", $id));

			return $this->GetObject($id);
		}

		/*
		Update van een bestaand record. Het is niet noodzakelijk om alle velden op te nemen in het verzoek
		*/		
		function UpdateObject($AanwezigVliegtuigData)
		{
			Debug(__FILE__, __LINE__, sprintf("AanwezigVliegtuigen.UpdateObject(%s)", print_r($AanwezigLedenData, true)));
			
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)	
				throw new Exception("401;Geen schrijfrechten;");

			if ($AanwezigVliegtuigData == null)
				throw new Exception("406;AanwezigVliegtuigen data moet ingevuld zijn;");	

			if (!array_key_exists('ID', $AanwezigVliegtuigData))
				throw new Exception("406;ID moet ingevuld zijn;");

			if (false === $id = isINT($AanwezigVliegtuigData['ID']))
				throw new Exception("405;ID moet een integer zijn;");
		   
			$db_record = $this->GetObject($id, null, null, $false);

            // De datum kan niet aangepast worden. 
			if (array_key_exists('DATUM', $AanwezigVliegtuigData))
			{
				if ($AanwezigVliegtuigData['DATUM'] !== $db_record['DATUM'])
					throw new Exception("409;Datum kan niet gewijzigd worden;");
			}

            // De lid_id kan niet aangepast worden. 
			if (array_key_exists('VLIEGTUIG_ID', $AanwezigVliegtuigData))
			{
				if ($AanwezigVliegtuigData['VLIEGTUIG_ID'] !== $db_record['VLIEGTUIG_ID'])
					throw new Exception("409;Vliegtuig ID kan niet gewijzigd worden;");
			}

			// Neem data over uit aanvraag
			$d = $this->RequestToRecord($AanwezigVliegtuigData);            
			parent::DbAanpassen($id, $d);			
			return  $this->GetObject($id);
		}

		/*
		Copieer data van request naar velden van het record 
		*/
		function RequestToRecord($input)
		{
			$record = array();		
			if (array_key_exists('ID', $input))
				$record['ID'] = $input['ID'];  

			if (array_key_exists('DATUM', $input))
			{
				if (false === isDATE($input['DATUM']))
					throw new Exception("405;DATUM heeft onjuist formaat;");

				$record['DATUM'] = $input['DATUM'];
			}

			if (array_key_exists('VLIEGTUIG_ID', $input))
			{
				if (false === $record['VLIEGTUIG_ID'] = isINT($input['VLIEGTUIG_ID']))
					throw new Exception("405;VLIEGTUIG_ID moet een integer zijn;");
			}              		
			
			if (array_key_exists('AANKOMST', $input))
			{
				if (false === $time = isTIME($input['AANKOMST']))
					throw new Exception("405;AANKOMST heeft onjuist formaat;");				

				$record['AANKOMST'] = $time;	
			}
				
			if (array_key_exists('VERTREK', $input))
			{
				if (false === $time = isTIME($input['VERTREK']))
					throw new Exception("405;VERTREK heeft onjuist formaat;");				

				$record['VERTREK'] = $time;
			}

			if (array_key_exists('SNELHEID', $input))
			{
				if (false === $record['SNELHEID'] = isINT($input['SNELHEID']))
					throw new Exception("405;SNELHEID moet een integer zijn;");
			}    

			if (array_key_exists('HOOGTE', $input))
			{
				if (false === $record['HOOGTE'] = isINT($input['HOOGTE']))
					throw new Exception("405;HOOGTE moet een integer zijn;");
			}   

			return $record;
		}

		/*
		Aanmelden van een lid
		*/
		function Aanmelden($AanmeldenVliegtuigData)
		{
			Debug(__FILE__, __LINE__, sprintf("AanwezigVliegtuigen.Aanmelden(%s)", print_r($AanmeldenLedenData, true)));

			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)	
				throw new Exception("401;Geen schrijfrechten;");

			if ($AanmeldenVliegtuigData == null)
				throw new Exception("406;AanmeldenVliegtuigData data moet ingevuld zijn;");	

			if (!array_key_exists('VLIEGTUIG_ID', $AanmeldenVliegtuigData))
				throw new Exception("406;VLIEGTUIG_ID moet ingevuld zijn;");

			if (false === $LidID = isINT($AanmeldenVliegtuigData['VLIEGTUIG_ID']))
				throw new Exception("405;VLIEGTUIG_ID moet een integer zijn;");
		   
			$datetime = new DateTime();
			$datetime->setTimeZone(new DateTimeZone('Europe/Amsterdam')); 
			
			if (array_key_exists('TIJDSTIP', $AanmeldenVliegtuigData))
			{
				if (false == $datetime = isDATETIME($AanmeldenVliegtuigData['TIJDSTIP']))
					throw new Exception("405;TIJDSTIP heeft onjuist formaat;");	
			}
	
			$db_record = null;
			try
			{
				$db_record = $this->GetObject(null, $LidID, $datetime->format('Y-m-d'), $false);
			}
			catch (Exception $e) {}		

			if ($db_record != null)
			{
				// Neem data over uit aanvraag
				$db_record['AANKOMST'] = $datetime->format('H:i:00');
				parent::DbAanpassen($db_record['ID'], $db_record);	

				Debug(__FILE__, __LINE__, sprintf("AanwezigLeden aangepast id=%s", $db_record['ID']));		
				return  $this->GetObject($db_record['ID']);
			}
			
			// Neem data over uit aanvraag
			$a['DATUM'] = $datetime->format('Y-m-d');
			$a['AANKOMST'] = $datetime->format('H:i:00');
			$a['VLIEGTUIG_ID'] = $AanmeldenVliegtuigData['VLIEGTUIG_ID'];
			$db_record = $this->RequestToRecord($a);

			$id = parent::DbToevoegen($db_record);

			Debug(__FILE__, __LINE__, sprintf("AanwezigLeden toegevoegd id=%d", $id));
			return $this->GetObject($id);
		}

		/*
		Afmelden van een lid
		*/
		function Afmelden($AanmeldenVliegtuigData)
		{
			Debug(__FILE__, __LINE__, sprintf("AanwezigVliegtuigen.Afmelden(%s)", print_r($AanmeldenLedenData, true)));

			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)	
				throw new Exception("401;Geen schrijfrechten;");

			if ($AanmeldenVliegtuigData == null)
				throw new Exception("406;AanmeldenVliegtuigData data moet ingevuld zijn;");	

			if (!array_key_exists('VLIEGTUIG_ID', $AanmeldenVliegtuigData))
				throw new Exception("406;VLIEGTUIG_ID moet ingevuld zijn;");

			if (false === $LidID = isINT($AanmeldenVliegtuigData['VLIEGTUIG_ID']))
				throw new Exception("405;VLIEGTUIG_ID moet een integer zijn;");
		   
			$datetime = new DateTime();
			$datetime->setTimeZone(new DateTimeZone('Europe/Amsterdam')); 

			if (array_key_exists('TIJDSTIP', $AanmeldenVliegtuigData))
			{
				if (false == $datetime = isDATETIME($AanmeldenVliegtuigData['TIJDSTIP']))
					throw new Exception("405;TIJDSTIP heeft onjuist formaat;");	
			}
	
			$db_record = null;
			try
			{
				$db_record = $this->GetObject(null, $LidID, $datetime->format('Y-m-d'), $false);
			}
			catch (Exception $e) 
			{
				throw new Exception("409;Kan een lid alleen afmelden als het eerst aangemeld is;");
			}		

			// Neem data over uit aanvraag
			$db_record['VERTREK'] = $datetime->format('H:i:00');
			parent::DbAanpassen($db_record['ID'], $db_record);	

			Debug(__FILE__, __LINE__, sprintf("AanwezigLeden aangepast id=%s", $db_record['ID']));		
			return  $this->GetObject($db_record['ID']);
		}		
	}
?>