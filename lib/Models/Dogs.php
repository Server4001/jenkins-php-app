<?php declare(strict_types=1);
/**
 * @category     Models
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */
 
namespace BentlerDesign\Models;

use Exception;
use PDO;

class Dogs
{
    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listAllDogs(): array
    {
        return $this->pdo->query('SELECT * FROM dogs;')
            ->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param string $name
     * @param string $breed
     *
     * @return int
     *
     * @throws Exception
     */
    public function createDog(string $name, string $breed): int
    {
        $sql = <<<SQL
INSERT INTO dogs SET
  name = :name,
  breed = :breed;
SQL;

        $statement = $this->pdo->prepare($sql);

        $created = $statement->execute([
            'name' => $name,
            'breed' => $breed,
        ]);

        if (!$created) {
            throw new Exception('Unable to create dog.');
        }

        return (int)$this->pdo->lastInsertId();
    }
}
