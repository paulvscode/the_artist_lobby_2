<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index() : Response
    {
        $projects = $this->getDoctrine()
            ->getRepository(Project::class)
            ->findAll();

        return $this->render('admin/index.html.twig',[
            'projects' => $projects
        ]);
    }
}