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
        <title>Login</title>
    </head>
    <body>
    <h1>Se Connecter</h1>
    <form action="login.php" method="post">
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" required><br>

        <label for="mdp">Mot de Passe:</label>
        <input type="password" id="mdp" name="mdp" required><br>

        <input type="submit" value="Se Connecter">
    </form>
    <a href='inscription.php'>S'inscire</a>
    <?php
        $utilisateurs = DB::db_script('SELECT * FROM UTILISATEUR');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entreeU = isset($_POST['nom']) ? $_POST['nom'] : '';
            $entreeMDP = isset($_POST['mdp']) ? $_POST['mdp'] : '';

            $nomValide = FALSE;
            $mdpValide = FALSE;
            $estAdmin = FALSE;
            foreach($utilisateurs as $u){
                if($u->nomUtilisateur === $entreeU){
                    $nomValide = TRUE;
                    if($u->mdp === $entreeMDP){
                        $mdpValide = TRUE;
                        $estAdmin = $u->estAdmin;
                        $_SESSION['idUtilisateur'] = $u->idUtilisateur;
                    }
                }
            }
            if(!$nomValide){
                echo "Votre compte n'existe pas";
            }else{
                if(!$mdpValide){
                    echo "Votre mot de passe est faux";
                }else{
                    $next = $estAdmin ? "admin.php" : "albums.php";
                    if(isset($_SESSION['next'])){
                        $next = $_SESSION['next'];
                    }
                    header("Location: $next");
                    exit();
                }
            }
        }
    ?>


    </body>
</html>