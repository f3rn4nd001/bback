
DELIMITER $$
DROP PROCEDURE IF EXISTS stpInsertarBitMenjes;
CREATE PROCEDURE `stpInsertarBitMenjes`(tMensaje varchar(250))
BEGIN
insert into bitmensaje(`tMensaje`,`fhSalida`)
values (tMensaje,NOW());
SELECT  LAST_INSERT_ID() AS Codigo;
end$$