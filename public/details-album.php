<html>
    <head>
        <title>Album</title>
    </head>
    <body>
        <?php
            require_once '../src/Factory.php';
            $album = Factory::create($_GET);
            // $album = get_album(intval($id));
            echo "<h1>$album->titre</h1>";
        ?>
        <table>
            <tr>
                <th>Chanteur</th>
                <th>Genre</th>
                <th>Année</th>
                <th>Auteur</th>
            </tr>
            <?php
                if($_GET['idAlbum']!==null){
                    echo "<img src='../data/images/".$album->image."'/>";
                    $chanteur = $album->chanteur;
                    $html = "<td>{$chanteur->render()}</td><td>";
                    $genres = "";
                    foreach($album->genres() as $g){
                        $genres.=$g." ";
                    }
                    $html.="$genres</td><td>$album->annee</td><td>{$album->auteur->render()} $album->roleParent</td>";
                    echo $html;
                }
            ?>
        </table>
    </body>
</html>