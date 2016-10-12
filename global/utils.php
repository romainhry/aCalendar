<?php

/**
* \brief     Vérifie si l'utilisateur est connecté
* \return    Un booléen, vrai si connecté
*/
if(!function_exists('user_connected')) {
	function user_connected() {
		return isset($_SESSION['user']);
	}
}

/**
* \brief     Définie une variable de session contenant un message devant être affiché.
* \param   type   type de message (error ou success ou  warning)
* \param   text   texte du message
*/
if(!function_exists('show_message')) {
	function show_message($type,$text) {
		$_SESSION['message']['type'] = strval($type);
		$_SESSION['message']['text'] = strval($text);
	}
}