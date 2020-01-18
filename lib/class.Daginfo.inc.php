<?php
	class Daginfo extends StartAdmin
	{
		function __construct() 
		{
			parent::__construct();
			$this->dbTable = "oper_daginfo";
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
                    `VELD_ID` mediumint UNSIGNED DEFAULT NULL,
                    `BAAN_ID` mediumint UNSIGNED DEFAULT NULL,
                    `BEDRIJF_ID` mediumint UNSIGNED DEFAULT NULL,
                    `STARTMETHODE_ID` mediumint UNSIGNED DEFAULT NULL,
                    `OPMERKINGEN` text DEFAULT NULL,
					`VLIEGBEDRIJF` text DEFAULT NULL,
					`METEO` text DEFAULT NULL,
                    `DIENSTEN` text DEFAULT NULL,
                    `VERSLAG` text DEFAULT NULL,
                    `ROLLENDMATERIEEL` text DEFAULT NULL,
                    `VLIEGENDMATERIEEL` text DEFAULT NULL,
                    `DDWV` tinyint UNSIGNED NOT NULL DEFAULT 0,
					`CLUB_BEDRIJF` tinyint UNSIGNED NOT NULL DEFAULT 1,
					`VERWIJDERD` tinyint UNSIGNED NOT NULL DEFAULT '0',
					`LAATSTE_AANPASSING` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					
					CONSTRAINT ID_PK PRIMARY KEY (ID),
						INDEX (`DATUM`), 
						INDEX (`VERWIJDERD`),
						
					FOREIGN KEY (VELD_ID) REFERENCES ref_types(ID),	
					FOREIGN KEY (BAAN_ID) REFERENCES ref_types(ID),	
					FOREIGN KEY (BEDRIJF_ID) REFERENCES ref_types(ID),	
					FOREIGN KEY (STARTMETHODE_ID) REFERENCES ref_types(ID)
				)", $this->dbTable);
			parent::DbUitvoeren($query);

            if (boolval($FillData))
            {
                $inject = array(
                    "1, '1999-01-01', 901, 109, 1550, NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%s'",
                    "2, '1999-01-02', 901, 108, 1550, 550,  '%s', '%s', '%s', '%s', '%s', '%s', '%s'",
                    "3, '1999-01-03', 901, 108, 1550, 550,  '%s', '%s', '%s', '%s', '%s', '%s', '%s'",
                    "4, '1999-01-04', 901, 108, 1550, 550,  '%s', '%s', '%s', '%s', '%s', '%s', '%s'",
                    "5, '1999-01-05', 901, 108, 1550, 550,  '%s', '%s', '%s', '%s', '%s', '%s', '%s'");

                $i = 0;    
                foreach ($inject as $record)
                {    
                    $fields = sprintf($record, 
                                parent::fakeText(), 
                                parent::fakeText(),
                                parent::fakeText(),
                                parent::fakeText(),
								parent::fakeText(),
								parent::fakeText(),
                                parent::fakeText());
                                
                    $query = sprintf("
                            INSERT INTO `%s` (
                                `ID`, 
                                `DATUM`, 
                                `VELD_ID`, 
                                `BAAN_ID`, 
                                `BEDRIJF_ID`, 
                                `STARTMETHODE_ID`, 
                                `OPMERKINGEN`, 
								`VLIEGBEDRIJF`, 
								`METEO`, 
                                `DIENSTEN`, 
                                `VERSLAG`, 
                                `ROLLENDMATERIEEL`, 
                                `VLIEGENDMATERIEEL`) 
                            VALUES
                                (%s);", $this->dbTable, $fields);
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
			parent::DbUitvoeren("DROP VIEW IF EXISTS daginfo_view");
			$query =  sprintf("CREATE VIEW `daginfo_view` AS
				SELECT 
					di.*,
					`T_Veld`.`CODE` AS `VELD_CODE`,
					`T_Veld`.`OMSCHRIJVING` AS  `VELD_OMS`,        
					`T_Baan`.`CODE` AS `BAAN_CODE`,
					`T_Baan`.`OMSCHRIJVING` AS  `BAAN_OMS`,
					`T_Bedrijf`.`CODE` AS `BEDRIJF_CODE`,
					`T_Bedrijf`.`OMSCHRIJVING` AS  `BEDRIJF_OMS`,
					`T_Startmethode`.`CODE` AS `STARTMETHODE_CODE`,
					`T_Startmethode`.`OMSCHRIJVING` AS  `STARTMETHODE_OMS`
				FROM
					`%s` `di`
				LEFT JOIN `ref_types` `T_Veld` ON (`di`.`VELD_ID` = `T_Veld`.`ID`)
				LEFT JOIN `ref_types` `T_Baan` ON (`di`.`BAAN_ID` = `T_Baan`.`ID`)
				LEFT JOIN `ref_types` `T_Bedrijf` ON (`di`.`BEDRIJF_ID` = `T_Bedrijf`.`ID`)
				LEFT JOIN `ref_types` `T_Startmethode` ON (`di`.`STARTMETHODE_ID` = `T_Startmethode`.`ID`)
			WHERE
					`di`.`VERWIJDERD` = 0;", $this->dbTable);				
			parent::DbUitvoeren($query);
		}

		/*
		Haal een enkel record op uit de database
		*/		
		function GetObject($ID = null, $DATUM = null, $heeftVerwijderd = true)
		{
			Debug(__FILE__, __LINE__, sprintf("Daginfo.GetObject(%s,%s,%s)", $ID, $DATUM, $heeftVerwijderd));	

			if (($ID == null) && ($DATUM == null))
				throw new Exception("406;Geen ID en DATUM in aanroep;");
			
			if (($ID != null) && (isINT($ID) === false))
				throw new Exception("405;ID moet een integer zijn;");

			if (($DATUM != null) && (isDATE($DATUM) === false))
				throw new Exception("405;DATUM heeft onjuist formaat;");

			$conditie = array();
			if ($ID != null)
				$conditie['ID'] = $ID;
			else
				$conditie['DATUM'] =  $DATUM ;
				
			if ($heeftVerwijderd == false)
				$conditie['VERWIJDERD'] = 0;		// Dus geen verwijderd record
				
			$obj = parent::GetSingleObject($conditie);

			Debug(__FILE__, __LINE__, print_r($obj, true));
			if ($obj == null)
				throw new Exception("404;Record niet gevonden;");
			
			return $obj;	
		}
	
		/*
		Haal een dataset op met records als een array uit de database. 
		*/		
		function GetObjects($params)
		{
			$functie = "Daginfo.GetObjects";
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
				}
			}
				
			$query = "
				SELECT 
					%s
				FROM
					`daginfo_view`" . $where . $orderby;
			
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
		function VerwijderObject($ID = null, $DATUM = null)
		{
			Debug(__FILE__, __LINE__, sprintf("Daginfo.VerwijderObject(%s, %s)", $ID, $DATUM));					
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)
				throw new Exception("401;Geen schrijfrechten;");

			if (($ID == null) && ($DATUM == null))
				throw new Exception("406;Geen ID en DATUM in aanroep;");
			
			if ($ID != null)
			{
				if (isINT($ID) === false)
					throw new Exception("405;ID moet een integer zijn;");
			}
			else
			{
				$vObj = $this->GetObject(null, $DATUM);
				$ID = $vObj["ID"];
			}
			
			parent::MarkeerAlsVerwijderd($ID);
			if (parent::NumRows() === 0)
				throw new Exception("404;Record niet gevonden;");			
		}		

		/*
		Toevoegen van een record. Het is niet noodzakelijk om alle velden op te nemen in het verzoek
		*/		
		function AddObject($DaginfoData)
		{
			Debug(__FILE__, __LINE__, sprintf("Daginfo.AddObject(%s)", print_r($DaginfoData, true)));
			
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)	
				throw new Exception("401;Geen schrijfrechten;");

			if ($DaginfoData == null)
				throw new Exception("406;Daginfo data moet ingevuld zijn;");	

			$where = "";
			$nieuw = true;
			if (array_key_exists('ID', $DaginfoData))
			{
				if (false === $id = isINT($DaginfoData['ID']))
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

			if (!array_key_exists('DATUM', $DaginfoData))
				throw new Exception("406;Datum is verplicht;");

			// Voorkom dat datum meerdere keren voorkomt in de tabel
			try 	// Als record niet bestaat, krijgen we een exception
			{				
				$this->GetObject(null, $DaginfoData['DATUM'], $false);
			}
			catch (Exception $e) {}		

			if (parent::NumRows() > 0)
				throw new Exception("409;Datum bestaat al;");
	
			// Neem data over uit aanvraag
			$d = $this->RequestToRecord($DaginfoData);
								
			$id = parent::DbToevoegen($d);
			Debug(__FILE__, __LINE__, sprintf("Daginfo toegevoegd id=%d", $id));

			return $this->GetObject($id);
		}

		/*
		Update van een bestaand record. Het is niet noodzakelijk om alle velden op te nemen in het verzoek
		*/		
		function UpdateObject($DaginfoData)
		{
			Debug(__FILE__, __LINE__, sprintf("Daginfo.UpdateObject(%s)", print_r($DaginfoData, true)));
			
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)	
				throw new Exception("401;Geen schrijfrechten;");

			if ($DaginfoData == null)
				throw new Exception("406;Daginfo data moet ingevuld zijn;");	

			if (!array_key_exists('ID', $DaginfoData))
				throw new Exception("406;ID moet ingevuld zijn;");

			if (false == $id = isINT($DaginfoData['ID']))
				throw new Exception("405;ID moet een integer zijn;");
		   
            // Voorkom dat datum meerdere keren voorkomt in de tabel
			if (array_key_exists('DATUM', $DaginfoData))
			{
				try 	// Als record niet bestaat, krijgen we een exception
				{
					$di = $this->GetObject(null, $DaginfoData['DATUM'], $false);
				}
				catch (Exception $e) {}	

				if (parent::NumRows() > 0)
				{
					if ($DaginfoData['ID'] != $di['ID'])
						throw new Exception("409;Datum bestaat reeds;");
				}	
			}
			
			// Neem data over uit aanvraag
			$d = $this->RequestToRecord($DaginfoData);            

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
		
			if (array_key_exists('VELD_ID', $input))
			{
				if (false === $record['VELD_ID'] = isINT($input['VELD_ID']))
					throw new Exception("405;VELD_ID moet een integer zijn;");
			}

			if (array_key_exists('BAAN_ID', $input))
			{
				if (false === $record['BAAN_ID'] = isINT($input['BAAN_ID']))
					throw new Exception("405;BAAN_ID moet een integer zijn;");
			}

			if (array_key_exists('BEDRIJF_ID', $input))
			{
				if (false === $record['BEDRIJF_ID'] = isINT($input['BEDRIJF_ID']))
					throw new Exception("405;BEDRIJF_ID moet een integer zijn;");
			}

			if (array_key_exists('STARTMETHODE_ID', $input))
			{
				if (false === $record['STARTMETHODE_ID'] = isINT($input['STARTMETHODE_ID']))
					throw new Exception("405;STARTMETHODE_ID moet een integer zijn;");
			}

			if (array_key_exists('SOORTBEDRIJF_ID', $input))
			{
				if (false === $record['SOORTBEDRIJF_ID'] = isINT($input['SOORTBEDRIJF_ID']))
					throw new Exception("405;SOORTBEDRIJF_ID moet een integer zijn;");
			}             
				
			if (array_key_exists('OPMERKINGEN', $input))
				$record['OPMERKINGEN'] = $input['OPMERKINGEN']; 
				
			if (array_key_exists('VLIEGBEDRIJF', $input))
				$record['VLIEGBEDRIJF'] = $input['VLIEGBEDRIJF'];
			
			if (array_key_exists('METEO', $input))
				$record['METEO'] = $input['METEO'];	
				
			if (array_key_exists('DIENSTEN', $input))
				$record['DIENSTEN'] = $input['DIENSTEN'];	
				
			if (array_key_exists('VERSLAG', $input))
				$record['VERSLAG'] = $input['VERSLAG'];	
				
			if (array_key_exists('ROLLENDMATERIEEL', $input))
				$record['ROLLENDMATERIEEL'] = $input['ROLLENDMATERIEEL'];	
				
			if (array_key_exists('VLIEGENDMATERIEEL', $input))
				$record['VLIEGENDMATERIEEL'] = $input['VLIEGENDMATERIEEL'];

			return $record;
		}
	}
?>