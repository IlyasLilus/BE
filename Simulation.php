<?php
    session_start();
    $host = 'localhost';
    $db = 'BE';
    $user = 'postgres';
    $pass = 'a';
    $port = '5432';
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";

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

<!DOCTYPE html>
<html>
<head>
    <title>Simulation</title>
    <link rel="stylesheet" type="text/css" href="style/style_simulation.css">
    <script src="https://cdn.jsdelivr.net/npm/interactjs@1.10.11/dist/interact.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
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
                <button class="commencer"><a href="Acceuil_Utilisateur.php" >Mon compte</a></button>
        </div>
</section>
</header>
<footer>
    <button id="datagramme-button" style="position: absolute; background-color: #B557FF; color: white; font-family: 'Poppins', sans-serif; border-radius: 76px; border: none; padding: 10px 20px; cursor: pointer; right: 120px; top: 10px">Datagramme</button>
    <button id="lancer-button" style="position: absolute; background-color: #B557FF; color: white; font-family: 'Poppins', sans-serif; border-radius: 76px; border: none; padding: 10px 20px; cursor: pointer; right: 20px; top: 10px">Lancer</button>
    <div id="wire" class="draggable"></div>
    <div id="pc" class="draggable"></div>
    <div id="router" class="draggable"></div>
</footer>

<canvas id="canvas"></canvas>

<div id="context-menu">
    <button id="configurer">Configurer...</button>
    <button id="supprimer">Supprimer</button>
    <button id="fermer">Fermer</button>
</div>

<div id="config-modal" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%;">
        <h2 id="config-title">Configuration</h2>
        <form id="config-form">
            <label for="typeLabel">Type de l'objet:</label>
            <input type="type" id="type" name="type" style="display: none;"><br>
            <label for="nom">Nom de l'objet:</label>
            <input type="text" id="nom" name="nom"><br>
            <label for="adresse-ip">Adresse IP:</label>
            <input type="text" id="adresse-ip" name="adresse-ip"><br>
            <label for="reseau" id="reseau-label" style="display: none;">Masque:</label>
            <input type="text" id="reseau" name="reseau" style="display: none;">
            <input type="submit" value="Submit">
            <button onclick="contextMenu.style.display = 'none';">Annuler</button>
        </form>
    </div>
</div>

<div id="datagramme" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%;">
        <h2>Configuration du datagramme</h2>
        <form id="datagramme-form">
            <label for="ttl">TTL:</label><br>
            <input type="number" id="ttl" name="ttl"><br>
            <label for="protocolee">Protocole:</label><br>
            <input type="text" id="protocole" name="protocole"><br>
            <label for="source">Source:</label><br>
            <input type="text" id="source" name="source"><br>
            <label for="destination">Destination:</label><br>
            <input type="text" id="destination" name="destination"><br>
            <input type="submit" value="Submit">
            <button onclick="datagrammeFenetre.style.display = 'none'">Annuler</button>
        </form>
    </div>
</div>

