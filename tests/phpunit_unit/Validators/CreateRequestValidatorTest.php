<?php declare(strict_types=1);
/**
 * @category     UnitTests
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */

namespace BentlerDesign\Tests\PhpunitUnit\Validators;

use BentlerDesign\Validators\CreateRequestValidator;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateRequestValidatorTest extends PHPUnit_Framework_TestCase
{
    /** @var null|CreateRequestValidator */
    private $validator = null;

    public function setUp()
    {
        $this->validator = new CreateRequestValidator();
    }

    public function testValidReturnsFalseFromMissingRequestData()
    {
        $request = new Request([], []);
        $valid = $this->validator->valid($request);
        $errors = $this->validator->getErrors();

        $this->assertFalse($valid);
        $this->assertInternalType('array', $errors);
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('breed', $errors);
        $this->assertInternalType('string', $errors['name']);
        $this->assertInternalType('string', $errors['breed']);
        $this->assertTrue(strlen($errors['name']) > 0);
        $this->assertTrue(strlen($errors['breed']) > 0);
    }

    public function testValidReturnsFalseFromInvalidRequestData()
    {
        $request = new Request([], [
            'name' => 52,
            'breed' => '',
        ]);

        $valid = $this->validator->valid($request);
        $errors = $this->validator->getErrors();

        $this->assertFalse($valid);
        $this->assertInternalType('array', $errors);
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('breed', $errors);
        $this->assertInternalType('string', $errors['name']);
        $this->assertInternalType('string', $errors['breed']);
        $this->assertTrue(strlen($errors['name']) > 0);
        $this->assertTrue(strlen($errors['breed']) > 0);
    }

    public function testValidReturnsTrueFromValidRequestData()
    {
        $request = new Request([], [
            'name' => 'Rufus',
            'breed' => 'Labrador',
        ]);

        $valid = $this->validator->valid($request);
        $errors = $this->validator->getErrors();

        $this->assertTrue($valid);
        $this->assertInternalType('array', $errors);
        $this->assertCount(0, $errors);
    }
}
