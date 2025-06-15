-- *********************************************
-- * SQL MySQL generation                      
-- *--------------------------------------------
-- * DB-MAIN version: 11.0.2              
-- * Generator date: Sep 14 2021              
-- * Generation date: Sun Jun 15 13:30:53 2025 
-- * LUN file: D:\utenti\michele\michele\unibo\db\Scuola_di_Ballo.lun 
-- * Schema: Scuola_di_Balllo/1 
-- ********************************************* 


-- Database Section
-- ________________ 

create database Scuola_di_Balllo;
use Scuola_di_Balllo;


-- Tables Section
-- _____________ 

create table ABBONAMENTO (
     cf varchar(50) not null,
     nome varchar(50) not null,
     tipo varchar(50) not null,
     numero_lezioni int not null,
     stato varchar(50) not null,
     prezzo_pagato int not null,
     lezioni_residue int not null,
     constraint ID_ABBONAMENTO_ID primary key (cf, nome, tipo, numero_lezioni));

create table AULA (
     ID int not null,
     constraint ID_AULA_ID primary key (ID));

create table CLASSE (
     nome varchar(50) not null,
     tipo varchar(50) not null,
     EVENTO varchar(50),
     CORSO varchar(50),
     constraint ID_CLASSE_ID primary key (nome, tipo));

create table CLIENTE (
     cf varchar(50) not null,
     cod_tessera varchar(50) not null,
     constraint FKPER_CLI_ID primary key (cf));

create table COMMENTO (
     ID int not null,
     descrizione varchar(500) not null,
     nome varchar(50) not null,
     tipo varchar(50) not null,
     anno int not null,
     livello varchar(50) not null,
     constraint ID_COMMENTO_ID primary key (ID));

create table CORSO (
     nome varchar(50) not null,
     tipo varchar(50) not null,
     descrizione varchar(500) not null,
     constraint FKCLA_COR_ID primary key (nome, tipo));

create table EDIZIONE_CORSO (
     nome varchar(50) not null,
     tipo varchar(50) not null,
     anno int not null,
     livello varchar(50) not null,
     ID int not null,
     data date not null,
     ora int not null,
     constraint ID_EDIZIONE_CORSO_ID primary key (nome, tipo, anno, livello),
     constraint FKappartiene_ID unique (ID, data, ora));

create table EVENTO (
     nome varchar(50) not null,
     tipo varchar(50) not null,
     ID int not null,
     data date not null,
     ora int not null,
     descrizione varchar(500) not null,
     constraint FKCLA_EVE_ID primary key (nome, tipo),
     constraint FKriferisce_ID unique (ID, data, ora));

create table insegna (
     ID int not null,
     data date not null,
     ora int not null,
     cf varchar(50) not null,
     constraint ID_insegna_ID primary key (cf, ID, data, ora));

create table INSEGNANTE (
     cf varchar(50) not null,
     cod_IBAN varchar(100) not null,
     constraint FKPER_INS_ID primary key (cf));

create table LEZIONE (
     ID int not null,
     data date not null,
     ora int not null,
     constraint ID_LEZIONE_ID primary key (ID, data, ora));

create table LISTINO (
     nome varchar(50) not null,
     tipo varchar(50) not null,
     numero_lezioni int not null,
     prezzo int not null,
     constraint ID_LISTINO_ID primary key (nome, tipo, numero_lezioni));

create table partecipa (
     ID int not null,
     data date not null,
     ora int not null,
     cf varchar(50) not null,
     constraint ID_partecipa_ID primary key (cf, ID, data, ora));

create table PERSONA (
     cf varchar(50) not null,
     nome varchar(50) not null,
     cognome varchar(50) not null,
     nascita date not null,
     email varchar(100),
     telefono varchar(15),
     INSEGNANTE varchar(50),
     CLIENTE varchar(50),
     constraint ID_PERSONA_ID primary key (cf));

create table richiesata_lezione_privata (
     R_C_cf varchar(50) not null,
     cf varchar(50) not null,
     constraint ID_richiesata_lezione_privata_ID primary key (cf, R_C_cf));

create table STILE (
     nome varchar(50) not null,
     constraint ID_STILE_ID primary key (nome));

create table USER (
     UserName varchar(50) not null,
     cf varchar(50) not null,
     passward_hash varchar(100) not null,
     ruolo varchar(50) not null,
     constraint ID_USER_ID primary key (UserName),
     constraint FKutente_ID unique (cf));


-- Constraints Section
-- ___________________ 

alter table ABBONAMENTO add constraint FKassociato_FK
     foreign key (nome, tipo, numero_lezioni)
     references LISTINO (nome, tipo, numero_lezioni);

alter table ABBONAMENTO add constraint FKacquista
     foreign key (cf)
     references CLIENTE (cf);

-- Not implemented
-- alter table CLASSE add constraint ID_CLASSE_CHK
--     check(exists(select * from LISTINO
--                  where LISTINO.nome = nome and LISTINO.tipo = tipo)); 

alter table CLASSE add constraint EXTONE_CLASSE
     check((EVENTO is not null and CORSO is null)
           or (EVENTO is null and CORSO is not null)); 

alter table CLASSE add constraint FKtipologia
     foreign key (nome)
     references STILE (nome);

-- Not implemented
-- alter table CLIENTE add constraint FKPER_CLI_CHK
--     check(exists(select * from partecipa
--                  where partecipa.cf = cf)); 

alter table CLIENTE add constraint FKPER_CLI_FK
     foreign key (cf)
     references PERSONA (cf);

alter table COMMENTO add constraint FKfeedback_FK
     foreign key (nome, tipo, anno, livello)
     references EDIZIONE_CORSO (nome, tipo, anno, livello);

