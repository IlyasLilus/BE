<?php
$host = 'localhost';
$db = 'Compte';
$user = 'postgres';
$pass = '123';
$port = '5432';
$dsn = "pgsql:host=$host;port=$port;dbname=$db";

function create_datagramme($TTL, $protocole, $ipSource, $ipDestination)
{
    global $dsn, $user, $pass;

    try {
        // La Création de l'objet PDO pour la connexion
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparation de la requête SQL pour créer un nouveau datagramme
        $stmt = $pdo->prepare("INSERT INTO datagramme (TTL, protocole, ipSource, ipDestination) VALUES (?, ?, ?, ?)");

        // Exécution de la requête 
        $stmt->execute([$TTL, $protocole, $ipSource, $ipDestination]);

        // Récupération de l'ID du datagramme 
        $lastId = $pdo->lastInsertId();
        echo "Datagramme créé avec succès. ID: " . $lastId;
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    } catch (Exception $e) {
        die("Erreur: " . $e->getMessage());
    }
}

//-------------------------------------------------------
function get_datagramme($idDatagramme)
{
    global $dsn, $user, $pass;

    try {
        // Création de l'objet PDO pour la connexion
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparation de la requête SQL
        $stmt = $pdo->prepare("SELECT TTL, protocole, ipSource, ipDestination FROM Datagramme WHERE idDatagramme = ?");
        $stmt->execute([$idDatagramme]);

        // Récupération des résultats
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Retourner le datagramme 
            return $result;
            // return "TTL : " . $result['ttl'] . " Protocole : " . $result['protocole'] . " Ipsource : " . $result['ipsource'] . " Ipdestination : " . $result['ipdestination'];
        } else {
            throw new Exception("Aucun datagramme trouvé avec l'ID spécifié.");
        }
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    } catch (Exception $e) {
        die("Erreur: " . $e->getMessage());
    }
}

function get_objet($ipSource)
{
    global $dsn, $user, $pass;

    try {
        // Création de l'objet PDO pour la connexion
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparation de la requête SQL pour récupérer l'objet 
        $stmt = $pdo->prepare("SELECT * FROM Objet WHERE ipobjet = ?");

        // Exécution de la requête avec l'adresse IP fournie
        $stmt->execute([$ipSource]);

        // Récupération du résultat
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
                // "ID :" . $result["idobjet"] .
                // " Nom: " . $result['nomobjet'] .
                // ", Position: (" . $result['xobjet'] . ", " . $result['yobjet'] . ")" .
                // ", Masque: " . $result['masqueobjet'];

        } else {
            return null; // Aucun objet trouvé pour cette adresse IP
        }
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }
}

function get_table($objetActuel)
{
    global $dsn, $user, $pass;

    try {
        // Création de l'objet PDO pour la connexion
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparation de la requête SQL pour récupérer les routes associées à un objet
        $stmt = $pdo->prepare("SELECT destination, nexthop, interface FROM Route WHERE idobjet = ?");

        // Exécution de la requête avec l'ID de l'objet fourni
        $stmt->execute([$objetActuel]);
        // Collecte des routes dans une liste de listes
        $routes = [];
        if ($stmt->rowCount() > 0) {

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $routes[] = [$row['destination'], $row['nexthop'], $row['interface']];
            }
            return $routes;
        } else {
            echo "La table de routage est vide"; // Retourne un tableau vide si aucun résultat n'est trouvé
            return  [];
        }
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }
}

// Exemple d'uti
// Exemple d'utilisation de la fonction
// create_datagramme(20, 'UDP', '10.10.2.3', '10.10.2.5');
$result = get_datagramme(2); 
print_r($result);
echo '<br>';

$ob=get_objet('10.10.10.5');
print_r($ob);
echo '<br>';
$routes = get_table(1);
foreach ($routes as $route) {
    echo "Destination : " . $route[0] . ", Next Hop : " . $route[1] . ", Interface : " . $route[2] . "<br>";
    }
?>