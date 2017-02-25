<?php

class CreateEndpointCest
{
    private $dogName = 'Sgt. Barkley';
    private $dogBreed = 'Bulldog';
    private $dogId = null;

    public function _after(DogsTester $I)
    {
        $I->deleteDog($this->dogId);
    }

    // tests
    public function createEndpointCreatesDog(DogsTester $I)
    {
        $I->wantTo('create a new dog');

        $this->dogId = $I->createDog($this->dogName, $this->dogBreed);
        $I->seeResponseContainsJson(['status' => 'success']);

        $header = $I->grabHttpHeader('location');
        $I->seeHeaderContains($this->dogId, $header, 'location header contains dog id');

        $I->seeDog($this->dogId, $this->dogName, $this->dogBreed);
    }
}
