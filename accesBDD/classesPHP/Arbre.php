<?php

require_once '../accesBDD/bddT3.php';
require_once '../accesBDD/MyPDO.php';
require_once '../accesBDD/classesPHP/Heritage.php';

/*
* \class Arbre
* \brief Permet de gèrer l'arbre généalogique.
 */
class Arbre {
    public function __construct(){
    }

    /**
    *\fn public function triParFratrie(array $tabPersoParents) : array
    * \brief Tri nos personnages sous formes de frateries en regroupant les enfants d'un même parent.
    * \pre tabPersoParents contient en clé l'id d'un personnage et en valeur celui de son parent.
    * \return Renvoie un tableau de tableaux. En clé un parent, en valeur un tableau contenant son propre id puis ceux de ses enfants.
    */
    public function triParFratrie(array $tabPersoParents) : array{
        //renvoie un tableau de tableau, organisé par parent, chaque sous tableau etant la liste de frères et soeurs qui sont les enfant du parent
        //on l'initialise avec la racine 1
        $tableParentEnfants[1][] = 1;
        foreach ($tabPersoParents as $enfant => $parent){
            //si on pas de case ayant pour clé $parent dans le tableau $tableParentEnfants alors on en créer une
            if (!(array_key_exists($parent,$tableParentEnfants))){
                //on crée pour le parent une liste de ses enfants contenant à la premiere case l'id du parent
                $tableParentEnfants[$parent][] = $parent;
            }
            //on ajoute l'enfant à sa liste de freres et soeurs
            $tableParentEnfants[$parent][] = $enfant;

        }
        //enfin on renvoie notre tableau contenant les fratries par parent
        return $tableParentEnfants;
    }

    /**
    *\fn public function remplissageArbre(int $idParent, array $tableParentEnfants) : void
    * \brief Créer une sous partie de l'arbre généalogique en forme de liste HTML. On ajoute à l'arbre tout les enfants d'un parent donné.
    * \pre idParent contient l'id d'un personnage.
    * \pre tableParentEnfants contient en clé un parent, en valeur un tableau contenant son propre id puis ceux de ses enfants.
    */
    public function remplissageArbre(int $idParent, array $tableParentEnfants) : void {
        //on regarde si le parent a des enfants
        if (array_key_exists($idParent,$tableParentEnfants)){
            //si oui on crée une liste html pour y mettre ses enfants
            echo '<ul>';
            //puis pour chacun de ses enfant on crée un élément de liste avec son id
            $i = 1;
            while (isset($tableParentEnfants[$idParent][$i])){
                echo'<li>';
                //la classe nous donnera la couleur de fond sur l'arbre
                $class = $this->chercheClassePerso($tableParentEnfants[$idParent][$i]);
                //le sexe nous sert pour l'image du personnage
                $sexe =  $this->chercheSexePerso($tableParentEnfants[$idParent][$i]);
                //le lien mene vers la meme page (l'index) mais avec en get l'indentifiant du personnage
                echo'<a '.$class.' href="../pageDeLancement/lancement.php?refresh=0&id='.$tableParentEnfants[$idParent][$i].'"><img src="../imagesPersos/'.$sexe.$tableParentEnfants[$idParent][$i].'.png"></a>';
                //et on fait la même chose avec ses propres enfants
                $this->remplissageArbre($tableParentEnfants[$idParent][$i],$tableParentEnfants);
                echo'</li>';
                $i++;
            }
            echo'</ul>';
        }
    }

    /**
    *\fn public function chercheClassePerso(int $id) : string
    * \brief Cherche dans la bdd la classe d'un personnage donné pour lui faire correspondre la bonne couleur sur l'arbre généalogique.
    * \pre id contient l'id du personnage dont on souhaite connaitre la classe.
    * \return Revoie la classe du personnage sous forme de chaine de caractères.
    */
    public function chercheClassePerso(int $id) : string {
        $resultClasse = MyPDO::pdo()->prepare("SELECT classe FROM perso WHERE id=:id");
        $idSucces = $resultClasse->bindValue(':id',$id, PDO::PARAM_INT);
        $resultClasse->execute();
        $classe;
        foreach($resultClasse as $row){
            $classe = $row['classe'];
        }
        //Il n'y a qu'un seul roi, ainsi le fond est changé en fonction de l'attribut id
        if($classe == 'roi'){
            $classe = 'id="roi"';
        }
        //pour les autres plusieurs perosnnages ont les mêmes couleurs donc il s'agit de l'attribut class
        else{
            $classe = 'class="'.$classe.'"';
        }

        return $classe;
    }

