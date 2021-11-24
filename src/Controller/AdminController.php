<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    // nouveau en php 8+
    public function __construct(
        private ProjectRepository $projectRepository
    )
    {
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $projects = $this->projectRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'projects' => $projects
        ]);
    }

    #[Route('/update', name: 'update', methods: ['POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formId = $request->request->get('id');
        $formTitle = htmlentities($request->request->get('title'));
        $formCategory = htmlentities($request->request->get('category'));

        $project = $this->projectRepository->find($formId);

        if ($project === null) {
            return new JsonResponse('Pas de projet sous cet id', 404);
        }

        // Vérifications des datas reçues
        if (empty($formTitle)) {
            return new JsonResponse('Format de titre incorrect', 404);
        } elseif (($project->getTitle()) === $formTitle) {
            return new JsonResponse('Titre inchangé', 404);
        } else {
            $project->setTitle($formTitle);
        }

        if (empty($formCategory)) {
            return new JsonResponse('Format de catégorie incorrect', 404);
        } elseif (($project->getTitle()) === $formCategory) {
            return new JsonResponse('Catégorie inchangée', 404);
        } else {
            $project->setCategory($formCategory);
        }

        $entityManager->persist($project);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $formId,
            'title' => $formTitle,
            'category' => $formCategory
        ]);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categories = [
            null,'business','marketing','communication'
        ];

        $formTitle = htmlentities($request->request->get('titleaddproject'));
        $formContent = htmlentities($request->request->get('contentaddproject'));
        $formCategory = htmlentities($request->request->get('categoryproject'));

        $selectedCategory = $categories[$formCategory];

        if ($selectedCategory == null){
            $selectedCategory = 'sans catégorie';
        }

        if (empty($formTitle)) {
            return new JsonResponse('Le titre du projet doit être mentionné', 404);
        }

        if (empty($formContent)) {
            return new JsonResponse('La description du projet doit être mentionnée', 404);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $project = new Project();
        $project->setTitle($formTitle);
        $project->setContent($formContent);
        $project->setCategory($selectedCategory);

        $entityManager->persist($project);
        $entityManager->flush();

        return new JsonResponse([
            'title' => $formTitle,
            'content' => $formContent,
            'category' => $selectedCategory,
        ]);
    }
}