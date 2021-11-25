<?php

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contact', name: 'contact_')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response {
        return $this->render('contact/index.html.twig');
    }

    #[Route('/submit', name: 'submit')]
    public function submit(Request $request, EntityManagerInterface $entityManager): Response {

        $entityManager = $this->getDoctrine()->getManager();

        $emailContactForm = $request->request->get("email-contact-form");
        $contentContactForm = $request->request->get('content-contact-form');

        $message = new Message();
        $message->setEmail($emailContactForm);
        $message->setContent($contentContactForm);

        $entityManager->persist($message);
        $entityManager->flush();

        return $this->render('contact/confirm.html.twig');
    }
}