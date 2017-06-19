<?php

namespace Hellcat\TwitchApiBundle\Twitch\Api;

use Hellcat\TwitchApiBundle\Model\Twitch\Response\Stream\StreamResponse;

/**
 * Class Streams
 * @package Hellcat\TwitchApiBundle\Twitch\Api
 */
class Streams extends ApiBaseClass
{
    /**
     * @param $username
     * @return array|\JMS\Serializer\scalar|mixed|object
     */
    public function getStreamByUser($username)
    {
        $parameters = [
            'channelName' => $username
        ];

        $call = $this->commHelper->parseEndpoint(ApiConstants::ENDPOINT_STREAMS_GETSTREAMBYUSER, $parameters);

        $responseJson = $this->commHelper->callTwitchApi($call->getUrl(), $call->getMethod());

        return $this->serializer->deserialize($responseJson, StreamResponse::class, 'json');
    }
}
