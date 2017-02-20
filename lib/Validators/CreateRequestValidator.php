<?php declare(strict_types=1);
/**
 * @category     Validators
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */
 
namespace BentlerDesign\Validators;

use Symfony\Component\HttpFoundation\Request;

class CreateRequestValidator
{
    /**
     * @var array
     */
    private $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    public function valid(Request $request): bool
    {
        $this->reset();

        $name = $request->request->get('name');
        $breed = $request->request->get('breed');

        if (!is_string($name) || strlen($name) < 1) {
            $this->errors['name'] = 'Must be a non-empty string.';
        }
        if (!is_string($breed) || strlen($breed) < 1) {
            $this->errors['breed'] = 'Must be a non-empty string.';
        }

        return (count($this->errors) === 0);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function reset()
    {
        $this->errors = [];
    }
}
