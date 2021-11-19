<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project', name: 'project_')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }

    #[Route('/create', name: 'create')]
    public function createProject() : Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $project = new Project();
        $project->setTitle('Carrefour');
        $project->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque gravida at purus eget tincidunt. Suspendisse gravida euismod nisl, non feugiat eros pretium aliquet. Donec in tortor neque. Sed sit amet dapibus libero. Maecenas sed metus eu diam tincidunt egestas. Etiam nunc justo, interdum vel lacus nec, consequat vestibulum nisi. Curabitur id nibh a metus cursus vehicula sed non nunc.');
        $project->setCategory('communication');

        $entityManager->persist($project);

        $entityManager->flush();

        return new Response('Project saved with id '.$project->getId());
    }
}
