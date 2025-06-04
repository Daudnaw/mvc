<?php

namespace App\Card;

use App\Card\Deck;

/**
 * Class Poker
 *
 * This class inherits from Deck
 * 
 */
class Poker extends Deck
{
    /**
     * Creates new Deck of 52 cards without jokers
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This function gives rank as integer and also a number for A, K, Q and J
     *
     * @param Card $card The card to evaluate.
     * @return int $rank as an integer
     */
    public function getRank(Card $card): int
    {
        $rank = $card->getRank();

        $cardValues = [
            'A' => 14,
            'K' => 13,
            'Q' => 12,
            'J' => 11
        ];

        return $cardValues[$rank] ?? (int) $rank;
    }

    /**
     * Returns highest card in the hand.
     *
     * @param Card[] $hand
     * @return int
     */
    public function getHighCard(array $hand): int
    {
        $ranks = array_map([$this, 'getRank'], $hand);

        /** @phpstan-ignore-next-line */
        return max($ranks);
    }

    /**
     * Returns the frequency of each rank in a hand.
     *
     * @param Card[] $hand
     * @return array<string, int> containing ranks and their frequency
     */
    public function getRankFreq(array $hand): array
    {
        $frequencies = [];

        foreach ($hand as $card) {
            $rank = $card->getRank();
            if (!isset($frequencies[$rank])) {
                $frequencies[$rank] = 0;
            }
            $frequencies[$rank]++;
        }

        return $frequencies;
    }

    /**
     * Checks if the hand contains a pair.
     *
     * @param Card[] $hand An array of Card.
     * @return bool True or false
     */
    public function isPair(array $hand): bool
    {
        foreach ($this->getRankFreq($hand) as $count) {
            if ($count === 2) return true;
        }
        return false;
    }

    /**
     * Checks if the hand contains two pairs.
     *
     * @param Card[] $hand An array of Card.
     * @return bool True or false
     */
    public function isTwoPair(array $hand): bool
    {
        $pair = 0;
        foreach ($this->getRankFreq($hand) as $count) {
            if ($count === 2) $pair++;
        }
        return $pair >= 2;
    }

    /**
     * Checks if the hand contains three of a kund.
     *
     * @param Card[] $hand An array of Card.
     * @return bool True or false
     */
    public function isThree(array $hand): bool
    {
        return in_array(3, $this->getRankFreq($hand));
    }

    /**
     * Checks if the hand contains four of a kind.
     *
     * @param Card[] $hand An array of Card.
     * @return bool True or false
     */
    public function isFour(array $hand): bool
    {
        return in_array(4, $this->getRankFreq($hand));
    }

    /**
     * Checks if the hand conatin 3 of a kind and a pair.
     *
     * @param Card[] $hand An array of Card.
     * @return bool True or false
     */
    public function isFullHouse(array $hand): bool
    {
        $frequency = $this->getRankFreq($hand);
        return in_array(3, $frequency) && in_array(2, $frequency);
    }

    /**
     * Checks if the hand is straight.
     *
     * @param Card[] $hand An array of Card.
     * @return bool True or false
     */
    public function isStraight(array $hand): bool
    {
        $ranks = array_map([$this, 'getRank'], $hand);
        $ranks = array_unique($ranks);
        sort($ranks);

        if ($ranks === [2, 3, 4, 5, 14]) {
            return true;
        }

        if(count($ranks) === 5) {
            $mini = $ranks[0];
            if($ranks === range($mini, $mini+4)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the hand is flush.
     *
     * @param Card[] $hand An array of Card.
     * @return bool True or false
     */
    public function isFlush(array $hand): bool
    {
        $firstSuit = $hand[0]->getSuit();

        foreach ($hand as $card) {
            if ($card->getSuit() !== $firstSuit) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if the hand is straight flush.
     *
     * @param Card[] $hand An array of Card.
     * @return bool True or false
     */
    public function isStraightFlush(array $hand): bool
    {
        return $this->isFlush($hand) && $this->isStraight($hand);
    }

    /**
     * Checks if a hand is royal flush.
     *
     * @param Card[] $hand
     * @return bool True or false
     */
    public function isRoyalFlush(array $hand): bool
    {
        //if(!$this->isStraightFlush($hand)) {
        //    return false;
        //}

        $ranks = array_map([$this, 'getRank'], $hand);
        sort($ranks);

        return $ranks === [10, 11, 12, 13, 14];
    }

    /**
     * Evaluates a hand and returns a unique numerical score.
     *
     * @SuppressWarnings("CyclomaticComplexity")
     * @SuppressWarnings("NPathComplexity")
     * 
     * @param Card[] $hand
     * @return int Score for the hand
     */
    public function evaluateHand(array $hand): int
    {
        if (empty($hand)) return 0;
        if ($this->isRoyalFlush($hand)) return 150;
        if ($this->isStraightFlush($hand)) return 135 + $this->getHighCard($hand);
        if ($this->isFour($hand)) return 120 + $this->getValueHigh($hand, 4);
        if ($this->isFullHouse($hand)) return 105 + $this->getValueHigh($hand, 3);
        if ($this->isFlush($hand)) return 90 + $this->getHighCard($hand);
        if ($this->isStraight($hand)) return 75 + $this->getHighCard($hand);
        if ($this->isThree($hand)) return 60 + $this->getValueHigh($hand, 3);
        if ($this->isTwoPair($hand)) return 45 + $this->getValueHigh($hand, 2);
        if ($this->isPair($hand)) return 30 + $this->getValueHigh($hand, 2);

        return $this->getHighCard($hand);
    }

    /**
     * Returns the highest card rank with the specified frequency.
     * Used so that every hand return a specifik value so  two player can never have same pints
     *
     * @param Card[] $hand
     * @param int $frequency
     * @return int|null Highest rank matching the frequency or null if none.
     */
    public function getValueHigh(array $hand, int $frequency): ?int
    {
        $rank = array_map([$this, 'getRank'], $hand);
        $counts = array_count_values($rank);
        $matchedRanks = [];

        foreach ($counts as $rank => $count) {
            if ($count === $frequency) {
                $matchedRanks[] = $rank;
            }
        }

    return empty($matchedRanks) ? null : (int) max($matchedRanks);
    }

}
