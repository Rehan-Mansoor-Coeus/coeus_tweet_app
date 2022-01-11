<?php

namespace App\Controller;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('index/home.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/logger", name="logger")
     */
    public function logger(LoggerInterface $logger)
    {
        $logger->info('Info Logger');
        $logger->error('An error occurred');
        $logger->critical('Critical error found!', [
            // include extra "context" info in your logs
            'cause' => 'bad coding ..!',
        ]);

        return new Response('logger practice');
    }

}
