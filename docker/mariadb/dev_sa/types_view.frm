TYPE=VIEW
query=select `types`.`ID` AS `ID`,`types`.`GROEP` AS `GROEP`,`types`.`CODE` AS `CODE`,`types`.`EXT_REF` AS `EXT_REF`,`types`.`OMSCHRIJVING` AS `OMSCHRIJVING`,`types`.`SORTEER_VOLGORDE` AS `SORTEER_VOLGORDE`,`types`.`READ_ONLY` AS `READ_ONLY`,`types`.`VERWIJDERD` AS `VERWIJDERD`,`types`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING` from `dev_sa`.`ref_types` `types` where `types`.`VERWIJDERD` = 0
md5=dcfffaa313f8f17401b64de82eb79b65
updatable=1
algorithm=0
definer_user=root
definer_host=%
suid=2
with_check_option=0
timestamp=2020-01-23 21:01:09
create-version=2
source=SELECT \n					types.*\n				FROM\n					`ref_types` `types`\n			WHERE\n					`types`.`VERWIJDERD` = 0
client_cs_name=latin1
connection_cl_name=latin1_swedish_ci
view_body_utf8=select `types`.`ID` AS `ID`,`types`.`GROEP` AS `GROEP`,`types`.`CODE` AS `CODE`,`types`.`EXT_REF` AS `EXT_REF`,`types`.`OMSCHRIJVING` AS `OMSCHRIJVING`,`types`.`SORTEER_VOLGORDE` AS `SORTEER_VOLGORDE`,`types`.`READ_ONLY` AS `READ_ONLY`,`types`.`VERWIJDERD` AS `VERWIJDERD`,`types`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING` from `dev_sa`.`ref_types` `types` where `types`.`VERWIJDERD` = 0
mariadb-version=100410
