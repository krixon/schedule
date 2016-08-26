<?php

namespace Krixon\Schedule;

final class Days
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
     * @param int $day
     *
     * @return bool
     */
    public static function contains(int $day) : bool
    {
        return in_array($day, self::ENUM, true);
    }
    
    
    /**
     * @param int $day
     */
    public static function assertContains(int $day)
    {
        if (!self::contains($day)) {
            throw new \InvalidArgumentException('Expected a valid day.');
        }
    }
}
