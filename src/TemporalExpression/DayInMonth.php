<?php

namespace Krixon\Schedule\TemporalExpression;

use Krixon\DateTime\DateTime;

/**
 * An occurrence of a certain day in a month.
 *
 * Second Tuesday of the month: new DayInMonth(DateTime::TUE, 2)
 * Last Friday of the month: new DayInMonth(DateTime::FRI, -1)
 */
class DayInMonth extends TemporalExpression
{
    private $dayOfWeek;
    private $occurrence;
    
    
    /**
     * @param int $dayOfWeek  The day of the week. 1 is Monday.
     * @param int $occurrence The occurrence within the month. Can be negative, -1 == last occurrence etc. Valid range
     *                        is -5 to 5, excluding zero.
     */
    public function __construct(int $dayOfWeek, int $occurrence = 0)
    {
//        DateTime::assertValidDayOfWeek($dayOfWeek);
        
        if ($occurrence < -5 || $occurrence === 0 || $occurrence > 5) {
            throw new \InvalidArgumentException(
                "Invalid occurrence: $occurrence. Must be between -5 and 5, excluding 0."
            );
        }
        
        $this->sequence   = 460;
        $this->dayOfWeek  = $dayOfWeek;
        $this->occurrence = $occurrence;
        
        parent::__construct();
    }
    
    
    /**
     * @inheritdoc
     */
    public function __toString() : string
    {
        return sprintf(
            '%s: day = %s, occurrence = %d',
            parent::__toString(),
            $this->dayOfWeek,
            $this->occurrence
        );
    }
    
    
    /**
     * @return int
     */
    public function dayOfWeek() : int
    {
        return $this->dayOfWeek;
    }
    
    
    /**
     * @return int
     */
    public function occurrence() : int
    {
        return $this->occurrence;
    }
    
    
    /**
     * @inheritdoc
     */
    public function accept(TemporalExpressionVisitor $visitor)
    {
        $visitor->visitDayInMonth($this);
    }
    
    
    /**
     * @inheritdoc
     */
    public function equals(TemporalExpression $other) : bool
    {
        return $this === $other || (
            $other instanceof static &&
            $this->dayOfWeek === $other->dayOfWeek &&
            $this->occurrence === $other->occurrence
        );
    }
    
    
    /**
     * @inheritdoc
     */
    public function firstOccurrenceOnOrAfter(DateTime $date)
    {
        // Try to find the first occurrence in the current month.
        // It's possible that the first occurrence in this month has already passed.
        
        $date  = $date->withTimeAtMidnight();
        $month = $date->month();
        $first = $this->alignDayOfWeek($date);
        
        if ($first->isEarlierThan($date)) {
            // The first occurrence has already passed so that one is no good.
            // Move to the start of the next month and try again.
            
            // It is possible that the date has already overflowed to the next month, so check that first.
            if ($first->month() === $month) {
                $first = $first->withDateAtStartOfMonth()->add('P1M');
            }
            
            $first = $this->alignDayOfWeek($first);
        }
        
        return $first;
    }
    
    
    /**
     * @inheritdoc
     */
    public function includes(DateTime $date) : bool
    {
        if ($this->dayOfWeek !== $date->dayOfWeek()) {
            return false;
        }
        
        if ($this->occurrence < 0) {
            return abs($this->occurrence) === (int)(($date->daysRemainingInMonth() / 7) + 1);
        }
        
        return $this->occurrence === (int)((($date->dayOfMonth() - 1) / 7) + 1);
    }
    
    
    /**
     * @inheritdoc
     */
    public function isSubstitutionCandidate(DateTime $date, TemporalExpression $expressionToTest) : bool
    {
        $date = $date->subtract('P1D');
        
        while (!$this->includes($date)) {
            if ($expressionToTest->includes($date)) {
                return true;
            }
            
            $date = $date->subtract('P1D');
        }
        
        return false;
    }
    
    
    /**
     * @inheritdoc
     */
    protected function calculateFirstOccurrenceAfter(DateTime $date, ExpressionContext $context)
    {
        $date  = $date->withTimeAtMidnight();
        $month = $date->month();
        $next  = $this->alignDayOfWeek($date);
        
        if ($next->isEarlierThanOrEqualTo($date)) {
            // Next occurrence is before the start date.
            // Move to the start of the next month and try again.
            
            // It is possible that the date has already overflowed to the next month, so check that first.
            if ($next->month() === $month) {
                $next = $next->withDateAtStartOfMonth()->add('P1M');
            }
            
            $next = $this->alignDayOfWeek($next);
        }
        
        return $next;
    }
    
    
    /**
     * Aligns the date to the correct occurrence of the day for this expression.
     *
     * For example given the date 2015-01-01, this will return 2015-01-05 if day of week is Monday and occurrence is 1.
     *
     * @param DateTime $date
     *
     * @return DateTime
     */
    protected function alignDayOfWeek(DateTime $date)
    {
        return $date->withDateAtDayOfWeekInMonth($this->dayOfWeek, $this->occurrence);
//
//
//        // Note that IntlCalendar uses 1 for Sunday but we use ISO8601 weekdays where Monday is 1.
//        // To make the calculations simpler we use the en_GB locale and use the local day of week as this ensures
//        // that Monday is 1.
//
//        $calendar = $date->withDateAtStartOfMonth()->toIntlCalendar('en_GB');
//
//        if ($this->occurrence > 0) {
//            // Positive occurrence, move forward in the current month to find the correct date.
//
//            // Step forwards until the correct day of week is reached.
//            while (!$this->dayOfWeek->is($calendar->get(Calendar::FIELD_DOW_LOCAL))) {
//                $calendar->add(Calendar::FIELD_DAY_OF_MONTH, 1);
//            }
//
//            $calendar->add(Calendar::FIELD_DAY_OF_MONTH, ($this->occurrence - 1) * 7);
//        } else {
//            // Negative occurrence, move backwards from the end of the current month to find the correct date.
//
//            $calendar->add(Calendar::FIELD_MONTH, 1);
//            $calendar->add(Calendar::FIELD_DAY_OF_MONTH, -1);
//
//            // Step backwards until the correct day of week is reached.
//            while (!$this->dayOfWeek->is($calendar->get(Calendar::FIELD_DOW_LOCAL))) {
//                $calendar->add(Calendar::FIELD_DAY_OF_MONTH, -1);
//            }
//
//            $calendar->add(Calendar::FIELD_DAY_OF_MONTH, ($this->occurrence + 1) * 7);
//        }
//
//        return DateTime::fromIntlCalendar($calendar);
    }
    
}
