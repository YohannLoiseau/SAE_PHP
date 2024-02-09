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
        <title>Admin - Albums</title>
    </head>
    <body>
        <h1>Gérer Les Albums</h1>
        <a href='admin.php'>Page Admin</a>
        <h2>Les Albums</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>TITRE</th>
                <th>IMAGE</th>
                <th>ANNÉE</th>
                <th>CHANTEUR</th>
                <th>AUTEUR</th>
            </tr>
            <?php
                $html="";
                $albums = DB::db_script('SELECT * FROM ALBUM');
                foreach($albums as $a){
                    $html.="<tr>
                    <td>$a->idAlbum</td>
                    <td>$a->titre</td>
                    <td>$a->image</td>
                    <td>$a->annee</td>
                    <td>$a->chanteur</td>
                    <td>".$a->auteur;
                    if($a->roleParent){
                        $html.=" (".$a->roleParent.")";
                    }
                    $html.="</td>
                    <td><a href=\"admin-albums.php?idAlbum=$a->idAlbum&action=edit\">editer</a></td>";
                    $html.="</td>
                    <td><a href=\"admin-albums.php?idAlbum=$a->idAlbum&action=delete\" onclick='return confirm(\"Confirmation\")'>supprimer</a></td>
                    </tr>";
                }
                if(!empty($_GET['idAlbum'])  && $_GET['action']=='delete'){
                    DB::db_delete_album($_GET['idAlbum']);
                }
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if(isset($_POST['idAlbum'])){
                        $idAlbum = $_POST['idAlbum'];
                        $titre = isset($_POST['titre']) ? $_POST['titre'] : '';
                        $image = isset($_POST['image']) ? $_POST['image'] : None;
                        $annee = isset($_POST['annee']) ? $_POST['annee'] : '';
                        $chanteur = isset($_POST['chanteur']) ? $_POST['chanteur'] : '';
                        $auteur = isset($_POST['auteur']) ? $_POST['auteur'] : '';
                        $roleParent = isset($_POST['roleParent']) ? $_POST['roleParent'] : '';
            
                        DB::db_edit_album($idAlbum, $titre, $image, intval($annee), $chanteur, $auteur, $roleParent);
                    }else{
                        $titre = isset($_POST['titre']) ? $_POST['titre'] : '';
                        $image = isset($_POST['image']) ? $_POST['image'] : None;
                        $annee = isset($_POST['annee']) ? $_POST['annee'] : '';
                        $chanteur = isset($_POST['chanteur']) ? $_POST['chanteur'] : '';
                        $auteur = isset($_POST['auteur']) ? $_POST['auteur'] : '';
                        $roleParent = isset($_POST['roleParent']) ? $_POST['roleParent'] : '';
            
                        DB::db_add_album($titre, $image, intval($annee), $chanteur, $auteur, $roleParent);
                    }
                }
                echo $html;
                $html = '</table>';

                if(empty($_GET['idAlbum'])){
                    $album = null;
                    $html.='<h2>Ajouter un album</h2>
                    <form action="admin-albums.php" method="post">';
                }else{
                    $album = DB::db_script('SELECT * FROM ALBUM WHERE idAlbum='.$_GET['idAlbum'])[0];
                    $html.='<h2>Editer l\'album '.$album->titre.'</h2>
                    <form action="admin-albums.php" method="post">
                        <input type="hidden" id="idAlbum" name="idAlbum" value='.$_GET['idAlbum'].'>';
                }
                
                $html.='<label for="titre">Titre:</label>
                <input type="text" id="titre" name="titre" '.($album ? 'value="' . $album->titre . '"' : '')
                .'required><br>
    
                <label for="image">Image:</label>
                <input type="text" id="image" name="image"'.($album ? 'value="' . $album->image . '"' : '')
                .'><br>
    
                <label for="annee">Année:</label>
                <input type="int" id="annee" name="annee" '.($album ? 'value="' . $album->annee . '"' : '')
                .'required><br>
    
                <label for="chanteur">Chanteur:</label>
                <select type="text" id="chanteur" name="chanteur" required><br>';
                $lesMusiciens = DB::db_script('SELECT nomMusicien FROM MUSICIEN');

                foreach($lesMusiciens as $m){
                    $html.='<option ';
                    if(!empty($album) && $m->nomMusicien == $album->chanteur)
                        $html.='selected ';
                    $html.='value="'.$m->nomMusicien.'">'.$m->nomMusicien.'</option>';
                }

                $html.='</select>
                <label for="auteur">Auteur:</label>
                <select type="text" id="auteur" name="auteur" required><br>';

                foreach($lesMusiciens as $m){
                    $html.='<option ';
                    if(!empty($album) && $m->nomMusicien == $album->auteur)
                        $html.='selected ';
                    $html.='value="'.$m->nomMusicien.'">'.$m->nomMusicien.'</option>';
                }

                $html.='</select><label for="roleParent">autre role d\'auteur:</label>
                <input type="text" id="roleParent" name="roleParent"'.($album ? 'value="' . $album->roleParent . '"' : '')
                .'><br>
                <input type="submit" value="';
                if(empty($album))
                    $html.='Créer un album">';
                else
                    $html.='Mettre à jour">';
            echo $html.'</form>';
            ?>
    </body>
</html>