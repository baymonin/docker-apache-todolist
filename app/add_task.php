<?php
require_once 'functions.php';
redirectUnauthenticated();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $label = $_POST['label'];
    $description = $_POST['description'];
    $due_at = $_POST['due_at'] ?: null; 

    $errors = [];
    if (empty($label)) {
        $errors[] = 'L\'étiquette est obligatoire';
    }
    if (empty($description)) {
        $errors[] = 'La description est obligatoire';
    }
    if ($due_at !== null && strtotime($due_at) === false) {
        $errors[] = 'La date d\'échéance est invalide';
    }

    if (empty($errors)) {
        $link = connectDB();

        $userid = $_SESSION['userid'];

        $query = $link->prepare("INSERT INTO tasks (owner_id, label, description, due_at) VALUES (:userid, :label, :description, :due_at)");
        $query->execute(['userid' => $userid, 'label' => $label, 'description' => $description, 'due_at' => $due_at]);

        header('Location: index.php');
        exit;
    }
}
?>