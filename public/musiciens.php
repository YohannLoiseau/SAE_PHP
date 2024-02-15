<html>
    <head>
        <title>Les Musiciens</title>
        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/header.css">
    </head>
    <body>
        <?php
            if(empty($_SESSION))
                session_start();

            include_once '../src/Factory.php';
            include_once '../data/DB.php';
            require_once '../src/autoloader.php';

            use src\Model\Genre;
            use src\Model\Album;
            use src\Model\Musicien;

            include_once 'header.php';
            include_once 'aside.php';

            $html = "<main>";
            if (!isset($_SESSION['idUtilisateur'])) {
                $_SESSION['next'] = "musiciens.php";
                header("Location: login.php");
                exit();
            }

            if (empty($_GET['nomMusicien'])){
                $html.='<h1>Tous les musiciens</h1><ul>';
                $musiciens = DB::db_script('SELECT * FROM MUSICIEN');
                foreach ($musiciens as $m){
                    $html.='<li><a href="musiciens.php?nomMusicien=' . $m->nomMusicien . '">' . $m->nomMusicien . '</a></li>';
                }
                $html.='</ul>';
            }else{
                $objet = Factory::create($_GET);
                $albums = $objet->lesAlbums();

                $html.="<h1>".$_GET['nomMusicien']."</h1>";

                if(count($albums)>0){
                    $html.="<h2>L'année de début: ".min(array_column($albums, 'annee'))."</h2>
                    <h2>Nombre d'albums: ".count($albums)."</h2>
                    <h2>Les Albums:</h2>";
                    foreach($albums as $unAlbum){
                        $html.=$unAlbum->render();
                    }
                }else{
                    $html.="<h2>L'année de début: Non Disponible</h2>
                    <h2>Nombre d'album: 0</h2>
                    <h2>Les Albums:</h2>
                    <h3>-- aucun album trouvé --</h3>";
                }
                
            }
            echo $html.'</main>';
        ?>
    </body>
</html>