/* 0.1.0.10
 * 2018-10-15
 * Sergio Abraham Flores Gutiérrez
 */

DROP DATABASE IF EXISTS aalims;
CREATE DATABASE aalims CHARACTER SET latin1 COLLATE latin1_spanish_ci;
USE aalims;



CREATE TABLE IF NOT EXISTS cc_config_setting (
id_config_setting SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
setting_key CHAR(50) NOT NULL, 
setting_value VARCHAR(500) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
nk_company_branch SMALLINT UNSIGNED, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_cc_config_setting PRIMARY KEY (id_config_setting)) ENGINE=InnoDB;





INSERT INTO cc_config_setting VALUES (1, 'Version', '0.1.0.10', 0, 0, NULL, 1, 1, NOW(), NOW());

CREATE TABLE IF NOT EXISTS cc_company ( 
id_company SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(200) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_cc_company PRIMARY KEY (id_company)) ENGINE=InnoDB;



INSERT INTO cc_company VALUES (1, 'Empresa', 0, 0, 1, 1, NOW(), NOW());

CREATE TABLE IF NOT EXISTS cc_company_branch ( 
id_company_branch SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(100) NOT NULL, 
code CHAR(25) NOT NULL, 
is_main BOOLEAN NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_company SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_cc_company_branch PRIMARY KEY (id_company_branch)) ENGINE=InnoDB;




INSERT INTO cc_company_branch VALUES (1, 'Matriz', 'MAT', 1, 0, 0, 1, 1, 1, NOW(), NOW());

CREATE TABLE IF NOT EXISTS cc_user_type ( 
id_user_type SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_cc_user_type PRIMARY KEY (id_user_type)) ENGINE=InnoDB;



