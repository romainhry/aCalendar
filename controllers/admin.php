<?php

require_once 'models/agenda.php';
require_once 'models/activite.php';
require_once 'models/categorie.php';
require_once 'models/abonnement.php';
require_once 'models/utilisateur.php';
require_once 'models/commentaire.php';

class Controller_Admin
{

	public function show_modules() {
    if(isset($_SESSION['user'])) {
      if(Utilisateur::is_admin($_SESSION['user'])) {
        include 'views/administrationModules.php';
      } else {
        $_SESSION['message']['type'] = 'error';
        $_SESSION['message']['text'] = "Vous n'êtes pas administrateur";
        include 'views/home.php';
      }
    }
    else {
      $_SESSION['message']['type'] = 'error';
      $_SESSION['message']['text'] = "You aren't connected";
      include 'views/connexion.php';
    }
  }

  public function users() {

    if(isset($_SESSION['user'])) {
      if(Utilisateur::is_admin($_SESSION['user'])) {
        $u=Utilisateur::get_all();
        include 'views/adminUser.php';
      } else {
        $_SESSION['message']['type'] = 'error';
        $_SESSION['message']['text'] = "Vous n'êtes pas administrateur";
        include 'views/home.php';
      }
    }
    else {
      $_SESSION['message']['type'] = 'error';
      $_SESSION['message']['text'] = "You aren't connected";
      include 'views/connexion.php';
    }
  }

  public function agendas() {

    if(isset($_SESSION['user'])) {
      if(Utilisateur::is_admin($_SESSION['user'])) {
        $a=Agenda::get_all();
        $users = array();
        for($i=0; $i<count($a); $i++) {
          $users[] = Utilisateur::get_by_id($a[$i]->idUtilisateur());
        }
        include 'views/adminAgenda.php';
      } else {
        $_SESSION['message']['type'] = 'error';
        $_SESSION['message']['text'] = "Vous n'êtes pas administrateur";
        include 'views/home.php';
      }
    }
    else {
      $_SESSION['message']['type'] = 'error';
      $_SESSION['message']['text'] = "You aren't connected";
      include 'views/connexion.php';
    }
  }

  public function commentaires() {

    if(isset($_SESSION['user'])) {
      if(Utilisateur::is_admin($_SESSION['user'])) {
        $c=Commentaire::get_all();
        $users = array();
        for($i=0; $i<count($c); $i++) {
          $users[] = Utilisateur::get_by_id($c[$i]->idUtilisateur());
        }
        include 'views/adminComm.php';
      } else {
        $_SESSION['message']['type'] = 'error';
        $_SESSION['message']['text'] = "Vous n'êtes pas administrateur";
        include 'views/home.php';
      }
    }
    else {
      $_SESSION['message']['type'] = 'error';
      $_SESSION['message']['text'] = "You aren't connected";
      include 'views/connexion.php';
    }
  }

  





}
