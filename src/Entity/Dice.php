<?php

namespace App\Entity;

class Dice
{
    private $sides = 6;
    private $value = 0;

    public function __construct($sides)
    {
        $this->diceSides = $sides;
    }

    public function roll()
    {
        $this->value = rand(1, $this->sides);
    }

    public function getValue()
    {
        return $this->value;
    }
}

class DiceHand
{
    private $amount = 0;
    private $arr = [];
    private $arrNew = [];
    private $sides;
    private $sum = 0;

    public function __construct($amount, $sides = 6)
    {
        $this->amount = $amount;
        $this->sides = $sides;

        // Creating Dice-objects
        for ($i = 0; $i < $this->amount; $i++) {
            array_push($this->arr, new Dice($this->sides));
        }
    }

    public function rollDices()
    {
        foreach ($this->arr as &$dice) {
            $dice->roll();
            $this->sum += $dice->getValue();
        }
    }

    public function getDiceSum()
    {
        return $this->sum;
    }

    public function getDiceArr()
    {
        foreach ($this->arr as &$dice) {
            array_push($this->arrNew, $dice->getValue());
        }

        return $this->arrNew;
    }
}

class DiceRound
{
    private $currentPlayer;
    private $computerScore = 0;
    private $roundMessage;
    private $diceAmount;
    private $diceArr;
    private $playerTotalScore;
    private $playerLastScore;
    private $validRound = true;

    public function __construct($currentPlayer, $playerTotalScore, $diceAmount = 1)
    {
        $this->currentPlayer = $currentPlayer;
        $this->diceAmount = $diceAmount;
        $this->playerTotalScore = $playerTotalScore;

        // Auto roll for computer
        if ($this->currentPlayer === "computer") {
            while ($this->computerScore < $this->playerTotalScore && $this->computerScore < 22) {
                $hand = new DiceHand($this->diceAmount);
                $hand->rollDices();
                $this->computerScore += $hand->getDiceSum();
            }
        }
    }

    public function playerRoll() {
        // Rolling
        $hand = new DiceHand($this->diceAmount);
        $hand->rollDices();
        $sum = $hand->getDiceSum();

        $this->playerScore = $sum;
        $this->playerTotalScore = $this->playerTotalScore + $sum;
        $this->roundMessage = "Du slog " . $sum . ". Du har totalt " . $this->playerTotalScore . " poäng.";
        // $this->diceArr = $hand->getDiceArr();
    }

    public function validateRoll() {
        if ($this->computerScore > 21) {
            $this->roundMessage = "Du vann rundan med poängen " . $this->playerTotalScore . " mot datorns " . $this->computerScore . " poäng! GRATTIS!";
            $this->validRound = false;
        } else if ($this->computerScore == 21 || $this->computerScore > $this->playerTotalScore) {
            $this->roundMessage = "Datorn vann rundan med poängen " . $this->computerScore . " mot dina " . $this->playerTotalScore . " poäng!";
            $this->validRound = false;
        } else if ($this->playerTotalScore > 21) {
            $this->roundMessage = "Du slog " . $this->playerTotalScore . " och förlorade eftersom det är över 21!";
            $this->validRound = false;
        } else if ($this->playerTotalScore == 21) {
            $this->roundMessage = "Du slog " . $this->playerTotalScore . " och vann. GRATTIS!";
            $this->validRound = false;
        } else {
            return;
        }
    }

    public function getPlayerScore() {
        return $this->playerScore;
    }

    public function getPlayerTotalScore() {
        return $this->playerTotalScore;
    }

    public function getComputerScore() {
        return $this->computerScore;
    }

    public function getRoundMessage() {
        return $this->roundMessage;
    }

    public function getValidRound() {
        return $this->validRound;
    }

    // public function getDiceArr() {
    //     return $this->diceArr;
    // }
}