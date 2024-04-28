
<!DOCTYPE html>
<html>
<?php
    require 'FonctionsSimulation.php';
    $host = 'localhost';
    $db = 'BE';
    $user = 'postgres';
    $pass = 'a';
    $port = '5432';
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    //$idProjet = $_GET['idProjet'];
    //pour test: 
    $idProjet = 1;

    global $idDatagramme;
    $idDatagramme = 0;

    function modifierObjet($dsn, $user, $pass, $name, $ip, $mask, $type){
        if($name != null && $ip != null && $mask != null && $type != null){
            if(filter_var($ip, FILTER_VALIDATE_IP) && filter_var($mask, FILTER_VALIDATE_IP)){
                if($type == 'pc' || $type == 'routeur'){
                    return edit_object($dsn, $user, $pass, $name, $ip, $mask,$type);
                }
                else return "E1";
            }
            else return "E2";
        }
        else return "E3";
    }

    function ajouterDatagramme($dsn, $user, $pass, $ttl, $protocole, $source, $destination){
        if($ttl != null && $protocole != null && $source != null && $destination != null){
            if(filter_var($source, FILTER_VALIDATE_IP) && filter_var($destination, FILTER_VALIDATE_IP)){
                return add_datagramme($dsn, $user, $pass, $ttl, $protocole, $source, $destination);
            }
            else return "E1";
        }
        else return "E2";
    }
?>
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
                    <button class="commencer"><a href="Accueil_Utilisateur.php" >Mon compte</a></button>
        </div>
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
        <form action="Simulation.php" method = "POST" id="config-form">
            <label for="typeLabel">Type de l'objet:</label>
            <input type="type" id="type" name="type" style="display: none;"><br>
            <label for="nom">Nom de l'objet:</label>
            <input type="text" id="nom" name="nom"><br>
            <label for="adresse-ip">Adresse IP:</label>
            <input type="text" id="adresse-ip" name="adresse-ip"><br>
            <label for="reseau" id="reseau-label" style="display: none;">Masque:</label>
            <input type="text" id="reseau" name="reseau" style="display: none;">
            <label for="idObjet">Id de l'objet:</label>
            <input type= "idcurrentObjet" id= "idcurrentObjet" style="display: none;"><br>
            <input type="submit" value="Submit">
            <button onclick="contextMenu.style.display = 'none';">Annuler</button>
        </form>

        <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $name = $_POST['nom'];
            $ip = $_POST['adresse-ip'];
            $mask = $_POST['reseau'];
            $type = $_POST['type'];
            $idObjet = $_POST['idcurrentObjet'];
            modifierObjet($dsn, $user, $pass, $name, $ip, $mask,$type);
            if($idObjet=="E1"){
                echo "<script> alert('Erreur: Protocole incorrect')</script>";
            }
            else if($idObjet=="E2"){
                echo "<script> alert('Erreur: Adresse IP incorrecte') </script>";
            }
            else if($idObjet=="E3"){
                echo "<script> alert('Erreur: Champs vides'); console.log('AAAAAAAAAAAAAAAAAAAAAAAA');</script>";
            }
        } 
        ?>
    </div>
</div>

<div id="datagramme" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%;">
        <h2>Configuration du datagramme</h2>
        <form action="Simulation.php" method="POST" id="datagramme-form">
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
        <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $ttl = $_POST['ttl'];
            $protocole = $_POST['protocole'];
            $source = $_POST['source'];
            $destination = $_POST['destination'];
            $idDatagramme = ajouterDatagramme($dsn, $user, $pass, $ttl, $protocole, $source, $destination);

            if($idDatagramme=="E1"){
                echo "<script>alert(Erreur: Adresse IP incorrecte)</script>";
            }
            else if($idDatagramme=="E2"){
                echo "<script>alert(Erreur: Champs vides)</script>";
            }
            else{
                echo "<script> var id_Datagramme = " + json_encode($idDatagramme) + ";</script>";
            }
        }
        ?>
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
    const currentObject = document.getElementById('idcurrentObjet');
    const datagrammeBouton = document.getElementById('datagramme-button');
    const datagrammeFenetre = document.getElementById('datagramme');
    const datagrammeFormulaire = document.getElementById('datagramme-form');
    const EquipementFooter = document.querySelectorAll('footer > *');
    let ElementSelectionne = null;
    let CableSelectionne = null;
    let ElementsSelectionne = [];
    let connexions = [];
    let cableEtEquipement = {};

    async function createDraggableElement(element) {
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
        //Rentre le clone dans la BD et récupère l'id
        var cloneData = {name: "newborn", ip: '0.0.0.0', mask: '0.0.0.0', type: typeInput.value, x: clone.getBoundingClientRect().left, y: clone.getBoundingClientRect().top, projectId: <?php echo $idProjet; ?>};
        var myResult = await sendData('add_object',cloneData);
        clone.setAttribute('id_bd', myResult);

        currentObject.value = myResult;
        currentObject.disabled = true;
        currentObject.style.display = 'block';


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
        var moveData = {id: target.getAttribute('id_bd'), x: x, y: y};
        sendData('move_object',moveData);
        
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
                        //TODO demander l'interface lors de création de la connexion
                        var connectionData = {idObjetA: ElementsSelectionne[0].getAttribute('id_bd'), idObjetB: ElementsSelectionne[1].getAttribute('id_bd'), InterfaceA: null, InterfaceB: null};
                        sendData('add_connection',connectionData);

        
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
                var connectionData = {idObjetA: ElementSelectionne.getAttribute('id_bd')};
                sendData('del_connections',connectionData);
                
            }
        }
        // Suppression de l'élément de l'écran
        ElementSelectionne.parentNode.removeChild(ElementSelectionne);
        // Fermeture du menu contextuel
        contextMenu.style.display = 'none';
        connexions = connexions.filter(connection => connection.start !== ElementSelectionne && connection.end !== ElementSelectionne);
        //Suppression dans la BD
        var delData = {id: ElementSelectionne.getAttribute('id_bd')};
        sendData('del_object',delData);
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
        currentObject.value = ElementSelectionne.getAttribute('id_bd');
    });

    configFormulaire.addEventListener('submit', function (event) {
        // Soumission du formulaire de configuration
        event.preventDefault();
        config.style.display = 'none';
    });

    document.getElementById('lancer-button').addEventListener('click', async function() {
        // Lancer la simulation
        var idData = {idDatagramme: <?php echo $idDatagramme; ?>};
        var myResult = await sendData('main',idData);
        
    });

    datagrammeBouton.addEventListener('click', function () {
        // Affichage de la fenêtre de configuration du datagramme
        datagrammeFenetre.style.display = 'block';
    });

    datagrammeFormulaire.addEventListener('submit', function (event) {
        // Soumission du formulaire de configuration du datagramme
        event.preventDefault();
        datagrammeFenetre.style.display = 'none';
    });

    async function sendData(action, jsonData) {
        try {
            const response = await fetch('FonctionsSimulation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: action, data: jsonData })
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const result = await response.text();  // Use response.json() if expecting JSON
            return result;
        } catch (error) {
            console.error('Error:', error);
            throw error;  // Re-throw the error if you want to handle it later
        }
    }



</script>
</body>
</html>