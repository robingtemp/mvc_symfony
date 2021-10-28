<?php

namespace App\Controller;

// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

// Yatzy
use App\Entity\Yatzy;
use App\Entity\YatzyHand;

// Game21
use App\Entity\Dice;
use App\Entity\DiceHand;
use App\Entity\DiceRound;
use App\Entity\DiceComputerRound;

$test = new Dice(6);

class IndexController extends AbstractController
{   
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $number = 1;
        return $this->render('pages/index.html.twig', [
            'number' => $number,
        ]);
    }

    /**
     * @Route("/game21", name="game21")
     */
    public function game21View(HttpFoundationRequest $request)
    {
        $session = $request->getSession();

        // Messages
        $roundMessage = $session->get('roundMessage') ?? null;
        $validRound =  $session->get('validRound') ?? true;

        return $this->render('pages/game21.html.twig', [
            'roundMessage' => $roundMessage,
            'validRound' => $validRound
        ]);
    }

    /**
     * @Route("/game21/process", name="game21FormProcessing")
     */
    public function game21Process(HttpFoundationRequest $request)
    {
        $session = $request->getSession();
        $validRound = $session->get('validRound') ?? true;

        // Player scores
        $playerTotalScore = $session->get('playerTotalScore') ?? 0;
        $playerLastScore = $session->get('playerLastScore') ?? 0;

        // Post parameters
        $roll = $request->get('roll') ?? null;
        $stay = $request->get('stay') ?? null;
        $reset = $request->get('reset') ?? null;

        // For player
        $diceAmount = $request->get('radiobtn__dice') ?? null;

        // Player
        if ($roll != null && $diceAmount != null && $validRound != false) {

            // Rolling player dices
            $round = new DiceRound('player', $playerTotalScore, $diceAmount);
            $round->playerRoll();
            $round->validateRoll();

            $playerTotalScoreUpdated = $round->getPlayerTotalScore();
            $validRound =  $round->getValidRound();

            $session->set('playerLastScore', $playerLastScore);
            $session->set('playerTotalScore', $playerTotalScoreUpdated);

            // Getting & setting score messages
            $roundMessage = $round->getRoundMessage();
            $session->set('roundMessage', $roundMessage);
            $session->set('validRound', $validRound);
        
        // Computer
        } else if ($stay != null && $validRound != false) {

            // Auto roll
            $round = new DiceRound('computer', $playerTotalScore, 1);
            $round->validateRoll();

            $roundMessage = $round->getRoundMessage();
            $validRound =  $round->getValidRound();

            $session->set('roundMessage', $roundMessage);
            $session->set('validRound', $validRound);

        // Reset    
        } else if ($reset != null) {
            $session->invalidate();
        }

        return $this->redirectToRoute('game21');
    }









    // /**
    //  * @Route("/yatzy", name="yatzy")
    //  */
    // public function yatzy(HttpFoundationRequest $request)
    // {
    //     $session = $request->getSession();

    //     // Session vars
    //     $currRoundSession = $session->get('yatzy_current_round') ?? 1;
    //     $currThrowSession = $session->get('yatzy_current_throw') ?? 0;
    //     $currSumSession = $session->get('yatzy_sum') ?? 0;
    //     $currValuesSession = $session->get('yatzy_values') ?? [];

    //     // Class vars
    //     $round = new Yatzy($currRoundSession, $currThrowSession, $currSumSession);
    //     $currDiceAmount = $round->getDiceCount(count($currValuesSession));
    //     $currRound = $round->getCurrRound();
    //     $currThrow = $round->getCurrThrow();
    //     $currSum = $round->getSum();

    //     $dices = null;
    //     $finishedRound = false;
        
    //     if ($currThrow < 3 && $currDiceAmount > 0) {
    //         $throw = new YatzyHand($currDiceAmount);
    //         $dices = $throw->getDiceValues();
    //         $finishedRound = true;
    //     }

    //     return $this->render('pages/yatzy.html.twig', [
    //         'currentRound' => $currRound,
    //         'dices' => $dices,
    //         'currThrow' => $currThrow,
    //         'currDiceAmount' => $currDiceAmount,
    //         'currSum' => $currSum,
    //         'finishedRound' => $finishedRound,
    //     ]);
    // }

    // /**
    //  * @Route("/yatzy/process", name="yatzyFormProcessing")
    //  * @param Request $request
    //  */
    // public function yatzyFormProcessing(HttpFoundationRequest $request)
    // {
    //     $session = $request->getSession();

    //     $currThrow = $session->get('yatzy_current_throw') + 1;
    //     $session->set('yatzy_current_throw', $currThrow);

    //     $n0 = $request->get('n0');
    //     $n1 = $request->get('n1');
    //     $n2 = $request->get('n2');
    //     $n3 = $request->get('n3');
    //     $n4 = $request->get('n4');

    //     if ($n0 != null) {
    //         array_push($session->get('yatzy_values'), $n0);
    //     }

    //     if ($n1 != null) {
    //         array_push($session->get('yatzy_values'), $n1);
    //     }

    //     if ($n2 != null) {
    //         array_push($session->get('yatzy_values'), $n2);
    //     }

    //     if ($n3 != null) {
    //         array_push($session->get('yatzy_values'), $n3);
    //     }

    //     if ($n4 != null) {
    //         array_push($session->get('yatzy_values'), $n4);
    //     }

    //     // Next round
    //     if ($session->get('next_round') != null) {
    //         $session->set('yatzy_values', []);
    //         $session->set('yatzy_current_throw', 0);
    //     }

    //     // Reset game
    //     if ($session->get('reset_full_gale') != null) {
    //         $session->invalidate();
    //     }

    //     // Count points
    //     if ($session->get('yatzy_current_throw') != null) {
    //         if ($session->get('yatzy_current_throw') == 3) {
    //             foreach ($session->get('yatzy_values') as $val) {
    //                 if ($val == $session->get('yatzy_current_round')) {
    //                     $newVal = $session->get('yatzy_sum') + $val;
    //                     $session->set('yatzy_sum', $newVal);
    //                 }
    //             }
    //         }
    //     }

    //     if ($session->get('yatzy_current_round') == 6 && $session->get('yatzy_current_throw') == 3 && $session->get('yatzy_sum') > 63) {
    //         $newSum = $session->get('yatzy_sum') + 50;
    //         $session->set('yatzy_sum', $newSum);
    //     }
        
    //     // Returning to Yatzy route
    //     return $this->redirectToRoute('yatzy');
    // }

    // // /**
    // //  * @Route("/routetest", name="routetest")
    // //  * @param Request $request
    // //  */
    // // public function routeTest(HttpFoundationRequest $request)
    // // {
    // //     // $session = $request->getSession();
    // //     // $session->set('test', 'value');
    // //     // $foo = $session->get('test');
    // //     // return new Response($foo);
    // // }
}