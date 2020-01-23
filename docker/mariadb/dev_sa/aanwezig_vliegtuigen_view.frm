TYPE=VIEW
query=select `av`.`ID` AS `ID`,`av`.`DATUM` AS `DATUM`,`av`.`VLIEGTUIG_ID` AS `VLIEGTUIG_ID`,`av`.`AANKOMST` AS `AANKOMST`,`av`.`VERTREK` AS `VERTREK`,`av`.`POSITIE` AS `POSITIE`,`av`.`HOOGTE` AS `HOOGTE`,`av`.`SNELHEID` AS `SNELHEID`,`av`.`VERWIJDERD` AS `VERWIJDERD`,`av`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING`,concat(ifnull(`v`.`REGISTRATIE`,\'\'),\' (\',ifnull(`v`.`CALLSIGN`,\'\'),\')\') AS `REGCALL` from (`dev_sa`.`oper_aanwezig_vliegtuigen` `av` left join `dev_sa`.`ref_vliegtuigen` `v` on(`av`.`VLIEGTUIG_ID` = `v`.`ID`)) where `av`.`VERWIJDERD` = 0
md5=f2c5f2245ac2702e7c18ec592e363f1d
updatable=0
algorithm=0
definer_user=root
definer_host=%
suid=2
with_check_option=0
timestamp=2020-01-23 21:03:59
create-version=2
source=SELECT \n					av.*,\n					CONCAT(IFNULL(`v`.`REGISTRATIE`,\'\'),\' (\',IFNULL(`v`.`CALLSIGN`,\'\'),\')\') AS `REGCALL`\n				FROM\n					`oper_aanwezig_vliegtuigen` `av`\n				LEFT JOIN `ref_vliegtuigen` `v` ON (`av`.`VLIEGTUIG_ID` = `v`.`ID`)\n			WHERE\n					`av`.`VERWIJDERD` = 0
client_cs_name=latin1
connection_cl_name=latin1_swedish_ci
view_body_utf8=select `av`.`ID` AS `ID`,`av`.`DATUM` AS `DATUM`,`av`.`VLIEGTUIG_ID` AS `VLIEGTUIG_ID`,`av`.`AANKOMST` AS `AANKOMST`,`av`.`VERTREK` AS `VERTREK`,`av`.`POSITIE` AS `POSITIE`,`av`.`HOOGTE` AS `HOOGTE`,`av`.`SNELHEID` AS `SNELHEID`,`av`.`VERWIJDERD` AS `VERWIJDERD`,`av`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING`,concat(ifnull(`v`.`REGISTRATIE`,\'\'),\' (\',ifnull(`v`.`CALLSIGN`,\'\'),\')\') AS `REGCALL` from (`dev_sa`.`oper_aanwezig_vliegtuigen` `av` left join `dev_sa`.`ref_vliegtuigen` `v` on(`av`.`VLIEGTUIG_ID` = `v`.`ID`)) where `av`.`VERWIJDERD` = 0
mariadb-version=100410
