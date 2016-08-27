<?php

namespace Krixon\Schedule\Test\TemporalExpression;

use Krixon\DateTime\DateTime;
use Krixon\Schedule\Day;
use Krixon\Schedule\TemporalExpression\DayInMonth;

/**
 * @coversDefaultClass Krixon\Schedule\TemporalExpression\DayInMonth
 * @covers ::<protected>
 * @covers ::<private>
 */
class DayInMonthTest extends TemporalExpressionTestCase
{
    /**
     * @dataProvider includedDateProvider
     * @covers ::includesDate
     *
     * @param Day    $day
     * @param int    $occurrence
     * @param string $date
     * @param bool   $expected
     */
    public function testIncludesDate(Day $day, int $occurrence, string $date, bool $expected)
    {
        $date       = DateTime::fromFormat('Y-m-d', $date, new \DateTimeZone('Europe/London'));
        $expression = new DayInMonth($day, $occurrence);
        
        $this->assertSame($expected, $expression->includes($date));
    }
    
    
    public function includedDateProvider() : array
    {
        return [
            [Day::mon(), 1, '2015-01-05', true],
            [Day::wed(), -1, '2015-07-29', true],
            [Day::wed(), -2, '2015-07-22', true],
            [Day::wed(), -3, '2015-07-15', true],
            [Day::mon(), 1, '2015-01-06', false],
            [Day::wed(), -1, '2015-07-30', false],
        ];
    }
    
    
    /**
     * @dataProvider firstOccurrenceAfterProvider
     * @covers ::firstOccurrenceAfter
     *
     * @param Day    $day
     * @param int    $occurrence
     * @param string $date
     * @param string $expected
     */
    public function testFirstOccurrenceAfter(Day $day, int $occurrence, string $date, string $expected)
    {
        $timezone   = new \DateTimeZone('Europe/London');
        $date       = DateTime::fromFormat('Y-m-d', $date, $timezone);
        $expected   = DateTime::fromFormat('Y-m-d', $expected, $timezone)->withTimeAtMidnight();
        $expression = new DayInMonth($day, $occurrence);
    
        $this->assertTrue($expected->equals($expression->firstOccurrenceOnOrAfter($date)));
    }
    
    
    public function firstOccurrenceAfterProvider() : array
    {
        return [
            '1st mon() after 2015-01-01 is 2015-01-05' => [Day::mon(), 1, '2015-01-01', '2015-01-05'],
            '2nd mon() after 2015-01-01 is 2015-01-12' => [Day::mon(), 2, '2015-01-01', '2015-01-12'],
            '3rd mon() after 2015-01-01 is 2015-01-19' => [Day::mon(), 3, '2015-01-01', '2015-01-19'],
            '4th mon() after 2015-01-01 is 2015-01-26' => [Day::mon(), 4, '2015-01-01', '2015-01-26'],
            '1st fri() after 2015-01-02 is 2015-01-02' => [Day::fri(), 1, '2015-01-02', '2015-01-02'],
            [Day::wed(), -1, '2015-07-01', '2015-07-29'],
            [Day::wed(), -2, '2015-07-01', '2015-07-22'],
            [Day::wed(), -3, '2015-07-01', '2015-07-15'],
            [Day::wed(), -4, '2015-07-01', '2015-07-08'],
            [Day::wed(), -1, '2015-07-05', '2015-07-29'],
            [Day::wed(), -1, '2015-07-28', '2015-07-29'],
            [Day::wed(), -1, '2015-07-29', '2015-07-29'],
            [Day::wed(), -1, '2015-07-30', '2015-08-26'],
        ];
    }
    
    
    /**
     * @dataProvider occurrencesOnOrAfterProvider
     * @covers ::occurrencesOnOrAfter
     *
     * @param Day    $day
     * @param int    $occurrence
     * @param string $startDate
     * @param array  $expected
     */
    public function testOccurrencesOnOrAfter(Day $day, int $occurrence, string $startDate, array $expected)
    {
        $expression = new DayInMonth($day, $occurrence);
        
        self::assertOccurrencesOnOrAfterStartDateGenerateCorrectly($expected, $expression, $startDate);
    }
    
    
    public function occurrencesOnOrAfterProvider() : array
    {
        return [
            [
                Day::mon(),
                1,
                '2015-01-01',
                [
                    '2015-01-05',
                    '2015-02-02',
                    '2015-03-02',
                    '2015-04-06',
                    '2015-05-04',
                    '2015-06-01',
                    '2015-07-06',
                    '2015-08-03',
                    '2015-09-07',
                    '2015-10-05',
                    '2015-11-02',
                    '2015-12-07',
                ]
            ],
        ];
    }
}
