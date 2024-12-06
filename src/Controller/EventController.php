<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EventController extends AbstractController
{
    #[Route('/events', name: 'events')]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        return $this->render('event/index.html.twig', [
            'events' => $events
        ]);
    }

    #[Route('/event/Create', 'event_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response{

        $event=new Event();
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('events');
        }

        return $this->render('event/create.html.twig', [
            'formView' =>$form
        ]);
    }

    #[Route('event/{id}', 'event_show')]
    public function show(int $id, EventRepository $eventRepository): Response{

        $event = $eventRepository->find($id);

        return $this->render('event/show.html.twig', [
            'event' => $event
        ]);
    }

    #[Route('/event/update/{id}', 'event_update')]
    public function update(int $id, EventRepository $eventRepository, Request $request, EntityManagerInterface $entityManager):
    Response{

        $eventUpdated = $eventRepository->find($id);

        if(!$eventUpdated){
            return $this->redirectToRoute('not_found');
        }

        $form = $this->createForm(EventType::class, $eventUpdated);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager->persist($eventUpdated);
            $entityManager->flush();

            return $this->redirectToRoute('events');
        }

        $form_view = $form->createView();

        return $this->render('event/update.html.twig', [
            'formView' => $form_view,
            'event' => $eventUpdated
        ]);
    }
}
