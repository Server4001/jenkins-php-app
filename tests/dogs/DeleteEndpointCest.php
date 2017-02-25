<?php

class DeleteEndpointCest
{
    private $dogId = null;
    private $dogName = 'Daisy';
    private $dogBreed = 'Pug';

    // tests
    public function sendingDeleteRequestRemovesDog(DogsTester $I)
    {
        $this->dogId = $I->createDog($this->dogName, $this->dogBreed);

        $I->deleteDog($this->dogId);
        $I->canSeeResponseEquals('');
    }

    public function sendingInvalidIdResultsIn404(DogsTester $I)
    {
        $I->sendDELETE('abc123');
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'fail',
            'data' => [
                'message' => 'Unable to delete dog.',
            ],
        ]);
    }
}
