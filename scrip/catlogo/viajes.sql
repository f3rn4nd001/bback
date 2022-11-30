DELIMITER $$
DROP PROCEDURE IF EXISTS stpInsertarBitCelular;
CREATE PROCEDURE `stpInsertarBitCelular`(telefono varchar(250))
BEGIN
declare existe int;
set existe = (select count(*) from bitcelular where tcelular = telefono);
if existe = 0

then
select existe;

insert into bitcelular(`tcelular`)
values (telefono);

SELECT  LAST_INSERT_ID() AS Codigo;

else

SELECT tcelular from bitcelular where tcelular = telefono;
END if;
end$$





DELIMITER $$
DROP PROCEDURE IF EXISTS stpInsertarRelusUarioCelular;
CREATE PROCEDURE `stpInsertarRelusUarioCelular`(ecodCliente int(17),codigoCel INT(16))
BEGIN

declare existe int;
set existe = (select count(*) from relusuariocelular WHERE ecodUsuario = ecodCliente AND ecodCelular = codigoCel);

if existe = 0
then
select existe;

insert into relusuariocelular(`ecodUsuario`,`ecodCelular`)
values (ecodCliente,codigoCel);
SELECT  LAST_INSERT_ID() AS Codigo;

else

SELECT ecodUsuarioCelular  from relusuariocelular WHERE ecodUsuario = ecodCliente AND ecodCelular = codigoCel;
END if;

end$$