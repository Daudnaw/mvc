<?php

namespace App\Card;

class Computer
{
    private Poker $poker;  // Instance of the Poker for injecting
    private Deck $deck;    // Instance of the Deck for injecting

    /**
     * Constructor to initialize the Poker and Deck objects.
     */
    public function __construct(Poker $poker, Deck $deck)
    {
        $this->poker = $poker;
        $this->deck = $deck;
    }

    /**
     * Main method to exceute computer decision.
     * Evaluates the hand and decides which card to discard
     * and the add same number of cards.
     * 
     * @param array $hand - The current hand of the computer.
     * @return array - New hand with new cards or same hand if already strong.
     */
    public function play(array $hand): array
    {
        $cardsKeep = $this->keepCards($hand);

        return $this->replaceCards($cardsKeep);
    }

    /**
     * Decides which cards to keep based on the cards in hand.
     * Evaluate the hand and decides which cards to keep.
     *
     * @param array $hand - The current hand of the computer.
     * @return array - The cards that the computer decids to keep.
     */
    public function keepCards(array $hand): array
    {
        if ($this->keepFullHand($hand)) {
            return $hand;
        }

        $rankFreq = $this->poker->getRankFreq($hand);

        $rankGroups = $this->groupRanks($hand);

        if ($this->poker->isFour($hand)) {
            return $this->getCardsByRank($rankGroups, $rankFreq, 4);
        }

        if ($this->poker->isFullHouse($hand)) {
            return $hand;
        }

        if ($this->poker->isThree($hand)) {
            return $this->getCardsByRank($rankGroups, $rankFreq, 3);
        }

        if ($this->poker->isTwoPair($hand)) {
            return $this->getTwoPairs($rankGroups, $rankFreq);
        }

        if ($this->poker->isPair($hand)) {
            return $this->getCardsByRank($rankGroups, $rankFreq, 2);
        }

        return $this->keepHighCards($hand);
    }

    /**
     * Replace discarded cards by drawCards from deck by same number.
     *
     * @param array $cardsToKeep - The cards that the computer decided to keep.
     * @return array - The new hand after drawing cards.
     */
    public function replaceCards(array $cardsToKeep): array
    {
        $cardsNeeded = 5 - count($cardsToKeep);

        $newCards = $this->deck->drawCards($cardsNeeded);

        return array_merge($cardsToKeep, $newCards);
    }

    /**
     * Groups the cards by their rank.
     *
     * @param array $hand - The current hand of the player.
     * @return array - Cards group by their rank.
     */
    public function groupRanks(array $hand): array
    {
        $groups = [];

        foreach ($hand as $card) {
            $rank = $card->getRank();
            $groups[$rank][] = $card;
        }

        return $groups;
    }

    /**
     * Get cards of a specific rank count.
     *
     * @param array $groups - The groups of cards by rank.
     * @param array $freq - The frequency of each rank in the hand.
     * @param int $count - The number of cards of the same rank to keep.
     * @return array - The cards of the specified rank count.
     */
    public function getCardsByRank(array $groups, array $freq, int $count): array
    {
        foreach ($freq as $rank => $time) {
            if ($time === $count) {
                return $groups[$rank];
            }
        }

        return [];
    }

    /**
     * Get and keep if two pairs exists.
     *
     * @param array $groups - The groups of cards by rank.
     * @param array $freq - The frequency of each rank in the hand.
     * @return array - The cards containing twopairs.
     */
    public function getTwoPairs(array $groups, array $freq): array
    {
        $pairs = [];

        foreach ($freq as $rank => $count) {
            if ($count === 2) {
                $pairs = array_merge($pairs, $groups[$rank]);
            }
        }

        return $pairs;
    }

    /**
     * Decide whether to keep all cards based on the hand strength.
     *
     * @param array $hand - The current hand.
     * @return bool - True if all cards should be kept, false otherwise.
     */
    public function keepFullHand(array $hand): bool
    {
        return (
            $this->poker->isRoyalFlush($hand) ||
            $this->poker->isStraightFlush($hand) ||
            $this->poker->isFlush($hand) ||
            $this->poker->isStraight($hand)
        );
    }

    /**
     * Keep only the high cards in case no other strong hand is available.
     *
     * @param array $hand - The current hand of the player.
     * @return array - The high cards.
     */
    public function keepHighCards(array $hand): array
    {
        $highCards = [];

        foreach ($hand as $card) {
            $rank = $this->poker->getRank($card);
            if ($rank >= 13) {
                $highCards[] = $card;
            }
        }

        return $highCards;
    }
}
