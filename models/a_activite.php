<?php

require_once 'base.php';

class A_activite extends Model_Base
{
	private $_idActivite;

	private $_idAgenda;

	private $_idCategorie;

	private $_idSimilaire;

	private $_titre;

	private $_descriptif;

	private $_posGeographique;

	private $_dateCreation;

	private $_dateUpdate;

  private $_dateDeb;

  private $_dateFin;

  private $_numSemaine;

  private $_numJour;

  private $_periodicite;

  private $_occurences;

  private $_priorite;

	public function __construct($idActivite, $idAgenda, $idCategorie,
   $idSimilaire, $titre, $descriptif, $posGeographique, $dateCreation,
   $dateUpdate, $dateDeb, $dateFin, $numSemaine, $numJour, $periodicite,
   $occurences, $priorite) {

		$this->set_idActivite($idActivite);
		$this->set_idAgenda($idAgenda);
		$this->set_idCategorie($idCategorie);
		$this->set_idSimilaire($idSimilaire);
		$this->set_titre($titre);
		$this->set_descriptif($descriptif);
		$this->set_posGeographique($posGeographique);
		$this->set_dateCreation($dateCreation);
		$this->set_dateUpdate($dateUpdate);
    $this->set_dateDeb($dateDeb);
    $this->set_dateFin($dateFin);
    $this->set_numSemaine($numSemaine);
    $this->set_numJour($numJour);
    $this->set_periodicite($periodicite);
    $this->set_occurences($occurences);
    $this->set_priorite($priorite);

	}

	//get

	public function idActivite() {
		return $this->_idActivite;
	}

	public function idAgenda() {
		return $this->_idAgenda;
	}

	public function idCategorie() {
		return $this->_idCategorie;
	}

	public function idSimilaire() {
		return $this->_idSimilaire;
	}

	public function titre() {
		return $this->_titre;
	}

	public function descriptif() {
		return $this->_descriptif;
	}

	public function posGeographique() {
		return $this->_posGeographique;
	}

	public function dateCreation() {
		return $this->_dateCreation;
	}

	public function dateUpdate() {
		return $this->_dateUpdate;
	}

  public function dateDeb() {
    return $this->_dateDeb;
  }

  public function dateFin() {
    return $this->_dateFin;
  }

  public function numSemaine() {
    return $this->_numSemaine;
  }

  public function numJour() {
    return $this->_numJour;
  }

  public function periodicite() {
    return $this->_periodicite;
  }

  public function occurences() {
    return $this->_occurences;
  }

  public function priorite() {
    return $this->_priorite;
  }

	//set

	private function set_idActivite($v) {
		$this->_idActivite = (int) $v;
	}

	private function set_idAgenda($v) {
		$this->_idAgenda = (int) $v;
	}

	private function set_idCategorie($v) {
		$this->_idCategorie = (int)($v);
	}

	private function set_idSimilaire($v) {
		$this->_idSimilaire = (int)($v);
	}

	public function set_titre($v) {
		$this->_titre = strval($v);
	}

	public function set_descriptif($v) {
		$this->_descriptif = strval($v);
	}

	public function set_posGeographique($v) {
		$this->_posGeographique = strval($v);
	}

	public function set_dateCreation($v) {
		$this->_dateCreation = strval($v);
	}

	private function set_dateUpdate($v) {
		$this->_dateUpdate = strval($v);
	}

  public function set_dateDeb($v) {
		$this->_dateDeb = strval($v);
	}

  public function set_dateFin($v) {
		$this->_dateFin = strval($v);
	}

  public function set_numSemaine($v) {
		$this->_numSemaine = (int)($v);
	}

  public function set_numJour($v) {
		$this->_numJour = (int)($v);
	}

  public function set_periodicite($v) {
		$this->_periodicite = (int)($v);
	}

  public function set_occurences($v) {
		$this->_occurences = (int)($v);
	}

  public function set_priorite($v) {
		$this->_priorite = (int)($v);
	}


