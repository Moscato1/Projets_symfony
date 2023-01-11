<?php

namespace App\Controller;

use App\Entity\Competence;

use App\Form\CompetenceType;
use App\Repository\CompetenceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompetenceController extends AbstractController
{
    #[Route('/admin/competence', name: 'admin_competence')]
    public function index(): Response
    {
        return $this->render('competence/index.html.twig');
    }


    #[Route('/admin/competence/create', name: 'competence_create')]
    public function create(Request $request, SluggerInterface $slugger, ManagerRegistry $managerRegistry): Response
    {
        $competence = new Competence(); //cretaion d'une nouvelle competence
        $form = $this->createForm(CompetenceType::class, $competence); // création d'un formulaire avec en parmètre la nouvelle categorie 
        $form->handleRequest($request); // gestionaire des requette http

        if ($form->isSubmitted() && $form->isValid()) {
            //Slug
            $slug = strtolower($slugger->slug($competence->getTitle()));
            $competence->setSlug($slug);
            //img
            $infoImg = $form['img']->getData(); //recuperer les données du champ img du formulaire 
            if (!empty($infoImg)) {
                $extensionImg = $infoImg->guessExtension();
                $nomImg = time() . '.' . $extensionImg;
                $competence->setImg($nomImg);
                $infoImg->move($this->getParameter('competence_img_dir'), $nomImg);
            }
            $manager = $managerRegistry->getManager(); // recupere le gestionnaire 
            $manager->persist($competence); // precise au gestionnaire qu'on va vouloir  envoyer un objet en BDD ( le rend persistant/liste d'attente)
            $manager->flush(); // envoie les objets perstités en bdd

            //message de succés
$this->addFlash('success', 'la Competence a bien été créee');
            return $this->redirectToRoute('admin_index');
        }
        // envoyer en base de données
        // redirection
        return $this->render('competence/create.html.twig', [
            'competence' => $competence,
            'competenceForm' => $form->createView()
        ]);}


        #[Route('/admin/competence/update/{id}', name: 'competence_update')]
        public function update(competence $competence, Request $request, ManagerRegistry $managerRegistry): Response
        {
    
            $form = $this->createForm(competenceType::class, $competence);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $infoImg = $form['img']->getData();
                if ($infoImg !== null) {
                    $oldImg = $this->getParameter('competence_img_dir') . '/' . $competence->getImg();
                    if ($competence->getImg() !== null && file_exists($oldImg)) {
                        unlink($oldImg);
                    }
                    $extensionImg = $infoImg->guessExtension();
                    $nomImg = time() . '.' . $extensionImg;
                    $competence->setImg($nomImg);
                    $infoImg->move($this->getParameter('competence_img_dir'), $nomImg);
                }
                $manager = $managerRegistry->getManager();
                $manager->persist($competence);
                $manager->flush();
                $this->addFlash('success', 'la Categorie a bien été modifier');
                return $this->redirectToRoute('admin_index');
            }
            return $this->render('competence/update.html.twig', [
                'competenceForm' => $form->createView()
            ]);
        }


        #[Route('/admin/competence/delete/{id}', name: 'competence_delete')]
    public function delet(competence $competence, ManagerRegistry $managerRegistry): Response
    {
        
        
        
            $img = $this->getParameter('competence_img_dir') . '/' . $competence->getImg();

            if ($competence->getImg() !== null && file_exists($img)) {
                unlink($img);
            }

            $manager = $managerRegistry->getManager();
            $manager->remove($competence);
            $manager->flush();
            $this->addFlash('success', 'la Categorie a bien été supprimée');
        
        return $this->redirectToRoute('admin_index');
    }


}
