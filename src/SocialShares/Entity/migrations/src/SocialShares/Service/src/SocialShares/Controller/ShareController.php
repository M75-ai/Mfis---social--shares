<?php

namespace App\SocialShares\Controller;

use App\Entity\Client;
use App\SocialShares\Entity\SocialShare;
use App\SocialShares\Entity\ShareTransaction;
use App\SocialShares\Service\DividendCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/shares')]
class ShareController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private DividendCalculatorService $dividendService
    ) {}

    #[Route('/', name: 'shares_index')]
    public function index(): Response
    {
        $clients = $this->em->getRepository(Client::class)->findAll();
        return $this->render('social_shares/index.html.twig', [
            'clients' => $clients
        ]);
    }

    #[Route('/purchase/{client}', name: 'shares_purchase')]
    public function purchase(Request $request, Client $client): Response
    {
        if ($request->isMethod('POST')) {
            $quantity = (int)$request->request->get('quantity');
            $unitPrice = $request->request->get('unit_price');

            $total = $quantity * $unitPrice;

            // Create share purchase
            $share = new SocialShare();
            $share->setClient($client);
            $share->setQuantity($quantity);
            $share->setUnitPrice($unitPrice);
            $share->setTotalAmount($total);
            $share->setPurchaseDate(new \DateTime());

            // Update client capital
            $newCapital = bcadd($client->getCapital(), (string)$total, 2);
            $client->setCapital($newCapital);

            // Record transaction
            $trans = new ShareTransaction();
            $trans->setClient($client);
            $trans->setType('PURCHASE');
            $trans->setShares($quantity);
            $trans->setAmount((string)$total);
            $trans->setDate(new \DateTime());
            $trans->setDescription("Purchase of $quantity shares");

            $this->em->persist($share);
            $this->em->persist($trans);
            $this->em->flush();

            $this->addFlash('success', 'Shares purchased successfully!');
            return $this->redirectToRoute('shares_index');
        }

        return $this->render('social_shares/purchase.html.twig', [
            'client' => $client
        ]);
    }

    #[Route('/distribute-dividends', name: 'shares_distribute_dividends')]
    public function distributeDividends(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $profit = $request->request->get('profit');
            $year = (int)$request->request->get('year');
            $this->dividendService->distributeDividends($profit, $year);

            $this->addFlash('success', "Dividends distributed for $year!");
            return $this->redirectToRoute('shares_index');
        }

        return $this->render('social_shares/dividends.html.twig');
    }
}
