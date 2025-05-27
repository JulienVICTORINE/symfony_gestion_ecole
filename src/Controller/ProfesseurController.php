<?php

namespace App\Controller;

use App\Entity\Professeur;
use App\Form\ProfesseurForm;
use App\Repository\ProfesseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/professeur')]
final class ProfesseurController extends AbstractController
{
    #[Route('/', name: 'app_professeur_index', methods: ['GET'])]
    public function index(ProfesseurRepository $professeurRepository): Response
    {
        return $this->render('professeur/index.html.twig', [
            'professeurs' => $professeurRepository->findAll(),
        ]);
    }


    // Contrôleur pour ajouter un professeur
    #[Route('/new', name: 'app_professeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $professeur = new Professeur();
        $form = $this->createForm(ProfesseurForm::class, $professeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($professeur);
            $entityManager->flush();
            $this->addFlash('success', 'Professeur ajouté avec succès !');
            return $this->redirectToRoute('app_professeur_index');
        }

        return $this->render('professeur/new.html.twig', [
            'professeur' =>$professeur,
            'form' =>$form,
        ]);
    }


    // Contrôleur pour voir les infos pour un professeur
    #[Route('/{id}', name: 'app_professeur_show', methods: ['GET'])]
    public function show(Professeur $professeur) : Response
    {
        return $this->render('professeur/show.html.twig', [
            'professeur' => $professeur,
        ]);
    }


    // Contrôleur pour supprimer un professeur
    #[Route('/{id}/delete', name: 'app_professeur_delete', methods: ['POST'])]
    public function delete(Request $request, Professeur $professeur, EntityManagerInterface $entityManager) : Response
    {
        if ($this->isCsrfTokenValid('delete'.$professeur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($professeur);
            $entityManager->flush();
            $this->addFlash('success', 'Professeur supprimé avec succès !');
        }

        return $this->redirectToRoute('app_professeur_index');
    }

}
