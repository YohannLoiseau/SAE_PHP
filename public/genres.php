<html>
    <head>
        <title>Les Genres</title>
    </head>
    <body>
        <?php
            include '../src/Factory.php';
            include '../data/DB.php';
            require_once '../src/autoloader.php';

            use src\Model\Genre;
            use src\Model\Album;
            use src\Model\Musicien;

            $html = "";

            if (empty($_GET['nomGenre'])){
                $html.='<a href="albums.php">Tous les albums</a>
                <a href="musiciens.php">Tous les musiciens</a>
                <h1>Tous les genres</h1>
                <ul>';
                $genres = DB::db_script('SELECT * FROM GENRE');
                foreach ($genres as $g){
                    $html.='<li><a href="genres.php?nomGenre=' . $g->nomGenre . '">' . $g->nomGenre . '</a></li>';
                }
                $html.='</ul>';
            }else{
                $objet = Factory::create($_GET);
                $html.='<a href="genres.php">Tous les genres</a>';
                $albums = DB::db_script('SELECT * FROM ALBUM');
                $albums = array_filter(
                    $albums,
                    fn($a) => in_array($objet, $a->genres()));
                $html.="<h1>Genre ".$_GET['nomGenre']."</h1>
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