<?php

namespace Krixon\Schedule\TemporalExpression;

interface TemporalExpressionVisitor
{
    /**
     * @param DayInMonth $expression
     *
     * @return void
     */
    public function visitDayInMonth(DayInMonth $expression);
    
    
    /**
     * @param Frequency $expression
     *
     * @return void
     */
    public function visitFrequency(Frequency $expression);
    
    
    /**
     * @param Substitution $expression
     *
     * @return void
     */
    public function visitSubstitution(Substitution $expression);
}
