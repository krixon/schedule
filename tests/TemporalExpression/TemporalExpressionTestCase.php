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
        $startDate = DateTime::create($startDate);
        $limit     = count($expected);
        
        /** @var DateTime $occurrence */
        foreach ($expression->occurrencesOnOrAfter($startDate) as $i => $occurrence) {
            if ($i === $limit) {
                break;
            }
            
            $expectedDate = DateTime::create($expected[$i]);
            
            $message = sprintf(
                'Failed asserting that %s is expected occurrence %s (%d of %d).',
                $occurrence->format('c'),
                $expectedDate->format('c'),
                $i + 1,
                $limit
            );
            
            $result = $occurrence->equals($expectedDate);
            
            self::assertTrue($result, $message);
        }
    }
}
