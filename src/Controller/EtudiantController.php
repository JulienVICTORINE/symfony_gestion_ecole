<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantForm;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/etudiant')]
final class EtudiantController extends AbstractController
{
    // Affiche la liste de tous les étudiants
    #[Route('/', name: 'app_etudiant_index', methods: ['GET'])]
    public function index(EtudiantRepository $etudiantRepository): Response
    {
        return $this->render('etudiant/index.html.twig', [
            'etudiants' => $etudiantRepository->findAll(),
        ]);
    }


    // Contrôleur pour ajouter un étudiant
    #[Route('/new', name: 'app_etudiant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantForm::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($etudiant);
            $entityManager->flush();
            $this->addFlash('success', 'Étudiant ajouté avec succès !');
            return $this->redirectToRoute('app_etudiant_index');
        }

        return $this->render('etudiant/new.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
        ]);
    }


    // Contrôleur pour voir les détails pour un étudiant
    #[Route('/{id}', name: 'app_etudiant_show', methods: ['GET'])]
    public function show(Etudiant $etudiant) : Response
    {
        return $this->render('etudiant/show.html.twig', [
            'etudiant' => $etudiant,
        ]);
    }


    // Contrôleur pour modifier les infos pour un étudiant
    #[Route('/{id}/edit', name: 'app_etudiant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etudiant $etudiant, EntityManagerInterface $entityManager) : Response
    {
        $form = $this->createForm(EtudiantForm::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "L'étudiant a été modifié avec succès !");
            return $this->redirectToRoute('app_etudiant_index');
        }

        return $this->render('etudiant/edit.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
        ]);
    }


    // Contrôleur pour supprimer un étudiant
    #[Route('/{id}/delete', name: 'app_etudiant_delete', methods: ['POST'])]
    public function delete(Request $request, Etudiant $etudiant, EntityManagerInterface $entityManager) : Response
    {
        if ($this->isCsrfTokenValid('delete'.$etudiant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($etudiant);
            $entityManager->flush();
            $this->addFlash('success', 'Etudiant supprimé avec succès !');
        }

        return $this->redirectToRoute('app_etudiant_index');
    }


    

}
