<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route(
     *      path = "/empty-page",
     *      name = "empty_page",
     *     methods={"GET"}
     * )
     *
     * @return Response
     */
    public function emptyPage()
    {
        return $this->render('admin/empty_page.html.twig', ['admin' => 'toto']);
    }
}
