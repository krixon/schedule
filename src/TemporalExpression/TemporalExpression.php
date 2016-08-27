<?php

namespace Krixon\Schedule\TemporalExpression;

use Krixon\DateTime\DateTime;
use Krixon\DateTime\DateRange as DateTimeRange;

abstract class TemporalExpression
{
    /**
     * Expressions are sorted using this value.
     *
     * Expression evaluation depends on correct ordering. Evaluation starts from the smallest unit of time to
     * largest. When unit of time is the same, they are evaluated from least ambiguous to most.
     *
     * For example a 'date range' expression should always be evaluated last since it is the most ambiguous possible
     * expression - all other expressions would be evaluated and finally a check would be made that the result falls
     * within the date range.
     *
     * @var int
     */
    protected $sequence = PHP_INT_MAX;
    
    
    public function __construct()
    {
        if ($this->containsExpression($this)) {
            throw new \InvalidArgumentException('Recursive expression detected.');
        }
    }
    
    
    /**
     * Determines if this expression is a candidate for substitution.
     *
     * This is true when this expression could have produced the specified date based on another expression.
     *
     * @param DateTime           $date
     * @param TemporalExpression $expressionToTest
     *
     * @return bool
     */
    abstract public function isSubstitutionCandidate(DateTime $date, TemporalExpression $expressionToTest) : bool;
    
    
    /**
     * Determines if this expression equals another.
     *
     * @param TemporalExpression $other
     *
     * @return bool
     */
    abstract public function equals(TemporalExpression $other) : bool;
    
    
    /**
     * Determines if this expression includes a date.
     *
     * @param DateTime $date
     *
     * @return bool
     */
    abstract public function includes(DateTime $date) : bool;
    
    
    /**
     * Returns a date representing the first occurrence of this expression on or after a specified date.
     *
     * @param DateTime $date
     *
     * @return DateTime|null
     */
    abstract public function firstOccurrenceOnOrAfter(DateTime $date);
    
    
    /**
     * Returns a date representing the next occurrence of this expression after a specified date.
     *
     * @param DateTime $date
     *
     * @return DateTime|null
     */
    public function firstOccurrenceAfter(DateTime $date)
    {
        return $this->calculateFirstOccurrenceAfter($date, new ExpressionContext);
    }
    
    
    /**
     * Returns all occurrences which match this expression between the specified bounds.
     *
     * @param DateTimeRange $range
     *
     * @return \Generator
     */
    public function occurrencesInRange(DateTimeRange $range) : \Generator
    {
        foreach ($this->occurrencesOnOrAfter($range->from()) as $occurrence) {
            if (!$occurrence instanceof DateTime || !$range->contains($occurrence)) {
                break;
            }
            
            yield $occurrence;
        }
    }
    
    
    /**
     * @param DateTime $startDate
     * @param int|null $limit
     *
     * @return \Generator
     */
    public function occurrencesOnOrAfter(DateTime $startDate, int $limit = null) : \Generator
    {
        $next = $this->firstOccurrenceOnOrAfter($startDate);
        $i    = 0;
        
        while ($next) {
            yield $next;
            
            $previous = $next;
            $next     = $this->firstOccurrenceAfter($next);
            
            if (($next && $previous->equals($next)) || ++$i === $limit) {
                break;
            }
        }
    }
    
    
    /**
     * @param TemporalExpression $other
     *
     * @return int
     */
    public function compare(TemporalExpression $other) : int
    {
        if ($this->equals($other)) {
            return 0;
        }
        
        return $this->sequence <=> $other->sequence;
    }
    
    
    abstract protected function calculateFirstOccurrenceAfter(DateTime $date, ExpressionContext $context);
    
    
    /**
     * Determines if this expression contains another.
     *
     * @param TemporalExpression $expression
     *
     * @return bool
     */
    protected function containsExpression(TemporalExpression $expression)
    {
        return false;
    }
}
