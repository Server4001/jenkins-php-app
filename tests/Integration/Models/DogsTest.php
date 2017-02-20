<?php declare(strict_types=1);
/**
 * @category     IntegrationTests
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */
 
namespace BentlerDesign\Tests\Integration\Models;

use BentlerDesign\Tests\Integration\IntegrationBase;

class DogsTest extends IntegrationBase
{
    public function testBlah()
    {

        $statement = self::$pdo->prepare('SELECT * FROM dogs;');
        $statement->execute();

        while ($row = $statement->fetchAll(\PDO::FETCH_ASSOC)) {
            var_dump($row);
        }
        $this->assertTrue(true);
    }
}
