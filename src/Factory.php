<?php
require_once '../src/autoloader.php';
use src\Model\Musicien;
use src\Model\Genre;
use src\Model\Album;

class Factory{
    public static function create($data){
        if(isset($data['nomMusicien'])){
            return new Musicien($data['nomMusicien']);
        }elseif(isset($data['nomGenre'])){
            return new Genre($data['nomGenre']);
        }else{
            $currentDir = dirname(__FILE__);
            $databasePath = $currentDir . '/../data/fixtures.sqlite3';
            $file_db = new PDO('sqlite:' . $databasePath);
            $requete = 'SELECT * FROM ALBUM WHERE idAlbum=:idAlbum';
            $stmt = $file_db->prepare($requete);
            $stmt->bindParam(':idAlbum', $data['idAlbum']);
            $stmt->execute();
            $instance = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Album(
                $instance['idAlbum'],
                $instance['titre'],
                $instance['image'],
                $instance['annee'],
                $instance['roleParent'],
                new Musicien($instance['musicienBy']),
                new Musicien($instance['musicienParent'])
            );
        }
    }
}
?>