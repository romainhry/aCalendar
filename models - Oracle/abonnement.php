<?php

require_once 'base.php';

class Abonnement extends Model_Base
{
	private $_idUtilisateur;

	private $_idAgenda;

	private $_priorite;

	public function __construct($idUtilisateur, $idAgenda, $priorite) {
		$this->set_idUtilisateur($idUtilisateur);
		$this->set_idAgenda($idAgenda);
		$this->set_aime($priorite);
	}

	//get

	public function idUtilisateur() {
		return $this->_idUtilisateur;
	}

	public function idAgenda() {
		return $this->_idAgenda;
	}

	public function priorite() {
		return $this->_priorite;
	}

	//set

	public function set_idUtilisateur($v) {
		$this->_idUtilisateur = (int) $v;
	}

	public function set_idAgenda($v) {
		$this->_idAgenda = (int) $v;
	}

	public function set_priorite($v) {
		$this->_priorite = strval($v);
	}

	public function add() {
		$q = self::$_db->prepare('INSERT INTO ABONNEMENT (idUtilisateur, idAgenda, priorite) VALUES (:idUtilisateur, :idAgenda, :priorite)');
		$q->bindValue(':idUtilisateur', $this->_idUtilisateur, PDO::PARAM_INT);
		$q->bindValue(':idAgenda', $this->_idAgenda, PDO::PARAM_INT);
		$q->bindValue(':priorite', $this->_priorite, PDO::PARAM_STR);
		$q->execute();
	}

	public function delete_by_user()
	{
		if(!is_null($this->_idUtilisateur)) {
			$q = self::$_db->prepare('DELETE FROM ABONNEMENT WHERE idUtilisateur = :id');
			$q->bindValue(':id', $this->_idUtilisateur);
			$q->execute();
			$this->_idUtilisateur = null;
		}
	}

	public function delete_by_agenda()
	{
		if(!is_null($this->_idAgenda)) {
			$q = self::$_db->prepare('DELETE FROM ABONNEMENT WHERE idAgenda = :id');
			$q->bindValue(':id', $this->_idAgenda);
			$q->execute();
			$this->_idAgenda = null;
		}
	}

	public static function get_by_agenda($idAgenda) {
		$s = self::$_db->prepare('SELECT * FROM ABONNEMENT where idAgenda = :id');
		$s->bindValue(':id', $idAgenda, PDO::PARAM_INT);
		$s->execute();
		$abonnements = array();
		while ($data = $s->fetch(PDO::FETCH_ASSOC)) {
			$abonnements[] = new Abonnement($data['IDUTILISATEUR'], $data['IDAGENDA'], $data['PRIORITE']);
		}
		return $abonnements;
	}

	public static function exist($idUtilisateur, $idAgenda) {
		$s = self::$_db->prepare('SELECT * FROM ABONNEMENT WHERE idUtilisateur=:idUtilisateur AND idAgenda = :idAgenda');
		$s->bindValue(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
		$s->bindValue(':idAgenda', $idAgenda, PDO::PARAM_INT);
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
