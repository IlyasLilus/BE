<?php
$host = 'localhost';
$db = 'Compte';
$user = 'postgres';
$pass = '123';
$port = '5432';
$dsn = "pgsql:host=$host;port=$port;dbname=$db";




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

function get_next_objet($route,$objetActuel){
    //TODO
    //Utilise une route[ipDestination, nexthop, Infetrface] et l'id d'un objet pour trouver l'id de l'objet suivant
    //verifier que les objets soient bien connectés
}

function ipMatch($ipDestination, $route){
    //TODO
    //Utilise une adresse IP de destination finale et un réseau de destination contenu dans la table de routage
    // renvoie vrai si l'adresse est dans le réseau, c'est à dire que cette route est la bonne
}

function main($idDatagramme){
    $datagramme[] = get_datagramme($idDatagramme);
    $TTL = $datagramme[0];
    $protocole = $datagramme[1];
    $ipSource = $datagramme[2];
    $ipDestination = $datagramme[3];
    
    $objetActuel = get_objet($ipSource);
    $estArrive = false;
    $foundmatch = false;

    //Boucle de parcours du chemin vers la déstination
    while($TTL > 0 and !$estArrive){
        $table = get_table($objetActuel);
        foreach ($table as $route){
            if (ipMatch($ipDestination, $route[0])){
                $objetActuel = get_next_objet($route,$objetActuel);
                //Sortie LOG -> BD table transporter 
                $foundmatch = true;
                break;
            }
        }
        if (!$foundmatch){ // cas ou aucune route n'est possible
            return "Erreur : Pas de route trouvée";
        }else{
            $foundmatch = false;
        }

        if ($objetActuel == $ipDestination){
            $estArrive = true;
        }
        $TTL--;
    }

    if ($estArrive){
        return "Le datagramme est arrivé à destination";
    }
    if ($TTL == 0){
        return "Erreur : Le datagramme a expiré";
    }
    return "Erreur inconnue";

}
?>