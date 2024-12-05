<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RoomController extends AbstractController
{
    #[Route('/room', name: 'rooms')]
    public function index(RoomRepository $roomRepository): Response
    {

        $rooms = $roomRepository->findAll();

        return $this->render('room/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    #[Route('/room/create', name: 'room_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response {

        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager->persist($room);
            $entityManager->flush();

            return $this->redirectToRoute('rooms');
        }

        return $this->render('room/create.html.twig', [
            'formView' => $form->createView(),
        ]);
    }


    #[Route('/room/update/{id}', 'room_update', ['id'=> '\d+'])]
    public function update(int $id, RoomRepository $roomRepository, Request $request, EntityManagerInterface $entityManager): Response {

        $roomUpdate = $roomRepository->find($id);

        if(!$roomUpdate){
            return $this->redirectToRoute('not_found');
        }

        $form = $this->createForm(RoomType::class, $roomUpdate);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager->persist($roomUpdate);
            $entityManager->flush();
        }

        $formView = $form->createView();

        return $this->render('room/update.html.twig', [
            'formView' => $formView,
            'room'=>$roomUpdate
        ]);

    }
}
