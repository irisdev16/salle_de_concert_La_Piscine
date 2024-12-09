<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted($request)) {

            $email= new Email();

            $emailTemplate = $this ->renderView('contact/template.html.twig', [
                'contact' => $contact,
            ]);

            $mailer ->send($email
                ->from ('no_reply@monsupersite.com')
                ->to('contact@monsupersite.com')
                ->subject('Demande de contact faite')
                ->html($emailTemplate)

            );

            return $this->redirectToRoute('contact');

        }


        return $this->render('contact/index.html.twig', [
            'formView' => $form

        ]);
    }
}
