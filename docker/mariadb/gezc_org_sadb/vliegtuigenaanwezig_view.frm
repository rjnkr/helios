TYPE=VIEW
query=(select `a`.`ID` AS `ID`,`v`.`ID` AS `VLIEGTUIG_ID`,`v`.`REGISTRATIE` AS `REGISTRATIE`,`v`.`CALLSIGN` AS `CALLSIGN`,`v`.`ZITPLAATSEN` AS `ZITPLAATSEN`,`v`.`CLUBKIST` AS `CLUBKIST`,`v`.`VLIEGTUIGTYPE` AS `VLIEGTUIGTYPE`,time_format(`a`.`AANKOMST`,\'%H:%i\') AS `AANKOMST`,time_format(`a`.`VERTREK`,\'%H:%i\') AS `VERTREK`,`a`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING` from (`gezc_org_sadb`.`vliegtuigenlijst_view` `v` join `gezc_org_sadb`.`oper_aanwezig` `a` on(`v`.`ID` = `a`.`VLIEGTUIG_ID`)) where `a`.`DATUM` = cast(current_timestamp() as date))
md5=5601db472744e140a4b42e682f0fb959
updatable=1
algorithm=0
definer_user=gezc_org_sadb
definer_host=%
suid=1
with_check_option=0
timestamp=2019-12-07 10:53:13
create-version=2
source=(select `a`.`ID` AS `ID`,`v`.`ID` AS `VLIEGTUIG_ID`,`v`.`REGISTRATIE` AS `REGISTRATIE`,`v`.`CALLSIGN` AS `CALLSIGN`,`v`.`ZITPLAATSEN` AS `ZITPLAATSEN`,`v`.`CLUBKIST` AS `CLUBKIST`,`v`.`VLIEGTUIGTYPE` AS `VLIEGTUIGTYPE`,time_format(`a`.`AANKOMST`,\'%H:%i\') AS `AANKOMST`,time_format(`a`.`VERTREK`,\'%H:%i\') AS `VERTREK`,`a`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING` from (`vliegtuigenlijst_view` `v` join `oper_aanwezig` `a` on((`v`.`ID` = `a`.`VLIEGTUIG_ID`))) where (`a`.`DATUM` = cast(now() as date)))
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_general_ci
view_body_utf8=(select `a`.`ID` AS `ID`,`v`.`ID` AS `VLIEGTUIG_ID`,`v`.`REGISTRATIE` AS `REGISTRATIE`,`v`.`CALLSIGN` AS `CALLSIGN`,`v`.`ZITPLAATSEN` AS `ZITPLAATSEN`,`v`.`CLUBKIST` AS `CLUBKIST`,`v`.`VLIEGTUIGTYPE` AS `VLIEGTUIGTYPE`,time_format(`a`.`AANKOMST`,\'%H:%i\') AS `AANKOMST`,time_format(`a`.`VERTREK`,\'%H:%i\') AS `VERTREK`,`a`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING` from (`gezc_org_sadb`.`vliegtuigenlijst_view` `v` join `gezc_org_sadb`.`oper_aanwezig` `a` on(`v`.`ID` = `a`.`VLIEGTUIG_ID`)) where `a`.`DATUM` = cast(current_timestamp() as date))
mariadb-version=100410
