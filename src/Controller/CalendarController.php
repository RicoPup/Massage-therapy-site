<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Service;
use App\Entity\Therapist;
use App\Entity\User;
use App\Helper\ServiceHelper;
use App\Service\TherapistService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class CalendarController extends BaseController
{
    /**
     * @var TherapistService
     */
    private $therapistService;

    public function __construct(EntityManagerInterface $em, TherapistService $therapistService)
    {
        parent::__construct($em);
        $this->therapistService = $therapistService;
    }

    /**
     * @Route("/calendar", name="calendar")
     * @return Response
     */
    public function calendar(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $startDate = new \DateTime("this monday");

        $dates[] = [
            'date' => $startDate->format('Y-m-d'),
            'day' => $startDate->format('d'),
            'dayOfWeek' => $startDate->format('D')
        ];
        for ($i=1; $i<28; $i++) {
            $startDate->add(new \DateInterval('P'.'1'.'D'));
            $dates[] = [
                'date' => $startDate->format('Y-m-d'),
                'day' => $startDate->format('d'),
                'dayOfWeek' => $startDate->format('D')
            ];
        }

        foreach ($dates as &$date) {
            $orders = $this->em->getRepository(Order::class)->findByDateAndUser(new \DateTime($date['date']), $this->getUser());
            $date['orders'] = $orders;

            $therapist = $this->therapistService->getTherapistForDate(new \DateTime($date['date']));
            $date['therapist'] = $therapist;

            if ($therapist) {
                $times = $this->therapistService->getTherapistStartAndEndTimeForDate(
                    $therapist,
                    new \DateTime($date['date'])
                );

                $date['startTime'] = $times['startTime'];
                $date['endTime'] = $times['endTime'];
            }
        }

        $bookings = $this->em->getRepository(Order::class)->findBy(['user' => $this->getUser()], ['dateTime' => 'DESC']);

        return $this->render('calendar.html.twig', [
            'startDate' => $startDate,
            'dates' => $dates,
            'bookings' => $bookings
        ]);
    }

    /**
     * @Route ("/calendar/{date}", name="bookings")
     * @param Request $request
     * @param string $date
     * @return Response
     */
    public function bookings(Request $request, string $date): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        try {
            $date = new \DateTime($date);
        } catch (\Exception $e) {
            return $this->redirectToRoute('calendar');
        }

        $therapist = $this->therapistService->getTherapistForDate($date);

        if (!$therapist) {
            return $this->redirectToRoute('calendar');
        }

        /** @var User $user */
        $user = $this->getUser();

        $services = $therapist->getServices();

        $serviceMinDuration = ServiceHelper::getMinDuration($services->toArray());

        $times = [];

        $therapistTimes = $this->therapistService->getTherapistStartAndEndTimeForDate(
            $therapist,
            $date
        );

        $startTime = new \DateTime($date->format('Y-m-d') . ' ' . $therapistTimes['startTime']);
        $endTime = new \DateTime($date->format('Y-m-d') . ' ' . $therapistTimes['endTime']);

        $i = 0;
        while (true){
            $time = (new \DateTime($startTime->format('Y-m-d H:i:s')))->modify('+'.$i.' minutes');
            if ($time > $endTime) {
                break;
            }

            $times[] = [
                'time' => $time->format('H:i'),
                'status' => Order::STATUS_AVAILABLE
            ];
            $i += 5;
        }

        if ($request->request->get('submit')) {
            if ($request->request->get('time') && $request->request->get('service')) {
                $service = $this->em->getRepository(Service::class)->find($request->request->get('service'));
                $order = new Order();
                $order->setDateTime(new \DateTime($date->format('Y-m-d').' '.$request->request->get('time')))
                    ->setUser($user)
                    ->setDuration($service->getDuration())
                    ->setPrice($service->getPrice())
                    ->setTitle($service->getTitle())
                    ->setStatus(Order::STATUS_BOOKED);
                $this->em->persist($order);
                $this->em->flush();

                return $this->redirectToRoute('bookings', ['date' => $date->format('Y-m-d')]);
            }
        }

        if ($request->request->get('cancel')) {

            $order = $this->em->getRepository(Order::class)->find($request->request->get('cancel-id'));
            $this->em->remove($order);
            $this->em->flush();

            return $this->redirectToRoute('bookings', ['date' => $date->format('Y-m-d')]);
        }

        /** @var Order[] $orders */
        $orders = $this->em->getRepository(Order::class)->findByDate($date);

        // Check if there is an order at a certain time
        foreach ($times as &$time) {
            foreach ($orders as $order) {
                $startTime = new \DateTime($date->format('Y-m-d') . ' ' . $time['time']);
                $endTime =  (new \DateTime($date->format('Y-m-d') . ' ' . $time['time']))->modify('+5 minutes');
                if ($order->getDateTime() <= $startTime && (new \DateTime($order->getDateTime()->format('Y-m-d H:i:s')))->modify('+' . $order->getDuration() . ' minutes') >= $endTime){
                    if ($order->getUser() == $user) {
                        $time['status'] = Order::STATUS_BOOKED;
                    } else {
                        $time['status'] = Order::STATUS_UNAVAILABLE;
                    }

                    $time['order'] = $order;
                } elseif ($time['status'] == Order::STATUS_AVAILABLE && $startTime > (new \DateTime($order->getDateTime()->format('Y-m-d H:i:s')))->modify('-' . $serviceMinDuration . ' minutes') && $startTime < $order->getDateTime()) {
                    $time['status'] = Order::STATUS_UNAVAILABLE;
                }
            }
        }

        return $this->render('bookings.html.twig', [
            'services' => $services,
            'date' => $date->format('jS') . ' of ' . $date->format('F Y'),
            'times' => $times,
            'therapist' => $therapist ? $therapist->getName() : 'N/A'
        ]);
    }

}