<?php

namespace Hellcat\TwitchApiBundle\Model\Twitch\Response\Stream;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class StreamPreview
 * @package Hellcat\TwitchApiBundle\Model\Twitch\Response\Stream
 */
class StreamPreview
{
    /**
     * @Serializer\SerializedName("small")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $small;

    /**
     * @Serializer\SerializedName("medium")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $medium;

    /**
     * @Serializer\SerializedName("large")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $large;

    /**
     * @Serializer\SerializedName("template")
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $template;

    /**
     * @return string
     */
    public function getSmall()
    {
        return $this->small;
    }

    /**
     * @param string $small
     * @return StreamPreview
     */
    public function setSmall($small)
    {
        $this->small = $small;
        return $this;
    }

    /**
     * @return string
     */
    public function getMedium()
    {
        return $this->medium;
    }

    /**
     * @param string $medium
     * @return StreamPreview
     */
    public function setMedium($medium)
    {
        $this->medium = $medium;
        return $this;
    }

    /**
     * @return string
     */
    public function getLarge()
    {
        return $this->large;
    }

    /**
     * @param string $large
     * @return StreamPreview
     */
    public function setLarge($large)
    {
        $this->large = $large;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     * @return StreamPreview
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }
}
