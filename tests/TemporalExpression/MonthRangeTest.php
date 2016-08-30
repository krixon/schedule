<?php

namespace Krixon\Schedule\Test\TemporalExpression;

use Krixon\DateTime\DateTime;
use Krixon\Schedule\TemporalExpression\MonthRange;

/**
 * @coversDefaultClass Krixon\Schedule\TemporalExpression\MonthRange
 * @covers ::<protected>
 * @covers ::<private>
 */
class MonthRangeTest extends TemporalExpressionTestCase
{
    /**
     * @dataProvider includedDateProvider
     * @covers ::includes
     *
     * @param int    $start
     * @param int    $end
     * @param string $date
     * @param bool   $expected
     */
    public function testIncludesDate(int $start, int $end, string $date, bool $expected)
    {
        $expression = new MonthRange($start, $end);
        
        self::assertIncludesDate($expected, $expression, $date);
    }
    
    
    public function includedDateProvider() : array
    {
        return [
            [DateTime::JAN, DateTime::DEC, '2015-01-01', true],
            [DateTime::JAN, DateTime::DEC, '2015-02-01', true],
            [DateTime::JAN, DateTime::DEC, '2015-03-01', true],
            [DateTime::JAN, DateTime::DEC, '2015-04-01', true],
            [DateTime::JAN, DateTime::DEC, '2015-05-01', true],
            [DateTime::JAN, DateTime::DEC, '2015-06-01', true],
            [DateTime::JAN, DateTime::DEC, '2015-07-01', true],
            [DateTime::JAN, DateTime::DEC, '2015-08-01', true],
            [DateTime::JAN, DateTime::DEC, '2015-09-01', true],
            [DateTime::JAN, DateTime::DEC, '2015-10-01', true],
            [DateTime::JAN, DateTime::DEC, '2015-11-01', true],
            [DateTime::JAN, DateTime::DEC, '2015-12-01', true],
            [DateTime::JAN, DateTime::MAR, '2015-01-01', true],
            [DateTime::JAN, DateTime::MAR, '2015-03-31', true],
            [DateTime::JAN, DateTime::MAR, '2015-04-01', false],
            [DateTime::SEP, DateTime::SEP, '2015-09-01', true],
            [DateTime::SEP, DateTime::SEP, '2015-09-30', true],
            [DateTime::SEP, DateTime::SEP, '2015-10-01', false],
            [DateTime::FEB, DateTime::FEB, '2016-02-29', true],
        ];
    }
    
    
    /**
     * @dataProvider occurrencesOnOrAfterProvider
     * @covers ::occurrencesOnOrAfter
     *
     * @param string   $startDate
     * @param int      $start
     * @param int      $end
     * @param string[] $expected
     */
    public function testOccurrencesOnOrAfter(string $startDate, int $start, int $end, array $expected)
    {
        $expression = new MonthRange($start, $end);
        
        self::assertOccurrencesOnOrAfterStartDateGenerateCorrectly($expected, $expression, $startDate);
    }
    
    
    public function occurrencesOnOrAfterProvider() : array
    {
        return [
            [
                '2015-01-01 00:00:00',
                DateTime::JAN,
                DateTime::DEC,
                [
                    '2015-01-01 00:00:00',
                    '2015-02-01 00:00:00',
                    '2015-03-01 00:00:00',
                    '2015-04-01 00:00:00',
                    '2015-05-01 00:00:00',
                    '2015-06-01 00:00:00',
                    '2015-07-01 00:00:00',
                    '2015-08-01 00:00:00',
                    '2015-09-01 00:00:00',
                    '2015-10-01 00:00:00',
                    '2015-11-01 00:00:00',
                    '2015-12-01 00:00:00',
                ]
            ],
            [
                '2015-01-01 00:00:00',
                DateTime::MAR,
                DateTime::JUN,
                [
                    '2015-03-01 00:00:00',
                    '2015-04-01 00:00:00',
                    '2015-05-01 00:00:00',
                    '2015-06-01 00:00:00',
                ]
            ],
        ];
    }
}
