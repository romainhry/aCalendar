<?php
require_once "./cas/phpCAS-master/CAS.php";
require_once "config.example.php";


phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);
phpCAS::setNoCasServerValidation();

if (isset($_REQUEST['logout'])) {
      $_SESSION['cas']="yes";
    	phpCAS::logoutWithRedirectService("https://osr-etudiant.unistra.fr/~r.henry/aCalendar/");
}

if (phpCAS::checkAuthentication())
{
	$_SESSION['user']= phpCas::getUser();
  $_SESSION['cas']="yes";
    	header('Location: ../index.php');
}

else
{
	phpCAS::forceAuthentication();
}

//echo phpCAS::logoutWithRedirectService('inscription.php');

	//
?>
<!--<!<?php
//if (isset($erreur)) echo '<br /><br />',$erreur;

//if(isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion'){

	//if((isset($_POST['login']) && isset($_POST['pass'])) && (!empty($_POST['login']) && !empty($_POST['pass'])))
	//{
		//$base = new PDO('mysql:host=localhost;dbname=test', 'root', '') ;
		//or die("Erreur".mysql_error($base));

   //mysqli_select_db ('test', $base);

		//$sql = 'select count(*) from membre where login="'. $base->quote($_POST['login']).'" and
		//pass_md5="'. $base->quote($_POST['pass']).'"';
		//$requete= $base->query($sql) or die('Erreur sql !'.$sql.mysql_error());
		//$data = $requete->fetch();

		//($requete);
		//$requete->closeCursor();

		//if($data[0] == 1)
		//{
			//session_start();
			//$_SESSION['login'] = $_POST['login'];
			//header('Location: membre.php');
			//exit();
		//}
		//elseif ($data[0] == 0) {
			//$erreur = 'Compte inconnu';

		//}
	//}
//}
?>-->
