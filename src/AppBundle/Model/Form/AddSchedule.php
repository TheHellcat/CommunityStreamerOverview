<?php

namespace AppBundle\Model\Form;

/**
 * Class AddSchedule
 * @package AppBundle\Model\Form
 */
class AddSchedule
{
    /**
     * @var integer
     */
    private $dayOfWeek;

    /**
     * @var integer
     */
    private $timeStart;

    /**
     * @var integer
     */
    private $timeEnd;

    /**
     * @var string
     */
    private $topic;

    /**
     * @return int
     */
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    /**
     * @param int $dayOfWeek
     * @return AddSchedule
     */
    public function setDayOfWeek($dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeStart()
    {
        return $this->timeStart;
    }

    /**
     * @param int $timeStart
     * @return AddSchedule
     */
    public function setTimeStart($timeStart)
    {
        $this->timeStart = $timeStart;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeEnd()
    {
        return $this->timeEnd;
    }

    /**
     * @param int $timeEnd
     * @return AddSchedule
     */
    public function setTimeEnd($timeEnd)
    {
        $this->timeEnd = $timeEnd;
        return $this;
    }

    /**
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param string $topic
     * @return AddSchedule
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
        return $this;
    }
}
