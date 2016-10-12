<?php

require_once 'base.php';

class Commentaire extends Model_Base
{
	private $_idComm;

	private $_idParent;

	private $_idUtilisateur;

	private $_idActivite;

	private $_dateComm;

	private $_commentaire;

	public function __construct($idComm, $idParent, $idUtilisateur, $idActivite, $dateComm, $commentaire) {
		$this->set_idComm($idComm);
		$this->set_idParent($idParent);
		$this->set_idUtilisateur($idUtilisateur);
		$this->set_idActivite($idActivite);
		$this->set_dateComm($dateComm);
		$this->set_commentaire($commentaire);
	}

	//get

	public function idComm() {
		return $this->_idComm;
	}

	public function idParent() {
		return $this->_idParent;
	}

	public function idUtilisateur() {
		return $this->_idUtilisateur;
	}

	public function idActivite() {
		return $this->_idActivite;
	}

	public function dateComm() {
		return $this->_dateComm;
	}

	public function commentaire() {
		return $this->_commentaire;
	}

	//set

	public function set_idComm($v) {
		$this->_idComm = (int) $v;
	}

	public function set_idParent($v) {
		$this->_idParent = (int) $v;
	}

	public function set_idUtilisateur($v) {
		$this->_idUtilisateur = (int) $v;
	}

	public function set_idActivite($v) {
		$this->_idActivite = (int) $v;
	}

	public function set_dateComm($v) {
		$this->_dateComm = strval($v);
	}

	public function set_commentaire($v) {
		$this->_commentaire = strval($v);
	}

	public function add() {
		if(!is_null($this->_idComm)) {
			$q = self::$_db->prepare('INSERT INTO COMMENTAIRE (idComm, idParent, idUtilisateur, idActivite, commentaire) VALUES (seq_commentaire.nextval, :idParent, :idUtilisateur, :idActivite, :commentaire)');
			$q->bindValue(':idParent', $this->_idParent, PDO::PARAM_INT);
			$q->bindValue(':idUtilisateur', $this->_idUtilisateur, PDO::PARAM_INT);
			$q->bindValue(':idActivite', $this->_idActivite, PDO::PARAM_INT);
			$q->bindValue(':commentaire', $this->_commentaire, PDO::PARAM_STR);
			$q->execute();
		}
	}

	public function delete()
	{
		if(!is_null($this->_idComm)) {
			$q = self::$_db->prepare('DELETE FROM COMMENTAIRE WHERE idComm = :id');
			$q->bindValue(':id', $this->_idComm);
			$q->execute();
			$this->_idComm = null;
		}
	}

	public function delete_by_activite()
	{
		if(!is_null($this->_idActivite)) {
			$q = self::$_db->prepare('DELETE FROM COMMENTAIRE WHERE idActivite = :id');
			$q->bindValue(':id', $this->_idActivite);
			$q->execute();
			$this->_idActivite = null;
		}
	}

	public static function get_by_activite($idActivite) {
		$s = self::$_db->prepare('SELECT * FROM COMMENTAIRE where idActivite = :id');
		$s->bindValue(':id', $idActivite, PDO::PARAM_INT);
		$s->execute();
		$activites = array();
		while ($data = $s->fetch(PDO::FETCH_ASSOC)) {
			$activites[] = new Commentaire($data['IDCOMM'], $data['IDPARENT'], $data['IDUTILISATEUR'], $data['IDACTIVITE'], $data['DATECOMM'], $data['COMMENTAIRE']);
		}
		return $activites;
	}

	public static function get_by_id($idComm) {
		$s = self::$_db->prepare('SELECT * FROM COMMENTAIRE where idComm= :id');
		$s->bindValue(':id', $idComm, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
		if ($data) {
			return new Commentaire($data['IDCOMM'], $data['IDPARENT'], $data['IDUTILISATEUR'], $data['IDACTIVITE'], $data['DATECOMM'], $data['COMMENTAIRE']);
		} else {
			return null;
		}
	}
}
