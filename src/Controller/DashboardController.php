<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\SupportRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DashboardController extends AbstractController
{
    const MAX_SUPPORT_DASHBOARD = 10;

    public function __construct()
    {
    }

    /**
     * @Route(
     *     path = "/",
     *     name="dashboard"
     *)
     * @IsGranted("ROLE_USER")
     *
     * @param Request                       $request
     * @param SupportRepository             $supportRepository
     * @param AuthorizationCheckerInterface $authorizationChecker
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request,
        SupportRepository $supportRepository,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        /** @var User $user */
        $user = $this->getUser();

        if ($authorizationChecker->isGranted('ROLE_ADMIN')) {
            return $this->render('dashboard/admin.html.twig');
        }

        $mySupportPage = $request->query->get('mySupportPage', '1');
        $myCompanyPage = $request->query->get('myCompanyPage', '1');

        if ($user->getCompany()) {
            $mySupportPager = $supportRepository->getPaginatedSupportByUser(
                $user->getId(),
                self::MAX_SUPPORT_DASHBOARD,
                $mySupportPage
            );

            $myCompanyPager = $supportRepository->getPaginatedSupportByCompanyMinusUser(
                $user->getCompany()->getId(),
                $user->getId(),
                self::MAX_SUPPORT_DASHBOARD,
                $myCompanyPage
            );
        }

        return $this->render('dashboard/user.html.twig', [
            'mySupports' => $mySupportPager ?? null,
            'companySupports' => $myCompanyPager ?? null,
        ]);
    }
}
