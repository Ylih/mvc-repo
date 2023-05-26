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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BlackJackController extends AbstractController
{
    #[Route("/proj/blackjack", name: "blackjack_start")]
    public function home(): Response
    {
        return $this->render('proj/blackjack/home.html.twig');
    }

    #[Route("/proj/blackjack/init", name: "blackjack_init_get", methods: ["GET"])]
    public function gameInit(): Response
    {
        return $this->render('proj/blackjack/init.html.twig');
    }

    #[Route("/proj/blackjack/init", name: "blackjack_init_post", methods: ['POST'])]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        /** @var string $name */
        $name = $request->request->get('player_name');

        /** @var int $credit */
        $credit = $request->request->get('player_credit');

        /** @var int $hands */
        $hands = $request->request->get('num_hands');

        $game = new BlackjackGame();

        $player = $game->getPlayer();
        $player->setName($name);
        $player->setMoney($credit);
        $player->createHands($hands);

        $deck = $game->getDeck();
        $deck->createNormalDeck();
        $deck->shuffle();

        $game->setPlayer($player);
        $game->setDeck($deck);

        $session->set("game", $game);

        $data = [
            "player_hands" => $player->getHands(),
            "player_balance" => $player->getMoney(),
        ];

        return $this->render('proj/blackjack/bets.html.twig', $data);
    }

    #[Route("/proj/blackjack/deal", name: "set_blackjack_board", methods: ['POST'])]
    public function setGameBoard(Request $request, SessionInterface $session): Response
    {
        /** @var \App\Blackjack\BlackjackGame $game */
        $game = $session->get("game");
        $handBets = $request->request->all();

        $game->takeBets($handBets);
        $game->deal();
        $game->controlBlackjack();

        $session->set("game", $game);

        return $this->redirectToRoute('blackjack_play');
    }

    #[Route("/proj/blackjack/play", name: "blackjack_play", methods: ['GET'])]
    public function playCurrentHand(SessionInterface $session): Response
    {
        /** @var \App\Blackjack\BlackjackGame|null $game */
        $game = $session->get("game");

        if ($game === null) {
            return $this->redirectToRoute('blackjack_start');
        }

        $game->getPlayer()->nextPlayableHand();

        $data = [
            "current" => $game->getPlayer()->getCurrent(),
            "player_name" => $game->getPlayer()->getName(),
            "player_money" => $game->getPlayer()->getMoney(),
            "player_hands" => $game->getPlayer()->getHandsAssociative(),
            "bank_hand" => $game->getBank()->getString(),
            "bank_sum" => $game->getBank()->getSum(),
            "deck" => $game->getDeck()->getNumberCards(),
            "gameover" => $game->getGameOver(),
            "money_status" => $game->getPlayer()->getMoneyStatus(),
        ];

        return $this->render('proj/blackjack/gameboard.html.twig', $data);
    }

    #[Route("/proj/blackjack/hit", name: "blackjack_hit", methods: ["POST"])]
    public function blackjackHit(SessionInterface $session): Response
    {
        /** @var \App\Blackjack\BlackjackGame $game */
        $game = $session->get("game");
        $hand = $game->getPlayer()->getActiveHand();

        $card = $game->getDeck()->draw();
        $hand->add($card);

        $game->controlHand($hand);

        $session->set("game", $game);

        if ($game->getPlayer()->isAllPlayed()) {
            return $this->redirectToRoute('blackjack_bank', [], 307);
        }

        return $this->redirectToRoute('blackjack_play');
    }

    #[Route("/proj/blackjack/stand", name: "blackjack_stand", methods: ["POST"])]
    public function gameStand(SessionInterface $session): Response
    {
        /** @var \App\Blackjack\BlackjackGame $game */
        $game = $session->get("game");
        $game->getPlayer()->getActiveHand()->setStatus("Stand");
        $game->getPlayer()->getActiveHand()->setPlayed();
        $game->getPlayer()->reduceCurrent();

        if ($game->getPlayer()->isAllPlayed()) {
            return $this->redirectToRoute('blackjack_bank', [], 307);
        }

        return $this->redirectToRoute('blackjack_play');
    }

    #[Route("/proj/blackjack/bank", name: "blackjack_bank", methods: ["POST"])]
    public function bankDraw(SessionInterface $session): Response
    {
        /** @var \App\Blackjack\BlackjackGame $game */
        $game = $session->get("game");
        $game->autoDraw($game->getBank(), $game->getDeck());

        return $this->redirectToRoute('blackjack_process_hands', [], 307);
    }

    #[Route("/proj/blackjack/process", name: "blackjack_process_hands", methods: ["POST"])]
    public function processHands(SessionInterface $session): Response
    {
        /** @var \App\Blackjack\BlackjackGame $game */
        $game = $session->get("game");
        $game->payOut();
        $game->setGameOver();

        return $this->redirectToRoute('blackjack_play');
    }

    #[Route("/proj/blackjack/new", name: "blackjack_new_round", methods: ["GET"])]
    public function newRound(SessionInterface $session): Response
    {
        /** @var \App\Blackjack\BlackjackGame|null $game */
        $game = $session->get("game");

        if ($game === null || $game->getPlayer()->getMoneyStatus()["minBet"] === false) {
            return $this->redirectToRoute('blackjack_start');
        }

        $oldPlayer = $game->getPlayer();
        $bets = $oldPlayer->getStakes();

        $newGame = new BlackjackGame();

        $newPlayer = $newGame->getPlayer();
        $newPlayer->setName($oldPlayer->getName());
        $newPlayer->setMoney($oldPlayer->getMoney());
        $newPlayer->createHands(count($oldPlayer->getHands()));

        $newDeck = $newGame->getDeck();
        $newDeck->createNormalDeck();
        $newDeck->shuffle();

        $newGame->setPlayer($newPlayer);
        $newGame->setDeck($newDeck);

        $newGame->takeBets($bets);
        $newGame->deal();
        $newGame->controlBlackjack();

        $session->set("game", $newGame);

        return $this->redirectToRoute('blackjack_play');
    }

    #[Route("/proj/blackjack/bets", name: "blackjack_new_bets", methods: ["GET"])]
    public function newBets(SessionInterface $session): Response
    {
        /** @var \App\Blackjack\BlackjackGame|null $game */
        $game = $session->get("game");

        if ($game === null || $game->getPlayer()->getMoneyStatus()["minBet"] === false) {
            return $this->redirectToRoute('blackjack_start');
        }

        $oldPlayer = $game->getPlayer();

        $newGame = new BlackjackGame();

        $newPlayer = $newGame->getPlayer();
        $newPlayer->setName($oldPlayer->getName());
        $newPlayer->setMoney($oldPlayer->getMoney());
        $newPlayer->createHands(count($oldPlayer->getHands()));

        $newDeck = $newGame->getDeck();
        $newDeck->createNormalDeck();
        $newDeck->shuffle();

        $newGame->setPlayer($newPlayer);
        $newGame->setDeck($newDeck);

        $session->set("game", $newGame);

        $data = [
            "player_hands" => $newPlayer->getHands(),
            "player_balance" => $newPlayer->getMoney(),
        ];

        return $this->render('proj/blackjack/bets.html.twig', $data);
    }
}
