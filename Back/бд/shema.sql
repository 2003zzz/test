
-- DROP SCHEMA ptt05;

CREATE SCHEMA ptt05 AUTHORIZATION ptt05_1;

-- DROP SEQUENCE ptt05.id_v01_number_pk;

CREATE SEQUENCE ptt05.id_v01_number_pk INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE;

-- Permissions

ALTER SEQUENCE ptt05.id_v01_number_pk OWNER TO ptt05_1;

GRANT ALL ON SEQUENCE ptt05.id_v01_number_pk TO ptt05_1;

GRANT USAGE ON SEQUENCE ptt05.id_v01_number_pk TO ptt05a_1;

-- DROP SEQUENCE ptt05.pkt50_v_id;

CREATE SEQUENCE ptt05.pkt50_v_id INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1000000000000000 CACHE 1 NO CYCLE;

-- Permissions

ALTER SEQUENCE ptt05.pkt50_v_id OWNER TO ptt05_1;

GRANT ALL ON SEQUENCE ptt05.pkt50_v_id TO ptt05_1;

GRANT USAGE ON SEQUENCE ptt05.pkt50_v_id TO ptt05a_1;

-- DROP SEQUENCE ptt05.ptt05v01_id_seq;

CREATE SEQUENCE ptt05.ptt05v01_id_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE;

-- Permissions

ALTER SEQUENCE ptt05.ptt05v01_id_seq OWNER TO ptt05_1;

GRANT ALL ON SEQUENCE ptt05.ptt05v01_id_seq TO ptt05_1;

GRANT USAGE ON SEQUENCE ptt05.ptt05v01_id_seq TO ptt05a_1;

-- DROP SEQUENCE ptt05.ptt05v01_id_v01_seq;

CREATE SEQUENCE ptt05.ptt05v01_id_v01_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE;

-- Permissions

ALTER SEQUENCE ptt05.ptt05v01_id_v01_seq OWNER TO ptt05_1;

GRANT ALL ON SEQUENCE ptt05.ptt05v01_id_v01_seq TO ptt05_1;

GRANT USAGE ON SEQUENCE ptt05.ptt05v01_id_v01_seq TO ptt05a_1;

-- DROP SEQUENCE ptt05.ptt05v02_id_v02_seq;

CREATE SEQUENCE ptt05.ptt05v02_id_v02_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE;

-- Permissions

ALTER SEQUENCE ptt05.ptt05v02_id_v02_seq OWNER TO ptt05_1;

GRANT ALL ON SEQUENCE ptt05.ptt05v02_id_v02_seq TO ptt05_1;

GRANT USAGE ON SEQUENCE ptt05.ptt05v02_id_v02_seq TO ptt05a_1;

-- DROP SEQUENCE ptt05.ptt05v03_id_v01_seq;

CREATE SEQUENCE ptt05.ptt05v03_id_v01_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1 NO CYCLE;

-- Permissions

ALTER SEQUENCE ptt05.ptt05v03_id_v01_seq OWNER TO ptt05_1;

GRANT ALL ON SEQUENCE ptt05.ptt05v03_id_v01_seq TO ptt05_1;

GRANT USAGE ON SEQUENCE ptt05.ptt05v03_id_v01_seq TO ptt05a_1;

-- DROP SEQUENCE ptt05.ptt05v04_id_v02_seq;

CREATE SEQUENCE ptt05.ptt05v04_id_v02_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1 NO CYCLE;

-- Permissions

ALTER SEQUENCE ptt05.ptt05v04_id_v02_seq OWNER TO ptt05_1;

GRANT ALL ON SEQUENCE ptt05.ptt05v04_id_v02_seq TO ptt05_1;

GRANT USAGE ON SEQUENCE ptt05.ptt05v04_id_v02_seq TO ptt05a_1;

-- DROP SEQUENCE ptt05.ptt05v05_id_v01_seq;

CREATE SEQUENCE ptt05.ptt05v05_id_v01_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1 NO CYCLE;

-- Permissions

ALTER SEQUENCE ptt05.ptt05v05_id_v01_seq OWNER TO ptt05_1;

GRANT ALL ON SEQUENCE ptt05.ptt05v05_id_v01_seq TO ptt05_1;
-- ptt05.pkt50_v определение

-- Drop table

-- DROP TABLE ptt05.pkt50_v;

CREATE TABLE ptt05.pkt50_v (
	id numeric(16) NOT NULL DEFAULT nextval('ptt05.pkt50_v_id'::regclass),
	skey numeric(6) NULL,
	idlistokz numeric(16) NULL,
	idparent numeric(16) NULL,
	snm_as_scaption varchar NULL,
	kategoryprof int4 NULL,
	profcode70 int4 NULL,
	dperiodto_dend timestamp NULL,
	CONSTRAINT pkt50_v_pkey PRIMARY KEY (id)
)
TABLESPACE ptt05
;

-- Permissions

ALTER TABLE ptt05.pkt50_v OWNER TO ptt05_1;

GRANT ALL ON TABLE ptt05.pkt50_v TO ptt05_1;

GRANT SELECT ON TABLE ptt05.pkt50_v TO ptt05a_1;

GRANT SELECT ON TABLE ptt05.pkt50_v TO ptt05b_1;

-- ptt05.ptt05_test определение

-- Drop table

-- DROP TABLE ptt05.ptt05_test;

CREATE TABLE ptt05.ptt05_test (
    id int4 NULL,
    age numeric(16) NULL,
    "name" varchar NULL
) TABLESPACE ptt05;

-- Permissions

ALTER TABLE ptt05.ptt05_test OWNER TO ptt05_1;

GRANT ALL ON TABLE ptt05.ptt05_test TO ptt05_1;

