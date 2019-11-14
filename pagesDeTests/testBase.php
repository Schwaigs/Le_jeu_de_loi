
    <?php
    session_start();
    if (!isset($_SESSION['argent']) || empty(htmlspecialchars($_SESSION['argent']))){
        $_SESSION['argent'] = 20;
    }
    if (!isset($_SESSION['satisfaction']) || empty(htmlspecialchars($_SESSION['satisfaction']))){
        $_SESSION['satisfaction'] = 50;
    }
        const SQL_DSN      = 'mysql:host=mysql.iutrs.unistra.fr;dbname=T3';
        const SQL_USERNAME = 'schwaiger';
        const SQL_PASSWORD = '4cpkbi06';

        $pdo = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $idEvent = rand(1, 9);
        $result = $pdo->prepare("SELECT * FROM evenements WHERE id = :idEvent");
        $parametrage = $result->bindValue(':idEvent', $idEvent, PDO::PARAM_INT);
        $execution = $result->execute();

        foreach ( $result as $row ) {
          echo $row['texte'] . '<br>';
          switch ($row['categorie']) {
              case "Naissance":
                    //Fonctionne pas car probleme de chemin etc
                    require_once 'le_jeu_de_lois/accesBDD/classesPHP/Personnage.php';
                    $perso = new Personnage();
                    $perso->creerPersonnage();

                    //Chercher le dernier personnage ajouté
                    $result2 = $pdo->prepare("SELECT * FROM personnage WHERE id = (SELECT max(id) FROM personnage)");
                    $execution2 = $result2->execute();
                      foreach ( $result2 as $row2 ) {
                        echo '  <div>
                                  <br>
                                  <p>Identifiant :'.$row2['id'].' </p>
                                </div>

                                <div class="ligne1">
                                    <p>Parent : '.$row2['parent'].'</p>
                                    <p>Sexe : '.$row2['sexe'].'</p>
                                    <p>Age : '.$row2['age'].'</p>
                                    <p>Ordre de Naissance : '.$row2['ordreNaissance'].'</p>
                                </div>
                                <div class="ligne2">
                                    <p>Religion : '.$row2['religion'].'</p>
                                    <p>Pays : '.$row2['nationnalite'].'</p>
                                    <p>Etat de sante : '.$row2['etatSante'].'</p>
                                    <p>Vivant : '.$row2['estEnVie'].'</p>
                                </div>
                            ';
                        }
                      break;


              case "Mort":

                  //Fonctionne pas car probleme de chemin etc
                  require_once 'le_jeu_de_lois/accesBDD/classesPHP/Personnage.php';
                  $perso = new Personnage();
                  $nbMorts = $perso->mortPerso();
                  echo $nbMorts . " personnes de votre famille ont été emporté par la mort...";
                  break;


              case "Choix":
                  $_SESSION['choix'] = $row;
                  echo '<form action="choix.php" method="POST" name="formChoix">';
                  echo    '<input type="radio" name="choix" value="oui"> Oui <br>
                           <input type="radio" name="choix" value="non"> Non <br>
                        <input type="submit" value="Valider"> <br>';
                  break;


              default:
                  $_SESSION['argent'] += $row['argent'];
                  $_SESSION['satisfaction'] += $row['satisfaction'];
          }



          echo "J'ai " . $_SESSION['argent'] . " pièces d'or" . "<br>";
          echo "Mon peulple est satisfait d'une valeur de : " . $_SESSION['satisfaction'];
        }
