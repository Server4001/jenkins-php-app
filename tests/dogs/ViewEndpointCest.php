<?php
/**
 * @category     DogsApiTests
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */

class ViewEndpointCest
{
    /**
     * @var null|int
     */
    private $dogId = null;
    private $dogName = 'Lucy';
    private $dogBreed = 'Dachshund';

    public function _before(DogsTester $I)
    {
        $this->dogId = $I->createDog($this->dogName, $this->dogBreed);
    }

    public function _after(DogsTester $I)
    {
        $I->deleteDog($this->dogId);
    }

    // tests
    public function sendingGetRequestReturnsSingleDog(DogsTester $I)
    {
        $I->seeDog($this->dogId, $this->dogName, $this->dogBreed);
    }
}
