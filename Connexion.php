<?php 
if (isset($_POST['connect'])){
    
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
                    <li class="go"><a href="About.php">About</a></li>
                </ul>
            </nav>
        <div class="cont-header">
            <div><a href="#home"><img class="logo" src="image/netvision.png" alt=""></a></div>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                    <button class="commencer"><a href="Acceuil_Utilisateur.php" >Mon compte</a></button>
                <?php else: ?>
                    <button class="connexion"><a href="Connexion.php" >Connexion</a></button>
                    <button class="commencer"><a href="Inscription.php" >S'inscrire</a></button>
            <?php endif; ?> 
        </div>
    </header>
  



    <h1 class="title">Bienvenue</h1>
    <h1 class="title2">à nouveau!</h1>

  
        <div class="container" >
             <div class="formulaire">
                  <form id="fom" action="" method="post">
            
                    <div>
                        <input class="date" type="email" name="mail" placeholder="Email" required>
                    </div>
                    <div>
                        <input class="password"  placeholder="Mot de passe" name="password" type="password" required>
                    </div>
                       
                        <p class="membre">  Pas de compte? <a class="connect" href="">Inscrivez Vous</a></p>
                       <input type="submit" class="submit" name="connect" value="Se connecter">
                  </form>
             </div>
        </div>
        <footer>
        <div class="footer">
                <p>Copyright NetVision   - Mention légal -Politique de confidentialité</p>
        </div>
            
        </footer>
</body>

</html>