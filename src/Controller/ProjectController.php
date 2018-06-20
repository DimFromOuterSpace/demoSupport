<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route(
     *      path = "/project",
     *      name = "project_list",
     *     methods={"GET"}
     * )
     * @param ProjectRepository $projectRepository
     * @return Response
     */
    public function index(ProjectRepository $projectRepository)
    {
        return $this->render('project/listProject.html.twig',['admin'=> 'toto']);
    }

    /**
     * @Route(path = "/project/new")
     */
    public function newProject(Project $project)
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class);

        return $this->render('admin/empty_page.html.twig',['admin'=> 'toto']);
    }



}