<?php

namespace Krixon\Schedule\Test;

use Krixon\DateTime\DateTime;
use Krixon\Schedule\IntervalPrecision;
use Krixon\Schedule\TemporalExpression\Frequency;
use Krixon\Schedule\Test\TemporalExpression\TemporalExpressionTestCase;

/**
 * @coversDefaultClass Krixon\Schedule\TemporalExpression\DayInMonth
 */
class FrequencyTest extends TemporalExpressionTestCase
{
    /**
     * @dataProvider occurrencesOnOrAfterProvider
     * @covers ::occurrencesOnOrAfter
     *
     * @param string   $startDate
     * @param int      $interval
     * @param int      $precision
     * @param string[] $expected
     */
    public function testOccurrencesOnOrAfter(string $startDate, int $interval, int $precision, array $expected)
    {
        $startDate  = DateTime::create($startDate);
        $precision  = new IntervalPrecision($precision);
        $expression = new Frequency($startDate, $interval, $precision);
        
        self::assertOccurrencesOnOrAfterStartDateGenerateCorrectly($expected, $expression, $startDate);
    }
    
    
    public function occurrencesOnOrAfterProvider() : array
    {
        return [
            [
                '2015-01-01 00:00:00',
                15,
                IntervalPrecision::MINUTES,
                [
                    '2015-01-01 00:00:00',
                    '2015-01-01 00:15:00',
                    '2015-01-01 00:30:00',
                    '2015-01-01 00:45:00',
                    '2015-01-01 01:00:00',
                    '2015-01-01 01:15:00',
                    '2015-01-01 01:30:00',
                    '2015-01-01 01:45:00',
                    '2015-01-01 02:00:00',
                    '2015-01-01 02:15:00',
                ]
            ],
            [
                '2015-01-01 00:00:00',
                1,
                IntervalPrecision::HOURS,
                [
                    '2015-01-01 00:00:00',
                    '2015-01-01 01:00:00',
                    '2015-01-01 02:00:00',
                    '2015-01-01 03:00:00',
                    '2015-01-01 04:00:00',
                    '2015-01-01 05:00:00',
                    '2015-01-01 06:00:00',
                    '2015-01-01 07:00:00',
                    '2015-01-01 08:00:00',
                    '2015-01-01 09:00:00',
                ]
            ],
            [
                '2015-01-01 12:42:02',
                3,
                IntervalPrecision::HOURS,
                [
                    '2015-01-01 12:42:02',
                    '2015-01-01 15:42:02',
                    '2015-01-01 18:42:02',
                    '2015-01-01 21:42:02',
                    '2015-01-02 00:42:02',
                    '2015-01-02 03:42:02',
                    '2015-01-02 06:42:02',
                    '2015-01-02 09:42:02',
                    '2015-01-02 12:42:02',
                    '2015-01-02 15:42:02',
                ]
            ],
            [
                '2015-01-01',
                1,
                IntervalPrecision::DAYS,
                [
                    '2015-01-01',
                    '2015-01-02',
                    '2015-01-03',
                    '2015-01-04',
                    '2015-01-05',
                    '2015-01-06',
                    '2015-01-07',
                    '2015-01-08',
                    '2015-01-09',
                    '2015-01-10',
                ]
            ],
            [
                '2015-02-27',
                1,
                IntervalPrecision::DAYS,
                [
                    '2015-02-27',
                    '2015-02-28',
                    '2015-03-01',
                ]
            ],
            [
                '2016-02-27',
                1,
                IntervalPrecision::DAYS,
                [
                    '2016-02-27',
                    '2016-02-28',
                    '2016-02-29',
                    '2016-03-01',
                ]
            ],
            [
                '2015-01-01',
                12,
                IntervalPrecision::DAYS,
                [
                    '2015-01-01',
                    '2015-01-13',
                    '2015-01-25',
                    '2015-02-06',
                    '2015-02-18',
                    '2015-03-02',
                    '2015-03-14',
                    '2015-03-26',
                    '2015-04-07',
                    '2015-04-19',
                ]
            ],
            [
                '2015-01-01',
                1,
                IntervalPrecision::WEEKS,
                [
                    '2015-01-01',
                    '2015-01-08',
                    '2015-01-15',
                    '2015-01-22',
                    '2015-01-29',
                    '2015-02-05',
                    '2015-02-12',
                    '2015-02-19',
                    '2015-02-26',
                    '2015-03-05',
                ]
            ],
        ];
    }
}