GRANT
SELECT,
UPDATE,
INSERT
,
    DELETE ON
TABLE ptt05.ptt05_test TO ptt05a_1;

-- ptt05.ptt05e05 определение

-- Drop table

-- DROP TABLE ptt05.ptt05e05;

CREATE TABLE ptt05.ptt05e05 (
    id_e05 int4 NOT NULL DEFAULT 1, -- Идентификатор таблицы
    cipher_change int4 NULL, -- Шифр изменения
    "change" text NULL, -- Изменение
    id_v07 int4 NULL, -- Идентификатор таблицы PTT05V07
    CONSTRAINT ptt05e05_pk PRIMARY KEY (id_e05)
) TABLESPACE ptt05;

COMMENT ON
TABLE ptt05.ptt05e05 IS 'Шифр изменений карт норм времени';

-- Column comments

COMMENT ON COLUMN ptt05.ptt05e05.id_e05 IS 'Идентификатор таблицы';

COMMENT ON COLUMN ptt05.ptt05e05.cipher_change IS 'Шифр изменения';

COMMENT ON COLUMN ptt05.ptt05e05."change" IS 'Изменение';

COMMENT ON COLUMN ptt05.ptt05e05.id_v07 IS 'Идентификатор таблицы PTT05V07';

-- Permissions

ALTER TABLE ptt05.ptt05e05 OWNER TO ptt05_1;

GRANT ALL ON TABLE ptt05.ptt05e05 TO ptt05_1;

GRANT
SELECT,
UPDATE,
INSERT
,
    DELETE ON
TABLE ptt05.ptt05e05 TO ptt05a_1;

GRANT SELECT ON TABLE ptt05.ptt05e05 TO ptt05b_1;

-- ptt05.ptt05operation_exception определение

-- Drop table

-- DROP TABLE ptt05.ptt05operation_exception;

CREATE TABLE ptt05.ptt05operation_exception (
    cipher_of_the_operation varchar NULL, -- Шифр операции
    name_operation varchar NULL -- Наименование операции
);

COMMENT ON
TABLE ptt05.ptt05operation_exception IS 'Операции, исключенные из расчета трудоемкости';

-- Column comments

COMMENT ON COLUMN ptt05.ptt05operation_exception.cipher_of_the_operation IS 'Шифр операции';

COMMENT ON COLUMN ptt05.ptt05operation_exception.name_operation IS 'Наименование операции';

-- Permissions

ALTER TABLE ptt05.ptt05operation_exception OWNER TO ptt05_1;

GRANT ALL ON TABLE ptt05.ptt05operation_exception TO ptt05_1;

-- ptt05.ptt05status определение

-- Drop table

-- DROP TABLE ptt05.ptt05status;

CREATE TABLE ptt05.ptt05status (
    id_status int4 NOT NULL,
    value varchar NOT NULL,
    CONSTRAINT ptt05status_pkey PRIMARY KEY (id_status)
) TABLESPACE ptt05;

COMMENT ON TABLE ptt05.ptt05status IS 'Статус карт норм времени';

-- Permissions

ALTER TABLE ptt05.ptt05status OWNER TO ptt05_1;

GRANT ALL ON TABLE ptt05.ptt05status TO ptt05_1;

GRANT SELECT ON TABLE ptt05.ptt05status TO ptt05a_1;

GRANT SELECT ON TABLE ptt05.ptt05status TO ptt05b_1;

-- ptt05.ptt05v03 определение

-- Drop table

-- DROP TABLE ptt05.ptt05v03;

CREATE TABLE ptt05.ptt05v03 (
	id_v01 int4 NOT NULL, -- Идентификатор КНВ
	workshop int4 NOT NULL, -- Цех
	party int4 NULL, -- Партия
	service_number varchar NULL, -- Табельный номер
	number_technological_notification int4 NULL, -- Номер технологического извещения
	cipher_main_td varchar NULL, -- Шифр основного ТД
	type_technical_doc varchar NULL, -- ВТД
	note text NULL, -- Примечание
	designation varchar NULL, -- Обозначение изделия
	code_detail int8 NULL, -- Код детали
	id_status int4 NULL DEFAULT 1, -- Идентификатор состояния
	id_version int4 NOT NULL DEFAULT 1, -- id  версии КНВ
	date_of_create timestamp NULL, -- Дата заведения КНВ
	notification_number_ott int4 NULL, -- Номер извещения ОТТ
	create_service_number varchar NULL, -- Таб. инженера вводившего карточку
	number_of_parts_in_batch varchar NULL, -- Количество деталей в партии
	validity_period_norms int4 NULL, -- Срок действия норм
	minimum_number_blanks int4 NULL, -- Минимальное число заготовок для технологических потерь и отход при планировании малой партии
	id_e05 int4 NULL, -- Идентификатор таблицы ptt05e05
	service_number_editor varchar NULL, -- Таб. инженера изменившего КНВ
	updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP, -- Дата изменения КНВ
	id_v03 int4 NOT NULL DEFAULT nextval('ptt05.ptt05v03_id_v01_seq'::regclass), -- Идентификатор таблицы
	CONSTRAINT ptt05v03_pkey PRIMARY KEY (id_v03)
)
TABLESPACE ptt05
;

COMMENT ON TABLE ptt05.ptt05v03 IS 'Архив карты норм времени';

-- Column comments

COMMENT ON COLUMN ptt05.ptt05v03.id_v01 IS 'Идентификатор КНВ';

COMMENT ON COLUMN ptt05.ptt05v03.workshop IS 'Цех';

COMMENT ON COLUMN ptt05.ptt05v03.party IS 'Партия';

COMMENT ON COLUMN ptt05.ptt05v03.service_number IS 'Табельный номер';

