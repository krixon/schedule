<?php

namespace Krixon\Schedule\TemporalExpression;

use Krixon\DateTime\DateTime;

/**
 * An expression which represents dates occurring after another date.
 */
class Before extends TemporalExpression
{
    /**
     * @var DateTime
     */
    private $date;
    
    
    /**
     * @param DateTime $date
     */
    public function __construct(DateTime $date)
    {
        $this->date     = $date;
        $this->sequence = 760;
        
        parent::__construct();
    }
    
    
    public function __toString() : string
    {
        return parent::__toString() . ", date = $this->date";
    }
    
    
    /**
     * @inheritdoc
     */
    public function accept(TemporalExpressionVisitor $visitor)
    {
        $visitor->visitBefore($this);
    }
    
    
    /**
     * @inheritdoc
     */
    public function isSubstitutionCandidate(DateTime $date, TemporalExpression $expressionToTest) : bool
    {
        return !$this->includes($date) && $expressionToTest->includes($date);
    }
    
    
    /**
     * @inheritdoc
     */
    public function equals(TemporalExpression $other) : bool
    {
        return $this === $other || ($other instanceof static && $this->date->equals($other->date));
    }
    
    
    /**
     * @inheritdoc
     */
    public function includes(DateTime $date) : bool
    {
        return $date->isEarlierThan($this->date);
    }
    
    
    /**
     * @inheritdoc
     */
    public function firstOccurrenceOnOrAfter(DateTime $date)
    {
        return $this->includes($date) ? $date : null;
    }
    
    
    /**
     * @inheritdoc
     */
    protected function calculateFirstOccurrenceAfter(DateTime $date, ExpressionContext $context)
    {
        return $this->firstOccurrenceOnOrAfter($date);
    }
}
