<?php

/*
    I assume the laravel project is already setup, so here is the example for creating unit test using PHP UNIT
 */
namespace Tests\Unit;

use Tests\TestCase;
use DTApi\Helpers\TeHelper;

class TestHelper extends TestCase
{
    public function testWillExpireAt()
    {
        // Mock the current time to ensure consistent results
        $currentTime = now();

        // Test case 1: $difference <= 90
        $dueTime1 = $currentTime->addHours(50);
        $createdAt1 = $currentTime->addHours(40);

        $result1 = TeHelper::willExpireAt($dueTime1, $createdAt1);
        $this->assertEquals($dueTime1->format('Y-m-d H:i:s'), $result1);

        // Test case 2: $difference <= 24
        $dueTime2 = $currentTime->addHours(20);
        $createdAt2 = $currentTime->addHours(10);

        $result2 = TeHelper::willExpireAt($dueTime2, $createdAt2);
        $expectedResult2 = $createdAt2->addMinutes(90)->format('Y-m-d H:i:s');
        $this->assertEquals($expectedResult2, $result2);

        // Test case 3: $difference > 24 && $difference <= 72
        $dueTime3 = $currentTime->addHours(80);
        $createdAt3 = $currentTime->addHours(40);

        $result3 = TeHelper::willExpireAt($dueTime3, $createdAt3);
        $expectedResult3 = $createdAt3->addHours(16)->format('Y-m-d H:i:s');
        $this->assertEquals($expectedResult3, $result3);

        // Test case 4: $difference > 72
        $dueTime4 = $currentTime->addHours(120);
        $createdAt4 = $currentTime->addHours(40);

        $result4 = TeHelper::willExpireAt($dueTime4, $createdAt4);
        $expectedResult4 = $dueTime4->subHours(48)->format('Y-m-d H:i:s');
        $this->assertEquals($expectedResult4, $result4);
    }
}