COMMENT ON COLUMN ptt05.ptt05v03.number_technological_notification IS 'Номер технологического извещения';

COMMENT ON COLUMN ptt05.ptt05v03.cipher_main_td IS 'Шифр основного ТД';

COMMENT ON COLUMN ptt05.ptt05v03.type_technical_doc IS 'ВТД';

COMMENT ON COLUMN ptt05.ptt05v03.note IS 'Примечание';

COMMENT ON COLUMN ptt05.ptt05v03.designation IS 'Обозначение изделия';

COMMENT ON COLUMN ptt05.ptt05v03.code_detail IS 'Код детали';

COMMENT ON COLUMN ptt05.ptt05v03.id_status IS 'Идентификатор состояния';

COMMENT ON COLUMN ptt05.ptt05v03.id_version IS 'id версии КНВ';

COMMENT ON COLUMN ptt05.ptt05v03.date_of_create IS 'Дата заведения КНВ';

COMMENT ON COLUMN ptt05.ptt05v03.notification_number_ott IS 'Номер извещения ОТТ';

COMMENT ON COLUMN ptt05.ptt05v03.create_service_number IS 'Таб. инженера вводившего карточку';

COMMENT ON COLUMN ptt05.ptt05v03.number_of_parts_in_batch IS 'Количество деталей в партии';

COMMENT ON COLUMN ptt05.ptt05v03.validity_period_norms IS 'Срок действия норм';

COMMENT ON COLUMN ptt05.ptt05v03.minimum_number_blanks IS 'Минимальное число заготовок для технологических потерь и отход при планировании малой партии';

COMMENT ON COLUMN ptt05.ptt05v03.id_e05 IS 'Идентификатор таблицы ptt05e05';

COMMENT ON COLUMN ptt05.ptt05v03.service_number_editor IS 'Таб. инженера изменившего КНВ';

COMMENT ON COLUMN ptt05.ptt05v03.updated_at IS 'Дата изменения КНВ';

COMMENT ON COLUMN ptt05.ptt05v03.id_v03 IS 'Идентификатор таблицы';

-- Permissions

ALTER TABLE ptt05.ptt05v03 OWNER TO ptt05_1;

GRANT ALL ON TABLE ptt05.ptt05v03 TO ptt05_1;

GRANT
SELECT,
UPDATE,
INSERT
,
    DELETE ON
TABLE ptt05.ptt05v03 TO ptt05a_1;

GRANT SELECT ON TABLE ptt05.ptt05v03 TO ptt05b_1;

-- ptt05.ptt05v06 определение

-- Drop table

-- DROP TABLE ptt05.ptt05v06;

CREATE TABLE ptt05.ptt05v06 (
	laboriousness_on_dse_workshop varchar NULL, -- Трудоемкость на ДСЕ по цеху
	laboriousness_on_dse_workshop_kzo varchar NULL, -- Трудоемкость на ДСЕ по цеху с учетом Кзо
	laboriousness_controloperations_dse_workshop varchar NULL, -- Трудоемкость контрольных операций на ДСЕ по цеху
	laboriousness_controloperations_dse_workshop_kzo varchar NULL, -- Трудоемкость контрольных операций на ДСЕ по цеху с Кзо
	laboriousness_on_dse_controloper_ceh varchar NULL, -- Трудоемкость на ДСЕ с учетом контрольных операций по цеху
	laboriousness_on_dse_controloper_ceh_kzo varchar NULL, -- Трудоемкость на ДСЕ с учетом контрольных операций по цеху с Кзо
	id_v01 int4 NOT NULL DEFAULT nextval('ptt05.ptt05v05_id_v01_seq'::regclass), -- Идентификатор КНВ
	workshop int4 NOT NULL, -- Цех
	code_detail varchar NULL, -- Код детали
	id_v08 int4 NOT NULL, -- Идентификатор таблицы ptt05v08
	id_v06 int4 NOT NULL, -- Идентификатор таблицы
	service_number varchar NULL, -- Табельный номер инженера
	date_of_create timestamp NULL DEFAULT CURRENT_TIMESTAMP, -- Дата заведения расчетов
	izv_knv_notification_number varchar NULL
);

COMMENT ON
TABLE ptt05.ptt05v06 IS 'Расчетные данные карт норм времени по цеху';

-- Column comments

COMMENT ON COLUMN ptt05.ptt05v06.laboriousness_on_dse_workshop IS 'Трудоемкость на ДСЕ по цеху';

COMMENT ON COLUMN ptt05.ptt05v06.laboriousness_on_dse_workshop_kzo IS 'Трудоемкость на ДСЕ по цеху с учетом Кзо';

COMMENT ON COLUMN ptt05.ptt05v06.laboriousness_controloperations_dse_workshop IS 'Трудоемкость контрольных операций на ДСЕ по цеху';

COMMENT ON COLUMN ptt05.ptt05v06.laboriousness_controloperations_dse_workshop_kzo IS 'Трудоемкость контрольных операций на ДСЕ по цеху с Кзо';

COMMENT ON COLUMN ptt05.ptt05v06.laboriousness_on_dse_controloper_ceh IS 'Трудоемкость на ДСЕ с учетом контрольных операций по цеху';

COMMENT ON COLUMN ptt05.ptt05v06.laboriousness_on_dse_controloper_ceh_kzo IS 'Трудоемкость на ДСЕ с учетом контрольных операций по цеху с Кзо';

COMMENT ON COLUMN ptt05.ptt05v06.id_v01 IS 'Идентификатор КНВ';

COMMENT ON COLUMN ptt05.ptt05v06.workshop IS 'Цех';

COMMENT ON COLUMN ptt05.ptt05v06.code_detail IS 'Код детали';

