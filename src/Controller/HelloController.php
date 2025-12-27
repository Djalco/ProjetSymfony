<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HelloController extends AbstractController
{
    #[Route('/hello/{name}', name: 'hello')]
    public function indexAction($name)
    {
        $response = new Response("<h1>Hello $name</h1>", Response::HTTP_OK);
        $response = new Response('<style> h1 { color: blue; }</style>');
        $response->headers->set('Content-Type', 'text/css');
        return $response;
    }
}