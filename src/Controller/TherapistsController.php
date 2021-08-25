<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Therapist;
use App\Form\TherapistType;
use App\Helper\DateTimeHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TherapistsController
 * @package App\Controller
 */
class TherapistsController extends BaseController
{
    /**
     * @Route("/therapists", name="therapists")
     *
     * @param Request $request
     * @return Response
     */
    public function therapists(Request $request):Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $therapists = $this->em->getRepository(Therapist::class)->findBy([], ['createdAt' => 'DESC']);
        $form = $this->createForm(TherapistType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Therapist $therapist */
            $therapist = $form->getData();

            // Manually add therapist to the service
            /** @var Service $service */
            foreach ($therapist->getServices() as $service) {
                $service->addTherapist($therapist);
            }
            dump($request->request);
            // We need to add the start and end times to the day manually too
            $days = [];
            foreach ($therapist->getDays() as $day) {
                $days[] = [
                    'day' => $day,
                    'startTime' => $request->request->get('startTime')[$day],
                    'endTime' => $request->request->get('endTime')[$day],
                ];
            }

            $therapist->setDays($days);

            $this->em->persist($therapist);
            $this->em->flush();
            return $this->redirectToRoute('therapists');
        }

        if ($request->request->get('delete') && $request->request->get('id')) {
            $therapist = $this->em->getRepository(Therapist::class)->find($request->request->get('id'));
            $this->em->remove($therapist);
            $this->em->flush();

            return $this->redirectToRoute('therapists');
        }

        return $this->render('therapists.html.twig', [
            'therapists' => $therapists,
            'form' => $form->createView(),
            'times' => DateTimeHelper::getTimesAsArray(new \DateTime('5:00'), new \DateTime('21:00'), '15')
        ]);
    }
}