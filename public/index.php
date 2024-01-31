<?php
    include '../functions.php';
    require_once '../src/autoloader.php';

    $file_db = new PDO('sqlite:../data/fixtures.sqlite3');
    $stmt = $file_db->query('SELECT * FROM ALBUM');
    $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $aff = [];
    foreach($albums as $a){
        $aaa = createAlbum($a);
        $aff[] = $aaa;
    }
    foreach($aff as $aaa){
        $aaa->render();
    }
?>