<?php

namespace Krixon\Schedule\TemporalExpression;

use Krixon\DateTime\DateTime;

/**
 * An expression which provides a substitution for an excluded expression.
 *
 * For example, "the first Monday of the month excluding September when the second Monday of the month".
 */
class Substitution extends TemporalExpression
{
    /**
     * @var TemporalExpression
     */
    private $included;
    
    /**
     * @var TemporalExpression
     */
    private $excluded;
    
    /**
     * @var TemporalExpression
     */
    private $substitute;
    
    
    /**
     * @param TemporalExpression $excluded
     * @param TemporalExpression $included
     * @param TemporalExpression $substitute
     */
    public function __construct(
        TemporalExpression $included,
        TemporalExpression $excluded,
        TemporalExpression $substitute
    ) {
        $this->included   = $included;
        $this->excluded   = $excluded;
        $this->substitute = $substitute;
        $this->sequence   = $included->sequence;
        
        parent::__construct();
    }
    
    
    /**
     * @inheritdoc
     */
    public function __toString() : string
    {
        return sprintf(
            '%s: excluded = %s, included = %s, substitute = %s',
            parent::__toString(),
            $this->excluded,
            $this->included,
            $this->substitute
        );
    }
    
    
    /**
     * @inheritdoc
     */
    public function accept(TemporalExpressionVisitor $visitor)
    {
        $visitor->visitSubstitution($this);
    }
    
    
    /**
     * @return TemporalExpression
     */
    public function excluded() : TemporalExpression
    {
        return $this->excluded;
    }
    
    
    /**
     * @return TemporalExpression
     */
    public function included() : TemporalExpression
    {
        return $this->included;
    }
    
    
    /**
     * @return TemporalExpression
     */
    public function substitute() : TemporalExpression
    {
        return $this->substitute;
    }
    
    
    /**
     * @inheritdoc
     */
    public function isSubstitutionCandidate(DateTime $date, TemporalExpression $expressionToTest) : bool
    {
        return $this->substitute->isSubstitutionCandidate($date, $expressionToTest);
    }
    
    
    /**
     * @inheritdoc
     */
    public function equals(TemporalExpression $other) : bool
    {
        return $this === $other || (
            $other instanceof static &&
            $this->included->equals($other->included) &&
            $this->excluded->equals($other->excluded) &&
            $this->substitute->equals($other->substitute)
        );
    }
    
    
    /**
     * @inheritdoc
     */
    public function includes(DateTime $date) : bool
    {
        if ($this->included->includes($date)) {
            return true;
        }
        
        return $this->substitute->isSubstitutionCandidate($date, $this->excluded);
    }
    
    
    /**
     * @inheritdoc
     */
    public function firstOccurrenceOnOrAfter(DateTime $date)
    {
        $first = $this->included->firstOccurrenceOnOrAfter($date);
        
        if ($first && $this->excluded->includes($first)) {
            $first = $this->substitute->firstOccurrenceOnOrAfter($date);
        }
        
        return $first;
    }
    
    
    /**
     * @inheritdoc
     */
    protected function calculateFirstOccurrenceAfter(DateTime $date, ExpressionContext $context)
    {
        $first = $this->included->calculateFirstOccurrenceAfter($date, $context);
        
        if ($first && $this->excluded->includes($first)) {
            $first = $this->substitute->calculateFirstOccurrenceAfter($date, $context);
        }
        
        return $first;
    }
    
    
    /**
     * @inheritdoc
     */
    protected function containsExpression(TemporalExpression $expression)
    {
        return $this->included->containsExpression($expression) || $this->excluded->containsExpression($expression);
    }
}
