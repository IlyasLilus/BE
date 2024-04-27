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
        switch ($data['action']) {
            case 'add_object':
                if (isset($data['name'], $data['ip'], $data['mask'], $data['type'], $data['x'], $data['y'])) {
                    echo add_object($dsn, $user, $pass, $data['name'], $data['ip'], $data['mask'], $data['type'], $data['x'], $data['y']);
                } else {
                    echo "Missing one or more required parameters for adding object.";
                }
                break;
            case 'del_object':
                echo del_object($dsn, $user, $pass, $data['id']);
                break;
            case 'edit_object':
                echo edit_object($dsn, $user, $pass, $data['id'], $data['name'], $data['ip'], $data['mask']);
                break;
            case 'move_object':
                echo move_object($dsn, $user, $pass, $data['id'], $data['x'], $data['y']);
                break;
            case 'add_connection':
                echo add_connection($dsn, $user, $pass, $data['idObjetA'], $data['idObjetB'], $data['InterfaceA'], $data['InterfaceB']);
                break;
            case 'del_connection':
                echo del_connection($dsn, $user, $pass, $data['idObjetA'], $data['idObjetB']);
                break;
            case 'del_connections':
                echo del_connections($dsn, $user, $pass,$data['idObjetA']);
                break;
            case 'add_route':
                echo add_route($dsn, $user, $pass, $data['idObjet'], $data['Destination'], $data['nexthop'], $data['Interface']);
                break;
            case 'del_route':
                echo del_route($dsn, $user, $pass, $data['idRoute']);
                break;
            case 'add_datagramme':
                echo add_datagramme($dsn, $user, $pass, $data['TTL'], $data['protocole'], $data['SourceData'], $data['Destination']);
                break;
            case 'del_datagramme':
                echo del_datagramme($dsn, $user, $pass, $data['idDatagramme']);
                break;
            case 'main':
                echo main($data['idDatagramme']);
                break;
            default:
                echo "Invalid function.";
        }
    }

    function add_object($dsn, $user, $pass, $name, $ip, $mask, $type, $x, $y){
        $conn = new PDO($dsn, $user, $pass);
        $sql = "INSERT INTO objet (typeObjet, NomObjet, xObjet, yObjet, dateObjet, IpObjet, masqueObjet) 
                VALUES ('$type', '$name', $x, $y, now(), '$ip', '$mask') 
                RETURNING IdObjet";
        $result = $conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $idObjet = $row['IdObjet'];
        
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

    function get_datagramme($idDatagramme){
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

    function get_next_objet($route, $objetActuel) {
        global $pdo;
    
        // Vérifier si l'élément $route[1] est défini
        if(isset($route[1])) {
            $nexthop = $route[1];  // Supposant que 'nexthop' est le deuxième élément de $route.
    
            $query = "SELECT IdObjet FROM Objet WHERE IpObjet = :nexthop";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':nexthop', $nexthop);
            $stmt->execute();
    
            // Vérifier les erreurs dans l'exécution de la requête SQL
            if ($stmt->errorCode() !== '00000') {
                $errorInfo = $stmt->errorInfo();
                echo "Erreur SQL : " . $errorInfo[2];
                return null;
            }
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result['IdObjet'];  // Retourne l'ID de l'objet suivant si trouvé.
            } else {
                return null;  // Retourne null si aucun objet correspondant n'est trouvé.
            }
        } else {
            // Si $route[1] n'est pas défini, retourner null
            return null;
        }
    }
    
    function ipMatch($ipDestination, $route){
        // Extraire l'adresse IP et le masque de sous-réseau de la route
        $routeParts = explode('/', $route);
        $network = $routeParts[0];
        $subnetMask = $routeParts[1];
    
        // Convertir l'adresse IP de destination en binaire
        $ipDestinationBinary = ip2long($ipDestination);
    
        // Extraire l'adresse réseau de destination en binaire
        $networkBinary = ip2long($network);
    
        // Calculer le masque de sous-réseau en binaire
        $subnetMaskBinary = ~((1 << (32 - $subnetMask)) - 1);
    
        // Vérifier si l'adresse IP de destination est dans le réseau spécifié
        if (($ipDestinationBinary & $subnetMaskBinary) == ($networkBinary & $subnetMaskBinary)) {
            return true;
        } else {
            return false;
        }
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