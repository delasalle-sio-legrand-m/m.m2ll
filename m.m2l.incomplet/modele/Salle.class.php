<?php
// Projet Réservations M2L - version web mobile
// fichier : modele/Salle.class.php
// Rôle : la classe Salle représente les salles pouvant être réservées
// Création : 27/09/2016 par Mathieu Legrand
// Mise à jour : 27/09/2016 par Mathieu Legrand

class Salle
{
	// ------------------------------------------------------------------------------------------------------
	// ---------------------------------- Membres privés de la classe ---------------------------------------
	// ------------------------------------------------------------------------------------------------------
	
	private $id;				// identifiant de la salle (numéro automatique dans la BDD)
	private $room_name;				// Nom de la salle.
	private $capacity;				// Nombre de personnes pouvant être accueillies.
	private $area_name;			// Nom de "l'aire" dans laquelle la salle se situe.
	
	// ------------------------------------------------------------------------------------------------------
	// ----------------------------------------- Constructeur -----------------------------------------------
	// ------------------------------------------------------------------------------------------------------
	
	public function Salle($unId, $unRoomName, $unCapacity, $unAreaName) {
		$this->id = $unId;
		$this->room_name = $unRoomName;
		$this->capacity = $unCapacity;
		$this->area_name = $unAreaName;
	}
		
	// ------------------------------------------------------------------------------------------------------
	// ---------------------------------------- Getters et Setters ------------------------------------------
	// ------------------------------------------------------------------------------------------------------
	
	public function getId()	{return $this->id;}
	public function setId($unId) {$this->id = $unId;}
	
	public function getRoom_name()	{return $this->room_name;}
	public function setRoom_name($unRoomName) {$this->room_name = $unRoomName;}
	
	public function getCapacity()	{return $this->capacity;}
	public function setCapacity($unCapacity) {$this->capacity = $unCapacity;}
	
	public function getAreaName()	{return $this->area_name;}
	public function setAreaName($unAreaName) {$this->area_name = $unAreaName;}
	
	public function toString() {
		$msg = 'Salle : <br>';
		$msg .= 'id : ' . $this->getId() . '<br>';
		$msg .= 'Room name : ' . $this->getRoom_name() . '<br>';
		$msg .= 'Capacity : ' . $this->getCapacity() . '<br>';
		$msg .= 'Area name : ' . $this->getAreaName() . '<br>';
		$msg .= '<br>';
		return $msg;
	}
}

// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces après la balise de fin de script !!!!!!!!!!!!