<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 29-1-2017
 */
if(!isset($ratios)) {
    global $ratios;
    $ratios = [
        'netherlands' => [
            3, // Boerenkool
            5, // Aardappelen
            2, // Worst
            'mustard', // Speciality
        ],
        'france' => [
            4, // Red union
            2, // Pommes de Terres
            5, // Jambon
            'escargots', // Speciality
        ],
        'germany' => [
            6, // Sauerkraut
            4, // Kartoffeln
            6, // Bradwurst
            'curry', // Speciality
        ],
        'belgium' => [
            1, // Picallily
            4, // Patat
            3, // Stoofvlees
            'mayonaise',
        ],
    ];
}

if (!function_exists('calculateScore')) {
    function calculateScore($region, $veg, $potato, $meat, $specialty)
    {
        global $ratios;

        if(!array_key_exists($region, $ratios)) { // Check if the region exists.
            exit ("Fatal error: Regio '$region' not found.");
        }
        if($veg <= 0 || $potato <= 0 || $meat <= 0) { // Any attempt with one ingredient missing scores a zero.
            return 0;
        }

        $ratio = $ratios[$region];
        $score = 5;
        if($specialty == 'non') {
            // Don't change anything if the specialty was non.
        } elseif ($specialty == $ratio[3]) {
            $score += 1;
        } else {
            $score -= 1;
        }
        $score += (5/3) * normalDistributionRatio($veg, $ratio[0]);
        $score += (5/3) * normalDistributionRatio($potato, $ratio[1]);
        $score += (5/3) * normalDistributionRatio($meat, $ratio[2]);

        return min([10, round($score)]);
    }
}

if (!function_exists('normalDistributionRatio')) {
    /**
     * Calculates the normal distribution, with a peak at ($center, 1).
     * @param $x
     * @param $center
     * @return float
     */
    function normalDistributionRatio($x, $center)
    {
        return pow(M_E,(-.5*pow($x-$center,2)));
    }
}

if(!function_exists('normalDistribution')) {
    /**
     * Calculates the normal distribution, with the peak at ($center, sqrt(2*pi).
     * @param $x
     * @param $center
     * @return float
     */
    function normalDistribution($x, $center) {
        return normalDistributionRatio($x, $center) / sqrt(2 * M_PI);
    }
}