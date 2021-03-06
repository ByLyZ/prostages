<?php

namespace App\Controller;

use App\Entity\Formations;
use App\Form\FormationsType;
use App\Repository\FormationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/formations/c/r/u/d")
 */
class FormationsCRUDController extends AbstractController
{
    /**
     * @Route("/", name="formations_c_r_u_d_index", methods={"GET"})
     */
    public function index(FormationsRepository $formationsRepository): Response
    {
        return $this->render('formations_crud/index.html.twig', [
            'formations' => $formationsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="formations_c_r_u_d_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formation = new Formations();
        $form = $this->createForm(FormationsType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('formations_c_r_u_d_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formations_crud/new.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="formations_c_r_u_d_show", methods={"GET"})
     */
    public function show(Formations $formation): Response
    {
        return $this->render('formations_crud/show.html.twig', [
            'formation' => $formation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="formations_c_r_u_d_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Formations $formation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormationsType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('formations_c_r_u_d_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formations_crud/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="formations_c_r_u_d_delete", methods={"POST"})
     */
    public function delete(Request $request, Formations $formation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('formations_c_r_u_d_index', [], Response::HTTP_SEE_OTHER);
    }
}
