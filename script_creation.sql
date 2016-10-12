create table UTILISATEUR
(
	idUtilisateur number(8) PRIMARY KEY,
	nom varchar2(30),
	prenom varchar2(30),
	adresse varchar2(50),
	pseudo varchar2(30) UNIQUE,
	mdp varchar2(50),
	email varchar2(40) UNIQUE,
	dateInscription date DEFAULT sysdate
);

create table AGENDA
(
	idAgenda number(8) PRIMARY KEY,
	idUtilisateur number(8),
	nom varchar2(30),
	description varchar2(300),
	dateCreation date DEFAULT sysdate,
	dateUpdate date DEFAULT sysdate,
	intersection number(1),
	prive number(1),
	FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR ON DELETE SET NULL
);

create table CATEGORIE
(
	idCategorie number(8) PRIMARY KEY,
	nom varchar2(50),
	descriptif varchar2(300)
);

create table ACTIVITE
(
	idActivite number(8) PRIMARY KEY,
	idAgenda number(8) NOT NULL,
	idCategorie number(8),
	idSimilaire number(8),
	titre varchar2(50),
	descriptif varchar2(300),
	posGeographique varchar2(50),
	dateCreation date DEFAULT sysdate,
	dateUpdate date DEFAULT sysdate,
	dateDeb date,
	dateFin date,
	numSemaine number(2),
	numJour number(1),
	periodicite number(1),
	occurences number(8),
	priorite number(8),
	FOREIGN KEY (idAgenda) REFERENCES AGENDA ON DELETE CASCADE,
	FOREIGN KEY (idCategorie) REFERENCES CATEGORIE ON DELETE SET NULL,
	CHECK (dateDeb < dateFin),
	CHECK (periodicite IN (NULL,'J','S','M','A'))
);

create table PAUSE
(
	idPause number(8) PRIMARY KEY,
	idActivite number(8) NOT NULL,
	dateDeb date,
	dateFin date,
	periodicite number(1),
	occurences number(8),
	FOREIGN KEY (idActivite) REFERENCES ACTIVITE ON DELETE CASCADE,
	CHECK (dateDeb < dateFin)
);

create table COMMENTAIRE
(
	idComm number(8) PRIMARY KEY,
	idParent number(8),
	idUtilisateur number(8) NOT NULL,
	idActivite number(8) NOT NULL,
	commentaire varchar2(300),
	dateComm date DEFAULT sysdate,
	FOREIGN KEY (idParent) REFERENCES COMMENTAIRE,
	FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR ON DELETE CASCADE,
	FOREIGN KEY (idActivite) REFERENCES ACTIVITE ON DELETE CASCADE
);

create table AIMECOMM
(
	idUtilisateur number(8),
	idComm number(8),
	dateAime date DEFAULT sysdate,
	aime number(1) DEFAULT NULL,
	PRIMARY KEY (idUtilisateur, idComm),
	FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR ON DELETE CASCADE,
	FOREIGN KEY (idComm) REFERENCES COMMENTAIRE ON DELETE CASCADE
);

create table ABONNEMENT
(
	idUtilisateur number(8),
	idAgenda number(8),
	priorite number(8),
	PRIMARY KEY (idUtilisateur, idAgenda),
	FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR ON DELETE CASCADE,
	FOREIGN KEY (idAgenda) REFERENCES AGENDA ON DELETE CASCADE
);

create table INSCRIT
(
	idUtilisateur number(8),
	idActivite number(8),
	PRIMARY KEY (idUtilisateur, idActivite),
	FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR ON DELETE CASCADE,
	FOREIGN KEY (idActivite) REFERENCES ACTIVITE ON DELETE CASCADE
);

create table EVALUATION
(
	idUtilisateur number(8),
	idActivite number(8),
	note number(1),
	dateEval date DEFAULT sysdate,
	PRIMARY KEY (idUtilisateur, idActivite),
	FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR ON DELETE CASCADE,
	FOREIGN KEY (idActivite) REFERENCES ACTIVITE ON DELETE CASCADE,
	CHECK (note >= 1 AND note <= 5)
);

create table A_AGENDA
(
	idAgenda number(8) PRIMARY KEY,
	idUtilisateur number(8),
	nom varchar2(30),
	description varchar2(300),
	intersection number(1),
	prive number(1),
	partage number(1)
);

create table A_ACTIVITE
(
	idActivite number(8) PRIMARY KEY,
	idAgenda number(8) NOT NULL,
	idCategorie number(8),
	idSimilaire number(8),
	titre varchar2(50),
	descriptif varchar2(300),
	posGeographique varchar2(50),
	dateCreation date DEFAULT sysdate,
	dateUpdate date DEFAULT sysdate,
	dateDeb date,
	dateFin date,
	numSemaine number(2),
	numJour number(1),
	periodicite number(1),
	occurences number(8),
	priorite number(8)
);

