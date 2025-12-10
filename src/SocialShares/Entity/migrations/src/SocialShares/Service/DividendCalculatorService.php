<?php

namespace App\SocialShares\Service;

use App\Entity\Client;
use App\SocialShares\Entity\ShareTransaction;
use Doctrine\ORM\EntityManagerInterface;

class DividendCalculatorService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * Distribute annual dividends
     * @param string $totalProfit  → total profit of the microfinance for the year (e.g. "500000.00")
     * @param int $year            → e.g. 2025
     */
    public function distributeDividends(string $totalProfit, int $year): void
    {
        $totalShares = $this->getTotalShares();
        if ($totalShares <= 0 || $totalProfit <= 0) {
            return;
        }

        $dividendPerShare = bcdiv($totalProfit, (string)$totalShares, 2);

        $clients = $this->em->getRepository(Client::class)->findAll();
        foreach ($clients as $client) {
            $clientShares = $this->getClientShares($client);
            if ($clientShares <= 0) continue;

            $dividendAmount = bcmul((string)$clientShares, $dividendPerShare, 2);

            // Update client capital
            $newCapital = bcadd($client->getCapital(), $dividendAmount, 2);
            $client->setCapital($newCapital);

            // Record transaction
            $transaction = new ShareTransaction();
            $transaction->setClient($client);
            $transaction->setType('DIVIDEND');
            $transaction->setShares($clientShares);
            $transaction->setAmount($dividendAmount);
            $transaction->setDate(new \DateTime("{$year}-12-31"));
            $transaction->setDescription("Annual dividend {$year}");

            $this->em->persist($transaction);
        }

        $this->em->flush();
    }

    private function getTotalShares(): int
    {
        return (int)$this->em->getRepository('App\SocialShares\Entity\SocialShare')
            ->createQueryBuilder('s')
            ->select('SUM(s.quantity)')
            ->getQuery()->getSingleScalarResult();
    }

    private function getClientShares(Client $client): int
    {
        return (int)$this->em->getRepository('App\SocialShares\Entity\SocialShare')
            ->createQueryBuilder('s')
            ->select('SUM(s.quantity)')
            ->where('s.client = :client')
            ->setParameter('client', $client)
            ->getQuery()->getSingleScalarResult();
    }
}
