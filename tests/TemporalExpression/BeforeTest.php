<?php

namespace Krixon\Schedule\Test\TemporalExpression;

use Krixon\DateTime\DateTime;
use Krixon\Schedule\TemporalExpression\Before;

/**
 * @coversDefaultClass Krixon\Schedule\TemporalExpression\Before
 */
class BeforeTest extends TemporalExpressionTestCase
{
    /**
     * @dataProvider includedDateProvider
     * @covers ::includes
     *
     * @param string $beforeDate
     * @param string $dateToCheck
     * @param bool   $expected
     */
    public function testIncludesDate(string $beforeDate, string $dateToCheck, bool $expected)
    {
        $expression = new Before(DateTime::create($beforeDate));
        
        self::assertIncludesDate($expected, $expression, $dateToCheck);
    }
    
    
    public function includedDateProvider() : array
    {
        return [
            ['2015-01-01', '2015-01-01', false],
            ['2015-01-01', '2015-01-02', false],
            ['2015-01-02', '2015-01-01', true],
            ['2015-01-01 00:00:00', '2015-01-01 00:00:01', false],
            ['2015-01-01 00:00:00', '2015-01-01 00:00:00', false],
            ['2015-01-01 00:00:00', '2014-12-31 23:59:59', true],
            ['2016-02-29', '2016-02-28', true],
        ];
    }
    
    
    /**
     * @dataProvider firstOccurrenceAfterProvider
     * @covers ::firstOccurrenceAfter
     *
     * @param string      $beforeDate
     * @param string      $startDate
     * @param string|null $expected
     */
    public function testFirstOccurrenceAfter(string $beforeDate, string $startDate, $expected)
    {
        $expression = new Before(DateTime::create($beforeDate));
        
        self::assertFirstOccurrenceAfterEquals($expected, $expression, $startDate);
    }
    
    
    public function firstOccurrenceAfterProvider() : array
    {
        return [
            ['2015-01-01 00:00:00', '2015-01-01 00:00:00', null],
            ['2015-01-01 00:00:00', '2015-01-02 00:00:00', null],
            ['2015-01-01 00:00:00', '2014-12-31 23:59:59', '2014-12-31 23:59:59'],
            ['2015-01-01 00:00:01', '2015-01-01 00:00:00', '2015-01-01 00:00:00'],
        ];
    }
    
    
    /**
     * @dataProvider occurrencesOnOrAfterProvider
     * @covers ::occurrencesOnOrAfter
     *
     * @param string $beforeDate
     * @param string $startDate
     * @param array  $expected
     */
    public function testOccurrencesOnOrAfter(string $beforeDate, string $startDate, array $expected)
    {
        $expression = new Before(DateTime::create($beforeDate));
        
        self::assertOccurrencesOnOrAfterStartDateGenerateCorrectly($expected, $expression, $startDate);
    }
    
    
    public function occurrencesOnOrAfterProvider() : array
    {
        return [
            [
                '2015-01-01',
                '2015-01-01',
                [],
            ],
            [
                '2015-01-15',
                '2015-01-01',
                ['2015-01-01'],
            ],
        ];
    }
}
