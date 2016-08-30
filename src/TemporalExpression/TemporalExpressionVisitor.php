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
    
    
    /**
     * @param DateRange $expression
     *
     * @return void
     */
    public function visitDateRange(DateRange $expression);
    
    
    /**
     * @param MonthRange $expression
     *
     * @return void
     */
    public function visitMonthRange(MonthRange $expression);
}
