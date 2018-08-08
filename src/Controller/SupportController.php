<?php

namespace App\Controller;

use App\Entity\Support;
use App\Entity\User;
use App\Form\SupportType;
use App\Repository\SupportRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SupportController.
 *
 * @Route(
 *     path="support",
 *     name="support_"
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
     *
     * @param SupportRepository $supportRepository
     */
    public function __construct(SupportRepository $supportRepository)
    {
        $this->supportRepository = $supportRepository;
    }

    /**
     * @Route(
     *     path = "/new",
     *     name="new"
     * )
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newSupport(Request $request)
    {
        if (!$this->getUser()->getCompany()) {
            return $this->redirectToRoute('dashboard');
        }
        $support = new Support();
        /** @var User $userCompany * */
        $userCompany = $this->getUser();
        $support->setCompany($userCompany->getCompany());
        $form = $this->createForm(SupportType::class, $support);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($support);
            $manager->flush();

            return $this->redirectToRoute('support_show', ['id' => $support->getId()]);
        }

        return $this->render('support/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(
     *     path = "/show/{id}",
     *     name="show",
     *     methods={"GET"}
     * )
     *
     * @param Support $support
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showSupport(int $id)
    {
        $support = $this->supportRepository->findOneBy([
                    'id' => $id,
                    'company' => $this->getUser()->getCompany(),
        ]);

        if (!$support) {
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('support/show.html.twig', [
            'support' => $support,
        ]);
    }
}
