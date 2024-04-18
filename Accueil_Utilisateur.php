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
if ($role == 'Userrole') {
    $sql = "SELECT fichierProjet_miyaou 
            FROM Projet P, Utilisateur U
            WHERE P.Pseudonyme = U.Pseudonyme";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id_utilisateur]);
    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($role == 'administrateur') {
    $sql = "SELECT idTicket, RaisonTicket, dateTicket FROM TicketSupport T, Utilisateur U
            WHERE T.Pseudonyme = U.Pseudonyme";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['pseudonyme' => $id_utilisateur]);
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="style/style_accueil_utilisateur.css">
</head>
<body>
    <img class="background" src="image/alexander-andrews-fsH1KjbdjE8-unsplash 1.png" alt="Background">
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
                <button class="Deconnexion">Déconnexion</button>
            </div>
    </section>
    </header>
    <section class="Bienvenue">
        <div class="Bienvenue-container">
            <div>Bienvenue dans <span>votre<br>espace !</span></div>
        </div>
    </section>
    <section class="Projets">
        <div class="Projets-container">
            <div class="Projets-title">Mes Projets</div>
            <div class="Projets-content">
                <?php foreach ($projets as $projet): ?>
                    <div class="Projets-content-container">
                        <div class="Projets-content-container-title">Projet :</div>
                        <div class="Projets-content-container-description"><?= $projet['fichierProjet.miyaou'] ?></div>
                        <div class="Projets-content-container-button-acceder"><button>Accéder</button></div>
                        <div class="Projets-content-container-button-supprimer"><button>Supprimer</button></div>
                    </div>
                <?php endforeach; ?>
                <div class="bouton-creation-container">
                    <button class="bouton-creation">Créer un nouveau projet</button>
                </div>
            </div>
        </div>
    </section> 
    <section class="Tickets">
        <div class="Tickets-container">
            <div class="Tickets-title">Tickets</div>
            <div class="Tickets-content">
                <?php foreach ($tickets as $ticket): ?>
                    <div class="Tickets-content-container">
                        <div class="Tickets-content-container-title">Ticket ID: <?= $ticket['idTicket'] ?></div>
                        <div class="Tickets-content-container-description">Raison: <?= $ticket['RaisonTicket'] ?></div>
                        <div class="Tickets-content-container-date">Date: <?= $ticket['dateTicket'] ?></div>
                        <div class="Tickets-content-container-button-traiter"><button>Traiter</button></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <footer>
        <section class="footer-container">
            <div class="footer-content">
                <div>Copyright NetVision - Mention légal -Politique de confidentialité</div>
            </div>
        </section>
    </footer>  