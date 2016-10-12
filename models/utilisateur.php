<?php

require_once 'base.php';

class Utilisateur extends Model_Base
{
	private $_idUtilisateur;

	private $_nom;

	private $_prenom;

	private $_adresse;

	private $_pseudo;

	private $_mdp;

	private $_email;

	private $_dateInscription;

	private $_admin;

	public function __construct($idUtilisateur, $nom, $prenom, $adresse, $pseudo, $mdp, $email, $dateInscription,$admin) {
		$this->set_idUtilisateur($idUtilisateur);
		$this->set_nom($nom);
		$this->set_prenom($prenom);
		$this->set_adresse($adresse);
		$this->set_pseudo($pseudo);
		$this->set_mdp($mdp);
		$this->set_email($email);
		$this->set_dateInscription($dateInscription);
		$this->set_admin($admin);
	}

	//get

	public function idUtilisateur() {
		return $this->_idUtilisateur;
	}

	public function nom() {
		return $this->_nom;
	}

	public function prenom() {
		return $this->_prenom;
	}

	public function adresse() {
		return $this->_adresse;
	}

	public function pseudo() {
		return $this->_pseudo;
	}

	public function mdp() {
		return $this->_mdp;
	}

	public function email() {
		return $this->_email;
	}

	public function dateInscription() {
		return $this->_dateInscription;
	}

	public function admin() {
		return $this->_admin;
	}

	//set

	public function set_idUtilisateur($v) {
		$this->_idUtilisateur = (int) $v;
	}

	public function set_nom($v) {
		$this->_nom = strval($v);
	}

	public function set_prenom($v) {
		$this->_prenom = strval($v);
	}

	public function set_adresse($v) {
		$this->_adresse = strval($v);
	}

	public function set_pseudo($v) {
		$this->_pseudo = strval($v);
	}

	public function set_mdp($v) {
		$this->_mdp = strval($v);
	}

	public function set_email($v) {
		$this->_email = strval($v);
	}

	public function set_dateInscription($v) {
		$this->_dateInscription = strval($v);
	}
	public function set_admin($v) {
		$this->_admin = (int)($v);
	}

	public function add() {
		if(!is_null($this->_idUtilisateur)) {
			$q = self::$_db->prepare('INSERT INTO UTILISATEUR (nom, prenom, adresse, pseudo, mdp, email,admin) VALUES (NULL, NULL, NULL, :pseudo, :mdp, NULL,0)');
			$q->bindValue(':pseudo', $this->_pseudo, PDO::PARAM_STR);
			$q->bindValue(':mdp', $this->_mdp, PDO::PARAM_STR);
			$q->execute();
		}
	}

	public function save()
	{
		if(!is_null($this->_idUtilisateur)) {
			$q = self::$_db->prepare('UPDATE UTILISATEUR SET nom=:nom, prenom=:prenom, adresse=:adresse, pseudo=:pseudo, mdp=:mdp, email=:email, admin=:admin WHERE idUtilisateur = :id');
			$q->bindValue(':id', $this->_idUtilisateur, PDO::PARAM_INT);
			$q->bindValue(':nom', $this->_nom, PDO::PARAM_STR);
			$q->bindValue(':prenom', $this->_prenom, PDO::PARAM_STR);
			$q->bindValue(':adresse', $this->_adresse, PDO::PARAM_STR);
			$q->bindValue(':pseudo', $this->_pseudo, PDO::PARAM_STR);
			$q->bindValue(':mdp', $this->_mdp, PDO::PARAM_STR);
			$q->bindValue(':email', $this->_email, PDO::PARAM_STR);
			$q->bindValue(':admin', $this->_email, PDO::PARAM_INT);
			$q->execute();
		}
	}

	public static function get_by_login($pseudo) {
		$s = self::$_db->prepare('SELECT * FROM UTILISATEUR where pseudo = :pseudo');
		$s->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
		if ($data) {
			return new Utilisateur($data['idUtilisateur'], $data['nom'], $data['prenom'], $data['adresse'], $data['pseudo'], $data['mdp'], $data['email'], $data['dateInscription'],$data['admin']);
		} else {
			return null;
		}
	}

	public static function get_all() {
		$s = self::$_db->prepare('SELECT * FROM UTILISATEUR');
		$s->execute();
		$u = array();
		while ($data = $s->fetch(PDO::FETCH_ASSOC)) {
			$u[] = new Utilisateur($data['idUtilisateur'], $data['nom'], $data['prenom'], $data['adresse'], $data['pseudo'], $data['mdp'], $data['email'], $data['dateInscription'],$data['admin']);
		}
		return $u;

	}

	public static function get_by_id($idUtilisateur) {
		$s = self::$_db->prepare('SELECT * FROM UTILISATEUR where idUtilisateur = :id');
		$s->bindValue(':id', $idUtilisateur, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
		if ($data) {
			return new Utilisateur($data['idUtilisateur'], $data['nom'], $data['prenom'], $data['adresse'], $data['pseudo'], $data['mdp'], $data['email'], $data['dateInscription'],$data['admin']);
		} else {
			return null;
		}
	}

	public function supprimer() {
		$s = self::$_db->prepare('DELETE FROM UTILISATEUR where idUtilisateur = :id');
		$s->bindValue(':id', $this->_idUtilisateur, PDO::PARAM_INT);
		$s->execute();
	}

	public function be_admin() {
		$s = self::$_db->prepare('UPDATE UTILISATEUR SET admin=1 WHERE idUtilisateur= :id');
		$s->bindValue(':id', $this->_idUtilisateur, PDO::PARAM_INT);
		$s->execute();
	}

	public static function is_admin($pseudo) {
		$s = self::$_db->prepare('SELECT * FROM UTILISATEUR where pseudo = :pseudo AND admin = 1');
		$s->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
		if($data) {
			return true;
		}
		else {
			return false;
		}
	}

	public static function exist($pseudo) {
		$s = self::$_db->prepare('SELECT * FROM UTILISATEUR where pseudo = :pseudo');
		$s->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
		if($data) {
			return true;
		}
		else {
			return false;
		}
	}
}
