TYPE=VIEW
query=(select case when `sl`.`VLIEGERNAAM` is not null then concat(ifnull(`sl`.`VLIEGERNAAM_LID`,\'\'),\' (\',ifnull(`sl`.`VLIEGERNAAM`,\'\'),\')\') else `sl`.`VLIEGERNAAM_LID` end AS `VLIEGERNAAM`,`sl`.`VLIEGER_ID` AS `VLIEGER_ID`,`sl`.`INZITTENDE_ID` AS `INZITTENDE_ID`,`sl`.`OP_REKENING_ID` AS `OP_REKENING_ID`,`sl`.`DAGNUMMER` AS `DAGNUMMER`,`sl`.`REGISTRATIE` AS `REGISTRATIE`,`t`.`CODE` AS `VLIEGTUIG_TYPE`,`sl`.`STARTTIJD` AS `STARTTIJD`,`sl`.`DUUR` AS `DUUR`,`sl`.`DATUM` AS `DATUM` from ((`gezc_org_sadb`.`startlijst_view` `sl` left join `gezc_org_sadb`.`ref_vliegtuigen` `v` on(`sl`.`VLIEGTUIG_ID` = `v`.`ID`)) left join `gezc_org_sadb`.`types` `t` on(`v`.`TYPE_ID` = `t`.`ID`)) where `v`.`CLUBKIST` = 1 and `sl`.`LANDINGSTIJD` is not null order by `sl`.`VLIEGERNAAM_LID`,`sl`.`VLIEGERNAAM`,`sl`.`STARTTIJD`)
md5=a2dac950b976d5b109e584b4f2234b19
updatable=0
algorithm=0
definer_user=gezc_org_sadb
definer_host=%
suid=1
with_check_option=0
timestamp=2019-12-07 10:53:13
create-version=2
source=(select (case when (`sl`.`VLIEGERNAAM` is not null) then concat(ifnull(`sl`.`VLIEGERNAAM_LID`,\'\'),\' (\',ifnull(`sl`.`VLIEGERNAAM`,\'\'),\')\') else `sl`.`VLIEGERNAAM_LID` end) AS `VLIEGERNAAM`,`sl`.`VLIEGER_ID` AS `VLIEGER_ID`,`sl`.`INZITTENDE_ID` AS `INZITTENDE_ID`,`sl`.`OP_REKENING_ID` AS `OP_REKENING_ID`,`sl`.`DAGNUMMER` AS `DAGNUMMER`,`sl`.`REGISTRATIE` AS `REGISTRATIE`,`t`.`CODE` AS `VLIEGTUIG_TYPE`,`sl`.`STARTTIJD` AS `STARTTIJD`,`sl`.`DUUR` AS `DUUR`,`sl`.`DATUM` AS `DATUM` from ((`startlijst_view` `sl` left join `ref_vliegtuigen` `v` on((`sl`.`VLIEGTUIG_ID` = `v`.`ID`))) left join `types` `t` on((`v`.`TYPE_ID` = `t`.`ID`))) where ((`v`.`CLUBKIST` = 1) and (`sl`.`LANDINGSTIJD` is not null)) order by `sl`.`VLIEGERNAAM_LID`,`sl`.`VLIEGERNAAM`,`sl`.`STARTTIJD`)
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_general_ci
view_body_utf8=(select case when `sl`.`VLIEGERNAAM` is not null then concat(ifnull(`sl`.`VLIEGERNAAM_LID`,\'\'),\' (\',ifnull(`sl`.`VLIEGERNAAM`,\'\'),\')\') else `sl`.`VLIEGERNAAM_LID` end AS `VLIEGERNAAM`,`sl`.`VLIEGER_ID` AS `VLIEGER_ID`,`sl`.`INZITTENDE_ID` AS `INZITTENDE_ID`,`sl`.`OP_REKENING_ID` AS `OP_REKENING_ID`,`sl`.`DAGNUMMER` AS `DAGNUMMER`,`sl`.`REGISTRATIE` AS `REGISTRATIE`,`t`.`CODE` AS `VLIEGTUIG_TYPE`,`sl`.`STARTTIJD` AS `STARTTIJD`,`sl`.`DUUR` AS `DUUR`,`sl`.`DATUM` AS `DATUM` from ((`gezc_org_sadb`.`startlijst_view` `sl` left join `gezc_org_sadb`.`ref_vliegtuigen` `v` on(`sl`.`VLIEGTUIG_ID` = `v`.`ID`)) left join `gezc_org_sadb`.`types` `t` on(`v`.`TYPE_ID` = `t`.`ID`)) where `v`.`CLUBKIST` = 1 and `sl`.`LANDINGSTIJD` is not null order by `sl`.`VLIEGERNAAM_LID`,`sl`.`VLIEGERNAAM`,`sl`.`STARTTIJD`)
mariadb-version=100410
