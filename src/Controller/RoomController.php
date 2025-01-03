<?php

namespace App\Controller;

use App\Entity\Image;
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

            $images= $form->get('images')->getData();

            foreach ($images as $image) {
                $fileName = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('uploads_directory'),
                    $fileName
                );

                $image = new Image();
                $image ->setPath($fileName);
                $image->setRoom($room);

                $entityManager->persist($image);
            }

            $entityManager->persist($room);
            $entityManager->flush();

            $this->addFlash('success', 'Room created successfully!');
            return $this->redirectToRoute('rooms');
        }


        return $this->render('room/create.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    #[\Symfony\Component\Routing\Annotation\Route ('room/{id}', name: 'room_show')]
    public function show(int $id, RoomRepository $roomRepository ): Response{

        $room = $roomRepository->find($id);

        return $this->render('room/show.html.twig',[
            'room' => $room
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

            $images= $form->get('images')->getData();

            foreach ($images as $image) {
                $fileName = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('uploads_directory'),
                    $fileName
                );

                $image = new Image();
                $image ->setPath($fileName);
                $image->setRoom($roomUpdate);

                $entityManager->persist($image);
            }

            $entityManager->persist($roomUpdate);
            $entityManager->flush();

            $this->addFlash('success', 'Room modified successfully!');
            return $this->redirectToRoute('rooms');
        }

        $formView = $form->createView();

        return $this->render('room/update.html.twig', [
            'formView' => $formView,
            'room'=>$roomUpdate
        ]);

    }
}
