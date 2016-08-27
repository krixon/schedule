<?php

namespace Krixon\Schedule;

final class IntervalPrecision
{
    const SECONDS  = 0;
    const MINUTES  = 1;
    const HOURS    = 2;
    const DAYS     = 3;
    const WEEKS    = 4;
    const MONTHS   = 5;
    const QUARTERS = 6;
    const YEARS    = 7;
    
    const ENUM = [
        self::SECONDS,
        self::MINUTES,
        self::HOURS,
        self::DAYS,
        self::WEEKS,
        self::MONTHS,
        self::QUARTERS,
        self::YEARS,
    ];
    
    /**
     * @var int
     */
    private $value;
    
    
    /**
     * @param int $precision
     */
    public function __construct(int $precision)
    {
        self::assertContains($precision);
        
        $this->value = $precision;
    }
    
    
    /**
     * @return int
     */
    public function __invoke() : int
    {
        return $this->value;
    }
    
    
    /**
     * @return string
     */
    public function __toString() : string
    {
        switch ($this->value) {
            case self::SECONDS:
                return 'seconds';
            case self::MINUTES:
                return 'minutes';
            case self::HOURS:
                return 'hours';
            case self::DAYS:
                return 'days';
            case self::WEEKS:
                return 'weeks';
            case self::MONTHS:
                return 'months';
            case self::QUARTERS:
                return 'quarters';
            case self::YEARS:
                return 'years';
        }
        
        throw new \UnexpectedValueException("Unhandled interval precision: {$this->value}.");
    }
    
    
    /**
     * @param self $other
     *
     * @return bool
     */
    public function equals(self $other) : bool
    {
        return $this->value === $other->value;
    }
    
    
    /**
     * @return self
     */
    public static function seconds() : self
    {
        return new static(self::SECONDS);
    }
    
    
    /**
     * @return self
     */
    public static function minutes() : self
    {
        return new static(self::MINUTES);
    }
    
    
    /**
     * @return self
     */
    public static function hours() : self
    {
        return new static(self::HOURS);
    }
    
    
    /**
     * @return self
     */
    public static function days() : self
    {
        return new static(self::DAYS);
    }
    
    
    /**
     * @return self
     */
    public static function weeks() : self
    {
        return new static(self::WEEKS);
    }
    
    
    /**
     * @return self
     */
    public static function months() : self
    {
        return new static(self::MONTHS);
    }
    
    
    /**
     * @return self
     */
    public static function quarters() : self
    {
        return new static(self::QUARTERS);
    }
    
    
    /**
     * @return self
     */
    public static function years() : self
    {
        return new static(self::YEARS);
    }
    
    
    /**
     * @param int $precision
     *
     * @return bool
     */
    public function is(int $precision) : bool
    {
        return $this->value === $precision;
    }
    
    
    /**
     * @return bool
     */
    public function isSeconds() : bool
    {
        return $this->is(self::SECONDS);
    }
    
    
    /**
     * @return bool
     */
    public function isMinutes() : bool
    {
        return $this->is(self::MINUTES);
    }
    
    
    /**
     * @return bool
     */
    public function isHours() : bool
    {
        return $this->is(self::HOURS);
    }
    
    
    /**
     * @return bool
     */
    public function isDays() : bool
    {
        return $this->is(self::DAYS);
    }
    
    
    /**
     * @return bool
     */
    public function isWeeks() : bool
    {
        return $this->is(self::WEEKS);
    }
    
    
    /**
     * @return bool
     */
    public function isMonths() : bool
    {
        return $this->is(self::MONTHS);
    }
    
    
    /**
     * @return bool
     */
    public function isQuarters() : bool
    {
        return $this->is(self::QUARTERS);
    }
    
    
    /**
     * @return bool
     */
    public function isYears() : bool
    {
        return $this->is(self::YEARS);
    }
    
    
    /**
     * @param int $precision
     *
     * @return bool
     */
    private static function contains(int $precision) : bool
    {
        return in_array($precision, self::ENUM, true);
    }
    
    
    /**
     * @param int $precision
     */
    private static function assertContains(int $precision)
    {
        if (!self::contains($precision)) {
            throw new \UnexpectedValueException("Invalid interval precision: $precision.");
        }
    }
}
