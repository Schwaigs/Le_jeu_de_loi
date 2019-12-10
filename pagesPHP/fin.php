<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>Fin</title>
        <link rel="stylesheet" href="../css/fin.css">
        <!-- Permet d'utiliser une police d'écriture au style moyenâgeux -->
        <link href="https://fonts.googleapis.com/css?family=Almendra&display=swap" rel="stylesheet">
    </head>
    <body>
        <!-- Si le joueur clique sur rejouer on lance la procédure quitter.php qui détruit sa session et le renvoie sur la page d'acceuil -->
        <form action="quitter.php" method="POST">
        <?php
            //l'image affichée change selon si le joueur a gagné ou perdu
            if ($_SESSION['jeu'] == 'gagne'){
                echo "<img src=../images/win.png>";
            }
            else{
                echo "<img src=../images/lose.png>";
            }
            //Le message affiché explique au joueur la situation qui l'a amené à la fin du jeu
            if (!empty($_SESSION['messageFin'])){
                echo "<p> " .$_SESSION['messageFin'] ."</p>";
            }
        ?>
        <input type="submit" value="Rejouer">
    </body>
</html>
