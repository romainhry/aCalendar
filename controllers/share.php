<?php

require_once 'models/agenda.php';
require_once 'models/activite.php';
require_once 'models/categorie.php';
require_once 'models/abonnement.php';
require_once 'models/utilisateur.php';

class Controller_Share
{
	public function show_share() {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				//si l'utilisateur est connecté on affiche la page de création d'une note
				if(isset($_SESSION['user'])) {
					include 'views/share.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				if(isset($_SESSION['user'])) {
					include 'views/share.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;
		}
	}

	public function search() {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				//si l'utilisateur est connecté on affiche la page de création d'une note
				if(isset($_SESSION['user'])) {
					include 'views/share.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				if(isset($_SESSION['user'])) {
					$u = Utilisateur::get_by_login($_POST['recherche']);
					$moi = Utilisateur::get_by_login($_SESSION['user']);
					if(Utilisateur::exist($_POST['recherche']) && $_SESSION['user'] != $_POST['recherche']) {
						$agendas = Agenda::get_by_user_public($u->idUtilisateur());
					}
					if(count($agendas)==0)
					{
						$_SESSION['message']['type'] = 'error';
						$_SESSION['message']['text'] = "Il n'y a pas d'agenda";
					}
					include 'views/share.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;
		}
	}

	public function search_by_name() {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				//si l'utilisateur est connecté on affiche la page de création d'une note
				if(isset($_SESSION['user'])) {
					include 'views/share.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				if(isset($_SESSION['user'])) {
					$moi = Utilisateur::get_by_login($_SESSION['user']);
					$agendas = Agenda::get_by_name_public($_POST['nom']);

					if(count($agendas)==0)
					{
						$_SESSION['message']['type'] = 'error';
						$_SESSION['message']['text'] = "Il n'y a pas d'agenda";
					}
					include 'views/share.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;
		}
	}

	public function search_by_desc() {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				//si l'utilisateur est connecté on affiche la page de création d'une note
				if(isset($_SESSION['user'])) {
					include 'views/share.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				if(isset($_SESSION['user'])) {
					$agendas = Agenda::get_by_desc_public($_POST['desc']);
					$moi = Utilisateur::get_by_login($_SESSION['user']);

					if(count($agendas)==0)
					{
						$_SESSION['message']['type'] = 'error';
						$_SESSION['message']['text'] = "Il n'y a pas d'agenda";
					}
					include 'views/share.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;
		}
	}

	public function abonnement($idAgenda, $nomAgenda) {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				//si l'utilisateur est connecté on affiche la page de création d'une note
				if(isset($_SESSION['user'])) {
					include 'views/share.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				if(isset($_SESSION['user'])) {
					$user = Utilisateur::get_by_login($_SESSION['user']);
					$abo = new Abonnement($user->idUtilisateur(), $idAgenda, 5);
					$abo->add();
					$_SESSION['message']['type'] = 'success';
					$_SESSION['message']['text'] = "Vous êtes maintenant abonné au calendrier ".$nomAgenda;
					include 'views/share.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;
		}
	}

	public function desabonnement($idAgenda, $nomAgenda) {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				//si l'utilisateur est connecté on affiche la page de création d'une note
				if(isset($_SESSION['user'])) {
					include 'views/share.php';
				}
				else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				if(isset($_SESSION['user'])) {
					$user = Utilisateur::get_by_login($_SESSION['user']);
					if(Abonnement::exist($user->idUtilisateur(), $idAgenda)) {
						$abo = Abonnement::delete($user->idUtilisateur(), $idAgenda);

						$_SESSION['message']['type'] = 'success';
						$_SESSION['message']['text'] = "Vous êtes maintenant désabonné du calendrier ".$nomAgenda;
					} else {
						$_SESSION['message']['type'] = 'error';
						$_SESSION['message']['text'] = "Vous n'êtes pas abonné à ce calendrier.";
					}
					include 'views/share.php';
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
