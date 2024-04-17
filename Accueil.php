<php>
<!DOCTYPE html>
<html>
<head>
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="style/style_accueil.css">
</head>
<body>
    <img class="background" src="image/alexander-andrews-fsH1KjbdjE8-unsplash 1.png" alt="Background">
    <section class="filter"></section>
    <header>
    <section class="header-container">
            <div class="navbar-left"></div>
                <div><a href="Accueil.php">Accueil</a></div>
                <div><a href="About.php">About</a></div>
                <div><a href="Support.php">Support</a></div>
            </div>
            <div class="navbar-mid">
                <img class="logo" src="image/Logo.png" alt="Logo">
            </div>
            <div class="navbar-right">
                <a href="Accueil_Utilisateur.php">
                    <button class="Compte">Mon compte</button>
                </a>
            </div>
    </section>
    </header>
    <section class="Description">
        <div>Simulations de routage avec NetVision<br>
            NetVision est l’outil le plus <span class ="Description-highlight">performant</span> de sa  <span class ="Description-highlight">génération<span></div>
    </section>
    <section>
        <img class="image1" src="image/nasa-Q1p7bh3SHj8-unsplash 1.png" alt="Image1">  
        <div class="Card1">
        </div>
        <div class="Card2">

        </div>
        <div class="Card3">

        </div>
        <button class="Decouvrir">Découvrir Maintenant !</button>
        
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
        <button class="Commencer">Commencer à simuler</button>
    </section>
    <section id="reviews">
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

    <section class="review-box active" id="Client1">
        <div class="Client1-Box"></div>
        <div class="Client1-Name">Ashraf Belhiss</div>
        <div class="Client1-Location">Toulouse, France</div>
        <div class="Client1-Text">C’est l’application teh les fou wallah</div>
        <img class="Client1-image" src="image/1698328915992.jpg" alt="Client1">
    </section>
    <section class="review-box" id="Client2">
        <div class="Client1-Box"></div>
        <div class="Client1-Name">André Aoun</div>
        <div class="Client1-Location">Toulouse, France</div>
        <div class="Client1-Text">Ce projet mérite un 20/20</div>
        <img class="Client1-image" src="image/1698328915992.jpg" alt="Client1">
    </section>
    
    <section class="Sponsors">
        <div class="SponsorTitle">Nos collaborateur</div>
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