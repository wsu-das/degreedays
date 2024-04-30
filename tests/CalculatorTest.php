<?php

namespace Tests;

use WsuDas\Degreedays\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function test_degree_day_with_horizontal_cutoff()
    {
        $upper = 88;
        $lower = 50;
        $cutoff = 'horizontal';

        $data = [
            ['tmax' => 79.12, 'tmin' => 54.64, 'dd' => 16.88],
            ['tmax' => 75.63, 'tmin' => 55.98, 'dd' => 15.805],
            ['tmax' => 82.75, 'tmin' => 52.33, 'dd' => 17.54],
            ['tmax' => 89.30, 'tmin' => 54.75, 'dd' => 21.918],
            ['tmax' => 93.85, 'tmin' => 59.08, 'dd' => 25.428],
            ['tmax' => 92.27, 'tmin' => 59.00, 'dd' => 24.977],
            ['tmax' => 89.61, 'tmin' => 59.71, 'dd' => 24.501],
            ['tmax' => 79.12, 'tmin' => 54.64, 'dd' => 16.88],
            ['tmax' => 75.63, 'tmin' => 55.98, 'dd' => 15.805],
            ['tmax' => 82.75, 'tmin' => 52.33, 'dd' => 17.54],
            ['tmax' => 89.30, 'tmin' => 54.75, 'dd' => 21.918],
            ['tmax' => 93.85, 'tmin' => 59.08, 'dd' => 25.428],
            ['tmax' => 92.27, 'tmin' => 59.00, 'dd' => 24.977],
            ['tmax' => 89.61, 'tmin' => 59.71, 'dd' => 24.501],
        ];

        foreach ($data as $d) {
            $dd = Calculator::degreedays($d['tmax'], $d['tmin'], $lower, $upper, $cutoff);
            $this->assertEquals($d['dd'], $dd, "{$d['tmax']}-{$d['tmin']}");
        }
    }

    public function test_degree_day_with_vertical_cutoff()
    {
        $upper = 85;
        $lower = 50;
        $cutoff = 'vertical';

        $data = [
            ['tmax' => 79.12, 'tmin' => 54.64, 'dd' => 16.88],
            ['tmax' => 75.63, 'tmin' => 55.98, 'dd' => 15.805],
            ['tmax' => 82.75, 'tmin' => 52.33, 'dd' => 17.54],
            ['tmax' => 89.30, 'tmin' => 54.75, 'dd' => 13.339],
            ['tmax' => 93.85, 'tmin' => 59.08, 'dd' => 12.734],
            ['tmax' => 92.27, 'tmin' => 59.00, 'dd' => 13.321],
            ['tmax' => 89.61, 'tmin' => 59.71, 'dd' => 14.888],
        ];

        foreach ($data as $d) {
            $dd = Calculator::degreedays($d['tmax'], $d['tmin'], $lower, $upper, $cutoff);
            $this->assertEquals($d['dd'], $dd);
        }
    }

    public function test_degree_day_extreme_values_horizontal()
    {
        $upper = 84;
        $lower = 40.8;
        $cutoff = 'horizontal';

        $data = [
            // temp curve all above
            ['tmax' => 111, 'tmin' => 88, 'dd' => 43.2],
            ['tmax' => 108.1, 'tmin' => 84, 'dd' => 43.2],
            // temp curve all below
            ['tmax' => 40.8, 'tmin' => 39, 'dd' => 0],
            ['tmax' => 37, 'tmin' => 32, 'dd' => 0],
        ];

        foreach ($data as $d) {
            $dd = Calculator::degreedays($d['tmax'], $d['tmin'], $lower, $upper, $cutoff);
            $this->assertEquals($d['dd'], $dd, "{$d['tmax']}-{$d['tmin']}");
        }
    }

    public function test_degree_day_extreme_values_vertical()
    {
        $upper = 84;
        $lower = 40.8;
        $cutoff = 'vertical';

        $data = [
            // temp curve all above
            ['tmax' => 111, 'tmin' => 88, 'dd' => 0],
            ['tmax' => 108.1, 'tmin' => 84, 'dd' => 0],
            // temp curve all below
            ['tmax' => 40.8, 'tmin' => 39, 'dd' => 0],
            ['tmax' => 37, 'tmin' => 32, 'dd' => 0],
        ];

        foreach ($data as $d) {
            $dd = Calculator::degreedays($d['tmax'], $d['tmin'], $lower, $upper, $cutoff);
            $this->assertEquals($d['dd'], $dd, "{$d['tmax']}-{$d['tmin']}");
        }
    }

    public function test_mean_degree_day()
    {
        $upper = 95;
        $lower = 50;

        $data = [
            ['tmax' => 97, 'tmin' => 96, 'dd' => 45],
            ['tmax' => 48, 'tmin' => 45, 'dd' => 0],
            ['tmax' => 80, 'tmin' => 60, 'dd' => 20],
            ['tmax' => 55, 'tmin' => 40, 'dd' => 0],

            ['tmax' => 60.1, 'tmin' => 38.2, 'dd' => 0],
            ['tmax' => 62.0, 'tmin' => 46.6, 'dd' => 4.3],
            ['tmax' => 84.9, 'tmin' => 65.8, 'dd' => 25.35],
            ['tmax' => 61.0, 'tmin' => 42.2, 'dd' => 1.6],
        ];

        foreach ($data as $d) {
            $dd = Calculator::degreedaysAvg($d['tmax'], $d['tmin'], $lower, $upper);
            $this->assertEquals($d['dd'], $dd);
        }
    }
}