    /**
    *\fn public function chercheSexePerso(int $id) : string
    * \brief Cherche dans la bdd le sexe d'un personnage donné pour lui faire correspondre la bonne image sur l'arbre généalogique.
    * \pre id contient l'id du personnage dont on souhaite connaitre le sexe.
    * \return Revoie le sexe du personnage sous forme de chaine de charactères.
    */
    public function chercheSexePerso(int $id) : string {
        $resultSexe = MyPDO::pdo()->prepare("SELECT sexe FROM perso WHERE id=:id");
        $idSucces = $resultSexe->bindValue(':id',$id, PDO::PARAM_INT);
        $resultSexe->execute();
        $sexe ='';
        foreach($resultSexe as $row){
            $sexe = $row['sexe'];
        }
        //On ne prends pas le sexe directement car les noms d'images sont de type H<id> ou F<id>
        if($sexe == 'homme'){
            $sexe = 'H';
        }
        else{
            $sexe = 'F';
        }

        return $sexe;
    }

    /**
    *\fn public function cherchePersoArbre() : array
    * \brief Cherche dans la bdd tout les personnages devant figurer sur l'arbre généalogique.
    * \return Revoie un tableau trié de tous les personnages à afficher.
    */
    public function cherchePersoArbre() : array{
        //on prends tous les personnages de notre base personnage
        $resultBase = MyPDO::pdo()->prepare("SELECT id,parent FROM perso");
        $resultBase->execute();
        //on crée un tableau contenant tout les id de notre base
        $tabId;
        //on crée un tableau contenant en clé l'id d'un personnage et en valeur celui de son parent
        $ListePersoParent;
        foreach ($resultBase as $row){
            if($row['id']!=1){
                $ListePersoParent[$row['id']] = $row['parent'];
                $tabId[] = $row['id'];
            }
            
        }
        //on tris nos personnages selon leur fratrie et leur parents
        $tabParentEnfant = $this->triParFratrie($ListePersoParent);

        //si un parent est mort et qu'il n'as pas d'enfants on le supprime de notre arbre pour l'alléger
        foreach($ListePersoParent as $enfant => $parent){
            /*On a trier les personnages en fonction de leur parent juste avant
             donc si un personnage n'est pas dans ce tableau en tant que parent c'est qu'il n'as pas d'enfants*/
            if (!(array_key_exists($enfant,$tabParentEnfant))){
                $resultClasse = MyPDO::pdo()->prepare("SELECT classe FROM perso WHERE id=:id");
                $idSucces = $resultClasse->bindValue(':id',$enfant, PDO::PARAM_INT);
                $resultClasse->execute();
                $classe;
                foreach($resultClasse as $row){
                    $classe = $row['classe'];
                }
                if ($classe == 'mort'){
                    $resultMort = MyPDO::pdo()->prepare("DELETE FROM perso WHERE id=:id");
                    $idSucces = $resultMort->bindValue(':id',$enfant, PDO::PARAM_INT);
                    $resultMort->execute();
                }

            }
        }
        return $tabParentEnfant;
    }

    /**
    *\fn public function initArbre() : void
    * \brief Créer l'arbre généalogique sous forme de liste en HTML. Initialise la racine puis lance la création de chaque fraterie de manière récursive.
    */
    public function initArbre() : void {
        //premier appel retire les personnages morts sans enfants pour alleger l'arbre
        $tabParentEnfant = $this->cherchePersoArbre();
        //deuxieme appel met à jour le tableau $tabParentEnfant sans les morts que l'on a supprimés
        $tabParentEnfant = $this->cherchePersoArbre();

        //ici débute la création de l'arbre
        echo '<div class="tree"> <ul> <li>';

        //on crée d'abord le personnage racine
        $persoRacine = 1;
        $class = $this->chercheClassePerso($persoRacine);
        echo'<a '.$class.' href="../pageDeLancement/lancement.php?refresh=0&id=1"><img src="../imagesPersos/H1.png"></a>';

        //la suite de l'arbre est créer de maniere récursive
        $this->remplissageArbre($persoRacine,$tabParentEnfant);

        //on referme l'arbre
        echo '</li>  </ul>  </div>';
    }

}
