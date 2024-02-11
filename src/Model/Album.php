<?php

namespace src\Model;
use \PDO;

class Album{

    public int $idAlbum;

    public string $titre;

    public ?string $image;

    public ?int $annee;

    public ?string $roleParent;

    public Musicien $chanteur;

    public Musicien $auteur;

    public function __construct(int $idAlbum, string $titre, ?string $image,
    ?int $annee, ?string $roleParent, Musicien $chanteur, Musicien $auteur){
        $this->idAlbum = $idAlbum;
        $this->titre = $titre;
        $this->image = $image;
        $this->annee = $annee;
        $this->roleParent = $roleParent;
        $this->chanteur = $chanteur;
        $this->auteur = $auteur;
    }

    public function genres(){
        $currentDir = dirname(__FILE__);
        $databasePath = $currentDir . '/../../data/fixtures.sqlite3';
        $file_db = new PDO('sqlite:' . $databasePath);
        $stmt = $file_db->prepare('SELECT * FROM APPARTENIR where idAlbum=:idAlbum');

        $stmt->bindParam(':idAlbum', $this->idAlbum);
        $stmt->execute();

        $instances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $genres = array();

        foreach($instances as $i){
            $genres[] = new Genre($i['nomGenre']);
        }

        return $genres;
    }

    public function render(){
        $path = null;
        if($this->image != "")
            $path = "../data/images/".$this->image;
        if(!file_exists($path))
            $path="../data/images/default.jpg";
        $html = "<li><a href='albums.php?idAlbum=$this->idAlbum'><img src='".$path."'/><p>";
        $html = $html.$this->titre."<br/>".$this->chanteur->nomMusicien."</p></a></li>";
        return $html;
    }

    public function __toString() {
        return $this->titre;
    }

    public function estDansPlaylist(int $idUtilisateur){
        $currentDir = dirname(__FILE__);
        $databasePath = $currentDir . '/../../data/fixtures.sqlite3';
        $file_db = new PDO('sqlite:' . $databasePath);
        $rqt = "SELECT * FROM APPRECIER WHERE idUtilisateur=$idUtilisateur";
        $stmt = $file_db->query($rqt);
        $instances = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($instances as $i){
            if($i['idAlbum'] == $this->idAlbum)
                return TRUE;
        }
        return FALSE;
    }

    public function estNote(int $idUtilisateur){
        $currentDir = dirname(__FILE__);
        $databasePath = $currentDir . '/../../data/fixtures.sqlite3';
        $file_db = new PDO('sqlite:' . $databasePath);
        $rqt = "SELECT * FROM EVALUER WHERE idUtilisateur=$idUtilisateur";
        $stmt = $file_db->query($rqt);
        $instances = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($instances as $i){
            if($i['idAlbum'] == $this->idAlbum)
                return TRUE;
        }
        return FALSE;
    }
    
}