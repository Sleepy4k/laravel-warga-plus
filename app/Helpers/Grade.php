<?php

namespace App\Helpers;

/**
 * Class Grade
 *
 * A helper class for grade calculations.
 * @method static string calculateGrade(int $score)
 */
class Grade
{
    /**
     * Calculate the grade based on the given score.
     *
     * @param int $score
     * @return string
     */
    public static function calculateGrade(int $score)
    {
        if ($score < 0 || $score > 100) {
            return 'INVALID';
        }
        if ($score >= 85) {
            return 'A';
        }
        if ($score >= 70) {
            return 'B';
        }
        return 'C';
    }
}