COMMENT ON COLUMN ptt05.ptt05v06.id_v08 IS 'Идентификатор таблицы ptt05v08';

COMMENT ON COLUMN ptt05.ptt05v06.id_v06 IS 'Идентификатор таблицы';

COMMENT ON COLUMN ptt05.ptt05v06.service_number IS 'Табельный номер инженера';

COMMENT ON COLUMN ptt05.ptt05v06.date_of_create IS 'Дата заведения расчетов';

-- Permissions

ALTER TABLE ptt05.ptt05v06 OWNER TO ptt05_1;

GRANT ALL ON TABLE ptt05.ptt05v06 TO ptt05_1;

-- ptt05.ptt05v07 определение

-- Drop table

-- DROP TABLE ptt05.ptt05v07;

CREATE TABLE ptt05.ptt05v07 (
    id_v07 int4 NOT NULL, -- Идентификатор таблицы
    number_izv_izm varchar NULL, -- Номер извещения
    id_v01 int4 NOT NULL, -- Идентификатор КНВ
    date_of_create timestamp NULL DEFAULT CURRENT_TIMESTAMP, -- Дата создани ИИ
    create_service_number varchar NULL, -- Таб. инженера, создавшего ИИ
    id_e05 int4 NULL -- Идентификатор таблицы РТТ05Е05
);

COMMENT ON
TABLE ptt05.ptt05v07 IS 'Перечень извещений об изменении КНВ';

-- Column comments

COMMENT ON COLUMN ptt05.ptt05v07.id_v07 IS 'Идентификатор таблицы';

COMMENT ON COLUMN ptt05.ptt05v07.number_izv_izm IS 'Номер извещения';

COMMENT ON COLUMN ptt05.ptt05v07.id_v01 IS 'Идентификатор КНВ';

COMMENT ON COLUMN ptt05.ptt05v07.date_of_create IS 'Дата создани ИИ';

COMMENT ON COLUMN ptt05.ptt05v07.create_service_number IS 'Таб. инженера, создавшего ИИ';

COMMENT ON COLUMN ptt05.ptt05v07.id_e05 IS 'Идентификатор таблицы РТТ05Е05';

-- Permissions

ALTER TABLE ptt05.ptt05v07 OWNER TO ptt05_1;

GRANT ALL ON TABLE ptt05.ptt05v07 TO ptt05_1;

-- ptt05.ptt05v08 определение

-- Drop table

-- DROP TABLE ptt05.ptt05v08;

CREATE TABLE ptt05.ptt05v08 (
	laboriousness_on_dse_workshop varchar NULL, -- Трудоемкость на ДСЕ по цеху
	laboriousness_on_dse_workshop_kzo varchar NULL, -- Трудоемкость на ДСЕ по цеху с учетом Кзо
	laboriousness_controloperations_dse_workshop varchar NULL, -- Трудоемкость контрольных операций на ДСЕ по цеху
	laboriousness_controloperations_dse_workshop_kzo varchar NULL, -- Трудоемкость контрольных операций на ДСЕ по цеху с Кзо
	laboriousness_on_dse_controloper_ceh varchar NULL, -- Трудоемкость на ДСЕ с учетом контрольных операций по цеху
	laboriousness_on_dse_controloper_ceh_kzo varchar NULL, -- Трудоемкость на ДСЕ с учетом контрольных операций по цеху с Кзо
	id_v06 int4 NOT NULL DEFAULT nextval('ptt05.ptt05v05_id_v01_seq'::regclass), -- Идентификатор таблицы ptt05v06
	workshop int4 NOT NULL, -- Цех
	code_detail varchar NULL, -- Код детали
	id_v08 int4 NULL, -- Идентификатор таблицы
	service_number_editor varchar NULL, -- Таб. инженера, который внес изменения
	updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP -- Дата изменения расчетов
);

COMMENT ON
TABLE ptt05.ptt05v08 IS 'Архив расчетных данных КНВ по цеху';

-- Column comments

COMMENT ON COLUMN ptt05.ptt05v08.laboriousness_on_dse_workshop IS 'Трудоемкость на ДСЕ по цеху';

COMMENT ON COLUMN ptt05.ptt05v08.laboriousness_on_dse_workshop_kzo IS 'Трудоемкость на ДСЕ по цеху с учетом Кзо';

COMMENT ON COLUMN ptt05.ptt05v08.laboriousness_controloperations_dse_workshop IS 'Трудоемкость контрольных операций на ДСЕ по цеху';

COMMENT ON COLUMN ptt05.ptt05v08.laboriousness_controloperations_dse_workshop_kzo IS 'Трудоемкость контрольных операций на ДСЕ по цеху с Кзо';

COMMENT ON COLUMN ptt05.ptt05v08.laboriousness_on_dse_controloper_ceh IS 'Трудоемкость на ДСЕ с учетом контрольных операций по цеху';

COMMENT ON COLUMN ptt05.ptt05v08.laboriousness_on_dse_controloper_ceh_kzo IS 'Трудоемкость на ДСЕ с учетом контрольных операций по цеху с Кзо';

COMMENT ON COLUMN ptt05.ptt05v08.id_v06 IS 'Идентификатор таблицы ptt05v06';

COMMENT ON COLUMN ptt05.ptt05v08.workshop IS 'Цех';

COMMENT ON COLUMN ptt05.ptt05v08.code_detail IS 'Код детали';

COMMENT ON COLUMN ptt05.ptt05v08.id_v08 IS 'Идентификатор таблицы';

COMMENT ON COLUMN ptt05.ptt05v08.service_number_editor IS 'Таб. инженера, который внес изменения';

COMMENT ON COLUMN ptt05.ptt05v08.updated_at IS 'Дата изменения расчетов';

