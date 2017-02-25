<?php

class ListEndpointCest
{
    private $dogId = null;
    private $dogName = 'Poochie';
    private $dogBreed = 'Labrador';

    public function _before(DogsTester $I)
    {
        $this->dogId = $I->createDog($this->dogName, $this->dogBreed);
    }

    public function _after(DogsTester $I)
    {
        $I->deleteDog($this->dogId);
    }

    // tests
    public function listEndpointShowsDogs(DogsTester $I)
    {
        $I->wantTo('view all dogs');
        $I->sendGET('');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
        ]);
        $I->seeResponseContainsJson([
            'id' => $this->dogId,
            'name' => $this->dogName,
            'breed' => $this->dogBreed,
        ]);
    }
}
