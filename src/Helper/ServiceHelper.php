<?php


namespace App\Helper;


use App\Entity\Service;

class ServiceHelper
{
    public static function getMinDuration(array $services): int
    {
        $minDuration = 0;
        /** @var Service $service */
        foreach ($services as $service) {
            if (!$minDuration || $service->getDuration() < $minDuration) {
                $minDuration = $service->getDuration();
            }
        }

        return $minDuration;
    }
}