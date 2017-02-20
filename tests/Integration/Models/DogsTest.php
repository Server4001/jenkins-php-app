<?php declare(strict_types=1);
/**
 * @category     IntegrationTests
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */
 
namespace BentlerDesign\Tests\Integration\Models;

use BentlerDesign\Models\Dogs;
use BentlerDesign\Tests\Integration\IntegrationBase;
use DateTime;
use PDO;

class DogsTest extends IntegrationBase
{
    /** @var null|Dogs */
    private $dogsModel = null;

    public function setUp()
    {
        parent::setUp();

        $this->dogsModel = new Dogs(self::$pdo);
    }

    public function testListAllDogsReturnsMultipleRecords()
    {
        $statement = self::$pdo->prepare('INSERT INTO dogs SET name = :name, breed = :breed;');

        for ($i = 1; $i <= 10; $i++) {
            $statement->execute([
                'name' => "Dog #{$i}",
                'breed' => "Breed #{$i}",
            ]);
        }

        $allDogs = $this->dogsModel->listAllDogs();

        $this->assertInternalType('array', $allDogs);
        $this->assertCount(10, $allDogs);

        foreach ($allDogs as $dog) {
            $this->assertInternalType('array', $dog);
            $this->assertArrayHasKey('id', $dog);
            $this->assertArrayHasKey('name', $dog);
            $this->assertArrayHasKey('breed', $dog);
            $this->assertArrayHasKey('created_at', $dog);
            $this->assertArrayHasKey('updated_at', $dog);

            $this->assertTrue(strlen($dog['name']) > 0);
            $this->assertTrue(strlen($dog['breed']) > 0);
            $this->assertTrue(strlen($dog['created_at']) > 0);
            $this->assertTrue(strlen($dog['updated_at']) > 0);
        }
    }

    public function testListAllDogsReturnsSingleRecord()
    {
        $name = 'Dog #1';
        $breed = 'Breed #1';

        $statement = self::$pdo->prepare('INSERT INTO dogs SET name = :name, breed = :breed;');
        $statement->execute([
            'name' => $name,
            'breed' => $breed,
        ]);

        $allDogs = $this->dogsModel->listAllDogs();

        $this->assertInternalType('array', $allDogs);
        $this->assertCount(1, $allDogs);

        $dog = $allDogs[0];

        $this->assertInternalType('array', $dog);
        $this->assertArrayHasKey('id', $dog);
        $this->assertArrayHasKey('name', $dog);
        $this->assertArrayHasKey('breed', $dog);
        $this->assertArrayHasKey('created_at', $dog);
        $this->assertArrayHasKey('updated_at', $dog);

        $this->assertEquals(1, $dog['id']);
        $this->assertEquals($name, $dog['name']);
        $this->assertEquals($breed, $dog['breed']);

        // Assert created/updated at are valid date/timestamps.
        $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $dog['created_at']);
        $updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $dog['updated_at']);

        $this->assertInstanceOf('DateTime', $createdAt);
        $this->assertInstanceOf('DateTime', $updatedAt);
    }

    public function testCreateDog()
    {
        $name = 'Patches';
        $breed = 'Labrador Retriever';
        $id = $this->dogsModel->createDog($name, $breed);

        $this->assertInternalType('int', $id);
        $this->assertGreaterThan(0, $id);

        $statement = self::$pdo->prepare("SELECT * FROM dogs WHERE id = :id;");
        $statement->execute(['id' => $id]);
        $dog = $statement->fetch(PDO::FETCH_ASSOC);

        $this->assertInternalType('array', $dog);

        $this->assertEquals($id, $dog['id']);
        $this->assertSame($name, $dog['name']);
        $this->assertSame($breed, $dog['breed']);

        // Assert created/updated at are valid date/timestamps.
        $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $dog['created_at']);
        $updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $dog['updated_at']);

        $this->assertInstanceOf('DateTime', $createdAt);
        $this->assertInstanceOf('DateTime', $updatedAt);
    }
}
