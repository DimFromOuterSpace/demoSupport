<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Company;
use App\Repository\SupportRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class DashboardController.
 *
 * @IsGranted("ROLE_USER")
 * @Route(
 *     path="",
 *     name="dashboard_"
 * )
 */
class DashboardController extends AbstractController
{
    /**
     * @param Request                       $request
     * @param SupportRepository             $supportRepository
     * @param AuthorizationCheckerInterface $authorizationChecker
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(
     *     path="",
     *     name="user"
     * )
     * @Route(
     *     path="dashboard",
     *     name="dashboard"
     * )
     */
    public function index(Request $request, SupportRepository $supportRepository, AuthorizationCheckerInterface $authorizationChecker)
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($authorizationChecker->isGranted('ROLE_ADMIN')) {
            return $this->render('admin/empty_page.html.twig');
        }

        /** @var Company $company */
        $company = $user->getCompany();

        $pageUser = $request->query->get('pageSupportUser', 1);
        $pagerUser = $supportRepository->getPaginatedSupportByUser($user->getId(), 10, $pageUser);

        if ($company) {
            $pageCompany = $request->query->get('pageSupportCompany', 1);
            $pagerCompany = $supportRepository->getPaginatedSupportByCompany($company->getId(), 10, $pageCompany, $user->getId());
        }

        return $this->render('dashboard/index.html.twig', [
            'supportsUser' => $pagerUser ?? null,
            'supportsCompany' => $pagerCompany ?? null,
        ]);
    }
}
