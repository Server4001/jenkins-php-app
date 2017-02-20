<?php declare(strict_types=1);
/**
 * @category     IntegrationTests
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */
 
namespace BentlerDesign\Tests\Integration;

use Dotenv\Dotenv;
use FilesystemIterator;
use PDO;
use PHPUnit_Framework_TestCase;
use RegexIterator;

class IntegrationBase extends PHPUnit_Framework_TestCase
{
    /**
     * @var null|string
     */
    protected static $databaseName = null;

    /**
     * @var null|PDO
     */
    protected static $pdo = null;

    public static function setUpBeforeClass()
    {
        // Load config.
        $config = new Dotenv(PROJECT_ROOT . '/config', '.env');
        $config->load();

        // Get database connection.
        self::$databaseName = self::getDatabaseName();
        self::$pdo = self::getPdoConnection();

        // Create the test database.
        self::createTestDatabase(self::$databaseName);

        // Explicitly connect to the test database.
        self::$pdo->exec('USE ' . self::$databaseName . ';');

        // Create the test database tables.
        self::createDatabaseTables();
    }

    public function setUp()
    {
        $this->truncateAllTables();
    }

    public function tearDown()
    {
        $this->truncateAllTables();
    }

    public static function tearDownAfterClass()
    {
        // Drop the test database.
        $databaseName = self::$databaseName;
        $dropDatabaseSql = <<<SQL
DROP DATABASE IF EXISTS `{$databaseName}`;    
SQL;

        self::$pdo->exec($dropDatabaseSql);
    }

    protected static function getDatabaseName(): string
    {
        $databaseName = getenv('PHPUNIT_TEST_DATABASE');

        if ($databaseName === false) {
            $databaseName = 'jenkinsphp_test';
        }

        return $databaseName;
    }

    protected static function getPdoConnection(): PDO
    {
        $dbHost = getenv('DB_HOST');
        $dbPort = getenv('DB_PORT');
        $dbUser = getenv('DB_USER');
        $dbPass = getenv('DB_PASS');

        $dsn = "mysql:host={$dbHost};port={$dbPort}";

        return new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    protected static function createTestDatabase(string $databaseName)
    {
        $createDatabaseSql = <<<SQL
CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET = 'utf8' COLLATE = 'utf8_general_ci';
SQL;

        self::$pdo->exec($createDatabaseSql);
    }

    protected static function createDatabaseTables()
    {
        $files = new RegexIterator(new FilesystemIterator(PROJECT_ROOT . '/migrations'), '/.sql$/');

        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            $sql = file_get_contents($file->getPathname());

            self::$pdo->exec($sql);
        }
    }

    protected function getAllDatabaseTables($databaseName): array
    {
        $tablesSql = <<<SQL
SELECT t.TABLE_NAME FROM information_schema.tables AS t
WHERE t.TABLE_SCHEMA = :schema;
SQL;

        $statement = self::$pdo->prepare($tablesSql);

        $statement->execute([
            'schema' => $databaseName,
        ]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function truncateAllTables()
    {
        // Get the names of all tables in the test database.
        $tableNames = self::getAllDatabaseTables(self::$databaseName);

        foreach ($tableNames as $record) {
            $tableName = $record['TABLE_NAME'];

            // Truncate the table.
            self::$pdo->exec("TRUNCATE TABLE {$tableName};");
        }
    }
}
