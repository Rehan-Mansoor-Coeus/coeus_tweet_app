<?php

namespace App\Controller;

use App\Entity\Tweet;
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

        $form = $this->createFormBuilder()
            ->add('tweet', TextareaType::class)
            ->add('Post' , SubmitType::class , [
                'attr' => [
                    'class' => 'btn btn-success float-right'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $data = $form->getData();

            $tweet = new Tweet();
            $tweet->setUserId(1);
            $tweet->settweet($data['tweet']);
            $tweet->setCreatedAt(new \DateTime(date('Y-m-d')));

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
        $result= $conn->query('SELECT tweet.* , user.username FROM tweet LEFT JOIN user ON tweet.user_id = user.id')->fetchAll();

         return $this->render('tweet/record.html.twig', [
                    'tweet' => $result
                ]);
    }
    /**
     * @Route("/delete-tweet/{id}", name="delete-tweet")
     */

    public function remove(int $id): Response
    {

        $em = $this->getDoctrine()->getManager();
        $tweet = $em->getRepository(Tweet::class)->find($id);
        $em->remove($tweet);
        $em->flush();

        $this->addFlash('success', 'Tweet has been Deleted!');

        return $this->redirectToRoute('tweet-record');
    }

    /**
     * @Route("/edit-tweet/{id}", name="delete-tweet")
     */

    public function edit(int $id): Response
    {

        $em = $this->getDoctrine()->getManager();
        $tweet = $em->getRepository(Tweet::class)->find($id);
        $em->flush();

        return $this->render('tweet/index.html.twig', [
            'form' => $tweet
        ]);
    }
}
