<?php
require_once "functions.php";
redirectUnauthenticated();
$error = '';
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
            <h2>Tâches à faire</h2>
            <table>
                <thead>
                <tr>
                    <th>Étiquette</th>
                    <th>Description</th>
                    <th>Échéance</th>
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
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <h2>Ajouter une tâche</h2>
            <form action="add_task.php" method="post">
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