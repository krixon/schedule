<?php

namespace Krixon\Schedule\Test\TemporalExpression;

use Krixon\DateTime\DateTime;
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
     * @covers ::includes
     *
     * @param int    $dayOfWeek
     * @param int    $occurrence
     * @param string $date
     * @param bool   $expected
     */
    public function testIncludesDate(int $dayOfWeek, int $occurrence, string $date, bool $expected)
    {
        $date       = DateTime::fromFormat('Y-m-d', $date, new \DateTimeZone('Europe/London'));
        $expression = new DayInMonth($dayOfWeek, $occurrence);
        
        $this->assertSame(
            $expected,
            $expression->includes($date),
            "Failed asserting that $expression includes date $date is " . ($expected ? 'true.' : 'false.')
        );
    }
    
    
    public function includedDateProvider() : array
    {
        return [
            [DateTime::MON, 1, '2015-01-05', true],
            [DateTime::WED, -1, '2015-07-29', true],
            [DateTime::WED, -2, '2015-07-22', true],
            [DateTime::WED, -3, '2015-07-15', true],
            [DateTime::MON, 1, '2015-01-06', false],
            [DateTime::WED, -1, '2015-07-30', false],
        ];
    }
    
    
    /**
     * @dataProvider firstOccurrenceAfterProvider
     * @covers ::firstOccurrenceAfter
     *
     * @param int    $dayOfWeek
     * @param int    $occurrence
     * @param string $date
     * @param string $expected
     */
    public function testFirstOccurrenceAfter(int $dayOfWeek, int $occurrence, string $date, string $expected)
    {
        $timezone   = new \DateTimeZone('Europe/London');
        $date       = DateTime::fromFormat('Y-m-d', $date, $timezone);
        $expected   = DateTime::fromFormat('Y-m-d', $expected, $timezone)->withTimeAtMidnight();
        $expression = new DayInMonth($dayOfWeek, $occurrence);
    
        $this->assertTrue($expected->equals($expression->firstOccurrenceOnOrAfter($date)));
    }
    
    
    public function firstOccurrenceAfterProvider() : array
    {
        return [
            '1st mon() after 2015-01-01 is 2015-01-05' => [DateTime::MON, 1, '2015-01-01', '2015-01-05'],
            '2nd mon() after 2015-01-01 is 2015-01-12' => [DateTime::MON, 2, '2015-01-01', '2015-01-12'],
            '3rd mon() after 2015-01-01 is 2015-01-19' => [DateTime::MON, 3, '2015-01-01', '2015-01-19'],
            '4th mon() after 2015-01-01 is 2015-01-26' => [DateTime::MON, 4, '2015-01-01', '2015-01-26'],
            '1st fri() after 2015-01-02 is 2015-01-02' => [DateTime::FRI, 1, '2015-01-02', '2015-01-02'],
            [DateTime::WED, -1, '2015-07-01', '2015-07-29'],
            [DateTime::WED, -2, '2015-07-01', '2015-07-22'],
            [DateTime::WED, -3, '2015-07-01', '2015-07-15'],
            [DateTime::WED, -4, '2015-07-01', '2015-07-08'],
            [DateTime::WED, -1, '2015-07-05', '2015-07-29'],
            [DateTime::WED, -1, '2015-07-28', '2015-07-29'],
            [DateTime::WED, -1, '2015-07-29', '2015-07-29'],
            [DateTime::WED, -1, '2015-07-30', '2015-08-26'],
        ];
    }
    
    
    /**
     * @dataProvider occurrencesOnOrAfterProvider
     * @covers ::occurrencesOnOrAfter
     *
     * @param int    $dayOfWeek
     * @param int    $occurrence
     * @param string $startDate
     * @param array  $expected
     */
    public function testOccurrencesOnOrAfter(int $dayOfWeek, int $occurrence, string $startDate, array $expected)
    {
        $expression = new DayInMonth($dayOfWeek, $occurrence);
        
        self::assertOccurrencesOnOrAfterStartDateGenerateCorrectly($expected, $expression, $startDate);
    }
    
    
    public function occurrencesOnOrAfterProvider() : array
    {
        return [
            [
                DateTime::MON,
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
