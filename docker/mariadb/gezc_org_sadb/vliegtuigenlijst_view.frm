TYPE=VIEW
query=(select `v`.`ID` AS `ID`,`v`.`REGISTRATIE` AS `REGISTRATIE`,`v`.`CALLSIGN` AS `CALLSIGN`,`RegCall`(`v`.`ID`) AS `REGCALL`,`v`.`VOLGORDE` AS `VOLGORDE`,`v`.`ZITPLAATSEN` AS `ZITPLAATSEN`,`v`.`FLARM_CODE` AS `FLARM_CODE`,`v`.`TYPE_ID` AS `TYPE_ID`,`v`.`TMG` AS `TMG`,`v`.`SLEEPKIST` AS `SLEEPKIST`,`v`.`ZELFSTART` AS `ZELFSTART`,`v`.`CLUBKIST` AS `CLUBKIST`,`t`.`OMSCHRIJVING` AS `VLIEGTUIGTYPE`,`v`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING` from (`gezc_org_sadb`.`ref_vliegtuigen` `v` left join `gezc_org_sadb`.`types` `t` on(`v`.`TYPE_ID` = `t`.`ID`)) where `v`.`VERWIJDERD` = 0)
md5=2e2732daad0b4ea99a9c2ec8b613c504
updatable=0
algorithm=0
definer_user=gezc_org_sadb
definer_host=%
suid=1
with_check_option=0
timestamp=2019-12-08 22:14:50
create-version=2
source=(select `v`.`ID` AS `ID`,`v`.`REGISTRATIE` AS `REGISTRATIE`,`v`.`CALLSIGN` AS `CALLSIGN`,\n`RegCall`(`v`.`ID`) AS `REGCALL`,`v`.`VOLGORDE` AS `VOLGORDE`,`v`.`ZITPLAATSEN` AS `ZITPLAATSEN`,\n`v`.`FLARM_CODE` AS `FLARM_CODE`,`v`.`TYPE_ID` AS `TYPE_ID`,\n `v`.`TMG`  AS `TMG`, `v`.`SLEEPKIST`  AS `SLEEPKIST`, `v`.`ZELFSTART`  AS `ZELFSTART`, `v`.`CLUBKIST`  AS `CLUBKIST`,`t`.`OMSCHRIJVING` AS `VLIEGTUIGTYPE`,`v`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING` from (`gezc_org_sadb`.`ref_vliegtuigen` `v` left join `gezc_org_sadb`.`types` `t` on(`v`.`TYPE_ID` = `t`.`ID`)) where `v`.`VERWIJDERD` = 0)
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_unicode_ci
view_body_utf8=(select `v`.`ID` AS `ID`,`v`.`REGISTRATIE` AS `REGISTRATIE`,`v`.`CALLSIGN` AS `CALLSIGN`,`RegCall`(`v`.`ID`) AS `REGCALL`,`v`.`VOLGORDE` AS `VOLGORDE`,`v`.`ZITPLAATSEN` AS `ZITPLAATSEN`,`v`.`FLARM_CODE` AS `FLARM_CODE`,`v`.`TYPE_ID` AS `TYPE_ID`,`v`.`TMG` AS `TMG`,`v`.`SLEEPKIST` AS `SLEEPKIST`,`v`.`ZELFSTART` AS `ZELFSTART`,`v`.`CLUBKIST` AS `CLUBKIST`,`t`.`OMSCHRIJVING` AS `VLIEGTUIGTYPE`,`v`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING` from (`gezc_org_sadb`.`ref_vliegtuigen` `v` left join `gezc_org_sadb`.`types` `t` on(`v`.`TYPE_ID` = `t`.`ID`)) where `v`.`VERWIJDERD` = 0)
mariadb-version=100410
