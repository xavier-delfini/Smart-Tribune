<html>
<head>
    <title>bdd doctrine</title>
    <style>
        table {
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
        }
    </style>
</head>
<body>

<?php

require_once 'vendor/autoload.php';

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

$dbParams = ['driver'=>'pdo_mysql','host'=>'localhost','dbname'=>'smart_tribune','user'=>'root','password'=>'password','charset'=>'utf8mb4'];

$config = new Configuration();
$connectionParams = ['dbname'=>$dbParams['dbname'],'user'=>$dbParams['user'],'password'=>$dbParams['password'],'host'=>$dbParams['host'],'driver'=>$dbParams['driver'],'charset' =>$dbParams['charset']];
$entityManager = DriverManager::getConnection($connectionParams, $config);

$dsn = 'mysql:host=localhost;port=3306;charset=utf8mb4';
$username = 'root';
$password = 'password';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $databaseName = 'Smart_Tribune';
    $sql = "CREATE DATABASE IF NOT EXISTS $databaseName";
    $table = "CREATE TABLE IF NOT EXISTS urls (id INT AUTO_INCREMENT PRIMARY KEY, Uniform_Resource_Locate VARCHAR(255), error INT, error_message TEXT)";
    $pdo->exec($sql);
    $pdo->exec("USE $databaseName");
    echo "La base de données $databaseName a été créée avec succès.";
    $pdo->exec($table);
}
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
}

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


    $rows = $entityManager->executeQuery('SELECT * FROM urls')->fetchAll();
    echo "<h2>Contenu de la base de données :</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Uniform_Resource_Locate</th><th>Error</th><th>Error Message</th></tr>";
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['Uniform_Resource_Locate'] . "</td>";
        echo "<td>" . $row['error'] . "</td>";
        echo "<td>" . $row['error_message'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<form method="POST" action="">
    <textarea name="text"></textarea>
    <br>
    <input type="submit" value="Submit">
</form>
</body>
</html>
