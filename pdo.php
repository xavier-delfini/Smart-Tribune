<html>
<head>
    <title>Smart Tribune Doctrine</title>
</head>
<body>

<?php

require_once 'vendor/autoload.php';

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

$dbParams = [
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'dbname' => 'smart_tribune',
    'user' => 'root',
    'password' => 'password',
    'charset' => 'utf8mb4'
];

$config = new Configuration();
$connectionParams = [
    'dbname' => '',
    'user' => $dbParams['user'],
    'password' => $dbParams['password'],
    'host' => $dbParams['host'],
    'driver' => $dbParams['driver'],
    'charset' => $dbParams['charset']
];
$entityManager = DriverManager::getConnection($connectionParams, $config);
$schemaManager = $entityManager->getSchemaManager();
$databaseExists = in_array($dbParams['dbname'], $schemaManager->listDatabases());

if (!$databaseExists) {
    echo "Creating database...\n";
    $tempConnection = DriverManager::getConnection($connectionParams);
    $tempConnection->getSchemaManager()->createDatabase($dbParams['dbname']);
    $tempConnection->close();
    echo "Database created.\n";
}

$entityManager->close();
$connectionParams['dbname'] = $dbParams['dbname'];
$entityManager = DriverManager::getConnection($connectionParams, $config);
$schemaManager = $entityManager->getSchemaManager();
$tableExists = $schemaManager->tablesExist(['urls']);


if (!$tableExists) {
    echo "Creating table...\n";
    $table = new \Doctrine\DBAL\Schema\Table('urls');
    $table->addColumn('id', 'integer', ['autoincrement' => true]);
    $table->addColumn('Uniform_Resource_Locate', 'string', ['length' => 255]);
    $table->addColumn('error', 'integer', ['default' => 0]);
    $table->addColumn('error_message', 'text');
    $table->setPrimaryKey(['id']);
    $schemaManager->createTable($table);
    echo "Table created.\n";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text'])) {
    $text = $_POST['text'];
    $entityManager->insert('urls', ['Uniform_Resource_Locate' => $text]);
    echo "Text added to the database successfully.";
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
?>

<form method="POST" action="">
    <textarea name="text"></textarea>
    <br>
    <input type="submit" value="Submit">
</form>
</body>
</html>