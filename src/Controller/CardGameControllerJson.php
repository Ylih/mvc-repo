<?php

namespace App\Controller;

use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\TwentyOneGame;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardGameControllerJson extends AbstractController
{
    #[Route("/api/game", name: "api_twenty_one_game", methods: ["POST"])]
    public function jsonTwentyOneGame(SessionInterface $session): Response
    {
        /** @var \App\Card\TwentyOneGame|null $game */
        $game = $session->get("game");

        $data = [
            "game" => "No active game.",
        ];

        if ($game !== null) {
            $player = $game->getPlayer();
            $bank = $game->getBank();

            $data = [
                "player_hand" => $player->getArray(),
                "player_sum" => $player->getSum(),
                "bank_hand" => $bank->getArray(),
                "bank_sum" => $bank->getSum(),
                "active_game" => $game->getGame(),
            ];
        }

        //return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
}
