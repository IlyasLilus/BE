<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="style/style_accueil.css">
</head>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@800&family=Teko:wght@300;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Volkhov:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

<body>
    <img class="background" src="image/alexander-andrews-fsH1KjbdjE8-unsplash 1.png" alt="Background">
    <section class="filter"></section>
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
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) : ?>
                <button class="commencer"><a href="Acceuil_Utilisateur.php">Mon compte</a></button>
            <?php else : ?>
                <button class="connexion"><a href="Connexion.php">Connexion</a></button>
                <button class="commencer"><a href="Inscription.php">S'inscrire</a></button>
            <?php endif; ?>
        </div>
    </header>
    <section class="Description">
        <div>Simulations de routage avec NetVision<br>
            NetVision est l’outil le plus <span class="Description-highlight">performant</span> de sa <span class="Description-highlight">génération<span></div>
    </section>
    <section class="preview">
        <img class="image1" src="image/nasa-Q1p7bh3SHj8-unsplash 1.png" alt="Image1">
        <div class="Card1">
            <img class="Card1-Image" src="image/demo1.png" alt="Card1">
        </div>
        <div class="Card2">
            <img class="Card2-Image" src="image/demo2.png" alt="Card2">
        </div>
        <div class="Card3">
            <img class="Card3-Image" src="image/.png" alt="Card3">
        </div>

    </section>
    <section class="CTAcontainer">
        <div class="CTAsupport">
            Un problème ?<br>
            <span class="CTA-highlight">Envoyez un simple mail à nos <br>
                équipes, vous recevrez une <br>
                réponse sous 24h</span>
        </div>
        <div class="CTAprobleme">
            Simulation <span class="CTA-highlight"> performante </span> et<br>
            <span class="CTA-highlight"></span> modulable </span> Utilisable par les <br>
            <span class="CTA-highlight"></span>professionnels</span> et les <span class="Description-highlight"></span>amateurs</span> <br>
        </div>
        <div class="CTAsimulation">
            <span class="CTA-highlight">SIMULATION<br>
                SUPPORT CLIENT</span><br>
            7j/7 24h/24
        </div>
        <div>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) : ?>
                <a href="Accueil_Utilisateur.php">
                    <button class="Decouvrir">Découvrir Maintenant !</button>
                </a>
            <?php else : ?>
                <a href="Connexion.php">
                    <button class="Decouvrir">Découvrir Maintenant !</button>
                </a>
            <?php endif; ?>
        </div>

    </section>
    <section class="reviews">
        <img class="image2" src="image/jj-ying-8bghKxNU1j0-unsplash 1.png" alt="Image2">
        <div class="textSatisfait">
            Des utilisateurs satisfaits
        </div>
        <section class="avis">
            <button id="prev-arrow" class="leftarrow-container">
                <img class="leftarrow" src="image/leftArrow.png" alt="leftarrow">
            </button>
            <button id="next-arrow" class="rightarrow-container">
                <img class="rightarrow" src="image/rightArrow.png" alt="rightarrow">
            </button>
            <section class="review-box active" id="Client1">
                <div class="Client1-Box"></div>
                <div class="Client1-Name">Ashraf Belhiss</div>
                <div class="Client1-Location">Toulouse, France</div>
                <div class="Client1-Text">Cette application est impréssionante</div>
                <img class="Client1-Image" src="image/1698328915992.jpg" alt="Client1">
            </section>
            <section class="review-box" id="Client2">
                <div class="Client1-Box"></div>
                <div class="Client1-Name">Ashraf Belhiss 2</div>
                <div class="Client1-Location">Toulouse, France</div>
                <div class="Client1-Text">Ce projet mérite un 20/20</div>
                <img class="Client1-Image" src="image/1698328915992.jpg" alt="Client1">
            </section>

        </section>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const prevArrow = document.getElementById('prev-arrow');
            const nextArrow = document.getElementById('next-arrow');
            const client1 = document.getElementById('Client1');
            const client2 = document.getElementById('Client2');

            client1.classList.add('review-box', 'active');
            client2.classList.add('review-box');

            prevArrow.addEventListener('click', () => {
                client1.classList.toggle('active');
                client2.classList.toggle('active');
            });

            nextArrow.addEventListener('click', () => {
                client1.classList.toggle('active');
                client2.classList.toggle('active');
            });
        });
    </script>



    <section class="Sponsors">
        <div class="SponsorTitle">Nos collaborateurs</div>
        <img class="SponsorImage" src="image/sponsors.png" alt="Sponsor1">
    </section>

    <footer>
        <section class="footer-container">
            <div class="footer-content">
                <div>Copyright NetVision©</div>
            </div>
        </section>
    </footer>
</body>