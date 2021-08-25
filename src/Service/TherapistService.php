<?php


namespace App\Service;


use App\Entity\Therapist;
use Doctrine\ORM\EntityManagerInterface;

class TherapistService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param \DateTime $date
     * @return Therapist
     */
    public function getTherapistForDate(\DateTime $date): ?Therapist
    {
        /** @var Therapist $therapist */
        $therapists = $this->em->getRepository(Therapist::class)->findAll();
        foreach ($therapists as $therapist) {
            foreach ($therapist->getDays() as $day) {
                if ($day['day'] == (new \DateTime($date->format('Y-m-d H:i:s')))->format('N')) {
                    return $therapist;
                }
            }
        }

        return null;
    }

    /**
     * @param Therapist $therapist
     * @param \DateTime $date
     * @return array
     */
    public function getTherapistStartAndEndTimeForDate(Therapist $therapist, \DateTime $date): array
    {
        foreach ($therapist->getDays() as $day) {
            if ($day['day'] == $date->format('N')){
                return [
                    'startTime' => $day['startTime'],
                    'endTime' => $day['endTime']
                ];
            }
        }
        return [];
    }
}