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

    public function getDog(int $dogId): array
    {
        $sql = <<<SQL
SELECT * FROM dogs
WHERE id = :id;
SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'id' => $dogId,
        ]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function updateDog(int $dogId, array $columns): bool
    {
        if (count($columns) < 1) {
            return true;
        }

        $sql = [];
        $params = [];

        if (isset($columns['name'])) {
            $sql[] = 'name = :name';
            $params['name'] = $columns['name'];
        }
        if (isset($columns['breed'])) {
            $sql[] = 'breed = :breed';
            $params['breed'] = $columns['breed'];
        }

        $sql = 'UPDATE dogs SET ' . implode(', ', $sql) . ' WHERE id = :id;';
        $params['id'] = $dogId;

        $statement = $this->pdo->prepare($sql);

        return $statement->execute($params);
    }

    public function deleteDog(int $dogId): bool
    {
        $sql = <<<SQL
DELETE FROM dogs WHERE id = :id;
SQL;

        $statement = $this->pdo->prepare($sql);

        return $statement->execute([
            'id' => $dogId,
        ]);
    }
}
