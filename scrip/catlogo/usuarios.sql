BEGIN

declare existe int;
set existe = (select count(*) from catusuarios where trfc = tRFCv);
if existe =0


then
 
SELECT existe;

insert into catusuarios(`trfc`, `tNombre`, `tApellido`,`fCreacion`,`EcodEstatus`)
values (tRFCv,tNombrev,tApellidov,NOW(),EcodEstatusv);

select LAST_INSERT_ID() AS Codigo;

else

SELECT ecodUsuarios AS Codigo from catusuarios where trfc = tRFCv;
UPDATE catusuarios set tNombre = tNombrev, tApellido=tApellidov where trfc =tRFCv;

END if;
end


DELIMITER $$
DROP PROCEDURE IF EXISTS stpInsertarBitCorreo;
CREATE PROCEDURE `stpInsertarBitCorreo`(tCorreov VARCHAR(150),tpasswordv VARCHAR(250))

BEGIN
declare existe int;
set existe = (select count(*) from bitcorreos where tcorreo = tCorreov);
if existe = 0
then

insert into bitcorreos(`tcorreo`,`tpassword`)
values (tCorreov,tpassword);
SELECT  LAST_INSERT_ID() AS Codigo;
else

SELECT ecodCorreo AS Codigo from bitcorreos where tcorreo = tCorreov;
UPDATE bitcorreos set tpassword = tpasswordv where tcorreo = tCorreov;
END if;
end