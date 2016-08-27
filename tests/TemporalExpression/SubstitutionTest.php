<?php

namespace Krixon\Schedule\Test;

use Krixon\DateTime\DateTime;
use Krixon\Schedule\Day;
use Krixon\Schedule\IntervalPrecision;
use Krixon\Schedule\TemporalExpression\DayInMonth;
use Krixon\Schedule\TemporalExpression\Frequency;
use Krixon\Schedule\TemporalExpression\Substitution;
use Krixon\Schedule\TemporalExpression\TemporalExpression;
use Krixon\Schedule\TemporalExpression\TemporalExpressionPrinter;
use Krixon\Schedule\Test\TemporalExpression\TemporalExpressionTestCase;

/**
 * @coversDefaultClass Krixon\Schedule\TemporalExpression\Substitution
 */
class SubstitutionTest extends TemporalExpressionTestCase
{
    /**
     * @dataProvider occurrencesOnOrAfterProvider
     * @covers ::occurrencesOnOrAfter
     *
     * @param string             $startDate
     * @param TemporalExpression $included
     * @param TemporalExpression $excluded
     * @param TemporalExpression $substitute
     * @param string[]           $expected
     */
    public function testOccurrencesOnOrAfter(
        string $startDate,
        TemporalExpression $included,
        TemporalExpression $excluded,
        TemporalExpression $substitute,
        array $expected
    ) {
        $expression = new Substitution($included, $excluded, $substitute);
        
        self::assertOccurrencesOnOrAfterStartDateGenerateCorrectly($expected, $expression, $startDate);
    }
    
    
    public function occurrencesOnOrAfterProvider() : array
    {
        return [
            'Every day unless the first Monday of the month then last Monday of the month' => [
                '2015-01-01 00:00:00',
                new Frequency(DateTime::create('2015-01-01 00:00:00'), 1, IntervalPrecision::days()),
                new DayInMonth(Day::mon(), 1),
                new DayInMonth(Day::mon(), -1),
                [
                    '2015-01-01 00:00:00',
                    '2015-01-02 00:00:00',
                    '2015-01-03 00:00:00',
                    '2015-01-04 00:00:00',
                    '2015-01-26 00:00:00', // Would have been the 1st Mon but substitute last Monday.
                    '2015-01-27 00:00:00',
                    '2015-01-28 00:00:00',
                    '2015-01-29 00:00:00',
                ],
            ],
        ];
    }
}
