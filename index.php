<?php

require_once 'config.php';
require_once 'global/utils.php';
require_once 'models/base.php';

$db = new PDO(BDD_DSN, BDD_USER, BDD_PW);
Model_Base::set_db($db);

session_set_cookie_params(6000, '/', '', false, true);
session_name('SESSION_ACALENDAR');
session_start();
date_default_timezone_set('Europe/Paris');

define('BASEURL', dirname($_SERVER['SCRIPT_NAME']));

ob_start();

if(isset($_SERVER['PATH_INFO'])) {
	$args = explode('/', $_SERVER['PATH_INFO']);
	$found = false;

	if(count($args) >= 3) {
		$controller = $args[1];
		$method = $args[2];
		$params = array();
		for ($i=3; $i < count($args); $i++) {
			$params[] = $args[$i];
		}

		$controller_file = dirname(__FILE__).'/controllers/'.$controller.'.php';
		if (is_file($controller_file)) {
			require_once $controller_file;
			$controller_name = 'Controller_'.ucfirst($controller);
			if (class_exists($controller_name)) {
				$c = new $controller_name;
				if (method_exists($c, $method)) {
					$found = true;
					call_user_func_array(array($c, $method), $params);
				}
			}
		}
	}

	if (!$found) {
		http_response_code(404);
		$_SESSION['message']['type'] = 'error';
		$_SESSION['message']['text'] = 'Erreur 404 : Page non trouvée';
		include 'views/home.php';

	}
} else {
	include 'views/home.php';
}

if(!isset($_SESSION['mois'])) {
	$_SESSION['mois'] = date("n");
	$_SESSION['jour'] = date("j");
	$_SESSION['annee'] = date("y");
	$_SESSION['similaire'] = 0;
	$_SESSION['num'] = 0;
	$_SESSION['admin'] = 0;
}

$content = ob_get_clean();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="<?=BASEURL?>/css/normalize.css" type="text/css">
	<link rel="stylesheet" href="<?=BASEURL?>/css/style.css" type="text/css">
	<title> aCalendar </title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script> var baseurl = '<?=BASEURL?>'; </script>
	
</head>
<body>

<?php
include 'views/header.php';
include 'views/menu.php';

if (isset($_SESSION['message'])) {
	$m = $_SESSION['message'];
	echo('<div class="message '.$m['type'].'">'.$m['text'].'</div>');
	unset($_SESSION['message']);
}
?>

<main>
<?php echo $content; ?>
</main>

<?php
include 'views/footer.php';
?>

</body>
</html>
