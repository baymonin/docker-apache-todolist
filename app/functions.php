<?php

session_start();

function redirectAlreadyConnected() {
    if (isset($_SESSION['email']) != "") {
        header("Location: login.php");
        exit;
    }
}

function redirectUnauthenticated() {
    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
        exit;
    }
}

function connectDB () {
    $dsn = 'mysql:dbname=todo;host=mysql';
    $user = 'root';
    $password = '';

    $link = new PDO($dsn, $user, $password);

    return $link;
}