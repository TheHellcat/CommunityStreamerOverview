<?php

namespace Hellcat\TwitchApiBundle\Model\Twitch\Response\Stream;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class StreamResponse
 * @package Hellcat\TwitchApiBundle\Model\Twitch\Response\Stream
 */
class StreamResponse
{
    /**
     * @Serializer\SerializedName("stream")
     * @Serializer\Type("Hellcat\TwitchApiBundle\Model\Twitch\Response\Stream\StreamType")
     *
     * @var StreamType
     */
    private $stream;

    /**
     * @return StreamType
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * @param StreamType $stream
     * @return StreamResponse
     */
    public function setStream($stream)
    {
        $this->stream = $stream;
        return $this;
    }
}
