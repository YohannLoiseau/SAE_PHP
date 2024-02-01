<?php

namespace src\Model;
use \PDO;

class Musicien{

    public string $nomMusicien;

    public function __construct(string $nomMusicien){
        $this->nomMusicien = $nomMusicien;
    }

    public function lesAlbums(){
        $currentDir = dirname(__FILE__);
        $databasePath = $currentDir . '/../../data/fixtures.sqlite3';
        $file_db = new PDO('sqlite:' . $databasePath);
        $stmt = $file_db->prepare('SELECT * FROM ALBUM WHERE musicienBy=:musicienBy');

        $stmt->bindParam(':musicienBy', $this->nomMusicien);
        $stmt->execute();

        $instances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $albums = array();

        foreach($instances as $i){
            $albums[] = new Album($i['idAlbum'], $i['titre'], $i['image'], $i['annee'],
            $i['idAlbum'], $this, new Musicien($i['musicienParent']));
        }

        return $albums;
    }

    public function __toString() {
        return $this->nomMusicien;
    }

    public function render(){
        return "<a href='details-musicien.php?nomMusicien=$this'>$this</a>";
    }
    
}