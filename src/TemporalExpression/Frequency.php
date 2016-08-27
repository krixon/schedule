<?php

namespace Krixon\Schedule\TemporalExpression;

use Krixon\DateTime\DateRange as DateTimeRange;
use Krixon\DateTime\DateTime;
use Krixon\Schedule\IntervalPrecision;

/**
 * A frequency expression such as "2 weeks" or "6 months".
 */
class Frequency extends TemporalExpression
{
    protected $sequence = 100;
    
    /**
     * @var DateTime
     */
    private $start;
    
    /**
     * @var int
     */
    private $interval;
    
    /**
     * @var int
     */
    private $intervalPrecision;
    
    
    /**
     * @param DateTime          $start
     * @param int               $interval
     * @param IntervalPrecision $intervalPrecision
     */
    public function __construct(DateTime $start, int $interval, IntervalPrecision $intervalPrecision)
    {
        if ($intervalPrecision->isQuarters()) {
            throw new \DomainException('Frequency expressions for quarters are not supported yet.');
        }
        
        if ($interval < 1) {
            throw new \InvalidArgumentException('Interval must be positive.');
        }
        
        $this->start             = $start;
        $this->interval          = $interval;
        $this->intervalPrecision = $intervalPrecision;
        $this->sequence         += $intervalPrecision();
        
        parent::__construct();
    }
    
    
    /**
     * @inheritdoc
     */
    public function isSubstitutionCandidate(DateTime $date, TemporalExpression $expressionToTest) : bool
    {
        $interval = $this->interval();
        $check    = $date->subtract($interval);
        
        while (!$this->includes($check)) {
            if ($expressionToTest->includes($check)) {
                return true;
            }
            $check->subtract($interval);
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
            $this->start->equals($other->start) &&
            $this->intervalPrecision->equals($other->intervalPrecision) &&
            $this->interval === $other->interval
        );
    }
    
    
    /**
     * @inheritdoc
     */
    public function includes(DateTime $date) : bool
    {
        if ($date->isEarlierThan($this->start)) {
            return false;
        }
    
        $range = new DateTimeRange($this->start, $date);
        $diff  = $range->diff();
        
        if ($this->intervalPrecision->isSeconds()) {
            return $diff->seconds() % $this->interval === 0;
        }
        
        if ($this->intervalPrecision->isMinutes()) {
            return $diff->minutes() % $this->interval === 0;
        }
        
        if ($this->intervalPrecision->isHours()) {
            return $diff->hours() % $this->interval === 0;
        }
        
        if ($this->intervalPrecision->isDays()) {
            return $range->totalDays() % $this->interval === 0;
        }
        
        if ($this->intervalPrecision->isWeeks()) {
            return $range->totalWeeks() % ($this->interval * 7) === 0;
        }
        
        if ($this->intervalPrecision->isMonths()) {
            return $range->totalMonths() % $this->interval === 0;
        }
        
        return false;
    }
    
    
    /**
     * @inheritdoc
     */
    public function firstOccurrenceOnOrAfter(DateTime $date)
    {
        $first    = $this->prepareDate($date)->toInternalDateTime();
        $date     = $date->toInternalDateTime();
        $interval = new \DateInterval($this->interval());
        
        while ($first < $date) {
            $first->add($interval);
        }
        
        return DateTime::fromInternalDateTime($first);
    }
    
    
    /**
     * @inheritdoc
     */
    protected function calculateFirstOccurrenceAfter(DateTime $date, ExpressionContext $context)
    {
        $next = $this->firstOccurrenceOnOrAfter($date);
        
        if ($next->equals($date)) {
            $next = $next->add($this->interval());
        }
        
        return $next;
    }
    
    
    /**
     * Prepares a date such that insane loops are avoided.
     *
     * For example, prevents adding one second at a time over a period of days when getting occurrences.
     *
     * @param DateTime $date
     *
     * @return DateTime
     */
    private function prepareDate(DateTime $date) : DateTime
    {
        $deltaMilliseconds = $date->timestampWithMillisecond() - $this->start->timestampWithMillisecond();
        $date              = $date->toInternalDateTime();
        $skip              = clone $date;
        
        $skip->setTime($this->start->hour(), $this->start->minute(), $this->start->second());
        
        if ($deltaMilliseconds < 1000) {
            return DateTime::fromInternalDateTime($skip);
        }
    
        if ($this->intervalPrecision->isDays()) {
            $interval = 'P%dD';
            $divisor  = 86400000;
        } elseif ($this->intervalPrecision->isHours()) {
            $interval = 'PT%dH';
            $divisor  = 3600000;
        } elseif ($this->intervalPrecision->isMinutes()) {
            $interval = 'PT%dM';
            $divisor  = 60000;
        } elseif ($this->intervalPrecision->isSeconds()) {
            $interval = 'PT%dS';
            $divisor  = 1000;
        } else {
            return DateTime::fromInternalDateTime($skip);
        }
        
        $units    = (($deltaMilliseconds / $divisor) / $this->interval) * $this->interval;
        $interval = new \DateInterval(sprintf($interval, $units));
        
        $skip->add($interval);
        
        while ($skip > $date) {
            $skip->sub($interval);
        }
        
        return DateTime::fromInternalDateTime($skip);
    }
    
    
    /**
     * Creates an interval specification representing this expression.
     *
     * @return string
     */
    private function interval() : string
    {
        if ($this->intervalPrecision->isYears()) {
            $interval = 'P%dY';
        } elseif ($this->intervalPrecision->isMonths()) {
            $interval = 'P%dM';
        } elseif ($this->intervalPrecision->isWeeks()) {
            $interval = 'P%dW';
        } elseif ($this->intervalPrecision->isDays()) {
            $interval = 'P%dD';
        } elseif ($this->intervalPrecision->isHours()) {
            $interval = 'PT%dH';
        } elseif ($this->intervalPrecision->isMinutes()) {
            $interval = 'PT%dM';
        } elseif ($this->intervalPrecision->isSeconds()) {
            $interval = 'PT%dS';
        } else {
            throw new \UnexpectedValueException('Unexpected IntervalPrecision: ' . ($this->intervalPrecision)());
        }
        
        return sprintf($interval, $this->interval);
    }
}
