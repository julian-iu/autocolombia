DROP DATABASE IF EXISTS autocolombia;

CREATE DATABASE autocolombia;

USE autocolombia;

CREATE TABLE usuario(
u_id BIGINT NOT NULL AUTO_INCREMENT,
u_nombre VARCHAR (500) NOT NULL,
u_celular VARCHAR (20) NOT NULL,
u_usuario VARCHAR (20) NOT NULL,
u_contrasena VARCHAR (100) NOT NULL,
u_tipo VARCHAR (50) NOT NULL,
PRIMARY KEY (u_id)
);

INSERT INTO usuario (u_id, u_nombre, u_celular, u_usuario, u_contrasena, u_tipo) VALUES ('1', 'AUTO COLOMMBIA', '321222222', 'admin', '12345','ADMINISTRADOR');


CREATE TABLE vehiculo(
v_id BIGINT NOT NULL AUTO_INCREMENT,
v_usuarioFK BIGINT NOT NULL,
v_placa VARCHAR (100) NOT NULL,
v_marca VARCHAR (255) NOT NULL,
v_modelo VARCHAR (255) NOT NULL,
v_color VARCHAR (255) NOT NULL,
FOREIGN KEY (v_usuarioFK) REFERENCES usuario (u_id),
PRIMARY KEY (v_id)
);

CREATE TABLE celda(
c_id INT NOT NULL AUTO_INCREMENT,
c_numero VARCHAR (100) NOT NULL, 
c_estado INT NOT NULL,
PRIMARY KEY (c_id)
);

INSERT INTO celda(c_id, c_numero, c_estado) VALUES ('1','CEL-1','0');
INSERT INTO celda(c_id, c_numero, c_estado) VALUES ('2','CEL-2','0');
INSERT INTO celda(c_id, c_numero, c_estado) VALUES ('3','CEL-3','0');
INSERT INTO celda(c_id, c_numero, c_estado) VALUES ('4','CEL-4','0');
INSERT INTO celda(c_id, c_numero, c_estado) VALUES ('5','CEL-5','0');
INSERT INTO celda(c_id, c_numero, c_estado) VALUES ('6','CEL-6','0');
INSERT INTO celda(c_id, c_numero, c_estado) VALUES ('7','CEL-7','0');
INSERT INTO celda(c_id, c_numero, c_estado) VALUES ('8','CEL-8','0');
INSERT INTO celda(c_id, c_numero, c_estado) VALUES ('9','CEL-9','0');
INSERT INTO celda(c_id, c_numero, c_estado) VALUES ('10','CEL-10','0');

CREATE TABLE parqueo(
p_id BIGINT NOT NULL AUTO_INCREMENT,
p_vehiculoFK BIGINT NOT NULL,
p_celdaFK INT NOT NULL,
p_fechaentrada DATE NOT NULL,
p_horaentrada TIME NOT NULL,
p_fechasalida DATE NOT NULL,
p_horasalida TIME NOT NULL,
p_estado INT NOT NULL, --  1 ADENTRO , -- 2 AFUERA
p_pago INT NOT NULL, --  0 PAGOP , -- 1 NO PAGO

FOREIGN KEY (p_vehiculoFK) REFERENCES vehiculo(v_id),
FOREIGN KEY (p_celdaFK) REFERENCES celda(c_id),
PRIMARY KEY (p_id)
);


CREATE TABLE pago(
pg_id BIGINT NOT NULL AUTO_INCREMENT,
pg_parqueoFK BIGINT NOT NULL,
pg_monto INT NOT NULL,
pg_fechapago DATE NOT NULL,
FOREIGN KEY (pg_parqueoFK) REFERENCES parqueo(p_id),
PRIMARY KEY (pg_id)
);


CREATE TABLE concepto(
cp_id INT NOT NULL,
cp_concepto VARCHAR (100) NOT NULL, 
cp_precio INT NOT NULL,
PRIMARY KEY (cp_id)
);

INSERT INTO concepto(cp_id, cp_concepto, cp_precio) VALUES ('1','HORA','1000');

