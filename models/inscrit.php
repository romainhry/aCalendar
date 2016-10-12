<?php

require_once 'base.php';

class Inscrit extends Model_Base
{
	private $_idUtilisateur;

	private $_idActivite;

	public function __construct($idUtilisateur, $idActivite) {
		$this->set_idUtilisateur($idUtilisateur);
		$this->set_idActivite($idActivite);
	}

	//get

	public function idUtilisateur() {
		return $this->_idUtilisateur;
	}

	public function idActivite() {
		return $this->_idActivite;
	}

	//set

	public function set_idUtilisateur($v) {
		$this->_idUtilisateur = (int) $v;
	}

	public function set_idActivite($v) {
		$this->_idActivite = (int) $v;
	}

	public function add() {
		$q = self::$_db->prepare('INSERT INTO INSCRIT (idUtilisateur, idActivite) VALUES (:idUtilisateur, :idActivite)');
		$q->bindValue(':idUtilisateur', $this->_idUtilisateur, PDO::PARAM_INT);
		$q->bindValue(':idActivite', $this->_idActivite, PDO::PARAM_INT);
		$q->execute();
	}

	public function delete_by_user()
	{
		if(!is_null($this->_idUtilisateur)) {
			$q = self::$_db->prepare('DELETE FROM INSCRIT WHERE idUtilisateur = :id');
			$q->bindValue(':id', $this->_idUtilisateur);
			$q->execute();
			$this->_idUtilisateur = null;
		}
	}

	public function delete_by_activite()
	{
		if(!is_null($this->_idActivite)) {
			$q = self::$_db->prepare('DELETE FROM INSCRIT WHERE idActivite = :id');
			$q->bindValue(':id', $this->_idActivite);
			$q->execute();
			$this->_idActivite = null;
		}
	}

	public static function get_by_activite($idActivite) {
		$s = self::$_db->prepare('SELECT * FROM INSCRIT where idActivite = :id');
		$s->bindValue(':id', $idActivite, PDO::PARAM_INT);
		$s->execute();
		$inscriptions = array();
		while ($data = $s->fetch(PDO::FETCH_ASSOC)) {
			$inscriptions[] = new Inscrit($data['idUtilisateur'], $data['idActivite']);
		}
		return $inscriptions;
	}

	public static function get_by_user($idUtilisateur) {
		$s = self::$_db->prepare('SELECT * FROM INSCRIT where idUtilisateur = :id');
		$s->bindValue(':id', $idUtilisateur, PDO::PARAM_INT);
		$s->execute();
		$inscriptions = array();
		while ($data = $s->fetch(PDO::FETCH_ASSOC)) {
			$inscriptions[] = new Inscrit($data['idUtilisateur'], $data['idActivite']);
		}
		return $inscriptions;
	}

	public static function exist($idUtilisateur, $idActivite) {
		$s = self::$_db->prepare('SELECT * FROM ABONNEMENT WHERE idUtilisateur=:idUtilisateur AND idActivite = :idActivite');
		$s->bindValue(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
		$s->bindValue(':idActivite', $idActivite, PDO::PARAM_INT);
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
