<?php

namespace WsuDas\Degreedays;

class Calculator
{
    const TWO_PI = 2 * M_PI; // 6.2831853071796

    const HLF_PI = M_PI_2; // 1.5707963267949

    /**
     * Calculates the degree-days value using the single-sine method.
     * Code originally extracted from DAS v6.0 calculations.
     *
     * @See http://ipm.ucanr.edu/WEATHER/ddconcepts.html
     */
    public static function degreedays(
        float $tmax,
        float $tmin,
        float $lower_threshold,
        float $upper_threshold = INF,
        string $cutoff = 'horizontal'
    ): float {
        if ($tmin > $tmax || $tmax <= $lower_threshold) {
            return 0;
        }

        return $cutoff == 'vertical'
            ? static::verticalCutoff($tmax, $tmin, $lower_threshold, $upper_threshold)
            : static::horizontalCutoff($tmax, $tmin, $lower_threshold, $upper_threshold);
    }

    private static function horizontalCutoff(
        float $tmax,
        float $tmin,
        float $lower_threshold,
        float $upper_threshold = INF
    ): float {
        if ($tmin >= $upper_threshold) {
            return round($upper_threshold - $lower_threshold, 3);
        }

        $sum_heat = $tmax + $tmin;
        $diff_heat = $tmax - $tmin;

        $a = $sum_heat / 2 - $lower_threshold;

        if ($lower_threshold <= $tmin && $upper_threshold >= $tmax) {
            return round($a, 3);
        }

        $b = 2 * $lower_threshold - $sum_heat;

        if (abs($diff_heat) > abs($b)) {
            $c = atan($b / sqrt($diff_heat * $diff_heat - $b * $b));
            $d = ($diff_heat * cos($c) - $b * (static::HLF_PI - $c)) / static::TWO_PI;
        } else {
            $d = 0;
        }

        if ($upper_threshold >= $tmax) {
            return round($d, 3);
        }

        $e = 2 * $upper_threshold - $sum_heat;

        $f = atan($e / sqrt($diff_heat * $diff_heat - $e * $e));

        $g = ($diff_heat * cos($f) - $e * (static::HLF_PI - $f)) / static::TWO_PI;

        if ($lower_threshold > $tmin) {
            return round($d - $g, 3);
        }

        return round($a - $g, 3);
    }

    private static function verticalCutoff(
        float $tmax,
        float $tmin,
        float $lower_threshold,
        float $upper_threshold = INF
    ): float {
        if ($tmin >= $upper_threshold) {
            return 0;
        }

        $sum_heat = $tmax + $tmin;
        $diff_heat = $tmax - $tmin;

        if ($lower_threshold <= $tmin && $upper_threshold >= $tmax) {
            return round($sum_heat / 2 - $lower_threshold, 3);
        }

        $a = 2 * $lower_threshold - $sum_heat;

        if (abs($diff_heat) > abs($a)) {
            $b = atan($a / sqrt($diff_heat * $diff_heat - $a * $a));
        } else {
            $b = 0;
        }

        $c = 2 * $upper_threshold - $sum_heat;

        if (abs($diff_heat) > abs($c)) {
            $d = atan($c / sqrt($diff_heat * $diff_heat - $c * $c));
        } else {
            $d = 0;
        }

        if ($lower_threshold <= $tmin) {
            $heat = (-$diff_heat * cos($d) - $a * ($d + static::HLF_PI)) / static::TWO_PI;

            return round($heat, 3);
        }

        if ($upper_threshold < $tmax) {
            $heat = (-$diff_heat * (cos($d) - cos($b)) - $a * ($d - $b)) / static::TWO_PI;

            return round($heat, 3);
        }

        $heat = ($diff_heat * cos($b) - $a * (static::HLF_PI - $b)) / static::TWO_PI;

        return round($heat, 3);
    }

    public static function dd(
        float $tmax,
        float $tmin,
        float $lower_threshold,
        float $upper_threshold = INF,
        string $cutoff = 'horizontal'
    ): float {
        return static::degreedays($tmax, $tmin, $lower_threshold, $upper_threshold, $cutoff);
    }

    /**
     * Calculates the degree-days value based on the passed parameters;
     * it uses an approximate formula using just the mean temperature
     */
    public static function degreedaysAvg(
        float $tmax,
        float $tmin,
        float $lower_threshold,
        float $upper_threshold = INF
    ): float {
        if ($tmin > $upper_threshold && $tmax > $upper_threshold) {
            $dd = $upper_threshold - $lower_threshold;
        } elseif ($tmax < $lower_threshold) {
            $dd = 0;
        } else {
            // calc with mean temp
            $dd = (($tmax + $tmin) / 2) - $lower_threshold;
        }

        if ($dd < 0) {
            $dd = 0;
        }

        return round($dd, 3);
    }
}
