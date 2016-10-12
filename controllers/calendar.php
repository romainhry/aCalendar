<?php

require_once 'models/agenda.php';
require_once 'models/activite.php';
require_once 'models/categorie.php';
require_once 'models/abonnement.php';
require_once 'models/utilisateur.php';

class Controller_Calendar
{

	public function show_calendar() {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :

				$jour = date("w");

				$_SESSION['mois'] = date("n");
				$_SESSION['jour'] = date("j");
				$_SESSION['annee'] = date("y");

				$dateDebSemaineFr = date("Y-n-j H:i:s", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour']-$jour+1,$_SESSION['annee']));
				$datePrecise = date("Y-n-j H:i:s", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour'],$_SESSION['annee']));
				$dateFinSemaineFr = date("Y-n-j H:i:s", mktime(23,59,0,$_SESSION['mois'],$_SESSION['jour']-$jour+7,$_SESSION['annee']));
				//self::actualise_date_maintenant();
				//si l'utilisateur est connecté on affiche la page de création d'une note
				if(isset($_SESSION['user'])) {
					$_SESSION['num'] = 0;

					$abonnement = Abonnement::get_by_user($_SESSION['idUser']);
					$mes_agendas = Agenda::get_by_user_login($_SESSION['user']);
					$all_agendas = array();
					$abonnements=array();
					for($i=0; $i<count($mes_agendas); $i++) {
						$all_agendas[] = Agenda::get_by_id($mes_agendas[$i]->idAgenda());
					}
					for($i=0; $i<count($abonnement); $i++) {

						$abonnements[] = Agenda::get_by_id($abonnement[$i]->idAgenda());
						$all_agendas[] = $abonnements[$i];
					}

					if(!empty($all_agendas))
					{
						$activites = Activite::get_by_idUtilisateurAgendaDate($all_agendas[$_SESSION['num']]->idUtilisateur(),$all_agendas[$_SESSION['num']]->idAgenda(),$dateDebSemaineFr,$dateFinSemaineFr);
						for($m=0;$m<count($activites);$m++)
						{
							for($l=0;$l<24;$l++)
							{
								for($k=0;$k<7;$k++)
								{
									$date_deb_inter = date_create_from_format('Y-n-j H:i:s',$activites[$m]->dateDeb());
									$date_fin_inter = date_create_from_format('Y-n-j H:i:s',$activites[$m]->dateFin());
									$date_debsem_inter = date_create_from_format('Y-n-j H:i:s',$dateDebSemaineFr);

									if($date_deb_inter->format('G') <= $l && $date_fin_inter->format('G') > $l && $date_deb_inter->format('j') == $date_debsem_inter->format('j')+$k) {
										$heure_jour[$l][$k] = $activites[$m];
									}
								}
							}
						}
					}

					include 'views/calendar.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				if(isset($_SESSION['user'])) {
					include 'views/calendar.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;
		}
	}

	public function show_other_calendar($numero) {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				$jour = date("w");

				$_SESSION['mois'] = date("n");
				$_SESSION['jour'] = date("j");
				$_SESSION['annee'] = date("y");

				$dateDebSemaineFr = date("Y-n-j H:i:s", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour']-$jour+1,$_SESSION['annee']));
				$datePrecise = date("Y-n-j H:i:s", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour'],$_SESSION['annee']));
				$dateFinSemaineFr = date("Y-n-j H:i:s", mktime(23,59,0,$_SESSION['mois'],$_SESSION['jour']-$jour+7,$_SESSION['annee']));
				//si l'utilisateur est connecté on affiche la page de création d'une note
				if(isset($_SESSION['user'])) {
					$_SESSION['num'] = $numero;

					$abonnement = Abonnement::get_by_user($_SESSION['idUser']);
					$mes_agendas = Agenda::get_by_user_login($_SESSION['user']);
					$all_agendas = array();
					$abonnements=array();
					for($i=0; $i<count($mes_agendas); $i++) {
						$all_agendas[] = Agenda::get_by_id($mes_agendas[$i]->idAgenda());
					}
					for($i=0; $i<count($abonnement); $i++) {
						$abonnements[] = Agenda::get_by_id($abonnement[$i]->idAgenda());
						$all_agendas[] = Agenda::get_by_id($abonnement[$i]->idAgenda());
					}

					if(!empty($all_agendas))
					{
						$activites = Activite::get_by_idUtilisateurAgendaDate($all_agendas[$_SESSION['num']]->idUtilisateur(),$all_agendas[$_SESSION['num']]->idAgenda(),$dateDebSemaineFr,$dateFinSemaineFr);
						for($m=0;$m<count($activites);$m++)
						{
							for($l=0;$l<24;$l++)
							{
								for($k=0;$k<7;$k++)
								{
									$date_deb_inter = date_create_from_format('Y-n-j H:i:s',$activites[$m]->dateDeb());
									$date_fin_inter = date_create_from_format('Y-n-j H:i:s',$activites[$m]->dateFin());
									$date_debsem_inter = date_create_from_format('Y-n-j H:i:s',$dateDebSemaineFr);

									if($date_deb_inter->format('G') <= $l && $date_fin_inter->format('G') > $l && $date_deb_inter->format('j') == $date_debsem_inter->format('j')+$k) {
										$heure_jour[$l][$k] = $activites[$m];
									}
								}
							}
						}
					}
					include 'views/calendar.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				if(isset($_SESSION['user'])) {
					include 'views/calendar.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;
		}
	}

	public function add_calendar() {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				//si l'utilisateur est connecté on affiche la page de création d'une note
				if(isset($_SESSION['user'])) {
					include 'views/createAgenda.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "Vous n'êtes pas connecté";
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				if(isset($_SESSION['user'])) {
					$u = Utilisateur::get_by_login($_SESSION['user']);

					if(isset($_POST['title']) && isset($_POST['content'])) {
						if(isset($_POST['intersection'])) {
							$intersection = 1;
						} else {
							$intersection = 0;
						}
						if(isset($_POST['prive'])) {
							$prive = 0;
						} else {
							$prive = 1;
						}
						$n = new Agenda(1, $u->idUtilisateur(), $_POST['title'], $_POST['content'], '0', '0', $intersection, $prive);
						$n->add();
						$_SESSION['message']['type'] = 'success';
						$_SESSION['message']['text'] = "L'agenda ".$_POST['title']." a bien été créé.";
						include 'views/home.php';
					}
					else {
						$_SESSION['message']['type'] = 'error';
						$_SESSION['message']['text'] = 'Données postées incomplètes';
						include 'views/home.php';
					}
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "Vous n'êtes pas connecté";
					include 'views/connexion.php';
				}
				break;
		}
	}

	public function add_activite() {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				//si l'utilisateur est connecté on affiche la page de création d'une note
				if(isset($_SESSION['user'])) {
					$cat = Categorie::get_all();
					$u = Utilisateur::get_by_login($_SESSION['user']);
					$agendas = Agenda::get_by_user($u->idUtilisateur());
					include 'views/createActivite.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "Vous n'êtes pas connecté";
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				if(isset($_SESSION['user'])) {
					$u = Utilisateur::get_by_login($_SESSION['user']);

					if(!empty($_POST['titre']) && !empty($_POST['description']) && !empty($_POST['location']) && !empty($_POST['datedeb']) && !empty($_POST['datefin'])) {

						$date_debut = date_create_from_format('Y-n-j?H:i', $_POST['datedeb']);
						$date_debut_ts = $date_debut->format('U');
						$heure_debut = $date_debut->format('H');
						$jour_debut = $date_debut->format('j');
						$mois_debut = $date_debut->format('n');
						$annee_debut = $date_debut->format('Y');
						$date_debut = $date_debut->format('Y-n-j H');

						$date_fin = date_create_from_format('Y-n-j?H:i', $_POST['datefin']);
						$date_fin_ts = $date_fin->format('U');
						$heure_fin = $date_fin->format('H');
						$jour_fin = $date_fin->format('j');
						$mois_fin = $date_fin->format('n');
						$annee_fin = $date_fin->format('Y');
						$date_fin = $date_fin->format('Y-n-j H');

						if(empty($_POST['periodicite'])) {
							$periodicite = 'P';
						} else {
							$periodicite = $_POST['periodicite'];
						}
						if(empty($_POST['occurences'])) {
							$occ = 0;
						} else {
							$occ = $_POST['occurences'];
						}

						$_SESSION['similaire']++;
						$act = new Activite(1, $_POST['agenda'], $_POST['categorie'], $_SESSION['similaire'], $_POST['titre'], $_POST['description'], $_POST['location'], '1', '1', $date_debut, $date_fin, 1, 1, $periodicite, $occ, $_POST['priorite']);
						$act->add();

						if($occ > 1) {
							if($periodicite == 'J') {
								while($occ > 1) {
									$nb_jours = date("t", $date_debut_ts);
									if($jour_debut+1 > $nb_jours) {
										if($mois_debut >= 12) {
											$mois_debut = 1;
											$annee_debut = $annee_debut + 1;
										} else {
											$mois_debut = $mois_debut + 1;
										}
										$jour_debut = ($jour_debut+1) - $nb_jours;
									} else {
										$jour_debut = $jour_debut+1;
									}

									$nb_jours = date("t", $date_fin_ts);
									if($jour_fin+1 > $nb_jours) {
										if($mois_fin >= 12) {
											$mois_fin = 1;
											$annee_fin = $annee_fin + 1;
										} else {
											$mois_fin = $mois_fin + 1;
										}
										$jour_fin = ($jour_fin+1) - $nb_jours;
									} else {
										$jour_fin = $jour_fin+1;
									}

									$date_debut_ts = date('U', mktime(0,0,0,$mois_debut,$jour_debut,$annee_debut));
									$date_fin_ts = date('U', mktime(0,0,0,$mois_fin,$jour_fin,$annee_fin));
									$date_debut = date('Y-n-j H', mktime($heure_debut,0,0,$mois_debut,$jour_debut,$annee_debut));
									$date_fin = date('Y-n-j H', mktime($heure_fin,0,0,$mois_fin,$jour_fin,$annee_fin));

									$occ = $occ - 1;
									$act = new Activite(1, $_POST['agenda'], $_POST['categorie'], $_SESSION['similaire'], $_POST['titre'], $_POST['description'], $_POST['location'], '1', '1', $date_debut, $date_fin, 1, 1, $periodicite, $occ, $_POST['priorite']);
									$act->add();
								}
							} else if ($periodicite == 'S') {
								while($occ > 1) {
									$nb_jours = date("t", $date_debut_ts);
									if($jour_debut+7 > $nb_jours) {
										if($mois_debut >= 12) {
											$mois_debut = 1;
											$annee_debut = $annee_debut + 1;
										} else {
											$mois_debut = $mois_debut + 1;
										}
										$jour_debut = ($jour_debut+7) - $nb_jours;
									} else {
										$jour_debut = $jour_debut+7;
									}

									$nb_jours = date("t", $date_fin_ts);
									if($jour_fin+7 > $nb_jours) {
										if($mois_fin >= 12) {
											$mois_fin = 1;
											$annee_fin = $annee_fin + 1;
										} else {
											$mois_fin = $mois_fin + 1;
										}
										$jour_fin = ($jour_fin+7) - $nb_jours;
									} else {
										$jour_fin = $jour_fin+7;
									}

									$date_debut_ts = date('U', mktime(0,0,0,$mois_debut,$jour_debut,$annee_debut));
									$date_fin_ts = date('U', mktime(0,0,0,$mois_fin,$jour_fin,$annee_fin));
									$date_debut = date('Y-n-j H', mktime($heure_debut,0,0,$mois_debut,$jour_debut,$annee_debut));
									$date_fin = date('Y-n-j H', mktime($heure_fin,0,0,$mois_fin,$jour_fin,$annee_fin));

									$occ = $occ - 1;
									$act = new Activite(1, $_POST['agenda'], $_POST['categorie'], $_SESSION['similaire'], $_POST['titre'], $_POST['description'], $_POST['location'], '1', '1', $date_debut, $date_fin, 1, 1, $periodicite, $occ, $_POST['priorite']);
									$act->add();
								}
							} else if ($periodicite == 'M') {
								while($occ > 1) {
									if($mois_debut >= 12) {
										$mois_debut = 1;
										$annee_debut = $annee_debut + 1;
									} else {
										$mois_debut = $mois_debut + 1;
									}

									if($mois_fin >= 12) {
										$mois_fin = 1;
										$annee_fin = $annee_fin + 1;
									} else {
										$mois_fin = $mois_fin + 1;
									}

									$date_debut = date('Y-n-j H', mktime($heure_debut,0,0,$mois_debut,$jour_debut,$annee_debut));
									$date_fin = date('Y-n-j H', mktime($heure_fin,0,0,$mois_fin,$jour_fin,$annee_fin));

									$occ = $occ - 1;
									$act = new Activite(1, $_POST['agenda'], $_POST['categorie'], $_SESSION['similaire'], $_POST['titre'], $_POST['description'], $_POST['location'], '1', '1', $date_debut, $date_fin, 1, 1, $periodicite, $occ, $_POST['priorite']);
									$act->add();
								}
							} else if ($periodicite == 'A') {
								while($occ > 1) {
									$annee_debut = $annee_debut + 1;
									$annee_fin = $annee_fin + 1;

									$date_debut = date('Y-n-j H', mktime($heure_debut,0,0,$mois_debut,$jour_debut,$annee_debut));
									$date_fin = date('Y-n-j H', mktime($heure_fin,0,0,$mois_fin,$jour_fin,$annee_fin));

									$occ = $occ - 1;
									$act = new Activite(1, $_POST['agenda'], $_POST['categorie'], $_SESSION['similaire'], $_POST['titre'], $_POST['description'], $_POST['location'], '1', '1', $date_debut, $date_fin, 1, 1, $periodicite, $occ, $_POST['priorite']);
									$act->add();
								}
							}
						}
						
						$_SESSION['message']['type'] = 'success';
						$_SESSION['message']['text'] = "L'activité ".$_POST['titre']." a bien été créée.";
						include 'views/home.php';
					}
					else {
						$_SESSION['message']['type'] = 'error';
						$_SESSION['message']['text'] = 'Données postées incomplètes';
						$this->show_calendar();
					}
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "Vous n'êtes pas connecté";
					include 'views/connexion.php';
				}
				break;
		}
	}

	public function actualise_date_plus() {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				//si l'utilisateur est connecté on affiche la page de création d'une note
				if(isset($_SESSION['user'])) {
					$jour = date("w");

					$datePrecise_ts = date("U", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour'],$_SESSION['annee']));

					$nb_jours = date("t", $datePrecise_ts);
					if($_SESSION['jour']+7 > $nb_jours) {
						if($_SESSION['mois'] >= 12) {
							$_SESSION['mois'] = 1;
							$_SESSION['annee'] = $_SESSION['annee'] + 1;
						} else {
							$_SESSION['mois'] = $_SESSION['mois'] + 1;
						}
						$_SESSION['jour'] = ($_SESSION['jour']+7) - $nb_jours;
					} else {
						$_SESSION['jour'] = $_SESSION['jour']+7;
					}

					$dateDebSemaineFr = date("Y-n-j H:i:s", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour']-$jour+1,$_SESSION['annee']));
				$datePrecise = date("Y-n-j H:i:s", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour'],$_SESSION['annee']));
				$dateFinSemaineFr = date("Y-n-j H:i:s", mktime(23,59,0,$_SESSION['mois'],$_SESSION['jour']-$jour+7,$_SESSION['annee']));

				$abonnement = Abonnement::get_by_user($_SESSION['idUser']);
				$mes_agendas = Agenda::get_by_user_login($_SESSION['user']);
				$all_agendas = array();
				$abonnements=array();
				for($i=0; $i<count($mes_agendas); $i++) {
					$all_agendas[] = Agenda::get_by_id($mes_agendas[$i]->idAgenda());
				}
				for($i=0; $i<count($abonnement); $i++) {
					$abonnements[] = Agenda::get_by_id($abonnement[$i]->idAgenda());
					$all_agendas[] = Agenda::get_by_id($abonnement[$i]->idAgenda());
				}

					if(!empty($all_agendas))
					{
						$activites = Activite::get_by_idUtilisateurAgendaDate($all_agendas[$_SESSION['num']]->idUtilisateur(),$all_agendas[$_SESSION['num']]->idAgenda(),$dateDebSemaineFr,$dateFinSemaineFr);
						for($m=0;$m<count($activites);$m++)
						{
							for($l=0;$l<24;$l++)
							{
								for($k=0;$k<7;$k++)
								{
									$date_deb_inter = date_create_from_format('Y-n-j H:i:s',$activites[$m]->dateDeb());
									$date_fin_inter = date_create_from_format('Y-n-j H:i:s',$activites[$m]->dateFin());
									$date_debsem_inter = date_create_from_format('Y-n-j H:i:s',$dateDebSemaineFr);

									if($date_deb_inter->format('G') <= $l && $date_fin_inter->format('G') > $l && $date_deb_inter->format('j') == $date_debsem_inter->format('j')+$k) {
										$heure_jour[$l][$k] = $activites[$m];
									}
								}
							}
						}
					}

					include 'views/calendar.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "Vous n'êtes pas connecté";
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				if(isset($_SESSION['user'])) {

				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "Vous n'êtes pas connecté";
					include 'views/connexion.php';
				}
				break;
		}
	}

	public function actualise_date_moins() {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				//si l'utilisateur est connecté on affiche la page de création d'une note
				if(isset($_SESSION['user'])) {
					$jour = date("w");

					$datePrecise_ts = date("U", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour'],$_SESSION['annee']));

					$nb_jours = date("t", $datePrecise_ts);
					if($_SESSION['jour']-7 < 0) {
						if($_SESSION['mois'] <= 1) {
							$_SESSION['mois'] = 12;
							$_SESSION['annee'] = $_SESSION['annee'] - 1;
						} else {
							$_SESSION['mois'] = $_SESSION['mois'] - 1;
						}
						$_SESSION['jour'] = ($nb_jours+$_SESSION['jour']) - 8;
					} else {
						$_SESSION['jour'] = $_SESSION['jour'] - 7;
					}

					$dateDebSemaineFr = date("Y-n-j H:i:s", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour']-$jour+1,$_SESSION['annee']));
				$datePrecise = date("Y-n-j H:i:s", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour'],$_SESSION['annee']));
				$dateFinSemaineFr = date("Y-n-j H:i:s", mktime(23,59,0,$_SESSION['mois'],$_SESSION['jour']-$jour+7,$_SESSION['annee']));

				$abonnement = Abonnement::get_by_user($_SESSION['idUser']);
				$mes_agendas = Agenda::get_by_user_login($_SESSION['user']);
				$all_agendas = array();
				$abonnements=array();
				for($i=0; $i<count($mes_agendas); $i++) {
					$all_agendas[] = Agenda::get_by_id($mes_agendas[$i]->idAgenda());
				}
				for($i=0; $i<count($abonnement); $i++) {
					$abonnements[] = Agenda::get_by_id($abonnement[$i]->idAgenda());
					$all_agendas[] = Agenda::get_by_id($abonnement[$i]->idAgenda());
				}

					if(!empty($all_agendas))
					{
						$activites = Activite::get_by_idUtilisateurAgendaDate($all_agendas[$_SESSION['num']]->idUtilisateur(),$all_agendas[$_SESSION['num']]->idAgenda(),$dateDebSemaineFr,$dateFinSemaineFr);
						for($m=0;$m<count($activites);$m++)
						{
							for($l=0;$l<24;$l++)
							{
								for($k=0;$k<7;$k++)
								{
									$date_deb_inter = date_create_from_format('Y-n-j H:i:s',$activites[$m]->dateDeb());
									$date_fin_inter = date_create_from_format('Y-n-j H:i:s',$activites[$m]->dateFin());
									$date_debsem_inter = date_create_from_format('Y-n-j H:i:s',$dateDebSemaineFr);

									if($date_deb_inter->format('G') <= $l && $date_fin_inter->format('G') > $l && $date_deb_inter->format('j') == $date_debsem_inter->format('j')+$k) {
										$heure_jour[$l][$k] = $activites[$m];
									}
								}
							}
						}
					}

					include 'views/calendar.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "Vous n'êtes pas connecté";
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				if(isset($_SESSION['user'])) {

				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "Vous n'êtes pas connecté";
					include 'views/connexion.php';
				}
				break;
		}
	}

	public function actualise_date_maintenant() {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				//si l'utilisateur est connecté on affiche la page de création d'une note
				if(isset($_SESSION['user'])) {


					$jour = date("w");

					$_SESSION['mois'] = date("n");
					$_SESSION['jour'] = date("j");
					$_SESSION['annee'] = date("y");

					$dateDebSemaineFr = date("Y-n-j H:i:s", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour']-$jour+1,$_SESSION['annee']));
				$datePrecise = date("Y-n-j H:i:s", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour'],$_SESSION['annee']));
				$dateFinSemaineFr = date("Y-n-j H:i:s", mktime(23,59,0,$_SESSION['mois'],$_SESSION['jour']-$jour+7,$_SESSION['annee']));

				$abonnement = Abonnement::get_by_user($_SESSION['idUser']);
				$mes_agendas = Agenda::get_by_user_login($_SESSION['user']);
				$all_agendas = array();
				$abonnements=array();
				for($i=0; $i<count($mes_agendas); $i++) {
					$all_agendas[] = Agenda::get_by_id($mes_agendas[$i]->idAgenda());
				}
				for($i=0; $i<count($abonnement); $i++) {
					$abonnements[] = Agenda::get_by_id($abonnement[$i]->idAgenda());
					$all_agendas[] = Agenda::get_by_id($abonnement[$i]->idAgenda());
				}

					if(!empty($all_agendas))
					{
						$activites = Activite::get_by_idUtilisateurAgendaDate($all_agendas[$_SESSION['num']]->idUtilisateur(),$all_agendas[$_SESSION['num']]->idAgenda(),$dateDebSemaineFr,$dateFinSemaineFr);
						for($m=0;$m<count($activites);$m++)
						{
							for($l=0;$l<24;$l++)
							{
								for($k=0;$k<7;$k++)
								{
									$date_deb_inter = date_create_from_format('Y-n-j H:i:s',$activites[$m]->dateDeb());
									$date_fin_inter = date_create_from_format('Y-n-j H:i:s',$activites[$m]->dateFin());
									$date_debsem_inter = date_create_from_format('Y-n-j H:i:s',$dateDebSemaineFr);

									if($date_deb_inter->format('G') <= $l && $date_fin_inter->format('G') > $l && $date_deb_inter->format('j') == $date_debsem_inter->format('j')+$k) {
										$heure_jour[$l][$k] = $activites[$m];
									}
								}
							}
						}
					}
					include 'views/calendar.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "Vous n'êtes pas connecté";
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				if(isset($_SESSION['user'])) {

				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "Vous n'êtes pas connecté";
					include 'views/connexion.php';
				}
				break;
		}
	}

	public function supprimer($id) {

    if(isset($_SESSION['user'])) {
			$_SESSION['message']['type'] = 'success';
      $_SESSION['message']['text'] = "Agenda supprimé";
			$u=Agenda::get_by_id($id);
      $u->supprimer();
      include 'views/home.php';
    }
    else {
      $_SESSION['message']['type'] = 'error';
      $_SESSION['message']['text'] = "You aren't connected";
      include 'views/connexion.php';
    }
  }
}
