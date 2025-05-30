<?php

namespace App\Controller;

use App\Repository\CoursRepository;
use App\Repository\EtudiantRepository;
use App\Repository\ProfesseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProfesseurRepository $professeurRepository, EtudiantRepository $etudiantRepository, CoursRepository $coursRepository, ChartBuilderInterface $chartBuilderInterface): Response
    {
        $nbProfesseurs = $professeurRepository->count([]);
        $nbEtudiants = $etudiantRepository->count([]);
        $nbCours = $coursRepository-> count([]);

        // Graphique : nombre d'étudiants par niveau
        $etudiantsParNiveau = $etudiantRepository->countEtudiantParNiveau();
        $labels = array_keys($etudiantsParNiveau);
        $data = array_values($etudiantsParNiveau);

        $chart = $chartBuilderInterface->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Étudiants par niveau',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $data,
                ],
            ],
        ]);
        //////////////////////////////////////////////


        return $this->render('home/index.html.twig', [
            'nbProfesseurs' => $nbProfesseurs,
            'nbEtudiants' => $nbEtudiants,
            'nbCours' => $nbCours,
            'chart' => $chart,
        ]);
    }
}