-- Permissions

ALTER TABLE ptt05.ptt05v08 OWNER TO ptt05_1;

GRANT ALL ON TABLE ptt05.ptt05v08 TO ptt05_1;

-- ptt05.ptt05v09 определение

-- Drop table

-- DROP TABLE ptt05.ptt05v09;

CREATE TABLE ptt05.ptt05v09 (
    total_laboriousness_electroperations varchar NULL, -- Суммарная трудоемкость по электромонтажным операциям
    total_laboriousness_workshop varchar NULL, -- Суммарная трудоемкость по цехам
    total_laboriousness_workshop_kzo varchar NULL, -- Суммарная трудоемкость по цехам с учетом Кзо
    laboriousness_controloper_dse_wshs varchar NULL, -- Трудоемкость контрольных операций на ДСЕ по всем цехам
    laboriousness_controloper_dse_wshs_kzo varchar NULL, -- Трудоемкость контрольных операций  на ДСЕ по всем цехам с Кзо
    laboriousness_on_dse_controloper_wshs varchar NULL, -- Трудоемкость на ДСЕ с учетом контрольных операций по всем цехам
    laboriousness_on_dse_controloper_wshs_kzo varchar NULL, -- Трудоемкость на ДСЕ с учетом контрольных операций по всем цехам с Кзо
    workshop int4 NOT NULL, -- Цех
    code_detail varchar NULL, -- Код детали
    id_v09 int4 NOT NULL, -- Идентификатор таблицы
    service_number varchar NULL, -- Табельный номер инженера
    date_of_create timestamp NULL DEFAULT CURRENT_TIMESTAMP, -- Дата заведения расчетов
    id_v01 int4 NOT NULL -- Идентификатор КНВ
);

COMMENT ON TABLE ptt05.ptt05v09 IS 'Расчетные данные КНВ по цехам';

-- Column comments

COMMENT ON COLUMN ptt05.ptt05v09.total_laboriousness_electroperations IS 'Суммарная трудоемкость по электромонтажным операциям';

COMMENT ON COLUMN ptt05.ptt05v09.total_laboriousness_workshop IS 'Суммарная трудоемкость по цехам';

COMMENT ON COLUMN ptt05.ptt05v09.total_laboriousness_workshop_kzo IS 'Суммарная трудоемкость по цехам с учетом Кзо';

COMMENT ON COLUMN ptt05.ptt05v09.laboriousness_controloper_dse_wshs IS 'Трудоемкость контрольных операций на ДСЕ по всем цехам';

COMMENT ON COLUMN ptt05.ptt05v09.laboriousness_controloper_dse_wshs_kzo IS 'Трудоемкость контрольных операций  на ДСЕ по всем цехам с Кзо';

COMMENT ON COLUMN ptt05.ptt05v09.laboriousness_on_dse_controloper_wshs IS 'Трудоемкость на ДСЕ с учетом контрольных операций по всем цехам';

COMMENT ON COLUMN ptt05.ptt05v09.laboriousness_on_dse_controloper_wshs_kzo IS 'Трудоемкость на ДСЕ с учетом контрольных операций по всем цехам с Кзо';

COMMENT ON COLUMN ptt05.ptt05v09.workshop IS 'Цех';

COMMENT ON COLUMN ptt05.ptt05v09.code_detail IS 'Код детали';

COMMENT ON COLUMN ptt05.ptt05v09.id_v09 IS 'Идентификатор таблицы';

COMMENT ON COLUMN ptt05.ptt05v09.service_number IS 'Табельный номер инженера';

COMMENT ON COLUMN ptt05.ptt05v09.date_of_create IS 'Дата заведения расчетов';

COMMENT ON COLUMN ptt05.ptt05v09.id_v01 IS 'Идентификатор КНВ';

-- Permissions

ALTER TABLE ptt05.ptt05v09 OWNER TO ptt05_1;

GRANT ALL ON TABLE ptt05.ptt05v09 TO ptt05_1;

-- ptt05.ptt05v10 определение

-- Drop table

-- DROP TABLE ptt05.ptt05v10;

CREATE TABLE ptt05.ptt05v10 (
    total_laboriousness_electroperations varchar NULL, -- Суммарная трудоемкость по электромонтажным операциям
    total_laboriousness_workshop varchar NULL, -- Суммарная трудоемкость по цехам
    total_laboriousness_workshop_kzo varchar NULL, -- Суммарная трудоемкость по цехам с учетом Кзо
    laboriousness_controloper_dse_wshs varchar NULL, -- Трудоемкость контрольных операций на ДСЕ по всем цехам
    laboriousness_controloper_dse_wshs_kzo varchar NULL, -- Трудоемкость контрольных операций на ДСЕ по всем цехам с Кзо
    laboriousness_on_dse_controloper_wshs varchar NULL, -- Трудоемкость на ДСЕ с учетом контрольных операций по всем цехам
    laboriousness_on_dse_controloper_wshs_kzo varchar NULL, -- Трудоемкость на ДСЕ с учетом контрольных операций по всем цехам с Кзо
    workshop int4 NOT NULL, -- Цех
    code_detail varchar NULL, -- Код детали
    id_v10 int4 NULL, -- Идентификатор таблицы
    service_number_editor varchar NULL, -- Таб. инженера, который внес изменения
    updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP -- Дата изменения расчетов
);

COMMENT ON
TABLE ptt05.ptt05v10 IS 'Архив расчетных данных КНВ по цехам';

-- Column comments

COMMENT ON COLUMN ptt05.ptt05v10.total_laboriousness_electroperations IS 'Суммарная трудоемкость по электромонтажным операциям';

