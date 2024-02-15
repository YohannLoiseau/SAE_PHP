<?php

$pdo = new PDO('sqlite:' . SQLITE_DB);

switch ($argv[2]) {
    case 'create-database':
        echo '→ Go create database "quiz.db"' . PHP_EOL;
        shell_exec('sqlite3 ' . SQLITE_DB);
        break;

    case 'create-table':
        echo '→ Go create "quiz" table' . PHP_EOL;
        $query =<<<EOF
            CREATE TABLE IF NOT EXISTS quiz (
                uuid        TEXT NOT NULL PRIMARY KEY,
                type        TEXT NOT NULL,
                label       TEXT NOT NULL,
                choices     TEXT NULL,
                correct     TEXT NOT NULL
            )
        EOF;
        break;

    case 'delete-table':
        echo '→ Go delete table "quiz"' . PHP_EOL;
        $query =<<<EOF
            DROP TABLE quiz
        EOF;
        break;

    case 'load-data':
        echo '→ Go load data to table "quiz"' . PHP_EOL;
        $dataFrom = json_decode(file_get_contents('Data/model.json'), true);
        $query = null;
        $stmt = $pdo->prepare('
            INSERT INTO quiz(uuid, type, label, choices, correct)
            VALUES(:uuid, :type, :label, :choices, :correct)
        ');
        foreach ($dataFrom as $data) {
            try {
                $stmt->execute([
                    ':uuid' => $data['uuid'], 
                    ':type' => $data['type'], 
                        ':label' => $data['label'], 
                    ':choices' => !empty($data['choices']) ? implode(',', $data['choices']) : null, 
                    ':correct' => $data['correct']
                ]);
            } catch (PDOException $e) {
                echo '→ '.$e->getMessage().PHP_EOL;
            }
        }
        break;
    
    default:
        echo 'No action defined 🙀'.PHP_EOL;
        break;
}

if ($query) {
    try {
        $pdo->exec($query);
    } catch (PDOException $e) {
        var_dump($e->getMessage());
    }
}