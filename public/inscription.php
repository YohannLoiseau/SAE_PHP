<?php
    session_start();

    include '../src/Factory.php';
    include '../data/DB.php';
    require_once '../src/autoloader.php';

    use src\Model\Genre;
    use src\Model\Album;
    use src\Model\Musicien;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inscription</title>
        <script>
        function validateForm() {
            var usernameInput = document.getElementById('nom');
            var passwordInput = document.getElementById('mdp');
            var confirmPasswordInput = document.getElementById('confirm_mdp');

            var username = usernameInput.value.trim();
            var password = passwordInput.value;
            var confirmPassword = confirmPasswordInput.value;

            var alphanumericRegex = /^[a-zA-Z0-9]+$/;

            if (username === "") {
                alert("Le nom ne peut pas être vide.");
                return false;
            }

            if (!alphanumericRegex.test(username)) {
                alert("Le nom doit être composé uniquement de lettres et de chiffres.");
                return false;
            }

            if (password === "") {
                alert("Le mot de passe ne peut pas être vide.");
                return false;
            }

            if (password !== confirmPassword) {
                alert("La confirmation du mot de passe ne correspond pas.");
                return false;
            }

            return true;
        }
    </script>
    </head>
    <body>
    <a href='login.php'>Page de Connexion</a>
    <h1>Créer un compte</h1>
    <form action="inscription.php" method="post" onsubmit="return validateForm()">
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" required><br>

        <label for="mdp">Mot de Passe:</label>
        <input type="password" id="mdp" name="mdp" required><br>

        <label for="confirm_mdp">Confirmation de mot de passe:</label>
        <input type="password" id="confirm_mdp" name="confirm_mdp" required><br>

        <input type="submit" value="Créer un compte">
    </form>
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
            $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';

            DB::db_add_user($nom, $mdp, FALSE);
            header("Location: login.php");
        }
    ?>