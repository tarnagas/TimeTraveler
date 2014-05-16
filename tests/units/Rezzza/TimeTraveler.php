<?php

namespace tests\units\Rezzza;

use mageekguy\atoum;
use Rezzza\TimeTraveler as TestedClass;

class TimeTraveler extends atoum\test
{
    public function testSetCurrentDateOffset()
    {
        $this->if(TestedClass::setCurrentDate('now'))
            ->integer(TestedClass::getCurrentTimeOffset())
            ->isEqualTo(0)

            ->and(TestedClass::setCurrentDate('+1 second'))
            ->integer(TestedClass::getCurrentTimeOffset())
            ->isEqualTo(1)

            ->and(TestedClass::setCurrentDate('-2 seconds'))
            ->integer(TestedClass::getCurrentTimeOffset())
            ->isEqualTo(-1)
            ;
    }

    public function testDateTimeConstructNotEnabled()
    {
        $this->if(TestedClass::setCurrentDate('2013-05-25 00:00:00'))
            ->object(new \DateTime())
            ->isNotEqualTo(new \DateTime('2013-05-25 00:00:00'));
    }

    public function dateTimeConstructDataProvider()
    {
        return array(
            // currentDate, args of construct, result
            array('2013-05-25 00:00:00', null, '2013-05-25 00:00:00'),
            array('2013-05-25 00:00:00', '+2 hours', '2013-05-25 02:00:00'),
            array('2013-05-25 00:00:00', '-2 hours', '2013-05-24 22:00:00'),
            array('2013-05-25 00:00:00', '10:30:20', '2013-05-25 10:30:20'),
            array('2013-05-25 10:00:00', '2014-02-02', '2014-02-02 10:00:00'),
        );
    }

    /**
     * @dataProvider dateTimeConstructDataProvider
     */
    public function testDateTimeConstruct($currentDate, $date, $result)
    {
        $this->if(TestedClass::enable())
            ->and(TestedClass::setCurrentDate($currentDate))
            ->object(new \DateTime($date))
            ->isEqualTo(new \DateTime($result));
    }

    public function timeDataProvider()
    {
        return array(
            // currentDate, result
            array('2013-05-25 00:00:00', 1369440000),
            array('2013-05-26 00:00:00', 1369526400),
        );
    }

    /**
     * @dataProvider timeDataProvider
     */
    public function testTime($currentDate, $result)
    {
        $this->if(TestedClass::enable())
            ->and(TestedClass::setCurrentDate($currentDate))
            ->integer(time())
            ->isIdenticalTo($result);
    }

    public function microtimeDataProvider()
    {
        return array(
            // currentDate, result
            array('2013-05-25 00:00:00', 1369440000),
            array('2013-05-26 00:00:00', 1369526400),
        );
    }

    /**
     * @dataProvider microtimeDataProvider
     */
    public function testMicrotime($currentDate, $result)
    {
        $this->if(TestedClass::enable())
            ->and(TestedClass::setCurrentDate($currentDate))

            ->integer(intval(microtime(true)))
            ->isEqualTo($result)

            ->string(microtime(false))
            ->endWith((string) $result);
    }
}