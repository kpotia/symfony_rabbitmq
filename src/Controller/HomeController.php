<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Incident;
use App\Form\IncidentType;
use App\Message\MailNotification;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $task  = new Incident();
        $task->setUser($this->getUser())
        	 ->setCreatedAt(new \DateTime('now'));
        $form = $this->createForm(IncidentType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$task = $form->getData();

        	$em->persist($task);
        	$em->flush();

        	// sleep(10);

        	$this->dispatchMessage(new MailNotification($task->getDescription(), $task->getId(),$task->getUser()->getEmail()));

        	return $this->redirectToRoute('home');
        }

        return $this->render('home/index.html.twig', [
        	'form' => $form->createView(),

        ]);
    }
}
