<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class TwitchSchedules
 *
 * @ORM\Table(name="twitch_schedules")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TwitchSchedulesRepository")
 *
 * @package AppBundle\Entity
 */
class TwitchSchedules
{
    /**
     * @ORM\Column(name="id", type="string", length=64)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="local_channel_id", type="string", length=64)
     *
     * @var string
     */
    private $localChannelId;

    /**
     * @ORM\Column(name="twitch_user_id", type="integer")
     *
     * @var integer
     */
    private $twitchUserId;

    /**
     * @ORM\Column(name="day", type="integer")
     *
     * @var integer
     */
    private $dayOfWeek;

    /**
     * @ORM\Column(name="time_start", type="integer")
     *
     * @var integer
     */
    private $timeStart;

    /**
     * @ORM\Column(name="time_end", type="integer")
     *
     * @var integer
     */
    private $timeEnd;

    /**
     * @ORM\Column(name="topic", type="string", length=128)
     *
     * @var string
     */
    private $topic;

    /**
     * @ORM\ManyToOne(targetEntity="TwitchChannels", inversedBy="id")
     * @ORM\JoinColumn(name="local_channel_id", referencedColumnName="id")
     *
     * @var TwitchChannels
     */
    private $twitchUser;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return TwitchSchedules
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getTwitchUserId()
    {
        return $this->twitchUserId;
    }

    /**
     * @param int $twitchUserId
     * @return TwitchSchedules
     */
    public function setTwitchUserId($twitchUserId)
    {
        $this->twitchUserId = $twitchUserId;
        return $this;
    }

    /**
     * @return int
     */
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    /**
     * @param int $dayOfWeek
     * @return TwitchSchedules
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
     * @return TwitchSchedules
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
     * @return TwitchSchedules
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
     * @return TwitchSchedules
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTwitchUser()
    {
        return $this->twitchUser;
    }

    /**
     * @param mixed $twitchUser
     * @return TwitchSchedules
     */
    public function setTwitchUser($twitchUser)
    {
        $this->twitchUser = $twitchUser;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocalChannelId()
    {
        return $this->localChannelId;
    }

    /**
     * @param string $localChannelId
     * @return TwitchSchedules
     */
    public function setLocalChannelId($localChannelId)
    {
        $this->localChannelId = $localChannelId;
        return $this;
    }
}
