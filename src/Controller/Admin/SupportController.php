<?php

namespace App\Controller\Admin;

use App\Form\Admin\SupportType;
use App\Entity\Support;
use App\Repository\SupportRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SupportController.
 *
 * @Route(
 *     path="admin/support",
 *     name="admin_support_"
 * )
 */
class SupportController extends AbstractController
{
    const MAX_SUPPORT_PAGE = 10;

    /**
     * @var SupportRepository
     */
    private $supportRepository;

    /**
     * SupportController constructor.
     */
    public function __construct(SupportRepository $supportRepository)
    {
        $this->supportRepository = $supportRepository;
    }

    /**
     * @Route(
     *     path = "",
     *     name="list"
     *)
     */
    public function index(Request $request)
    {
        $page = $request->query->get('page', 1);
        $pager = $this->supportRepository->getLastSupport(self::MAX_SUPPORT_PAGE, $page);

        return $this->render('admin/support/liste.html.twig', ['supports' => $pager]);
    }

    /**
     * @Route(
     *     path = "/new",
     *     name="new"
     * )
     */
    public function newSupport(Request $request)
    {
        $support = new Support();
        $form = $this->createForm(SupportType::class, $support);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($support);
            $manager->flush();

            return $this->redirectToRoute('admin_support_show', ['id' => $support->getId()]);
        }

        return $this->render('admin/support/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(
     *     path = "/update/{id}",
     *     name="update"
     * )
     */
    public function updateSupport(Request $request, Support $support)
    {
        $form = $this->createForm(SupportType::class, $support);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($support);
            $manager->flush();

            return $this->redirectToRoute('admin_support_show', ['id' => $support->getId()]);
        }

        return $this->render('admin/support/update.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(
     *     path = "/delete/{id}",
     *     name="delete"
     * )
     */
    public function deleteSupport(Request $request, Support $support)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_support_list', ['support' => $support]);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($support);
        $manager->flush();

        return $this->redirectToRoute('admin_support_list');
    }

    /**
     * @Route(
     *     path = "/show/{id}",
     *     name="show",
     *     methods={"GET"}
     * )
     */
    public function showSupport(Support $support)
    {
        return $this->render('admin/support/show.html.twig', [
            'support' => $support,
        ]);
    }
}