COMMENT ON COLUMN ptt05.ptt05v10.total_laboriousness_workshop IS 'Суммарная трудоемкость по цехам';

COMMENT ON COLUMN ptt05.ptt05v10.total_laboriousness_workshop_kzo IS 'Суммарная трудоемкость по цехам с учетом Кзо';

COMMENT ON COLUMN ptt05.ptt05v10.laboriousness_controloper_dse_wshs IS 'Трудоемкость контрольных операций на ДСЕ по всем цехам';

COMMENT ON COLUMN ptt05.ptt05v10.laboriousness_controloper_dse_wshs_kzo IS 'Трудоемкость контрольных операций на ДСЕ по всем цехам с Кзо';

COMMENT ON COLUMN ptt05.ptt05v10.laboriousness_on_dse_controloper_wshs IS 'Трудоемкость на ДСЕ с учетом контрольных операций по всем цехам';

COMMENT ON COLUMN ptt05.ptt05v10.laboriousness_on_dse_controloper_wshs_kzo IS 'Трудоемкость на ДСЕ с учетом контрольных операций по всем цехам с Кзо';

COMMENT ON COLUMN ptt05.ptt05v10.workshop IS 'Цех';

COMMENT ON COLUMN ptt05.ptt05v10.code_detail IS 'Код детали';

COMMENT ON COLUMN ptt05.ptt05v10.id_v10 IS 'Идентификатор таблицы';

COMMENT ON COLUMN ptt05.ptt05v10.service_number_editor IS 'Таб. инженера, который внес изменения';

COMMENT ON COLUMN ptt05.ptt05v10.updated_at IS 'Дата изменения расчетов';

-- Permissions

ALTER TABLE ptt05.ptt05v10 OWNER TO ptt05_1;

GRANT ALL ON TABLE ptt05.ptt05v10 TO ptt05_1;

-- ptt05.ptt05v01 определение

-- Drop table

-- DROP TABLE ptt05.ptt05v01;

CREATE TABLE ptt05.ptt05v01 (
    id_v01 serial4 NOT NULL, -- Идентификатор КНВ
    workshop int4 NOT NULL, -- Цех
    party int4 NULL, -- Партия
    service_number varchar NULL, -- Табельный номер
    number_technological_notification int4 NULL, -- Номер технологического извещения
    cipher_main_td varchar NULL, -- Шифр основного ТД
    type_technical_doc varchar NULL, -- ВТД
    note text NULL, -- Примечание
    designation varchar NULL, -- Обозначение изделия
    code_detail varchar NULL, -- Код детали
    id_status int4 NULL DEFAULT 2, -- Идентификатор состояния
    id_version int4 NULL DEFAULT 1, -- id  версии КНВ
    date_of_create timestamp NULL DEFAULT CURRENT_TIMESTAMP, -- Дата заведения КНВ
    create_service_number varchar NULL, -- Таб. инженера вводившего карточку
    number_of_parts_in_batch varchar NULL, -- Количество деталей в партии
    validity_period_norms int4 NULL, -- Срок действия норм
    minimum_number_blanks int4 NULL, -- Минимальное число заготовок для технологических потерь и отход при планировании малой партии
    id_v06 int4 NULL, -- Идентификатор таблицы ptt05v06
    id_v02 int4 NULL, -- Идентификатор таблицы ptt05v02
    id_e05 int4 NULL,
    CONSTRAINT ptt05v01_pk PRIMARY KEY (id_v01),
    CONSTRAINT ptt05v01_fk FOREIGN KEY (id_status) REFERENCES ptt05.ptt05status (id_status) ON DELETE CASCADE ON UPDATE CASCADE
) TABLESPACE ptt05;

COMMENT ON TABLE ptt05.ptt05v01 IS 'Карты норм времени';

-- Column comments

COMMENT ON COLUMN ptt05.ptt05v01.id_v01 IS 'Идентификатор КНВ';

COMMENT ON COLUMN ptt05.ptt05v01.workshop IS 'Цех';

COMMENT ON COLUMN ptt05.ptt05v01.party IS 'Партия';

COMMENT ON COLUMN ptt05.ptt05v01.service_number IS 'Табельный номер';

COMMENT ON COLUMN ptt05.ptt05v01.number_technological_notification IS 'Номер технологического извещения';

COMMENT ON COLUMN ptt05.ptt05v01.cipher_main_td IS 'Шифр основного ТД';

COMMENT ON COLUMN ptt05.ptt05v01.type_technical_doc IS 'ВТД';

COMMENT ON COLUMN ptt05.ptt05v01.note IS 'Примечание';

COMMENT ON COLUMN ptt05.ptt05v01.designation IS 'Обозначение изделия';

COMMENT ON COLUMN ptt05.ptt05v01.code_detail IS 'Код детали';

COMMENT ON COLUMN ptt05.ptt05v01.id_status IS 'Идентификатор состояния';

COMMENT ON COLUMN ptt05.ptt05v01.id_version IS 'id версии КНВ';

COMMENT ON COLUMN ptt05.ptt05v01.date_of_create IS 'Дата заведения КНВ';

COMMENT ON COLUMN ptt05.ptt05v01.create_service_number IS 'Таб. инженера вводившего карточку';

COMMENT ON COLUMN ptt05.ptt05v01.number_of_parts_in_batch IS 'Количество деталей в партии';

COMMENT ON COLUMN ptt05.ptt05v01.validity_period_norms IS 'Срок действия норм';

COMMENT ON COLUMN ptt05.ptt05v01.minimum_number_blanks IS 'Минимальное число заготовок для технологических потерь и отход при планировании малой партии';

COMMENT ON COLUMN ptt05.ptt05v01.id_v06 IS 'Идентификатор таблицы ptt05v06';

