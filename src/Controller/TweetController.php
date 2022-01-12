<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Form\TweetType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TweetController extends AbstractController
{
    /**
     * @Route("/tweet", name="tweet")
     */
    public function index(Request $request): Response
    {
        $tweet = new Tweet();


        $form = $this->createForm(TweetType::class , $tweet , [
            'action' => $this->generateUrl('tweet')
        ]);


        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $file = $request->files->get('tweet')['image'];
            $upload_directory = $this->getParameter('upload_directory');
            $file_name = rand(100000,999999).'.'.$file->guessExtension();

            $file->move($upload_directory,$file_name);

            $tweet->setUserId($this->getUser()->getId());
            $tweet->setCreatedAt(new \DateTime(date('Y-m-d')));
            $tweet->setImage($file_name);

            $em = $this->getDoctrine()->getManager();
            $em->persist($tweet);
            $em->flush();


            $this->addFlash('success', 'Tweet has been Uploaded!');

            return $this->redirect($this->generateUrl('tweet'));
        }

        return $this->render('tweet/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tweet-record", name="tweet-record")
     */
    public function record(Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $result = $conn->query('SELECT tweet.* , user.username FROM tweet LEFT JOIN user ON tweet.user_id = user.id')->fetchAll();

         return $this->render('tweet/record.html.twig', [
                    'tweet' => $result
                ]);
    }


    /**
     * @Route("/delete-tweet/{id}", name="delete-tweet")
     */
    public function remove(int $id){
        $em = $this->getDoctrine()->getManager();
        $tweet = $em->getRepository(Tweet::class)->find($id);
        $em->remove($tweet);
        $em->flush();

        $this->addFlash('success', 'Tweet has been Deleted!');

        return $this->redirectToRoute('tweet-record');
    }


    /**
     * @Route("/tweet/edit/{id}", name="tweet-edit")
     */
    public function edit(Request $request , $id): Response
    {

        $em = $this->getDoctrine()->getManager();
        $tweet = $em->getRepository(Tweet::class)->find($id);
        $form = $this->createForm(TweetType::class , $tweet);


        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $data = $form->getData();

            $file = $request->files->get('tweet')['image'];
            $upload_directory = $this->getParameter('upload_directory');
            $file_name = rand(100000,999999).'.'.$file->guessExtension();

            $file->move($upload_directory,$file_name);

            $tweet->setImage($file_name);
            $tweet->setUserId($this->getUser()->getId());

            $em->flush();

            $this->addFlash('success', 'Tweet has been Updated!');
            return $this->redirect($this->generateUrl('tweet-record'));
        }

        return $this->render('tweet/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
