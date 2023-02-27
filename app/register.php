<?php
require_once "functions.php";
redirectAlreadyConnected();
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hash_pass = password_hash($password, PASSWORD_DEFAULT);

        $link = connectDB();

        $query = $link->prepare("SELECT id FROM users WHERE email = :email");
        $query->execute(['email' => $email]);
        if ($query->rowCount() == 0) {        

            $query = $link->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
            $query->execute(['email' => $email, 'password' => $hash_pass]);

            if ($query->rowCount() == 1) {
                header('Location: login.php');
            } 
        } else {
            $error = 'Cette adresse mail est déjà utilisé.';
        }
    } else {
        $error = 'Merci de remplir tous les champs';
    }
}
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css"> 
    <title>Page d'Inscription'</title>
</head>
<body>
    <div class="form-card">
        <div class="card-title">
            <h2>Page d'Inscription</h2>
        </div>
        <?php if ($error != '') {?>
        <div class="err-box">
            <span><?php echo $error; ?></span>
        </div>
        <?php } ?>
        <form>
            <div class="field">
                <label for="email">Adresse eMail :</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="field">
                <label for="password">Mot de Passe :</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="btn-holder">
                <button id="login-btn" class="sq-btn btn-lavande" type="submit" formmethod="POST">S'inscrire</button>
            </div>
        </form>
        <hr>
        <div>
            <p>Déjà inscrit ? <a href="/login.php">Se connecter</a></p>
        </div>
    </div>
</body>
</html>