<?php
session_start();
if (isset($_POST["submit"])) {
    $host = 'localhost';
    $db = 'Compte';
    $user = 'postgres';
    $pass = '123';
    $port = '5432';
    $username = $_POST["username"];
    $mail = $_POST["mail"];
    $password1 = $_POST["password1"];
    $password2 = $_POST["password2"];
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $role = "user";
    try {
        if ($password1 != $password2) {
            echo '<p class="erreur">Mot de passe incorrect</p>';
        } else {
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO Utilisateur (Pseudonyme, Userpassword , adressemail ,Userrole) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            // Exécution de la requête avec les valeurs récupérées à partir de $_POST
            $stmt->execute([$username, $password1, $mail, $role]);
            session_destroy();
            echo '<p class="success">Votre compte est créé avec succès</p>';
            echo '<script>
                setTimeout(function(){
                    window.location.href = "Connexion.php";
                }, 3000);
              </script>';
        }
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="style/style_inscription.css">
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com"> -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@800&family=Teko:wght@300;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="mobile_anim.css" media="(max-width:900px)">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"> -->
</head>

<body>
    <header class="header" id="header">
        <nav>
            <ul>
                <li class="go"><a href="">Accueil</a></li>
                <li class="go"><a href="Connexion.php">Support</a></li>
                <li class="go"><a href="">About</a></li>
            </ul>
        </nav>
        <div class="cont-header">
            <div href=""><a href="Inscription.php"><img class="logo" src="image/netvision.png" alt=""></a></div>
            <button class="connexion"><a href="Connexion.php">Connexion</a></button>
            <button class="commencer"><a href="Inscription.php">S'inscrire</a></button>
        </div>
    </header>

    <h1 class="title">Bienvenue sur notre</h1>
    <h1 class="title2">Plateforme!</h1>


    <div class="container">
        <div class="formulaire">
            <form id="fom" action="" method="post">

                <div>
                    <input class="username" type="text" placeholder="Nom d'utilisateur" name="username" required>
                </div>
                <div>
                    <input class="date" type="email" name="mail" placeholder="Email" required>
                </div>
                <div>
                    <input class="password" placeholder="Mot de passe" name="password1" type="password" autocomplete="current-password" required>
                    <input type="password" name="password2" placeholder="confirmer votre mot de passe" name="passc" required>
                </div>
                <div id="publicite">
                    <input type="checkbox" name="check">
                    <label for="check">J’accepte de recevoir des mails commerciaux et publicitaires de la part de FootGenius</label>
                </div>
                <p class="membre">Déjà membre? <a class="connect" href="">Connectez Vous</a></p>
                <input type="submit" class="submit" name="submit" value="S'inscrire">
            </form>
        </div>
    </div>
    <footer>
        <div class="footer">
            <p>Copyright NetVision - Mention légal -Politique de confidentialité</p>
        </div>

    </footer>
</body>

</html>