create table UTILISATEUR
(
	idUtilisateur INT(8) PRIMARY KEY AUTO_INCREMENT,
	nom varchar(30),
	prenom varchar(30),
	adresse varchar(50),
	pseudo varchar(30) UNIQUE,
	mdp varchar(50),
	email varchar(40) UNIQUE,
	dateInscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

create table AGENDA
(
	idAgenda INT(8) PRIMARY KEY AUTO_INCREMENT,
	idUtilisateur INT(8),
	nom varchar(30),
	description varchar(300),
	dateCreation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	dateUpdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	intersection INT(1),
	prive INT(1),
	FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR(idUtilisateur) ON DELETE SET NULL
);

create table CATEGORIE
(
	idCategorie INT(8) PRIMARY KEY AUTO_INCREMENT,
	nom varchar(50),
	descriptif varchar(300)
);


create table ACTIVITE
(
	idActivite INT(8) PRIMARY KEY AUTO_INCREMENT,
	idAgenda INT(8) NOT NULL,
	idCategorie INT(8),
	idSimilaire INT(8),
	titre varchar(50),
	descriptif varchar(300),
	posGeographique varchar(50),
	dateCreation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	dateUpdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	dateDeb date,
	dateFin date,
	numSemaine INT(2),
	numJour INT(1),
	periodicite INT(1),
	occurences INT(8),
	priorite INT(8),
	FOREIGN KEY (idAgenda) REFERENCES AGENDA(idAgenda) ON DELETE CASCADE,
	FOREIGN KEY (idCategorie) REFERENCES CATEGORIE(idCategorie) ON DELETE SET NULL
);

create table PAUSE
(
	idPause INT(8) PRIMARY KEY AUTO_INCREMENT,
	idActivite INT(8) NOT NULL,
	dateDeb date,
	dateFin date,
	periodicite INT(1),
	occurences INT(8),
	FOREIGN KEY (idActivite) REFERENCES ACTIVITE(idActivite) ON DELETE CASCADE
);

create table COMMENTAIRE
(
	idComm INT(8) PRIMARY KEY AUTO_INCREMENT,
	idParent INT(8),
	idUtilisateur INT(8) NOT NULL,
	idActivite INT(8) NOT NULL,
	commentaire varchar(300),
	dateComm TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR(idUtilisateur) ON DELETE CASCADE,
	FOREIGN KEY (idActivite) REFERENCES ACTIVITE(idActivite) ON DELETE CASCADE
);

create table AIMECOMM
(
	idUtilisateur INT(8) AUTO_INCREMENT,
	idComm INT(8),
	dateAime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	aime INT(1) DEFAULT NULL,
	PRIMARY KEY (idUtilisateur, idComm),
	FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR(idUtilisateur) ON DELETE CASCADE,
	FOREIGN KEY (idComm) REFERENCES COMMENTAIRE(idComm) ON DELETE CASCADE
);

create table ABONNEMENT
(
	idUtilisateur INT(8),
	idAgenda INT(8),
	priorite INT(8),
	PRIMARY KEY (idUtilisateur, idAgenda),
	FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR(idUtilisateur) ON DELETE CASCADE,
	FOREIGN KEY (idAgenda) REFERENCES AGENDA(idAgenda) ON DELETE CASCADE
);

create table INSCRIT
(
	idUtilisateur INT(8),
	idActivite INT(8),
	PRIMARY KEY (idUtilisateur, idActivite),
	FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR(idUtilisateur) ON DELETE CASCADE,
	FOREIGN KEY (idActivite) REFERENCES ACTIVITE(idActivite) ON DELETE CASCADE
);

create table EVALUATION
(
	idUtilisateur INT(8),
	idActivite INT(8),
	note INT(1),
	dateEval TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (idUtilisateur, idActivite),
	FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR(idUtilisateur) ON DELETE CASCADE,
	FOREIGN KEY (idActivite) REFERENCES ACTIVITE(idActivite) ON DELETE CASCADE
);

create table A_AGENDA
(
	idAgenda INT(8) PRIMARY KEY,
	idUtilisateur INT(8),
	nom varchar(30),
	description varchar(300),
	intersection INT(1),
	prive INT(1),
	partage INT(1)
);

create table A_ACTIVITE
(
	idActivite INT(8) PRIMARY KEY,
	idAgenda INT(8) NOT NULL,
	idCategorie INT(8),
	idSimilaire INT(8),
	titre varchar(50),
	descriptif varchar(300),
	posGeographique varchar(50),
	dateCreation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	dateUpdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	dateDeb date,
	dateFin date,
	numSemaine INT(2),
	numJour INT(1),
	periodicite INT(1),
	occurences INT(8),
	priorite INT(8)
);
