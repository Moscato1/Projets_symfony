<?php

namespace App\Controller;

use App\Repository\CompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CompetenceRepository $competenceRepository): Response
    {
        return $this->render('home/index.html.twig',
        [
            'competences' => $competenceRepository->findAll()
        ]);
    }
}