	public function add() {
		if(!is_null($this->_id)) {
			$q = self::$_db->prepare('INSERT INTO A_ACTIVITE (seq_activite.nextval,
        idAgenda, idCategorie, idSimilaire, titre, descriptif, posGeographique,
        dateCreation, dateUpdate, dateDeb, dateFin, numSemaine, numJour,
        periodicite, occurences, priorite) VALUES (:idAgenda,:idCategorie,
        :idSimilaire,
        :titre, :descriptif, :posGeographique, :dateCreation, sysdate,
        :dateDeb, :dateFin, :numSemaine, :numJour, :periodicite, :occurences,
        :priorite)');
      $q->bindValue(':idAgenda', $this->_idAgenda, PDO::PARAM_INT);
      $q->bindValue(':idCategorie', $this->_idCategorie, PDO::PARAM_INT);
			$q->bindValue(':idSimilaire', $this->_idSimilaire, PDO::PARAM_INT);
			$q->bindValue(':titre', $this->_titre, PDO::PARAM_STR);
			$q->bindValue(':descriptif', $this->_descriptif, PDO::PARAM_STR);
			$q->bindValue(':posGeographique', $this->_posGeographique, PDO::PARAM_STR)
      ;
			$q->bindValue(':dateCreation', $this->_dateCreation, PDO::PARAM_STR);
      $q->bindValue(':dateDeb', $this->_dateCreation, PDO::PARAM_STR);
      $q->bindValue(':dateFin', $this->_dateCreation, PDO::PARAM_STR);
      $q->bindValue(':numSemaine', $this->_numSemaine, PDO::PARAM_INT);
      $q->bindValue(':numJour', $this->_numJour, PDO::PARAM_INT);
      $q->bindValue(':periodicite', $this->_periodicite, PDO::PARAM_STR);
      $q->bindValue(':occurences', $this->_occurences, PDO::PARAM_INT);
      $q->bindValue(':priorite', $this->_priorite, PDO::PARAM_INT);
			$q->execute();
		}
	}

