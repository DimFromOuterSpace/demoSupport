<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class DashboardController.
 *
 * @IsGranted("ROLE_USER")
 * @Route(
 *     path="dashboard",
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
    public function index()
    {
        dump($this->getUser());

        return $this->render('dashboard/index.html.twig');
    }
}
