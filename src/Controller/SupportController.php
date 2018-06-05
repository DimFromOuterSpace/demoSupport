<?php

namespace App\Controller;

use App\Form\SupportType;
use App\Entity\Support;
use App\Repository\SupportRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SupportController
 * @package App\Controller
 * @Route(
 *     path="support",
 *     name="support_"
 * )
 */
class SupportController extends AbstractController
{
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
     * @Route(path = "",
     *          name="list")
     */
    public function index()
    {
        /** @var Support[] $supports */
        $supports = $this->supportRepository->findAll();

        return $this->render('support/liste.html.twig', ['supports' => $supports]);
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
        if($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($support);
            $manager->flush();
            return $this->redirectToRoute("support_show", ['id'=>$support->getId()]);
        }
        return $this->render('support/new.html.twig', ['form' => $form->createView()]);
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
        if($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($support);
            $manager->flush();
            return $this->redirectToRoute("support_show", ['id'=>$support->getId()]);
        }
        return $this->render('support/update.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(
     *     path = "/delete/{id}",
     *     name="delete"
     * )
     */
    public function deleteSupport(Request $request, Support $support)
    {
        if(!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('support_list', ['support' => $support]);
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($support);
        $manager->flush();
        return $this->redirectToRoute('support_list');
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
        return $this->render('support/show.html.twig', [
            'support' => $support
        ]);

    }
}