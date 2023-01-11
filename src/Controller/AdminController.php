<?php

namespace App\Controller;

use App\Repository\CompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_index',)]
    public function index(CompetenceRepository $competenceRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'competences' => $competenceRepository->findAll()
        ]);
    }
}
