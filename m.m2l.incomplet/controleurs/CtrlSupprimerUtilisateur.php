<?php
// Projet Réservations M2L - version web mobile
// fichier : controleurs/CtrlCreerUtilisateur.php
// Rôle : traiter la demande de création d'un nouvel utilisateur
// Création : 21/10/2015 par JM CARTRON
// Mise à jour : 2/6/2016 par JM CARTRON

// on vérifie si le demandeur de cette action a le niveau administrateur
if ($_SESSION['niveauUtilisateur'] != 'administrateur') {
	// si l'utilisateur n'a pas le niveau administrateur, il s'agit d'une tentative d'accès frauduleux
	// dans ce cas, on provoque une redirection vers la page de connexion
	header ("Location: index.php?action=Deconnecter");
}
else {
	if ( ! isset ($_POST ["txtName"]) ) {
		// si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
		$name = '';
// 		$adrMail = '';
// 		$level = '0';
		$message = '';
		$typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
		$themeFooter = $themeNormal;
		include_once ('vues/VueSupprimerUtilisateur.php');
	}
	else {
		// récupération des données postées
		if ( empty ($_POST ["txtName"]) == true)  $name = "";  else   $name = $_POST ["txtName"];
		
		// inclusion de la classe Outils pour utiliser les méthodes statiques estUneAdrMailValide et creerMdp
		include_once ('modele/Outils.class.php');
		
		if ($name == '') {
			// si les données sont incorrectes ou incomplètes, réaffichage de la vue de suppression avec un message explicatif
			$message = 'Données incomplètes ou incorrectes !';
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			include_once ('vues/VueSupprimerUtilisateur.php');
		}
		else {
			// connexion du serveur web à la base MySQL
			include_once ('modele/DAO.class.php');
			$dao = new DAO();
				
			if (! $dao->existeUtilisateur($name) ) {
				// si le nom existe déjà, réaffichage de la vue
				$message = "Nom d'utilisateur inexistant !";
				$typeMessage = 'avertissement';
				$themeFooter = $themeProbleme;
				include_once ('vues/VueSupprimerUtilisateur.php');
			}
			else {
				//récupération de l'utilisateur associé au nom
				$unUtilisateur = $dao->getUtilisateur($name);
				
				//récupération du mail de l'utilisateur
				$adrMail = $unUtilisateur->getEmail();
				// suppression de l'utilisateur dans la BDD
				$ok = $dao->supprimerUtilisateur($name);
				if (  $ok ) {
					// si la suppression a échoué, réaffichage de la vue avec un message explicatif					
					$message = "Problème lors de la suppression !";
					$typeMessage = 'avertissement';
					$themeFooter = $themeProbleme;
					include_once ('vues/VueSupprimerUtilisateur.php');
				}
				else {
					// envoi d'un mail de confirmation de l'enregistrement
					$sujet = "Suppression de votre compte dans le système de réservation de M2L";
					$contenuMail = "L'administrateur du système de réservations de la M2L vient de vous supprimer votre compte utilisateur.\n\n";

						
					$ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
					if ( ! $ok ) {
						// si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
						$message = "Enregistrement effectué.<br>L'envoi du mail à l'utilisateur a rencontré un problème !";
						$typeMessage = 'avertissement';
						$themeFooter = $themeProbleme;
						include_once ('vues/VueSupprimerUtilisateur.php');
					}
					else {
						// tout a fonctionné
						$message = "Enregistrement effectué.<br>Un mail va être envoyé à l'utilisateur !";
						$typeMessage = 'information';
						$themeFooter = $themeNormal;
						include_once ('vues/VueSupprimerUtilisateur.php');
					}
				}
			}
			unset($dao);		// fermeture de la connexion à MySQL
		}
	}
}