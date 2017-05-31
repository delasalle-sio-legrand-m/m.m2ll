<?php
// Projet Réservations M2L - version web mobile
// fichier : controleurs/CtrlDemanderMdp.php
// Rôle : traiter la demande de nouveau mot de passe
// Création : 15/11/2016 par Mathieu LEGRAND
// Mise à jour : 15/11/2016 par Mathieu LEGRAND


	if ( ! isset ($_POST ["txtName"]) ) {
		// si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
		$name = '';
// 		$adrMail = '';
// 		$level = '0';
		$message = '';
		$typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
		$themeFooter = $themeNormal;
		include_once ('vues/VueDemanderMdp.php');
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
			include_once ('vues/VueDemanderMdp.php');
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
				include_once ('vues/VueDemanderMdp.php');
			}
			else {
				//récupération de l'utilisateur associé au nom
				$unUtilisateur = $dao->getUtilisateur($name);
				
				//récupération du mail de l'utilisateur
				$adrMail = $unUtilisateur->getEmail();

					$nouveauMdp = "motdepasse0";
					$dao->modifierMdpUser($name, $nouveauMdp);
					$ok = $dao->envoyerMdp($name, $nouveauMdp);
					
					if ( ! $ok ) {
						// si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
						$message = "Enregistrement effectué.<br>L'envoi du mail a rencontré un problème !";
						$typeMessage = 'avertissement';
						$themeFooter = $themeProbleme;
						include_once ('vues/VueDemanderMdp.php');
					}
					else {
						// tout a fonctionné
						$message = "Enregistrement effectué.<br>Un mail va vous être envoyé !";
						$typeMessage = 'information';
						$themeFooter = $themeNormal;
						include_once ('vues/VueDemanderMdp.php');
					}
				}
			}
			unset($dao);		// fermeture de la connexion à MySQL
		}
	
