<?php

namespace Krixon\Schedule\TemporalExpression;

class TemporalExpressionPrinter implements TemporalExpressionVisitor
{
    /**
     * @var TemporalExpression
     */
    private $expression;
    
    /**
     * @var int
     */
    private $indentSize;
    
    /**
     * @var int
     */
    private $currentIndent = 0;
    
    /**
     * @var string;
     */
    private $buffer = '';
    
    
    /**
     * @param TemporalExpression $expression
     * @param int                $indentSize
     */
    public function __construct(TemporalExpression $expression, int $indentSize = 2)
    {
        if ($indentSize < 0) {
            throw new \InvalidArgumentException("Invalid indent size: $indentSize. Indent size must be positive.");
        }
        
        $this->expression = $expression;
        $this->indentSize = $indentSize;
    }
    
    
    /**
     * @param TemporalExpression $expression
     * @param int                $indentSize
     *
     * @return string
     */
    public static function print(TemporalExpression $expression, int $indentSize = 2) : string
    {
        return new static($expression, $indentSize);
    }
    
    
    /**
     * @inheritdoc
     */
    public function __toString() : string
    {
        $this->expression->accept($this);
        
        return $this->buffer;
    }
    
    
    /**
     * @inheritdoc
     */
    public function visitDayInMonth(DayInMonth $expression)
    {
        $this->appendExpression($expression);
    }
    
    
    /**
     * @inheritdoc
     */
    public function visitFrequency(Frequency $expression)
    {
        $this->appendExpression($expression);
    }
    
    
    /**
     * @inheritdoc
     */
    public function visitSubstitution(Substitution $expression)
    {
        $this->appendIndent();
        
        $this->buffer .= "Substitution:\n";
        
        $this->indent();
        $this->appendIndent();
        
        $this->buffer .= "Include:\n";
        
        $this->indent();
        $expression->included()->accept($this);
        $this->unindent();
        
        $this->appendIndent();
        
        $this->buffer .= "Exclude:\n";
        
        $this->indent();
        $expression->excluded()->accept($this);
        $this->unindent();
        
        $this->appendIndent();
        
        $this->buffer .= "Substitute:\n";
        
        $this->indent();
        $expression->substitute()->accept($this);
        $this->unindent();
        $this->unindent();
    }
    
    
    public function visitDateRange(DateRange $expression)
    {
        $this->appendExpression($expression);
    }
    
    
    public function visitMonthRange(MonthRange $expression)
    {
        $this->appendExpression($expression);
    }
    
    
    public function visitBefore(Before $expression)
    {
        $this->appendExpression($expression);
    }
    
    
    public function visitAfter(After $expression)
    {
        $this->appendExpression($expression);
    }
    
    
    private function indent()
    {
        $this->currentIndent += $this->indentSize;
    }
    
    
    private function unindent()
    {
        $this->currentIndent -= $this->indentSize;
    }
    
    
    private function appendIndent()
    {
        $this->buffer .= str_repeat(' ', $this->currentIndent);
    }
    
    
    private function appendExpression(TemporalExpression $expression)
    {
        $this->appendIndent();
        
        $this->buffer .= "$expression\n";
    }
}
