<?php

namespace src\Model;
use \PDO;

class Genre{

    public string $nomGenre;

    public function __construct(string $nomGenre){
        $this->nomGenre = $nomGenre;
    }

    public function lesAlbums(){
        $currentDir = dirname(__FILE__);
        $databasePath = $currentDir . '/../../data/fixtures.sqlite3';
        $file_db = new PDO('sqlite:' . $databasePath);
        $stmt = $file_db->prepare('SELECT idAlbum FROM APPARTENIR WHERE nomGenre=:nomGenre');

        $stmt->bindParam(':nomGenre', $this->nomGenre);
        $stmt->execute();

        $idAlbums = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $albums = array();

        foreach($idAlbums as $id){
            $stmt = $file_db->prepare('SELECT * FROM ALBUM WHERE idAlbum=:idAlbum');

            $stmt->bindParam(':idAlbum', $id['idAlbum']);
            $stmt->execute();

            $i = $stmt->fetch(PDO::FETCH_ASSOC);

            $albums[] = new Album($i['idAlbum'], $i['titre'], $i['image'], $i['annee'],
            $i['idAlbum'], new Musicien($i['musicienBy']), new Musicien($i['musicienParent']));
        }

        return $albums;
    }

    public function __toString() {
        return $this->nomGenre;
    }

    public function render(){
        return "<a href='albums.php?nomGenre=".$this->nomGenre."'>".$this->nomGenre."</a>";
    }
    
}