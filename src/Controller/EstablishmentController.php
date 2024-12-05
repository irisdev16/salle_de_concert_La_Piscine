<?php

namespace App\Controller;

use App\Entity\Establishment;
use App\Form\EstablishmentType;
use App\Repository\EstablishmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EstablishmentController extends AbstractController
{
    #[Route('/establishments', name: 'establishments')]
    public function index(EstablishmentRepository $establishmentRepository): Response
    {

        $establishments = $establishmentRepository->findAll();

        return $this->render('establishment/index.html.twig', [
            'establishments' => $establishments,
        ]);
    }

    //Je créé une fonction pour la création de mes établissements
    // ainsi l'utilisateur pourra entrer le nom de ses établissements
    #[Route('/establishment/create', name: 'establishment_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response{

        $establishment = new Establishment();
        $form = $this->createForm(EstablishmentType::class, $establishment);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager->persist($establishment);
            $entityManager->flush();

            return $this->redirectToRoute('establishments');
        }

        return $this->render('establishment/create.html.twig',[
            'formView' => $form->createView(),
        ]);

    }


    #[Route('/establishment/update/{id}', 'establishment_update', ['id'=> '\d+'] )]
    public function update(int $id,EstablishmentRepository $establishmentRepository, Request $request, EntityManagerInterface $entityManager): Response{

        $establishmentUpdated = $establishmentRepository->find($id);

        if(!$establishmentUpdated){
            return $this->redirectToRoute('not_found');
        }

        $form = $this->createForm(EstablishmentType::class, $establishmentUpdated);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager->persist($establishmentUpdated);
            $entityManager->flush();
        }

        $form_view = $form->createView();

        return $this->render('establishment/update.html.twig',[
            'formView' => $form_view,
            'establishment' => $establishmentUpdated
        ]);

    }


}
