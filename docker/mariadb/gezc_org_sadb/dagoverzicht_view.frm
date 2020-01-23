TYPE=VIEW
query=select `rf`.`ID` AS `ID`,`rf`.`NAAM` AS `NAAM`,`aanwezig`.`DATUM` AS `DATUM`,`aanwezig`.`OPMERKING` AS `OPMERKING`,`aanwezig`.`DDWV_VOORAANMELDING` AS `DDWV_VOORAANMELDING`,(select count(0) AS `STARTS` from `gezc_org_sadb`.`oper_startlijst` where (`gezc_org_sadb`.`oper_startlijst`.`VLIEGER_ID` = `aanwezig`.`LID_ID` or `gezc_org_sadb`.`oper_startlijst`.`INZITTENDE_ID` = `aanwezig`.`LID_ID`) and (`gezc_org_sadb`.`oper_startlijst`.`STARTTIJD` is not null or `gezc_org_sadb`.`oper_startlijst`.`LANDINGSTIJD` is not null) and `gezc_org_sadb`.`oper_startlijst`.`SOORTVLUCHT_ID` <> 815 and `gezc_org_sadb`.`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`) AS `STARTS`,(select count(0) AS `VLIEGER` from `gezc_org_sadb`.`oper_startlijst` where `gezc_org_sadb`.`oper_startlijst`.`VLIEGER_ID` = `aanwezig`.`LID_ID` and (`gezc_org_sadb`.`oper_startlijst`.`STARTTIJD` is not null or `gezc_org_sadb`.`oper_startlijst`.`LANDINGSTIJD` is not null) and `gezc_org_sadb`.`oper_startlijst`.`SOORTVLUCHT_ID` <> 815 and `gezc_org_sadb`.`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`) AS `VLIEGER`,(select count(0) AS `INZITTENDENAAM` from `gezc_org_sadb`.`oper_startlijst` where `gezc_org_sadb`.`oper_startlijst`.`INZITTENDE_ID` = `aanwezig`.`LID_ID` and (`gezc_org_sadb`.`oper_startlijst`.`STARTTIJD` is not null or `gezc_org_sadb`.`oper_startlijst`.`LANDINGSTIJD` is not null) and `gezc_org_sadb`.`oper_startlijst`.`SOORTVLUCHT_ID` <> 815 and `gezc_org_sadb`.`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`) AS `INZITTENDE`,(select count(0) AS `DDWV` from `gezc_org_sadb`.`oper_startlijst` where `gezc_org_sadb`.`oper_startlijst`.`OP_REKENING_ID` = `aanwezig`.`LID_ID` and (`gezc_org_sadb`.`oper_startlijst`.`STARTTIJD` is not null or `gezc_org_sadb`.`oper_startlijst`.`LANDINGSTIJD` is not null) and `gezc_org_sadb`.`oper_startlijst`.`SOORTVLUCHT_ID` = 814 and `gezc_org_sadb`.`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`) AS `DDWV`,(select count(0) AS `SLEEP` from `gezc_org_sadb`.`oper_startlijst` where `gezc_org_sadb`.`oper_startlijst`.`OP_REKENING_ID` = `aanwezig`.`LID_ID` and `gezc_org_sadb`.`oper_startlijst`.`STARTMETHODE_ID` = 501 and (`gezc_org_sadb`.`oper_startlijst`.`STARTTIJD` is not null or `gezc_org_sadb`.`oper_startlijst`.`LANDINGSTIJD` is not null) and `gezc_org_sadb`.`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`) AS `SLEEP`,(select count(0) AS `OPREKENING` from `gezc_org_sadb`.`oper_startlijst` where `gezc_org_sadb`.`oper_startlijst`.`OP_REKENING_ID` = `aanwezig`.`LID_ID` and (`gezc_org_sadb`.`oper_startlijst`.`STARTTIJD` is not null or `gezc_org_sadb`.`oper_startlijst`.`LANDINGSTIJD` is not null) and `gezc_org_sadb`.`oper_startlijst`.`SOORTVLUCHT_ID` <> 815 and `gezc_org_sadb`.`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`) AS `OPREKENING` from (`gezc_org_sadb`.`oper_aanwezig` `aanwezig` join `gezc_org_sadb`.`ref_leden` `rf` on(`aanwezig`.`LID_ID` = `rf`.`ID`)) where `aanwezig`.`LID_ID` is not null order by `rf`.`NAAM`
md5=d08054bb356f01a0b33047f87be62dc5
updatable=1
algorithm=0
definer_user=gezc_org_sadb
definer_host=%
suid=1
with_check_option=0
timestamp=2019-12-07 10:53:13
create-version=2
source=select `rf`.`ID` AS `ID`,`rf`.`NAAM` AS `NAAM`,`aanwezig`.`DATUM` AS `DATUM`,`aanwezig`.`OPMERKING` AS `OPMERKING`,`aanwezig`.`DDWV_VOORAANMELDING` AS `DDWV_VOORAANMELDING`,(select count(0) AS `STARTS` from `oper_startlijst` where (((`oper_startlijst`.`VLIEGER_ID` = `aanwezig`.`LID_ID`) or (`oper_startlijst`.`INZITTENDE_ID` = `aanwezig`.`LID_ID`)) and ((`oper_startlijst`.`STARTTIJD` is not null) or (`oper_startlijst`.`LANDINGSTIJD` is not null)) and (`oper_startlijst`.`SOORTVLUCHT_ID` <> 815) and (`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`))) AS `STARTS`,(select count(0) AS `VLIEGER` from `oper_startlijst` where ((`oper_startlijst`.`VLIEGER_ID` = `aanwezig`.`LID_ID`) and ((`oper_startlijst`.`STARTTIJD` is not null) or (`oper_startlijst`.`LANDINGSTIJD` is not null)) and (`oper_startlijst`.`SOORTVLUCHT_ID` <> 815) and (`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`))) AS `VLIEGER`,(select count(0) AS `INZITTENDENAAM` from `oper_startlijst` where ((`oper_startlijst`.`INZITTENDE_ID` = `aanwezig`.`LID_ID`) and ((`oper_startlijst`.`STARTTIJD` is not null) or (`oper_startlijst`.`LANDINGSTIJD` is not null)) and (`oper_startlijst`.`SOORTVLUCHT_ID` <> 815) and (`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`))) AS `INZITTENDE`,(select count(0) AS `DDWV` from `oper_startlijst` where ((`oper_startlijst`.`OP_REKENING_ID` = `aanwezig`.`LID_ID`) and ((`oper_startlijst`.`STARTTIJD` is not null) or (`oper_startlijst`.`LANDINGSTIJD` is not null)) and (`oper_startlijst`.`SOORTVLUCHT_ID` = 814) and (`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`))) AS `DDWV`,(select count(0) AS `SLEEP` from `oper_startlijst` where ((`oper_startlijst`.`OP_REKENING_ID` = `aanwezig`.`LID_ID`) and (`oper_startlijst`.`STARTMETHODE_ID` = 501) and ((`oper_startlijst`.`STARTTIJD` is not null) or (`oper_startlijst`.`LANDINGSTIJD` is not null)) and (`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`))) AS `SLEEP`,(select count(0) AS `OPREKENING` from `oper_startlijst` where ((`oper_startlijst`.`OP_REKENING_ID` = `aanwezig`.`LID_ID`) and ((`oper_startlijst`.`STARTTIJD` is not null) or (`oper_startlijst`.`LANDINGSTIJD` is not null)) and (`oper_startlijst`.`SOORTVLUCHT_ID` <> 815) and (`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`))) AS `OPREKENING` from (`oper_aanwezig` `aanwezig` join `ref_leden` `rf` on((`aanwezig`.`LID_ID` = `rf`.`ID`))) where (`aanwezig`.`LID_ID` is not null) order by `rf`.`NAAM`
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_general_ci
view_body_utf8=select `rf`.`ID` AS `ID`,`rf`.`NAAM` AS `NAAM`,`aanwezig`.`DATUM` AS `DATUM`,`aanwezig`.`OPMERKING` AS `OPMERKING`,`aanwezig`.`DDWV_VOORAANMELDING` AS `DDWV_VOORAANMELDING`,(select count(0) AS `STARTS` from `gezc_org_sadb`.`oper_startlijst` where (`gezc_org_sadb`.`oper_startlijst`.`VLIEGER_ID` = `aanwezig`.`LID_ID` or `gezc_org_sadb`.`oper_startlijst`.`INZITTENDE_ID` = `aanwezig`.`LID_ID`) and (`gezc_org_sadb`.`oper_startlijst`.`STARTTIJD` is not null or `gezc_org_sadb`.`oper_startlijst`.`LANDINGSTIJD` is not null) and `gezc_org_sadb`.`oper_startlijst`.`SOORTVLUCHT_ID` <> 815 and `gezc_org_sadb`.`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`) AS `STARTS`,(select count(0) AS `VLIEGER` from `gezc_org_sadb`.`oper_startlijst` where `gezc_org_sadb`.`oper_startlijst`.`VLIEGER_ID` = `aanwezig`.`LID_ID` and (`gezc_org_sadb`.`oper_startlijst`.`STARTTIJD` is not null or `gezc_org_sadb`.`oper_startlijst`.`LANDINGSTIJD` is not null) and `gezc_org_sadb`.`oper_startlijst`.`SOORTVLUCHT_ID` <> 815 and `gezc_org_sadb`.`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`) AS `VLIEGER`,(select count(0) AS `INZITTENDENAAM` from `gezc_org_sadb`.`oper_startlijst` where `gezc_org_sadb`.`oper_startlijst`.`INZITTENDE_ID` = `aanwezig`.`LID_ID` and (`gezc_org_sadb`.`oper_startlijst`.`STARTTIJD` is not null or `gezc_org_sadb`.`oper_startlijst`.`LANDINGSTIJD` is not null) and `gezc_org_sadb`.`oper_startlijst`.`SOORTVLUCHT_ID` <> 815 and `gezc_org_sadb`.`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`) AS `INZITTENDE`,(select count(0) AS `DDWV` from `gezc_org_sadb`.`oper_startlijst` where `gezc_org_sadb`.`oper_startlijst`.`OP_REKENING_ID` = `aanwezig`.`LID_ID` and (`gezc_org_sadb`.`oper_startlijst`.`STARTTIJD` is not null or `gezc_org_sadb`.`oper_startlijst`.`LANDINGSTIJD` is not null) and `gezc_org_sadb`.`oper_startlijst`.`SOORTVLUCHT_ID` = 814 and `gezc_org_sadb`.`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`) AS `DDWV`,(select count(0) AS `SLEEP` from `gezc_org_sadb`.`oper_startlijst` where `gezc_org_sadb`.`oper_startlijst`.`OP_REKENING_ID` = `aanwezig`.`LID_ID` and `gezc_org_sadb`.`oper_startlijst`.`STARTMETHODE_ID` = 501 and (`gezc_org_sadb`.`oper_startlijst`.`STARTTIJD` is not null or `gezc_org_sadb`.`oper_startlijst`.`LANDINGSTIJD` is not null) and `gezc_org_sadb`.`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`) AS `SLEEP`,(select count(0) AS `OPREKENING` from `gezc_org_sadb`.`oper_startlijst` where `gezc_org_sadb`.`oper_startlijst`.`OP_REKENING_ID` = `aanwezig`.`LID_ID` and (`gezc_org_sadb`.`oper_startlijst`.`STARTTIJD` is not null or `gezc_org_sadb`.`oper_startlijst`.`LANDINGSTIJD` is not null) and `gezc_org_sadb`.`oper_startlijst`.`SOORTVLUCHT_ID` <> 815 and `gezc_org_sadb`.`oper_startlijst`.`DATUM` = `aanwezig`.`DATUM`) AS `OPREKENING` from (`gezc_org_sadb`.`oper_aanwezig` `aanwezig` join `gezc_org_sadb`.`ref_leden` `rf` on(`aanwezig`.`LID_ID` = `rf`.`ID`)) where `aanwezig`.`LID_ID` is not null order by `rf`.`NAAM`
mariadb-version=100410