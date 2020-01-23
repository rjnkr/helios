TYPE=VIEW
query=select `l`.`ID` AS `ID`,`l`.`NAAM` AS `NAAM`,`l`.`VOORNAAM` AS `VOORNAAM`,`l`.`ACHTERNAAM` AS `ACHTERNAAM`,`l`.`TELEFOON` AS `TELEFOON`,`l`.`MOBIEL` AS `MOBIEL`,`l`.`NOODNUMMER` AS `NOODNUMMER`,`l`.`EMAIL` AS `EMAIL`,`l`.`LIDNR` AS `LIDNR`,`l`.`LIDTYPE_ID` AS `LIDTYPE_ID`,`l`.`LIERIST` AS `LIERIST`,`l`.`STARTLEIDER` AS `STARTLEIDER`,`l`.`INSTRUCTEUR` AS `INSTRUCTEUR`,`l`.`INLOGNAAM` AS `INLOGNAAM`,`l`.`WACHTWOORD` AS `WACHTWOORD`,`l`.`HEEFT_BETAALD` AS `HEEFT_BETAALD`,`l`.`PRIVACY` AS `PRIVACY`,`l`.`VERWIJDERD` AS `VERWIJDERD`,`l`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING`,`t`.`OMSCHRIJVING` AS `LIDTYPE` from (`dev_sa`.`ref_leden` `l` left join `dev_sa`.`ref_types` `t` on(`l`.`LIDTYPE_ID` = `t`.`ID`)) where `l`.`VERWIJDERD` = 0
md5=09c8c9c197a49992b58c8760d1867cd6
updatable=0
algorithm=0
definer_user=root
definer_host=%
suid=2
with_check_option=0
timestamp=2020-01-23 21:02:29
create-version=2
source=SELECT \n					l.*,\n					`t`.`OMSCHRIJVING` AS `LIDTYPE`\n				FROM\n					`ref_leden` `l`    \n					LEFT JOIN `ref_types` `t` ON (`l`.`LIDTYPE_ID` = `t`.`ID`)\n				WHERE\n					`l`.`VERWIJDERD` = 0
client_cs_name=latin1
connection_cl_name=latin1_swedish_ci
view_body_utf8=select `l`.`ID` AS `ID`,`l`.`NAAM` AS `NAAM`,`l`.`VOORNAAM` AS `VOORNAAM`,`l`.`ACHTERNAAM` AS `ACHTERNAAM`,`l`.`TELEFOON` AS `TELEFOON`,`l`.`MOBIEL` AS `MOBIEL`,`l`.`NOODNUMMER` AS `NOODNUMMER`,`l`.`EMAIL` AS `EMAIL`,`l`.`LIDNR` AS `LIDNR`,`l`.`LIDTYPE_ID` AS `LIDTYPE_ID`,`l`.`LIERIST` AS `LIERIST`,`l`.`STARTLEIDER` AS `STARTLEIDER`,`l`.`INSTRUCTEUR` AS `INSTRUCTEUR`,`l`.`INLOGNAAM` AS `INLOGNAAM`,`l`.`WACHTWOORD` AS `WACHTWOORD`,`l`.`HEEFT_BETAALD` AS `HEEFT_BETAALD`,`l`.`PRIVACY` AS `PRIVACY`,`l`.`VERWIJDERD` AS `VERWIJDERD`,`l`.`LAATSTE_AANPASSING` AS `LAATSTE_AANPASSING`,`t`.`OMSCHRIJVING` AS `LIDTYPE` from (`dev_sa`.`ref_leden` `l` left join `dev_sa`.`ref_types` `t` on(`l`.`LIDTYPE_ID` = `t`.`ID`)) where `l`.`VERWIJDERD` = 0
mariadb-version=100410
