<?php

class UpdateEndpointsCest
{
    private $dogId = null;
    private $dogName = 'Wunderdog';
    private $dogBreed = 'Great Dane';

    public function _before(DogsTester $I)
    {
        $this->dogId = $I->createDog($this->dogName, $this->dogBreed);
    }

    public function _after(DogsTester $I)
    {
        $I->deleteDog($this->dogId);
    }

    // tests
    public function sendingPutRequestUpdatesDog(DogsTester $I)
    {
        $newName = 'Sparkles';
        $newBreed = 'Chow Chow';

        $I->wantTo('update a dog using a PUT request');

        $I->sendPUT($this->dogId, [
            'name' => $newName,
            'breed' => $newBreed,
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data' => [
                'dog' => [
                    'id' => $this->dogId,
                    'name' => $newName,
                    'breed' => $newBreed,
                ],
            ],
        ]);

        $I->seeDog($this->dogId, $newName, $newBreed);
    }

    public function sendingPatchRequestUpdatesDog(DogsTester $I)
    {
        $newName = 'Bailey';
        $newBreed = 'Boxer';

        $I->wantTo('update a dog using a PATCH request');

        $I->sendPATCH($this->dogId, [
            'name' => $newName,
            'breed' => $newBreed,
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'data' => [
                'dog' => [
                    'id' => $this->dogId,
                    'name' => $newName,
                    'breed' => $newBreed,
                ],
            ],
        ]);

        $I->seeDog($this->dogId, $newName, $newBreed);
    }
}
