TYPE=VIEW
query=select `di`.`ID` AS `ID`,`di`.`DATUM` AS `DATUM`,`di`.`VELD_ID` AS `VELD_ID`,`di`.`BAAN_ID` AS `BAAN_ID`,`di`.`BEDRIJF_ID` AS `BEDRIJF_ID`,`di`.`WINDRICHTING_ID` AS `WINDRICHTING_ID`,`di`.`WINDKRACHT_ID` AS `WINDKRACHT_ID`,`di`.`STARTMETHODE_ID` AS `STARTMETHODE_ID`,`di`.`OPMERKINGEN` AS `OPMERKINGEN`,`di`.`VLIEGBEDEDRIJF` AS `VLIEGBEDEDRIJF`,`di`.`DIENSTEN` AS `DIENSTEN`,`di`.`VERSLAG` AS `VERSLAG`,`di`.`ROLLENDMATERIEEL` AS `ROLLENDMATERIEEL`,`di`.`VLIEGENDMATERIEEL` AS `VLIEGENDMATERIEEL`,`di`.`SOORTBEDRIJF_ID` AS `SOORTBEDRIJF_ID`,`di`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING`,`T_Veld`.`CODE` AS `VELD_CODE`,`T_Veld`.`OMSCHRIJVING` AS `VELD_OMS`,`T_Baan`.`CODE` AS `BAAN_CODE`,`T_Baan`.`OMSCHRIJVING` AS `BAAN_OMS`,`T_Bedrijf`.`CODE` AS `BEDRIJF_CODE`,`T_Bedrijf`.`OMSCHRIJVING` AS `BEDRIJF_OMS`,`T_Soort`.`CODE` AS `SOORTBEDRIJF_CODE`,`T_Soort`.`OMSCHRIJVING` AS `SOORTBEDRIJF_OMS`,`T_Startmethode`.`CODE` AS `STARTMETHODE_CODE`,`T_Startmethode`.`OMSCHRIJVING` AS `STARTMETHODE_OMS` from (((((`gezc_org_sadb`.`oper_daginfo` `di` left join `gezc_org_sadb`.`types` `T_Veld` on(`di`.`VELD_ID` = `T_Veld`.`ID`)) left join `gezc_org_sadb`.`types` `T_Baan` on(`di`.`BAAN_ID` = `T_Baan`.`ID`)) left join `gezc_org_sadb`.`types` `T_Bedrijf` on(`di`.`BEDRIJF_ID` = `T_Bedrijf`.`ID`)) left join `gezc_org_sadb`.`types` `T_Soort` on(`di`.`SOORTBEDRIJF_ID` = `T_Soort`.`ID`)) left join `gezc_org_sadb`.`types` `T_Startmethode` on(`di`.`STARTMETHODE_ID` = `T_Startmethode`.`ID`))
md5=6b3dbcdf2ec231118448ad0262cb6b17
updatable=0
algorithm=0
definer_user=root
definer_host=%
suid=1
with_check_option=0
timestamp=2019-12-13 11:39:13
create-version=2
source=SELECT 
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_unicode_ci
view_body_utf8=select `di`.`ID` AS `ID`,`di`.`DATUM` AS `DATUM`,`di`.`VELD_ID` AS `VELD_ID`,`di`.`BAAN_ID` AS `BAAN_ID`,`di`.`BEDRIJF_ID` AS `BEDRIJF_ID`,`di`.`WINDRICHTING_ID` AS `WINDRICHTING_ID`,`di`.`WINDKRACHT_ID` AS `WINDKRACHT_ID`,`di`.`STARTMETHODE_ID` AS `STARTMETHODE_ID`,`di`.`OPMERKINGEN` AS `OPMERKINGEN`,`di`.`VLIEGBEDEDRIJF` AS `VLIEGBEDEDRIJF`,`di`.`DIENSTEN` AS `DIENSTEN`,`di`.`VERSLAG` AS `VERSLAG`,`di`.`ROLLENDMATERIEEL` AS `ROLLENDMATERIEEL`,`di`.`VLIEGENDMATERIEEL` AS `VLIEGENDMATERIEEL`,`di`.`SOORTBEDRIJF_ID` AS `SOORTBEDRIJF_ID`,`di`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING`,`T_Veld`.`CODE` AS `VELD_CODE`,`T_Veld`.`OMSCHRIJVING` AS `VELD_OMS`,`T_Baan`.`CODE` AS `BAAN_CODE`,`T_Baan`.`OMSCHRIJVING` AS `BAAN_OMS`,`T_Bedrijf`.`CODE` AS `BEDRIJF_CODE`,`T_Bedrijf`.`OMSCHRIJVING` AS `BEDRIJF_OMS`,`T_Soort`.`CODE` AS `SOORTBEDRIJF_CODE`,`T_Soort`.`OMSCHRIJVING` AS `SOORTBEDRIJF_OMS`,`T_Startmethode`.`CODE` AS `STARTMETHODE_CODE`,`T_Startmethode`.`OMSCHRIJVING` AS `STARTMETHODE_OMS` from (((((`gezc_org_sadb`.`oper_daginfo` `di` left join `gezc_org_sadb`.`types` `T_Veld` on(`di`.`VELD_ID` = `T_Veld`.`ID`)) left join `gezc_org_sadb`.`types` `T_Baan` on(`di`.`BAAN_ID` = `T_Baan`.`ID`)) left join `gezc_org_sadb`.`types` `T_Bedrijf` on(`di`.`BEDRIJF_ID` = `T_Bedrijf`.`ID`)) left join `gezc_org_sadb`.`types` `T_Soort` on(`di`.`SOORTBEDRIJF_ID` = `T_Soort`.`ID`)) left join `gezc_org_sadb`.`types` `T_Startmethode` on(`di`.`STARTMETHODE_ID` = `T_Startmethode`.`ID`))
mariadb-version=100410