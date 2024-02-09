<?php

namespace src\Model;
use \PDO;

class Utilisateur{

    public int $idUtilisateur;

    public string $nomUtilisateur;

    public string $mdp;

    public bool $estAdmin;

    public function __construct(int $idUtilisateur,string $nomUtilisateur,string $mdp,bool $estAdmin){
        $this->idUtilisateur = $idUtilisateur;
        $this->nomUtilisateur = $nomUtilisateur;
        $this->mdp = $mdp;
        $this->estAdmin = $estAdmin;
    }

    public function playlist(){
        $currentDir = dirname(__FILE__);
        $databasePath = $currentDir . '/../../data/fixtures.sqlite3';
        $file_db = new PDO('sqlite:' . $databasePath);
        $stmt = $file_db->prepare('SELECT idAlbum FROM APPRECIER WHERE idUtilisateur=:idUtilisateur');

        $stmt->bindParam(':idUtilisateur', $this->idUtilisateur);
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
        return "<ul>
        <li>{$this->nomUtilisateur}</li>
        </ul>";
    }

    public function add_playlist(int $idAlbum){
        try{
            $currentDir = dirname(__FILE__);
            $databasePath = $currentDir . '/../../data/fixtures.sqlite3';
            $file_db = new PDO('sqlite:' . $databasePath);

            $stmt = $file_db->prepare("INSERT OR REPLACE INTO APPRECIER(idUtilisateur,idAlbum,note,estDansPlaylist)
            VALUES(:idUtilisateur,:idAlbum,COALESCE(
                (SELECT note FROM APPRECIER WHERE idUtilisateur=:idUtilisateur AND idAlbum=:idAlbum),
                NULL),1
            )");

            // $stmt = $file_db->prepare("INSERT INTO APPRECIER(idUtilisateur,idAlbum,estDansPlaylist) VALUES
            // (:idUtilisateur,:idAlbum,1)");

            $stmt->bindParam(':idUtilisateur', $this->idUtilisateur);
            $stmt->bindParam(':idAlbum', $idAlbum);

            $stmt->execute();
        }catch(PDOException $ex){
            echo "<script type='text/javascript'>alert('ajout album dans playlist IMPOSSIBLE');</script>";
        }
    }

    public function delete_playlist(int $idAlbum){
        try{
            $currentDir = dirname(__FILE__);
            $databasePath = $currentDir . '/../../data/fixtures.sqlite3';
            $file_db = new PDO('sqlite:' . $databasePath);

            $update_or_delete_p = "UPDATE APPRECIER 
                                SET estDansPlaylist = 0
                                WHERE idUtilisateur = :idUtilisateur 
                                AND idAlbum = :idAlbum";

            $stmt = $file_db->prepare($update_or_delete_p);
            $stmt->bindParam(':idUtilisateur', $this->idUtilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':idAlbum', $idAlbum, PDO::PARAM_INT);
            $stmt->execute();
        }catch(PDOException $ex){
            echo "<script type='text/javascript'>alert('delete album dans playlist IMPOSSIBLE');</script>";
        }
    }

    public function add_note_album(int $idAlbum, int $note){
        try{
            $currentDir = dirname(__FILE__);
            $databasePath = $currentDir . '/../../data/fixtures.sqlite3';
            $file_db = new PDO('sqlite:' . $databasePath);

            $stmt = $file_db->prepare("INSERT OR REPLACE INTO APPRECIER(idUtilisateur,idAlbum,note,estDansPlaylist)
            VALUES(:idUtilisateur,:idAlbum,:note,COALESCE(
                (SELECT estDansPlaylist FROM APPRECIER WHERE idUtilisateur=:idUtilisateur AND idAlbum=:idAlbum),
                0)
            )");

            $stmt->bindParam(':idUtilisateur', $this->idUtilisateur);
            $stmt->bindParam(':idAlbum', $idAlbum);
            $stmt->bindParam(':note', $note);

            $stmt->execute();
            header('Location: profil.php');
        }catch(PDOException $ex){
            echo "<script type='text/javascript'>alert(\"ajout note d'album IMPOSSIBLE\");</script>";
        }
    }

    public function delete_note_album(int $idAlbum){
        try{
            $currentDir = dirname(__FILE__);
            $databasePath = $currentDir . '/../../data/fixtures.sqlite3';
            $file_db = new PDO('sqlite:' . $databasePath);

            $update_or_delete_p = "UPDATE APPRECIER 
                                SET note = null
                                WHERE idUtilisateur = :idUtilisateur 
                                AND idAlbum = :idAlbum";

            $stmt = $file_db->prepare($update_or_delete_p);
            $stmt->bindParam(':idUtilisateur', $this->idUtilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':idAlbum', $idAlbum, PDO::PARAM_INT);
            $stmt->execute();

            header('Location: profil.php');
        }catch(PDOException $ex){
            echo "<script type='text/javascript'>alert('delete album dans playlist IMPOSSIBLE');</script>";
        }
    }
    
}