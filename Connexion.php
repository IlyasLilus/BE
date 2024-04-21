<?php
session_start(); // Démarrer la session

$host = 'localhost';
$db = 'Compte';
$user = 'postgres';
$pass = '123';
$port = '5432';
$dsn = "pgsql:host=$host;port=$port;dbname=$db";

if (isset($_POST['connect'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT Userpassword FROM Utilisateur WHERE Pseudonyme = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && $password === $user['userpassword']) {
            // Redirection si le mot de passe est correct
            header("Location: Contact.php");
            exit;
        } else {
            // Affichage d'un message d'erreur si le mot de passe ou le nom d'utilisateur est incorrect
            echo '<p class="erreur">Username ou password incorrect</p>';
        }
    } catch (PDOException $e) {
        $error = "Erreur de connexion à la base de données : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style/style_connexion.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@800&family=Teko:wght@300;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="auth_mobile.css" media="(max-width:900px)">
</head>

<body>
    <header class="header">
        <nav>
            <ul>
                <li class="go"><a href="Accueil.php">Accueil</a></li>
                <li class="go"><a href="Contact.php">Support</a></li>
                <li class="go"><a href="">About</a></li>
            </ul>
        </nav>
        <div class="cont-header">
            <div href=""><a href="#home"><img class="logo" src="image/netvision.png" alt=""></a></div>
            <button class="connexion"><a href="">Connexion</a></button>
            <button class="commencer"><a href="">Commencer</a></button>
        </div>
    </header>




    <h1 class="title">Bienvenue</h1>
    <h1 class="title2">à nouveau!</h1>


    <div class="container">
        <div class="formulaire">
            <form id="fom" action="" method="post">

                <div>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div>
                    <input class="password" placeholder="Mot de passe" name="password" type="password" required>
                </div>

                <p class="membre"> Pas de compte? <a class="connect" href="">Inscrivez Vous</a></p>
                <input type="submit" class="submit" name="connect" value="Se connecter">
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