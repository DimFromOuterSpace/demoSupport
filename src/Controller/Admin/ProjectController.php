<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Project;
use App\Form\Admin\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(
 *     path="/admin/project",
 *     name="admin_project_"
 * )
 */
class ProjectController extends AbstractController
{
    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * @param ProjectRepository $projectRepository
     */
    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @Route(
     *     path = "",
     *     name = "list",
     *     methods = {"GET"}
     * )
     *
     * @return Response
     */
    public function index()
    {
        /** @var Project[] $projects */
        $projects = $this->projectRepository->getProject();

        return $this->render('admin/project/liste.html.twig', ['projects' => $projects]);
    }

    /**
     * @Route(path = "/new")
     */
    public function newProject(Request $request)
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($project);
            $manager->flush();

            return $this->redirectToRoute('admin_project_show', ['id' => $project->getId()]);
        }

        return $this->render('admin/empty_page.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(
     *     path      = "/{id}",
     *     name      = "show",
     *     methods   = {"GET"}
     * )
     *
     * @param Project $project
     *
     * @return Response
     */
    public function showProject(Project $project)
    {
        return $this->render('admin/project/show.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route(
     *     path = "/delete/{id}",
     *     name="delete"
     * )
     *
     * @param Request $request
     * @param Project $project
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteProject(Request $request, Project $project)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_project_list', ['project' => $project]);
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($project);
        $manager->flush();

        return $this->redirectToRoute('admin_project_list');
    }

    /**
     * @Route(
     *     path = "/update/{id}",
     *     name="update"
     * )
     *
     * @param Request $request
     * @param Project $project
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function updateProject(Request $request, Project $project)
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($project);
            $manager->flush();

            return $this->redirectToRoute('admin_project_show', ['id' => $project->getId()]);
        }

        return $this->render('admin/project/update.html.twig', ['form' => $form->createView()]);
    }
}
