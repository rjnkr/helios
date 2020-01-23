TYPE=VIEW
query=(select `rv`.`ID` AS `ID`,`REGCALL`(`rv`.`ID`) AS `REG_CALL`,`rv`.`ZITPLAATSEN` AS `ZITPLAATSEN`,`rv`.`TMG` AS `TMG`,`rv`.`SLEEPKIST` AS `SLEEPKIST`,`rv`.`ZELFSTART` AS `ZELFSTART`,`rv`.`TYPE_ID` AS `TYPE_ID`,`rv`.`CLUBKIST` AS `CLUBKIST`,`VLIEGTUIGVLIEGT`(`rv`.`ID`) AS `VLIEGT`,`VLIEGTUIGOVERLAND`(`rv`.`ID`) AS `OVERLAND`,`VLIEGTUIGAANWEZIGVANDAAG`(`rv`.`ID`) AS `AANWEZIG`,coalesce((select `gezc_org_sadb`.`oper_aanwezig`.`LAATSTE_AANPASSING` from `gezc_org_sadb`.`oper_aanwezig` where `gezc_org_sadb`.`oper_aanwezig`.`VLIEGTUIG_ID` = `rv`.`ID` order by `gezc_org_sadb`.`oper_aanwezig`.`LAATSTE_AANPASSING` desc limit 0,1),`rv`.`LAATSTE_AANPASSING`) AS `LAATSTE_AANPASSING` from `gezc_org_sadb`.`ref_vliegtuigen` `rv` where `rv`.`VERWIJDERD` = 0)
md5=d070d765853da571103781e28cabf81f
updatable=0
algorithm=0
definer_user=gezc_org_sadb
definer_host=%
suid=1
with_check_option=0
timestamp=2019-12-07 10:53:13
create-version=2
source=(select `rv`.`ID` AS `ID`,`REGCALL`(`rv`.`ID`) AS `REG_CALL`,`rv`.`ZITPLAATSEN` AS `ZITPLAATSEN`,`rv`.`TMG` AS `TMG`,`rv`.`SLEEPKIST` AS `SLEEPKIST`,`rv`.`ZELFSTART` AS `ZELFSTART`,`rv`.`TYPE_ID` AS `TYPE_ID`,`rv`.`CLUBKIST` AS `CLUBKIST`,`VLIEGTUIGVLIEGT`(`rv`.`ID`) AS `VLIEGT`,`VLIEGTUIGOVERLAND`(`rv`.`ID`) AS `OVERLAND`,`VLIEGTUIGAANWEZIGVANDAAG`(`rv`.`ID`) AS `AANWEZIG`,coalesce((select `oper_aanwezig`.`LAATSTE_AANPASSING` from `oper_aanwezig` where (`oper_aanwezig`.`VLIEGTUIG_ID` = `rv`.`ID`) order by `oper_aanwezig`.`LAATSTE_AANPASSING` desc limit 0,1),`rv`.`LAATSTE_AANPASSING`) AS `LAATSTE_AANPASSING` from `ref_vliegtuigen` `rv` where (`rv`.`VERWIJDERD` = 0))
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_general_ci
view_body_utf8=(select `rv`.`ID` AS `ID`,`REGCALL`(`rv`.`ID`) AS `REG_CALL`,`rv`.`ZITPLAATSEN` AS `ZITPLAATSEN`,`rv`.`TMG` AS `TMG`,`rv`.`SLEEPKIST` AS `SLEEPKIST`,`rv`.`ZELFSTART` AS `ZELFSTART`,`rv`.`TYPE_ID` AS `TYPE_ID`,`rv`.`CLUBKIST` AS `CLUBKIST`,`VLIEGTUIGVLIEGT`(`rv`.`ID`) AS `VLIEGT`,`VLIEGTUIGOVERLAND`(`rv`.`ID`) AS `OVERLAND`,`VLIEGTUIGAANWEZIGVANDAAG`(`rv`.`ID`) AS `AANWEZIG`,coalesce((select `gezc_org_sadb`.`oper_aanwezig`.`LAATSTE_AANPASSING` from `gezc_org_sadb`.`oper_aanwezig` where `gezc_org_sadb`.`oper_aanwezig`.`VLIEGTUIG_ID` = `rv`.`ID` order by `gezc_org_sadb`.`oper_aanwezig`.`LAATSTE_AANPASSING` desc limit 0,1),`rv`.`LAATSTE_AANPASSING`) AS `LAATSTE_AANPASSING` from `gezc_org_sadb`.`ref_vliegtuigen` `rv` where `rv`.`VERWIJDERD` = 0)
mariadb-version=100410
