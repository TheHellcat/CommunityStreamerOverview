<?php

namespace Hellcat\TwitchApiBundle\Twitch\Api;

use Hellcat\TwitchApiBundle\Twitch\Twitch;

/**
 * Class Factory
 * @package Hellcat\TwitchApiBundle\Twitch\Api
 */
class Factory
{
    /**
     * @var array
     */
    private $factory;

    /**
     * @var Twitch
     */
    private $twMan;

    /**
     * Factory constructor.
     * @param Twitch $twMan
     */
    public function __construct(Twitch $twMan)
    {
        $this->factory = [];
        $this->twMan = $twMan;
    }

    /**
     * @return Auth
     */
    public function auth()
    {
        if (!isset($this->factory[__FUNCTION__])) {
            $this->factory[__FUNCTION__] = new Auth(
                $this->twMan->getConfig(),
                $this->twMan->getHttpClient(),
                $this->twMan->getSerializer(),
                $this->twMan->helper()->communication(),
                $this->twMan->getModelFactory()
            );
        }
        return $this->factory[__FUNCTION__];
    }

    /**
     * @return Channels
     */
    public function channels()
    {
        if (!isset($this->factory[__FUNCTION__])) {
            $this->factory[__FUNCTION__] = new Channels(
                $this->twMan->getConfig(),
                $this->twMan->getHttpClient(),
                $this->twMan->getSerializer(),
                $this->twMan->helper()->communication(),
                $this->twMan->getModelFactory()
            );
        }
        return $this->factory[__FUNCTION__];
    }

    /**
     * @return Users
     */
    public function users()
    {
        if (!isset($this->factory[__FUNCTION__])) {
            $this->factory[__FUNCTION__] = new Users(
                $this->twMan->getConfig(),
                $this->twMan->getHttpClient(),
                $this->twMan->getSerializer(),
                $this->twMan->helper()->communication(),
                $this->twMan->getModelFactory()
            );
        }
        return $this->factory[__FUNCTION__];
    }

    /**
     * @return Streams
     */
    public function streams()
    {
        if (!isset($this->factory[__FUNCTION__])) {
            $this->factory[__FUNCTION__] = new Streams(
                $this->twMan->getConfig(),
                $this->twMan->getHttpClient(),
                $this->twMan->getSerializer(),
                $this->twMan->helper()->communication(),
                $this->twMan->getModelFactory()
            );
        }
        return $this->factory[__FUNCTION__];
    }
}
