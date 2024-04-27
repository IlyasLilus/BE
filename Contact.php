<?php
session_start();

if (isset($_POST["submit"])) {

    $host = 'localhost';
    $db = 'Compte';
    $user = 'postgres';
    $pass = '123';
    $port = '5432';
    $nom = $_POST['name'];
    $email = $_POST['email'];
    $number=$_POST['number'];
    $message = $_POST['message'];  

    $dsn = "pgsql:host=$host;port=$port;dbname=$db";

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO TicketSupport (NomUtilisateur, EmailUtilisateur, dateTicket) VALUES (?, ?, CURRENT_TIMESTAMP)";
        $stmt = $pdo->prepare($sql); 

        $stmt->execute([$nom, $email]);
        $subject = urlencode("Question de client");
        $body = urlencode("Nom: $nom\nNumber: $number\nMessage: $message");
        $adminEmail = "adminnetvision@gmail.com";  // Email du destinataire administrateur
        $mailto = "mailto:$adminEmail?subject=$subject&body=$body";
        echo '<p class="success">Merci de nous avoir contactés !</p>';
        echo "<script>window.open('$mailto', '_blank');</script>";

    } catch (PDOException $e) {
        echo '<p class="erreur">Contact echouee</p>';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="style/style_contact.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@800&family=Teko:wght@300;600&display=swap" rel="stylesheet">
</head>

<body>
    <style>
        .success {
            color: green;
            font-size: 25px;
            font-weight: bold;
            position: absolute;
            top: 75%;
            left: 43%
        }

        .erreur {
            color: red;
            font-size: 25px;
            font-weight: bold;
            position: absolute;
            top: 75%;
            left: 43%
        }
    </style>
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
            <button class="connexion"><a href="Connexion.php">Connexion</a></button>
            <button class="commencer"><a href="Inscription.php">S'inscrire</a></button>
        </div>
    </header>
    <section id="Section1">
        <div class="container3">
            <div class="formulaire">
                <h3><span class="c">C</span>ontactez-nous</h3>
                <form id="fom" method="post">
                    <input class="nom-fa" type="text" placeholder="Nom" name="name" required>
                    <input class="email" type="text" placeholder="Prenom" name="text" required>
                    <input class="num" type="text" placeholder="Num tel" name="number" required>
                    <input class="date" type="email" name="email" placeholder="email" required>
                    <textarea name="message" cols="20" rows="5" placeholder="Votre messsage"></textarea>
                    <input type="submit" class="submit" name="submit" value="Envoyer">

                </form>
            </div>
            <div class="form-img">
                <img class="img3" src="image/imgMail.svg" alt="">
            </div>
        </div>
    </section>
    <footer>
        <div class="footer">
            <p>Copyright NetVision - Mention légal -Politique de confidentialité</p>
        </div>

    </footer>
</body>

</html>