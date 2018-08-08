<?php

namespace App\Controller\Admin;

use App\Form\CompanyType;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\Repository\SupportRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use App\Events;

/**
 * Class CompanyController.
 *
 * @Route(
 *     path="admin/company",
 *     name="admin_company_"
 * )
 */
class CompanyController extends AbstractController
{
    const MAX_SUPPORT_BY_COMPANY = 10;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @param CompanyRepository $companyRepository
     */
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * @Route(
     *     path = "",
     *     name="list"
     * )
     */
    public function index()
    {
        /** @var Company[] $companies */
        $companies = $this->companyRepository->getLastCompanies(5);

        return $this->render('admin/company/liste.html.twig', ['companies' => $companies]);
    }

    /**
     * @Route(
     *     path = "/new",
     *     name="new"
     * )
     *
     * @param Request                  $request
     * @param EventDispatcherInterface $dispatcher
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newCompany(Request $request, EventDispatcherInterface $dispatcher)
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($company);
            $manager->flush();
            $dispatcher->dispatch(Events::COMPANY_CREATED, new GenericEvent($company));

            return $this->redirectToRoute('admin_company_show', ['id' => $company->getId()]);
        }

        return $this->render('admin/company/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(
     *     path = "/update/{id}",
     *     name="update"
     * )
     *
     * @param Request $request
     * @param Company $company
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateCompany(Request $request, Company $company)
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($company);
            $manager->flush();

            return $this->redirectToRoute('admin_company_show', ['id' => $company->getId()]);
        }

        return $this->render('admin/company/update.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(
     *     path = "/delete/{id}",
     *     name="delete"
     * )
     *
     * @param Request $request
     * @param Company $company
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCompany(Request $request, Company $company)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_company_list', ['company' => $company]);
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($company);
        $manager->flush();

        return $this->redirectToRoute('admin_company_list');
    }

    /**
     * @Route(
     *     path = "/show/{id}",
     *     name="show",
     *     methods={"GET"}
     * )
     *
     * @param Company           $company
     * @param Request           $request
     * @param SupportRepository $supportRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCompany(Company $company, Request $request, SupportRepository $supportRepository)
    {
        $page = $request->query->get('page', '1');

        $pager = $supportRepository->getPaginatedSupportByCompany($company->getId(), self::MAX_SUPPORT_BY_COMPANY, $page);

        return $this->render('admin/company/show.html.twig', [
            'company' => $company,
            'supports' => $pager,
        ]);
    }
}
