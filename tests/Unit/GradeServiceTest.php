<?php

namespace Tests\Unit;

use App\Helpers\Grade;
use PHPUnit\Framework\TestCase;

class GradeServiceTest extends TestCase
{
    public function test_it_returns_invalid_for_invalid_scores()
    {
        $this->assertEquals('INVALID', Grade::calculateGrade(-1));
        $this->assertEquals('INVALID', Grade::calculateGrade(101));
    }

    public function test_it_returns_grade_A_for_high_scores()
    {
        $this->assertEquals('A', Grade::calculateGrade(85));
        $this->assertEquals('A', Grade::calculateGrade(95));
    }

    public function test_it_returns_grade_B_for_medium_scores()
    {
        $this->assertEquals('B', Grade::calculateGrade(70));
        $this->assertEquals('B', Grade::calculateGrade(80));
    }

    public function test_it_returns_grade_C_for_low_scores()
    {
        $this->assertEquals('C', Grade::calculateGrade(69));
        $this->assertEquals('C', Grade::calculateGrade(50));
    }
}
