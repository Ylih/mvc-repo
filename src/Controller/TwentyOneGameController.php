<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\TwentyOneGame;
use Exception;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class TwentyOneGameController extends AbstractController
{
    #[Route("/game", name: "game_start")]
    public function home(): Response
    {
        return $this->render('game/home.html.twig');
    }

    #[Route("/game/init", name: "game_init", methods: ["POST"])]
    public function gameInit(SessionInterface $session): Response
    {
        $game = new TwentyOneGame();
        $game->getDeck()->shuffle();

        $session->set("game", $game);

        return $this->redirectToRoute('game_play');
    }

    #[Route("/game/play", name: "game_play", methods: ['GET'])]
    public function gameBoard(SessionInterface $session): Response
    {
        /** @var \App\Card\TwentyOneGame|null $game */
        $game = $session->get("game");
        if ($game === null) {
            $game = new TwentyOneGame();
            $game->getDeck()->shuffle();

            $session->set("game", $game);
        }

        $player = $game->getPlayer();
        $bank = $game->getBank();

        $data = [
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
        /** @var \App\Card\TwentyOneGame $game */
        $game = $session->get("game");
        $deck = $game->getDeck();
        $hand = $game->getPlayer();

        $card = $deck->draw();
        $hand->add($card);

        if ($game->checkLimit($hand) && $game->containsAce($hand)) {
            $game->handleAce($hand);
        }

        if ($game->checkLimit($hand)) {
            $game->setGameOver();
            $this->addFlash(
                'winner',
                'Bank wins'
            );
        }

        $game->setPlayer($hand);
        $game->setDeck($deck);

        $session->set("game", $game);

        return $this->redirectToRoute('game_play');
    }

    #[Route("/game/stay", name: "game_stay", methods: ["POST"])]
    public function gameStay(SessionInterface $session): Response
    {
        /** @var \App\Card\TwentyOneGame $game */
        $game = $session->get("game");
        $deck = $game->getDeck();
        $bank = $game->getBank();

        $game->autoDraw($bank, $deck);

        $game->setGameOver();

        if ($game->checkLimit($bank)) {
            $this->addFlash(
                'winner',
                'Player wins'
            );
        }

        if (!$game->checkLimit($bank)) {
            $winner = $game->compareHands();
            $this->addFlash(
                'winner',
                "{$winner} wins"
            );
        }

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
