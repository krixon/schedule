<?php

namespace Krixon\Schedule\Test\TemporalExpression;

use Krixon\DateTime\DateTime;
use Krixon\Schedule\TemporalExpression\TemporalExpression;

/**
 * @coversDefaultClass Krixon\Schedule\TemporalExpression\TemporalExpression
 * @covers ::<protected>
 * @covers ::<private>
 */
class TemporalExpressionTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string[]           $expected   An array of date strings in a format accepted by DateTime::create().
     * @param TemporalExpression $expression The expression to test.
     * @param string             $startDate
     */
    protected static function assertOccurrencesOnOrAfterStartDateGenerateCorrectly(
        array $expected,
        TemporalExpression $expression,
        string $startDate
    ) {
        $startDate      = DateTime::create($startDate);
        $limit          = count($expected);
        $numOccurrences = 0;
        
        /** @var DateTime $occurrence */
        foreach ($expression->occurrencesOnOrAfter($startDate) as $i => $occurrence) {
            if ($i === $limit) {
                break;
            }
            
            ++$numOccurrences;
            
            $expectedDate = DateTime::create($expected[$i]);
            
            $message = sprintf(
                'Failed asserting that %s is expected occurrence %s (%d of %d).',
                $occurrence,
                $expectedDate,
                $numOccurrences,
                $limit
            );
            
            $result = $occurrence->equals($expectedDate);
            
            self::assertTrue($result, $message);
        }
        
        self::assertSame(
            $numOccurrences,
            $limit,
            "Failed asserting that $numOccurrences matches expected $limit occurrences on or after $startDate."
        );
    }
    
    
    /**
     * @param string|null        $expected
     * @param TemporalExpression $expression
     * @param string             $startDate
     */
    protected static function assertFirstOccurrenceAfterEquals(
        $expected,
        TemporalExpression $expression,
        string $startDate
    ) {
        $startDate  = DateTime::create($startDate);
        $occurrence = $expression->firstOccurrenceOnOrAfter($startDate);
        
        if ($expected) {
            self::assertTrue(DateTime::create($expected)->equals($occurrence));
        } else {
            self::assertNull($occurrence);
        }
    }
    
    
    /**
     * @param bool               $expected
     * @param TemporalExpression $expression
     * @param string             $date
     */
    protected static function assertIncludesDate(bool $expected, TemporalExpression $expression, string $date)
    {
        $date = DateTime::create($date);
        
        self::assertSame(
            $expected,
            $expression->includes($date),
            "Failed asserting that $expression includes date $date is " . ($expected ? 'true.' : 'false.')
        );
    }
}
