<?php
require_once 'vendor/autoload.php';

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

if (isset($_GET['displayData'])) {

    $dbParams = ['driver' => 'pdo_mysql', 'host' => 'localhost', 'dbname' => 'smart_tribune', 'user' => 'root', 'password' => '', 'charset' => 'utf8mb4'];

    $config = new Configuration();
    $connectionParams = ['dbname' => $dbParams['dbname'], 'user' => $dbParams['user'], 'password' => $dbParams['password'], 'host' => $dbParams['host'], 'driver' => $dbParams['driver'], 'charset' => $dbParams['charset']];
    $entityManager = DriverManager::getConnection($connectionParams, $config);

    try {
        $schemaManager = $entityManager->getSchemaManager();
        $existingDatabases = $schemaManager->listDatabases();
        $databaseExists = in_array($dbParams['dbname'], $existingDatabases);
        if (!$databaseExists) {
            $schemaManager->createDatabase($dbParams['dbname']);
        }
        $entityManager->close();
        $connectionParams['dbname'] = $dbParams['dbname'];
        $entityManager = DriverManager::getConnection($connectionParams, $config);
        $tableExists = $schemaManager->tablesExist(['urls']);
        if (!$tableExists) {
            $table = new \Doctrine\DBAL\Schema\Table('urls');
            $table->addColumn('id', 'integer', ['autoincrement' => true]);
            $table->addColumn('Uniform_Resource_Locate', 'string', ['length' => 255]);
            $table->addColumn('error', 'integer', ['default' => null, 'notnull' => false]);
            $table->addColumn('error_message', 'text', ['default' => null, 'notnull' => false]);
            $table->setPrimaryKey(['id']);
            $schemaManager->createTable($table);
        }
        if (isset($_POST['text'])) {
            $text = $_POST['text'];
            $entityManager->insert('urls', ['Uniform_Resource_Locate' => $text]);
            echo "URL added to the database successfully.";
        }

        $color = 'clair';

        $rows = $entityManager->executeQuery('SELECT * FROM urls')->fetchAll();
        echo <<<HTML
                <h2>Contenu de la base de données :</h2>
                <table>
                <tr><th>ID</th><th>Uniform_Resource_Locate</th><th>Error</th><th>Error Message</th></tr>
            HTML;

        foreach ($rows as $row) {

            $color === 'clair' ? $color = 'fonce' : $color = 'clair';

            echo '<tr class="' . $color . '">
                    <td>' . $row['id'] . '</td>
                    <td><a href="http://' . $row['Uniform_Resource_Locate'] . '">' . $row['Uniform_Resource_Locate'] . '<a/></td>
                    <td>' . $row['error'] . '</td>
                    <td>' . $row['error_message'] . '</td>
                  </tr>';
        }
        echo "</table>";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    die();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="display.js" defer></script>
    <title>Liste URL</title>
</head>

<body>
    <main>
        <form method="" action="" class="formAddUrl">
            <input type="text" id="text" name="text" placeholder="ajouter URL" />
            <button type="submit" id="submitButton">Ajouter</button>
        </form>

        <div class="display"></div>
    </main>
</body>

</html>