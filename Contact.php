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
            <button class="connexion"><a href="Connexion.php" >Connexion</a></button>
            <button class="commencer"><a href="Inscription.php" >Commencer</a></button>
        </div>
    </header>
    <section id="Section1">
        <div class="container3" >
             <div class="formulaire">
                  <h3><span class="c">C</span>ontactez-nous</h3>
                  <form id="fom" action="mailto:youssef.elmirr@gmail.com?subject=question Client &body=" method="post" enctype="text/plain">
                       <input class="nom-fa" type="text" placeholder="Nom" name="name">
                       <input class="email" type="text" placeholder="Prenom" name="text">
                       <input class="num" type="text" placeholder="Num tel" name="number">
                       <input class="date" type="email" name="date" placeholder="Email" required>
                       <textarea name="message"  cols="20" rows="5" placeholder="Votre messsage"></textarea>
                       <input type="submit" class="submit" value="Envoyer">
                       
                  </form>
             </div>
             <div class="form-img">
                  <img class="img3" src="image/imgMail.svg" alt="">
             </div>
        </div>
   </section>
        <footer>
            <div class="footer">
                <p>Copyright NetVision   - Mention légal -Politique de confidentialité</p>
            </div>
            
        </footer>
</body>

</html>