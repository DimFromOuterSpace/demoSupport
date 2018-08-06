<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     * @Route(
     *     path = "/dashboard",
     *     name="dashboard"
     *)
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        dump($this->getUser());

        return $this->render('dashboard/index.html.twig');
    }
}
