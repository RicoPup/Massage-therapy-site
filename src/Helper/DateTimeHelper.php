<?php


namespace App\Helper;


class DateTimeHelper
{
    /**
     * @param \DateTime $startTime
     * @param \DateTime $endTime
     * @param string $intervalMinutes
     * @return array
     * @throws \Exception
     */
    public static function getTimesAsArray(\DateTime $startTime, \DateTime $endTime, string $intervalMinutes): array
    {
        $times = [];
        $time = new \DateTime($startTime->format('Y-m-d H:i'));
        while ($time <= $endTime) {
            $times[] = $time->format('H:i');
            $time->modify('+' . $intervalMinutes . ' minutes');
        }
        return $times;
    }
}