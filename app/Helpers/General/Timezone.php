<?php

namespace App\Helpers\General;

use \Carbon\Carbon;

/**
 * Class Timezone.
 */
class Timezone
{
    /**
     * @param Carbon $date
     * @param string $format
     *
     * @return Carbon $format = 'D M j H:i:s T Y', 
     */
    public function convertToLocal(Carbon $date, $attr = 'daydatetime') : string
    {
        // return $date->setTimezone(auth()->user()->timezone ?? setting()->get('timezone'))->format($format);
        // return $date->setTimezone(auth()->user()->timezone ?? config('app.timezone'))->format($format);
        if($attr == 'date'){
            return $date->translatedFormat('jS F Y');
        }else if($attr == 'datetime'){
            return $date->translatedFormat('jS F Y H:i:s');
        }else if($attr == 'time'){
            return $date->translatedFormat('H:i:s');
        }else if($attr == 'day'){
            return $date->translatedFormat('l');
        }else{
            return $date->translatedFormat('l, jS F Y | H:i:s');
        }
        
    }

    /**
     * @param $date
     *
     * @return Carbon
     */
    public function convertFromLocal($date) : Carbon
    {
        return Carbon::parse($date, auth()->user()->timezone)->setTimezone('Asia/Jakarta');
    }
}
