<?php

require_once 'models/agenda.php';
require_once 'models/activite.php';
require_once 'models/categorie.php';
require_once 'models/utilisateur.php';
require_once 'models/commentaire.php';
require_once 'models/aimecomm.php';
require_once 'models/pause.php';
require_once 'controllers/calendar.php';

class Controller_Activite
{

  public static function doTree($comm,$niveau,$activite)
  {
    $aime=Aimecomm::get_by_comm($comm->idComm());
    for($i=0;$i<count($aime);$i++)
    {
      $UtilAime[$i]=Utilisateur::get_by_id($aime[$i]->idUtilisateur());
    }

    $commentateur=Utilisateur::get_by_id($comm->idUtilisateur());
    include 'views/commentaire.php';
    $childs=Commentaire::get_childs($comm->idComm());
    $niveau ++;
    for($i=0;$i<count($childs);$i++)
    {
      self::doTree($childs[$i],$niveau,$activite);
    }

  }

	public static function show($id) {
        if(isset($_SESSION['user'])) {
          $user = Utilisateur::get_by_login($_SESSION['user']);
          $activite=Activite::get_by_id($id);
					include 'views/activite.php';
          $commentaires=Commentaire::get_by_activite($id);
          for($j=0;$j<count($commentaires);$j++){
            self::doTree($commentaires[$j],0,$activite);
          }
          include 'views/endActivite.php';
  			}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
  }

  public static function doCommentaire($idActivite,$idParent)
  {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				break;

			case 'POST' :
				if(isset($_SESSION['user'])) {
          $c= new Commentaire(1,$idParent,$_SESSION['idUser'],$idActivite,'',$_POST['contenu']);
          $c->add();
					self::show($idActivite);
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;
		}
  }

  public static function delete_comm($idComm)
  {
    switch ($_SERVER['REQUEST_METHOD']) {
      case 'GET' :
        if(isset($_SESSION['user'])) {
          $c = Commentaire::get_by_id($idComm);
          $c->delete();
        }
        else {
          $_SESSION['message']['type'] = 'error';
          $_SESSION['message']['text'] = "You aren't connected";
          include 'views/connexion.php';
        }
        break;

      case 'POST' :
        if(isset($_SESSION['user'])) {
          $c = Commentaire::get_by_id($idComm);
          $c->delete();
        }
        else {
          $_SESSION['message']['type'] = 'error';
          $_SESSION['message']['text'] = "You aren't connected";
          include 'views/connexion.php';
        }
        break;
    }
  }

  public static function pause($idActivite, $idUser)
  {
      if(isset($_SESSION['user'])) {
        include 'views/pause.php';
      }
      else {
        $_SESSION['message']['type'] = 'error';
        $_SESSION['message']['text'] = "You aren't connected";
        include 'views/connexion.php';
      }
  }

  public static function add_pause($idActivite, $idUser)
  {
    switch ($_SERVER['REQUEST_METHOD']) {
      case 'POST' :
        if(isset($_SESSION['user'])) {

          $date_debut = date_create_from_format('Y-n-j?H:i', $_POST['datedebpause']);
          $date_debut_ts = $date_debut->format('U');
          $heure_debut = $date_debut->format('H');
          $jour_debut = $date_debut->format('j');
          $mois_debut = $date_debut->format('n');
          $annee_debut = $date_debut->format('Y');
          $date_debut = $date_debut->format('Y-n-j H');

          $date_fin = date_create_from_format('Y-n-j?H:i', $_POST['datefinpause']);
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

          $pause = new Pause(1, $idActivite, $date_debut, $date_fin, $periodicite, $occurences);
          $act = Activite::get_by_id($idActivite);

          $activites = Activite::get_by_idUtilisateurAgendaDateSimilaire($idUser,$act->idAgenda(),$act->idSimilaire(),$date_debut,$date_fin);
          for($i=0; $i<count($activites); $i++) {
            $activites[$i]->delete();
          } 

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
                $activites = Activite::get_by_idUtilisateurAgendaDateSimilaire($idUser,$act->idAgenda(),$act->idSimilaire(),$date_debut,$date_fin);
                for($i=0; $i<count($activites); $i++) {
                  $activites[$i]->delete();
                } 
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
                $activites = Activite::get_by_idUtilisateurAgendaDateSimilaire($idUser,$act->idAgenda(),$act->idSimilaire(),$date_debut,$date_fin);
                for($i=0; $i<count($activites); $i++) {
                  $activites[$i]->delete();
                } 
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
                $activites = Activite::get_by_idUtilisateurAgendaDateSimilaire($idUser,$act->idAgenda(),$act->idSimilaire(),$date_debut,$date_fin);
                for($i=0; $i<count($activites); $i++) {
                  $activites[$i]->delete();
                } 
              }
            } else if ($periodicite == 'A') {
              while($occ > 1) {
                $annee_debut = $annee_debut + 1;
                $annee_fin = $annee_fin + 1;

                $date_debut = date('Y-n-j H', mktime($heure_debut,0,0,$mois_debut,$jour_debut,$annee_debut));
                $date_fin = date('Y-n-j H', mktime($heure_fin,0,0,$mois_fin,$jour_fin,$annee_fin));

                $occ = $occ - 1;
                $activites = Activite::get_by_idUtilisateurAgendaDateSimilaire($idUser,$act->idAgenda(),$act->idSimilaire(),$date_debut,$date_fin);
                for($i=0; $i<count($activites); $i++) {
                  $activites[$i]->delete();
                } 
              }
            }
          }

          include 'views/pause.php';
        }
        else {
          $_SESSION['message']['type'] = 'error';
          $_SESSION['message']['text'] = "You aren't connected";
          include 'views/connexion.php';
        }
        break;

      case 'GET' :
        if(isset($_SESSION['user'])) {
          include 'views/pause.php';
        }
        else {
          $_SESSION['message']['type'] = 'error';
          $_SESSION['message']['text'] = "You aren't connected";
          include 'views/connexion.php';
        }
        break;
    }
  }
}
