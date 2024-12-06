<?php

namespace App\Controller;

use App\Entity\Animator;
use App\Form\AnimatorType;
use App\Repository\AnimatorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AnimatorController extends AbstractController
{
    #[Route('/animators', name: 'animators')]
    public function index(AnimatorRepository $animatorRepository): Response
    {

        $animators = $animatorRepository->findAll();

        return $this->render('animator/index.html.twig', [
            'animators' => $animators,
        ]);
    }

    #[Route('/animator/create', 'animator_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response{

        $animator = new Animator();
        $form = $this->createForm(AnimatorType::class, $animator);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager->persist($animator);
            $entityManager->flush();

            return $this->redirectToRoute('animators');
        }

        $formView = $form->createView();
        return $this->render('animator/create.html.twig', [
            'formView'=>$formView
        ]);
    }

    #[Route('animator/{id}', 'animator_show')]
    public function show(int $id, AnimatorRepository $animatorRepository): Response{

        $animator = $animatorRepository->find($id);

        return $this->render('animator/show.html.twig', [
            'animator' => $animator,
        ]);
    }

    #[Route('/animator/update/{id}', 'animator_update')]
    public function update(int $id, AnimatorRepository $animatorRepository, Request $request, EntityManagerInterface $entityManager): Response
    {

        $animatorUpdated = $animatorRepository->find($id);

        if (!$animatorUpdated) {
            return $this->redirectToRoute('not_found');
        }

        $form = $this->createForm(AnimatorType::class, $animatorUpdated);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager->persist($animatorUpdated);
            $entityManager->flush();

            return $this->redirectToRoute('animators');
        }

        $formView = $form->createView();

        return $this->render('animator/update.html.twig', [
            'formView' => $formView,
            'animator' => $animatorUpdated
        ]);
    }
}
