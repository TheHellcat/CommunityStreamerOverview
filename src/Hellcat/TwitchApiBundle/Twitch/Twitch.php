<?php

namespace Hellcat\TwitchApiBundle\Twitch;

use GuzzleHttp\Client as HttpClient;
use JMS\Serializer\Serializer;
use Hellcat\TwitchApiBundle\Model\Configuration;
use Hellcat\TwitchApiBundle\Model\Factory as ModelFactory;

/**
 * Class Twitch
 * @package Hellcat\TwitchApiBundle\Twitch
 */
class Twitch
{
    /**
     * @var array
     */
    private $factory;

    /**
     * @var Configuration
     */
    private $config;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ModelFactory
     */
    private $modelFactory;

    /**
     * Twitch constructor.
     * @param string $config Serialized version of the configuration object
     */
    public function __construct($config, Serializer $serializer, ModelFactory $modelFactory)
    {
        $this->factory = [];
        $this->serializer = $serializer;
        $this->modelFactory = $modelFactory;

        $this->httpClient = new HttpClient();

        $config = $this->fixSerializedData($config);

        $configModel = unserialize($config);
        if ($configModel instanceof Configuration) {
            $this->config = $configModel;
        } else {
            $this->config = new Configuration([]);
        }
    }

    /**
     * This function recalculates the sizes/lengths of the serialized elements
     * in a string of serialized() data.
     *
     * It unfortunately necessary due to Symfony only putting placeholders into the configuration parameters,
     * when using "%env(XXXX)%" values to fetch the actual value from the environment variables
     * (for security reasons), and then when actually passing or returning them replacing them with the actual
     * values - which corrupts serialized data based on those values.
     *
     * @param string $data
     * @return string mixed
     */
    private function fixSerializedData($data)
    {
        $fixedData = preg_replace_callback(
            '/s:([0-9]+):\"(.*?)\";/',
            function ($matches) { return "s:".strlen($matches[2]).':"'.$matches[2].'";'; },
            $data
        );

        return $fixedData;
    }

    /**
     * @return Api\Factory
     */
    public function api()
    {
        if (!isset($this->factory[__FUNCTION__])) {
            $this->factory[__FUNCTION__] = new Api\Factory($this);
        }
        return $this->factory[__FUNCTION__];
    }

    /**
     * @return Helper\Factory
     */
    public function helper()
    {
        if (!isset($this->factory[__FUNCTION__])) {
            $this->factory[__FUNCTION__] = new Helper\Factory($this);
        }
        return $this->factory[__FUNCTION__];
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @return ModelFactory
     */
    public function getModelFactory()
    {
        return $this->modelFactory;
    }
}
