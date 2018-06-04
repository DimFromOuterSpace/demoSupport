<?php

namespace App\Controller;

use App\Form\SupportType;
use App\Entity\Support;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SupportController extends AbstractController
{
    /**
     * @Route(path = "/support")
     */
    public function index()
    {
        
    }

    /**
     * @Route(path = "/support/new")
     */
    public function newSupport(Request $request)
    {
        $support = new Support();
        $form = $this->createForm(SupportType::class);

        return new Response("OK");
    }
}