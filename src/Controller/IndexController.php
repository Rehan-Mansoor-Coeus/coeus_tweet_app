<?php

namespace App\Controller;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Acme\TestBundle\AcmeTestBundle;

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


    /**
     * @Route("/acme", name="acme")
     */
    public function acme(AcmeTestBundle $acme)
    {
        $acme = $acme->get('https://api.publicapis.org/entries');
        $data = $acme['entries'];
        dd(array_slice($data,1,10));

        return new Response('logger practice');
    }

    /**
     * @Route("/markdown", name="markdown")
     */
    public function markdown(MarkdownParserInterface $markdownParser)
    {
       $data = "<h3>This is <b>H3</b> Tag</h3>";
       $process_data = $markdownParser->transformMarkdown($data);

        return $this->render('index/markdown.html.twig', [
            'data' => $data ,
            'markdown' => $process_data ,
        ]);

    }

}
