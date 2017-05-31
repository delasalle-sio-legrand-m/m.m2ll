<?php
// Projet Réservations M2L - version web mobile
// fichier : controleurs/CtrlCreerUtilisateur.php
// Rôle : traiter la demande de création d'un nouvel utilisateur
// Création : 8/11/2016 par LEGRAND Mathieu
// Mise à jour : 15/11/2016 par Legrand Mathieu

	if ( ! isset ($_POST ["txtIdReservation"]) ) {
		// si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
		$idReservation = '';
		$name = '';
		$message = '';
		$typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
		$themeFooter = $themeNormal;
		include_once ('vues/VueConfirmerReservation.php');
	}
	else {
		// récupération des données postées
		if ( empty ($_POST ["txtIdReservation"]) == true)  $idReservation = "";  else   $idReservation = $_POST ["txtIdReservation"];
		if (isset ($_SESSION["nom"])) $name = $_SESSION["nom"]; 
		// inclusion de la classe Outils pour utiliser les méthodes statiques estUneAdrMailValide et creerMdp
		include_once ('modele/Outils.class.php');
		
		if ($idReservation == '') {
			// si les données sont incorrectes ou incomplètes, réaffichage de la vue de suppression avec un message explicatif
			$message = 'Données incomplètes ou incorrectes !';
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			include_once ('vues/VueConfirmerReservation.php');
		}
		else {
			// connexion du serveur web à la base MySQL
			include_once ('modele/DAO.class.php');
			$dao = new DAO();
				
			if (! $dao->existeReservation($idReservation) ) {
				// si la réservation n'existe pas, réaffichage de la vue
				$message = "La réservation n'existe pas !";
				$typeMessage = 'avertissement';
				$themeFooter = $themeProbleme;
				include_once ('vues/VueConfirmerReservation.php');
			}
			else {
				$ok = $dao->estLeCreateur($name,$idReservation);
				if ( ! $ok ) {
					// Si la personne connectée n'est pas l'auteur de la réservation				
					$message = "Erreur : vous n'êtes pas l'auteur de cette réservation !";
					$typeMessage = 'avertissement';
					$themeFooter = $themeProbleme;
					include_once ('vues/VueConfirmerReservation.php');
				}
				else {
					$reservation = $dao->getReservation($idReservation);
					$status = $reservation->getStatus();
					if ($status == 0) {
						$message = "Erreur : cette réservation a déjà été confirmée !";
						$typeMessage = 'avertissement';
						$themeFooter = $themeProbleme;
						include_once ('vues/VueConfirmerReservation.php');
					}
					else {
						$date = $reservation->getEnd_time();
						if ($date < time()){
							$message = "Erreur : cette réservation est déjà passée !";
							$typeMessage = 'avertissement';
							$themeFooter = $themeProbleme;
							include_once ('vues/VueConfirmerReservation.php');
						}
						else {
							// envoi d'un mail de confirmation de l'enregistrement
							$sujet = "Confirmation de la réservation numéro " . $idReservation;
							$contenuMail = "La réservation numéro " . $idReservation . " a été confirmée. \n\n";
							//récupération de l'utilisateur associé au nom
							$unUtilisateur = $dao->getUtilisateur($name);
							
							//récupération du mail de l'utilisateur
							$adrMail = $unUtilisateur->getEmail();
							$dao->confirmerReservation($idReservation);
							$ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
							if ( ! $ok ) {
								// si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
								$message = "Enregistrement effectué.<br>L'envoi du mail a rencontré un problème !";
								$typeMessage = 'avertissement';
								$themeFooter = $themeProbleme;
								include_once ('vues/VueConfirmerReservation.php');
							}
							else {
								// tout a fonctionné
								$message = "Enregistrement effectué.<br>Un mail va vous être envoyé !";
								$typeMessage = 'information';
								$themeFooter = $themeNormal;
								include_once ('vues/VueConfirmerReservation.php');
							}
						}
					}
					unset($dao);		// fermeture de la connexion à MySQL
				}
			}
		}
	}