INSERT INTO cc_user_type VALUES (1, 'Usuario estándar', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_type VALUES (2, 'Administrador', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_type VALUES (3, 'Súpervisor', 1, 0, 1, 1, NOW(), NOW());

CREATE TABLE IF NOT EXISTS cc_user_role ( 
id_user_role SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_cc_user_role PRIMARY KEY (id_user_role)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS cc_user_attrib ( 
id_user_attrib SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_cc_user_attrib PRIMARY KEY (id_user_attrib)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS cc_user_job ( 
id_user_job SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_cc_user_job PRIMARY KEY (id_user_job)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS cc_user (
id_user SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
name CHAR(201) NOT NULL,
prefix CHAR(25) NOT NULL,
surname CHAR(100) NOT NULL,
forename CHAR(100) NOT NULL,
initials CHAR(10) NOT NULL,
mail CHAR(200) NOT NULL,
user_name CHAR(50) NOT NULL,
user_pswd CHAR(200) NOT NULL,
is_system BOOLEAN NOT NULL,
is_deleted BOOLEAN NOT NULL,
fk_user_type SMALLINT UNSIGNED NOT NULL,
nk_entity INTEGER UNSIGNED,
nk_user_job SMALLINT UNSIGNED,
fk_user_ins SMALLINT UNSIGNED NOT NULL,
fk_user_upd SMALLINT UNSIGNED NOT NULL,
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT pk_cc_user PRIMARY KEY (id_user)) ENGINE=InnoDB;








INSERT INTO cc_user VALUES (1, '-', '', '', '', '-', '', '', '', 1, 1, 1, NULL, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_user VALUES (2, 'Administrador', '', '', '', 'A', '', 'admin', 'admin', 1, 0, 2, NULL, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_user VALUES (3, 'Supervisor', '', '', '', 'S', '', 'super', 'super', 1, 0, 3, NULL, NULL, 1, 1, NOW(), NOW());

CREATE TABLE IF NOT EXISTS cc_user_user_role ( 
id_user SMALLINT UNSIGNED NOT NULL, 
id_user_role SMALLINT UNSIGNED NOT NULL,
CONSTRAINT pk_cc_user_user_role PRIMARY KEY (id_user, id_user_role)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS cc_user_user_attrib ( 
id_user SMALLINT UNSIGNED NOT NULL,
id_user_attrib SMALLINT UNSIGNED NOT NULL, 
CONSTRAINT pk_cc_user_user_attrib PRIMARY KEY (id_user, id_user_attrib)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS cc_user_process_area ( 
id_user SMALLINT UNSIGNED NOT NULL, 
id_process_area SMALLINT UNSIGNED NOT NULL, 
CONSTRAINT pk_cc_user_process_area PRIMARY KEY (id_user, id_process_area)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS cc_market_segment ( 
id_market_segment SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
sorting SMALLINT UNSIGNED NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_cc_market_segment PRIMARY KEY (id_market_segment)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS cc_entity_class ( 
id_entity_class SMALLINT UNSIGNED NOT NULL, 
name CHAR(50) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_cc_entity_class PRIMARY KEY (id_entity_class)) ENGINE=InnoDB;



INSERT INTO cc_entity_class VALUES (1, 'Empresa', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_class VALUES (2, 'Cliente/Deudor', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_class VALUES (3, 'Proveedor/Acreedor', 1, 0, 1, 1, NOW(), NOW());

CREATE TABLE IF NOT EXISTS cc_entity_type ( 
id_entity_type SMALLINT UNSIGNED NOT NULL, 
name CHAR(50) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_entity_class SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_cc_entity_type PRIMARY KEY (id_entity_type)) ENGINE=InnoDB;




INSERT INTO cc_entity_type VALUES (101, 'Empresa', 1, 0, 1, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (201, 'Público en general', 1, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (211, 'Asociación o Corporativo', 1, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (212, 'Institución o Empresa', 1, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (221, 'Clínica u Hospital', 1, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (226, 'Aseguradora', 1, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (229, 'Laboratorio', 1, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (231, 'Especialista', 1, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (251, 'Servicios financieros', 1, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (252, 'Servicios en general', 1, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (256, 'Insumos y activos', 1, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (261, 'Comisionista', 1, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (266, 'Agente comercial', 1, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (269, 'Colaborador', 1, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (301, 'Público en general', 1, 0, 3, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (311, 'Asociación o Corporativo', 1, 0, 3, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (312, 'Institución o Empresa', 1, 0, 3, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (321, 'Clínica u Hospital', 1, 0, 3, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (326, 'Aseguradora', 1, 0, 3, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (329, 'Laboratorio', 1, 0, 3, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (331, 'Especialista', 1, 0, 3, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (351, 'Servicios financieros', 1, 0, 3, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (352, 'Servicios en general', 1, 0, 3, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (356, 'Insumos y activos', 1, 0, 3, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (361, 'Comisionista', 1, 0, 3, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (366, 'Agente comercial', 1, 0, 3, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_type VALUES (369, 'Colaborador', 1, 0, 3, 1, 1, NOW(), NOW());

CREATE TABLE IF NOT EXISTS cc_entity ( 
id_entity INTEGER UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(201) NOT NULL, 
code CHAR(25) NOT NULL, 
alias CHAR(100) NOT NULL, 
prefix CHAR(25) NOT NULL, 
surname CHAR(100) NOT NULL, 
forename CHAR(100) NOT NULL, 
fiscal_id CHAR(25) NOT NULL, 
is_person BOOLEAN NOT NULL, 
is_credit BOOLEAN NOT NULL, 
credit_days SMALLINT UNSIGNED NOT NULL, 
billing_prefs CHAR(100) NOT NULL, 
web_page VARCHAR(100) NOT NULL, 
notes VARCHAR(500) NOT NULL, 
is_def_sampling_img BOOLEAN NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_entity_class SMALLINT UNSIGNED NOT NULL, 
nk_market_segment SMALLINT UNSIGNED, 
nk_entity_parent INTEGER UNSIGNED, 
nk_entity_billing INTEGER UNSIGNED, 
nk_entity_agent INTEGER UNSIGNED, 
nk_report_delivery_type SMALLINT UNSIGNED, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_cc_entity PRIMARY KEY (id_entity)) ENGINE=InnoDB;












INSERT INTO cc_entity VALUES (1, 'Empresa', 'EMP', '', '', '', '', 'XAXX010101000', 0, 0, 0, '', '', '', 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_entity VALUES (2, 'Público en general', 'PUB', 'Público', '', '', '', 'XAXX010101000', 0, 0, 0, '', '', '', 0, 0, 0, 2, NULL, NULL, NULL, NULL, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_entity VALUES (3, 'Público en general', 'PUB', 'Público', '', '', '', 'XAXX010101000', 0, 0, 0, '', '', '', 0, 0, 0, 3, NULL, NULL, NULL, NULL, NULL, 1, 1, NOW(), NOW());

CREATE TABLE IF NOT EXISTS cc_entity_entity_type ( 
id_entity INTEGER UNSIGNED NOT NULL, 
id_entity_type SMALLINT UNSIGNED NOT NULL, 
CONSTRAINT pk_cc_entity_entity_type PRIMARY KEY (id_entity, id_entity_type)) ENGINE=InnoDB;



INSERT INTO cc_entity_entity_type VALUES (1, 101);
INSERT INTO cc_entity_entity_type VALUES (2, 201);
INSERT INTO cc_entity_entity_type VALUES (3, 301);

CREATE TABLE IF NOT EXISTS cc_entity_sampling_img ( 
id_entity_sampling_img INTEGER UNSIGNED NOT NULL AUTO_INCREMENT, 
sampling_img VARCHAR(250) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_entity INTEGER UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_cc_entity_sampling_img PRIMARY KEY (id_entity_sampling_img)) ENGINE=InnoDB;




CREATE TABLE IF NOT EXISTS cc_entity_address ( 
id_entity_address INTEGER UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL,
street CHAR(200) NOT NULL, 
district CHAR(100) NOT NULL, 
postcode CHAR(15) NOT NULL, 
reference CHAR(100) NOT NULL, 
city CHAR(50) NOT NULL, 
county CHAR(50) NOT NULL, 
state_region CHAR(50) NOT NULL, 
country CHAR(3) NOT NULL, 
location CHAR(25) NOT NULL, 
business_hr VARCHAR(100) NOT NULL, 
notes VARCHAR(500) NOT NULL, 
is_main BOOLEAN NOT NULL, 
is_recept BOOLEAN NOT NULL, 
is_process BOOLEAN NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_entity INTEGER UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_cc_entity_address PRIMARY KEY (id_entity_address)) ENGINE=InnoDB;





INSERT INTO cc_entity_address VALUES (1, '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, 1, 0, 0, 1, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_address VALUES (2, '', '', '', '', '', '', '', '', '', '', '', '', 1, 0, 0, 0, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_entity_address VALUES (3, '', '', '', '', '', '', '', '', '', '', '', '', 1, 0, 0, 0, 0, 3, 1, 1, NOW(), NOW());

CREATE TABLE IF NOT EXISTS cc_contact_type ( 
id_contact_type SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL, 
ts_user_upd TIMESTAMP NOT NULL, 
CONSTRAINT pk_cc_contact_type PRIMARY KEY (id_contact_type)) ENGINE=InnoDB;



INSERT INTO cc_contact_type VALUES (1, 'Principal', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_contact_type VALUES (2, 'Técnico', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_contact_type VALUES (3, 'Recepción', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_contact_type VALUES (4, 'Muestreo', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_contact_type VALUES (5, 'Proceso', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_contact_type VALUES (6, 'Resultados', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_contact_type VALUES (7, 'Calidad', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_contact_type VALUES (8, 'Comercial', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_contact_type VALUES (9, 'Facturación', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_contact_type VALUES (10, 'Crédito y cobranza', 1, 0, 1, 1, NOW(), NOW());

CREATE TABLE IF NOT EXISTS cc_contact ( 
id_contact INTEGER UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(201) NOT NULL, 
prefix CHAR(25) NOT NULL, 
surname CHAR(100) NOT NULL, 
forename CHAR(100) NOT NULL, 
job CHAR(50) NOT NULL, 
mail CHAR(200) NOT NULL, 
phone CHAR(100) NOT NULL, 
mobile CHAR(100) NOT NULL, 
is_report BOOLEAN NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_entity INTEGER UNSIGNED NOT NULL, 
fk_entity_address INTEGER UNSIGNED NOT NULL, 
fk_contact_type SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_cc_contact PRIMARY KEY (id_contact)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_process_area (
id_process_area SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
code CHAR(5) NOT NULL, 
sorting SMALLINT UNSIGNED NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_process_area PRIMARY KEY (id_process_area)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_sample_class ( 
id_sample_class SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_sample_class PRIMARY KEY (id_sample_class)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_sample_type ( 
id_sample_type SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_sample_class SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_sample_type PRIMARY KEY (id_sample_type)) ENGINE=InnoDB;




CREATE TABLE IF NOT EXISTS oc_sample_status ( 
id_sample_status SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
code CHAR(5) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_sample_status PRIMARY KEY (id_sample_status)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_sampling_equipt_type ( 
id_sampling_equipt_type SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_sampling_equipt_type PRIMARY KEY (id_sampling_equipt_type)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_sampling_equipt ( 
id_sampling_equipt SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(101) NOT NULL, 
code CHAR(25) NOT NULL, 
brand CHAR(50) NOT NULL, 
model CHAR(50) NOT NULL, 
is_active BOOLEAN NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_sampling_equipt_type SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_sampling_equipt PRIMARY KEY (id_sampling_equipt)) ENGINE=InnoDB;




CREATE TABLE IF NOT EXISTS oc_sampling_method ( 
id_sampling_method SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(200) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_sampling_method PRIMARY KEY (id_sampling_method)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_sampling_note ( 
id_sampling_note SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(200) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_sampling_note PRIMARY KEY (id_sampling_note)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_container_type ( 
id_container_type SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_container_type PRIMARY KEY (id_container_type)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_container_unit ( 
id_container_unit SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
code CHAR(25) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_container_unit PRIMARY KEY (id_container_unit)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_test_acredit_attrib ( 
id_test_acredit_attrib SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(200) NOT NULL, 
code CHAR(10) NOT NULL, 
is_contract BOOLEAN NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_test_acredit_attrib PRIMARY KEY (id_test_acredit_attrib)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_test ( 
id_test SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(200) NOT NULL, 
code CHAR(25) NOT NULL, 
sample_quantity VARCHAR(100) NOT NULL, 
sample_directs VARCHAR(500) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_process_area SMALLINT UNSIGNED NOT NULL, 
fk_sample_class SMALLINT UNSIGNED NOT NULL, 
fk_testing_method SMALLINT UNSIGNED NOT NULL, 
fk_test_acredit_attrib SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_test PRIMARY KEY (id_test)) ENGINE=InnoDB;










CREATE TABLE IF NOT EXISTS oc_test_entity ( 
id_test_entity INTEGER UNSIGNED NOT NULL AUTO_INCREMENT, 
process_days_min SMALLINT UNSIGNED NOT NULL, 
process_days_max SMALLINT UNSIGNED NOT NULL, 
cost DECIMAL(17, 2) NOT NULL, 
is_default BOOLEAN NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_test SMALLINT UNSIGNED NOT NULL, 
fk_entity INTEGER UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_test_entity PRIMARY KEY (id_test_entity)) ENGINE=InnoDB;







CREATE TABLE IF NOT EXISTS oc_test_package ( 
id_test_package SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(200) NOT NULL, 
code CHAR(25) NOT NULL, 
sample_quantity VARCHAR(100) NOT NULL, 
sample_directs VARCHAR(500) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_sample_class SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_test_package PRIMARY KEY (id_test_package)) ENGINE=InnoDB;




CREATE TABLE IF NOT EXISTS oc_test_package_test ( 
id_test_package SMALLINT UNSIGNED NOT NULL, 
id_test SMALLINT UNSIGNED NOT NULL, 
CONSTRAINT pk_oc_test_package_test PRIMARY KEY (id_test_package, id_test)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_test_package_package ( 
id_test_package SMALLINT UNSIGNED NOT NULL, 
id_test_package_cont SMALLINT UNSIGNED NOT NULL, 
CONSTRAINT pk_oc_test_package_package PRIMARY KEY (id_test_package, id_test_package_cont)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_testing_method ( 
id_testing_method SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(200) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_testing_method PRIMARY KEY (id_testing_method)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_testing_note ( 
id_testing_note SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(200) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_testing_note PRIMARY KEY (id_testing_note)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_recept_status ( 
id_recept_status SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
code CHAR(5) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_recept_status PRIMARY KEY (id_recept_status)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_job_status ( 
id_job_status SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
code CHAR(5) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_job_status PRIMARY KEY (id_job_status)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_report_delivery_type ( 
id_report_delivery_type SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
code CHAR(5) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_report_delivery_type PRIMARY KEY (id_report_delivery_type)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_report_reissue_cause ( 
id_report_reissue_cause SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(200) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_report_reissue_cause PRIMARY KEY (id_report_reissue_cause)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_report_status ( 
id_report_status SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(50) NOT NULL, 
code CHAR(5) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_report_status PRIMARY KEY (id_report_status)) ENGINE=InnoDB;




CREATE TABLE IF NOT EXISTS oc_result_unit ( 
id_result_unit SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
code CHAR(25) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_result_unit PRIMARY KEY (id_result_unit)) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS oc_result_permiss_limit ( 
id_result_permiss_limit SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
name CHAR(200) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_oc_result_permiss_limit PRIMARY KEY (id_result_permiss_limit)) ENGINE=InnoDB;





CREATE TABLE IF NOT EXISTS o_sample ( 
id_sample BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
sample_num CHAR(25) NOT NULL, 
sample_name CHAR(100) NOT NULL, 
sample_lot VARCHAR(50) NOT NULL, 
sample_date_mfg_n DATE, 
sample_date_sell_by_n DATE, 
sample_quantity DECIMAL(23, 8) NOT NULL, 
sample_quantity_orig DECIMAL(23, 8) NOT NULL, 
sample_child SMALLINT UNSIGNED NOT NULL, 
sample_released CHAR(1) NOT NULL, 
is_sampling_company BOOLEAN NOT NULL, 
sampling_datetime_n DATETIME, 
sampling_temperat_n DECIMAL(19, 4), 
sampling_area VARCHAR(100) NOT NULL, 
sampling_guide SMALLINT UNSIGNED NOT NULL, 
sampling_conditions VARCHAR(100) NOT NULL, 
sampling_deviations VARCHAR(500) NOT NULL, 
sampling_notes VARCHAR(500) NOT NULL, 
sampling_imgs SMALLINT UNSIGNED NOT NULL, 
recept_sample SMALLINT UNSIGNED NOT NULL, 
recept_datetime_n DATETIME, 
recept_temperat_n DECIMAL(19, 4), 
recept_deviations VARCHAR(500) NOT NULL, 
recept_notes VARCHAR(500) NOT NULL, 
service_type CHAR(1) NOT NULL, 
process_days SMALLINT UNSIGNED NOT NULL, 
process_start_date DATE NOT NULL, 
process_deadline DATE NOT NULL, 
is_customer_custom BOOLEAN NOT NULL, 
customer_name VARCHAR(201) NOT NULL, 
customer_street VARCHAR(200) NOT NULL, 
customer_district VARCHAR(100) NOT NULL, 
customer_postcode VARCHAR(15) NOT NULL, 
customer_reference VARCHAR(100) NOT NULL, 
customer_city VARCHAR(50) NOT NULL, 
customer_county VARCHAR(50) NOT NULL, 
customer_state_region VARCHAR(50) NOT NULL, 
customer_country VARCHAR(3) NOT NULL, 
customer_contact VARCHAR(250) NOT NULL, 
is_def_sampling_img BOOLEAN NOT NULL, 
ref_chain_custody VARCHAR(25) NOT NULL, 
ref_request VARCHAR(25) NOT NULL, 
ref_agreet VARCHAR(25) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_company_branch SMALLINT UNSIGNED NOT NULL, 
fk_customer INTEGER UNSIGNED NOT NULL, 
nk_customer_sample INTEGER UNSIGNED, 
nk_customer_billing INTEGER UNSIGNED, 
fk_report_contact INTEGER UNSIGNED NOT NULL, 
fk_report_delivery_type SMALLINT UNSIGNED NOT NULL, 
fk_sample_class SMALLINT UNSIGNED NOT NULL, 
fk_sample_type SMALLINT UNSIGNED NOT NULL, 
fk_sample_status SMALLINT UNSIGNED NOT NULL, 
nk_sample_parent BIGINT UNSIGNED, 
fk_container_type SMALLINT UNSIGNED NOT NULL, 
fk_container_unit SMALLINT UNSIGNED NOT NULL, 
fk_sampling_method SMALLINT UNSIGNED NOT NULL, 
nk_sampling_equipt_1 SMALLINT UNSIGNED, 
nk_sampling_equipt_2 SMALLINT UNSIGNED, 
nk_sampling_equipt_3 SMALLINT UNSIGNED, 
nk_recept BIGINT UNSIGNED, 
fk_user_sampler SMALLINT UNSIGNED NOT NULL, 
fk_user_receiver SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_o_sample PRIMARY KEY (id_sample)) ENGINE=InnoDB;






















CREATE TABLE IF NOT EXISTS o_sample_test ( 
id_sample_test BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
sample_test SMALLINT UNSIGNED NOT NULL, 
process_days_min SMALLINT UNSIGNED NOT NULL, 
process_days_max SMALLINT UNSIGNED NOT NULL, 
process_days SMALLINT UNSIGNED NOT NULL, 
process_start_date DATE NOT NULL, 
process_deadline DATE NOT NULL, 
cost DECIMAL(17, 2) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_sample BIGINT UNSIGNED NOT NULL, 
fk_test SMALLINT UNSIGNED NOT NULL, 
fk_entity INTEGER UNSIGNED NOT NULL, 
fk_process_area SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_o_sample_test PRIMARY KEY (id_sample_test)) ENGINE=InnoDB;







CREATE TABLE IF NOT EXISTS o_sample_status_log ( 
id_sample_status_log BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
status_datetime DATETIME NOT NULL, 
status_temperat_n DECIMAL(19, 4), 
status_notes VARCHAR(500) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_sample BIGINT UNSIGNED NOT NULL, 
fk_sample_status SMALLINT UNSIGNED NOT NULL, 
fk_user_status SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_o_sample_status_log PRIMARY KEY (id_sample_status_log)) ENGINE=InnoDB;






CREATE TABLE IF NOT EXISTS o_sampling_img ( 
id_sampling_img BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
sampling_img VARCHAR(250) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_sample BIGINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_o_sampling_img PRIMARY KEY (id_sampling_img)) ENGINE=InnoDB;




CREATE TABLE IF NOT EXISTS o_recept ( 
id_recept BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
recept_num CHAR(25) NOT NULL, 
recept_datetime DATETIME NOT NULL, 
process_days SMALLINT UNSIGNED NOT NULL, 
process_start_date DATE NOT NULL, 
process_deadline DATE NOT NULL, 
recept_deadline DATE NOT NULL, 
recept_deviations VARCHAR(500) NOT NULL, 
recept_notes VARCHAR(500) NOT NULL, 
service_type CHAR(1) NOT NULL, 
ref_chain_custody VARCHAR(25) NOT NULL, 
ref_request VARCHAR(25) NOT NULL, 
ref_agreet VARCHAR(25) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_company_branch SMALLINT UNSIGNED NOT NULL, 
fk_customer INTEGER UNSIGNED NOT NULL, 
fk_recept_status SMALLINT UNSIGNED NOT NULL, 
fk_user_receiver SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_o_recept PRIMARY KEY (id_recept)) ENGINE=InnoDB;







CREATE TABLE IF NOT EXISTS o_job ( 
id_job BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
job_num CHAR(25) NOT NULL, 
job_date DATE NOT NULL, 
process_days SMALLINT UNSIGNED NOT NULL, 
process_start_date DATE NOT NULL, 
process_deadline DATE NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_company_branch SMALLINT UNSIGNED NOT NULL, 
fk_process_area SMALLINT UNSIGNED NOT NULL, 
fk_sample BIGINT UNSIGNED NOT NULL, 
fk_recept BIGINT UNSIGNED NOT NULL, 
fk_job_status SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_o_job PRIMARY KEY (id_job)) ENGINE=InnoDB;








CREATE TABLE IF NOT EXISTS o_job_test ( 
id_job_test BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
job_test SMALLINT UNSIGNED NOT NULL, 
process_days SMALLINT UNSIGNED NOT NULL, 
process_start_date DATE NOT NULL, 
process_deadline DATE NOT NULL, 
ext_job_num VARCHAR(25) NOT NULL, 
ext_tracking_num VARCHAR(25) NOT NULL, 
ext_result_deadline_n DATE, 
ext_result_released_n DATE, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_job BIGINT UNSIGNED NOT NULL, 
fk_test SMALLINT UNSIGNED NOT NULL, 
fk_entity INTEGER UNSIGNED NOT NULL, 
fk_sample_test BIGINT UNSIGNED NOT NULL, 
fk_job_test_status SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_o_job_test PRIMARY KEY (id_job_test)) ENGINE=InnoDB;








CREATE TABLE IF NOT EXISTS o_job_status_log ( 
id_job_status_log BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
status_datetime DATETIME NOT NULL, 
status_notes VARCHAR(500) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_job BIGINT UNSIGNED NOT NULL, 
fk_job_status SMALLINT UNSIGNED NOT NULL, 
fk_user_status SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_o_job_status_log PRIMARY KEY (id_job_status_log)) ENGINE=InnoDB;






CREATE TABLE IF NOT EXISTS o_report ( 
id_report BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
report_num CHAR(25) NOT NULL, 
report_date DATE NOT NULL, 
process_deviations VARCHAR(500) NOT NULL, 
process_notes VARCHAR(500) NOT NULL, 
reissue SMALLINT UNSIGNED NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_company_branch SMALLINT UNSIGNED NOT NULL, 
fk_customer INTEGER UNSIGNED NOT NULL, 
fk_sample BIGINT UNSIGNED NOT NULL, 
fk_recept BIGINT UNSIGNED NOT NULL, 
fk_report_delivery_type SMALLINT UNSIGNED NOT NULL, 
fk_result_permiss_limit SMALLINT UNSIGNED NOT NULL, 
nk_report_reissue_cause SMALLINT UNSIGNED, 
fk_report_status SMALLINT UNSIGNED NOT NULL, 
fk_user_finish SMALLINT UNSIGNED NOT NULL, 
fk_user_verif SMALLINT UNSIGNED NOT NULL, 
fk_user_valid SMALLINT UNSIGNED NOT NULL, 
fk_user_release SMALLINT UNSIGNED NOT NULL, 
fk_user_deliver SMALLINT UNSIGNED NOT NULL, 
fk_user_cancel SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_finish TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_verif TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_valid TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_release TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_deliver TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_cancel TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_o_report PRIMARY KEY (id_report)) ENGINE=InnoDB;

















CREATE TABLE IF NOT EXISTS o_report_test ( 
id_report_test BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
report_test SMALLINT UNSIGNED NOT NULL, 
result VARCHAR(100) NOT NULL, 
uncertainty VARCHAR(10) NOT NULL, 
permiss_limit VARCHAR(100) NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_report BIGINT UNSIGNED NOT NULL, 
fk_test SMALLINT UNSIGNED NOT NULL, 
fk_job_test BIGINT UNSIGNED NOT NULL, 
fk_sample_test BIGINT UNSIGNED NOT NULL, 
nk_result_unit SMALLINT UNSIGNED, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_o_report_test PRIMARY KEY (id_report_test)) ENGINE=InnoDB;








CREATE TABLE IF NOT EXISTS o_report_status_log ( 
id_report_status_log BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
status_datetime DATETIME NOT NULL, 
status_notes VARCHAR(500) NOT NULL, 
reissue SMALLINT UNSIGNED NOT NULL, 
is_system BOOLEAN NOT NULL, 
is_deleted BOOLEAN NOT NULL, 
fk_report BIGINT UNSIGNED NOT NULL, 
fk_report_status SMALLINT UNSIGNED NOT NULL, 
nk_report_reissue_cause SMALLINT UNSIGNED, 
fk_user_status SMALLINT UNSIGNED NOT NULL, 
fk_user_ins SMALLINT UNSIGNED NOT NULL, 
fk_user_upd SMALLINT UNSIGNED NOT NULL, 
ts_user_ins TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', 
ts_user_upd TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
CONSTRAINT pk_o_report_status_log PRIMARY KEY (id_report_status_log)) ENGINE=InnoDB;



ALTER TABLE cc_config_setting ADD CONSTRAINT fk_cc_config_setting_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_config_setting ADD CONSTRAINT fk_cc_config_setting_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE cc_config_setting ADD UNIQUE INDEX idx_cc_config_setting_setting_key (setting_key);













ALTER TABLE cc_company ADD CONSTRAINT fk_cc_company_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_company ADD CONSTRAINT fk_cc_company_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
















ALTER TABLE cc_company_branch ADD CONSTRAINT fk_cc_company_branch_fk_company FOREIGN KEY fk_fk_company (fk_company) REFERENCES cc_company (id_company) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_company_branch ADD CONSTRAINT fk_cc_company_branch_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_company_branch ADD CONSTRAINT fk_cc_company_branch_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;













ALTER TABLE cc_user_type ADD CONSTRAINT fk_cc_user_type_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_user_type ADD CONSTRAINT fk_cc_user_type_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;















ALTER TABLE cc_user_role ADD CONSTRAINT fk_cc_user_role_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_user_role ADD CONSTRAINT fk_cc_user_role_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;











ALTER TABLE cc_user_attrib ADD CONSTRAINT fk_cc_user_attrib_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_user_attrib ADD CONSTRAINT fk_cc_user_attrib_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;











ALTER TABLE cc_user_job ADD CONSTRAINT fk_cc_user_job_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_user_job ADD CONSTRAINT fk_cc_user_job_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;





















ALTER TABLE cc_user ADD CONSTRAINT fk_cc_user_fk_user_type FOREIGN KEY fk_fk_user_type (fk_user_type) REFERENCES cc_user_type (id_user_type) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_user ADD CONSTRAINT fk_cc_user_nk_entity FOREIGN KEY fk_nk_entity (nk_entity) REFERENCES cc_entity (id_entity) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_user ADD CONSTRAINT fk_cc_user_nk_user_job FOREIGN KEY fk_nk_user_job (nk_user_job) REFERENCES cc_user_job (id_user_job) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_user ADD CONSTRAINT fk_cc_user_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_user ADD CONSTRAINT fk_cc_user_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE cc_user ADD INDEX idx_cc_user_user_name (user_name);









ALTER TABLE cc_user_user_role ADD CONSTRAINT fk_cc_user_user_role_id_user FOREIGN KEY fk_id_user (id_user) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_user_user_role ADD CONSTRAINT fk_cc_user_user_role_id_user_role FOREIGN KEY fk_id_user_role (id_user_role) REFERENCES cc_user_role (id_user_role) ON DELETE RESTRICT ON UPDATE RESTRICT;





ALTER TABLE cc_user_user_attrib ADD CONSTRAINT fk_cc_user_user_attrib_id_user FOREIGN KEY fk_id_user (id_user) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_user_user_attrib ADD CONSTRAINT fk_cc_user_user_attrib_id_user_attrib FOREIGN KEY fk_id_user_attrib (id_user_attrib) REFERENCES cc_user_attrib (id_user_attrib) ON DELETE RESTRICT ON UPDATE RESTRICT;





ALTER TABLE cc_user_process_area ADD CONSTRAINT fk_cc_user_process_area_id_user FOREIGN KEY fk_id_user (id_user) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_user_process_area ADD CONSTRAINT fk_cc_user_process_area_id_process_area FOREIGN KEY fk_id_process_area (id_process_area) REFERENCES oc_process_area (id_process_area) ON DELETE RESTRICT ON UPDATE RESTRICT;












ALTER TABLE cc_market_segment ADD CONSTRAINT fk_cc_market_segment_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_market_segment ADD CONSTRAINT fk_cc_market_segment_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;











ALTER TABLE cc_entity_class ADD CONSTRAINT fk_cc_entity_class_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity_class ADD CONSTRAINT fk_cc_entity_class_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
















ALTER TABLE cc_entity_type ADD CONSTRAINT fk_cc_entity_type_fk_entity_class FOREIGN KEY fk_fk_entity_class (fk_entity_class) REFERENCES cc_entity_class (id_entity_class) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity_type ADD CONSTRAINT fk_cc_entity_type_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity_type ADD CONSTRAINT fk_cc_entity_type_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;


























































ALTER TABLE cc_entity ADD CONSTRAINT fk_cc_entity_fk_entity_class FOREIGN KEY fk_fk_entity_class (fk_entity_class) REFERENCES cc_entity_class (id_entity_class) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity ADD CONSTRAINT fk_cc_entity_nk_market_segment FOREIGN KEY fk_nk_market_segment (nk_market_segment) REFERENCES cc_market_segment (id_market_segment) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity ADD CONSTRAINT fk_cc_entity_nk_entity_parent FOREIGN KEY fk_nk_entity_parent (nk_entity_parent) REFERENCES cc_entity (id_entity) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity ADD CONSTRAINT fk_cc_entity_nk_entity_billing FOREIGN KEY fk_nk_entity_billing (nk_entity_billing) REFERENCES cc_entity (id_entity) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity ADD CONSTRAINT fk_cc_entity_nk_entity_agent FOREIGN KEY fk_nk_entity_agent (nk_entity_agent) REFERENCES cc_entity (id_entity) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity ADD CONSTRAINT fk_cc_entity_nk_report_delivery_type FOREIGN KEY fk_nk_report_delivery_type (nk_report_delivery_type) REFERENCES oc_report_delivery_type (id_report_delivery_type) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity ADD CONSTRAINT fk_cc_entity_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity ADD CONSTRAINT fk_cc_entity_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE cc_entity ADD INDEX idx_cc_entity_name (name);
ALTER TABLE cc_entity ADD INDEX idx_cc_entity_alias (alias);









ALTER TABLE cc_entity_entity_type ADD CONSTRAINT fk_cc_entity_entity_type_id_entity FOREIGN KEY fk_id_entity (id_entity) REFERENCES cc_entity (id_entity) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity_entity_type ADD CONSTRAINT fk_cc_entity_entity_type_id_entity_type FOREIGN KEY fk_id_entity_type (id_entity_type) REFERENCES cc_entity_type (id_entity_type) ON DELETE RESTRICT ON UPDATE RESTRICT;
















ALTER TABLE cc_entity_sampling_img ADD CONSTRAINT fk_cc_entity_sampling_img_fk_entity FOREIGN KEY fk_fk_entity (fk_entity) REFERENCES cc_entity (id_entity) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity_sampling_img ADD CONSTRAINT fk_cc_entity_sampling_img_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity_sampling_img ADD CONSTRAINT fk_cc_entity_sampling_img_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;


























ALTER TABLE cc_entity_address ADD CONSTRAINT fk_cc_entity_address_fk_entity FOREIGN KEY fk_fk_entity (fk_entity) REFERENCES cc_entity (id_entity) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity_address ADD CONSTRAINT fk_cc_entity_address_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_entity_address ADD CONSTRAINT fk_cc_entity_address_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
















ALTER TABLE cc_contact_type ADD CONSTRAINT fk_cc_contact_type_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_contact_type ADD CONSTRAINT fk_cc_contact_type_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;

































ALTER TABLE cc_contact ADD CONSTRAINT fk_cc_contact_fk_entity FOREIGN KEY fk_fk_entity (fk_entity) REFERENCES cc_entity (id_entity) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_contact ADD CONSTRAINT fk_cc_contact_fk_entity_address FOREIGN KEY fk_fk_entity_address (fk_entity_address) REFERENCES cc_entity_address (id_entity_address) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_contact ADD CONSTRAINT fk_cc_contact_fk_contact_type FOREIGN KEY fk_fk_contact_type (fk_contact_type) REFERENCES cc_contact_type (id_contact_type) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_contact ADD CONSTRAINT fk_cc_contact_fk_user_ins FOREIGN KEY fk_fk_user_ins (fk_user_ins) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE cc_contact ADD CONSTRAINT fk_cc_contact_fk_user_upd FOREIGN KEY fk_fk_user_upd (fk_user_upd) REFERENCES cc_user (id_user) ON DELETE RESTRICT ON UPDATE RESTRICT;






INSERT INTO cc_config_setting VALUES (2, 'BusinessDaySun', '0', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (3, 'BusinessDayMon', '1', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (4, 'BusinessDayTue', '1', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (5, 'BusinessDayWed', '1', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (6, 'BusinessDayThu', '1', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (7, 'BusinessDayFri', '1', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (8, 'BusinessDaySat', '0.5', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (9, 'HalfDayHour', '14:00', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (10, 'ExtraProcessDaysForContract', '5', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (11, 'ExtraProcessDaysForReporting', '2', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (12, 'FilePathSampleImage', 'app/img/sample/', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (13, 'FilePathReportPDF', 'app/doc/sample/', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (14, 'SampleNumberFormat', 'yymmddnnnn-nnn', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (15, 'ReceptNumberFormat', 'yymmddnnnn', 0, 0, NULL, 1, 1, NOW(), NOW());



INSERT INTO cc_user_role VALUES (1, 'Administración', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_role VALUES (2, 'Muestreo', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_role VALUES (3, 'Recepción', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_role VALUES (4, 'Proceso', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_role VALUES (5, 'Resultados', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_role VALUES (6, 'Calidad', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_role VALUES (7, 'Comercial', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_role VALUES (8, 'Dirección', 1, 0, 1, 1, NOW(), NOW());



INSERT INTO cc_user_attrib VALUES (1, 'Signatario Muestreo', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_attrib VALUES (2, 'Signatario Proceso Analítico', 1, 0, 1, 1, NOW(), NOW());



INSERT INTO cc_user_job VALUES (1, 'Recepcionista', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_job VALUES (2, 'Químico de Muestreo', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_job VALUES (3, 'Químico Analista de Microbiología', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_job VALUES (4, 'Químico Analista de Fisicoquímicos', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_job VALUES (5, 'Químico Analista de Medios de Cultivo', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_job VALUES (6, 'Director Técnico de Alimentos y Agua', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_job VALUES (7, 'Agente Comercial', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_user_job VALUES (8, 'Director General', 0, 0, 1, 1, NOW(), NOW());



INSERT INTO cc_market_segment VALUES (1, 'Agroindustria', 1, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_market_segment VALUES (2, 'Pecuario/ Pesca', 2, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_market_segment VALUES (3, 'Servicio de alimentos', 3, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_market_segment VALUES (4, 'Lácteos', 4, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_market_segment VALUES (5, 'Cárnicos', 5, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_market_segment VALUES (6, 'Hospitales', 6, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_market_segment VALUES (7, 'Granos y cereales/ Panificación', 7, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_market_segment VALUES (8, 'Farmacéutica', 8, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_market_segment VALUES (9, 'Bebidas', 9, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_market_segment VALUES (10, 'Empaque y embalaje', 10, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_market_segment VALUES (11, 'Institucional', 11, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_market_segment VALUES (12, 'Confitería', 12, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_market_segment VALUES (13, 'Materias primas', 13, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_market_segment VALUES (14, 'Alimento animales', 14, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO cc_market_segment VALUES (15, 'Alimentos procesados', 15, 0, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_process_area VALUES (1, 'Microbiología', 'MB', 1, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_process_area VALUES (2, 'Fisicoquímicos', 'FQ', 2, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_process_area VALUES (3, 'Medios de Cultivo', 'MC', 3, 0, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_sample_class VALUES (1, 'Alimento/ Agua', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_sample_class VALUES (2, 'Otros', 0, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_sample_type VALUES (1, 'Alimento', 0, 0, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_sample_type VALUES (2, 'Agua', 0, 0, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_sample_type VALUES (3, 'Material empaque', 0, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_sample_type VALUES (4, 'Superficie', 0, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_sample_type VALUES (5, 'Ambiental', 0, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_sample_type VALUES (6, 'Espora', 0, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_sample_type VALUES (7, 'Otro', 0, 0, 2, 1, 1, NOW(), NOW());



INSERT INTO oc_sample_status VALUES (1, 'En muestreo', 'Mst', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_sample_status VALUES (2, 'En recepción', 'Rcp', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_sample_status VALUES (3, 'En área proceso', 'AP', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_sample_status VALUES (4, 'En proceso', 'Prc', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_sample_status VALUES (5, 'En resguardo', 'Rsg', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_sample_status VALUES (6, 'Desechado', 'Des', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_sample_status VALUES (9, 'Cancelado', 'Can', 0, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_sampling_method VALUES (1, 'Establecido por el cliente', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_sampling_method VALUES (2, 'Muestreo de probabilidad no selectivo', 0, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_sampling_note VALUES (1, 'Muestra preservada desde la toma con tiosulfato de sodio', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_sampling_note VALUES (2, 'Exposición ambiental durante 15 minutos', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_sampling_note VALUES (3, 'Lectura de pH tomada en sitio', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_sampling_note VALUES (4, 'Lectura de cloro tomada en sitio', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_sampling_note VALUES (5, 'Muestra recolectada no muestreada', 0, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_test_acredit_attrib VALUES (1, 'Acreditado por EMA. Acreditación No. A-0412-036/12', '1', 0, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_test_acredit_attrib VALUES (2, 'Acreditado por EMA. Acreditación No. A-0412-036/12 + Autorizado por COFEPRIS. Autorización No. TA-48-17', '1, 2', 0, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_test_acredit_attrib VALUES (3, 'Acreditado por EMA. Acreditación No. A-0412-036/12 + Reconocido por SENASICA', '1, 5', 0, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_test_acredit_attrib VALUES (4, 'Acreditado por EMA. Acreditación No. A-0412-036/12 + Autorizado por COFEPRIS. Autorización No. TA-48-17 + Reconocido por SENASICA', '1, 2, 5', 0, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_test_acredit_attrib VALUES (5, 'Autorizado por COFEPRIS. Autorización No. TA-48-17', '2', 0, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_test_acredit_attrib VALUES (6, 'Reconocido por SENASICA', '5', 0, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_test_acredit_attrib VALUES (7, 'Contratado/subcontratado a otros laboratorios', '3', 1, 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_test_acredit_attrib VALUES (8, 'Ausentes de acreditación/autorización, con métodos basados en normas oficiales', '4', 0, 0, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_testing_method VALUES (1, 'AOAC Official Method 2007.01pesticide Residues in Food by Acetonitrile Extraction and Partitioning with Magnesium Sulfate', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (2, 'AOAC- Método oficial 964.07', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (3, 'AOCS Ce 1f-96', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (4, 'AOCS Official Method 2007', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (5, 'AOCS-APHA', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (6, 'APHA-AWWA-WPCF,2150', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (7, 'Basado parcialmente en la NOM-092-SSA1-1994', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (8, 'Basado parcialmente en la NOM-210-SSA1-2014 Apéndice B', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (9, 'Basado parcialmente en la NOM-210-SSA1-2014 Apéndice C', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (10, 'Basado parcialmente en la NOM-210-SSA1-2014 Apéndice H', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (11, 'Brookfiel Engineering DVT3', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (12, 'CCAYAC-M-004/11', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (13, 'Compendium of Methods for the microbiological Examination of foods 3a. edition. 265-274', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (14, 'Compendium of Methods for the microbiological Examination of foods 3a. edition. 309-316', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (15, 'E-ENSAA015', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (16, 'Elisa', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (17, 'EPA 3015-1996', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (18, 'EPA 3510C-1996', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (19, 'FDA-EAM-4.4-2010', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (20, 'HACH', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (21, 'Método interno', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (22, 'Microbiología Sanitaria Agua y Alimentos / Eduardo Fernández. Escartín', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (23, 'NMX-AA-026-SCFI-2010', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (24, 'NMX-AA-038-SCFI-2001', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (25, 'NMX-AA-039-SCFI-2015', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (26, 'NMX-AA-045-SCFI-2001', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (27, 'NMX-AA-050-SCFI-2001', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (28, 'NMX-AA-072-SCFI-2001', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (29, 'NMX-AA-073-SCFI-2001', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (30, 'NMX-AA-099-SCFI-2006', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (31, 'NMX-F-317-NORMEX-2013', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (32, 'NMX-F-608-NORMEX-2011', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (33, 'NOM 147-SSA1-1996', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (34, 'NOM-031-SSA1-1993', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (35, 'NOM-040-SCFI-999', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (36, 'NOM-040-SSA1-1993', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (37, 'NOM-086-SSA1-1994 AN C1.1.1', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (38, 'NOM-086-SSA1-1994 AN C2', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (39, 'NOM-086-SSA1-1994 AN.C7', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (40, 'NOM-092-SSA1-1994', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (41, 'NOM-111-SSA1-1994', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (42, 'NOM-113-SSA1-1994', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (43, 'NOM-114-SSA1-1994', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (44, 'NOM-115-SSA1-1994', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (45, 'NOM-116-SSA1-1994', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (46, 'NOM-201-SSA1-2015 A.N A3.1', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (47, 'NOM-201-SSA1-2015 A.N A3.1/ US EPA 335.3-1978', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (48, 'NOM-201-SSA1-2015 APENDICE A 3.10', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (49, 'NOM-201-SSA1-2015 APENDICE A 3.13', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (50, 'NOM-201-SSA1-2015 APENDICE A 3.5', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (51, 'NOM-210-SSA1-2014 Apéndice A', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (52, 'NOM-210-SSA1-2014 Apéndice B', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (53, 'NOM-210-SSA1-2014 Apéndice C', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (54, 'NOM-210-SSA1-2014 Apéndice H', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (55, 'NOM-244-SSA1-2008 Apéndice B', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (56, 'Petrifilm', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (57, 'RRQ-M-003', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (58, 'SISTEMA MALDI TOF MS', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (59, 'UNE-EN-12821-2009', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (60, 'US EPA 80818-2007', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (61, 'US EPA 8260C 1986', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (62, 'US EPA 8260C 2006', 0, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_testing_note VALUES (1, 'LD= Límite de detección', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_note VALUES (2, 'Bacterias mesófilas aerobias incubadas a 35 ± 2 °C durante 48 h en Agar cuenta estándar', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_note VALUES (3, 'Hongos y levaduras incubados a 25 ± 1°C durante 5 días en Agar papa dextrosa acidificado', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_note VALUES (4, 'Coliformes totales incubados a 35 ± 1°C durante 24 h en Agar bilis rojo violeta', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_note VALUES (5, '* Valor estimado', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_testing_note VALUES (6, 'Muestra ensayada en las condiciones originales proporcionadas por el cliente', 0, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_container_type VALUES (1, 'Ampolleta', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_type VALUES (2, 'Bolsa estéril', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_type VALUES (3, 'Bolsa estéril con tiosulfato', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_type VALUES (4, 'Bolsa no esteril', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_type VALUES (5, 'Botella', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_type VALUES (6, 'Envase original', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_type VALUES (7, 'Esponja hidratada en bolsa estéril', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_type VALUES (8, 'Frasco estéril', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_type VALUES (9, 'Frasco no estéril', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_type VALUES (10, 'Garrafón', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_type VALUES (11, 'Hisopo en 10 mL de solución Buffer', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_type VALUES (12, 'Hisopo en 4 mL de solución Buffer', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_type VALUES (13, 'Placa de Petri', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_type VALUES (14, 'Tubo estéril', 0, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_container_unit VALUES (1, 'g', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_unit VALUES (2, 'kg', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_unit VALUES (3, 'ml', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_unit VALUES (4, 'l', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_unit VALUES (5, 'cm²', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_container_unit VALUES (6, 'pza', 0, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_recept_status VALUES (1, 'Nuevo', 'Nvo', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_recept_status VALUES (2, 'En proceso', 'Prc', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_recept_status VALUES (9, 'Cancelado', 'Can', 1, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_job_status VALUES (1, 'Pendiente', 'Pnd', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_job_status VALUES (2, 'En proceso', 'Prc', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_job_status VALUES (3, 'Terminado', 'Ter', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_job_status VALUES (9, 'Cancelado', 'Can', 1, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_report_delivery_type VALUES (1, 'Electrónico', 'E', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_report_delivery_type VALUES (2, 'Electrónico y físico en recepción', 'ER', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_report_delivery_type VALUES (3, 'Electrónico y físico en sitio', 'ES', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_report_delivery_type VALUES (4, 'Físico en recepción', 'R', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_report_delivery_type VALUES (5, 'Físico en sitio', 'S', 1, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_report_reissue_cause VALUES (1, 'Por solicitud del cliente (reimpresión sin modificación)', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_report_reissue_cause VALUES (2, 'Por modificaciones solicitadas por el cliente', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_report_reissue_cause VALUES (3, 'Por modificaciones realizadas por el laboratorio', 0, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_report_status VALUES (1, 'Pendiente', 'Pnd', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_report_status VALUES (2, 'En proceso', 'Prc', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_report_status VALUES (3, 'Terminado', 'Ter', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_report_status VALUES (4, 'Verificado', 'Ver', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_report_status VALUES (5, 'Validado', 'Val', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_report_status VALUES (6, 'Liberado', 'Lib', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_report_status VALUES (7, 'Entregado', 'Ent', 1, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_report_status VALUES (9, 'Cancelado', 'Can', 1, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_result_unit VALUES (1, 'UFC/mL', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (2, 'UFC/g', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (3, 'NMP/g', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (4, 'NMP/mL', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (5, 'NMP/100mL', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (6, 'U de pH', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (7, 'mg/L', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (8, 'µg/L', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (9, 'Bq/L', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (10, 'mg/Kg', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (11, 'U Pt/Co', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (12, 'UTN', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (13, 'PPM', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (14, 'en 25 g', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (15, 'No detectable', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (16, 'Pt-Co', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (17, 'N U', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (18, 'UFC/15 min', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (19, 'UFC/10 s', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_unit VALUES (20, 'ms/cm', 0, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_result_permiss_limit VALUES (1, '------', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_permiss_limit VALUES (2, 'Establecidos por el cliente', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_permiss_limit VALUES (3, 'NOM-127-SSA1-1994', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_permiss_limit VALUES (4, 'NOM-201-SSA1-2015', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_permiss_limit VALUES (5, 'NOM-187-SSA1/SCFI-2002', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_permiss_limit VALUES (6, 'NMX-F-108-SCFI-2016', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_result_permiss_limit VALUES (7, 'NOM-245-SSA1-2010', 0, 0, 1, 1, NOW(), NOW());

#eof