<?php

class ListEndpointCest
{
    public function _before(DogsTester $I)
    {
    }

    public function _after(DogsTester $I)
    {
    }

    // tests
    public function listEndpointShowsDogs(DogsTester $I)
    {
        $I->wantTo('view all dogs');
        $I->sendGET('');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            "id" => "4",
            "name" => "Poochie",
            "breed" => "Labrador",
            "created_at" => "2017-02-20 02:10:31",
            "updated_at" => "2017-02-20 06:08:00",
        ]);
    }
}
