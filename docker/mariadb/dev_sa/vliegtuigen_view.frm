TYPE=VIEW
query=select `v`.`ID` AS `ID`,`v`.`REGISTRATIE` AS `REGISTRATIE`,`v`.`CALLSIGN` AS `CALLSIGN`,`v`.`ZITPLAATSEN` AS `ZITPLAATSEN`,`v`.`CLUBKIST` AS `CLUBKIST`,`v`.`FLARMCODE` AS `FLARMCODE`,`v`.`TYPE_ID` AS `TYPE_ID`,`v`.`TMG` AS `TMG`,`v`.`ZELFSTART` AS `ZELFSTART`,`v`.`SLEEPKIST` AS `SLEEPKIST`,`v`.`VOLGORDE` AS `VOLGORDE`,`v`.`VERWIJDERD` AS `VERWIJDERD`,`v`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING`,concat(ifnull(`v`.`REGISTRATIE`,\'\'),\' (\',ifnull(`v`.`CALLSIGN`,\'\'),\')\') AS `REGCALL`,`t`.`OMSCHRIJVING` AS `VLIEGTUIGTYPE` from (`dev_sa`.`ref_vliegtuigen` `v` left join `dev_sa`.`ref_types` `t` on(`v`.`TYPE_ID` = `t`.`ID`)) where `v`.`VERWIJDERD` = 0
md5=3e86d2bd9c42a610668eb89d41875ca8
updatable=0
algorithm=0
definer_user=root
definer_host=%
suid=2
with_check_option=0
timestamp=2020-01-23 21:01:54
create-version=2
source=SELECT \n					v.*,\n					CONCAT(IFNULL(`v`.`REGISTRATIE`,\'\'),\' (\',IFNULL(`v`.`CALLSIGN`,\'\'),\')\') AS `REGCALL`,\n					`t`.`OMSCHRIJVING` AS `VLIEGTUIGTYPE`\n				FROM\n					`ref_vliegtuigen` `v`    \n					LEFT JOIN `ref_types` `t` ON (`v`.`TYPE_ID` = `t`.`ID`)\n				WHERE\n					`v`.`VERWIJDERD` = 0
client_cs_name=latin1
connection_cl_name=latin1_swedish_ci
view_body_utf8=select `v`.`ID` AS `ID`,`v`.`REGISTRATIE` AS `REGISTRATIE`,`v`.`CALLSIGN` AS `CALLSIGN`,`v`.`ZITPLAATSEN` AS `ZITPLAATSEN`,`v`.`CLUBKIST` AS `CLUBKIST`,`v`.`FLARMCODE` AS `FLARMCODE`,`v`.`TYPE_ID` AS `TYPE_ID`,`v`.`TMG` AS `TMG`,`v`.`ZELFSTART` AS `ZELFSTART`,`v`.`SLEEPKIST` AS `SLEEPKIST`,`v`.`VOLGORDE` AS `VOLGORDE`,`v`.`VERWIJDERD` AS `VERWIJDERD`,`v`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING`,concat(ifnull(`v`.`REGISTRATIE`,\'\'),\' (\',ifnull(`v`.`CALLSIGN`,\'\'),\')\') AS `REGCALL`,`t`.`OMSCHRIJVING` AS `VLIEGTUIGTYPE` from (`dev_sa`.`ref_vliegtuigen` `v` left join `dev_sa`.`ref_types` `t` on(`v`.`TYPE_ID` = `t`.`ID`)) where `v`.`VERWIJDERD` = 0
mariadb-version=100410
