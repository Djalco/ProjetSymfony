<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Deal;
use App\Form\DealType;
use App\Repository\CategoryRepository;
use App\Repository\DealRepository;
use App\Service\RandomDiscount;
use App\Service\RandomSlogan;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class DealController extends AbstractController
{
    #[Route('/home', name: 'homepage', methods: ['GET'])]
    public function homeAction()
    {
        $response = new Response("<h1>Bienvenue a vous</h1>");
        return $response;
    }
    
    #[Route('/deal/list', name: 'deal_list', methods: ['GET'])]
    public function dealListAction(DealRepository $dealRepository, RandomSlogan  $randomSlogan, RandomDiscount $randomDiscount)
    {
        $deals = $dealRepository->listDeals();
        //DD($deals);
        return $this->render('deal/index.html.twig', [
            'deals' => $deals,
            'slogan' => $randomSlogan->getSlogan(),
            'discount' => $randomDiscount->getRandomDiscount(),

        ]);
    }
    #[Route('/deal/new', name: 'deal_create', methods: ['GET', 'POST'])]
    public function dealCreateAction(Request $request, EntityManagerInterface $manager, LoggerInterface $logger)
    {   
        $deal = new Deal();
        $form = $this->createForm(DealType::class, $deal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deal->setEnable(true);
            $manager->persist($deal);
            $manager->flush();
            $logger->info('Deal created successfully!', ['dealId' => $deal->getId()]);

            return $this->redirectToRoute('deal_list');
        }
        return $this->render('deal/formulaire.html.twig',[
            'form' => $form->createView(),
        ]);
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

    #[Route('/category/list', name: 'category_list', methods: ['GET'])]
    public function categoryListAction(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        //DD($categories);
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
