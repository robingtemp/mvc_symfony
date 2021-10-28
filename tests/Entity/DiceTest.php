<?php
namespace App\Tests\Entity;

use App\Entity\Dice;
use App\Entity\DiceHand;
use App\Entity\DiceRound;

use PHPUnit\Framework\TestCase;

class DiceTest extends TestCase {


    public function testCreateDice() 
    {
        $dice = new Dice(1);
        $this->assertInstanceOf(Dice::class, $dice);
    }

    public function testExpectedDiceValue()
    {
        $dice = new Dice(1);
        $dice->roll();
        $res = $dice->getValue();
        $this->assertIsInt($res);
    }

    public function testCreateDiceHandObject()
    {
        $diceHand = new DiceHand(1);
        $this->assertInstanceOf(DiceHand::class, $diceHand);
    }

    public function testRollAllDices()
    {
        $diceHand = new DiceHand(1);
        $diceHand->rollDices();
        $sum = $diceHand->getDiceSum();
        $this->assertIsInt($sum);
    }

    public function testGetDiceSum()
    {
        $diceHand = new DiceHand(1);
        $sum = $diceHand->getDiceSum();
        $this->assertEquals($sum, 0);
    }

    public function testGetDiceArr()
    {
        $diceHand = new DiceHand(1);
        $arr = $diceHand->getDiceArr();
        $this->assertIsArray($arr);
    }

    public function testCreateDiceRound()
    {
        $diceRound = new DiceRound('player', 5, 1);
        $this->assertInstanceOf(DiceRound::class, $diceRound);
    }

    public function testCreateDiceRoundComputer()
    {
        $diceRound = new DiceRound('computer', 5, 1);
        $this->assertInstanceOf(DiceRound::class, $diceRound);
        $this->assertGreaterThan(0, $diceRound->getComputerScore());
    }

    public function testPlayerRoll()
    {
        $diceRound = new DiceRound('player', 5, 1);
        $diceRound->playerRoll();
        $this->assertGreaterThan(0, $diceRound->getPlayerScore());
    }

    public function testValidateRoll()
    {
        $diceRound = new DiceRound('player', 5, 1);
        $diceRound->playerRoll();
        $diceRound->validateRoll();
        $this->assertNotEmpty($diceRound->getRoundMessage());
    }
    
    public function testGetPlayerScore()
    {
        $diceRound = new DiceRound('player', 5, 1);
        $diceRound->playerRoll();
        $this->assertGreaterThan(0, $diceRound->getPlayerScore());
    }

    public function testGetPlayerTotalScore()
    {
        $diceRound = new DiceRound('player', 5, 1);
        $diceRound->playerRoll();
        $this->assertGreaterThan(0, $diceRound->getPlayerTotalScore());
    }

    public function testGetComputerScore()
    {
        $diceRound = new DiceRound('computer', 5, 1);
        $this->assertGreaterThan(0, $diceRound->getComputerScore());
    }

    public function testGetRoundMessage()
    {
        $diceRound = new DiceRound('computer', 21, 10);
        $diceRound->validateRoll();
        $this->assertIsString($diceRound->getRoundMessage());
    }

    public function testGetValidRound()
    {
        $diceRound = new DiceRound('computer', 5, 1);
        $diceRound->validateRoll();
        $this->assertIsBool($diceRound->getValidRound());
    }
}