	public function save()
	{
		if(!is_null($this->_id)) {
			$q = self::$_db->prepare('UPDATE A_ACTIVITE SET descriptif=:descriptif,
        posGeographique=:posGeographique, dateUpdate=sysdate, dateDeb=:dateDeb,
        dateFin=:dateFin, numSemaine=:numSemaine, numJour=:numJour,
        periodicite=:periodicite, occurences=:occurences, priorite=:priorite
        WHERE idActivite = :id');
			$q->bindValue(':id', $this->_idActivite, PDO::PARAM_INT);
			$q->bindValue(':descriptif', $this->_descriptif, PDO::PARAM_STR);
			$q->bindValue(':posGeographique', $this->_posGeographique, PDO::PARAM_STR)
      ;
      $q->bindValue(':dateDeb', $this->_dateCreation, PDO::PARAM_STR);
      $q->bindValue(':dateFin', $this->_dateCreation, PDO::PARAM_STR);
      $q->bindValue(':numSemaine', $this->_numSemaine, PDO::PARAM_INT);
      $q->bindValue(':numJour', $this->_numJour, PDO::PARAM_INT);
      $q->bindValue(':periodicite', $this->_periodicite, PDO::PARAM_STR);
      $q->bindValue(':occurences', $this->_occurences, PDO::PARAM_INT);
      $q->bindValue(':priorite', $this->_priorite, PDO::PARAM_INT);
      $q->execute();
		}
	}

	public function delete()
	{
		if(!is_null($this->_id)) {
			$q = self::$_db->prepare('DELETE FROM A_ACTIVITE WHERE idAgenda = :id');
			$q->bindValue(':id', $this->_idAgenda);
			$q->execute();
			$this->_id = null;
		}
	}

	public static function get_by_descriptif($descriptif) {
		$s = self::$_db->prepare('SELECT * FROM A_ACTIVITE where descriptif LIKE
      "%:descr%"');
		$s->bindValue(':descr', $descriptif, PDO::PARAM_STR);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
		if ($data) {
			return new Activite($data['idActivite'],$data['idAgenda'],
        $data['idCategorie'],$data['idSimilaire'],$data['titre'],
        $data['descriptif'],$data['posGeographique'],$data['dateCreation'],
        $data['dateUpdate'], $data['dateDeb'],$data['dateFin'],
        $data['numSemaine'],$data['numJour'],$data['periodicite'],
        $data['occurences'],$data['priorite']
			);
		} else {
			return null;
		}
	}

	public static function get_by_id($id) {
		$s = self::$_db->prepare('SELECT * FROM A_ACTIVITE where idActivite = :id');
		$s->bindValue(':id', $id, PDO::PARAM_INT);
		$s->execute();
		$data = $s->fetch(PDO::FETCH_ASSOC);
		if ($data) {
			return new Activite($data['idActivite'],$data['idAgenda'],
        $data['idCategorie'],$data['idSimilaire'],$data['titre'],
        $data['descriptif'],$data['posGeographique'],$data['dateCreation'],
        $data['dateUpdate'], $data['dateDeb'],$data['dateFin'],
        $data['numSemaine'],$data['numJour'],$data['periodicite'],
        $data['occurences'],$data['priorite']
			);
		} else {
			return null;
		}
	}

  public static function get_by_idAgenda($id) {
		$s = self::$_db->prepare('SELECT * FROM A_ACTIVITE where idAgenda = :id');
		$s->bindValue(':id', $id, PDO::PARAM_INT);
		$s->execute();
    $activites = array();
		while($data = $s->fetch(PDO::FETCH_ASSOC)) {
			$activites[] = new Activite($data['idActivite'],$data['idAgenda'],
        $data['idCategorie'],$data['idSimilaire'],$data['titre'],
        $data['descriptif'],$data['posGeographique'],$data['dateCreation'],
        $data['dateUpdate'], $data['dateDeb'],$data['dateFin'],
        $data['numSemaine'],$data['numJour'],$data['periodicite'],
        $data['occurences'],$data['priorite']
			);
		}
		return $activites;
	}

  public static function get_by_idUtilisateur($id) {
    $s = self::$_db->prepare('SELECT * FROM A_ACTIVITE where idUtilisateur =:id');
		$s->bindValue(':id', $id, PDO::PARAM_INT);
		$s->execute();
    $activites = array();
		while($data = $s->fetch(PDO::FETCH_ASSOC)) {
			$activites[] = new Activite($data['idActivite'],$data['idAgenda'],
        $data['idCategorie'],$data['idSimilaire'],$data['titre'],
        $data['descriptif'],$data['posGeographique'],$data['dateCreation'],
        $data['dateUpdate'], $data['dateDeb'],$data['dateFin'],
        $data['numSemaine'],$data['numJour'],$data['periodicite'],
        $data['occurences'],$data['priorite']
			);
		}
		return $activites;
	}

  public static function get_by_idUtilisateurAgenda($idUtilisateur,$idActivite){
    $s = self::$_db->prepare('SELECT * FROM A_ACTIVITE
      where idUtilisateur =:idUtil AND idAgenda=:idAgenda');
		$s->bindValue(':idUtil', $idUtilisateur, PDO::PARAM_INT);
    $s->bindValue(':idAgenda', $idAgenda, PDO::PARAM_INT);
		$s->execute();
    $activites = array();
		while($data = $s->fetch(PDO::FETCH_ASSOC)) {
			$activites[] = new Activite($data['idActivite'],$data['idAgenda'],
        $data['idCategorie'],$data['idSimilaire'],$data['titre'],
        $data['descriptif'],$data['posGeographique'],$data['dateCreation'],
        $data['dateUpdate'], $data['dateDeb'],$data['dateFin'],
        $data['numSemaine'],$data['numJour'],$data['periodicite'],
        $data['occurences'],$data['priorite']
			);
		}
		return $activites;
	}
}
