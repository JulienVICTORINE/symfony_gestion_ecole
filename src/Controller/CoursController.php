<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursForm;
use App\Repository\CoursRepository;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route(path: '/cours')]
final class CoursController extends AbstractController
{
    // Afficher la liste de tous les cours
    #[Route('/', name: 'app_cours_index', methods: ['GET'])]
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('cours/index.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }


    // Ajouter un nouveau cours
    #[Route('/new', name: 'app_cours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $cours = new Cours();
        $form = $this->createForm(CoursForm::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cours);
            $entityManager->flush();
            $this->addFlash('success', 'Cours ajouté avec succès !');
            return $this->redirectToRoute('app_cours_index');
        }

        return $this->render('cours/new.html.twig', [
            'cours' => $cours,
            'form' => $form,
        ]);
    }


    // Voir les détails d'un cours (professeur + les étudiants inscrits au cours)
    #[Route('/{id}', name: 'app_cours_show', methods: ['GET'])]
    public function show(Cours $cours) : Response
    {
        return $this->render('cours/show.html.twig', [
            'cours' => $cours,
        ]);
    }


    // Modifier un cours
    #[Route('/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cours $cours, EntityManagerInterface $entityManager) : Response
    {
        $form = $this->createForm(CoursForm::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Cours modifié avec succès !');
            return $this->redirectToRoute('app_cours_index');
        }

        return $this->render('cours/edit.html.twig', [
            'cours' => $cours,
            'form' => $form,
        ]);
    }


    // Supprimer un cours
    #[Route('/{id}/delete', name: 'app_cours_delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cours, EntityManagerInterface $entityManager) : Response
    {
        if ($this->isCsrfTokenValid('delete'.$cours->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cours);
            $entityManager->flush();
            $this->addFlash('success', 'Cours supprimé avec succès !');
        }

        return $this->redirectToRoute('app_cours_index');
    }


    // Désinscrire un étudiant à un cours
    #[Route('/{coursId}/remove-etudiant/{etudiantId}', name: 'cours_remove_etudiant', methods: ['POST'])]
    public function removeEtudiant(Request $request, CoursRepository $coursRepository, EntityManagerInterface $entityManager, EtudiantRepository $etudiantRepository, int $coursId, int $etudiantId) : Response
    {
        $cours = $coursRepository->find($coursId);
        $etudiant = $etudiantRepository->find($etudiantId);

        if ($this->isCsrfTokenValid('remove'.$etudiantId, $request->request->get('_token'))) {
            $cours->removeEtudiant($etudiant);
            $entityManager->flush();
            $this->addFlash('success', 'Étudiant désinscrit avec succès.');
        }

        return $this->redirectToRoute('app_cours_show', ['id' => $coursId]);
    }

}
