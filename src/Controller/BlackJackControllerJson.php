<?php

namespace App\Controller;

use App\Blackjack\Card;
use App\Blackjack\Deck;
use App\Blackjack\Hand;
use App\Blackjack\Player;
use App\Blackjack\BlackjackGame;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardGameControllerJson extends AbstractController
{
    #[Route("proj/api/game", name: "api_blackjack_game", methods: ["POST"])]
    public function blackjackGameJson(SessionInterface $session): Response
    {
        /** @var \App\Blackjack\BlackjackGame|null $game */
        $game = $session->get("game");

        $data = [
            "game" => "No active game.",
        ];

        if ($game !== null) {
            $player = [
                "name" => $game->getPlayer()->getName(),
                "balance" => $game->getPlayer()->getMoney(),
                "hands" => $game->getPlayer()->getHandsAssociative(),
                "currentHand" => $game->getPlayer()->getCurrent(),
            ];

            $data = [
                "player" => $player,
                "bank" => $game->getBank()->getAssociative(),
                "gameOver" => $game->isGameOver(),
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("proj/api/start", name: "api_blackjack_start", methods: ["POST"])]
    public function startBlackjackGameJson(SessionInterface $session): Response
    {
        $game = new BlackjackGame();

        if ($session->get("hasDealt")) {
            $session->set("hasDealt", false);
        }

        $game->getPlayer()->setName("Test testsson");
        $game->getPlayer()->setMoney(150);
        $game->getPlayer()->createHands(2);

        $game->getDeck()->createNormalDeck();
        $game->getDeck()->shuffle();

        $player = [
            "name" => $game->getPlayer()->getName(),
            "balance" => $game->getPlayer()->getMoney(),
            "hands" => $game->getPlayer()->getHandsAssociative(),
            "currentHand" => $game->getPlayer()->getCurrent(),
        ];

        $data = [
            "player" => $player,
            "bank" => $game->getBank()->getAssociative(),
            "gameOver" => $game->isGameOver(),
        ];

        $session->set("apiGame", $game);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("proj/api/deal", name: "api_blackjack_deal", methods: ["POST"])]
    public function dealBlackjackGameJson(SessionInterface $session): Response
    {
        /** @var \App\Blackjack\BlackjackGame|null $game */
        $game = $session->get("apiGame");

        $data = [
            "game" => "Start a game first.",
        ];

        if ($game !== null) {
            $game->takeBets([20, 30]);
            $game->deal();
            $game->controlBlackjack();

            $game->getPlayer()->nextPlayableHand();

            $player = [
                "name" => $game->getPlayer()->getName(),
                "balance" => $game->getPlayer()->getMoney(),
                "hands" => $game->getPlayer()->getHandsAssociative(),
                "currentHand" => $game->getPlayer()->getCurrent(),
            ];

            $data = [
                "player" => $player,
                "bank" => $game->getBank()->getAssociative(),
                "gameOver" => $game->isGameOver(),
            ];

            if ($game->getPlayer()->isAllPlayed()) {
                $game->setGameOver();
                $data["gameOver"] = $game->isGameOver();
            }

            $session->set("apiGame", $game);
            $session->set("hasDealt", true);
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("proj/api/hit", name: "api_blackjack_hit", methods: ["POST"])]
    public function hitBlackjackGameJson(SessionInterface $session): Response
    {
        /** @var \App\Blackjack\BlackjackGame|null $game */
        $game = $session->get("apiGame");
        $dealt = $session->get("hasDealt");

        $data = [
            "game" => "Start a game first and then deal cards.",
        ];

        if ($game !== null && $dealt === true) {
            $hand = $game->getPlayer()->getActiveHand();

            $card = $game->getDeck()->draw();
            $hand->add($card);

            $game->controlHand($hand);
            $game->getPlayer()->nextPlayableHand();

            $player = [
                "name" => $game->getPlayer()->getName(),
                "balance" => $game->getPlayer()->getMoney(),
                "hands" => $game->getPlayer()->getHandsAssociative(),
                "currentHand" => $game->getPlayer()->getCurrent(),
            ];

            $data = [
                "player" => $player,
                "bank" => $game->getBank()->getAssociative(),
                "gameOver" => $game->isGameOver(),
            ];

            if ($game->getPlayer()->isAllPlayed()) {
                $game->setGameOver();
                $data["gameOver"] = $game->isGameOver();
            }

            $session->set("apiGame", $game);
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("proj/api/stand", name: "api_blackjack_stand", methods: ["POST"])]
    public function standBlackjackGameJson(SessionInterface $session): Response
    {
        /** @var \App\Blackjack\BlackjackGame|null $game */
        $game = $session->get("apiGame");
        $dealt = $session->get("hasDealt");

        $data = [
            "game" => "Start a game first and then deal cards.",
        ];

        if ($game !== null && $dealt === true) {

            $game->getPlayer()->getActiveHand()->setStatus("Stand");
            $game->getPlayer()->getActiveHand()->setPlayed();
            $game->getPlayer()->reduceCurrent();
            $game->getPlayer()->nextPlayableHand();

            $player = [
                "name" => $game->getPlayer()->getName(),
                "balance" => $game->getPlayer()->getMoney(),
                "hands" => $game->getPlayer()->getHandsAssociative(),
                "currentHand" => $game->getPlayer()->getCurrent(),
            ];

            $data = [
                "player" => $player,
                "bank" => $game->getBank()->getAssociative(),
                "gameOver" => $game->isGameOver(),
            ];

            if ($game->getPlayer()->isAllPlayed()) {
                $game->setGameOver();
                $data["gameOver"] = $game->isGameOver();
            }

            $session->set("apiGame", $game);
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("proj/api/end", name: "api_blackjack_end", methods: ["POST"])]
    public function endBlackjackGameJson(SessionInterface $session): Response
    {
        /** @var \App\Blackjack\BlackjackGame|null $game */
        $game = $session->get("apiGame");
        $dealt = $session->get("hasDealt");

        $data = [
            "game" => "Start a game first and then deal cards.",
        ];

        if ($game !== null && $dealt === true) {

            $game->autoDraw($game->getBank(), $game->getDeck());
            $game->payOut();

            $player = [
                "name" => $game->getPlayer()->getName(),
                "balance" => $game->getPlayer()->getMoney(),
                "hands" => $game->getPlayer()->getHandsAssociative(),
                "currentHand" => $game->getPlayer()->getCurrent(),
            ];

            $data = [
                "player" => $player,
                "bank" => $game->getBank()->getAssociative(),
                "gameOver" => $game->isGameOver(),
            ];

            if ($game->getPlayer()->isAllPlayed()) {
                $game->setGameOver();
                $data["gameOver"] = $game->isGameOver();
            }

            $session->set("apiGame", $game);
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
}
