<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\SupportType;
use App\Entity\Support;
use App\Repository\SupportRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(
 *     path="dashboard/support",
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
     * @param SupportRepository $supportRepository
     */
    public function __construct(SupportRepository $supportRepository)
    {
        $this->supportRepository = $supportRepository;
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route(
     *     path="/new",
     *     name="new"
     * )
     */
    public function newSupport(Request $request)
    {
        $support = new Support();

        /** @var User $user */
        $user = $this->getUser();

        if (!$user->getCompany()) {
            return $this->redirectToRoute('dashboard_index');
        }

        $form = $this->createForm(SupportType::class, $support);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $support->setCompany($user->getCompany());
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($support);
            $manager->flush();

            return $this->redirectToRoute('dashboard_index');
        }

        return $this->render('dashboard/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(
     *     path = "/show/{id}",
     *     name="show",
     *     methods={"GET"}
     * )
     */
    public function showSupport(int $id)
    {
        /** @var Company $company */
        $company = $this->getUser()->getCompany();
        $support = $this->supportRepository->findOneBy([
            'id' => $id,
            'company' => $company,
        ]);

        if (!$support) {
            throw $this->createNotFoundException(sprintf('Aucun support trouvé pour la société %s.', $company->getLabel()));
        }

        return $this->render('dashboard/show.html.twig', [
            'support' => $support,
        ]);
    }
}
