<?php

namespace Krixon\Schedule\Test\TemporalExpression;

use Krixon\DateTime\DateTime;
use Krixon\Schedule\TemporalExpression\After;

/**
 * @coversDefaultClass Krixon\Schedule\TemporalExpression\After
 */
class AfterTest extends TemporalExpressionTestCase
{
    /**
     * @dataProvider includedDateProvider
     * @covers ::includes
     *
     * @param string $afterDate
     * @param string $dateToCheck
     * @param bool   $expected
     */
    public function testIncludesDate(string $afterDate, string $dateToCheck, bool $expected)
    {
        $expression = new After(DateTime::create($afterDate));
        
        self::assertIncludesDate($expected, $expression, $dateToCheck);
    }
    
    
    public function includedDateProvider() : array
    {
        return [
            ['2015-01-01', '2015-01-01', false],
            ['2015-01-01', '2015-01-02', true],
            ['2015-01-01 00:00:00', '2015-01-01 00:00:01', true],
            ['2016-02-28', '2016-02-29', true],
        ];
    }
    
    
    /**
     * @dataProvider firstOccurrenceAfterProvider
     * @covers ::firstOccurrenceAfter
     *
     * @param string      $afterDate
     * @param string      $startDate
     * @param string|null $expected
     */
    public function testFirstOccurrenceAfter(string $afterDate, string $startDate, $expected)
    {
        $expression = new After(DateTime::create($afterDate));
        
        self::assertFirstOccurrenceAfterEquals($expected, $expression, $startDate);
    }
    
    
    public function firstOccurrenceAfterProvider() : array
    {
        return [
            ['2015-01-01 00:00:00', '2014-12-31 23:59:59', null],
            ['2015-01-01 00:00:00', '2015-01-01 00:00:00', null],
            ['2015-01-01 00:00:00', '2015-01-02 00:00:00', '2015-01-02 00:00:00'],
            ['2015-01-01 00:00:00', '2015-01-01 00:00:01', '2015-01-01 00:00:01'],
        ];
    }
    
    
    /**
     * @dataProvider occurrencesOnOrAfterProvider
     * @covers ::occurrencesOnOrAfter
     *
     * @param string $afterDate
     * @param string $startDate
     * @param array  $expected
     */
    public function testOccurrencesOnOrAfter(string $afterDate, string $startDate, array $expected)
    {
        $expression = new After(DateTime::create($afterDate));
        
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
                '2015-01-01',
                '2015-01-15',
                ['2015-01-15'],
            ],
        ];
    }
}
