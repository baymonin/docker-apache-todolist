<?php
require_once "functions.php";
redirectUnauthenticated();
$errors = [];
if(isset($_GET['action'])) {
    $link = connectDB();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_GET['action'] == 'add') {
            $label = $_POST['label'];
            $description = $_POST['description'];
            $due_at = $_POST['due_at'] ?: null; 
                    
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
                $userid = $_SESSION['userid'];
        
                $query = $link->prepare("INSERT INTO tasks (owner_id, label, description, due_at) VALUES (:userid, :label, :description, :due_at)");
                $query->execute(['userid' => $userid, 'label' => $label, 'description' => $description, 'due_at' => $due_at]);
            }
        }
    } elseif($_GET['action'] == 'del') {
        if(isset($_GET['tid'])) {
            $tid = $_GET['tid'];

            $query = $link->prepare("SELECT * FROM tasks WHERE owner_id = :userid AND id = :tid");
            $query->execute(['userid' => $_SESSION['userid'], 'tid' => $tid]);

            if ($query->rowCount() == 1) {
                $query = $link->prepare("DELETE FROM tasks WHERE id = :tid ");
                $query->execute(['tid' => $tid]);
            } else {
                $errors[] = 'Cette tâche n\'existe pas ou ne vous appartiens pas';
            }
        } else {
            $errors[] = 'Erreur dans la requête';
        }
    }
}

?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css"> 
    <title>Mes Taches</title>
</head>
<body>
    <div class="form-card">
        <div>
            <h1>Mes Tâches</h1>
            <p>Bienvenue, <?php echo $_SESSION['email']; ?>! <a href="logout.php">Déconnexion</a></p>
            <?php 
            if (!empty($errors)) {
                foreach ($errors as $error):
            ?>
            <div class="err-box">
                <span><?php echo $error; ?></span>
            </div>
            <?php endforeach; } ?>
            <h2>Tâches à faire</h2>
            <table>
                <thead>
                <tr>
                    <th>Étiquette</th>
                    <th>Description</th>
                    <th>Échéance</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                $link = connectDB();

                $query = $link->prepare("SELECT * FROM tasks WHERE owner_id = :userid");
                $query->execute(['userid' => $_SESSION['userid']]);

                $tasks = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($tasks as $task): 
                ?>
                    <tr>
                    <td><?php echo $task['label']; ?></td>
                    <td><?php echo $task['description']; ?></td>
                    <td><?php echo ($task['due_at'] ? date('d/m/Y H:i', strtotime($task['due_at'])) : ''); ?></td>
                    <td><a href="index.php?action=del&tid=<?php echo $task['id'];?>">Supprimer</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <h2>Ajouter une tâche</h2>
            <form action="index.php?action=add" method="post">
                <label for="label">Étiquette:</label>
                <input type="text" id="label" name="label">
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>
                <label for="due_at">Échéance:</label>
                <input type="datetime-local" id="due_at" name="due_at">
                <input type="submit" value="Ajouter">
            </form>
        </div>
    </div>
</body>
</html>