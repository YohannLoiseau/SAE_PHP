<html>
    <head>
        <title>Les Musiciens</title>
    </head>
    <body>
        <?php
            include '../src/Factory.php';
            include '../data/DB.php';
            require_once '../src/autoloader.php';

            use src\Model\Genre;
            use src\Model\Album;
            use src\Model\Musicien;

            if (empty($_GET['nomMusicien'])){
                $html="";
                $html.='<a href="albums.php">Tous les albums</a>
                <a href="genres.php">Tous les genres</a>
                <h1>Tous les musiciens</h1>
                <ul>';
                $musiciens = DB::db_script('SELECT * FROM MUSICIEN');
                foreach ($musiciens as $m){
                    $html.='<li><a href="musiciens.php?nomMusicien=' . $m->nomMusicien . '">' . $m->nomMusicien . '</a></li>';
                }
                $html.='</ul>';
            }else{
                $html="";
                $html.='<a href="musiciens.php">Tous les musiciens</a>';
                $albums = DB::db_script('SELECT * FROM ALBUM');
                $albums = array_filter(
                    $albums,
                    fn($a) => $a->chanteur==$_GET['nomMusicien']);
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