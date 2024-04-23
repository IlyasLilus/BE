<?php
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