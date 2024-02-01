<html>
    <head>
        <title>Musicien</title>
    </head>
    <body>
        <?php
            require_once '../src/Factory.php';
            $musicien = Factory::create($_GET);
            echo "<h1>Les Albums de $musicien</h1>";
            foreach($musicien->lesAlbums() as $a){
                $a->render();
            }
        ?>
    </body>
</html>