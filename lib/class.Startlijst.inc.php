<?php
	class Startlijst extends StartAdmin
	{
		function __construct() 
		{
			parent::__construct();
			$this->dbTable = "oper_startlijst";
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
					`DAGNUMMER` tinyint UNSIGNED NOT NULL DEFAULT 0,
					`VLIEGTUIG_ID` mediumint UNSIGNED NOT NULL,
					`STARTTIJD` time  DEFAULT NULL,
					`LANDINGSTIJD` time DEFAULT NULL,
					`STARTMETHODE_ID` mediumint UNSIGNED DEFAULT NULL,
					`VLIEGER_ID` mediumint UNSIGNED DEFAULT NULL,
					`INZITTENDE_ID` mediumint UNSIGNED DEFAULT NULL,
					`VLIEGERNAAM` varchar(50) DEFAULT NULL,
					`INZITTENDENAAM` varchar(50) DEFAULT NULL,
					`SLEEPKIST_ID` mediumint UNSIGNED DEFAULT NULL,
					`SLEEP_HOOGTE` smallint UNSIGNED DEFAULT NULL,
                    `VELD_ID` mediumint UNSIGNED DEFAULT NULL,
                    `OPMERKINGEN` text DEFAULT NULL,
					`VERWIJDERD` tinyint UNSIGNED NOT NULL DEFAULT '0',
					`LAATSTE_AANPASSING` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					 
					CONSTRAINT ID_PK PRIMARY KEY (ID),
                        INDEX (`DATUM`), 
                        INDEX (`VLIEGTUIG_ID`), 
                        INDEX (`VLIEGER_ID`), 
                        INDEX (`INZITTENDE_ID`), 
						INDEX (`VERWIJDERD`),

					FOREIGN KEY (VLIEGTUIG_ID) REFERENCES ref_vliegtuigen(ID),		
					FOREIGN KEY (STARTMETHODE_ID) REFERENCES ref_types(ID),	
					FOREIGN KEY (VLIEGER_ID) REFERENCES ref_leden(ID),	
					FOREIGN KEY (INZITTENDE_ID) REFERENCES ref_leden(ID),	
					FOREIGN KEY (SLEEPKIST_ID) REFERENCES ref_vliegtuigen(ID),	
					FOREIGN KEY (VELD_ID) REFERENCES ref_types(ID)
				)", $this->dbTable);
			parent::DbUitvoeren($query);

			if (boolval($FillData))
			{
				$inject = array(

                    "'1',   '1999-01-01', '1', '200', '10:02:00', '10:09:00', '550', '10265', '10115', NULL,NULL,NULL",
                    "'2',   '1999-01-01', '2', '201', '10:29:00', '10:40:00', '550', '10265',  NULL  , NULL,NULL,NULL",
                    "'3',   '1999-01-01', '3', '200', '10:25:00', '10:35:00', '550', '10115', '10265', NULL,NULL,NULL",
                    "'4',   '1999-01-01', '4', '200', '10:59:00', '11:12:00', '550', '10855', '10115', NULL,NULL,NULL",
                    "'5',   '1999-01-01', '5', '208', '12:02:00', '12:22:00', '550', '10855', '10265', NULL,NULL,NULL",
                    "'6',   '1999-01-01', '6', '211', '16:00:00', '17:30:00', '550', '10855',  NULL  , NULL,NULL,NULL",
                    "'7',   '1999-01-01', '7', '200', '19:04:00',  NULL     , '550', '10265', '10855', NULL,NULL,NULL",
                    "'8',   '1999-01-01', '8', '211', '11:45:00', '19:20:00', '550', '10115',  NULL  , NULL,NULL,NULL",

                    "'9',   '1999-01-02', '1', '211', '13:22:00', '14:00:00', '550', '10001',  NULL  , NULL,NULL,NULL",
                    "'10',  '1999-01-02', '2', '218', '10:27:00', '11:35:00', '550', '10001', '10470', NULL,NULL,NULL",
                    "'11',  '1999-01-02', '3', '218', '13:33:00', '17:42:00', '550', '10470',  NULL  , NULL,NULL,NULL",
                    "'12',  '1999-01-02', '4', '211', '11:30:00', '11:39:00', '550',  NULL  ,  NULL  , NULL,NULL,NULL",

                    "'13',  '1999-01-03', '1', '220', '11:58:00', '12:04:00', '550', '10213',  NULL  , NULL,NULL, 'chute ging open vlieger ontkoppeld'",
                    "'14',  '1999-01-03', '2', '220', '11:45:00', '17:46:00', '550', '10213',  NULL  , NULL,NULL, NULL",
                    
                    "'15',  '1999-01-04', '1', '201', '10:00:00', '11:10:00', '550', '10063',  NULL  , NULL,NULL,NULL",
                    "'16',  '1999-01-04', '2', '201', '12:02:00', '12:08:00', '550', '10063',  NULL  , NULL,NULL,NULL",
                    "'17',  '1999-01-04', '3', '200', '12:16:00', '14:27:00', '550', '10858',  NULL  , NULL,NULL,NULL",
                    "'18',  '1999-01-04', '4', '216', '12:28:00', '12:32:00', '550', '10632',  NULL  , NULL,NULL,NULL",
                    "'19',  '1999-01-04', '5', '201', '14:22:00', '14:30:00', '550', '10063',  NULL  , NULL,NULL,NULL",
                    "'20',  '1999-01-04', '6', '201', '15:25:00', '15:42:00', '550', '10063',  NULL  , NULL,NULL,NULL",
                    "'21',  '1999-01-04', '7', '200',  NULL     ,  NULL     , '550',  NULL  , '10858', 'Marius de Bok' ,NULL,NULL",
                    "'22',  '1999-01-04', '8', '200',  NULL     ,  NULL     , '550',  NULL  ,  NULL  , 'Orm de Aap', 'Mister Maraboe',NULL");


				$i = 0;    
				foreach ($inject as $record)
				{    			
					$query = sprintf("
							INSERT INTO `%s` (
								`ID`, 
								`DATUM`, 
								`DAGNUMMER`, 
								`VLIEGTUIG_ID`, 
								`STARTTIJD`, 
								`LANDINGSTIJD`, 
								`STARTMETHODE_ID`, 
								`VLIEGER_ID`, 
								`INZITTENDE_ID`, 
								`VLIEGERNAAM`, 
								`INZITTENDENAAM`, 
								`OPMERKINGEN`)
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
			parent::DbUitvoeren("DROP VIEW IF EXISTS startlijst_view");
			$query =  sprintf("CREATE VIEW `startlijst_view` AS
                SELECT 
                    `sl`.*,
                    `v`.`REGISTRATIE`   AS `REGISTRATIE`,
                    `v`.`CALLSIGN`      AS `CALLSIGN`,
                    CONCAT(IFNULL(`v`.`REGISTRATIE`,''),' (',IFNULL(`v`.`CALLSIGN`,''),')') AS `REGCALL`,
                    CASE WHEN `sl`.`DATUM` = cast(current_timestamp() AS date) 
                        THEN 
                            time_format(timediff(ifnull(`sl`.`LANDINGSTIJD`,curtime()),`sl`.`STARTTIJD`),'%%H:%%i') 
                        ELSE 
                            CASE WHEN `sl`.`LANDINGSTIJD` IS NOT NULL
                                THEN 
                                    time_format(timediff(`sl`.`LANDINGSTIJD`,`sl`.`STARTTIJD`),'%%H:%%i') 
                                ELSE 
                                    '' 
                                END 
                        END               AS `DUUR`,
                    `vl`.`NAAM`           AS `VLIEGERNAAM_LID`,
                    `il`.`NAAM`           AS `INZITTENDENAAM_LID`,
                    `sm`.`OMSCHRIJVING`   AS `STARTMETHODE`,
                    `veld`.`OMSCHRIJVING` AS `VELD` 
                FROM 
                    `%s` `sl` 
                    LEFT JOIN `ref_leden`       `vl`    ON `sl`.`VLIEGER_ID` = `vl`.`ID` 
                    LEFT JOIN `ref_leden`       `il`    ON `sl`.`INZITTENDE_ID` = `il`.`ID` 
                    LEFT JOIN `ref_vliegtuigen` `v`     ON `sl`.`VLIEGTUIG_ID` = `v`.`ID` 
                    LEFT JOIN `ref_types`       `veld`  ON `sl`.`VELD_ID` = `veld`.`ID` 
                    LEFT JOIN `ref_types`       `sm`    ON `sl`.`STARTMETHODE_ID` = `sm`.`ID` 
				WHERE
					`sl`.`VERWIJDERD` = 0;", $this->dbTable);		
			parent::DbUitvoeren($query);
		}

		/*
		Haal een enkel record op uit de database
		*/
		function GetObject($ID = null, $heeftVerwijderd = true)
		{
			Debug(__FILE__, __LINE__, sprintf("Startlijst.GetObject(%s,%s,%s)", $ID, $DATUM, $heeftVerwijderd));	

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
		Haal een dataset op met records als een array uit de database. 
		*/		
		function GetObjects($params)
		{
			$functie = "Startlijst.GetObjects";
			Debug(__FILE__, __LINE__, sprintf("%s(%s)", $functie, print_r($params, true)));		
			
			$where = ' WHERE 1=1 ';
			$orderby = " ORDER BY DATUM DESC";
			$alleenLaatsteAanpassing = false;
			$limit = -1;
			$start = -1;
			$velden = "*";

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
                            $where .= " AND ((VLIEGERNAAM_LID LIKE ?) ";
                            $where .= " OR  ((INZITTENDENAAM_LID LIKE ?) ";
                            $where .= " OR  ((VLIEGERNAAM LIKE ?) ";
                            $where .= " OR  ((INZITTENDENAAM LIKE ?) ";
                            $where .= " OR  ((REGCALL LIKE ?))";

                            $s = "%" . trim($value) . "%";
                            array_push($query_params, $s);
                            array_push($query_params, $s);
                            array_push($query_params, $s);
                            array_push($query_params, $s);
                            array_push($query_params, $s);

                            Debug(__FILE__, __LINE__, sprintf("%s: SELECTIE='%s'", $functie, $start));
                            break;
                        }    																																
				}
			}
				
			$query = "
				SELECT 
					%s
				FROM
					`startlijst_view`" . $where . $orderby;
			
			$retVal = array();

			$retVal['totaal'] = $this->Count($query);		// total amount of records in the database
			$retVal['laatste_aanpassing']=  $this->LaatsteAanpassing($query);
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
				parent::DbOpvraag($rquery);
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
			Debug(__FILE__, __LINE__, sprintf("Startlijst.VerwijderObject(%s, %s)", $ID, $DATUM));								
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
		function AddObject($StartlijstData)
		{
			Debug(__FILE__, __LINE__, sprintf("Startlijst.AddObject(%s)", print_r($StartlijstData, true)));
			
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)	
				throw new Exception("401;Geen schrijfrechten;");

			if ($StartlijstData == null)
				throw new Exception("406;Daginfo data moet ingevuld zijn;");	

			$where = "";
			$nieuw = true;
			if (array_key_exists('ID', $StartlijstData))
			{
				if (false === $id = isINT($StartlijstData['ID']))
					throw new Exception("405;ID moet een integer zijn;");
				
				// ID is opgegeven, maar bestaat record?
				try 	// Als record niet bestaat, krijgen we een exception
				{		
					$this->GetObject($id, null, $false);	
				}
				catch (Exception $e) {}

				if (parent::NumRows() > 0)
					throw new Exception(sprintf("409;Record met ID=%s bestaat al;", $id));
			}

			if (!array_key_exists('VLIEGTUIG_ID', $AanwezigVliegtuigData))
				throw new Exception("406;VLIEGTUIG_ID is verplicht;");		

			if (!array_key_exists('DATUM', $AanwezigVliegtuigData))
				throw new Exception("406;DATUM is verplicht;");			
			
			// Neem data over uit aanvraag
            $d = $this->RequestToRecord($StartlijstData);
            $d['DAGNUMMER'] = $this->NieuwDagNummer($d['DATUM']);
								
			$id = parent::DbToevoegen($d);
			Debug(__FILE__, __LINE__, sprintf("Daginfo toegevoegd id=%d", $id));

			return $this->GetObject($id);
		}

		/*
		Update van een bestaand record. Het is niet noodzakelijk om alle velden op te nemen in het verzoek
		*/		
		function UpdateObject($StartlijstData)
		{
			Debug(__FILE__, __LINE__, sprintf("Startlijst.UpdateObject(%s)", print_r($StartlijstData, true)));
			
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)	
				throw new Exception("401;Geen schrijfrechten;");

			if ($StartlijstData == null)
				throw new Exception("406;Daginfo data moet ingevuld zijn;");	

			if (!array_key_exists('ID', $StartlijstData))
				throw new Exception("406;ID moet ingevuld zijn;");

			if (false === $id = isINT($StartlijstData['ID']))
				throw new Exception("405;ID moet een integer zijn;");
		   
			$id = intval($StartlijstData['ID']);

			// Neem data over uit aanvraag
			$d = $this->RequestToRecord($StartlijstData);            

			parent::DbAanpassen($id, $d);
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

			if (array_key_exists('STARTTIJD', $input))
			{
				if (false === $record['STARTTIJD'] = isTIME($input['STARTTIJD']))
					throw new Exception("405;STARTTIJD moet een tijd zijn;");
			}

			if (array_key_exists('LANDINGSTIJD', $input))
			{
				if (false === $record['LANDINGSTIJD'] = isTIME($input['LANDINGSTIJD']))
					throw new Exception("405;LANDINGSTIJD moet een tijd zijn;");
			}

			if (array_key_exists('STARTMETHODE_ID', $input))
			{
				if (false === $record['STARTMETHODE_ID'] = isINT($input['STARTMETHODE_ID']))
					throw new Exception("405;STARTMETHODE_ID moet een integer zijn;");
			}

			if (array_key_exists('VLIEGER_ID', $input))
			{
				if (false === $record['VLIEGER_ID'] = isINT($input['VLIEGER_ID']))
					throw new Exception("405;VLIEGER_ID moet een integer zijn;");
			}

			if (array_key_exists('INZITTENDE_ID', $input))
			{
				if (false === $record['INZITTENDE_ID'] = isINT($input['INZITTENDE_ID']))
					throw new Exception("405;INZITTENDE_ID moet een integer zijn;");
			}

			if (array_key_exists('VLIEGERNAAM', $input))
				$record['VLIEGERNAAM'] = $input['VLIEGERNAAM']; 

			if (array_key_exists('INZITTENDENAAM', $input))
				$record['INZITTENDENAAM'] = $input['INZITTENDENAAM']; 

			if (array_key_exists('SLEEPKIST_ID', $input))
			{
				if (false === $record['SLEEPKIST_ID'] = isINT($input['SLEEPKIST_ID']))
					throw new Exception("405;SLEEPKIST_ID moet een integer zijn;");
			}

			if (array_key_exists('SLEEP_HOOGTE', $input))
			{
				if (false === $record['SLEEP_HOOGTE'] = isINT($input['SLEEP_HOOGTE']))
					throw new Exception("405;SLEEP_HOOGTE moet een integer zijn;");
			}			

			if (array_key_exists('VELD_ID', $input))
			{
				if (false === $record['VELD_ID'] = isINT($input['VELD_ID']))
					throw new Exception("405;VELD_ID moet een integer zijn;");
			}		
						
			if (array_key_exists('OPMERKINGEN', $input))
				$record['OPMERKINGEN'] = $input['OPMERKINGEN']; 

			return $record;
        }
        
        		// ------------------------------------------------------------------
		// Bepaal het volgnummer van de dag
		function NieuwDagNummer($datum)
		{
			parent::DbOpvraag("
					SELECT 
						DAGNUMMER + 1 AS NIEUW_DAGNUMMER
					FROM 
						oper_startlijst
					WHERE 
						((DATUM = '" . $datum . "')) 
					ORDER BY 
						DAGNUMMER DESC
                    LIMIT 1;");
                    
			$dagnr = parent::DbData();
			if (count($dagnr) > 0)
				return $dagnr[0]['NIEUW_DAGNUMMER'];
			else
				return 1;		
		}
	}
?>