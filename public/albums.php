<html>
    <head>
        <title>Les Albums</title>
        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/aside.css">
    </head>
    <body>
        <?php
            if(empty($_SESSION))
                session_start();
            unset($_SESSION['next']);

            include_once '../src/Factory.php';
            include_once '../data/DB.php';
            require_once '../src/autoloader.php';

            use src\Model\Genre;
            use src\Model\Album;
            use src\Model\Musicien;

            include_once 'header.php';
            include_once 'aside.php';

            $html = "<main>";

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $user = DB::db_script('SELECT * FROM UTILISATEUR WHERE idUtilisateur='.$_SESSION["idUtilisateur"])[0];
                $idAlbum = isset($_POST['idAlbum']) ? $_POST['idAlbum'] : None;
                $note = isset($_POST['note']) ? $_POST['note'] : None;
                $user->add_note_album($idAlbum, $note);
                header('Location: albums.php?idAlbum='.$idAlbum);
            }

            $albums = DB::db_script('SELECT * FROM ALBUM');
            if(empty($_GET['idAlbum'])){
                

                
                $html.='<h2>Les Albums</h2>';
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
                    $html.="<h2>Nombre d'albums: ".count($albums)."</h2><ul id='albums'>";
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
                $html.='<a href="albums.php"><button>Tous les albums</button></a>
                <h3>'.$album->titre.'</h3>';
                if($album->image != "")
                    $path = "../data/images/".urldecode($album->image);
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
                    $html.=$g->render().", ";
                }
                $html.="</td><td><a href='albums.php?annee=".$album->annee."'>".$album->annee."</a></td>";
                $html.="<td>{$album->auteur->render()} ";
                if(!empty($album->roleParent))
                    $html.="($album->roleParent)";
                $html.="</td>
                </table>";
                if (!empty($_GET['action'])) {
                    $user = DB::db_script('SELECT * FROM UTILISATEUR WHERE idUtilisateur='.$_SESSION["idUtilisateur"])[0];
                    if($_GET['action']=='add'){
                        $user->add_playlist(intval($_GET['idAlbum']));
                    }elseif($_GET['action']=='remove'){
                        $user->delete_playlist(intval($_GET['idAlbum']));
                    }
                }
                if (!$album->estDansPlaylist($_SESSION['idUtilisateur'])) {
                    $html.="<a href='albums.php?idAlbum=".$album->idAlbum."&action=add'><button>Ajouter dans playlist</button></a>";
                }else{
                    $html.="<a href='albums.php?idAlbum=".$album->idAlbum."&action=remove' onclick='return confirm(\"Confirmation\")'><button>Enlever de playlist</button></a>";
                }
                if ($album->estNote($_SESSION['idUtilisateur'])) {
                    $note=DB::db_script('SELECT note FROM EVALUER WHERE idAlbum='.$album->idAlbum
                    .' AND idUtilisateur='.$_SESSION['idUtilisateur'])[0]['note'];
                    $html.="<h2>Votre Note</h2>
                    <p>Note: $note</p>";
                }else{
                    $html.="<form action='albums.php' method='POST'>
                        <input type='hidden' id='idAlbum' name='idAlbum' value=".$_GET['idAlbum'].">
                        <label for='note'>Note:</label>
                        <input type='int' id='note' name='note' pattern='[0-9]|10'
                        title='Entier entre 0 à 10' required><br>
                        <input type='submit' value='Évaluer'>
                    </form>";
                }
            }
            echo $html.'</main>';
        ?>
    </body>
</html>