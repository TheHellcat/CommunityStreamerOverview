<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class TwitchChannels
 *
 * @ORM\Table(name="twitch_channels")
 * # , indexes={@ORM\Index(name="idx", columns={"local_userid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TwitchChannelsRepository")
 *
 * @package AppBundle\Entity
 */
class TwitchChannels
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
     * @ORM\Column(name="twitch_user_id", type="integer")
     *
     * // TODO: make this column "unique"
     *
     * @var integer
     */
    private $twitchUserId;

    /**
     * @ORM\Column(name="twitch_channel_name", type="string", length=128)
     *
     * @var string
     */
    private $channelName;

    /**
     * @ORM\Column(name="added", type="integer")
     *
     * @var integer
     */
    private $added;

    /**
     * @ORM\OneToMany(targetEntity="TwitchSchedules", mappedBy="localChannelId")
     *
     * @var TwitchSchedules[]|ArrayCollection
     */
    private $schedule;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return TwitchChannels
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
     * @return TwitchChannels
     */
    public function setTwitchUserId($twitchUserId)
    {
        $this->twitchUserId = $twitchUserId;
        return $this;
    }

    /**
     * @return string
     */
    public function getChannelName()
    {
        return $this->channelName;
    }

    /**
     * @param string $channelName
     * @return TwitchChannels
     */
    public function setChannelName($channelName)
    {
        $this->channelName = $channelName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdded()
    {
        return $this->added;
    }

    /**
     * @param mixed $added
     * @return TwitchChannels
     */
    public function setAdded($added)
    {
        $this->added = $added;
        return $this;
    }

    /**
     * @return TwitchSchedules[]|ArrayCollection
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @param TwitchSchedules[]|ArrayCollection $schedule
     * @return TwitchChannels
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;
        return $this;
    }

    public function addSchedule($schedule)
    {
        // TODO: do it
    }
}