-- SEQUENCES :

create sequence seq_utilisateur start with 1 increment by 1;
create sequence seq_agenda start with 1 increment by 1;
create sequence seq_activite start with 1 increment by 1;
create sequence seq_categorie start with 1 increment by 1;
create sequence seq_pause start with 1 increment by 1;
create sequence seq_comm start with 1 increment by 1;
create sequence seq_agenda_a start with 1 increment by 1;
create sequence seq_activite_a start with 1 increment by 1;
create sequence seq_similaire start with 1 increment by 1;

-- Agenda, privé ne peut pas être à 0 si partage est à 0.

CREATE OR REPLACE TRIGGER partageAgenda
	BEFORE INSERT OR UPDATE ON AGENDA
	FOR EACH ROW WHEN (NEW.prive = 0 AND NEW.partage = 0)
BEGIN
	RAISE_APPLICATION_ERROR(-20200, 'Insertion ou mise à jour d\'un agenda en mode public non partagé.');
END;
/

-- Activité similaire doit avoir les mêmes valeurs

CREATE OR REPLACE TRIGGER activiteSimilaire
	BEFORE INSERT OR UPDATE ON ACTIVITE
	FOR EACH ROW
BEGIN
	FOR curs IN (SELECT * FROM ACTIVITE WHERE idSimilaire = :new.idSimilaire)
	LOOP
		IF ((curs.idAgenda != :new.idAgenda)
			OR (curs.idCategorie != :new.idAgenda)
			OR (curs.titre != :new.titre)
			OR (curs.descriptif != :new.descriptif)
			OR (curs.posGeographique != :new.posGeographique)
			OR (curs.periodicite != :new.periodicite)
			OR (curs.priorite != :new.priorite)) THEN
			RAISE_APPLICATION_ERROR(-20005, 'Activité non similaire avec même idSimilaire.');
		END IF;
	END LOOP;
END;
/

-- Calcule le numéro de la semaine

SET DATEFIRST 1;

CREATE OR REPLACE TRIGGER numSemaine
	BEFORE INSERT ON ACTIVITE
	FOR EACH ROW
BEGIN
	:new.numSemaine = DATEPART(week, :new.dateDeb);
	:new.numJour = DATEPART(weekday, :new.dateDeb);
END;
/

-- Commentaire, un commentaire ne peut être posté que si l'activité est terminée

CREATE OR REPLACE TRIGGER commFinActivite
	BEFORE INSERT OR UPDATE ON COMMENTAIRE
	FOR EACH ROW
DECLARE
	date_fin date;
BEGIN
	SELECT dateFin INTO date_fin
	FROM ACTIVITE
	WHERE :new.idActivite = idActivite;

	IF :new.dateComm < date_fin THEN
		-- soulever une exception;
		RAISE_APPLICATION_ERROR(-20200, 'Insertion ou mise à jour d\'un commentaire pour une activité non terminée.');
	END IF;
END;
/

-- Archivage des tables lors de la suppression d'un élément de agenda ou activité

CREATE OR REPLACE TRIGGER archivageAgenda
	BEFORE DELETE ON AGENDA
	FOR EACH ROW
BEGIN
	INSERT INTO A_AGENDA
	VALUES (:old.idAgenda, :old.idUtilisateur, :old.nom, :old.description, :old.intersection, :old.prive, :old.partage);
END;
/

CREATE OR REPLACE TRIGGER archivageActivite
	BEFORE DELETE ON ACTIVITE
	FOR EACH ROW
BEGIN
	INSERT INTO A_ACTIVITE
	VALUES (:old.idActivite, :old.idAgenda, :old.titre, :old.descriptif, :old.posGeographique, :old.dateCreation, :old.dateDeb, :old.dateFin, :old.periodicite, :old.occurences, :old.priorite);
END;
/


DROP TABLE EVALUATION CASCADE CONSTRAINTS;
DROP TABLE ABONNEMENT CASCADE CONSTRAINTS;
DROP TABLE COMMENTAIRE CASCADE CONSTRAINTS;
DROP TABLE INSCRIT CASCADE CONSTRAINTS;
DROP TABLE AIMECOMM CASCADE CONSTRAINTS;
DROP TABLE PAUSE CASCADE CONSTRAINTS;
DROP TABLE ACTIVITE CASCADE CONSTRAINTS;
DROP TABLE CATEGORIE CASCADE CONSTRAINTS;
DROP TABLE AGENDA CASCADE CONSTRAINTS;
DROP TABLE UTILISATEUR CASCADE CONSTRAINTS;
DROP TABLE A_ACTIVITE CASCADE CONSTRAINTS;
DROP TABLE A_AGENDA CASCADE CONSTRAINTS;
