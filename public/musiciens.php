<html>
    <head>
        <title>Les Musiciens</title>
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

            $html = "";
            if (!isset($_SESSION['idUtilisateur'])) {
                $_SESSION['next'] = "musiciens.php";
                header("Location: login.php");
                exit();
            }else{
                $html.="<a href='logout.php'>Déconnexion</a>";
            }

            if (empty($_GET['nomMusicien'])){
                $html.='<a href="albums.php">Tous les albums</a>
                <h1>Tous les musiciens</h1>
                <ul>';
                $musiciens = DB::db_script('SELECT * FROM MUSICIEN');
                foreach ($musiciens as $m){
                    $html.='<li><a href="musiciens.php?nomMusicien=' . $m->nomMusicien . '">' . $m->nomMusicien . '</a></li>';
                }
                $html.='</ul>';
            }else{
                $html.='<a href="musiciens.php">Tous les musiciens</a>';
                $objet = Factory::create($_GET);
                $albums = $objet->lesAlbums();

                $html.="<h1>".$_GET['nomMusicien']."</h1>
                <h2>L'année de début: ".min(array_column($albums, 'annee'))."</h2>
                <h2>Nombre d'album: ".count($albums)."</h2>
                <h2>Les Albums:</h2>";
                foreach($albums as $unAlbum){
                    $html.=$unAlbum->render();
                }
            }
            echo $html;
        ?>
    </body>
</html>