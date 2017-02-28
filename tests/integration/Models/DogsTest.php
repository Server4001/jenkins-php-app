<?php declare(strict_types=1);
/**
 * @category     IntegrationTests
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */

namespace Models;

use BentlerDesign\Models\Dogs;
use Codeception\Test\Unit;

class DogsTest extends Unit
{
    /**
     * @var \IntegrationTester
     */
    protected $tester;

    /**
     * @var null|\PDO
     */
    private $pdo = null;

    /**
     * @var null|Dogs
     */
    private $dogsModel = null;

    protected function _before()
    {
        $this->pdo = $this->getModule('Db')->dbh;
        $this->dogsModel = new Dogs($this->pdo);
    }

    protected function _after()
    {
        $this->dogsModel = null;
        $this->pdo = null;
    }

    public function testUpdateDogReturnsTrueWhenThereAreNoUpdatesPresent()
    {
        $results = $this->dogsModel->updateDog(0, []);

        $this->assertTrue($results);
    }

    public function testGetDogReturnsDogRecord()
    {
        $name = 'Fido';
        $breed = 'Mutt';

        // Create a dog record.
        $affected = $this->pdo->exec("INSERT INTO dogs SET `name` = '{$name}', breed = '{$breed}';");
        $this->assertSame(1, $affected);

        $id = (int)$this->pdo->lastInsertId();

        // Call the getDog() model method, and assert results.
        $dog = $this->dogsModel->getDog($id);

        $this->assertInternalType('array', $dog);
        $this->assertArrayHasKey('id', $dog);
        $this->assertArrayHasKey('name', $dog);
        $this->assertArrayHasKey('breed', $dog);
        $this->assertArrayHasKey('created_at', $dog);
        $this->assertArrayHasKey('updated_at', $dog);

        $this->assertEquals($id, $dog['id']);
        $this->assertEquals($name, $dog['name']);
        $this->assertEquals($breed, $dog['breed']);
    }
}
