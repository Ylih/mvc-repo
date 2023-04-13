<?php

namespace App\Controller;

use App\Card\CardHand;
use App\Card\DeckOfCards;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DeckControllerJson extends AbstractController
{
    #[Route("/api/deck", name: "api_deck", methods: ["GET"])]
    public function jsonDeck(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();

        $session->set("deck", $deck);

        $data = [
            'deck' => $deck->getArray(),
        ];

        //return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ["POST"])]
    public function jsonDeckShuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set("deck", $deck);

        $deck->shuffle();

        $data = [
            'deck' => $deck->getArray(),
        ];

        //return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ["POST"])]
    public function jsonDeckDraw(SessionInterface $session): Response
    {
        /** @var \App\Card\DeckOfCards $deck */
        $deck = $session->get("deck");

        $card = $deck->draw();

        $data = [
            "cardsLeft" => $deck->getNumberCards(),
            'card' => $card->getAsArray(),
        ];

        //return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("/api/deck/process", name: "api_setup_draw_many", methods: ["POST"])]
    public function jsonDeckDrawManyProcess(Request $request): Response
    {
        $numberOfCards = $request->request->get('num_cards');

        return $this->redirectToRoute('api_deck_draw_many', ['number' => $numberOfCards], 307);
    }

    #[Route("/api/deck/draw/{number<\d+>}", name: "api_deck_draw_many", methods: ["POST"])]
    public function jsonDeckDrawMany(SessionInterface $session, int $number): Response
    {
        /** @var \App\Card\DeckOfCards $deck */
        $deck = $session->get("deck");
        $hand = new CardHand();

        for ($i = 1; $i <= $number; $i++) {
            $card = $deck->draw();
            $hand->add($card);
        }

        $data = [
            "cardsLeft" => $deck->getNumberCards(),
            "cards" => $hand->getArray(),
        ];

        //return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
}
