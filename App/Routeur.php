<?php


require_once 'Controleur/ControleurAccueil.php';
require_once 'Controleur/ControleurBillet.php';
require_once 'Controleur/ControleurCreationBillet.php';
require_once 'Controleur/ControleurConnexion.php';
/**
require_once 'Controleur/ControleurEditionBillet';

require_once 'Controleur/ControleurUser';
require_once 'Controleur/ControleurAdmin';
require_once 'Controleur/ControleurCreateUser';
 */
require_once 'Vue/Vue.php';


class Routeur {

    /** @var ControleurAccueil  */
    private $ctrlAccueil;
    /** @var ControleurBillet  */
    private $ctrlBillet;

    /** On instancie les classes */
    public function __construct() {
        /** @var  ctrlAccueil */
        $this->ctrlAccueil = new ControleurAccueil();
        /** @var  ctrlBillet */
        $this->ctrlBillet = new ControleurBillet();
        /** @var  crtlCreationBillet */
        $this->crtlCreationBillet = new ControleurCreateBillet();
        /** @var  crtlConnexion */
        $this->crtlConnexion = new ControleurConnexion();
        /** @var  crtlEditBillet
        $this->crtlEditBillet = new ControleurEditionBillet();
         */
    }

    /** Route une requête entrante : exécution l'action associée */

    public function routerRequete() {
        try {

            /** s'il existe une action on execute la suite du programme */

            if (isset($_GET['action'])) {

                /**   -------------------------------- Les pages ------------------------------ */
                /** ___________________________________________________________________________ */

                if ($_GET['action'] == 'billet') {
                    $idBillet = intval($this->getParametre($_GET, 'id'));
                    if ($idBillet != 0) {
                        $this->ctrlBillet->billet($idBillet);
                    }
                    else
                        throw new Exception("Identifiant de billet non valide");
                }

                if ($_GET['action'] == 'createBillet') {
                    $this->crtlCreationBillet->creationBillet();
                }

                if ($_GET['action'] == 'connexion') {
                    $this->crtlConnexion->connexionPage();
                }
                /**
                if ($_GET['action'] == 'editionBillet') {
                    $this->crtlEditBillet->editPage();
                }


                if ($_GET['action'] == 'user') {
                    $this->crtlEditBillet->user();
                }

                if ($_GET['action'] == 'createUser') {
                    $this->crtlEditBillet->createUser();
                }

                if ($_GET['action'] == 'admin') {
                    $this->crtlEditBillet->admin();
                }
                */


                /**   -------------------------------- Action directe ------------------------------ */




                if ($_GET['action'] == 'creation') {
                    /** @var  $titre */
                    $titre = $this->getParametre($_POST, 'titre');
                    /** @var  $contenu */
                    $contenu = $this->getParametre($_POST, 'contenu');

                    $this->ctrlBillet->creation($titre, $contenu);
                }

                if ($_GET['action'] == 'deleteBillet') {
                    /** @var  $idBillet */
                    $idBillet = intval($this->getParametre($_GET, 'id'));
                    $this->ctrlBillet->supprimer($idBillet);
                    header("location:index.php");

                }

                else if ($_GET['action'] == 'commenter') {
                    /** @var  $auteur */
                    $auteur = $this->getParametre($_POST, 'auteur');
                    /** @var  $contenu */
                    $contenu = $this->getParametre($_POST, 'contenu');
                    /** @var  $idBillet */
                    $idBillet = $this->getParametre($_POST, 'id');
                    /** @var  $idParent */
                    $idParent = $this->getParametre($_POST, 'id_parent');

                    $this->ctrlBillet->commenter($auteur, $contenu, $idBillet , $idParent);
                }
                else
                    throw new Exception("");
            }
            else {  // aucune action définie : affichage de l'accueil
                $this->ctrlAccueil->accueil();
            }
        }
        catch (Exception $e) {
            $this->erreur($e->getMessage());
        }
    }



    /** Affiche une erreur */

    private function erreur($msgErreur) {
        /** @var  $vue */
        $vue = new Vue("Erreur");
        $vue->generer(array('msgErreur' => $msgErreur));
    }

    /** Recherche un paramètre dans un tableau */

    private function getParametre($tableau, $nom) {
        if (isset($tableau[$nom])) {
            return $tableau[$nom];
        }
        else
            throw new Exception("Paramètre '$nom' absent");
    }

}