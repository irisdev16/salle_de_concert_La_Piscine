<?php

namespace App\Controller;


use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/tags', name: 'tags')]
    public function index(TagRepository $tagRepository): Response
    {

        $tags = $tagRepository->findAll();

        return $this->render('tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    #[Route('/tag/create', name: 'tag_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response{

        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager->persist($tag);
            $entityManager->flush();

            $this->addFlash('success', 'Tag created successfully!');
            return $this->redirectToRoute('tags');
        }

        return $this->render('tag/create.html.twig',[
            'formView' => $form->createView(),
        ]);
    }

    #[Route ('tag/{id}', name: 'tag_show')]
    public function show(int $id, TagRepository $tagRepository): Response{

        $tag = $tagRepository->find($id);

        return $this->render('tag/show.html.twig',[
            'tag' => $tag,
        ]);
    }

    #[Route('/tag/update/{id}', 'tag_update', ['id'=> '\d+'] )]
    public function update(int $id,TagRepository$tagRepository, Request $request, EntityManagerInterface $entityManager): Response{

        $tagUpdated = $tagRepository->find($id);

        if(!$tagUpdated){
            return $this->redirectToRoute('not_found');
        }

        $form = $this->createForm(TagType::class, $tagUpdated);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager->persist($tagUpdated);
            $entityManager->flush();

            $this->addFlash('success', 'Tag modified successfully!');
            return $this->redirectToRoute('tags');
        }

        $form_view = $form->createView();

        return $this->render('tag/update.html.twig',[
            'formView' => $form_view,
            'tag' => $tagUpdated
        ]);

    }
}

