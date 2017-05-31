/**
 * Application de suivi des réservations de la Maison des Ligues de Lorraine
 * Thème : développement et test des classes Salle, Reservation, Utilisateur et Passerelle
 * Auteur : JM CARTRON
 * Dernière mise à jour : 7/11/2016
 */

package reservations.classes;

import java.util.ArrayList;

/**
 * Cette classe représente un utilisateur
 */
public class Utilisateur {
	
	/** Membres privés */
	private int _id;				// identifiant de l'utilisateur
	private int _level;				// 0=simple visiteur  1=utilisateur pouvant réserver  2=administrateur
	private String _name;			// nom de l'utilisateur
	private String _password;		// mot de passe de l'utilisateur
	private String _email;			// adresse mail de l'utilisateur
	private ArrayList<Reservation> _lesReservations;	// les réservations passées par l'utilisateur
	
	/** Constructeurs  */
	public Utilisateur() {
		this._id = 0;
		this._level = 0;
		this._name = "";
		this._password = "";
		this._email = "";
		this._lesReservations = new ArrayList<Reservation>();
	}	
	public Utilisateur(int unId, int unLevel, String unName, String unPassword, String unEmail) {
		this._id = unId;
		this._level = unLevel;
		this._name = unName;
		this._password = unPassword;
		this._email = unEmail;
		this._lesReservations = new ArrayList<Reservation>();
	}	
	
	/** Accesseurs */	
	public int getId() {
		return _id;
	}
	public void setId(int unId) {
		this._id = unId;
	}
	public int getLevel() {
		return _level;
	}
	public void setLevel(int unLevel) {
		this._level = unLevel;
	}
	public String getName() {
		return _name;
	}
	public void setName(String unName) {
		this._name = unName;
	}
	public String getPassword() {
		return _password;
	}
	public void setPassword(String unPassword) {
		this._password = unPassword;
	}
	public String getEmail() {
		return _email;
	}
	public void setEmail(String unEmail) {
		this._email = unEmail;
	}
	public ArrayList<Reservation> getLesReservations() {
		return _lesReservations;
	}
	
	/** Méthodes d'instance publiques */
	public void ajouteReservation(Reservation uneReservation)
	{	// ajoute l'objet à la liste
		this._lesReservations.add(uneReservation);
	}
	public Reservation getLaReservation(int i)
	{	// fournit la réservation correspondant à l'index demandé
		return this._lesReservations.get(i);
	}
	public int getNbReservations()
	{	// fournit le nombre de réservations dans la liste
		return this._lesReservations.size();
	}
	public void viderListeReservations()
	{	// vide la collection des réservations
		_lesReservations.clear();
	}

}
