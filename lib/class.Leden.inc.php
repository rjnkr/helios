<?php
	class Leden extends StartAdmin
	{
		function __construct() 
		{
			parent::__construct();
			$this->dbTable = "ref_leden";
		}
		
		/*
		Aanmaken van de database tabel. Indien FILLDATA == true, dan worden er ook voorbeeld records toegevoegd 
		*/		
		function CreateTable($FillData)
		{
			$query = sprintf ("
				CREATE TABLE `%s` (
                    `ID` mediumint UNSIGNED NOT NULL AUTO_INCREMENT,
                    `NAAM` varchar(255) NOT NULL,
                    `VOORNAAM` varchar(15) DEFAULT NULL,
                    `ACHTERNAAM` varchar(30) DEFAULT NULL,
                    `TELEFOON` varchar(255) DEFAULT NULL,
                    `MOBIEL` varchar(255) DEFAULT NULL,
                    `NOODNUMMER` varchar(255) DEFAULT NULL,
                    `EMAIL` varchar(45) DEFAULT NULL,
                    `LIDNR` varchar(10) DEFAULT NULL,
                    `LIDTYPE_ID` mediumint UNSIGNED NOT NULL,
                    `LIERIST` tinyint UNSIGNED NOT NULL DEFAULT 0,
                    `STARTLEIDER` tinyint UNSIGNED NOT NULL DEFAULT 0,
                    `INSTRUCTEUR` tinyint UNSIGNED NOT NULL DEFAULT 0,
                    `INLOGNAAM` varchar(45) DEFAULT NULL,
                    `WACHTWOORD` varchar(255) DEFAULT NULL,             
                    `HEEFT_BETAALD` tinyint UNSIGNED NOT NULL DEFAULT 0,
					`PRIVACY` tinyint UNSIGNED NOT NULL DEFAULT 0,
                    `VERWIJDERD` tinyint UNSIGNED NOT NULL DEFAULT 0,
					`LAATSTE_AANPASSING` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

					CONSTRAINT ID_PK PRIMARY KEY (ID),
						INDEX (`NAAM`),
						INDEX (`LIDTYPE_ID`),
						INDEX (`STARTLEIDER`),
						INDEX (`INSTRUCTEUR`),
						INDEX (`LIERIST`),
						INDEX (`VERWIJDERD`),

						FOREIGN KEY (LIDTYPE_ID) REFERENCES ref_types(ID) 				
				)", $this->dbTable);
			parent::DbUitvoeren($query);

			if (boolval($FillData))
			{
				$query = sprintf("
					INSERT INTO 
						`%s` 
							(`ID`,  
							`NAAM`, 
							`LIDNR`, 
							`LIDTYPE_ID`, 
							`MOBIEL`, 
							`EMAIL`, 
                            `NOODNUMMER`, 
                            `TELEFOON`, 
                            `INSTRUCTEUR`, 
                            `STARTLEIDER`, 
                            `HEEFT_BETAALD`, 
                            `LIERIST`) 
						VALUES
                            ('10855','Truus de Mier','12091',625,NULL,'mier@fabeltje.com', NULL, NULL, '0','0','1','0'),
                            ('10213','Teun Stier','10022',602, NULL, '06-1256770','stier@fabeltje.com','06-1256770','0','1','1','0'),
                            ('10858','Meneer de Uil','41382',601,'06-5112006', NULL, '0882-10111','0310-430210','0','0','1','1'),
                            ('10408','Meindert het Paard','11139',603,'06-1025500','meindert@fabeltje.com','0112-11801','086-1506822','1','1','0','1'),
                            ('10804','Willem Bever','588139',602,'06-2828281','willem@fabeltje.com', NULL, NULL, '0','0','1','1'),
                            ('10632','Gerrit de Postduif','11140',602,'06-10285005','g.de.postduif@fabeltje.com','0319-18348','0278-20000','0','0','1','0'),
                            ('10063','Isadora Paradijsvogel','91900',606,'06-10005001','isadora.paradijsvogel@fabeltje.com','0112-99820','0818-71100','0','0','1','0'),
                            ('10265','Lowieke de Vos','12239',602,'06-10333005','vos@fabeltje.com','0112-10008','020-120333','0','0','1','0'),
                            ('10395','Juffrouw Ooievaar','22154',608,'06-2011301','ooievaar@fabeltje.com', NULL, '070-120311','0','0','0','0'),
                            ('10115','Momfer de Mol','93562',602,'06-2009710','m.de.mol@fabeltje.com', NULL, '020-200120','1','0','1','0'),
                            ('10470','Bor de Wolf','11511',602,'06-1119220','wolf@fabeltje.com', NULL, '027-120887','1','0','1','0'),
							('10001','Zoef de Haas','31313',603,'06-6119330','zoefzoef@fabeltje.com', NULL, '027-120827','1','1','1','0');
					", $this->dbTable);
                parent::DbUitvoeren($query);
			}
		}

		/*
		Maak database views, als view al bestaat wordt deze overschreven
		*/		
		function CreateViews()
		{
			parent::DbUitvoeren("DROP VIEW IF EXISTS leden_view");
			$query =  sprintf("CREATE VIEW `leden_view` AS
				SELECT 
					l.*,
					`t`.`OMSCHRIJVING` AS `LIDTYPE`
				FROM
					`%s` `l`    
					LEFT JOIN `ref_types` `t` ON (`l`.`LIDTYPE_ID` = `t`.`ID`)
				WHERE
					`l`.`VERWIJDERD` = 0;", $this->dbTable);			
			parent::DbUitvoeren($query);
		}

		/*
		Haal een enkel record op uit de database
		*/
		function GetObject($ID,  $heeftVerwijderd = true)
		{
			Debug(__FILE__, __LINE__, sprintf("Leden.GetObject(%s)", $ID));	

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
		function GetObjectByLidnr($LidNr)
		{
			Debug(__FILE__, __LINE__, sprintf("Leden.GetObject(%s)", $ID));	

			if ($LidNr == null)
				throw new Exception("406;Geen LidNr in aanroep;");

			$query = sprintf("
				SELECT
					*
				FROM
					%s
				WHERE
					LIDNR = :LIDNR AND VERWIJDERD=0", $this->dbTable);

			$conditie = array();
			$conditie[':LIDNR'] = $LidNr;

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
			$functie = "Leden.GetObjects";
			Debug(__FILE__, __LINE__, sprintf("%s(%s)", $functie, print_r($params, true)));		
			
			$where = ' WHERE 1=1 ';
			$orderby = " ORDER BY ACHTERNAAM, VOORNAAM";
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
							$where .= " AND ((NAAM LIKE ?) ";
							$where .= "  OR (EMAIL LIKE ?) ";
							$where .= "  OR (TELEFOON LIKE ?) ";
							$where .= "  OR (MOBIEL LIKE ?) ";
							$where .= "  OR (NOODNUMMER LIKE ?)) ";

							$s = "%" . trim($value) . "%";
							$query_params = array($s, $s, $s, $s, $s);

							Debug(__FILE__, __LINE__, sprintf("%s: SELECTIE='%s'", $functie, $value));	
							break;
						}
					case "TYPES" : 
						{
							if (isCSV($value) === false)
								throw new Exception("405;ID moet een integer zijn in de TYPES parameter;");

							$where .= sprintf(" AND LIDTYPE_ID IN(%s)", trim($value));

							Debug(__FILE__, __LINE__, sprintf("%s: TYPES='%s'", $functie, $value));
							break;
						}																														
				}
			}
				
			$query = "
				SELECT 
					%s
				FROM
					`leden_view`" . $where . $orderby;
			
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
			Debug(__FILE__, __LINE__, sprintf("Leden.VerwijderObject(%s)", $ID));	
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
		function AddObject($LidData)
		{
			Debug(__FILE__, __LINE__, sprintf("Leden.AddObject(%s)", print_r($LidData, true)));
			
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)	
				throw new Exception("401;Geen schrijfrechten;");

			if ($LidData == null)
				throw new Exception("406;Lid data moet ingevuld zijn;");			

			if (array_key_exists('ID', $LidData))
			{
				if (false === $id = isINT($LidData['ID']))
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
			if (array_key_exists('LIDNR', $LidData))
			{
				if ($LidData['LIDNR'] != null)	// null is altijd goed
				{
					try 	// Als record niet bestaat, krijgen we een exception
					{				
						$this->GetObjectByLidnr($LidData['LIDNR']);
					}
					catch (Exception $e) {}	

					if (parent::NumRows() > 0)
						throw new Exception(sprintf("409;Record met LIDNR=%s bestaat al;", $LidData['LIDNR']));
				}
			}

			if (!array_key_exists('NAAM', $LidData))
				throw new Exception("406;NAAM is verplicht;");
			
			if (!array_key_exists('LIDTYPE_ID', $LidData))
				throw new Exception("406;Lidtype is verplicht;");
				
			// Neem data over uit aanvraag
			$l = $this->RequestToRecord($LidData);
						
			$id = parent::DbToevoegen($l);
			Debug(__FILE__, __LINE__, sprintf("lid toegevoegd id=%d", $id));

			return $this->GetObject($id);
		}

		/*
		Toevoegen van een record. Het is niet noodzakelijk om alle velden op te nemen in het verzoek
		*/		
		function UpdateObject($LidData)
		{
			Debug(__FILE__, __LINE__, sprintf("Leden.SaveObject(%s)", print_r($LidData, true)));
			
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)	
				throw new Exception("401;Geen schrijfrechten;");

			if ($LidData == null)
				throw new Exception("406;Lid data moet ingevuld zijn;");			

			if (!array_key_exists('ID', $LidData))
				throw new Exception("406;ID moet ingevuld zijn;");

			if (false === $id = isINT($LidData['ID']))
				throw new Exception("405;ID moet een integer zijn;");
			
            // Voorkom dat datum meerdere keren voorkomt in de tabel
			if (array_key_exists('LIDNR', $LidData))
			{
				if ($LidData['LIDNR'] != null)	// null is altijd goed
				{
					try 	// Als record niet bestaat, krijgen we een exception
					{
						$l = $this->GetObjectByLidnr($LidData['LIDNR']);
					}
					catch (Exception $e) {}

					if (parent::NumRows() > 0)
					{
						if ($LidData['ID'] != $l['ID'])
							throw new Exception("409;LidNr bestaat reeds;");
					}	
				}
			}

			// Neem data over uit aanvraag
			$l = $this->RequestToRecord($LidData);

			parent::DbAanpassen($id, $l);
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

			if (array_key_exists('NAAM', $input))
				$record['NAAM'] = $input['NAAM']; 

			if (array_key_exists('VOORNAAM', $input))
				$record['VOORNAAM'] = $input['VOORNAAM']; 

			if (array_key_exists('ACHTERNAAM', $input))
				$record['ACHTERNAAM'] = $input['ACHTERNAAM']; 

			if (array_key_exists('TELEFOON', $input))
				$record['TELEFOON'] = $input['TELEFOON']; 

			if (array_key_exists('MOBIEL', $input))
				$record['MOBIEL'] = $input['MOBIEL']; 

			if (array_key_exists('NOODNUMMER', $input))
				$record['NOODNUMMER'] = $input['NOODNUMMER']; 

			if (array_key_exists('EMAIL', $input))
				$record['EMAIL'] = $input['EMAIL']; 

			if (array_key_exists('LIDNR', $input))
				$record['LIDNR'] = $input['LIDNR']; 	

			if (array_key_exists('LIDTYPE_ID', $input))
			{
				if (false === $record['LIDTYPE_ID'] = isINT($input['LIDTYPE_ID']))
					throw new Exception("405;LIDTYPE_ID moet een integer zijn;");
			}

			if (array_key_exists('LIERIST', $input))
			{
				if (false === $record['LIERIST'] = isBOOL($input['LIERIST']))
					throw new Exception("405;LIERIST moet een boolean zijn;");
			}

			if (array_key_exists('STARTLEIDER', $input))
			{
				if (false === $record['STARTLEIDER'] = isBOOL($input['STARTLEIDER']))
					throw new Exception("405;STARTLEIDER moet een boolean zijn;");
			}

			if (array_key_exists('INSTRUCTEUR', $input))
			{
				if (false === $record['INSTRUCTEUR'] = isBOOL($input['INSTRUCTEUR']))
					throw new Exception("405;INSTRUCTEUR moet een boolean zijn;");
			}

			if (array_key_exists('INLOGNAAM', $input))
				$record['INLOGNAAM'] = $input['INLOGNAAM']; 

			if (array_key_exists('WACHTWOORD', $input))
				$record['WACHTWOORD'] = $input['WACHTWOORD']; 	

			if (array_key_exists('HEEFT_BETAALD', $input))
			{
				if (false === $record['HEEFT_BETAALD'] = isBOOL($input['HEEFT_BETAALD']))
					throw new Exception("405;HEEFT_BETAALD moet een boolean zijn;");
			}

			if (array_key_exists('PRIVACY', $input))
			{
				if (false === $record['PRIVACY'] = isBOOL($input['PRIVACY']))
					throw new Exception("405;PRIVACY moet een boolean zijn;");
			}

			return $record;				
		}
	}
?>