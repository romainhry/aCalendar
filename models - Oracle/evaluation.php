<?php

require_once 'base.php';

class Evaluation extends Model_Base
{
	private $_idUtilisateur;

	private $_idActivite;

	private $_note;

	private $_dateEval;

	public function __construct($idUtilisateur, $idActivite, $note, $dateEval) {

		$this->set_idUtilisateur($idUtilisateur);
		$this->set_idActivite($idActivite);
		$this->set_note($note);
		$this->set_dateEval($dateEval);
  }

	//get

	public function idUtilisateur() {
		return $this->_idUtilisateur;
	}

	public function idActivite() {
		return $this->_idActivite;
	}

	public function note() {
		return $this->_note;
	}

	public function dateEval() {
		return $this->_dateEval;
	}

	//set

	private function set_idUtilisateur($v) {
		$this->_idUtilisateur = (int) $v;
	}

	private function set_idActivite($v) {
		$this->_idActivite = (int) $v;
	}

	private function set_note($v) {
		$this->_note = (int)($v);
	}

	private function set_dateEval($v) {
		$this->_dateEval = (int)($v);
	}


	public function add() {
		if(!is_null($this->_id)) {
			$q = self::$_db->prepare('INSERT INTO EVALUATION(idUtilisateur,idActivite,
        note, dateEval) VALUES (:idUtilisateur,:idActivite,:note, :dateEval)');
      $q->bindValue(':idUtilisateur', $this->_idUtilisateur, PDO::PARAM_INT);
      $q->bindValue(':idActivite', $this->_idActivite, PDO::PARAM_INT);
      $q->bindValue(':note', $this->_note, PDO::PARAM_INT);
			$q->bindValue(':dateEval', $this->_dateEval, PDO::PARAM_STRING);
			$q->execute();
		}
	}

	public function delete()
	{
		if(!is_null($this->_id)) {
			$q = self::$_db->prepare('DELETE FROM EVALUATION WHERE idActivite = :id');
			$q->bindValue(':id', $this->_idActivite);
			$q->execute();
			$this->_id = null;
		}
	}

	public static function get_by_idActivite($idActivite) {
		$s = self::$_db->prepare('SELECT * FROM EVALUATION
      where idActivite = :id');
		$s->bindValue(':id', $idActivite, PDO::PARAM_INT);
		$s->execute();
    $evaluations = array();
		while($data = $s->fetch(PDO::FETCH_ASSOC)) {
			$evaluations[] = new Evaluation(
        $data['IDUTILISATEUR'], $data['IDACTIVITE'],
        $data['NOTE'], $data['DATEEVAL']
      );
		}
		return $evaluations;
	}

	public static function get_by_idUtilisateur($id) {
		$s = self::$_db->prepare('SELECT * FROM EVALUATION where idUtilisateur=:id')
    ;
		$s->bindValue(':id', $id, PDO::PARAM_INT);
		$s->execute();
    $evaluations = array();
		while($data = $s->fetch(PDO::FETCH_ASSOC)) {
			$evaluations[] = new Evaluation(
        $data['IDUTILISATEUR'], $data['IDACTIVITE'],
        $data['NOTE'], $data['DATEEVAL']
      );
		}
		return $evaluations;
	}

  public static function get_by_ids($idUtilisateur,$idActivite) {
		$s = self::$_db->prepare('SELECT * FROM EVALUATION
      where idActivite = :idActivite AND idUtilisateur =:idUtilisateur');
		$s->bindValue(':idActivite', $idActivite, PDO::PARAM_INT);
    $s->bindValue(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
    $s->execute();
    $evaluations = array();
		$data = $s->fetch(PDO::FETCH_ASSOC);
    if($data) {
      return new Evaluation(
        $data['IDUTILISATEUR'], $data['IDACTIVITE'],
        $data['NOTE'], $data['DATEEVAL']
      );
    }
		return null;
	}
}