COMMENT ON COLUMN ptt05.ptt05v01.id_v02 IS 'Идентификатор таблицы ptt05v02';

-- Permissions

ALTER TABLE ptt05.ptt05v01 OWNER TO ptt05_1;

GRANT ALL ON TABLE ptt05.ptt05v01 TO ptt05_1;

GRANT
SELECT,
UPDATE,
INSERT
,
    DELETE ON
TABLE ptt05.ptt05v01 TO ptt05a_1;

GRANT SELECT ON TABLE ptt05.ptt05v01 TO ptt05b_1;

-- ptt05.ptt05v02 определение

-- Drop table

-- DROP TABLE ptt05.ptt05v02;

CREATE TABLE ptt05.ptt05v02 (
    id_v02 serial4 NOT NULL, -- Идентификатор параметра КНВ
    id_v01 int4 NOT NULL, -- Идентификатор КНВ
    end_to_end_operation_number int4 NOT NULL, -- Сквозной номер операции
    cipher_of_the_operation varchar NULL, -- Шифр Операции
    cipher_of_the_profession int4 NOT NULL, -- Шифр профессии
    category_of_work int4 NOT NULL, -- Разряд работы
    hardware_cipher varchar NULL, -- Шифр оборудования
    type_of_norms int4 NULL, -- Вид норм
    code_of_the_tariff_grid varchar NOT NULL, -- код тарифной сетки
    unit_of_the_rationong int4 NOT NULL, -- единица нормирования
    time_rate_is_paid float4 NOT NULL, -- норма времени оплатная
    unit_of_measurement varchar NULL, -- еденица измерения
    launch_ratio varchar NULL, -- коэффициент запуска
    operation_number int4 NULL, -- номер операции
    cipher_of_the_reference_tp varchar NULL, -- Шифр ссылочного ТД
    norm_of_cycle_time varchar NULL, -- норма времени цикла
    operation_as_needed varchar NULL, -- Признак выполнения операции по мере необходимости
    number_of_worker varchar NULL, -- количество рабочих, на которых расчитанна норма времени
    operations_for_samples varchar NULL, -- операции для образцов
    note text NULL, -- примечание
    number_parts_of_detail int4 NULL, -- номер части детали, для которой выполняется опреация
    date_entry_operation timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, -- дата ввода операции
    number_notification_sgt int4 NULL, -- номер извещения СГТ
    aria int4 NULL, -- Участок
    id_version int4 NULL DEFAULT 2, -- идентификатор версии КНВ
    type_of_profession_reference_book int4 NULL, -- Справочник по типу профессий
    operation_with_technological_shutdowns varchar NULL, -- операция с технологическими остановами
    operation_for_execution varchar NULL, -- операция для исполнения
    pricing varchar NULL, -- Расценка (руб.)
    billing_time_kzo varchar NULL, -- Время оплатное с Кзо
    cycle_time_kzo varchar NULL, -- Время цикла с Кзо
    pricing_kzo varchar NULL, -- Расценка с Кзо
    pr_generating_operation int4 NULL, -- Признак генерации операции
    CONSTRAINT ptt05v02_pk PRIMARY KEY (id_v02),
    CONSTRAINT ptt05v02_fk FOREIGN KEY (id_v01) REFERENCES ptt05.ptt05v01 (id_v01) ON DELETE RESTRICT ON UPDATE RESTRICT
) TABLESPACE ptt05;

COMMENT ON TABLE ptt05.ptt05v02 IS 'Операции карт норм времени';

-- Column comments

COMMENT ON COLUMN ptt05.ptt05v02.id_v02 IS 'Идентификатор параметра КНВ';

COMMENT ON COLUMN ptt05.ptt05v02.id_v01 IS 'Идентификатор КНВ';

COMMENT ON COLUMN ptt05.ptt05v02.end_to_end_operation_number IS 'Сквозной номер операции';

COMMENT ON COLUMN ptt05.ptt05v02.cipher_of_the_operation IS 'Шифр Операции';

COMMENT ON COLUMN ptt05.ptt05v02.cipher_of_the_profession IS 'Шифр профессии';

COMMENT ON COLUMN ptt05.ptt05v02.category_of_work IS 'Разряд работы';

COMMENT ON COLUMN ptt05.ptt05v02.hardware_cipher IS 'Шифр оборудования';

COMMENT ON COLUMN ptt05.ptt05v02.type_of_norms IS 'Вид норм';

COMMENT ON COLUMN ptt05.ptt05v02.code_of_the_tariff_grid IS 'код тарифной сетки';

COMMENT ON COLUMN ptt05.ptt05v02.unit_of_the_rationong IS 'единица нормирования';

COMMENT ON COLUMN ptt05.ptt05v02.time_rate_is_paid IS 'норма времени оплатная';

COMMENT ON COLUMN ptt05.ptt05v02.unit_of_measurement IS 'еденица измерения';

COMMENT ON COLUMN ptt05.ptt05v02.launch_ratio IS 'коэффициент запуска';

COMMENT ON COLUMN ptt05.ptt05v02.operation_number IS 'номер операции';

COMMENT ON COLUMN ptt05.ptt05v02.cipher_of_the_reference_tp IS 'Шифр ссылочного ТД';

COMMENT ON COLUMN ptt05.ptt05v02.norm_of_cycle_time IS 'норма времени цикла';

COMMENT ON COLUMN ptt05.ptt05v02.operation_as_needed IS 'Признак выполнения операции по мере необходимости';

COMMENT ON COLUMN ptt05.ptt05v02.number_of_worker IS 'количество рабочих, на которых расчитанна норма времени';

COMMENT ON COLUMN ptt05.ptt05v02.operations_for_samples IS 'операции для образцов';

