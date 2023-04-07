<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DiceGameController extends AbstractController
{
    #[Route("/card", name: "card_start")]
    public function home(): Response
    {
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
    public function cardShuffle(): Response {
        $deck = new DeckOfCards();
        $deck->shuffle();

        $data = [
            "cards" => $deck->getString(),
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "deck_draw", methods: ['GET'])]
    public function cardDraw(): Response
    {
        //Dra ett kort ur kortleken och visa upp det. Visa även hur många kort det är kvar i kortleken.
        return $this->render('pig/play.html.twig', $data);
    }

    #[Route("/card/deck/draw/{number<\d+>}", name: "deck_draw_many", methods: ['POST'])]
    public function roll(int $number): Response
    {
        //Dra X kort ur kortleken och visa upp dem. Visa även hur många kort det är kvar i kortleken.
        //Throw exception "kan inte dra mer kort än kortleken."
        return $this->redirectToRoute('pig_play');
    }
}
