<?php

class ViewEndpointCest
{
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
