<?php

namespace App\Controller;

use App\Repository\CoursRepository;
use App\Repository\EtudiantRepository;
use App\Repository\ProfesseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProfesseurRepository $professeurRepository, EtudiantRepository $etudiantRepository, CoursRepository $coursRepository): Response
    {
        $nbProfesseurs = $professeurRepository->count([]);
        $nbEtudiants = $etudiantRepository->count([]);
        $nbCours = $coursRepository-> count([]);

        // Compter les étudiants par niveau
        $etudiantsParNiveau = [
            'Licence 1' => $etudiantRepository->count(['niveau' => 'L1']),
            'Licence 2' => $etudiantRepository->count(['niveau' => 'L2']),
            'Licence 3' => $etudiantRepository->count(['niveau' => 'L3']),
            'Master 1' => $etudiantRepository->count(['niveau' => 'M1']),
            'Master 2' => $etudiantRepository->count(['niveau' => 'M2']),
        ];

        $labels = array_keys($etudiantsParNiveau);
        $data = array_values($etudiantsParNiveau);

        return $this->render('home/index.html.twig', [
            'nbProfesseurs' => $nbProfesseurs,
            'nbEtudiants' => $nbEtudiants,
            'nbCours' => $nbCours,
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
