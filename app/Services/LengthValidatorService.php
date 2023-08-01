<?php

namespace App\Services;

use App\Models\Calendar;
use App\Http\Requests\ValidateLength as Request;

class LengthValidatorService
{
    /**
     * @return array
     */
    public function validateLength(Calendar $calendar, Request $request)
    {
        $day = $request['day'] ?? 0;
        $month = $request['month'];
        $length = $request['length'];

        $daysInYear = $calendar->daysInYear();
        $counter = 0;
        $monthLength = 0;
        foreach ($calendar->monthDataProperties() as $monthData) {
            $counter = $counter + 1;
            if ($counter >= $month) {
                $monthLength = $monthLength + $monthData['data-length'];
            }
        }
        $totalLength = $monthLength - $day + $daysInYear;
        if ($length >= $totalLength) {
            return json_encode([
                'overflow' => true,
                'message' => __('calendars.warnings.event_length', ['documentation' => link_to('https://docs.kanka.io/en/latest/entities/calendars.html#long-lasting-reminders', '<i class="fa-solid fa-external-link" aria-hidden="true"></i> ' . __('front.menu.documentation'), ['target' => '_blank'], null, false)]),
            ]);
        }
        return json_encode([
            'overflow' => false,
            'message' => __('calendars.warnings.event_length', ['documentation' => link_to('https://docs.kanka.io/en/latest/entities/calendars.html#long-lasting-reminders', '<i class="fa-solid fa-external-link" aria-hidden="true"></i> ' . __('front.menu.documentation'), ['target' => '_blank'], null, false)]),
        ]);
    }
}
