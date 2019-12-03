<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>Fin</title>
        <link rel="stylesheet" href="../css/fin.css">
    </head>
    <body>
        <form action="quitter.php" method="POST">
        <?php
            if ($_SESSION['jeu'] == 'gagne'){
                echo "<img src=../images/win.png>";
            }
            else{
                echo "<img src=../images/lose.png>";
            }
            if (!empty($_SESSION['messageFin'])){
                echo "<p> " .$_SESSION['messageFin'] ."</p>";
            }
        ?>
        <input type="submit" value="Rejouer">
    </body>
</html>