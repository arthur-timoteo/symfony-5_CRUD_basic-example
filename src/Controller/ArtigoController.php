<?php

namespace App\Controller;

use App\Entity\Artigo;
use App\Form\ArtigoType;
use App\Repository\ArtigoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/artigo')]
class ArtigoController extends AbstractController
{
    #[Route('/', name: 'artigo_index', methods: ['GET'])]
    public function index(ArtigoRepository $artigoRepository): Response
    {
        return $this->render('artigo/index.html.twig', [
            'artigos' => $artigoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'artigo_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $artigo = new Artigo();

        $form = $this->createFormBuilder($artigo)
            ->add('categoria', TextType::class, array('label'=> 'Categoria:','attr' =>array('class' => 'form-control mb-3')))
            ->add('titulo', TextType::class, array('label'=> 'Título:','attr' =>array('class' => 'form-control mb-3')))
            ->add('texto', TextareaType::class, array('label'=> 'Texto:','attr' =>array('class' => 'form-control mb-3')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($artigo);
            $entityManager->flush();

            return $this->redirectToRoute('artigo_index');
        }

        return $this->render('artigo/new.html.twig', [
            'artigo' => $artigo,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'artigo_show', methods: ['GET'])]
    public function show(Artigo $artigo): Response
    {
        return $this->render('artigo/show.html.twig', [
            'artigo' => $artigo,
        ]);
    }

    #[Route('/{id}/edit', name: 'artigo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Artigo $artigo): Response
    {
        $form = $this->createFormBuilder($artigo)
            ->add('categoria', TextType::class, array('label'=> 'Categoria:','attr' =>array('class' => 'form-control mb-3')))
            ->add('titulo', TextType::class, array('label'=> 'Título:','attr' =>array('class' => 'form-control mb-3')))
            ->add('texto', TextareaType::class, array('label'=> 'Texto:','attr' =>array('class' => 'form-control mb-3')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('artigo_index');
        }

        return $this->render('artigo/edit.html.twig', [
            'artigo' => $artigo,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'artigo_delete', methods: ['POST'])]
    public function delete(Request $request, Artigo $artigo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$artigo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($artigo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('artigo_index');
    }
}
