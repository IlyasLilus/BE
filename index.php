<?php
include("Conne.php");
session_start();
if (isset($_POST['submit'])) {
    $Email = htmlspecialchars(strtolower(trim($_POST['Email'])));
    $Mpasse = htmlspecialchars(strtolower(trim($_POST['Mpasse'])));
    $query = "SELECT * FROM etudiant WHERE Email='$Email' && Mpasse='$Mpasse' ";
    if (mysqli_num_rows(mysqli_query($con, $query)) > 0) {
        $_SESSION['Mpasse'] = $Mpasse;
    } else {
        echo "Email ou mot de passe est incorrect";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>EL MIR</title>
    <link rel="stylesheet" href="index.css">
    <style type="text/css">
        * {
            padding: 0;
            margin: 0;
        }

        body {
            background-image: url("backpfe.jpg");

            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            /* height: 1000px; */

        }

        .iii {
            height: 60px;
            width: 60px;
        }

        .iii:hover {
            cursor: pointer;
        }

        .titre {
            font-family: 'Bebas Neue', cursive;

            font-size: 80px;
            position: absolute;
            left: 40%;
            font-weight: bolder;
            bottom: 40%;
        }

        span {
            color: sienna
        }

        #ul1 {
            background-color: rgb(95, 86, 72);
            height: 60px;
        }

        .inpt:focus {
            border: none;
        }

        .rrr {
            height: 500px;
        }

        #ul1 {
            position: sticky;
            top: 0px;
        }

        li[class^="x"] {

            color: gray;
            padding: 10px;
            margin: 10px;
            width: 60px;
            text-align: center;
            float: right;
            list-style: none;

        }

        li:hover {
            cursor: pointer;
            color: darkgoldenrod;
            font-size: 15pt;
        }

        .xxx {

            width: 300px;
            height: 250px;
            /* background-color: white; */
            background-attachment: scroll;
            position: fixed;
            left: 0%;
            top: 10%;
            border-radius: 30px;

            /* H-shadow | V-shadow | blur | spread | color | Inset */
            box-shadow: 5px 5px 20px 0 rgb(104, 80, 52);

        }

        label {
            display: block;
        }

        header {
            height: 400px;
        }

        .one {

            background-color: aqua;

            width: 40%;
            margin-left: auto;
            margin-right: auto;

        }

        input {
            display: block;
        }

        .date {
            position: relative;


        }

        h1 {
            text-align: center;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif
        }

        input {
            width: 200px;
            border-radius: 5px;
            height: 25px;
            border: none;
        }

        div form {
            position: absolute;
            left: 23%;
            top: 23%;
            text-align: center;
        }

        .save {
            position: absolute;
            left: 37%;
            width: 55px;
            border-radius: 20px;
        }

        /* visited is first _ secondly is hover _  */
        .save:hover {
            background-color: aqua;
        }

        .save:active {
            /* couleur lorsque on fait un clique long*/
            background-color: yellowgreen;
        }

        .all {
            position: absolute;
            left: 18%;
        }

        form :first-child {
            margin-bottom: 8px;
            padding-right: 100%;
            font-weight: bold;
            border-bottom: 2px solid black;
        }

        form :nth-child(3) {
            border-bottom: 2px solid black;
            padding-top: 10px;
            padding-right: 100%;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form :last-child {
            width: fit-content;
            padding: 5px;
            margin: 15px;
            position: relative;
            left: 20%;
        }

        .sub:hover {
            cursor: pointer;
        }

        .inp {
            background: non;
        }

        /* ----------------------------------------------------------------------------- */
    </style>
</head>

<body>
    <header class="rrr">

        <ul id="ul1">
            <img class="iii" src="est.png" alt="">
            <li class="xo1">Support</li>
            <li class="xo2">Account</li>
            <li class="xo3">Login</li>
            <li class="xo4">Home</li>
        </ul>
    </header>


    <div class="xxx">
        <h1>Connexion</h1>
        <form class="all" action="WL.php" method="POST">
            <label for="email" id="em">Email:</label>
            <input type="email" name="Email" placeholder="email" id="email" class="inpt">
            <label for="pass" id="ps">Password:</label>
            <input type="Password" name="Mpasse" id="pass" placeholder="password">
            <button type="submit" name="submit" class="sub">Se connecter</button>
        </form>
    </div>
    <h1 class="titre">WE ARE HERE TO <span>HELP</span> YOU</h1>


</body>

</html>