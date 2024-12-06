<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\EstablishmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'categories')]
    public function index(CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/category/create', 'category_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response{

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('categories');

        }

        return $this->render('category/create.html.twig', [
            'formView' => $form->createView()
        ]);
    }

    #[Route ('category/{id}', name: 'category_show')]
    public function show(int $id, CategoryRepository $categoryRepository): Response{

        $category = $categoryRepository->find($id);

        return $this->render('category/show.html.twig',[
            'category' => $category
        ]);
    }

    #[Route('/category/update/{id}', 'category_update', ['id'=> '\d+'] )]
    public function update(int $id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface
    $entityManager): Response{

        $categoryUpdated = $categoryRepository->find($id);

        if(!$categoryUpdated){
            return $this->redirectToRoute('not_found');
        }

        $form = $this->createForm(CategoryType::class, $categoryUpdated);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager->persist($categoryUpdated);
            $entityManager->flush();

            return $this->redirectToRoute('categories');
        }
        $formView = $form->createView();
        return $this->render('category/update.html.twig', [
            'formView' => $form,
            'category' => $categoryUpdated
        ]);

    }
}
