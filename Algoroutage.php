<?php
$host = 'localhost';
$db = 'BE';
$user = 'postgres';
$pass = 'a';
$port = '5432';
$dsn = "pgsql:host=$host;port=$port;dbname=$db";


function get_datagramme($idDatagramme){
    //TODO
    // Utilise l'id du datagramme et renvoie une liste [TTL, protocole, ipSource, ipDestination]
    //faire attention car dans la bd les info sont stocké en binaire donc convertir TTL en int
}

function get_objet($ipSource){
    //TODO
    // Utilise une adresse IP pour trouver l'id d'un objet
}

function get_table($objetActuel){
    //TODO
    // Utilise l'id d'un objet pour trouver la table de routage
    //recuperer toutes les routes correspondante à l'objet dans une liste de liste
    //chaque route devrait être une liste de 3 elements [ipDestination, nexthop, Infetrface]
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
                //Sortie LOG
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