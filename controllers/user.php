<?php

require_once 'models/utilisateur.php';

class Controller_User
{
	/**
	* \brief     Affiche la page de connexion et gère la connexion d'un utilisateur.
	*/
	public function signin() {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				if (isset($_SESSION['user'])) {
					$_SESSION['user'] = $u->pseudo();
					if(Utilisateur::is_admin($_SESSION['user'])) {
						$_SESSION['admin'] = 1;
					}
					show_message('message_success',"You're already connected as ".$_SESSION['user']);
					include 'views/home.php';
				}
				else {
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				if (isset($_POST['login']) && isset($_POST['pw'])) {

					$u = Utilisateur::get_by_login(htmlspecialchars($_POST['login']));
					if (!is_null($u)) {
						if ($u->mdp() == sha1($_POST['pw']))
						{
							$_SESSION['user'] = $u->pseudo();
							$_SESSION['idUser'] = $u->idUtilisateur();
							if(Utilisateur::is_admin($_SESSION['user'])) {
								$_SESSION['admin'] = 1;
							}
							show_message('message_success',"Vous êtes connecté");
							include 'views/home.php';
						}
						else {
							show_message('message_error',"Echec de connexion : login ou mot de passe incorrect");
							include 'views/connexion.php';
						}
					}
					else {
						show_message('message_error',"Echec de connexion : login ou mot de passe incorrect");
						include 'views/connexion.php';
					}
				}
				else {
						show_message('message_error',"Données incompletes!");
						include 'views/connexion.php';
				}
				break;
		}
	}

	/**
	* \brief     Affiche la page d'inscription et gère la création d'un nouvel utilisateur d'après les données réceptionnées.
	*/
	public function signup() {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				if (isset($_SESSION['user'])) {
					show_message('message_success',"Déjà connecté en tant que ".$_SESSION['user']);
					include 'views/home.php';
				}
				else {
					include 'views/inscription.php';
				}
				break;

			case 'POST' :
				if (isset($_POST['login']) && isset($_POST['pw']) && isset($_POST['pwConfirm'])) {
					$exist = Utilisateur::exist(htmlspecialchars($_POST['login']));
					if (!$exist) {
						if($_POST['pw'] == $_POST['pwConfirm']) {
							//Fonction sha1 permet crypté le mot de passe
							$u = new Utilisateur(1, NULL, NULL, NULL, htmlspecialchars($_POST['login']), sha1($_POST['pw']), NULL, NULL,0);
							$u->add();
							show_message('message_success',"Inscription de ".$_POST['login'].' !');
							include 'views/home.php';
						}
						else {
							show_message('message_error',"Pas le même mot de passe");
							include 'views/inscription.php';
						}
					}
					else {
						show_message('message_error',"Entrer d'autres informations");
						include 'views/inscription.php';
					}
				}
				else {
						show_message('message_error',"Données incomplètes");
						include 'views/inscription.php';
				}
				break;
		}
	}

	/**
	* \brief     Gère la deconnexion d'un utilisateur et le redirige sur la page d'acceuil.
	*/
	public function signout() {
		unset($_SESSION['user']);
		header('Location: '.BASEURL.'/index.php');
	}

	public function change_user() {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET' :
				if (isset($_SESSION['user'])) {
					$u = Utilisateur::get_by_login($_SESSION['user']);
					include 'views/profil.php';
				} else {
					$_SESSION['message']['type'] = 'error';
					$_SESSION['message']['text'] = "You aren't connected";
					include 'views/connexion.php';
				}
				break;

			case 'POST' :
				$u = Utilisateur::get_by_login($_SESSION['user']);
				if (!is_null($u)) {
					if($u->mdp() == sha1($_POST['mdp'])) {
						if(isset($_POST['nom'])) {
							$u->set_nom(htmlspecialchars($_POST['nom']));
						}
						else {
							$u->set_nom(htmlspecialchars(" "));
						}
						if(isset($_POST['prenom'])) {
							$u->set_prenom(htmlspecialchars($_POST['prenom']));
						}
						else {
							$u->set_prenom(htmlspecialchars(" "));
						}
						if(isset($_POST['adresse'])) {
							$u->set_adresse(htmlspecialchars($_POST['adresse']));
						}
						else {
							$u->set_adresse(htmlspecialchars(" "));
						}
						if(isset($_POST['email'])) {
							$u->set_email(htmlspecialchars($_POST['email']));
						}
						else {
							$u->set_email(htmlspecialchars(" "));
						}
						$u->save();
						show_message('message_success',"Votre compte à été modifié !");
						include 'views/home.php';
					}
					else {
						$_SESSION['message']['type'] = 'error';
						$_SESSION['message']['text'] = 'Wrong password';
						include 'views/profil.php';
					}
				}

				break;
		}
	}

	public function supprimer($id) {

    if(isset($_SESSION['user'])) {
			$_SESSION['message']['type'] = 'success';
      $_SESSION['message']['text'] = "Utilisateur supprimé";
			$u=Utilisateur::get_by_id($id);
      $u->supprimer();
      include 'views/home.php';
    }
    else {
      $_SESSION['message']['type'] = 'error';
      $_SESSION['message']['text'] = "You aren't connected";
      include 'views/connexion.php';
    }
  }

	public function admin($id) {

	    if(isset($_SESSION['user'])) {
				$u=Utilisateur::get_by_id($id);
				$_SESSION['message']['type'] = 'success';
	      $_SESSION['message']['text'] = "Droit donné à ".$u->pseudo();

	      $u->be_admin();
	      $_SESSION['admin'] = 1;
	      include 'views/home.php';
	    }
	    else {
	      $_SESSION['message']['type'] = 'error';
	      $_SESSION['message']['text'] = "You aren't connected";
	      include 'views/connexion.php';
	    }
  }
}