-- Not implemented
-- alter table CORSO add constraint FKCLA_COR_CHK
--     check(exists(select * from EDIZIONE_CORSO
--                  where EDIZIONE_CORSO.nome = nome and EDIZIONE_CORSO.tipo = tipo)); 

alter table CORSO add constraint FKCLA_COR_FK
     foreign key (nome, tipo)
     references CLASSE (nome, tipo);

alter table EDIZIONE_CORSO add constraint FKappartiene_FK
     foreign key (ID, data, ora)
     references LEZIONE (ID, data, ora);

alter table EDIZIONE_CORSO add constraint FKstorico
     foreign key (nome, tipo)
     references CORSO (nome, tipo);

alter table EVENTO add constraint FKCLA_EVE_FK
     foreign key (nome, tipo)
     references CLASSE (nome, tipo);

alter table EVENTO add constraint FKriferisce_FK
     foreign key (ID, data, ora)
     references LEZIONE (ID, data, ora);

alter table insegna add constraint FKins_INS
     foreign key (cf)
     references INSEGNANTE (cf);

alter table insegna add constraint FKins_LEZ_FK
     foreign key (ID, data, ora)
     references LEZIONE (ID, data, ora);

-- Not implemented
-- alter table INSEGNANTE add constraint FKPER_INS_CHK
--     check(exists(select * from insegna
--                  where insegna.cf = cf)); 

alter table INSEGNANTE add constraint FKPER_INS_FK
     foreign key (cf)
     references PERSONA (cf);

-- Not implemented
-- alter table LEZIONE add constraint ID_LEZIONE_CHK
--     check(exists(select * from insegna
--                  where insegna.ID = ID and insegna.data = data and insegna.ora = ora)); 

-- Not implemented
-- alter table LEZIONE add constraint ID_LEZIONE_CHK
--     check(exists(select * from partecipa
--                  where partecipa.ID = ID and partecipa.data = data and partecipa.ora = ora)); 

alter table LEZIONE add constraint FKcalendario
     foreign key (ID)
     references AULA (ID);

-- Not implemented
-- alter table LISTINO add constraint ID_LISTINO_CHK
--     check(exists(select * from ABBONAMENTO
--                  where ABBONAMENTO.nome = nome and ABBONAMENTO.tipo = tipo and ABBONAMENTO.numero_lezioni = numero_lezioni)); 

alter table LISTINO add constraint FKprezzo_per_classe
     foreign key (nome, tipo)
     references CLASSE (nome, tipo);

alter table partecipa add constraint FKpar_CLI
     foreign key (cf)
     references CLIENTE (cf);

alter table partecipa add constraint FKpar_LEZ_FK
     foreign key (ID, data, ora)
     references LEZIONE (ID, data, ora);

alter table PERSONA add constraint EXTONE_PERSONA
     check((CLIENTE is not null and INSEGNANTE is null)
           or (CLIENTE is null and INSEGNANTE is not null)); 

alter table richiesata_lezione_privata add constraint FKric_INS
     foreign key (cf)
     references INSEGNANTE (cf);

alter table richiesata_lezione_privata add constraint FKric_CLI_FK
     foreign key (R_C_cf)
     references CLIENTE (cf);

-- Not implemented
-- alter table STILE add constraint ID_STILE_CHK
--     check(exists(select * from CLASSE
--                  where CLASSE.nome = nome)); 

alter table USER add constraint FKutente_FK
     foreign key (cf)
     references PERSONA (cf);


-- Index Section
-- _____________ 

create unique index ID_ABBONAMENTO_IND
     on ABBONAMENTO (cf, nome, tipo, numero_lezioni);

create index FKassociato_IND
     on ABBONAMENTO (nome, tipo, numero_lezioni);

create unique index ID_AULA_IND
     on AULA (ID);

create unique index ID_CLASSE_IND
     on CLASSE (nome, tipo);

create unique index FKPER_CLI_IND
     on CLIENTE (cf);

create unique index ID_COMMENTO_IND
     on COMMENTO (ID);

create index FKfeedback_IND
     on COMMENTO (nome, tipo, anno, livello);

create unique index FKCLA_COR_IND
     on CORSO (nome, tipo);

create unique index ID_EDIZIONE_CORSO_IND
     on EDIZIONE_CORSO (nome, tipo, anno, livello);

create unique index FKappartiene_IND
     on EDIZIONE_CORSO (ID, data, ora);

create unique index FKCLA_EVE_IND
     on EVENTO (nome, tipo);

create unique index FKriferisce_IND
     on EVENTO (ID, data, ora);

create unique index ID_insegna_IND
     on insegna (cf, ID, data, ora);

create index FKins_LEZ_IND
     on insegna (ID, data, ora);

create unique index FKPER_INS_IND
     on INSEGNANTE (cf);

create unique index ID_LEZIONE_IND
     on LEZIONE (ID, data, ora);

create unique index ID_LISTINO_IND
     on LISTINO (nome, tipo, numero_lezioni);

create unique index ID_partecipa_IND
     on partecipa (cf, ID, data, ora);

create index FKpar_LEZ_IND
     on partecipa (ID, data, ora);

create unique index ID_PERSONA_IND
     on PERSONA (cf);

create unique index ID_richiesata_lezione_privata_IND
     on richiesata_lezione_privata (cf, R_C_cf);

create index FKric_CLI_IND
     on richiesata_lezione_privata (R_C_cf);

create unique index ID_STILE_IND
     on STILE (nome);

create unique index ID_USER_IND
     on USER (UserName);

create unique index FKutente_IND
     on USER (cf);

