<?php

namespace App\Controller;

use App\Form\CompanyType;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CompanyController
 * @package App\Controller
 * @Route(
 *     path="company",
 *     name="company_"
 * )
 */
class CompanyController extends AbstractController
{
    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * CompanyController constructor.
     */
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * @Route(path = "",
     *          name="list")
     */
    public function index()
    {
        /** @var Company[] $companies */
        $companies = $this->companyRepository->getLastCompanies(5);

        return $this->render('company/liste.html.twig', ['companies' => $companies]);
    }

    /**
     * @Route(
     *     path = "/new",
     *     name="new"
     * )
     */
    public function newCompany(Request $request)
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($company);
            $manager->flush();
            return $this->redirectToRoute("company_show", ['id'=>$company->getId()]);
        }
        return $this->render('company/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(
     *     path = "/update/{id}",
     *     name="update"
     * )
     */
    public function updateCompany(Request $request, Company $company)
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($company);
            $manager->flush();
            return $this->redirectToRoute("company_show", ['id'=>$company->getId()]);
        }
        return $this->render('company/update.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(
     *     path = "/delete/{id}",
     *     name="delete"
     * )
     */
    public function deleteCompany(Request $request, Company $company)
    {
        if(!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('company_list', ['company' => $company]);
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($company);
        $manager->flush();
        return $this->redirectToRoute('company_list');
    }

    /**
     * @Route(
     *     path = "/show/{id}",
     *     name="show",
     *     methods={"GET"}
     * )
     */
    public function showCompany(Company $company)
    {
        return $this->render('company/show.html.twig', [
            'company' => $company
        ]);

    }
}