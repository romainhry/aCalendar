<?php

require_once 'base.php';

class Pause extends Model_Base
{
	private $_idPause;

	private $_idActivite;

	private $_dateDeb;

	private $_dateFin;

	private $_periodicite;

	private $_occurences;

	public function __construct($idPause, $idActivite, $dateDeb, $dateFin, $periodicite, $occurences) {
		$this->set_idPause($idPause);
		$this->set_idActivite($idActivite);
		$this->set_dateDeb($dateDeb);
		$this->set_dateFin($dateFin);
		$this->set_periodicite($periodicite);
		$this->set_occurences($occurences);
	}

	//get

	public function idPause() {
		return $this->_idPause;
	}

	public function idActivite() {
		return $this->_idActivite;
	}

	public function dateDeb() {
		return $this->_dateDeb;
	}

	public function dateFin() {
		return $this->_dateFin;
	}

	public function periodicite() {
		return $this->_periodicite;
	}

	public function occurences() {
		return $this->_occurences;
	}

	//set

	public function set_idPause($v) {
		$this->_idPause = (int) $v;
	}

	public function set_idActivite($v) {
		$this->_idActivite = (int) $v;
	}

	public function set_dateDeb($v) {
		$this->_dateDeb = strval($v);
	}

	public function set_dateFin($v) {
		$this->_dateFin = strval($v);
	}

	public function set_periodicite($v) {
		$this->_periodicite = (int)($v);
	}

	public function set_occurences($v) {
		$this->_occurences = (int)($v);
	}

	public function add() {
		if(!is_null($this->_idPause)) {
			$q = self::$_db->prepare('INSERT INTO PAUSE (idActivite, dateDeb, dateFin, periodicite, occurences) VALUES (:id, :dateDeb, :dateFin, :periodicite, :occurences)');
			$q->bindValue(':id', $this->_idActivite, PDO::PARAM_INT);
			$q->bindValue(':dateDeb', $this->_dateDeb, PDO::PARAM_STR);
			$q->bindValue(':dateFin', $this->_dateFin, PDO::PARAM_STR);
			$q->bindValue(':periodicite', $this->_periodicite, PDO::PARAM_STR);
			$q->bindValue(':occurences', $this->_occurences, PDO::PARAM_INT);
			$q->execute();
		}
	}

	public function save()
	{
		if(!is_null($this->_idPause)) {
			$q = self::$_db->prepare('UPDATE PAUSE SET dateDeb=:dateDeb, dateFin=:dateFin, periodicite=:periodicite, occurences=:occurences WHERE idPause = :id');
			$q->bindValue(':id', $this->_idPause, PDO::PARAM_INT);
			$q->bindValue(':dateDeb', $this->_dateDeb, PDO::PARAM_STR);
			$q->bindValue(':dateFin', $this->_dateFin, PDO::PARAM_STR);
			$q->bindValue(':periodicite', $this->_periodicite, PDO::PARAM_STR);
			$q->bindValue(':occurences', $this->_occurences, PDO::PARAM_STR);
			$q->execute();
		}
	}

	public function delete()
	{
		if(!is_null($this->_idPause)) {
			$q = self::$_db->prepare('DELETE FROM PAUSE WHERE idPause = :id');
			$q->bindValue(':id', $this->_idPause);
			$q->execute();
			$this->_idPause = null;
		}
	}

	public static function get_by_activite($idActivite) {
		$s = self::$_db->prepare('SELECT * FROM PAUSE where idActivite = :id');
		$s->bindValue(':id', $idActivite, PDO::PARAM_INT);
		$s->execute();
		$pauses = array();
		while ($data = $s->fetch(PDO::FETCH_ASSOC)) {
			$pauses[] = new Pause($data['idPause'], $data['idActivite'], $data['dateDeb'], $data['dateFin'], $data['periodicite'], $data['occurences']);
		}
		return $pauses;
	}

	public static function get_by_id($idPause) {
		$s = self::$_db->prepare('SELECT * FROM PAUSE where idPause=:id');
		$s->bindValue(':id', $idPause, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
		if ($data) {
			return new Pause($data['idPause'], $data['idActivite'], $data['dateDeb'], $data['dateFin'], $data['periodicite'], $data['occurences']);
		} else {
			return null;
		}
	}
}
