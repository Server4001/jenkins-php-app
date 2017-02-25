<?php

class CreateEndpointCest
{
    private $dogName = 'Sgt. Barkley';
    private $dogBreed = 'Bulldog';
    private $dogId = null;

    public function _after(DogsTester $I)
    {
        $I->sendDELETE($this->dogId);
        $I->seeResponseCodeIs(204);
    }

    // tests
    public function createEndpointCreatesDog(DogsTester $I)
    {
        $I->wantTo('create a new dog');

        $this->dogId = $I->createDog($this->dogName, $this->dogBreed);
        $I->seeResponseContainsJson(['status' => 'success']);

        $header = $I->grabHttpHeader('location');
        $I->seeHeaderContains($this->dogId, $header, 'location header contains dog id');

        $I->sendGET($this->dogId);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data' => [
                'id' => $this->dogId,
                'name' => $this->dogName,
                'breed' => $this->dogBreed,
            ],
        ]);
    }
}
