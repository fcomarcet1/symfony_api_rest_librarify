<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LibraryController extends AbstractController
{
    /**
     * @Route("/library", name="library")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/LibraryController.php',
        ]);
    }

    /**
     * @Route("/library/list", name="library_list")
     */
    public function list(Request $request, LoggerInterface $logger)
    {

        //get guery from url
        $title = $request->get('title', null);
        
        // test service logger
        $logger->info('List action called 2');
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'data' => [
                [
                    'id' => 1,
                    'title' => 'Hacia rutas salvajes'
                ],
                [
                    'id' => 2,
                    'title' => 'El nombre del viento'
                ],
                [
                    'id' => 3,
                    'title' => $title
                ]
            ]
        ]);
        return $response;
    }
}
