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

function getNewNexthop($router, $idObject) {
    global $dsn, $user, $pass;
    // Création de l'objet PDO pour la connexion
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT nexthop FROM route WHERE nexthop = :router AND id_object = :idObject");
    $stmt->execute(['router' => $router, 'idObject' => $idObject]);
    $newRouter = $stmt->fetchColumn();

    if ($newRouter === false || $newRouter == $router) {
        echo "Aucun nouveau nexthop trouvé ou boucle détectée<br>";
        return false;  // Retourne false pour signaler qu'aucun nouveau nexthop valide n'a été trouvé
    }

    echo "Nouveau nexthop : $newRouter<br>";
    return $newRouter;  // Retourne le nouveau nexthop si trouvé
}



function ipMatch($ipDestination, $ipSource, $mask) {
    // Convertir l'adresse IP de destination en binaire
    $ipDestinationBinary = ip2long($ipDestination);

    // Convertir l'adresse IP source en binaire
    $networkBinary = ip2long($ipSource);

    // Calculer le masque de sous-réseau en binaire
    $subnetMaskBinary = ~((1 << (32 - $mask)) - 1);

    // Vérifier si l'adresse IP de destination est dans le réseau spécifié
    if (($ipDestinationBinary & $subnetMaskBinary) == ($networkBinary & $subnetMaskBinary)) {
        return true;
    } else {
        return false;
    }
}

function findAndSaveNextHops($idObject, $destinationIP) {
    global $dsn, $user, $pass;
    
    $nextHops = [];  // Tableau pour stocker tous les nexthops rencontrés

    // Création de l'objet PDO pour la connexion
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Obtenez le premier nexthop
    $stmt = $pdo->prepare("SELECT nexthop FROM route WHERE id_object = :idObject");
    $stmt->execute(['idObject' => $idObject]);
    $router = $stmt->fetchColumn();

    if ($router) {
        $nextHops[] = $router;  // Ajoute le premier nexthop au tableau
        echo "Nexthop initial : $router<br>";
    }

    while ($router != $destinationIP) {
        // Vérifier si l'une des interfaces correspond
        $interfaces = $pdo->query("SELECT name, ip, mask, id_object FROM interface WHERE id_object = $idObject");
        $matchFound = false;
        foreach ($interfaces as $interface) {
            if ($interface['ip'] == $router) {
                echo "Interface correspondante trouvée : " . $interface['name'] . "<br>";
                $matchFound = true;
                break;
            }
        }

        if ($matchFound) {
            break;
        }

        // Obtenez un nouveau nexthop si disponible
        $newRouter = getNewNexthop($router, $idObject);

        if ($newRouter === false) {  // Gestion de la boucle ou du non-trouvé
            break;
        }

        $router = $newRouter;
        $nextHops[] = $newRouter;  // Ajoute le nouveau nexthop au tableau
    }

    // Création du fichier avec tous les nexthops
    $file = 'nexthops.txt';
    file_put_contents($file, implode("\n", $nextHops));
    echo "Fichier créé avec succès : $file<br>";

    // Lien pour télécharger le fichier
    echo "<a href='$file'>Télécharger les nexthops</a>";
}


function main($idDatagramme) {
    // Récupérer les informations du datagramme
    $datagramme = get_datagramme($idDatagramme);
    if (!$datagramme) {
        return "Erreur : Datagramme non trouvé.";
    }

    $TTL = $datagramme['TTL'];
    $ipSource = $datagramme['ipSource'];
    $ipDestination = $datagramme['ipDestination'];

    // Récupérer l'objet actuel basé sur l'IP source
    $objetActuel = get_objet($ipSource);
    if (!$objetActuel) {
        return "Erreur : Objet non trouvé pour l'IP source spécifiée.";
    }

    // Vérifier et parcourir la route jusqu'à la destination
    while ($TTL > 0) {
        $routes = get_table($objetActuel);
        if (empty($routes)) {
            return "Erreur : Table de routage vide ou aucune route disponible.";
        }

        $foundMatch = false;
        foreach ($routes as $route) {
            if (ipMatch($ipDestination, $route[0], $objetActuel['masque'])) {
                $newRouter = getNewNexthop($route[1], $objetActuel['id']);
                if ($newRouter === false) {
                    break; // Aucun nouveau nexthop trouvé ou boucle détectée
                }
                $objetActuel = get_objet($newRouter); // Mise à jour de l'objet actuel
                $foundMatch = true;
                break;
            }
        }

        if (!$foundMatch) {
            return "Erreur : Aucune correspondance de route trouvée.";
        }

        if ($objetActuel['ip'] == $ipDestination) {
            return "Le datagramme est arrivé à destination.";
        }

        $TTL--;
    }

    if ($TTL == 0) {
        return "Erreur : Le datagramme a expiré.";
    }
    return "Erreur inconnue";

}
?>