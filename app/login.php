<?php
require_once "functions.php";
redirectAlreadyConnected();
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $link = connectDB();

        $query = $link->prepare("SELECT * FROM users WHERE email = :email");
        $query->execute(['email' => $email]);

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($query->rowCount() == 1) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['userid'] = $user['id'];
                $_SESSION['email'] = $email;
                header('Location: index.php');
            } else {
                $error = 'Mot de passe invalide';
            }            
        } else {
            $error = 'Ce compte n\'existe pas';
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
    <title>Page de Connexion</title>
</head>
<body>
    <div class="form-card">
        <div class="card-title">
            <h2>Page de Connexion</h2>
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
                <button id="login-btn" class="sq-btn btn-lavande" type="submit" formmethod="POST">Se connecter</button>
            </div>
        </form>
        <hr>
        <div>
            <p>Pas de compte ? <a href="/register.php">S'inscrire</a></p>
        </div>
    </div>
</body>
</html>