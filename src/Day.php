<?php

namespace Krixon\Schedule;

final class Day
{
    const MON = 1;
    const TUE = 2;
    const WED = 3;
    const THU = 4;
    const FRI = 5;
    const SAT = 6;
    const SUN = 7;
    
    const ENUM = [self::MON, self::TUE, self::WED, self::THU, self::FRI, self::SAT, self::SUN];
    
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
            case self::MON:
                return 'Monday';
            case self::TUE:
                return 'Tuesday';
            case self::WED:
                return 'Wednesday';
            case self::THU:
                return 'Thursday';
            case self::FRI:
                return 'Friday';
            case self::SAT:
                return 'Saturday';
            case self::SUN:
                return 'Sunday';
        }
        
        throw new \UnexpectedValueException("Unhandled day: {$this->value}.");
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
    public static function mon() : self
    {
        return new static(self::MON);
    }
    
    
    /**
     * @return self
     */
    public static function tue() : self
    {
        return new static(self::TUE);
    }
    
    
    /**
     * @return self
     */
    public static function wed() : self
    {
        return new static(self::WED);
    }
    
    
    /**
     * @return self
     */
    public static function thu() : self
    {
        return new static(self::THU);
    }
    
    
    /**
     * @return self
     */
    public static function fri() : self
    {
        return new static(self::FRI);
    }
    
    
    /**
     * @return self
     */
    public static function sat() : self
    {
        return new static(self::SAT);
    }
    
    
    /**
     * @return self
     */
    public static function sun() : self
    {
        return new static(self::SUN);
    }
    
    
    /**
     * Determines if this is a certain day given an ISO8601 day of week number.
     *
     * @param int $day
     *
     * @return bool
     */
    public function is(int $day) : bool
    {
        return $this->value === $day;
    }
    
    
    /**
     * @return bool
     */
    public function isMon() : bool
    {
        return $this->is(self::MON);
    }
    
    
    /**
     * @return bool
     */
    public function isTue() : bool
    {
        return $this->is(self::TUE);
    }
    
    
    /**
     * @return bool
     */
    public function isWed() : bool
    {
        return $this->is(self::WED);
    }
    
    
    /**
     * @return bool
     */
    public function isThu() : bool
    {
        return $this->is(self::THU);
    }
    
    
    /**
     * @return bool
     */
    public function isFri() : bool
    {
        return $this->is(self::FRI);
    }
    
    
    /**
     * @return bool
     */
    public function isSat() : bool
    {
        return $this->is(self::SAT);
    }
    
    
    /**
     * @return bool
     */
    public function isSun() : bool
    {
        return $this->is(self::SUN);
    }
    
    
    /**
     * @param int $day
     *
     * @return bool
     */
    private static function contains(int $day) : bool
    {
        return in_array($day, self::ENUM, true);
    }
    
    
    /**
     * @param int $day
     */
    private static function assertContains(int $day)
    {
        if (!self::contains($day)) {
            throw new \InvalidArgumentException('Expected a valid day.');
        }
    }
}
