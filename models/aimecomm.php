<?php

require_once 'base.php';

class Aimecomm extends Model_Base
{
	private $_idUtilisateur;

	private $_idComm;

	private $_dateAime;

	private $_aime;

	public function __construct($idUtilisateur, $idComm, $dateAime, $aime) {
		$this->set_idUtilisateur($idUtilisateur);
		$this->set_idComm($idComm);
		$this->set_dateAime($dateAime);
		$this->set_aime($aime);
	}

	//get

	public function idUtilisateur() {
		return $this->_idUtilisateur;
	}

	public function idComm() {
		return $this->_idComm;
	}

	public function dateAime() {
		return $this->_dateAime;
	}

	public function aime() {
		return $this->_aime;
	}

	//set

	public function set_idUtilisateur($v) {
		$this->_idUtilisateur = (int) $v;
	}

	public function set_idComm($v) {
		$this->_idComm = (int) $v;
	}

	public function set_dateAime($v) {
		$this->_dateAime = strval($v);
	}

	public function set_aime($v) {
		$this->_aime = (int)($v);
	}

	public function add() {
		$q = self::$_db->prepare('INSERT INTO AIMECOMM (idUtilisateur, idComm, aime) VALUES (:idUtilisateur, :idComm, :aime)');
		$q->bindValue(':idUtilisateur', $this->_idUtilisateur, PDO::PARAM_INT);
		$q->bindValue(':idComm', $this->_idComm, PDO::PARAM_INT);
		$q->bindValue(':aime', $this->_aime, PDO::PARAM_STR);
		$q->execute();
	}

	public function delete_by_comm()
	{
		if(!is_null($this->_idComm)) {
			$q = self::$_db->prepare('DELETE FROM AIMECOMM WHERE idComm = :id');
			$q->bindValue(':id', $this->_idComm);
			$q->execute();
			$this->_idComm = null;
		}
	}

	public static function get_by_comm($idComm) {
		$s = self::$_db->prepare('SELECT * FROM AIMECOMM where idActivite = :id');
		$s->bindValue(':id', $idComm, PDO::PARAM_INT);
		$s->execute();
		$aimes = array();
		while ($data = $s->fetch(PDO::FETCH_ASSOC)) {
			$aimes[] = new Aimecomm($data['idUtilisateur'], $data['idComm'], $data['dateAime'], $data['aime']);
		}
		return $aimes;
	}

	public static function exist($idUtilisateur, $idComm) {
		$s = self::$_db->prepare('SELECT * FROM AIMECOMM WHERE idUtilisateur=:idUtilisateur AND idComm = :idComm');
		$s->bindValue(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
		$s->bindValue(':idComm', $idComm, PDO::PARAM_INT);
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
