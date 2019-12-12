<?php

require_once '../accesBDD/bddT3.php';
require_once '../accesBDD/MyPDO.php';

/*
* \class CtrlLoi
* \brief Permet de gèrer l'ensemble des lois du jeu.
 */
class CtrlLoi {

    public function __construct(){
    }

    /**
    *\fn public function afficheLoiEnPlace() : void
    * \brief Rempli le panneau d'affichage des lois qui sont mises en place actuellement.
    */
    public function afficheLoiEnPlace() : void {
        $result = MyPDO::pdo()->prepare("SELECT * FROM lois WHERE misEnPlace=1");
        $ok2 = $result->execute();
        /*Si aucune loi n'est mise en place alors un message par défaut est affiché*/
        if (0 == $result->rowCount()){
            echo '<p>Degrés de parenté</p>';
        }
        else{
            echo '<p>Degrés de parenté</p>';
            /*Afficher la liste des lois que l'utilistateur a choisit */
            foreach ( $result as $row ) {
                echo '<p>'. $row['label'] . '</p>';
            }
        }
    }

    /**
    *\fn public function remplissageVote() : void
    * \brief Rempli la zone de vote du joueur. Deux listes en accordéons par catégories sont créés :
    * \li Une pour abroger les lois qui sont mises en place.
    * \li Une pour voter les lois dont aucune de la catéorie n'est est en place.
    */
    public function remplissageVote() : void {
        $result = MyPDO::pdo()->prepare("SELECT * FROM lois WHERE misEnPlace=1");
        $ok = $result->execute();
        //On sauvegarde dans un tableau les différentes catégories de loi sur lesquels on peut agir
        $tabParam=['religion'=>'Religion','sexe'=>'Sexe','ordreNaissance'=>'Ordre de naissance','richesse'=>'Richesse','sante'=>'Santé'];
        $i=1;
        echo"<p> Vous devez choisir de modifier une seule des lois suivantes </p>";
        echo"<h2> Abroger une Loi </h2>";
        if (0 != $result->rowCount()){
            //On créer une liste accordéon pour les catégories de loi où l'une d'elles est déjà en place
            echo '<ul id="accordion_supp">';
            foreach ($result as $row){
                //Le champs valeur contient les informations à envoyer avec le formulaire
                //On concatène la catégorie et la caractéristique sur laquelle porte la loi mais aussi qu'il s'agit d'une suppréssion de loi existante
                $value='supp '.$row['parametre'].' '.$row['paramVal'];
                /*On supprime du tableau tabParam la catégorie de cette loi
                car si elle est déjà en place alos aucune autre de cette catégorie ne doit pouvoir être votée
                Ainsi la catégorie ne sera pas présente dans le second accordéon*/
                $paramLabel = $tabParam[$row['parametre']];
                unset($tabParam[$row['parametre']]);
                //enfin on créer notre élément de la liste accordéon
                echo '<li>
                    <label for="menu'.$i.'">'.$paramLabel.'</label>
                    <input id="menu'.$i.'" type="checkbox" name="menu"/>
                    <ul class="accordion_loi">
                        <li><input type="radio" name="Lois" value="'.$value.'">'.$row['label'].'</li>
                    </ul>
                </li>';
                $i++;
            }
            echo '</ul>';
        }
        //Si aucune loi n'est en place on affiche un texte par défaut
        else{
            echo"<p>Aucune loi n'étant mise en place vous ne pouvez pas en abroger.</p>";
        }
        echo"<h2> Voter une Loi </h2>";
        if(!empty($tabParam)){
            //On créer une liste accordéon pour les catégories de loi pour lesquelles aucune n'est en place
            echo'<ul id="accordion_ajout">';
            //On passe en revue les catégories qui ne sont pas dans le premier accordéon
            foreach ($tabParam as $param => $paramLabel){
                //On créer une liste par catégorie
                echo' <li>
                <label for="menu'.$i.'">'.$paramLabel.'</label>
                <input id="menu'.$i.'" type="checkbox" name="menu"/>
                <ul class="accordion_loi">';
                $result = MyPDO::pdo()->prepare("SELECT * FROM lois WHERE parametre=:param order by label");
                $paramSucces = $result->bindValue(':param',$param, PDO::PARAM_STR);
                $ok2 = $result->execute();
                foreach ( $result as $row ) {
                    //Le champs valeur contient les informations à envoyer avec le formulaire
                    //On concatène la catégorie et la caractéristique sur laquelle porte la loi mais aussi qu'il s'agit d'un ajout de loi
                    $value='ajout '.$param.' '.$row['paramVal'];
                    //enfin on affiche une loi dans la sous-liste de sa catégorie
                    echo'<li><input type="radio" name="Lois" value="'.$value.'">'.$row['label'].'</li>';
                }
                echo'</ul>
                    </li>';
                $i++;
            }
            echo '</ul>';
        }
        //Si une loi est en place pour chaque catégorie on affiche un texte par défaut
        else{
            echo"<p>Vous avez déjà mis en place une loi pour chaque caractéritique. Vous ne pouvez donc pas en voter une nouvelle.</p>";
        }
    }

}
