--                      REQUETES :

-- Nombre d’activité des agendas par catégorie et par utilisateur :

SELECT c.idCategorie, g.idUtilisateur, count(c.idActivite)
FROM ACTIVITE c NATURAL JOIN AGENDA g
GROUP BY c.idCategorie, g.idUtilisateur;

-- Nombre de commentaires total pour les utilisateurs actifs (utilisateurs 
-- ayant édité un agenda au cours des trois derniers mois) :

SELECT count(*)
FROM COMMENTAIRE
WHERE idUtilisateur IN (SELECT idUtilisateur
						FROM AGENDA
						WHERE idAgenda IN (SELECT idAgenda
											FROM ACTIVITE
											WHERE datediff('DD', dateUpdate, sysdate) < 90));

-- Les activités ayant eu au moins cinq évaluations et dont la note moyenne est inférieure à
-- trois :

SELECT *
FROM ACTIVITE
WHERE idActivite IN (SELECT idActivite
					FROM EVALUATION
					GROUP BY idActivite
					HAVING count(*) > 5 AND avg(note) < 3);

-- L’agenda ayant le plus de commentaire moyens (commentaires ouverts et restreints) par
-- activités :

SELECT a.idAgenda, max(avg(count(c.idComm))
FROM ACTIVITE a NATURAL JOIN COMMENTAIRE c
GROUP BY a.idAgenda;
-- à revoir


-- Pour chaque utilisateur, son login, son nom, son prénom, son adresse, son nombre
-- d’agendas, son nombre d’activités, le nombre de commentaires qu’il a rédigés et 
-- le nombre d’activités qu’il a évaluées :


SELECT u.idUtilisateur, u.pseudo, u.nom, u.prenom, u.adresse, count(g.idAgenda), count(t.idActivite), count(c.idComm), count(e.idActivite)
FROM UTILISATEUR u JOIN AGENDA g ON u.idUtilisateur = g.idUtilisateur 
					JOIN ACTIVITE t ON g.idAgenda = t.idAgenda 
					JOIN COMMENTAIRE c ON u.idUtilisateur = c.idUtilisateur
					JOIN EVALUATION e ON u.idUtilisateur = e.idUtilisateur
GROUP BY u.idUtilisateur;


--						PROCEDURES :


-- Définir une fonction qui convertit au format csv (colonnes csv dans le même 
-- ordre que celles de la table) ou au format iCalendar une activité d’un 
-- calendrier :

CREATE OR REPLACE FUNCTION sqltocsv(csv_p boolean, idAct_p number) return varchar2 IS
DECLARE
	row ACTIVITE%ROWTYPE;
	return_string_p varchar2;
	categorie varchar2;
BEGIN
	SELECT * INTO row
	FROM ACTIVITE
	WHERE idActivite = idAct_p;

	SELECT descriptif INTO categorie
	FROM CATEGORIE
	WHERE idCategorie = row.idCategorie;

	IF (csv_p = 1) THEN
		return_string_p := '\"' || row.titre ||'\"','\"'|| 
						row.descriptif ||'\"','\"'|| 
						row.posGeographique ||'\"','\"'||
						categorie ||'\"',||
						to_char(row.dateDeb,'DD/MM/YYYY HH:MM:SS') ||,|| 
						to_char(row.dateFin,'DD/MM/YYYY HH:MM:SS');
	ELSE
		return_string_p := 'BEGIN:VEVENT' || char(13) ||
						'DTSTART:' || to_char(row.dateDeb, 'YYYYMMDD') || 'T' || to_char(row.dateDeb, 'HHMMSS') || 'Z' || char(13) ||
						'DTEND:' || to_char(row.dateFin, 'YYYYMMDD') || 'T' || to_char(row.dateFin, 'HHMMSS') || 'Z' || char(13) ||
						'SUMMARY:' || row.titre || char(13) ||
						'DESCRIPTION:' || row.descriptif || char(13) ||
						'LOCATION:' || row.posGeographique || char(13) ||
						'CATEGORIES:' || categorie || char(13) ||
						'END:VEVENT';
	END IF;

	return return_string_p;
END;
/

-- Créer une fonction permettant d’exporter au format csv ou iCalendar les 
-- activités d’un agenda pour une semaine de l’année donnée. Le résultat sera 
-- renvoyé sous la forme d’une chaîne de caractère :

CREATE OR REPLACE FUNCTION sqltocsv_week(csv_p boolean, idAg_p number, numSem_p number) return varchar2 IS
DECLARE
	CURSOR curs IS
		SELECT idActivite
		FROM ACTIVITE
		WHERE idAgenda = idAg_p AND numSemaine = numSem_p;
	return_string_p varchar2;
BEGIN
	IF (csv_p = 1) THEN
		return_string_p := 'titre,description,localisation,categorie,dateDebut,dateFin' || char(13)

		FOR act IN curs
		LOOP
			return_string_p := return_string_p || sqltocsv(1,act) || char(13);
		END LOOP;
	ELSE
		return_string_p := 'BEGIN:VCALENDAR' || char(13);

		FOR act IN curs
		LOOP
			return_string_p := return_string_p || sqltocsv(0,act) || char(13);
		END LOOP;

		return_string_p := return_string_p || 'END:VCALENDAR';
	END IF;

	return return_string_p;
END;
/

-- Définir une procédure qui permet de fusionner plusieurs agendas, c’est à dire 
-- à partir de N agendas, créer un nouvel agenda :


CREATE OR REPLACE PROCEDURE fusionAgendas(agendas_p VARRAY OF NUMBER, name VARCHAR2) IS
DECLARE
	new_id_v number := seq_agenda;
BEGIN
	INSERT INTO AGENDA (idAgenda, nom)
	VALUES (new_id_v, name);

	FOR i IN 0..agendas_p.count
	LOOP
		FOR row IN (SELECT * FROM ACTIVITE WHERE idAgenda = agendas_p(i))
		LOOP
			INSERT INTO ACTIVITE
			VALUES (seq_activite, new_id_v, row.idCategorie, row.titre, row.descriptif, row.posGeographique, sysdate, sysdate, row.dateDeb, row.dateFin, row.periodicite, row.occurences, row.priorite);
		END LOOP;
	END LOOP;
END;
/

-- Définir une procédure qui crée une activité inférée à partir d’agendas
-- existants pour réaliser les cas présents dans l’énoncé comme par exemple, 
-- reporter au week-end l’achat d’objets sortis aux cours de la semaine ou 
-- reporter au soir le visionnage d’une émission sortie au cours de la journée :

CREATE OR REPLACE PROCEDURE deplacementActivite(idAct_p NUMBER, new_date_deb date) IS
DECLARE
	old_date_deb date;
	old_date_fin date;
	new_date_fin date;
	temps_inter date;
BEGIN
	SELECT dateDeb, dateFin INTO old_date_deb, old_date_fin
	FROM ACTIVITE
	WHERE idActivite = idAct_p;

	temps_inter := new_date_deb - old_date_deb;
	new_date_fin := old_date_fin + temps_inter;

	UPDATE ACTIVITE
	SET dateDeb = new_date_deb AND dateFin = new_date_fin
	WHERE idActivite = idAct_p;
END;
/

-- Définir une procédure qui archive les agendas dont toutes les dates sont passées :

CREATE OR REPLACE PROCEDURE archiveAgenda IS
DECLARE
	CURSOR agendas IS
		SELECT idAgenda
		FROM AGENDA;
	dateAct date;
	allFinish number := 0;
BEGIN
	FOR ag IN agendas
	LOOP
		FOR actDate IN (SELECT dateFin FROM ACTIVITE WHERE idAgenda = ag)
		LOOP
			IF(actDate > sysdate) THEN
				allFinish := allFinish + 1;
			END IF;
		END LOOP;

		IF(allFinish = 0) THEN
			DELETE FROM AGENDA WHERE idAgenda = ag;
		END IF;
	END LOOP;
END;
/

--						TRIGGERS :


-- Un agenda comportera au maximum 50 activités par semaine :


CREATE OR REPLACE TRIGGER maxSem
	BEFORE INSERT ON ACTIVITE
	FOR EACH ROW
	FOLLOWS numSemaine
DECLARE
	nbAct_v number;
BEGIN
	SELECT count(*) INTO nbAct_v
	FROM ACTIVITE
	WHERE idAgenda = :new.idAgenda AND to_char(sysdate, 'YYYY') = to_char(dateDeb, 'YYYY') AND numSemaine = :new.numSemaine;

	IF (nbAct_v > 50) THEN
		RAISE_APPLICATION_ERROR(-20007, 'Il y a déjà 50 activités durant cette semaine.');
	END IF;
END;
/


-- Les agendas et les activités supprimées seront archivés pour pouvoir être 
-- récupérés si nécessaire :


CREATE OR REPLACE TRIGGER archivageAgenda
	BEFORE DELETE ON AGENDA
	FOR EACH ROW
BEGIN
	FOR act IN (SELECT idActivite FROM ACTIVITE WHERE idAgenda = :old.idAgenda)
	LOOP
		DELETE FROM ACTIVITE WHERE idActivite = act;
	END LOOP;

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


-- Le nombre d’activités présentes dans l’agenda et la périodicité 
-- indiquée pour l’activité correspondent strictement :

CREATE OR REPLACE TRIGGER nombreOccurencesCorres
	AFTER INSERT ON ACTIVITE
	FOR EACH ROW
DECLARE
	nb_activite_v number;
BEGIN
	SELECT count(*) INTO nb_activite_v
	FROM ACTIVITE
	WHERE idSimilaire = :new.idSimilaire;

	IF (nb_activite_v != :new.occurences) THEN
		RAISE_APPLICATION_ERROR(-20006, 'Nombre d\'activité présentes dans l\'agenda ne correspond pas à la périodicité indiquée');
	END IF;
END;
/


-- Pour les agendas où la simultanéité d’activité n’est pas autorisée, 
-- interdire que deux activités aient une intersection non nulle de 
-- leur créneau : 








-- Afin de limiter le spam de commentaires et d'évaluation de commentaires, 
-- un utilisateur enregistré depuis moins d'une journée ne pourra écrire un 
-- message ou une évaluation que toutes les 10 minutes, un utilisateur qui 
-- n’a pas au moins une évaluation positive d’un de ses commentaires devra 
-- attendre 1 minute entre chaque commentaires/évaluations et pour tous les 
-- utilisateurs il y aura une limite de 20 messages/évaluations par minute :


CREATE OR REPLACE TRIGGER limitationComm
	BEFORE INSERT ON COMMENTAIRE
	FOR EACH ROW
DECLARE
	test number := 0;
	test_aime number := 0;
	aime_comm boolean;
	dateI_v date;
	date_inter_v date;
	dix_minutes EXCEPTION;
	non_aime EXCEPTION;
	plus_vingt EXCEPTION;
BEGIN
	SELECT dateInscription INTO dateI_v
	FROM UTILISATEUR
	WHERE idUtilisateur = :new.idUtilisateur;

	date_inter_v = sysdate - dateI_v;

	-- inscrit depuis moins d'un jour
	IF(to_number(to_char(date_inter_v, 'HH') < 24)) THEN
		FOR curs IN (SELECT dateComm FROM COMMENTAIRE WHERE idUtilisateur = :new.idUtilisateur)
		LOOP
			IF(to_number(to_char((sysdate-curs), 'MM')) < 10) THEN
				test := 1;
			END IF;
		END LOOP;
		FOR curs IN (SELECT dateEval FROM EVALUATION WHERE idUtilisateur = :new.idUtilisateur)
		LOOP
			IF(to_number(to_char((sysdate-curs), 'MM')) < 10) THEN
				test := 1;
			END IF;
		END LOOP;
		IF test = 1 THEN
			RAISE dix_minutes;
		END IF;
	ELSE
		-- évalue ou commente plusieurs fois en moins d'une minute
		test := 0;
		FOR curs IN (SELECT dateComm FROM COMMENTAIRE WHERE idUtilisateur = :new.idUtilisateur)
		LOOP
			IF(to_number(to_char((sysdate-curs), 'SS')) < 60) THEN
				test := test + 1;
			END IF;
		END LOOP;
		FOR curs IN (SELECT dateEval FROM EVALUATION WHERE idUtilisateur = :new.idUtilisateur)
		LOOP
			IF(to_number(to_char((sysdate-curs), 'SS')) < 60) THEN
				test := test + 1;
			END IF;
		END LOOP;
		IF test >= 1 THEN
			FOR curs IN (SELECT idComm FROM COMMENTAIRE WHERE idUtilisateur = :new.idUtilisateur)
			LOOP
				SELECT aime INTO aime_comm
				FROM AIMECOMM
				WHERE idComm = curs;

				IF(aime_comm = 1) THEN
					test_aime := 1;
				END IF;
			END LOOP;
			IF test_aime = 0 THEN
				RAISE non_aime;
			ELSE IF test > 20 THEN
				RAISE plus_vingt;
			END IF;
		END IF;
	END IF;

	EXCEPTION
		WHEN dix_minutes THEN
			RAISE_APPLICATION_ERROR(-20001, 'Vous êtes inscrits depuis moins d\'un jour et avez déjà évalué ou commenté durant les dix dernières minutes.');
		WHEN non_aime THEN
			RAISE_APPLICATION_ERROR(-20002, 'Vous ne pouvez pas commenter ou évaluer plus d\'une fois par minute car aucun de vos commentaires n\'a été aimé.');
		WHEN plus_vingt THEN
			RAISE_APPLICATION_ERROR(-20003, 'Vous avez commenté ou évalué plus de 20 fois au cours de la dernière minute.');
END;
/



















