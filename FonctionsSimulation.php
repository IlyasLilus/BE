<?php
    session_start();
    $host = 'localhost';
    $db = 'BE';
    $user = 'postgres';
    $pass = 'a';
    $port = '5432';
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";


    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    
    if (isset($data['action'])) {
        $values = $data['data'];
        switch ($data['action']) {
            case 'add_object':
                if (isset($values['name'], $values['ip'], $values['mask'], $values['type'], $values['x'], $values['y'], $values['projectId'])) {
                    echo add_object($dsn, $user, $pass, $values['name'], $values['ip'], $values['mask'], $values['type'], $values['x'], $values['y'], $values['projectId']);
                } else {    
                    echo "Missing one or more required parameters for adding object. name: " . $values['name'] . "ip: " . $values['ip'] . "mask: " . $values['mask'] . "type: " . $values['type'] . "x: " . $values['x'] . "y: " .$values['y']. "projectId: " . $values['projectId'];
                }
                break;
            case 'del_object':
                echo del_object($dsn, $user, $pass, $values['id']);
                break;
            case 'edit_object':
                echo edit_object($dsn, $user, $pass, $values['id'], $values['name'], $values['ip'], $values['mask']);
                break;
            case 'move_object':
                echo move_object($dsn, $user, $pass, $values['id'], $values['x'], $values['y']);
                break;
            case 'add_connection':
                echo add_connection($dsn, $user, $pass, $values['idObjetA'], $values['idObjetB'], $values['InterfaceA'], $values['InterfaceB']);
                break;
            case 'del_connection':
                echo del_connection($dsn, $user, $pass, $values['idObjetA'], $values['idObjetB']);
                break;
            case 'del_connections':
                echo del_connections($dsn, $user, $pass,$values['idObjetA']);
                break;
            case 'add_route':
                echo add_route($dsn, $user, $pass, $values['idObjet'], $values['Destination'], $values['nexthop'], $values['Interface']);
                break;
            case 'del_route':
                echo del_route($dsn, $user, $pass, $values['idRoute']);
                break;
            case 'add_datagramme':
                echo add_datagramme($dsn, $user, $pass, $values['TTL'], $values['protocole'], $values['SourceData'], $values['Destination']);
                break;
            case 'del_datagramme':
                echo del_datagramme($dsn, $user, $pass, $values['idDatagramme']);
                break;
            case 'main':
                echo main($values['iddatagramme']);
                break;
            default:
                echo "Invalid function.";
        }
    }

    function add_object($dsn, $user, $pass, $name, $ip, $mask, $type, $x, $y, $projectId){
        $conn = new PDO($dsn, $user, $pass);
        $sql = "INSERT INTO objet (typeObjet, NomObjet, xObjet, yObjet, dateObjet, IpObjet, masqueObjet, IdProjet) 
                VALUES ('$type', '$name', $x, $y, now(), '$ip', '$mask', $projectId) 
                RETURNING IdObjet";
        $result = $conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $idObjet = $row['idobjet'];
        
        return $idObjet;
    }
    
    function del_object($dsn, $user, $pass, $idObjet){
        $conn = new PDO($dsn, $user, $pass);
        $sql = "DELETE FROM objet WHERE IdObjet = $idObjet";
        $conn->query($sql);
    }
    
    function edit_object($dsn, $user, $pass,$idObjet, $name, $ip, $mask){
        $conn = new PDO($dsn, $user, $pass);
        $sql = "UPDATE objet SET NomObjet = '$name', dateObjet = now(), IpObjet = '$ip', masqueObjet = '$mask' WHERE IdObjet = $idObjet";
        $conn->query($sql);
    }
    function move_object($dsn, $user, $pass, $idObjet, $x, $y){
        $conn = new PDO($dsn, $user, $pass);
        $sql = "UPDATE objet SET xObjet = $x, yObjet = $y WHERE IdObjet = $idObjet";
        $conn->query($sql);
    }
    
    function add_connection($dsn, $user, $pass, $idObjetA, $idObjetB, $InterfaceA, $InterfaceB){
        $conn = new PDO($dsn, $user, $pass);
        $sql = "INSERT INTO Se_connecter (IdObjetA, IdObjetB, InterfaceA, InterfaceB) 
                VALUES ($idObjetA, $idObjetB, $InterfaceA, $InterfaceB)";
        $conn->query($sql);
    
    }
    
    function del_connection($dsn, $user, $pass, $idObjetA, $idObjetB){
        $conn = new PDO($dsn, $user, $pass);
        $sql = "DELETE FROM Se_connecter WHERE IdObjetA = $idObjetA AND IdObjetB = $idObjetB OR IdObjetA = $idObjetB AND IdObjetB = $idObjetA";
        $conn->query($sql);
    
    }

    function del_connections($dsn, $user, $pass, $idObjetA){
        $conn = new PDO($dsn, $user, $pass);
        $sql = "DELETE FROM Se_connecter WHERE IdObjetA = $idObjetA OR IdObjetB = $idObjetA";
        $conn->query($sql);
    
    }
    
    function add_route($dsn, $user, $pass, $idObjet, $Destination, $nexthop, $Interface){
        $conn = new PDO($dsn, $user, $pass);
        $sql = "INSERT INTO route (IdObjet, Destination, nexthop, Interface) 
                VALUES ($idObjet, '$Destination', '$nexthop', $Interface) RETURNING IdRoute";
        $result = $conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $idRoute = $row['IdRoute'];
    
        return $idRoute;
    }
    
    function del_route($dsn, $user, $pass, $idRoute){
        $conn = new PDO($dsn, $user, $pass);
        $sql = "DELETE FROM route WHERE IdRoute = $idRoute";
        $conn->query($sql);
    }
    
    function add_datagramme($dsn, $user, $pass, $TTL, $protocole, $SourceData, $Destination){
        $conn = new PDO($dsn, $user, $pass);
        $sql = "INSERT INTO datagramme (TTL, protocole, ipSource, ipDestination) 
                VALUES ($TTL, '$protocole', '$SourceData', '$Destination') RETURNING IdDatagramme";
        $result = $conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $idDatagramme = $row['IdDatagramme'];
        
        return $idDatagramme;
    }
    
    function del_datagramme($dsn, $user, $pass, $idDatagramme){
        $conn = new PDO($dsn, $user, $pass);
        $sql = "DELETE FROM datagramme WHERE IdDatagramme = $idDatagramme";
        $conn->query($sql);
    }

    function get_datagramme($idDatagramme)
    {
        global $dsn, $user, $pass;

        try {
            // Création de l'objet PDO pour la connexion
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Préparation de la requête SQL
            $stmt = $pdo->prepare("SELECT ttl, protocole, sourcedata, destination FROM Datagramme WHERE idDatagramme = :idDatagramme");
            $stmt->bindParam(':idDatagramme', $idDatagramme);
            $stmt->execute();

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
?>