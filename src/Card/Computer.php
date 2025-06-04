<?php

namespace App\Card;

/**
 * Computer decision class.
 *
 * @SuppressWarnings("PHPMD.TooManyMethods")
 */
class Computer
{
    private Poker $poker;  // Instance of the Poker for injecting
    private Deck $deck;    // Instance of the Deck for injecting

    /**
     * Constructor to initialize the Poker and Deck objects.
     * 
     * @param Poker $poker An instance of the Poker
     * @param Deck $deck An instance of the Deck
     */
    public function __construct(Poker $poker, Deck $deck)
    {
        $this->poker = $poker;
        $this->deck = $deck;
    }

    /**
     * Main method to exceute computer decision.
     * 
     * @param Card[] $hand The current hand of the computer.
     * @return Card[] The new hand after replacing cards.
     */
    public function play(array $hand): array
    {
        $cardsKeep = $this->keepCards($hand);

        return $this->replaceCards($cardsKeep);
    }

    /**
     * Decides which cards to keep based on the cards in hand.
     *
     * @param Card[] $hand The current hand.
     * @return Card[] Cards the computer chooses to keep.
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
     * @param Card[] $cardsToKeep Cards to keep.
     * @return Card[] New full hand after replacement
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
     * @param Card[] $hand The current hand.
     * @return array<string, Card[]> Grouped cards by rank.
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
     * @param array<string, Card[]> $groups Cards grouped by rank.
     * @param array<string, int> $freq Frequency of each rank in the hand.
     * @param int $count The number of cards to match.
     * @return Card[] Cards matching the given count.
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
     * @param array<string, Card[]> $groups Cards grouped by rank.
     * @param array<string, int> $freq Frequency of each rank.
     * @return Card[] Cards forming two pairs.
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
     * @param Card[] $hand The current hand.
     * @return bool True if all cards should be kep
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
     * @param Card[] $hand The current hand.
     * @return Card[] High cards 13 or 14.
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
