<?php


namespace App\Helpers;


use App\Models\Center;
use Carbon\Carbon;

class CommonHelpers
{
    public static function checkCenterSchedule(Center $center, ?string $date, ?string $time): bool
    {
        if (empty($date) || empty($time)) {
            return false;
        }
        $carbonDate = Carbon::parse($date);
        $carbonTime = Carbon::parse($time)->format('H:i:s');
        $day = strtolower($carbonDate->englishDayOfWeek);
        return $center->schedules()
            ->where('day', $day)
            ->where('is_active', true)
            ->where('start_time', '<=', $carbonTime)
            ->where('end_time', '>=', $carbonTime)
            ->exists();
    }

}