<script>
    // Récupération des éléments HTML et initialisation des variables
    const canvas = document.getElementById('canvas');
    const dessin = canvas.getContext('2d');
    canvas.width = window.innerWidth; // Largeur du canvas = largeur de la fenêtre
    canvas.height = window.innerHeight; // Hauteur du canvas = hauteur de la fenêtre
    const contextMenu = document.getElementById('context-menu');
    const supprimerBouton = document.getElementById('supprimer');
    const clonerBouton = document.getElementById('cloner');
    const fermerBouton = document.getElementById('fermer');
    const configBouton = document.getElementById('configurer');
    const config = document.getElementById('config-modal');
    const configFormulaire = document.getElementById('config-form');
    const configTitle = document.getElementById('config-title');
    const IPInput = document.getElementById('adresse-ip');
    const reseauInput = document.getElementById('reseau');
    const reseauLabel = document.getElementById('reseau-label');
    const typeInput = document.getElementById('type');
    const datagrammeBouton = document.getElementById('datagramme-button');
    const datagrammeFenetre = document.getElementById('datagramme');
    const ddatagrammeFormulaire = document.getElementById('datagramme-form');
    const EquipementFooter = document.querySelectorAll('footer > *');
    let ElementSelectionne = null;
    let CableSelectionne = null;
    let ElementsSelectionne = [];
    let connexions = [];
    let cableEtEquipement = {};

    function createDraggableElement(element) {
              // Clonage de l'élément sélectionné
              const clone = element.cloneNode();
        clone.classList.add('clone');

        const rect = element.getBoundingClientRect(); // Obtient les dimensions et la position de l'élément
        clone.style.position = 'absolute';
        clone.style.left = `${rect.left}px`;
        clone.style.top = `80%`;
        
        
        document.body.appendChild(clone);
        interact(clone)
            .draggable({
                inertia: true,
                autoScroll: true,
                onmove: dragMoveListener
            });

        
        contextMenu.style.display = 'none';
        // Ajout de l'élément cloné dans le tableau
        if (element.id === 'pc') {
            configTitle.textContent = 'Configuration PC';
            reseauInput.style.display = 'none';
            reseauLabel.style.display = 'none';
            config.style.display = 'block';
            typeInput.value = 'pc';
            typeInput.disabled = true;
            typeInput.style.display = 'block';
            
            
            
        } else if (element.id === 'router') {
            configTitle.textContent = 'Configuration Routeur';
            reseauInput.style.display = 'block';
            reseauLabel.style.display = 'block';
            config.style.display = 'block';
            typeInput.value = 'routeur';
            typeInput.disabled = true;
            typeInput.style.display = 'block';
        }
        //entre le clone dans la BD !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! IP ET MASK NON DEFINI à création
        sendData($name= clone.name, $ip= clone.ip,$mask = clone.mask, $x: clone.style.left, $y: clone.style.top, $type: typeInput.value);
        <?php
            $cloneid = add_object($dsn, $user, $pass, $name, $ip, $mask,$type ,$x, $y);
        ?>

        var id_bd = <?php echo json_encode($cloneid); ?>;
        clone.setAttribute('id_bd', id_bd.toString());

        return clone;
    }

    function dragMoveListener (event) {
        // Fonction pour déplacer les éléments
        var target = event.target,
            x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx, // Déplacement horizontal
            y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy; // Déplacement vertical

        target.style.transform = 'translate(' + x + 'px, ' + y + 'px)'; // Déplacement de l'élément

        target.setAttribute('data-x', x); // Mise à jour des coordonnées de l'élément
        target.setAttribute('data-y', y);
        //mise à jours des cooordoonées dans la BD
        sendData($idObjet: target.getAttribute('id_bd'), $x: x, $y: y);
        <?php 
            move_object($dsn, $user, $pass, $idObjet, $x, $y); 
        ?>

        // Redessiner les connexions lors du déplacement des éléments
        for (let connection of connexions) {
            if (connection.start === target || connection.end === target) {
                // Vérification de l'élément de départ et de l'élément d'arrivée
                dessin.clearRect(0, 0, canvas.width, canvas.height);
                const rect1 = connection.start.getBoundingClientRect(); // Obtient les dimensions et la position de l'élément de départ
                const rect2 = connection.end.getBoundingClientRect();
                dessin.beginPath();
                dessin.moveTo(rect1.left + rect1.width / 2, rect1.top + rect1.height / 2); 
                dessin.lineTo(rect2.left + rect2.width / 2, rect2.top + rect2.height / 2); 
                dessin.stroke();
            }
        }
    }

    interact('.draggable')
    // Fonction pour déplacer les éléments
        .draggable({
            inertia: true,
            autoScroll: true,
            onmove: dragMoveListener,
        })
        .on('doubletap', function (event) {
            // Affichage du menu contextuel
            ElementSelectionne = event.target; // Récupération de l'élément sélectionné
            contextMenu.style.display = 'block'; // Affichage du menu contextuel
            contextMenu.style.left = `${event.pageX}px`; // Positionnement du menu contextuel
            contextMenu.style.top = `${event.pageY}px`;
            event.preventDefault();
        })
        // Fonction pour dessiner les connexions lors du câblage des éléments
        .on('tap', function (event) {
            if (event.target.id === 'wire') {
                CableSelectionne = event.target;
            } else if (CableSelectionne) {
                if (event.target.id === 'pc' && ElementsSelectionne.length > 0 && ElementsSelectionne[0].id === 'pc') {
                    return;
                }

                let count = 0;
                for (let connection of connexions) {
                    // Vérification du nombre de connexions par élément
                    if (connection.start === event.target || connection.end === event.target) {
                        count++;
                    }
                }

                if (count < 4) {
                    // Limite de 4 connexions par élément
                    ElementsSelectionne.push(event.target); // Ajout de l'élément sélectionné dans le tableau
                    if (ElementsSelectionne.length === 2) {
                        // Dessin du câble entre les deux éléments sélectionnés
                        const EltSelect1 = ElementsSelectionne[0].getBoundingClientRect();
                        const EltSelect2 = ElementsSelectionne[1].getBoundingClientRect();
                        dessin.beginPath();
                        dessin.moveTo(EltSelect1.left + EltSelect1.width / 2, EltSelect1.top + EltSelect1.height / 2);
                        dessin.lineTo(EltSelect2.left + EltSelect2.width / 2, EltSelect2.top + EltSelect2.height / 2);
                        dessin.stroke();
                        connexions.push({
                            start: ElementsSelectionne[0],
                            end: ElementsSelectionne[1]
                        });

                        // Ajout des éléments et du câble dans le tableau
                        if (!cableEtEquipement[ElementsSelectionne[0].id]) {
                            // Vérification de l'existence de l'élément dans le tableau
                            cableEtEquipement[ElementsSelectionne[0].id] = []; // Création du tableau s'il n'existe pas
                        }
                        cableEtEquipement[ElementsSelectionne[0].id].push(dessin); // Ajout du câble dans le tableau

                        if (!cableEtEquipement[ElementsSelectionne[1].id]) {
                            cableEtEquipement[ElementsSelectionne[1].id] = []; 
                        }
                        cableEtEquipement[ElementsSelectionne[1].id].push(dessin); // Ajout du câble dans le tableau
                        //Ajout de la connexion dans la BD
                        sendData($idObjetA: ElementsSelectionne[0].getAttribute('id_bd'), $idObjetB: ElementsSelectionne[1].getAttribute('id_bd'), $InterfaceA: , $InterfaceB: );//A completer interfaces
                        <?php
                        add_connection($dsn, $user, $pass, $idObjetA, $idObjetB, $InterfaceA, $InterfaceB);
                        ?>
        
                        ElementsSelectionne = []; // Réinitialisation du tableau
                        CableSelectionne = null; // Réinitialisation du câble sélectionné
                    }
                }
            }
        });

    // Création d'un élément draggable lors du clic sur un élément du footer sauf le câble et les boutons
    EquipementFooter.forEach(element => {
        if (element.id !== 'wire' && element.id !== 'datagramme-button' && element.id !== 'lancer-button') {
            element.addEventListener('click', function() {
                createDraggableElement(element);
            });
        }
    });

    supprimerBouton.addEventListener('click', function () {
        // Suppression de l'élément sélectionné
        if (cableEtEquipement[ElementSelectionne.id]) {
            // Suppression des câbles associés à l'élément
            for (let cable of cableEtEquipement[ElementSelectionne.id]) {
                cable.clearRect(0, 0, canvas.width, canvas.height);
                //Suppression de la connexion dans la BD
                sendData($idObjetA: ElementsSelectionne[0].getAttribute('id_bd'), $idObjetB: ElementsSelectionne[1].getAttribute('id_bd'));
                <?php
                    del_connection($dsn, $user, $pass, $idObjetA, $idObjetB);
                ?>
            }
        }
        // Suppression de l'élément de l'écran
        ElementSelectionne.parentNode.removeChild(ElementSelectionne);
        // Fermeture du menu contextuel
        contextMenu.style.display = 'none';
        connexions = connexions.filter(connection => connection.start !== ElementSelectionne && connection.end !== ElementSelectionne);
        //Suppression dans la BD
        sendData($idObjet: ElementsSelectionne.getAttribute('id_bd'));
        <?php
            del_object($dsn, $user, $pass, $idObjet); 
        ?>
    }); 

    fermerBouton.addEventListener('click', function () {
        // Fermeture du menu contextuel
        contextMenu.style.display = 'none';
    });

    configBouton.addEventListener('click', function () {
        // Affichage de la fenêtre de configuration
        if (ElementSelectionne.id === 'pc') {
            configTitle.textContent = 'Configuration PC';
            reseauInput.style.display = 'none';
            reseauLabel.style.display = 'none';
            typeInput.value = 'pc';
            typeInput.disabled = true;
            typeInput.style.display = 'block';
        } else if (ElementSelectionne.id === 'router') {
            configTitle.textContent = 'Configuration Routeur';
            reseauInput.style.display = 'block';
            reseauLabel.style.display = 'block';
            typeInput.value = 'routeur';
            typeInput.disabled = true;
            typeInput.style.display = 'block';
        }
        config.style.display = 'block'; 
    });

    configFormulaire.addEventListener('submit', function (event) {
        // Soumission du formulaire de configuration
        event.preventDefault();
        config.style.display = 'none';
        // Modification dans la BD
        sendData($idObjet: ElementsSelectionne.getAttribute('id_bd'), $name: nomInput.value, $adresseIP: IPInput.value, $reseau: reseauInput.value);
        <?php
            edit_object($dsn, $user, $pass, $idObjet, $name, $adresseIP, $reseau);
        ?>
    });

    datagrammeBouton.addEventListener('click', function () {
        // Affichage de la fenêtre de configuration du datagramme
        datagrammeFenetre.style.display = 'block';
    });

    datagrammeFormulaire.addEventListener('submit', function (event) {
        // Soumission du formulaire de configuration du datagramme
        event.preventDefault();
        datagrammeFenetre.style.display = 'none';
        // Envoi des données du datagramme dans la bd
        // sendData($ttl: ttlInput.value, $protocole: protocoleInput.value, $source: sourceInput.value, $destination: destinationInput.value);
        // <?php
        //     $id_datagram = add_datagramme($dsn, $user, $pass, $ttl, $protocole, $source, $destination);
        // ?>
        // var id_bd = <?php echo json_encode($id_datagram); ?>;
        // clone.setAttribute('id_bd', id_bd.toString());

    });

    function sendData(data) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "your-server-script.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText); // Handle response from the server
            }
        };

        xhr.send("data=" + encodeURIComponent(JSON.stringify(data)));
    }

</script>
</body>
</html>