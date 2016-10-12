<?php

require_once 'base.php';

class Categorie extends Model_Base
{
	private $_idCategorie;

	private $_nom;

	private $_descriptif;

	public function __construct($idCategorie, $descriptif) {
		$this->set_idCategorie($idCategorie);
		$this->set_nom($nom);
		$this->set_descriptif($descriptif);
	}

	//get

	public function idCategorie() {
		return $this->_idCategorie;
	}

	public function nom() {
		return $this->_nom;
	}

	public function descriptif() {
		return $this->_descriptif;
	}

	//set

	public function set_idCategorie($v) {
		$this->_idCategorie = (int) $v;
	}

	public function set_nom($v) {
		$this->_nom = strval($v);
	}

	public function set_descriptif($v) {
		$this->_descriptif = strval($v);
	}

	public function add() {
		if(!is_null($this->_idCategorie)) {
			$q = self::$_db->prepare('INSERT INTO CATEGORIE (idCategorie, nom, descriptif) VALUES (seq_categorie.nextval, :nom, :descriptif)');
			$q->bindValue(':nom', $this->_nom, PDO::PARAM_STR);
			$q->bindValue(':descriptif', $this->_descriptif, PDO::PARAM_STR);
			$q->execute();
		}
	}

	public function save()
	{
		if(!is_null($this->_idCategorie)) {
			$q = self::$_db->prepare('UPDATE CATEGORIE SET descriptif=:descriptif WHERE idCategorie = :id');
			$q->bindValue(':id', $this->_idCategorie, PDO::PARAM_INT);
			$q->bindValue(':descriptif', $this->_descriptif, PDO::PARAM_STR);
			$q->execute();
		}
	}

	public function delete()
	{
		if(!is_null($this->_idCategorie)) {
			$q = self::$_db->prepare('DELETE FROM CATEGORIE WHERE idCategorie = :id');
			$q->bindValue(':id', $this->_idCategorie);
			$q->execute();
			$this->_idCategorie = null;
		}
	}

	public static function get_by_id($idCategorie) {
		$s = self::$_db->prepare('SELECT * FROM CATEGORIE where idCategorie = :id');
		$s->bindValue(':id', $idCategorie, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
		if ($data) {
			return new Categorie($data['IDCATEGORIE'], $data['NOM'], $data['DESCRIPTIF']);
		} else {
			return null;
		}
	}

	public static function get_all_name() {
		$s = self::$_db->prepare('SELECT * FROM CATEGORIE');
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
		$categories = array();
		while ($data = $s->fetch(PDO::FETCH_ASSOC)) {
			$categories[] = $data['NOM'];
		}
		return $categories;
	}

	public static function exist($idCategorie) {
		$s = self::$_db->prepare('SELECT * FROM CATEGORIE where idCategorie = :id');
		$s->bindValue(':id', $idCategorie, PDO::PARAM_INT);
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
