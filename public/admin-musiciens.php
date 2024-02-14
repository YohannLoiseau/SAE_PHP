<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin - Musicien</title>
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
            <h1>Gérer Les Musiciens</h1>
            <h2>Les Musiciens</h2>
            <table>
                <tr>
                    <th>NOM</th>
                    <th>ALBUMS</th>
                </tr>
                <?php
                    $html="";
                    $musiciens = DB::db_script('SELECT * FROM MUSICIEN');
                    foreach($musiciens as $m){
                        $html.="<tr>
                        <td>$m->nomMusicien</td>
                        <td>";
                        foreach($m->lesAlbums() as $a){
                            $html.=$a." | ";
                        }
                        $html.="</td>
                        <td><a href=\"admin-musiciens.php?nomMusicien='$m->nomMusicien'&action=edit\">editer</a></td>";
                        $html.="</td>
                        <td><a href=\"admin-musiciens.php?nomMusicien=$m->nomMusicien&action=delete\" onclick='return confirm(\"Confirmation\")'>supprimer</a></td>
                        </tr>";
                    }
                    if(!empty($_GET['nomMusicien']) && $_GET['action']=='delete'){
                        DB::db_delete_musicien($_GET['nomMusicien']);
                    }
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if(isset($_POST['nomAncien'])){
                            $nomAncien = isset($_POST['nomAncien']) ? $_POST['nomAncien'] : '';
                            $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
                            DB::db_edit_musicien($nomAncien, $nom);
                        }else{
                            $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
                            DB::db_add_musicien($nom);
                        }
                    }
                    echo $html;
                    if(!empty($_GET['action']) && $_GET['action']=='edit'){
                        $html = "</table>
                        <h2>Editer musicien ".$_GET['nomMusicien'].'</h2>
                        <form action="admin-musiciens.php" method="post">
                            <label for="nom">Nom:</label>
                            <input type="hidden" id="nomAncien" name="nomAncien" value='.$_GET['nomMusicien'].'><br>
                            <input type="text" id="nom" name="nom" value='.$_GET['nomMusicien'].' required><br>
                
                            <input type="submit" value="Mettre à jour">
                        </form>';
                    }else{
                        $html = '</table>
                        <h2>Ajouter un musicien</h2>
                        <form action="admin-musiciens.php" method="post">
                            <label for="nom">Nom:</label>
                            <input type="text" id="nom" name="nom" required><br>
                
                            <input type="submit" value="Créer un musicien">
                        </form>';
                    }
                    echo $html;
                ?>
        </main>
    </body>
</html>