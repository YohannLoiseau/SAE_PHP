<html>
    <head>
        <title>Les Albums</title>
    </head>
    <body>
        <?php
            session_start();
            unset($_SESSION['next']);

            include '../src/Factory.php';
            include '../data/DB.php';
            require_once '../src/autoloader.php';

            use src\Model\Genre;
            use src\Model\Album;
            use src\Model\Musicien;

            $html = "";

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $user = DB::db_script('SELECT * FROM UTILISATEUR WHERE idUtilisateur='.$_SESSION["idUtilisateur"])[0];
                $idAlbum = isset($_POST['idAlbum']) ? $_POST['idAlbum'] : None;
                $note = isset($_POST['note']) ? $_POST['note'] : None;
                $user->add_note_album($idAlbum, $note);
                header('Location: albums.php?idAlbum='.$idAlbum);
            }

            if (isset($_SESSION['idUtilisateur'])) {
                $html.="<a href='logout.php'>Déconnexion</a>
                <a href='profil.php'>Mon Profil</a>";
            }else{
                $html.="<a href='login.php'>Connexion</a>";
            }

            $albums = DB::db_script('SELECT * FROM ALBUM');
            if(empty($_GET['idAlbum'])){
                $html.="<a href='musiciens.php'>Tous les musiciens</a>
                <form method='GET' action='albums.php'>
                <input type='text' id='titre' name='titre'>";

                $html.="<select type='text' id='annee' name='annee'>
                    <option value='' disabled selected>-- Choisir une année --</option>";

                $lesAnnees = DB::db_script('SELECT DISTINCT annee FROM ALBUM ORDER BY annee');

                foreach($lesAnnees as $a){
                    $html.='<option value="'.$a["annee"].'">'.$a["annee"].'</option>';
                }

                $html.="</select><select type='text' id='nomGenre' name='nomGenre'>
                    <option value='' disabled selected>-- Choisir un genre --</option>";

                $lesGenres = DB::db_script('SELECT * FROM GENRE ORDER BY nomGenre');

                foreach($lesGenres as $g){
                    $html.='<option value="'.$g->nomGenre.'">'.$g->nomGenre.'</option>';
                }

                $html.='</select>
                    <input type="submit" value="Submit">
                </form>
                <h1>Les Albums';
                if(!empty($_GET['nomGenre'])){
                    $html.=" de genre ".$_GET['nomGenre'];
                    $albums = array_filter(
                        $albums,
                    fn($a) => in_array($_GET['nomGenre'], $a->genres()));
                }
                if(!empty($_GET['annee'])){
                    $html.=" de l'année ".$_GET['annee'];
                    $albums = array_filter(
                        $albums,
                    fn($a) => $a->annee==intval($_GET['annee']));
                }
                if(!empty($_GET['titre'])){
                    $html.=' contient "'.$_GET['titre'].'"';
                    $albums = array_filter(
                        $albums,
                    fn($a) => str_contains(strtoupper($a->titre), strtoupper($_GET['titre'])));
                }
                if(count($albums)>0){
                    $html.="<h2>Nombre d'albums: ".count($albums)."</h2><ul>";
                }else{
                    $html.="<h2>Aucun résultat trouvé</h2><ul>";
                }
                
                foreach($albums as $unAlbum){
                    $html.=$unAlbum->render();
                }
                $html.="</ul>";
            }else{
                if (!isset($_SESSION['idUtilisateur'])) {
                    $_SESSION['next'] = "albums.php?idAlbum=".$_GET['idAlbum'];
                    header("Location: login.php");
                    exit();
                }
                $album = Factory::create(array("idAlbum" => intval($_GET['idAlbum'])));
                $html.='<a href="albums.php">Tous les albums</a>
                <h1>'.$album->titre.'</h1>';
                if($album->image != "")
                    $path = "../data/images/".$album->image;
                if(!file_exists($path))
                    $path="../data/images/default.jpg";
                $html.='<img src="'.$path.'"/>';
                $html.='<table>
                <tr>
                    <th>Chanteur</th>
                    <th>Genre</th>
                    <th>Année</th>
                    <th>Auteur</th>
                </tr>';
                $chanteur = $album->chanteur;
                $html.="<td>{$chanteur->render()}</td><td>";
                foreach($album->genres() as $g){
                    $html.=$g->render()." ";
                }
                // $html.="</td><td>$album->annee</td><td>{$album->auteur->render()} $album->roleParent</td>
                // </table>";
                $html.="</td><td><a href='albums.php?annee=".$album->annee."'>".$album->annee."</a></td>";
                $html.="<td>{$album->auteur->render()} $album->roleParent</td>
                </table>";
                if (!empty($_GET['action'])) {
                    $user = DB::db_script('SELECT * FROM UTILISATEUR WHERE idUtilisateur='.$_SESSION["idUtilisateur"])[0];
                    if($_GET['action']=='add'){
                        $user->add_playlist(intval($_GET['idAlbum']));
                    }elseif($_GET['action']=='remove'){
                        $user->delete_playlist(intval($_GET['idAlbum']));
                        // DB::db_add_album($titre, $image, intval($annee), $chanteur, $auteur, $roleParent);
                    }
                }
                if (!$album->estDansPlaylist($_SESSION['idUtilisateur'])) {
                    $html.="<a href='albums.php?idAlbum=".$album->idAlbum."&action=add'>Ajouter dans playlist</a>";
                }else{
                    $html.="<a href='albums.php?idAlbum=".$album->idAlbum."&action=remove' onclick='return confirm(\"Confirmation\")'>Enlever de playlist</a>";
                }
                if ($album->estNote($_SESSION['idUtilisateur'])) {
                    // $html.="<form>
                    // <a href='albums.php?idAlbum=".$album->idAlbum."&action=note'>Évaluer l'album</a>
                    // <input type='submit' value='Évaluer'>";

                    $note=DB::db_script('SELECT note FROM EVALUER WHERE idAlbum='.$album->idAlbum
                    .' AND idUtilisateur='.$_SESSION['idUtilisateur'])[0]['note'];
                    $html.="<h2>Votre Note</h2>
                    <p>Note: $note</p>";
                }else{
                    // $html.="<a href='albums.php?idAlbum=".$album->idAlbum."&action=denote'>Supprimer la note</a>";
                    // NEXT
                    $html.="<form action='albums.php' method='POST'>
                        <input type='hidden' id='idAlbum' name='idAlbum' value=".$_GET['idAlbum'].">
                        <label for='note'>Note:</label>
                        <input type='int' id='note' name='note' pattern='[0-9]|10'
                        title='Entier entre 0 à 10' required><br>
                        <input type='submit' value='Évaluer'>
                    </form>";
                }
            }
            echo $html;
        ?>
    </body>
</html>