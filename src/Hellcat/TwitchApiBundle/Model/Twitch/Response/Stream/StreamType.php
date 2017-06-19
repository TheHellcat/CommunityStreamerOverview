<?php

namespace Hellcat\TwitchApiBundle\Model\Twitch\Response\Stream;

use JMS\Serializer\Annotation as Serializer;
use Hellcat\TwitchApiBundle\Model\Twitch\Response\Channel;

/**
 * Class StreamType
 * @package Hellcat\TwitchApiBundle\Model\Twitch\Response\Stream
 */
class StreamType
{
    /**
     * @Serializer\SerializedName("_id")
     * @Serializer\Type("integer")
     *
     * @var integer
     */
    private $id;

    /**
     * @Serializer\SerializedName("game")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $game;

    /**
     * @Serializer\SerializedName("viewers")
     * @Serializer\Type("integer")
     *
     * @var integer
     */
    private $viewers;

    /**
     * @Serializer\SerializedName("video_height")
     * @Serializer\Type("integer")
     *
     * @var integer
     */
    private $videoHeight;

    /**
     * @Serializer\SerializedName("average_fps")
     * @Serializer\Type("integer")
     *
     * @var integer
     */
    private $averageFps;

    /**
     * @Serializer\SerializedName("delay")
     * @Serializer\Type("integer")
     *
     * @var integer
     */
    private $delay;

    /**
     * @Serializer\SerializedName("created_at")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $createdAt;

    /**
     * @Serializer\SerializedName("is_playlist")
     * @Serializer\Type("boolean")
     *
     * @var boolean
     */
    private $isPlaylist;

    /**
     * @Serializer\SerializedName("channel")
     * @Serializer\Type("Hellcat\TwitchApiBundle\Model\Twitch\Response\Channel")
     *
     * @var Channel
     */
    private $channel;

    /**
     * @Serializer\SerializedName("preview")
     * @Serializer\Type("Hellcat\TwitchApiBundle\Model\Twitch\Response\Stream\StreamPreview")
     *
     * @var StreamPreview
     */
    private $preview;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return StreamType
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param string $game
     * @return StreamType
     */
    public function setGame($game)
    {
        $this->game = $game;
        return $this;
    }

    /**
     * @return int
     */
    public function getViewers()
    {
        return $this->viewers;
    }

    /**
     * @param int $viewers
     * @return StreamType
     */
    public function setViewers($viewers)
    {
        $this->viewers = $viewers;
        return $this;
    }

    /**
     * @return int
     */
    public function getVideoHeight()
    {
        return $this->videoHeight;
    }

    /**
     * @param int $videoHeight
     * @return StreamType
     */
    public function setVideoHeight($videoHeight)
    {
        $this->videoHeight = $videoHeight;
        return $this;
    }

    /**
     * @return int
     */
    public function getAverageFps()
    {
        return $this->averageFps;
    }

    /**
     * @param int $averageFps
     * @return StreamType
     */
    public function setAverageFps($averageFps)
    {
        $this->averageFps = $averageFps;
        return $this;
    }

    /**
     * @return int
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * @param int $delay
     * @return StreamType
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     * @return StreamType
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function isIsPlaylist()
    {
        return $this->isPlaylist;
    }

    /**
     * @param bool $isPlaylist
     * @return StreamType
     */
    public function setIsPlaylist($isPlaylist)
    {
        $this->isPlaylist = $isPlaylist;
        return $this;
    }

    /**
     * @return StreamPreview
     */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * @param StreamPreview $preview
     * @return StreamType
     */
    public function setPreview($preview)
    {
        $this->preview = $preview;
        return $this;
    }

    /**
     * @return Channel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param Channel $channel
     * @return StreamType
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
        return $this;
    }
}
