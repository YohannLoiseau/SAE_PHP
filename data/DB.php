<?php
class DB{
    static function db_script(string $requete){
        $file_db = new PDO('sqlite:../data/fixtures.sqlite3');
        $stmt = $file_db->query($requete);
        $instances = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        if(!isset($instances[0]['nomMusicien']) && !isset($instances[0]['nomGenre']) && !isset($instances[0]['idAlbum'])){
            return $instances;
        }
        foreach($instances as $i){
            $objet = Factory::create($i);
            $res[] = $objet;
        }
        return $res;
    }
}
?>