<?php
session_start();
$id_utilisateur = $_SESSION['id_utilisateur'];


$host = 'localhost';
$db   = 'BE';
$user = 'postgres';
$pass = 'a';
$charset = 'utf8mb4';
$port = '5432';

$dsn = "pgsql:host=$host;dbname=$db;port=$port;user=$user;password=$pass";
$pdo = new PDO($dsn, $user, $pass);

$sql = "SELECT Userrole FROM Utilisateur WHERE pseudonyme = :pseudonyme";
$stmt = $pdo->prepare($sql);
$stmt->execute(['pseudonyme' => $id_utilisateur]);
$role = $stmt->fetchColumn();

$projets = [];
$tickets = [];
if ($role == 'User') {
    $sql = "SELECT idProjet 
            FROM Projet P
            WHERE P.pseudonyme = :pseudonyme";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['pseudonyme' => $id_utilisateur]);
    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($role == 'Admin') {
    $sql = "SELECT idTicket, RaisonTicket, dateTicket 
            FROM TicketSupport T, Utilisateur U
            WHERE T.Pseudonyme = U.Pseudonyme";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="style/style_accueil_utilisateur.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@800&family=Teko:wght@300;600&display=swap" rel="stylesheet">
</head>

<body>
    <img class="background" src="image/alexander-andrews-fsH1KjbdjE8-unsplash 1.png" alt="Background">
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
        </section>
    </header>
    <section class="Bienvenue">
        <div class="Bienvenue-container">
            <div>Bienvenue dans <span>votre<br>espace !</span></div>
        </div>
    </section>
    <?php if ($role == 'User') : ?>
        <section class="Projets">
            <div class="Projets-container">
                <div class="Projets-title">Mes Projets</div>
                <div class="Projets-content">
                    <?php foreach ($projets as $projet) : ?>
                        <div class="Projets-content-container">
                            <div class="Projets-content-container-title">Projet :</div>
                            <div class="Projets-content-container-description"><?= $projet['idprojet'] ?></div>
                            <div class="Projets-content-container-button-acceder"><button onclick = "ouvrirProjet(<?= $projet['idprojet'] ?>)">Accéder</button></div>
                            <div class="Projets-content-container-button-supprimer"><button onclick="deleteProjet(<?= $projet['idprojet'] ?>)">Supprimer</button></div>
                        </div>
                    <?php endforeach; ?>
                    <div class="bouton-creation-container">
                        <button onclick="createProjet()" class="bouton-creation">Créer un nouveau projet</button>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($role == 'Admin') : ?>
        <section class="Tickets">
            <div class="Tickets-container">
                <div class="Tickets-title">Tickets</div>
                <div class="Tickets-content">
                    <?php foreach ($tickets as $ticket) : ?>
                        <div class="Tickets-content-container">
                            <div class="Tickets-content-container-title">Ticket ID: <?= $ticket['idticket'] ?></div>
                            <div class="Tickets-content-container-description">Raison: <?= $ticket['raisonticket'] ?></div>
                            <div class="Tickets-content-container-date">Date: <?= $ticket['dateticket'] ?></div>
                            <div class="Tickets-content-container-button-traiter"><button onclick="traiterTicket(<?=$ticket['idticket']?>)">Traiter</button></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <footer>
        <section class="footer-container">
            <div class="footer-content">
                <div>Copyright NetVision - Mention légal -Politique de confidentialité</div>
            </div>
        </section>
    </footer>
    <script>
        function deleteProjet(idProjet) {
            fetch('delete_projet.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'idProjet=' + idProjet
            })
            .then(response => response.text())
            .then(text => {
            console.log(text); // Log response
            window.location.reload(); // Reload the page to update the list of projects
            }).catch(error => console.error('Error:', error));
        }
        
        function createProjet(){
            fetch('creation_projet.php', {
                method: 'POST'
            })
            .then(response => response.text())
            .then(text => {
                console.log(text); 
                window.location.reload(); // Reload the page to update the list of projects
            })
            .catch(error => console.error('Error:', error));
        }

        function traiterTicket(idTicket){
            <?php
                $query = $pdo->prepare("DELETE FROM TicketSupport WHERE idTicket = :idTicket");
                $query->bindParam('idTicket', $_POST['idTicket']);
                $query->execute();
            ?>
        }
        function ouvrirProjet(idProjet) {
            window.location.href = "Simulation.php?idProjet=" + idProjet;
        }
    </script>
</body>
