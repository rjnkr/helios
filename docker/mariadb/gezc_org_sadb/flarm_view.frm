TYPE=VIEW
query=select `flarm`.`ID` AS `ID`,`flarm`.`DATUM` AS `DATUM`,`flarm`.`FLARM_CODE` AS `FLARM_CODE`,`rv`.`ID` AS `VLIEGTUIG_ID`,`RegCall`(`rv`.`ID`) AS `REG_CALL`,`flarm`.`STARTTIJD` AS `STARTTIJD`,`flarm`.`LANDINGSTIJD` AS `LANDINGSTIJD`,`gezc_org_sadb`.`types`.`OMSCHRIJVING` AS `BAAN` from ((`gezc_org_sadb`.`oper_flarm` `flarm` left join `gezc_org_sadb`.`ref_vliegtuigen` `rv` on(`rv`.`FLARM_CODE` = `flarm`.`FLARM_CODE`)) left join `gezc_org_sadb`.`types` on(`flarm`.`BAAN_ID` = `gezc_org_sadb`.`types`.`ID`)) order by `flarm`.`ID`
md5=91b5fa5c1741f45cb2597629d1321b40
updatable=0
algorithm=0
definer_user=gezc_org_sadb
definer_host=%
suid=1
with_check_option=0
timestamp=2019-12-07 10:53:13
create-version=2
source=select `flarm`.`ID` AS `ID`,`flarm`.`DATUM` AS `DATUM`,`flarm`.`FLARM_CODE` AS `FLARM_CODE`,`rv`.`ID` AS `VLIEGTUIG_ID`,`RegCall`(`rv`.`ID`) AS `REG_CALL`,`flarm`.`STARTTIJD` AS `STARTTIJD`,`flarm`.`LANDINGSTIJD` AS `LANDINGSTIJD`,`types`.`OMSCHRIJVING` AS `BAAN` from ((`oper_flarm` `flarm` left join `ref_vliegtuigen` `rv` on((`rv`.`FLARM_CODE` = `flarm`.`FLARM_CODE`))) left join `types` on((`flarm`.`BAAN_ID` = `types`.`ID`))) order by `flarm`.`ID`
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_general_ci
view_body_utf8=select `flarm`.`ID` AS `ID`,`flarm`.`DATUM` AS `DATUM`,`flarm`.`FLARM_CODE` AS `FLARM_CODE`,`rv`.`ID` AS `VLIEGTUIG_ID`,`RegCall`(`rv`.`ID`) AS `REG_CALL`,`flarm`.`STARTTIJD` AS `STARTTIJD`,`flarm`.`LANDINGSTIJD` AS `LANDINGSTIJD`,`gezc_org_sadb`.`types`.`OMSCHRIJVING` AS `BAAN` from ((`gezc_org_sadb`.`oper_flarm` `flarm` left join `gezc_org_sadb`.`ref_vliegtuigen` `rv` on(`rv`.`FLARM_CODE` = `flarm`.`FLARM_CODE`)) left join `gezc_org_sadb`.`types` on(`flarm`.`BAAN_ID` = `gezc_org_sadb`.`types`.`ID`)) order by `flarm`.`ID`
mariadb-version=100410
