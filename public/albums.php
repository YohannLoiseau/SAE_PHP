<html>
    <head>
        <title>Les Albums</title>
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

            $albums = DB::db_script('SELECT * FROM ALBUM');
            if(empty($_GET['idAlbum'])){
                $html.="<a href='genres.php'>Tous les genres</a>
                <a href='musiciens.php'>Tous les musiciens</a>
                <form method='GET' action='albums.php'>
                <input type='text' id='titre' name='titre'>";

                $html.="<select type='text' id='annee' name='annee'>
                    <option value='' disabled selected>-- Choisir une année --</option>";

                $lesAnnees = DB::db_script('SELECT DISTINCT annee FROM ALBUM ORDER BY annee');

                foreach($lesAnnees as $a){
                    $html.='<option value="'.$a["annee"].'">'.$a["annee"].'</option>';
                }

                $html.='</select>
                    <input type="submit" value="Submit">
                </form>
                <h1>Les Albums';
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
                $html.='</h1><ul>';
                foreach($albums as $unAlbum){
                    $html.=$unAlbum->render();
                }
                $html.='</ul>';
            }else{
                $album = Factory::create(array("idAlbum" => intval($_GET['idAlbum'])));
                $html.='<a href="albums.php">Tous les albums</a>
                <h1>'.$album->titre.'</h1>';
                $html.='<img src="../data/images/'.$album->image.'"/>';
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
            }
            echo $html;
        ?>
    </body>
</html>