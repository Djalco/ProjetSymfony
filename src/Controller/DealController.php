<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Deal;
use App\Repository\DealRepository;
use Doctrine\Persistence\ManagerRegistry;

class DealController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function homeAction()
    {
        $response = new Response("<h1>Bienvenue a vous</h1>");
        return $response;
    }
    
    #[Route('/deal/list', name: 'deal_list', methods: ['GET'])]
    public function indexAction(DealRepository $dealRepository)
    {
        $deals = $dealRepository->listDeals();
        DD($deals);
        $response = new Response("<h1>Liste De Deals</h1>");
        return $response;
    }

    #[Route('/deal/show/{dealId}', name: 'deal_show', methods: ['GET'], requirements: ['dealId' => '\d+'])]
    public function showAction($dealId, ManagerRegistry $manager) 
    {
        $deal = $manager->getRepository(Deal::class)->find($dealId);
        $name = $deal->getName();
        $price = $deal->getPrice();
        $description = $deal->getDescription();
        $enable = $deal->isEnable();
        // recupere l'objet dans sa globalite avec les associations lies a cet objet
         dd($deal);
        return new Response(
            "Name: $name <br>" . " Price :$price <br> " . "Decription:  $description <br>" . " Enable:  $enable"
        );
    }

    #[Route('/deal/toggle/{id}', name: 'deal_toggle', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function toggleDealAction($id, ManagerRegistry $manager)
    {
        $deal = $manager->getRepository(Deal::class)->find($id);
        if (!$deal) {
            throw $this->createNotFoundException(
                'No deal found for id ' . $id
            );
        }
        $deal->setEnable(!$deal->isEnable());
        $entityManager = $manager->getManager();
        $entityManager->persist($deal);
        $entityManager->flush();

        //dd($deal);
        return new Response('Deal with id ' . $id . ' has been toggled to ' . ($deal->isEnable() ? 'enabled' : 'disabled') . '.');
    }
}