COMMENT ON COLUMN ptt05.ptt05v02.note IS 'примечание';

COMMENT ON COLUMN ptt05.ptt05v02.number_parts_of_detail IS 'номер части детали, для которой выполняется опреация';

COMMENT ON COLUMN ptt05.ptt05v02.date_entry_operation IS 'дата ввода операции';

COMMENT ON COLUMN ptt05.ptt05v02.number_notification_sgt IS 'номер извещения СГТ';

COMMENT ON COLUMN ptt05.ptt05v02.aria IS 'Участок';

COMMENT ON COLUMN ptt05.ptt05v02.id_version IS 'идентификатор версии КНВ';

COMMENT ON COLUMN ptt05.ptt05v02.type_of_profession_reference_book IS 'Справочник по типу профессий';

COMMENT ON COLUMN ptt05.ptt05v02.operation_with_technological_shutdowns IS 'операция с технологическими остановами';

COMMENT ON COLUMN ptt05.ptt05v02.operation_for_execution IS 'операция для исполнения';

COMMENT ON COLUMN ptt05.ptt05v02.pricing IS 'Расценка (руб.)';

COMMENT ON COLUMN ptt05.ptt05v02.billing_time_kzo IS 'Время оплатное с Кзо';

COMMENT ON COLUMN ptt05.ptt05v02.cycle_time_kzo IS 'Время цикла с Кзо';

COMMENT ON COLUMN ptt05.ptt05v02.pricing_kzo IS 'Расценка с Кзо';

COMMENT ON COLUMN ptt05.ptt05v02.pr_generating_operation IS 'Признак генерации операции';

-- Permissions

ALTER TABLE ptt05.ptt05v02 OWNER TO ptt05_1;

GRANT ALL ON TABLE ptt05.ptt05v02 TO ptt05_1;

GRANT
SELECT,
UPDATE,
INSERT
,
    DELETE ON
TABLE ptt05.ptt05v02 TO ptt05a_1;

GRANT SELECT ON TABLE ptt05.ptt05v02 TO ptt05b_1;

-- ptt05.ptt05v04 определение

-- Drop table

-- DROP TABLE ptt05.ptt05v04;

CREATE TABLE ptt05.ptt05v04 (
	id_v02 int4 NOT NULL,
	id_v01 int4 NOT NULL,
	end_to_end_operation_number int4 NOT NULL,
	cipher_of_the_operation varchar NULL,
	cipher_of_the_profession int4 NOT NULL,
	category_of_work int4 NOT NULL,
	hardware_cipher varchar NULL,
	type_of_norms int4 NULL,
	code_of_the_tariff_grid varchar NOT NULL,
	unit_of_the_rationong int4 NOT NULL,
	time_rate_is_paid float4 NOT NULL,
	unit_of_measurement varchar NULL,
	launch_ratio varchar NULL,
	operation_number int4 NULL,
	cipher_of_the_reference_tp varchar NULL,
	norm_of_cycle_time varchar NULL,
	operation_as_needed varchar NULL,
	number_of_worker varchar NULL,
	operations_for_samples varchar NULL,
	note text NULL,
	number_parts_of_detail int4 NULL,
	date_entry_operation timestamp NULL,
	number_notification_sgt int4 NULL,
	aria int4 NULL,
	id_version int4 NOT NULL DEFAULT 1,
	type_of_profession_reference_book int4 NULL,
	updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	service_number_editor varchar NULL,
	id_v03 int4 NULL,
	id_v04 int4 NOT NULL DEFAULT nextval('ptt05.ptt05v04_id_v02_seq'::regclass),
	operation_with_technological_shutdowns varchar NULL,
	operation_for_execution varchar NULL,
	pricing varchar NULL, -- Расценка (руб.)
	billing_time_kzo varchar NULL, -- Время оплатное с Кзо
	cycle_time_kzo varchar NULL, -- Время цикла с Кзо
	pricing_kzo varchar NULL, -- Расценка с Кзо
	pr_generating_operation int4 NULL, -- Признак генерации операции
	CONSTRAINT ptt05v04_pkey PRIMARY KEY (id_v04),
	CONSTRAINT ptt05v04_fk FOREIGN KEY (id_v03) REFERENCES ptt05.ptt05v03(id_v03) ON DELETE RESTRICT ON UPDATE RESTRICT
)
TABLESPACE ptt05
;

COMMENT ON
TABLE ptt05.ptt05v04 IS 'Архив операций карт норм времени';

-- Column comments

COMMENT ON COLUMN ptt05.ptt05v04.pricing IS 'Расценка (руб.)';

COMMENT ON COLUMN ptt05.ptt05v04.billing_time_kzo IS 'Время оплатное с Кзо';

COMMENT ON COLUMN ptt05.ptt05v04.cycle_time_kzo IS 'Время цикла с Кзо';

COMMENT ON COLUMN ptt05.ptt05v04.pricing_kzo IS 'Расценка с Кзо';

COMMENT ON COLUMN ptt05.ptt05v04.pr_generating_operation IS 'Признак генерации операции';

-- Permissions

ALTER TABLE ptt05.ptt05v04 OWNER TO ptt05_1;

GRANT ALL ON TABLE ptt05.ptt05v04 TO ptt05_1;

GRANT
SELECT,
UPDATE,
INSERT
,
    DELETE ON
TABLE ptt05.ptt05v04 TO ptt05a_1;

GRANT SELECT ON TABLE ptt05.ptt05v04 TO ptt05b_1;

-- Permissions

GRANT ALL ON SCHEMA ptt05 TO ptt05_1;

GRANT USAGE ON SCHEMA ptt05 TO ptt05a_1;

GRANT ALL ON SCHEMA ptt05 TO "02338430";