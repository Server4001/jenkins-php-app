<?php
/**
 * @category     Tests
 * @package      JenkinsPhpApp
 * @copyright    Copyright (c) 2017 Bentler Design (www.bricebentler.com)
 * @author       Brice Bentler <me@bricebentler.com>
 */

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class DogsTester extends \Codeception\Actor
{
    use _generated\DogsTesterActions;

    public function createDog(string $dogName, string $dogBreed): int
    {
        $this->sendPOST('', [
            'name' => $dogName,
            'breed' => $dogBreed,
        ]);

        $this->seeResponseCodeIs(201);
        $this->seeResponseIsJson();

        $response = json_decode($this->grabResponse(), true);

        return (int)$response['data']['dog_id'];
    }

    public function deleteDog(int $dogId): DogsTester
    {
        $this->sendDELETE($dogId);
        $this->seeResponseCodeIs(204);

        return $this;
    }

    public function seeDog(int $dogId, string $dogName, string $dogBreed): DogsTester
    {
        $this->sendGET($dogId);

        $this->seeResponseCodeIs(200);
        $this->seeResponseIsJson();
        $this->seeResponseContainsJson([
            'status' => 'success',
            'data' => [
                'id' => $dogId,
                'name' => $dogName,
                'breed' => $dogBreed,
            ],
        ]);

        return $this;
    }
}
