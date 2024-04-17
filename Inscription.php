<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="inscription.css">
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com"> -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@800&family=Teko:wght@300;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="mobile_anim.css" media="(max-width:900px)">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"> -->
</head>

<body>
    <header class="header">
            <nav>
                <ul>
                    <li class="go"><a href="">Accueil</a></li>
                    <li class="go"><a href="">Support</a></li>
                    <li class="go"><a href="">About</a></li>
                </ul>
            </nav>
        <div class="cont-header">
            <div href=""><a href="#home"><img class="logo" src="netvision.png" alt=""></a></div>
            <button class="connexion"><a href="" >Connexion</a></button>
            <button class="commencer"><a href="" >Commencer</a></button>
        </div>
    </header>

    <h1 class="title">Bienvenue sur notre</h1>
    <h1 class="title2">Plateforme!</h1>

  
        <div class="container" >
             <div class="formulaire">
                  <form id="fom" action="inscription_data.php" method="post">
                  
                    <div>
                            <input class="username" type="text" placeholder="Nom d'utilisateur" name="username">
                    </div>
                    <div>
                        <input class="date" type="email" name="mail" placeholder="Email" required>
                    </div>
                    <div>
                        <input class="password"  placeholder="Mot de passe" name="password" type="password">
                       <input type="password" name="password2" placeholder="confirmer votre mot de passe" name="passc">
                    </div>
                        <div id="publicite">
                            <input type="checkbox" name="check"> 
                            <label for="check">J’accepte de recevoir des mails commerciaux et publicitaires de la part de FootGenius</label>
                        </div>
                        <p class="membre">Déjà membre? <a class="connect" href="">Connectez Vous</a></p>
                        <input type="submit" class="submit" value="S'inscrire">
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