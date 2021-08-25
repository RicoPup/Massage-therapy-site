<?php

namespace App\Controller;


use App\Entity\Service;
use App\Form\ServiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ServicesController
 * @package App\Controller
 */
class ServicesController extends BaseController
{
    /**
     * @Route("/services", name="services")
     *
     * @param Request $request
     * @return Response
     */
    public function services(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $services = $this->em->getRepository(Service::class)->findBy([], ['createdAt' => 'DESC']);
        $form = $this->createForm(ServiceType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $service = $form->getData();

            $this->em->persist($service);
            $this->em->flush();
            return $this->redirectToRoute('services');
        }

        if ($request->request->get('delete') && $request->request->get('id')) {
            $service = $this->em->getRepository(Service::class)->find($request->request->get('id'));
            $this->em->remove($service);
            $this->em->flush();

            return $this->redirectToRoute('services');
        }

        return $this->render('services.html.twig', [
            'services' => $services,
            'form' => $form->createView()
        ]);
    }
}