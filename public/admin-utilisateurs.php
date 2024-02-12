<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin - Utilisateur</title>
        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/header.css">
    </head>
    <body>
        <?php
            session_start();

            include '../src/Factory.php';
            include '../data/DB.php';
            require_once '../src/autoloader.php';

            use src\Model\Genre;
            use src\Model\Album;
            use src\Model\Musicien;

            include_once 'navbar.php';
        ?>
        <main>
            <button><a href='admin.php'>Page Admin</a></button>
            <h1>Gérer Les Utilisateurs</h1>
            <h2>Les Utilisateurs</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>NOM</th>
                    <th>ADMIN</th>
                </tr>
                <?php
                    $html="";
                    $utilisateurs = DB::db_script('SELECT * FROM UTILISATEUR');
                    foreach($utilisateurs as $u){
                        $html.="<tr>
                        <td>$u->idUtilisateur</td>
                        <td>$u->nomUtilisateur</td>
                        <td>".($u->estAdmin ? 'TRUE' : 'FALSE')."</td>";
                        
                        if($u->idUtilisateur != $_SESSION['idUtilisateur'])
                            $html.="<td><a href='admin-utilisateurs.php?idUtilisateur=$u->idUtilisateur' onclick='return confirm(\"Confirmation\")'>supprimer</a></td>";
                        $html.="</tr>";
                    }
                    if(!empty($_GET['idUtilisateur'])){
                        DB::db_delete_user($_GET['idUtilisateur']);
                    }
                    echo $html;
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
                        $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';
                        $estAdmin = isset
                        ($_POST['estAdmin']) ? $_POST['estAdmin'] : '';
                        
                        DB::db_add_user($nom, $mdp, $estAdmin);
                    }
                ?>
            </table>
            <h2>Ajouter un utilisateur</h2>
            <form action="admin-utilisateurs.php" method="post">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" required><br>

                <label for="mdp">Mot de Passe:</label>
                <input type="password" id="mdp" name="mdp" required><br>

                <label for="estAdmin">Admin:</label>
                <input type="checkbox" id="estAdmin" name="estAdmin"><br>

                <input type="submit" value="Créer un utilisateur">
            </form>
        </main>
    </body>
</html>