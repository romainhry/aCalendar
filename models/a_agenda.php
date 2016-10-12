<?php

require_once 'base.php';

class A_agenda extends Model_Base
{
	private $_idAgenda;

	private $_idUtilisateur;

	private $_nom;

	private $_description;

	private $_dateCreation;

	private $_dateUpdate;

	private $_intersection;

	private $_prive;

	private $_partage;

	public function __construct($idAgenda, $idUtilisateur, $nom, $description, $dateCreation, $dateUpdate, $intersection, $prive, $partage) {
		$this->set_idAgenda($idAgenda);
		$this->set_idUtilisateur($idUtilisateur);
		$this->set_nom($nom);
		$this->set_description($description);
		$this->set_dateCreation($dateCreation);
		$this->set_dateUpdate($dateUpdate);
		$this->set_intersection($intersection);
		$this->set_prive($prive);
		$this->set_partage($partage);
	}

	//get

	public function idAgenda() {
		return $this->_idAgenda;
	}

	public function idUtilisateur() {
		return $this->_idUtilisateur;
	}

	public function nom() {
		return $this->_nom;
	}

	public function description() {
		return $this->_description;
	}

	public function dateCreation() {
		return $this->_dateCreation;
	}

	public function dateUpdate() {
		return $this->_dateUpdate;
	}

	public function intersection() {
		return $this->_intersection;
	}

	public function prive() {
		return $this->_prive;
	}

	public function partage() {
		return $this->_partage;
	}

	//set

	public function set_idAgenda($v) {
		$this->_idAgenda = (int) $v;
	}

	public function set_idUtilisateur($v) {
		$this->_idUtilisateur = (int) $v;
	}

	public function set_nom($v) {
		$this->_nom = strval($v);
	}

	public function set_description($v) {
		$this->_description = strval($v);
	}

	public function set_dateCreation($v) {
		$this->_dateCreation = strval($v);
	}

	public function set_dateUpdate($v) {
		$this->_dateUpdate = strval($v);
	}

	public function set_intersection($v) {
		$this->_intersection = (int)($v);
	}

	public function set_prive($v) {
		$this->_prive = (int)($v);
	}

	public function set_partage($v) {
		$this->_partage = (int)($v);
	}

	public function add() {
		if(!is_null($this->_idAgenda)) {
			$q = self::$_db->prepare('INSERT INTO A_AGENDA (idAgenda, idUtilisateur, nom, description, intersection, prive, partage) VALUES (seq_agenda.nextval, :id, :nom, :description, :intersection, :prive, :partage)');
			$q->bindValue(':id', $this->_idUtilisateur, PDO::PARAM_INT);
			$q->bindValue(':nom', $this->_nom, PDO::PARAM_STR);
			$q->bindValue(':description', $this->_description, PDO::PARAM_STR);
			$q->bindValue(':intersection', $this->_intersection, PDO::PARAM_STR);
			$q->bindValue(':prive', $this->_prive, PDO::PARAM_STR);
			$q->bindValue(':partage', $this->_partage, PDO::PARAM_STR);
			$q->execute();
		}
	}

	public function save()
	{
		if(!is_null($this->_idAgenda)) {
			$q = self::$_db->prepare('UPDATE A_AGENDA SET nom=:nom, description=:description, dateUpdate=:dateUpdate, intersection=:intersection, prive=:prive, partage=:partage WHERE idAgenda = :id');
			$q->bindValue(':id', $this->_idAgenda, PDO::PARAM_INT);
			$q->bindValue(':nom', $this->_nom, PDO::PARAM_STR);
			$q->bindValue(':description', $this->_description, PDO::PARAM_STR);
			$q->bindValue(':dateUpdate', $this->_dateUpdate, PDO::PARAM_STR);
			$q->bindValue(':intersection', $this->_intersection, PDO::PARAM_STR);
			$q->bindValue(':prive', $this->_prive, PDO::PARAM_STR);
			$q->bindValue(':partage', $this->_partage, PDO::PARAM_STR);
			$q->execute();
		}
	}

	public function delete()
	{
		if(!is_null($this->_idAgenda)) {
			$q = self::$_db->prepare('DELETE FROM A_AGENDA WHERE idAgenda = :id');
			$q->bindValue(':id', $this->_idAgenda);
			$q->execute();
			$this->_idAgenda = null;
		}
	}

	public function delete_by_user()
	{
		if(!is_null($this->_idUtilisateur)) {
			$q = self::$_db->prepare('DELETE FROM A_AGENDA WHERE idUtilisateur = :id');
			$q->bindValue(':id', $this->_idUtilisateur);
			$q->execute();
			$this->_idUtilisateur = null;
		}
	}

	public static function get_by_user($idUtilisateur) {
		$s = self::$_db->prepare('SELECT * FROM A_AGENDA where idUtilisateur = :id');
		$s->bindValue(':id', $idUtilisateur, PDO::PARAM_INT);
		$s->execute();
		$agendas = array();
		while ($data = $s->fetch(PDO::FETCH_ASSOC)) {
			$agendas[] = new Agenda($data['idAgenda'], $data['idUtilisateur'], $data['nom'], $data['description'], $data['dateCreation'], $data['dateUpdate'], $data['intersection'], $data['prive'], $data['partage']);
		}
		return $agendas;
	}

	public static function get_by_id($idAgenda) {
		$s = self::$_db->prepare('SELECT * FROM A_AGENDA where idAgenda= :id');
		$s->bindValue(':id', $idAgenda, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
		if ($data) {
			return new Agenda($data['idAgenda'], $data['idUtilisateur'], $data['nom'], $data['description'], $data['dateCreation'], $data['dateUpdate'], $data['intersection'], $data['prive'], $data['partage']);
		} else {
			return null;
		}
	}

	public static function exist($idAgenda) {
		$s = self::$_db->prepare('SELECT * FROM A_AGENDA where idAgenda = :id');
		$s->bindValue(':id', $idAgenda, PDO::PARAM_INT);
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
