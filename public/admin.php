<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Espace Admin</title>
        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/header.css">
    </head>
    <body>
        <?php
            session_start();
            include '../data/DB.php';
            include '../src/Factory.php';

            include_once 'navbar.php';
        ?>
        <main>
            <h1>PAGE ADMIN</h1>
            <button><a href='admin-albums.php'>Gérer les albums</a></button>
            <button><a href='admin-musiciens.php'>Gérer les musiciens</a></button>
            <button><a href='admin-utilisateurs.php'>Gérer les utilisateurs</a></button>
        </main>
    </body>
</html>