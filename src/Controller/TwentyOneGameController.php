<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\Game;
use Exception;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class TwentyOneGameController extends AbstractController
{
    #[Route("/game", name: "game_start")]
    public function home(SessionInterface $session): Response
    {
        return $this->render('game/home.html.twig');
    }

    #[Route("/game/init", name: "game_init", methods: ["POST"])]
    public function gameInit(SessionInterface $session): Response 
    {
        $game = new Game(new CardHand(), new CardHand(), new DeckOfCards());
        $game->getDeck()->shuffle();

        $session->set("game", $game);

        return $this->redirectToRoute('game_play');
    }

    #[Route("/game/play", name: "game_play")]
    public function gameBoard(SessionInterface $session): Response
    {
        $game = $session->get("game");
        $deck = $game->getDeck();
        $player = $game->getPlayer();
        $bank = $game->getBank();

        //Kolla om någon hand är över 21
        //Kolla om den handen har ett ess
        //Sätt värdet på esset till 1
        //kolla om spelet fortfarande är över
        //OM INTE fortsätt
        //ANNARS avgör vinnare

        if ($game->checkLimit($player) || $game->checkLimit($bank)) {
            $game->setGameOver();
        }

        $data = [
            "cardsLeft" => $deck->getNumberCards(),
            "player_hand" => $player->getString(),
            "player_sum" => $player->getSum(),
            "bank_hand" => $bank->getString(),
            "bank_sum" => $bank->getSum(),
            "game" => $game->getGame(),
        ];
        return $this->render('game/gameboard.html.twig', $data);
    }

    #[Route("/game/draw", name: "game_draw", methods: ["POST"])]
    public function gameDraw(SessionInterface $session): Response 
    {
        $game = $session->get("game");
        $deck = $game->getDeck();
        $hand = $game->getPlayer();

        $card = $deck->draw();
        $hand->add($card);

        $game->setPlayer($hand);
        $game->setDeck($deck);

        $session->set("game", $game);

        return $this->redirectToRoute('game_play');
    }

    #[Route("/game/stay", name: "game_stay", methods: ["POST"])]
    public function gameStay(SessionInterface $session): Response 
    {
        $game = $session->get("game");
        $deck = $game->getDeck();
        $bank = $game->getBank();

        $game->autoDraw($bank, $deck);

        //Check if $bank is over 21
        //if $bank is over 21 check if contains ace and ace value is 14.
        //(method to loop through hand and look for aces if statement will be something like "if $card->getName === "ace" && $card->getValue === 14)
        //if true set ace value to 1
        //break the loop after one ace has been set to 1?

        $game->setBank($bank);
        $game->setDeck($deck);

        $session->set("game", $game);

        return $this->redirectToRoute('game_play');
    }

    #[Route("/game/doc", name: "game_doc")]
    public function documentation(): Response
    {
        return $this->render('game/doc.html.twig');
    }
}
