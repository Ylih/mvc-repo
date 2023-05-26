<?php

namespace App\Blackjack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player.
 */
class PlayerTest extends TestCase
{
    /**
     * Construct object.
     */
    public function testCreateObject(): void
    {
        $player = new Player();
        $name = $player->getName();
        $money = $player->getMoney();
        $hands = $player->getHands();
        $currentHand = $player->getCurrent();

        $this->assertInstanceOf("\App\Blackjack\Player", $player);
        
        $this->assertEquals($name, "");
        $this->assertEquals($money, 0);
        $this->assertIsArray($hands);
        $this->assertEmpty($hands);
        $this->assertEquals($currentHand, -1);
    }

    /**
     * Verify set methods for Player attribute works.
     */
    public function testSet(): void
    {
        $player = new Player();

        $handsArr = [new Hand(), new Hand(), new Hand()];

        $this->assertInstanceOf("\App\Blackjack\Player", $player);
        
        $this->assertEquals($player->getName(), "");
        $this->assertEquals($player->getMoney(), 0);
        $this->assertIsArray($player->getHands());
        $this->assertEmpty($player->getHands());
        $this->assertEquals($player->getCurrent(), -1);

        $player->setName("John Doe");
        $player->setMoney(1337);
        $player->setHands($handsArr);

        $this->assertEquals($player->getName(), "John Doe");
        $this->assertEquals($player->getMoney(), 1337);
        $this->assertIsArray($player->getHands());
        $this->assertCount(3, $player->getHands());
    }

    /**
     * Verify createHands works.
     */
    public function testCreateHands(): void
    {
        $player = new Player();
        $this->assertEmpty($player->getHands());
        $this->assertEquals($player->getCurrent(), -1);

        $player->createHands(3);
        $this->assertContainsOnlyInstancesOf("\App\Blackjack\Hand", $player->getHands());
        $this->assertCount(3, $player->getHands());
        $this->assertEquals($player->getCurrent(), 3);
    }

    /**
     * Verify getMoneyStatus works.
     */
    public function testGetMoneyStatus(): void
    {
        $player = new Player();

        $hand1 = new Hand();
        $hand2 = new Hand();

        $hand1->setStake(12);
        $hand2->setStake(34);

        $player->setHands([$hand1, $hand2]);

        $noMoney = $player->getMoneyStatus();
        $this->assertFalse($noMoney["sameBet"]);
        $this->assertFalse($noMoney["minBet"]);

        $player->addMoney(30);
        $someMoney = $player->getMoneyStatus();
        $this->assertFalse($someMoney["sameBet"]);
        $this->assertTrue($someMoney["minBet"]);
        $this->assertEquals($player->getMoney(), 30);

        $player->addMoney(1307);
        $cashMoney = $player->getMoneyStatus();
        $this->assertTrue($cashMoney["sameBet"]);
        $this->assertTrue($cashMoney["minBet"]);
        $this->assertEquals($player->getMoney(), 1337);
    }

    /**
     * Verify getHandOnIndex works.
     */
    public function testGetHandOnIndex(): void
    {
        $player = new Player();

        $hand = new Hand();

        $player->setHands([$hand]);

        $isNull = $player->getHandOnIndex(42);
        $exp = null;
        $this->assertEquals($isNull, $exp);

        $isHand = $player->getHandOnIndex(0);
        $this->assertInstanceOf("\App\Blackjack\Hand", $isHand);
    }

    /**
     * Verify getActiveHand works.
     */
    public function testGetActiveHand(): void
    {
        $player = new Player();

        $player->createHands(2);

        $stake = 20;
        $temp = [];
        foreach ($player->getHands() as $hand) {
            $hand->setStake($stake);
            $stake = $stake * 2;
            $temp[] = $hand;
        }

        $player->setHands($temp);

        $active = $player->getActiveHand();
        $activeSum = $active->getStake();
        $exp = 40;
        $this->assertEquals($activeSum, $exp);

        $player->reduceCurrent();

        $active = $player->getActiveHand();
        $activeSum = $active->getStake();
        $exp = 20;
        $this->assertEquals($activeSum, $exp);
    }

    /**
     * Verify nextPlayableHand works.
     */
    public function testNextPlayableHand(): void
    {
        $player = new Player();

        $player->createHands(3);

        $count = 0;
        foreach ($player->getHands() as $hand) {
            if ($count === 0) {
                $hand->setStake(101);
                $count += 1;
                continue;
            }
            $hand->setPlayed();
        }

        $hands = $player->getHands();

        $this->assertFalse($hands[0]->isPlayed());
        $this->assertTrue($hands[1]->isPlayed());
        $this->assertTrue($hands[2]->isPlayed());
        $this->assertFalse($player->isAllPlayed());
        $this->assertEquals($player->getCurrent(), 3);

        $hand = $player->nextPlayableHand();
        $this->assertEquals($player->getCurrent(), 1);
        $this->assertEquals($hand->getStake(), 101);

        $hand->setPlayed();
        $finalHand = $player->nextPlayableHand();
        $this->assertEquals($player->getCurrent(), -1);
        $this->assertEquals($hand, $finalHand);
        $this->assertTrue($player->isAllPlayed());
    }

    /**
     * Verify getStakes works.
     */
    public function testGetStakes(): void
    {
        $player = new Player();

        $player->createHands(3);

        $stake = 20;
        foreach ($player->getHands() as $hand) {
            $hand->setStake($stake);
            $stake = $stake * 2;
        }

        $res = $player->getStakes();
        $exp = [20, 40, 80];
        $this->assertEquals($res, $exp);
    }

    /**
     * Verify getHandsAssociative works.
     */
    public function testGetHandsAssociative(): void
    {
        $player = new Player();

        $player->createHands(3);

        $handsArr = $player->getHandsAssociative();

        $exp = [
            [
                "values" => [],
                "stake" => 0,
                "sum" => 0,
                "status" => "",
            ],
            [
                "values" => [],
                "stake" => 0,
                "sum" => 0,
                "status" => "",
            ],
            [
                "values" => [],
                "stake" => 0,
                "sum" => 0,
                "status" => "",
            ],
        ];

        $this->assertEquals($handsArr, $exp);
    }


}
