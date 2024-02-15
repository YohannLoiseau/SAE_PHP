<aside>
    <?php
        $html="<nav><a href='musiciens.php'><button>Tous les musiciens</button></a>
        <a href='albums.php'><button>Tous les albums</button></a></nav>";
        if($_GET['idAlbum'] == null && $_GET['nomMusicien'] == null && strpos($_SERVER['PHP_SELF'], "profil") == false && strpos($_SERVER['PHP_SELF'], "admin") == false){
            $html.="<div><h2>Rechercher</h2>
            <form method='GET' action='albums.php'>
            <input type='text' id='titre' name='titre' placeholder='ex: we, love, etc..'>";
            
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
                </div>';
        } else if (strpos($_SERVER['PHP_SELF'], "admin") !== false){
            $html.="";
        }

        
        
        echo $html;
    ?>
</aside>