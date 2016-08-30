<?php

namespace Krixon\Schedule\TemporalExpression;

use Krixon\DateTime\DateTime;
use Krixon\DateTime\DateTimeCalculator;

/**
 * A range of months.
 *
 * Both start and end months are inclusive.
 *
 * MonthRange(DateTime::JUN, DateTime::SEP) = June to September, inclusive.
 */
class MonthRange extends TemporalExpression
{
    /**
     * @var int
     */
    private $start;
    
    /**
     * @var int
     */
    private $end;
    
    
    /**
     * @param int $start
     * @param int $end
     */
    public function __construct(int $start, int $end)
    {
        if ($start > $end) {
            list ($start, $end) = [$end, $start];
        }
        
        $this->start    = $start;
        $this->end      = $end;
        $this->sequence = 600 + $start;
        
        parent::__construct();
    }
    
    
    public function __toString() : string
    {
        return parent::__toString() . ", start = {$this->start}, end = {$this->end}";
    }
    
    
    /**
     * @inheritdoc
     */
    public function accept(TemporalExpressionVisitor $visitor)
    {
        $visitor->visitMonthRange($this);
    }
    
    
    /**
     * @inheritdoc
     */
    public function isSubstitutionCandidate(DateTime $date, TemporalExpression $expressionToTest) : bool
    {
        $calculator = $date->withDateAtStartOfMonth()->calculator();
        
        $calculator->add(DateTimeCalculator::MONTH, 1);
        
        while (!$this->includesCalculator($calculator)) {
            if ($expressionToTest->includes($calculator->result())) {
                return true;
            }
            $calculator->add(DateTimeCalculator::MONTH, 1);
        }
        
        return false;
    }
    
    
    /**
     * @inheritdoc
     */
    public function equals(TemporalExpression $other) : bool
    {
        return $this === $other || (
            $other instanceof static &&
            $this->start === $other->start &&
            $this->end   === $other->end
        );
    }
    
    
    /**
     * @inheritdoc
     */
    public function firstOccurrenceOnOrAfter(DateTime $date) : DateTime
    {
        $calculator = $date->withDateAtStartOfMonth()->calculator();
        
        while (!$this->includesCalculator($calculator)) {
            $calculator->add(DateTimeCalculator::MONTH, 1);
        }
        
        return $calculator->result();
    }
    
    
    /**
     * @inheritdoc
     */
    public function includes(DateTime $date) : bool
    {
        return $this->includesCalculator($date->calculator());
    }
    
    
    /**
     * @inheritdoc
     */
    protected function calculateFirstOccurrenceAfter(DateTime $date, ExpressionContext $context) : DateTime
    {
        $calculator = $date->withDateAtStartOfMonth()->calculator();
    
        $calculator->add(DateTimeCalculator::MONTH, 1);
        
        while (!$this->includesCalculator($calculator)) {
            $calculator->add(DateTimeCalculator::MONTH, 1);
        }
        
        return $calculator->result();
    }
    
    
    /**
     * @param DateTimeCalculator $calculator
     *
     * @return bool
     */
    private function includesCalculator(DateTimeCalculator $calculator) : bool
    {
        $month = $calculator->month();
        
        return $month >= $this->start && $month <= $this->end;
//
//        if (($this->start === $month) || ($this->end === $month)) {
//            return true;
//        }
//
//        while ($calculator->month() !== $this->start) {
//            $calculator->modify('+1 month');
//        }
//
//        while ($calculator->month() !== $this->end) {
//            if ($calculator->month() === $month) {
//                return true;
//            }
//
//            $calculator->modify('+1 month');
//        }
//
//        return false;
    }
}
