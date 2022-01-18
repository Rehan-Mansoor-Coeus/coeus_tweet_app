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
use App\Security\Voter\TweetVoter;

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

            if($request->files->get('tweet')['image']) {
                $file = $request->files->get('tweet')['image'];
                $upload_directory = $this->getParameter('upload_directory');
                $file_name = rand(100000, 999999) . '.' . $file->guessExtension();

                $file->move($upload_directory, $file_name);
                $tweet->setImage($file_name);
            }

            $tweet->setUser($this->getUser());
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
    public function record(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository(Tweet::class)->findAll();
        $header = "Tweets Records";
        return $this->render('tweet/record.html.twig', [
            'tweet' => $result,
            'header' => $header,
        ]);
    }



    /**
     * @Route("/tweet-record-user", name="tweet-record-user")
     */
    public function recordUser(): Response
    {

        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository(Tweet::class)->findBy([
            'user' => $this->getUser()
        ]);
        $header = "My Tweets Records";
        return $this->render('tweet/user-record.html.twig', [
            'tweet' => $result,
            'header' => $header,
        ]);
    }


    /**
     * @Route("/delete-tweet/{id}", name="delete-tweet")
     */
    public function remove(Tweet $tweet){

        $this->denyAccessUnlessGranted('DELETE', $tweet);

        $em = $this->getDoctrine()->getManager();
        $em->remove($tweet);
        $em->flush();

        $this->addFlash('success', 'Tweet has been Deleted!');

        return $this->redirectToRoute('tweet-record');
    }


    /**
     * @Route("/tweet/edit/{id}", name="tweet-edit")
     */
    public function edit(Tweet $tweet ,Request $request , $id): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $tweet);


        $form = $this->createForm(TweetType::class , $tweet);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();

            if($request->files->get('tweet')['image']){
                $file = $request->files->get('tweet')['image'];
                $upload_directory = $this->getParameter('upload_directory');
                $file_name = rand(100000,999999).'.'.$file->guessExtension();

                $file->move($upload_directory,$file_name);

                $tweet->setImage($file_name);
            }

            $tweet->setUser($this->getUser());

            $em->flush();

            $this->addFlash('success', 'Tweet has been Updated!');
            return $this->redirect($this->generateUrl('tweet-record'));
        }

        return $this->render('tweet/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
