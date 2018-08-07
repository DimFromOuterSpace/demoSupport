<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\SupportRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(
     *     path="",
     *     name="index"
     * )
     */
    public function index(Request $request, SupportRepository $supportRepository)
    {
        /** @var User $user */
        $user = $this->getUser();

        $company = $user->getCompany();

        if($company) {
            $pagerCompany = $supportRepository->getPaginatedSupportByCompany($company->getId());
        }

        if ($user) {
            $pagerUser = $supportRepository->getPaginatedSupportByUser($user->getId());
        }

        return $this->render('dashboard/index.html.twig', [
            'supportsCompany' => $pagerCompany ?? null,
            'supportsUser' => $pagerUser ?? null
        ]);
    }
}
