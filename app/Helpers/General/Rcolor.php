<?PHP
namespace App\Helpers\General;
/*
 * Code converted from https://github.com/sterlingwes/RandomColor
 * which was inspired from http://martin.ankerl.com/2009/12/09/how-to-create-random-colors-programmatically/
 *
 * Saleh Jamal (@salehjamal)
 */
class Rcolor
{
    protected static $hue;
    protected static $initiated = false;

    protected static function initiate()
    {
        if (!Rcolor::$initiated)
        {
            Rcolor::$hue = mt_rand() / mt_getrandmax();
            Rcolor::$initiated = true;
        }
    }

    protected static function HSVtoRGB($hue, $saturation, $v)
    {
            $h_i = floor($hue * 6);
            $f   = $hue * 6 - $h_i;
            $p   = $v * (1 - $saturation);
            $q   = $v * (1 - $f * $saturation);
            $t   = $v * (1 - (1 - $f) * $saturation);
            $r   = 255;
            $g   = 255;
            $b   = 255;

        switch($h_i)
        {
            case 0: $r = $v ; $g = $t ; $b = $p;        break;
            case 1: $r = $q ; $g = $v ; $b = $p;        break;
            case 2: $r = $p ; $g = $v ; $b = $t;        break;
            case 3: $r = $p ; $g = $q ; $b = $v;        break;
            case 4: $r = $t ; $g = $p ; $b = $v;        break;
            case 5: $r = $v ; $g = $p ; $b = $q;        break;
        }

        return array(floor($r * 256),
                     floor($g * 256),
                     floor($b * 256));
    }

    protected static function padHEX($str)
    {
        $hexwidth = 2;

        if(strlen($str) > $hexwidth)
            return $str;
        else
            return str_pad($str, $hexwidth, '0', STR_PAD_LEFT);
    }

    public function generate($hex = true, $saturation = 0.5, $value = 0.95)
    {
        Rcolor::initiate();

        $goldenRatio = 0.618033988749895;

        Rcolor::$hue += $goldenRatio;
        Rcolor::$hue = fmod(Rcolor::$hue, 1);

        if(!is_numeric($saturation))
            $saturation = 0.5;

        if(!is_numeric($value))
            $value = 0.95;

        $rgb = Rcolor::HSVtoRGB(Rcolor::$hue , $saturation, $value);

        if($hex)
            return "#" . Rcolor::padHex(dechex($rgb[0]))
                       . Rcolor::padHex(dechex($rgb[1]))
                       . Rcolor::padHex(dechex($rgb[2]));
        else
            return $rgb;
    }

    public static function listColor($num){
        $data = array();
        for ($i = 0; $i < $num; $i++)
        {
            $data[] = rcolor()->generate();
        }

        return $data;
    }

}
?>