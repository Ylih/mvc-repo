<?php

namespace App\Card;

class CardHand
{
    /** @var Card[] */
    private array $hand = [];

    public function add(Card $card): void
    {
        $this->hand[] = $card;
    }

    //discard function?

    /**
     * @return Card[]
     */
    public function getHand(): array
    {
        return $this->hand;
    }

    public function countCards(): int
    {
        return count($this->hand);
    }

    /**
     * @return string[]
     */
    public function getString(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsString();
        }
        return $values;
    }

    /**
     * @return array<int, array{ "type": string, "name": string, "value": int }>
     */
    public function getArray(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsArray();
        }
        return $values;
    }

    /**
     * @return string[]
     */
    public function getLowRes(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsLowRes();
        }
        return $values;
    }
}
