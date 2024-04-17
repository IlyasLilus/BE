<?php 

$con = pg_connect("host=localhost port=5432 dbname=test user=postgres password=123");

// Vérifier la connexion
if($con){
    echo "success";}
else{
    echo pg_last_error();
}
?>