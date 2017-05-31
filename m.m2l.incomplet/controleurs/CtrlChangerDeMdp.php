<?php
// Projet Réservations M2L - version web mobile
// fichier : controleurs/CtrlConsulterReservations.php
// Rôle : traiter la demande de consultation des réservations d'un utilisateur
// écrit par Jim le 12/10/2015
// modifié par Jim le 28/6/2016
	
	if ( ! isset ($_POST ["txtNewMdp"]) && ! isset ($_POST ["txtCNMdp"])  == true) {
		// si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
		$nouveauMdp = '';
		$CNMdp = '';
		include_once ('vues/VueChangerDeMdp.php');
	}
	else {
		// récupération des données postées
		if ( empty ($_SESSION ["nom"]) == true)  $nom = "";  else   $nom = $_SESSION ["nom"];
		if ( empty ($_POST ["txtNewMdp"]) == true)  $nouveauMdp = "";  else   $nouveauMdp = $_POST ["txtNewMdp"];
		if ( empty ($_POST ["txtCNMdp"]) == true)  $CNMdp = "";  else   $CNMdp = $_POST ["txtCNMdp"];
		if ( empty ($_POST ["caseAfficherMdp"]) == true)  $afficherMdp = "off";  else   $afficherMdp = $_POST ["caseAfficherMdp"];
	
		// inclusion de la classe Outils pour utiliser les méthodes statiques estUneAdrMailValide et creerMdp
		include_once ('modele/Outils.class.php');
		
		if ($CNMdp == '' || $nouveauMdp == '') {
			// si les données sont incomplètes, réaffichage de la vue avec un message explicatif
			$message = 'Données incomplètes ou incorrectes !';
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			$changerMdp = '';
			include_once ('vues/VueChangerDeMdp.php');
		}
	else {
		// connexion du serveur web à la base MySQL
		include_once ('modele/DAO.class.php');
		$dao = new DAO();
		
		if ($CNMdp != $nouveauMdp) {
			$message = 'Le mot de passe et sa confirmation sont différents !';
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			$changerMdp = '';
			include_once ('vues/VueChangerDeMdp.php');
		}
		else {
			// test de l'authentification de l'utilisateur
			// la méthode getNiveauUtilisateur de la classe DAO retourne les valeurs 'inconnu' ou 'utilisateur' ou 'administrateur'
			$dao->modifierMdpUser($nom, $nouveauMdp);
			// envoi d'un mail de confirmation de l'enregistrement
			$sujet = "modification de votre mot de passe dans le système de réservation de M2L";
			$contenuMail = "L'administrateur du système de réservations de la M2L vient de modifier le mot de passe de votre compte utilisateur.\n\n";
			
			
			$ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
			if ( ! $ok ) {
				// si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
				$message = "Enregistrement effectué.<br>L'envoi du mail à l'utilisateur a rencontré un problème !";
				$typeMessage = 'avertissement';
				$themeFooter = $themeProbleme;
				include_once ('vues/VueChangerDeMdp.php');
			}
			else {
				// tout a fonctionné
				$message = "Enregistrement effectué.<br>Un mail va être envoyé à l'utilisateur !";
				$typeMessage = 'information';
				$themeFooter = $themeNormal;
				include_once ('vues/VueChangerDeMdp.php');
			}
		}

	// affichage de la vue
	include_once ('vues/VueChangerDeMdp.php');
	
	unset($dao);		// fermeture de la connexion à MySQL
}
}


?>