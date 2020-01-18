<?php
	class Types extends StartAdmin
	{
		function __construct() 
		{
			parent::__construct();
			$this->dbTable = "ref_types";
		}
		
		/*
		Aanmaken van de database tabel. Indien FILLDATA == true, dan worden er ook voorbeeld records toegevoegd 
		*/        
        function CreateTable($FillData)
		{

			$query = sprintf ("
				CREATE TABLE `%s` (
					`ID` mediumint UNSIGNED NOT NULL AUTO_INCREMENT,
					`GROEP` smallint UNSIGNED NOT NULL,
					`CODE` varchar(5) DEFAULT NULL,
					`EXT_REF` varchar(25) DEFAULT NULL,
					`OMSCHRIJVING` varchar(75) NOT NULL,
					`SORTEER_VOLGORDE` tinyint UNSIGNED DEFAULT NULL,
					`READ_ONLY` tinyint UNSIGNED NOT NULL DEFAULT '0',       
					`VERWIJDERD` tinyint UNSIGNED NOT NULL DEFAULT '0',
					`LAATSTE_AANPASSING` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

					CONSTRAINT ID_PK PRIMARY KEY (ID),
						INDEX (`GROEP`), 
						INDEX (`VERWIJDERD`)
					)", $this->dbTable);
			parent::DbUitvoeren($query);

			if (boolval($FillData))
			{
				$inject = "
					(101, 1, '14L',     '14L', NULL, 0),
					(102, 1, '32L',     '32L', NULL, 0),
					(103, 1, '04R',     '04R', NULL, 0),
					(104, 1, '22L',     '22L', NULL, 0),
					(105, 1, '14',      '14', NULL, 0),
					(106, 1, '12',      '12', NULL, 0),
					(107, 1, '30R',     '30R', NULL, 0),
					(108, 1, '04L',     '04L', NULL, 0),
					(109, 1, '22R',     '22R', NULL, 0),
					(201, 2, 'N',       'Noord', NULL, 0),
					(202, 2, 'NNO',     'NNO', NULL, 1),
					(203, 2, 'NO',      'Noordoost', NULL, 0),
					(204, 2, 'ONO',     'ONO', NULL, 1),
					(205, 2, 'O',       'Oost', NULL, 0),
					(206, 2, 'OZO',     'OZO', NULL, 1),
					(207, 2, 'ZO',      'Zuidoost', NULL, 0),
					(208, 2, 'ZZO',     'ZZO', NULL, 1),
					(209, 2, 'Z',       'Zuid', NULL, 0),
					(210, 2, 'ZZW',     'ZZW', NULL, 1),
					(211, 2, 'ZW',      'Zuidwest', NULL, 0),
					(212, 2, 'WZV',     'WZW', NULL, 1),
					(213, 2, 'W',       'West', NULL, 0),
					(214, 2, 'WNW',     'WNW', NULL, 1),
					(215, 2, 'NW',      'Noordwest', NULL, 0),
					(216, 2, 'NNW',     'NNW', NULL, 1),
					(301, 3,  NULL,     'Windkracht 1 (1-3 kn)', NULL, 0),
					(302, 3,  NULL,     'Windkracht 2 (4-6 kn)', NULL, 0),
					(303, 3,  NULL,     'Windkracht 3 (7-10 kn)', NULL, 0),
					(304, 3,  NULL,     'Windkracht 4 (11-15 kn)', NULL, 0),
					(305, 3,  NULL,     'Windkracht 5 (16 - 21 kn)', NULL, 0),
					(306, 3,  NULL,     'Windkracht 6 (22 - 27 kn)', NULL, 0),
					(307, 3,  NULL,     'Windkracht 7 (28 - 33 kn)', NULL, 0),
					(308, 3,  NULL,     'Windkracht 8 (34 - 40 kn)', NULL, 0),
					(309, 3,  NULL,     'Windkracht 0 (0 - 1 kn)', NULL, 0),
					(401, 4, 'DIS',     'Discus CS', 4, 0),
					(402, 4, 'LS4',     'LS 4', 3, 0),
					(403, 4, 'LS6',     'LS 6-18 w', NULL, 1),
					(404, 4, 'LS8',     'LS8', 5, 0),
					(405, 4, 'Duo',     'Duo Discus', 7, 0),
					(406, 4, 'ASK21',   'ASK 21', 1, 0),
					(407, 4, 'ASK23',   'ASK 23 B', 2, 1),
					(408, 4, 'ASG29',   'ASG-29', 6, 0),
					(501, 5, 'slp',     'Slepen', NULL, 0),
					(502, 5, 'slm',     'Slepen (sleepkist)', NULL, 1),
					(506, 5, 'zel',     'Zelfstart (zweefkist)', NULL, 0),
					(507, 5, 'tmg',     'Zelfstart (TMG)', NULL, 0),
					(508, 5, 'vfr',     'Overig motorkisten', NULL, 0),
					(550, 5, 'gezc',    'Lierstart GeZC', NULL, 0),
					(551, 5, 'cct',     'Lierstart CCT', NULL, 0),
					(552, 5, 'zcrd',    'Lierstart ZCD/ZCR', NULL, 0),
					(553, 5, 'gae',     'Lierstart GAE', NULL, 0),
					(600, 6, '0',       'Diverse (Bijvoorbeeld bedrijven- of jongerendag)', NULL, 0),
					(601, 6, '1',       'Erelid', NULL, 0),
					(602, 6, '2',       'Lid', NULL, 0),
					(603, 6, '3',       'Jeugdlid', NULL, 0),
					(606, 6, '6',       'Donateur', NULL, 0),
					(607, 6, 'zus',     'Zusterclub', NULL, 0),
					(608, 6, '8',       '5-rittenkaarthouder', NULL, 0),
					(609, 6, '9',       'Nieuw lid, nog niet verwerkt in ledenadministratie', NULL, 0),
					(610, 6,  NULL,     'Oprotkabel', NULL, 0),
					(611, 6, '9',       'Cursist', NULL, 0),
					(612, 6,  NULL,     'Penningmeester', NULL, 0),
					(625, 6, '9',       'DDWV vlieger', NULL, 0),
					(701, 7,  NULL,     'Club bedrijf', NULL, 0),
					(702, 7,  NULL,     'Kamp + DDWV', NULL, 0),
					(703, 7,  NULL,     'DDWV', NULL, 0),
					(801, 8,  NULL,     'Passagierstart (kosten voor pax)', NULL, 0),
					(802, 8,  NULL,     'Relatiestart', NULL, 0),
					(803, 8,  NULL,     'Start zusterclub', NULL, 0),
					(804, 8,  NULL,     'Oprotkabel', NULL, 0),
					(805, 8,  NULL,     'Normale GeZC start', NULL, 0),
					(806, 8,  NULL,     'Proefstart privekist eenzitter', NULL, 0),
					(807, 8,  NULL,     'Privestart', NULL, 0),
					(809, 8,  NULL,     'Instructie of checkvlucht', NULL, 0),
					(810, 8,  NULL,     'Solostart met tweezitter', NULL, 0),
					(811, 8, 'dis',     'Invliegen, Dienststart', NULL, 0),
					(812, 8,  NULL,     'Donateursstart', NULL, 0),
					(813, 8,  NULL,     '5- of 10-rittenkaarthouder', NULL, 0),
					(814, 8, 'mid',     'DDWV: Midweekvliegen', NULL, 0),
					(815, 8,  NULL,     'Sleepkist, Dienststart', NULL, 0),
					(901, 9, 'EHTL',    'Terlet', NULL, 0),
					(902, 9, 'EHDL',    'Deelen', NULL, 0),
					(903, 9, 'EHSB',    'Soesterberg', NULL, 0),

					(1550, 15, 'GeZC',  'GeZC', NULL, 0),
					(1551, 15, 'CCT',   'CCT', NULL, 0),
					(1552, 15, 'ZCRD',  'ZCD/ZCR', NULL, 0),
					(1553, 15, 'GAE',   'GAE', NULL, 0);";
				
				$query = sprintf("
						INSERT INTO `%s` (
							`ID`, 
							`GROEP`, 
							`CODE`, 
							`OMSCHRIJVING`, 
							`SORTEER_VOLGORDE`, 
							`VERWIJDERD`) 
						VALUES
							%s;", $this->dbTable, $inject);
				$i++;
				parent::DbUitvoeren($query);
			}
            
		}

		/*
		Maak database views, als view al bestaat wordt deze overschreven
		*/		
		function CreateViews()
		{
			parent::DbUitvoeren("DROP VIEW IF EXISTS types_view");
			$query =  sprintf("CREATE VIEW `types_view` AS
				SELECT 
					types.*
				FROM
					`%s` `types`
			WHERE
					`types`.`VERWIJDERD` = 0;", $this->dbTable);				
			parent::DbUitvoeren($query);
		}

		/*
		Haal een enkel record op uit de database
		*/		
		function GetObject($ID, $heeftVerwijderd = true)
		{
			Debug(__FILE__, __LINE__, sprintf("Types.GetObject(%s)", $ID));	

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
			$functie = "Daginfo.GetObjects";
			Debug(__FILE__, __LINE__, sprintf("%s(%s)", $functie, print_r($params, true)));		
			
			$where = ' WHERE 1=1 ';
			$orderby = " ORDER BY SORTEER_VOLGORDE, ID";
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
                    case "GROEP" : 
                        if (isINT($value) === false)
                            throw new Exception("405;GROEP moet een integer zijn;");
                        
                        $where .= sprintf(" AND GROEP=%d",  $value);	
                        
                        Debug(__FILE__, __LINE__, sprintf("%s: GROEP='%s'", $functie, $value));
                        break;	    																																			
				}
			}
				
			$query = "
				SELECT 
					%s
				FROM
					`types_view` " . $where; // . $orderby;
			
			$retVal = array();

			$retVal['totaal'] = $this->Count($query);		// total amount of records in the database
			$retVal['laatste_aanpassing']=  $this->LaatsteAanpassing($query);
			Debug(__FILE__, __LINE__, sprintf("TOTAAL=%d, LAATSTE_AANPASSING=%s", $retVal['totaal'], $retVal['laatste_aanpassing']));

			$query = "
				SELECT 
					%s
				FROM
					`types_view` " . $where . $orderby;

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
		function VerwijderObject($ID = null)
		{
			Debug(__FILE__, __LINE__, sprintf("Daginfo.VerwijderObject(%s)", $ID));
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)
				throw new Exception("401;Geen schrijfrechten;");

			if ($ID === null)
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
		function AddObject($TypeData)
		{
			Debug(__FILE__, __LINE__, sprintf("Types.AddObject(%s)", print_r($TypeData, true)));
			
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)
				throw new Exception("401;Geen schrijfrechten;");
			
			if ($TypeData == null)
				throw new Exception("406;Type data moet ingevuld zijn;");
					
			if (array_key_exists('ID', $TypeData))
			{
				if (false === $id = isINT($TypeData['ID']))
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

			if (!array_key_exists('GROEP', $TypeData))
				throw new Exception("406;Groep is verplicht;");
			
			if (!array_key_exists('OMSCHRIJVING', $TypeData))
				throw new Exception("406;Omschrijving is verplicht;");
			
			// Neem data over uit aanvraag
			$t = $this->RequestToRecord($TypeData);
	
			$id = parent::DbToevoegen($t);
			Debug(__FILE__, __LINE__, sprintf("type toegevoegd id=%d", $id));

			return $this->GetObject($id);
		}

		/*
		Update van een bestaand record. Het is niet noodzakelijk om alle velden op te nemen in het verzoek
		*/		
		function UpdateObject($TypeData)
		{
			Debug(__FILE__, __LINE__, sprintf("Types.UpdateObject(%s)", print_r($TypeData, true)));
			
			$l = MaakObject('Login');
			if ($l->magSchrijven() == false)
				throw new Exception("401;Geen schrijfrechten;");

			if ($TypeData == null)
				throw new Exception("406;Type data moet ingevuld zijn;");
					
			if (!array_key_exists('ID', $TypeData))
				throw new Exception("406;ID moet ingevuld zijn;");
			
			if (false === $id = isINT($TypeData['ID']))
				throw new Exception("405;ID moet een integer zijn;");
				
			// Neem data over uit aanvraag
			$t = $this->RequestToRecord($TypeData);
    
			parent::DbAanpassen($id, $t);
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

			if (array_key_exists('GROEP', $input))
			{
				if (false === $record['GROEP'] = isINT($input['GROEP']))
					throw new Exception("405;GROEP moet een integer zijn;");
			}

			if (array_key_exists('OMSCHRIJVING', $input))
				$record['OMSCHRIJVING'] = $input['OMSCHRIJVING']; 

			if (array_key_exists('CODE', $input))
				$record['CODE'] = $input['CODE']; 

			if (array_key_exists('EXT_REF', $input))
				$record['EXT_REF'] = $input['EXT_REF']; 

            if (array_key_exists('SORTEER_VOLGORDE', $input))
			{
				if (false === $record['SORTEER_VOLGORDE'] = isINT($input['SORTEER_VOLGORDE']))
					throw new Exception("405;SORTEER_VOLGORDE moet een integer zijn;");
			}
           
			if (array_key_exists('READ_ONLY', $input))
			{
				if (false === $record['READ_ONLY'] = isBOOL($input['READ_ONLY']))
					throw new Exception("405;READ_ONLY moet een boolean zijn;");
			} 
				
			return $record;
		}
	}
?>