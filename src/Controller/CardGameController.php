<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardGameController extends AbstractController
{
    #[Route("/card", name: "card_start")]
    public function home(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();

        $deck->shuffle();

        $session->set("deck", $deck);

        return $this->render('card/home.html.twig');
    }

    #[Route("/card/deck", name: "card_deck")]
    public function cardDeck(): Response
    {
        $deck = new DeckOfCards();

        $data = [
            "cards" => $deck->getString(),
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "deck_shuffle")]
    public function cardShuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set("deck", $deck);

        $data = [
            "cards" => $deck->getString(),
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "deck_draw")]
    public function cardDraw(SessionInterface $session): Response
    {
        if ($session->get("deck")->getNumberCards() <= 0) {
            throw new \Exception("Can't draw more cards than the deck currently contains.");
        }

        $deck = $session->get("deck");
        $hand = new CardHand();

        $card = $deck->draw();

        $hand->add($card);

        $data = [
            "cards" => $hand->getString(),
            "cardsLeft" => $deck->getNumberCards(),
        ];

        return $this->render('card/draw.html.twig', $data);
    }

    #[Route("/card/deck/draw/{number<\d+>}", name: "deck_draw_many")]
    public function cardDrawMany(SessionInterface $session, int $number): Response
    {

        if ($number > 52) {
            throw new \Exception("Can't draw more cards than the deck contains.");
        } elseif ($number > $session->get("deck")->getNumberCards()) {
            throw new \Exception("Can't draw more cards than the deck currently contains.");
        }

        $deck = $session->get("deck");
        $hand = new CardHand();

        for ($i = 1; $i <= $number; $i++) {
            $card = $deck->draw();
            $hand->add($card);
        }

        $data = [
            "cards" => $hand->getString(),
            "cardsLeft" => $deck->getNumberCards(),
        ];

        return $this->render('card/draw.html.twig', $data);
    }
}
