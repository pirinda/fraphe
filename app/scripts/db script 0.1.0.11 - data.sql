/* 0.1.0.11 - data
 * 2018-10-22
 * Sergio Abraham Flores Gutiérrez
 */

USE aalims;

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
INSERT INTO cc_config_setting VALUES (16, 'DBversion', '0.1.0.11', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (17, 'DBversionUpdateStart', '', 0, 0, NULL, 1, 1, NOW(), NOW());
INSERT INTO cc_config_setting VALUES (18, 'DBversionUpdateEnd', '', 0, 0, NULL, 1, 1, NOW(), NOW());



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



INSERT INTO cc_user VALUES (4, 'Cortés Torres Adriana', '', 'Cortés Torres', 'Adriana', 'CTA', 'recepcion@cedimi.com', 'adriana.cortes', 'ac', 0, 0, 1, NULL, 1, 1, 1, NOW(), NOW());
INSERT INTO cc_user VALUES (5, 'Leiva Gutiérrez Erika Atenea', '', 'Leiva Gutiérrez', 'Erika Atenea', 'LGEA', 'recepcion@cedimi.com', 'erika.leiva', 'el', 0, 0, 1, NULL, 1, 1, 1, NOW(), NOW());
INSERT INTO cc_user VALUES (6, 'Pérez Alejo Luz Elena', '', 'Pérez Alejo', 'Luz Elena', 'PALE', 'proceso@cedimi.com', 'luz.perez', 'lp', 0, 0, 1, NULL, 3, 1, 1, NOW(), NOW());
INSERT INTO cc_user VALUES (7, 'García Ortiz Vanessa', '', 'García Ortiz', 'Vanessa', 'GOV', 'proceso@cedimi.com', 'vanessa.garcia', 'vg', 0, 0, 1, NULL, 3, 1, 1, NOW(), NOW());
INSERT INTO cc_user VALUES (8, 'Esquivel Ruiz Bryan Alexis', '', 'Esquivel Ruiz', 'Bryan Alexis', 'ERBA', 'proceso@cedimi.com', 'bryan.esquivel', 'be', 0, 0, 1, NULL, 4, 1, 1, NOW(), NOW());
INSERT INTO cc_user VALUES (9, 'Ibarra Pineda Bryan Michel', '', 'Ibarra Pineda', 'Bryan Michel', 'IPBM', 'muestreo@cedimi.com', 'bryan.ibarra', 'bi', 0, 0, 1, NULL, 2, 1, 1, NOW(), NOW());
INSERT INTO cc_user VALUES (10, 'Venegas López Teresita Zasil', '', 'Venegas López', 'Teresita Zasil', 'VLTZ', 'direccion@cedimi.com', 'zasil.venegas', 'zv', 0, 0, 1, NULL, 6, 1, 1, NOW(), NOW());




INSERT INTO cc_user_user_role VALUES (4, 3);
INSERT INTO cc_user_user_role VALUES (5, 3);
INSERT INTO cc_user_user_role VALUES (6, 4);
INSERT INTO cc_user_user_role VALUES (7, 4);
INSERT INTO cc_user_user_role VALUES (8, 4);
INSERT INTO cc_user_user_role VALUES (9, 2);
INSERT INTO cc_user_user_role VALUES (10, 8);



INSERT INTO cc_user_user_attrib VALUES (9, 1);
INSERT INTO cc_user_user_attrib VALUES (10, 2);



INSERT INTO cc_user_process_area VALUES (6, 1);
INSERT INTO cc_user_process_area VALUES (6, 2);
INSERT INTO cc_user_process_area VALUES (7, 1);
INSERT INTO cc_user_process_area VALUES (7, 2);
INSERT INTO cc_user_process_area VALUES (8, 1);
INSERT INTO cc_user_process_area VALUES (8, 2);



INSERT INTO oc_sampling_equipt_type VALUES (1, 'Muestreador', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_sampling_equipt_type VALUES (2, 'Termómetro', 0, 0, 1, 1, NOW(), NOW());
INSERT INTO oc_sampling_equipt_type VALUES (3, 'Comparador', 0, 0, 1, 1, NOW(), NOW());



INSERT INTO oc_sampling_equipt VALUES (1, 'Muestreador', 'AL-MU-NA-01', 'Nasco', '', 1, 0, 0, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_sampling_equipt VALUES (2, 'Termómetro infrarojo', 'AL-TE-ME-43', 'Metron', '', 1, 0, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_sampling_equipt VALUES (3, 'Termómetro bimetálico', 'AL-TE-EX-23', 'Extech', '', 1, 0, 0, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_sampling_equipt VALUES (4, 'Comparador visual', 'AL-KI-LA-01', 'LaMotte', '', 1, 0, 0, 3, 1, 1, NOW(), NOW());
INSERT INTO oc_sampling_equipt VALUES (5, 'Cucharones', 'NA', 'NE', '', 1, 0, 0, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_sampling_equipt VALUES (6, 'Cucharas', 'NA', 'NE', '', 1, 0, 0, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_sampling_equipt VALUES (7, 'Cuchillos', 'NA', 'NE', '', 1, 0, 0, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_sampling_equipt VALUES (8, 'Pinzas', 'NA', 'NE', '', 1, 0, 0, 1, 1, 1, NOW(), NOW());

UPDATE cc_entity SET name = 'CENTRO DE DIAGNOSTICO MICROBIOLOGICO SA DE CV', alias = 'CEDIMI' WHERE id_entity = 1;



DELETE FROM oc_testing_method;
INSERT INTO oc_testing_method VALUES (1, 'ND', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (2, '(Por Diferencia de Análisis Proximal)', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (3, 'AOAC', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (4, 'AOAC Official Method 2007.01pesticide Residues in Food by Acetonitrile Extraction and Partitioning with Magnesium Sulfate', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (5, 'AOAC- Método oficial 964.07', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (6, 'AOCS Ce-1f-96', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (7, 'AOCS Official Method 2007', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (8, 'AOCS-APHA', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (9, 'AOCS-Ce-1f-96', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (10, 'APHA-AWWA-WPCF,2150', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (11, 'APHA-AWWA-WPCF,2160', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (12, 'BAM 8a Edición. Rev.A 1998, Cap.14', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (13, 'Basado parcialmente en la NOM-092-SSA1-1994', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (14, 'Basado parcialmente en la NOM-111-SSA1-1994', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (15, 'Basado parcialmente en la NOM-113-SSA1-1994', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (16, 'Basado parcialmente en la NOM-210-SSA1-2014 Apéndice B', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (17, 'Basado parcialmente en la NOM-210-SSA1-2014 Apéndice C', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (18, 'Basado parcialmente en la NOM-210-SSA1-2014 Apéndice H', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (19, 'Brookfiel Engineering DVT3', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (20, 'Cálculo', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (21, 'Chemical Technicians', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (22, 'Compendium of Methods for the microbiological Examination of foods 3a. edition. 265-274', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (23, 'Compendium of Methods for the microbiological Examination of foods 3a. edition. 309-316', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (24, 'Conductimetro', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (25, 'E-ENSAA015', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (26, 'Elisa', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (27, 'EPA 3015-1996', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (28, 'EPA 3510C-1996', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (29, 'EPA 8151A-1996', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (30, 'EPA Method 8081A', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (31, 'Espectrofotometría UV visible', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (32, 'FDA-EAM-4.4-2010', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (33, 'HACH', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (34, 'HPLC', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (35, 'Método basado en:NMX-F-102-NORMEX-F-2010', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (36, 'Metodo con reactivo DPD', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (37, 'Método Interno', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (38, 'MÉTODO INTERNO E-ENSAA013', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (39, 'METODO INTERNO INS-SM-US-71', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (40, 'Método potenciométrico', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (41, 'Microbiología e Inocuidad de los Alimentos / Eduardo Fernández. Escartín, Pág. 213', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (42, 'Microbiología e Inocuidad de los Alimentos / Eduardo Fernández. Escartín, Pág. 320', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (43, 'Microbiología Sanitaria Agua y Alimentos / Eduardo Fernández. Escartín, Vol. 1', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (44, 'Microbiología Sanitaria Agua y Alimentos / Eduardo Fernández. Escartín, Vol. 1 Pág. 415', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (45, 'NMX-AA-026-SCFI-2010', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (46, 'NMX-AA-038-SCFI-2001', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (47, 'NMX-AA-039-SCFI-2015', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (48, 'NMX-AA-039SCFI-2001', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (49, 'NMX-AA-045-SCFI-2001', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (50, 'NMX-AA-050-SCFI-2001', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (51, 'NMX-AA-072-SCFI-2001', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (52, 'NMX-AA-073-SCFI-2001', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (53, 'NMX-AA-099-SCFI-2006', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (54, 'NMX-B-231-1190/NMX-K-369-1972', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (55, 'NMX-F-083-1986', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (56, 'NMX-F-103-NORMEX-2009', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (57, 'NMX-F-317-NORMEX-2013', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (58, 'NMX-F-490-NORMEX-1999', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (59, 'NMX-F-607-NORMEX-2013', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (60, 'NMX-F-608-NORMEX-2011', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (61, 'NMX-F-613-NORMEX-2003', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (62, 'NMX-V-013-NORMEX-2013', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (63, 'NOM 147-SSA1-1996', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (64, 'NOM-031-SSA1-1993', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (65, 'NOM-040-SCFI-999', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (66, 'NOM-040-SSA1-1993', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (67, 'NOM-086-SSA1-1994 AN C.2', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (68, 'NOM-086-SSA1-1994 AN C2', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (69, 'NOM-086-SSA1.1994', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (70, 'NOM-092-SSA1-1994', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (71, 'NOM-111-SSA1-1994', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (72, 'NOM-113-SSA1-1994', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (73, 'NOM-117-SSA1-1994', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (74, 'NOM-201-SSA1-2015 A.N A3.1', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (75, 'NOM-201-SSA1-2015 A.N A3.1/ US EPA 335.3-1978', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (76, 'NOM-201-SSA1-2015 AP.A.3.13', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (77, 'NOM-201-SSA1-2015 APENDICE A 3.10', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (78, 'NOM-201-SSA1-2015 APENDICE A 3.13', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (79, 'NOM-201-SSA1-2015 APENDICE A 3.5', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (80, 'NOM-210-SSA1-2014', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (81, 'NOM-210-SSA1-2014 Apéndice A', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (82, 'NOM-210-SSA1-2014 Apéndice B', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (83, 'NOM-210-SSA1-2014 Apéndice C', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (84, 'NOM-210-SSA1-2014 Apéndice H', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (85, 'NOM-210-SSA1-2015 A.N A3.9', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (86, 'NOM-243-SSA1-2010 APB15 4.1.4', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (87, 'NOM-244-SSA1-2008 Apéndice B', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (88, 'Petrifilm', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (89, 'Petrifilm Recuento Enterobacteriaceae', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (90, 'RRQ-M-003', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (91, 'SISTEMA MALDI TOF MS', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (92, 'Texturometro', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (93, 'UNE-EN-12821-2009', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (94, 'US EPA 80818-2007', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (95, 'US EPA 8260C 1986', '0', '0', 1, 1, NOW(), NOW());
INSERT INTO oc_testing_method VALUES (96, 'US EPA 8260C 2006', '0', '0', 1, 1, NOW(), NOW());



INSERT INTO oc_test VALUES (1, 'Bacterias mesófilas aerobias', 'BMA', '300', 'ESTERIL', 0, 0, 1, 1, 70, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (2, 'Bacterias termofílicos aerobios', 'BTA', '300', 'ESTERIL', 0, 0, 1, 1, 70, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (3, 'Bacterias psicrotróficas', 'BMP', '300', 'ESTERIL', 0, 0, 1, 1, 70, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (4, 'Bacterias psicrofílicas', 'BMF', '300', 'ESTERIL', 0, 0, 1, 1, 70, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (5, 'Hongos', 'H', '300', 'ESTERIL', 0, 0, 1, 1, 71, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (6, 'Levaduras', 'L', '300', 'ESTERIL', 0, 0, 1, 1, 71, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (7, 'Coliformes totales', 'CT NMP', '300', 'ESTERIL', 0, 0, 1, 1, 84, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (8, 'Coliformes totales', 'CT VP', '300', 'ESTERIL', 0, 0, 1, 1, 72, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (9, 'Coliformes totales.', 'CT FM', '300', 'ESTERIL', 0, 0, 1, 1, 87, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (10, 'Coliformes Fecales.', 'CF NMP', '300', 'ESTERIL', 0, 0, 1, 1, 84, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (11, 'E. coli.', 'EC', '300', 'ESTERIL', 0, 0, 1, 1, 84, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (12, 'E. coli O157:H7', 'EC O157', '300', 'ESTERIL', 0, 0, 1, 1, 3, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (13, 'Salmonella spp.', 'SAL 210', '300', 'ESTERIL', 0, 0, 1, 1, 81, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (14, 'Salmonella spp', 'SAL AMB', '300', 'ESTERIL', 0, 0, 1, 1, 38, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (15, 'S. aureus.', 'SAU 210', '300', 'ESTERIL', 0, 0, 1, 1, 82, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (16, 'Listeria monocytogenees.', 'LMON 210', '300', 'ESTERIL', 0, 0, 1, 1, 83, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (17, 'Bacterias mesófilas aerobias.', 'BMA SUP', '300', 'ESTERIL', 0, 0, 1, 1, 13, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (18, 'Coliformes totales', 'CT SUP', '300', 'ESTERIL', 0, 0, 1, 1, 15, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (19, 'Hongos', 'H SUP', '300', 'ESTERIL', 0, 0, 1, 1, 14, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (20, 'Levaduras', 'L SUP', '300', 'ESTERIL', 0, 0, 1, 1, 14, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (21, 'Coliformes Fecales.', 'CF SUP', '300', 'ESTERIL', 0, 0, 1, 1, 18, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (22, 'E. coli.', 'EC SUP', '300', 'ESTERIL', 0, 0, 1, 1, 18, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (23, 'Salmonella spp.', 'SAL SUP', '300', 'ESTERIL', 0, 0, 1, 1, 81, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (24, 'S. aureus.', 'SAU SUP', '300', 'ESTERIL', 0, 0, 1, 1, 16, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (25, 'Listeria monocytogenees.', 'LMON SUP', '300', 'ESTERIL', 0, 0, 1, 1, 17, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (26, 'Indicador Biologico de Esterilizador', 'INDICADOR', '300', 'NA', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (27, 'Bacterias lácticas', 'BAL', '300', 'ESTERIL', 0, 0, 1, 1, 44, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (28, 'Bacterias mesófilas esporuladas', 'BME', '300', 'ESTERIL', 0, 0, 1, 1, 22, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (29, 'Enterobacteriaspor petrifilm', 'ENT', '300', 'ESTERIL', 0, 0, 1, 1, 89, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (30, 'Actividad antimicrobiana', 'RETO', '300', 'NA', 0, 0, 1, 1, 65, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (31, 'Shigella', 'SHI', '300', 'ESTERIL', 0, 0, 1, 1, 42, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (32, 'Aflatoxinas', 'AFLAT', '300', 'NA', 0, 0, 1, 1, 26, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (33, 'Observación al microscopio', 'OBS', '300', 'NA', 0, 0, 1, 1, 5, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (34, 'Lactobacillus', 'LAC', '300', 'ESTERIL', 0, 0, 1, 1, 43, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (35, 'C.perfringens', 'CPER', '300', 'EMPAQUE FINAL', 0, 0, 1, 1, 41, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (36, 'Bacterias mesofílas anaerobias', 'BMAN', '300', 'ESTERIL', 0, 0, 1, 1, 23, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (37, 'Bacterias Termofilas anaerobias', 'BTAN', '300', 'ESTERIL', 0, 0, 1, 1, 23, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (38, 'Vibrio cholerae', 'VIB', '300', 'ESTERIL', 0, 0, 1, 1, 64, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (39, 'Validación', 'VALI', '300', 'NA', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (40, 'pH', 'PH', '300', 'NA', 0, 0, 2, 1, 40, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (41, 'Cloro residual', 'CLORO', '300', 'NA', 0, 0, 2, 1, 36, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (42, 'Dureza total', 'DUREZA', '300', 'NA', 0, 0, 2, 1, 51, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (43, 'Color', 'color', '300', '', 0, 0, 2, 1, 49, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (44, 'Olor', 'Olor', '300', '', 0, 0, 2, 1, 10, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (45, 'Sabor', 'Sabor', '300', '', 0, 0, 2, 1, 11, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (46, 'Turbidez', 'Turbidez', '300', '', 0, 0, 2, 1, 46, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (47, 'Conductividad', 'Conductividad', '300', 'NA', 0, 0, 2, 1, 24, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (48, 'Identificación microbiana', 'Identificación', '300', 'NA', 0, 0, 1, 1, 91, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (49, 'Superficie Inerte Hospitalaria', 'SUP INERTE H', '300', 'ESTERIL', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (50, 'Superficie Viva Hospitalaria', 'SUP VIVA H', '300', 'ESTERIL', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (51, 'Materia extraña', 'MATERIA E', '300', 'NA', 0, 0, 2, 1, 63, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (52, 'Yodo Residual', 'YODO', '300', '', 0, 0, 2, 1, 31, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (53, 'Sodio', 'Sodio', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (54, 'Cloro residual', 'CL', '300', '', 0, 0, 2, 1, 77, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (55, 'Fluoruros', 'FLUOR', '300', '', 0, 0, 2, 1, 78, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (56, 'Nitratos (Como N)', 'NITRATOS', '300', '', 0, 0, 2, 1, 79, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (57, 'Nitritos (Como N)', 'NITRITOS', '300', '', 0, 0, 2, 1, 53, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (58, 'Arsénico', 'Arse', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (59, 'Boro', 'boro', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (60, 'Cadmio', 'CADMIO', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (61, 'Niquel', 'niquel', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (62, 'Plata', 'plata', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (63, 'Plomo', 'plomo', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (64, 'Selenio', 'selenio', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (65, 'Control Bacteriologico de Agua de Hemodialisis', 'CBH', '300', 'ESTERIL', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (66, 'Control de Antisepticos y Desinfectantes', 'CAD', '300', 'NA', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (67, 'Multiresidual de Plaguicidas', 'PLAGUI', '300', '', 0, 0, 2, 1, 4, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (68, 'Grasa total', 'GRASA ALI', '300', '', 0, 0, 2, 1, 69, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (69, 'Húmedad', 'HUMEDAD', '300', '', 0, 0, 2, 1, 55, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (70, 'Grasa', 'GRASA', '300', '', 0, 0, 2, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (71, 'Trihalometanos', 'TRIHA', '300', '', 0, 0, 2, 1, 96, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (72, '1,2,4 Triclorobenceno', '124 T', '300', '', 0, 0, 2, 1, 8, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (73, 'Sulfatos', 'SULF', '300', '', 0, 0, 2, 1, 95, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (74, 'Metodo J&J', 'JYJ', '300', '', 0, 0, 1, 1, 25, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (75, 'pH', 'PH ALI', '300', '', 0, 0, 2, 1, 57, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (76, 'Porcentaje de Sal', 'SAL', '300', '', 0, 0, 2, 1, 66, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (77, 'Viscosidad', 'VISC', '300', '', 0, 0, 2, 1, 19, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (78, 'Bacterias Mesofilas Aerobias', 'BMA AMB', '300', 'ESTERIL', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (79, 'Organismos Coliformes totales', 'CT AMB', '300', 'ESTERIL', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (80, 'Hongos', 'H AMB', '300', 'ESTERIL', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (81, 'Levaduras', 'L AMB', '300', 'ESTERIL', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (82, 'Listeria', 'LMON AMB', '300', 'ESTERIL', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (83, 'E. coli', 'EC PETRIF', '300', 'ESTERIL', 0, 0, 1, 1, 88, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (84, 'Listeria spp', 'LMON SPP', '300', 'ESTERIL', 0, 0, 1, 1, 80, 2, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (85, 'Coliformes totales', 'CT PETRIF', '300', 'ESTERIL', 0, 0, 1, 1, 88, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (86, 'Cenizas', 'CENI', '300', '', 0, 0, 2, 1, 59, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (87, 'Proteinas', 'PROTE', '300', '', 0, 0, 2, 1, 60, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (88, 'Fibra Cruda', 'FIB', '300', '', 0, 0, 2, 1, 61, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (89, 'Carbohidrátos', 'CARBO', '300', '', 0, 0, 2, 1, 20, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (90, 'Contenido energético KJ', 'CONT E', '300', '', 0, 0, 2, 1, 20, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (91, 'Contenido energético Kcal', 'CONTE E', '300', '', 0, 0, 2, 1, 20, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (92, 'Azúcares reductores totales', 'AZC RE', '300', '', 0, 0, 2, 1, 68, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (93, 'Ácidos grasos cis y trans', 'ACYG', '300', '', 0, 0, 2, 1, 6, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (94, 'Grasas monosaturada', 'GRASMON', '300', '', 0, 0, 2, 1, 9, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (95, 'Grasas polisaturada', 'GRASPOLI', '300', '', 0, 0, 2, 1, 9, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (96, 'Grasas insaturadas', 'GRASIN', '300', '', 0, 0, 2, 1, 9, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (97, 'Colesterol', 'COLE', '300', '', 0, 0, 2, 1, 37, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (98, 'Calcio', 'CALCIO', '300', '', 0, 0, 2, 1, 32, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (99, 'Potasio', 'POTASIO', '300', '', 0, 0, 2, 1, 32, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (100, 'Fierro', 'FIERRO', '300', '', 0, 0, 2, 1, 32, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (101, 'Vitamina D3', 'VITD3', '300', '', 0, 0, 2, 1, 93, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (102, 'Control Bacteriologico de Agua Esteril', 'CBAE', '300', '', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (103, 'OPP(2-Phenylphenol)', 'OPP', '300', '', 0, 0, 1, 1, 7, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (104, 'Clorpropham', 'CLORP', '300', '', 0, 0, 1, 1, 7, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (105, 'Cyhalothrin-Lambda', 'CYHA', '300', '', 0, 0, 1, 1, 7, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (106, 'Bacterias Mesofilas Aerobias J&J', 'BMA JJ', '300', 'ESTERIL', 0, 0, 1, 1, 25, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (107, 'Hongos J&J', 'HONGOS JJ', '300', 'ESTERIL', 0, 0, 1, 1, 25, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (108, 'Levaduras J&J', 'LEVADURAS JJ', '300', 'ESTERIL', 0, 0, 1, 1, 25, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (109, 'Fenoles', 'FENOLES', '300', '', 0, 0, 2, 1, 50, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (110, 'Solidos disueltos totales', 'SOLIDOS DIS', '300', '', 0, 0, 2, 1, 47, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (111, 'Sustancias activas al azul de metileno', 'SUST', '300', '', 0, 0, 2, 1, 48, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (112, 'Cianuros', 'CIANUROS', '300', '', 0, 0, 2, 1, 33, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (113, 'Cloruros', 'CLORUROS', '300', '', 0, 0, 2, 1, 52, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (114, 'Nitrogeno amoniacal', 'AMONIACO', '300', '', 0, 0, 2, 1, 45, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (115, 'Aluminio', 'ALUMINIO', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (116, 'Bario', 'BARIO', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (117, 'Cobre', 'COBRE', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (118, 'Cromo', 'CROMO', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (119, 'Manganeso', 'MANGA', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (120, 'Mercurio', 'MERCURIO', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (121, 'Zinc', 'ZINC', '300', '', 0, 0, 2, 1, 39, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (122, 'Radioactividad alfa global', 'RAD ALFA', '300', '', 0, 0, 2, 1, 90, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (123, 'Radioactividad beta global', 'RAD BETA', '300', '', 0, 0, 2, 1, 90, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (124, 'Color verdarero', 'COLOR V', '300', '', 0, 0, 2, 1, 74, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (125, 'Fenoles', 'FENOLES', '300', '', 0, 0, 2, 1, 75, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (126, 'Digestión ácida con horno de microondas', 'DIGESTION', '300', '', 0, 0, 2, 1, 27, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (127, 'SAAM (calculado como L.A.S 340 UMAs)', 'SAAM', '300', '', 0, 0, 2, 1, 85, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (128, 'Extracción de plaguicidas clorados', 'EXTRACCION', '300', '', 0, 0, 2, 1, 28, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (129, 'Aldrin', 'ALDRIN', '300', '', 0, 0, 2, 1, 94, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (130, 'Clordano', 'CLORDANO', '300', '', 0, 0, 2, 1, 94, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (131, 'DDD(2.4 -DDD)', 'DDD', '300', '', 0, 0, 2, 1, 94, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (132, 'DDE(2.4-DDE)', 'DDE', '300', '', 0, 0, 2, 1, 94, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (133, 'DDT(2.4-DDT)', 'DDT', '300', '', 0, 0, 2, 1, 94, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (134, 'Dieldrin', 'DIELDRIN', '300', '', 0, 0, 2, 1, 94, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (135, 'Gama BCH (Lindano)', 'GAMA B', '300', '', 0, 0, 2, 1, 94, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (136, 'Heptacloro', 'HEPTA', '300', '', 0, 0, 2, 1, 94, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (137, 'Heptacloro Epoxido', 'HEPTA E', '300', '', 0, 0, 2, 1, 94, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (138, 'Hexaclorobenceno', 'HEXAC', '300', '', 0, 0, 2, 1, 94, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (139, 'Metoxicloro', 'METOX', '300', '', 0, 0, 2, 1, 94, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (140, 'Bromodirclometano', 'BROMOD', '300', '', 0, 0, 2, 1, 96, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (141, 'Bromoformo', 'BROMOF', '300', '', 0, 0, 2, 1, 96, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (142, 'Clorodibrometano', 'CLORODI', '300', '', 0, 0, 2, 1, 96, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (143, 'Cloroformo', 'CLOROFO', '300', '', 0, 0, 2, 1, 96, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (144, 'Hidrocarburos', 'HIDROC', '300', '', 0, 0, 2, 1, 1, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (145, 'BTEX', 'BTEX', '300', '', 0, 0, 2, 1, 1, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (146, 'Benceno', 'BENCENO', '300', '', 0, 0, 2, 1, 96, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (147, 'Etilbenceno', 'ETILB', '300', '', 0, 0, 2, 1, 96, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (148, 'm+p Xileno', 'MP', '300', '', 0, 0, 2, 1, 96, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (149, 'O-Xileno', 'OXI', '300', '', 0, 0, 2, 1, 96, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (150, 'Tolueno', 'TOLUENO', '300', '', 0, 0, 2, 1, 96, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (151, 'Herbicidas fenociclorados', 'HERBICIDAS', '300', '', 0, 0, 2, 1, 29, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (152, 'Enterotoxinas estafilococcica', 'ENTEROTOXINAS', '300', '', 0, 0, 2, 1, 86, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (153, 'Hierro', 'HIERRO', '300', '', 0, 0, 2, 1, 73, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (154, 'Calorías Kcal', 'CALORIAS', '300', '', 0, 0, 2, 1, 20, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (155, 'Azúcar', 'AZUCAR', '300', '', 0, 0, 2, 1, 67, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (156, 'Bacterias mesófilas aerobias', 'BMA PETRIF', '300', '', 0, 0, 1, 1, 88, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (157, 'Hongos', 'H PETRIF', '300', 'ESTERIL', 0, 0, 1, 1, 88, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (158, 'Levaduras', 'L PETRIF', '300', 'ESTERIL', 0, 0, 1, 1, 88, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (159, 'Coliformes totales', 'CT SUP2', '300', 'ESTERIL', 0, 0, 1, 1, 18, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (160, 'Fluoruros', 'FLUOR', '300', 'ESTERIL', 0, 0, 2, 1, 76, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (161, 'Plaguicidas organoclorados', 'PLAGUICIDAS O', '300', '', 0, 0, 2, 1, 30, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (162, 'Cultivo de Exudado Faringeo', 'CEF', '300', 'ESTERIL', 0, 0, 1, 1, 37, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (163, 'Coprocultivo', 'CPL', '300', 'ESTERIL', 0, 0, 1, 1, 37, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (164, 'Brucelosis', 'BRU', '300', 'ESTERIL', 0, 0, 1, 1, 37, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (165, 'Amebas de Vida Libre', 'AMEBA', '300', 'ESTERIL', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (166, 'Gravedad Específica', 'GRAVEDAD', '300', '', 0, 0, 2, 1, 21, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (167, 'Granulometría 7-35', 'GRANULOMETRIA', '300', '', 0, 0, 2, 1, 54, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (168, 'Reacciones Febriles', 'REACCIONES FEB', '300', 'ESTERIL', 0, 0, 1, 1, 37, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (169, 'Coproparasitoscópico', 'CPS', '300', 'ESTERIL', 0, 0, 1, 1, 37, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (170, 'Grasas Saturadas', 'GRASAS SAT', '300', '', 0, 0, 2, 1, 58, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (171, 'Carbohidrátos Totales', 'CARBOHIDRATOS TOTALES', '300', '', 0, 0, 2, 1, 2, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (172, 'Sal', 'SAL', '300', '', 0, 0, 2, 1, 66, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (173, 'Bacillus Cereus', 'BACILLUS CEREUS', '300', 'ESTERIL', 0, 0, 1, 1, 12, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (174, 'Bacterioscopico Directo', 'BACT', '300', 'ESTERIL', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (175, 'Bacterias Mesofilicas Anaerobias', 'BM ANAEROBIAS', '300', '', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (176, 'Acidez Titulable', 'ACIDEZ', '300', '', 0, 0, 2, 1, 35, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (177, 'Textura', 'TEXTURA', '300', '', 0, 0, 2, 1, 92, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (178, 'PCR TIEMPO REAL', 'LMON PCR', '300', 'ESTERIL', 0, 0, 2, 1, 1, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (179, 'PCR TIEMPO REAL', 'SAL PCR', '300', 'ESTERIL', 0, 0, 2, 1, 1, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (180, 'Recálculo', 'RECALCULO', '300', '', 0, 0, 2, 1, 37, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (181, 'Inulina', 'INULINA', '300', '', 0, 0, 2, 1, 34, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (182, '%Contenido Alcoholico a 20°', 'CONTENIDO ALCOHOLICO', '300', '', 0, 0, 2, 1, 62, 7, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (183, 'Klebsiella pneumoniae', 'KLEB', '300', 'ESTERIL', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (184, 'Pseudomona aeruginosa', 'PSEUDOMONAS', '300', 'ESTERIL', 0, 0, 1, 1, 37, 8, 1, 1, NOW(), NOW());
INSERT INTO oc_test VALUES (185, '°Brix', 'BRIX', '300', '', 0, 0, 2, 1, 56, 7, 1, 1, NOW(), NOW());



INSERT INTO oc_test_entity VALUES (1, 2, 2, 0, 1, 0, 0, 1, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (2, 2, 2, 0, 1, 0, 0, 2, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (3, 2, 2, 0, 1, 0, 0, 3, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (4, 2, 2, 0, 1, 0, 0, 4, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (5, 5, 5, 0, 1, 0, 0, 5, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (6, 5, 5, 0, 1, 0, 0, 6, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (7, 4, 4, 0, 1, 0, 0, 7, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (8, 1, 1, 0, 1, 0, 0, 8, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (9, 2, 2, 0, 1, 0, 0, 9, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (10, 3, 3, 0, 1, 0, 0, 10, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (11, 6, 6, 0, 1, 0, 0, 11, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (12, 5, 5, 0, 1, 0, 0, 12, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (13, 9, 9, 0, 1, 0, 0, 13, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (14, 9, 9, 0, 1, 0, 0, 14, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (15, 7, 7, 0, 1, 0, 0, 15, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (16, 10, 10, 0, 1, 0, 0, 16, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (17, 2, 2, 0, 1, 0, 0, 17, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (18, 1, 1, 0, 1, 0, 0, 18, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (19, 5, 5, 0, 1, 0, 0, 19, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (20, 5, 5, 0, 1, 0, 0, 20, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (21, 3, 3, 0, 1, 0, 0, 21, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (22, 9, 9, 0, 1, 0, 0, 22, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (23, 9, 9, 0, 1, 0, 0, 23, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (24, 7, 7, 0, 1, 0, 0, 24, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (25, 10, 10, 0, 1, 0, 0, 25, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (26, 1, 1, 0, 1, 0, 0, 26, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (27, 2, 2, 0, 1, 0, 0, 27, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (28, 4, 4, 0, 1, 0, 0, 28, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (29, 3, 3, 0, 1, 0, 0, 29, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (30, 7, 7, 0, 1, 0, 0, 30, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (31, 3, 3, 0, 1, 0, 0, 31, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (32, 0, 0, 0, 1, 0, 0, 32, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (33, 2, 2, 0, 1, 0, 0, 33, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (34, 3, 3, 0, 1, 0, 0, 34, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (35, 5, 5, 0, 1, 0, 0, 35, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (36, 3, 3, 0, 1, 0, 0, 36, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (37, 3, 3, 0, 1, 0, 0, 37, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (38, 4, 4, 0, 1, 0, 0, 38, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (39, 0, 0, 0, 1, 0, 0, 39, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (40, 1, 1, 0, 1, 0, 0, 40, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (41, 1, 1, 0, 1, 0, 0, 41, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (42, 1, 1, 0, 1, 0, 0, 42, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (43, 0, 0, 0, 1, 0, 0, 43, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (44, 0, 0, 0, 1, 0, 0, 44, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (45, 0, 0, 0, 1, 0, 0, 45, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (46, 0, 0, 0, 1, 0, 0, 46, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (47, 0, 0, 0, 1, 0, 0, 47, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (48, 5, 5, 0, 1, 0, 0, 48, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (49, 5, 5, 0, 1, 0, 0, 49, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (50, 5, 5, 0, 1, 0, 0, 50, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (51, 0, 0, 0, 1, 0, 0, 51, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (52, 0, 0, 0, 1, 0, 0, 52, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (53, 0, 0, 0, 1, 0, 0, 53, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (54, 0, 0, 0, 1, 0, 0, 54, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (55, 0, 0, 0, 1, 0, 0, 55, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (56, 0, 0, 0, 1, 0, 0, 56, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (57, 0, 0, 0, 1, 0, 0, 57, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (58, 0, 0, 0, 1, 0, 0, 58, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (59, 0, 0, 0, 1, 0, 0, 59, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (60, 0, 0, 0, 1, 0, 0, 60, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (61, 0, 0, 0, 1, 0, 0, 61, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (62, 0, 0, 0, 1, 0, 0, 62, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (63, 0, 0, 0, 1, 0, 0, 63, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (64, 0, 0, 0, 1, 0, 0, 64, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (65, 5, 5, 0, 1, 0, 0, 65, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (66, 5, 5, 0, 1, 0, 0, 66, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (67, 0, 0, 0, 1, 0, 0, 67, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (68, 0, 0, 0, 1, 0, 0, 68, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (69, 0, 0, 0, 1, 0, 0, 69, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (70, 0, 0, 0, 1, 0, 0, 70, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (71, 0, 0, 0, 1, 0, 0, 71, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (72, 0, 0, 0, 1, 0, 0, 72, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (73, 0, 0, 0, 1, 0, 0, 73, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (74, 5, 5, 0, 1, 0, 0, 74, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (75, 0, 0, 0, 1, 0, 0, 75, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (76, 0, 0, 0, 1, 0, 0, 76, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (77, 0, 0, 0, 1, 0, 0, 77, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (78, 2, 2, 0, 1, 0, 0, 78, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (79, 1, 1, 0, 1, 0, 0, 79, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (80, 5, 5, 0, 1, 0, 0, 80, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (81, 5, 5, 0, 1, 0, 0, 81, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (82, 10, 10, 0, 1, 0, 0, 82, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (83, 9, 9, 0, 1, 0, 0, 83, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (84, 10, 10, 0, 1, 0, 0, 84, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (85, 2, 2, 0, 1, 0, 0, 85, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (86, 0, 0, 0, 1, 0, 0, 86, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (87, 0, 0, 0, 1, 0, 0, 87, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (88, 0, 0, 0, 1, 0, 0, 88, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (89, 0, 0, 0, 1, 0, 0, 89, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (90, 0, 0, 0, 1, 0, 0, 90, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (91, 0, 0, 0, 1, 0, 0, 91, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (92, 0, 0, 0, 1, 0, 0, 92, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (93, 0, 0, 0, 1, 0, 0, 93, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (94, 0, 0, 0, 1, 0, 0, 94, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (95, 0, 0, 0, 1, 0, 0, 95, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (96, 0, 0, 0, 1, 0, 0, 96, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (97, 0, 0, 0, 1, 0, 0, 97, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (98, 0, 0, 0, 1, 0, 0, 98, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (99, 0, 0, 0, 1, 0, 0, 99, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (100, 0, 0, 0, 1, 0, 0, 100, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (101, 0, 0, 0, 1, 0, 0, 101, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (102, 0, 0, 0, 1, 0, 0, 102, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (103, 0, 0, 0, 1, 0, 0, 103, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (104, 0, 0, 0, 1, 0, 0, 104, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (105, 0, 0, 0, 1, 0, 0, 105, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (106, 2, 2, 0, 1, 0, 0, 106, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (107, 5, 5, 0, 1, 0, 0, 107, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (108, 5, 5, 0, 1, 0, 0, 108, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (109, 0, 0, 0, 1, 0, 0, 109, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (110, 0, 0, 0, 1, 0, 0, 110, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (111, 0, 0, 0, 1, 0, 0, 111, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (112, 0, 0, 0, 1, 0, 0, 112, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (113, 0, 0, 0, 1, 0, 0, 113, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (114, 0, 0, 0, 1, 0, 0, 114, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (115, 0, 0, 0, 1, 0, 0, 115, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (116, 0, 0, 0, 1, 0, 0, 116, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (117, 0, 0, 0, 1, 0, 0, 117, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (118, 0, 0, 0, 1, 0, 0, 118, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (119, 0, 0, 0, 1, 0, 0, 119, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (120, 0, 0, 0, 1, 0, 0, 120, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (121, 0, 0, 0, 1, 0, 0, 121, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (122, 0, 0, 0, 1, 0, 0, 122, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (123, 0, 0, 0, 1, 0, 0, 123, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (124, 0, 0, 0, 1, 0, 0, 124, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (125, 0, 0, 0, 1, 0, 0, 125, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (126, 0, 0, 0, 1, 0, 0, 126, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (127, 0, 0, 0, 1, 0, 0, 127, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (128, 0, 0, 0, 1, 0, 0, 128, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (129, 0, 0, 0, 1, 0, 0, 129, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (130, 0, 0, 0, 1, 0, 0, 130, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (131, 0, 0, 0, 1, 0, 0, 131, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (132, 0, 0, 0, 1, 0, 0, 132, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (133, 0, 0, 0, 1, 0, 0, 133, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (134, 0, 0, 0, 1, 0, 0, 134, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (135, 0, 0, 0, 1, 0, 0, 135, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (136, 0, 0, 0, 1, 0, 0, 136, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (137, 0, 0, 0, 1, 0, 0, 137, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (138, 0, 0, 0, 1, 0, 0, 138, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (139, 0, 0, 0, 1, 0, 0, 139, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (140, 0, 0, 0, 1, 0, 0, 140, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (141, 0, 0, 0, 1, 0, 0, 141, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (142, 0, 0, 0, 1, 0, 0, 142, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (143, 0, 0, 0, 1, 0, 0, 143, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (144, 0, 0, 0, 1, 0, 0, 144, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (145, 0, 0, 0, 1, 0, 0, 145, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (146, 0, 0, 0, 1, 0, 0, 146, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (147, 0, 0, 0, 1, 0, 0, 147, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (148, 0, 0, 0, 1, 0, 0, 148, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (149, 0, 0, 0, 1, 0, 0, 149, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (150, 0, 0, 0, 1, 0, 0, 150, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (151, 0, 0, 0, 1, 0, 0, 151, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (152, 6, 6, 0, 1, 0, 0, 152, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (153, 0, 0, 0, 1, 0, 0, 153, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (154, 0, 0, 0, 1, 0, 0, 154, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (155, 0, 0, 0, 1, 0, 0, 155, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (156, 2, 2, 0, 1, 0, 0, 156, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (157, 5, 5, 0, 1, 0, 0, 157, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (158, 5, 5, 0, 1, 0, 0, 158, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (159, 2, 2, 0, 1, 0, 0, 159, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (160, 0, 0, 0, 1, 0, 0, 160, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (161, 0, 0, 0, 1, 0, 0, 161, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (162, 0, 0, 0, 1, 0, 0, 162, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (163, 0, 0, 0, 1, 0, 0, 163, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (164, 0, 0, 0, 1, 0, 0, 164, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (165, 0, 0, 0, 1, 0, 0, 165, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (166, 0, 0, 0, 1, 0, 0, 166, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (167, 0, 0, 0, 1, 0, 0, 167, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (168, 0, 0, 0, 1, 0, 0, 168, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (169, 0, 0, 0, 1, 0, 0, 169, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (170, 0, 0, 0, 1, 0, 0, 170, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (171, 0, 0, 0, 1, 0, 0, 171, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (172, 0, 0, 0, 1, 0, 0, 172, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (173, 3, 3, 0, 1, 0, 0, 173, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (174, 2, 2, 0, 1, 0, 0, 174, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (175, 0, 0, 0, 1, 0, 0, 175, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (176, 0, 0, 0, 1, 0, 0, 176, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (177, 0, 0, 0, 1, 0, 0, 177, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (178, 2, 2, 0, 1, 0, 0, 178, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (179, 2, 2, 0, 1, 0, 0, 179, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (180, 0, 0, 0, 1, 0, 0, 180, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (181, 0, 0, 0, 1, 0, 0, 181, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (182, 0, 0, 0, 1, 0, 0, 182, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (183, 5, 5, 0, 1, 0, 0, 183, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (184, 5, 5, 0, 1, 0, 0, 184, 1, 1, 1, NOW(), NOW());
INSERT INTO oc_test_entity VALUES (185, 0, 0, 0, 1, 0, 0, 185, 1, 1, 1, NOW(), NOW());

#eof