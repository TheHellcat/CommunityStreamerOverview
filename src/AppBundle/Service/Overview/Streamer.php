<?php

namespace AppBundle\Service\Overview;

use Hellcat\TwitchApiBundle\Twitch\Twitch;
use Hellcat\TwitchApiBundle\Model\Twitch\Response\Stream\StreamResponse;

/**
 * Class Streamer
 * @package AppBundle\Service\Overview
 */
class Streamer
{
    /**
     * @var Twitch
     */
    private $twitchApi;

    /**
     * Streamer constructor.
     * @param Twitch $twitch
     */
    public function __construct(Twitch $twitch)
    {
        $this->twitchApi = $twitch->api();
    }

    /**
     * @return array
     */
    public function getCommunityStreamers()
    {
        $streamers = [
            'TheRealHellcat',
            'pixel_maniacs',
            'Cirdan77',
            'Niels_Boehm',
            'gronkh'
        ];

        $streamerData = [];

        foreach ($streamers as $streamer) {
            /** @var StreamResponse $streamData */
            $streamData = $this->fetchStreamData($streamer);
            $streamerDetails = [];
            $streamerDetails['isLive'] = null !== $streamData->getStream();
            $streamerDetails['data'] = $streamData;
            $streamerData[$streamer] = $streamerDetails;
        }

        return $streamerData;
    }

    /**
     * @param $channelName
     * @return StreamResponse
     */
    private function fetchStreamData($channelName)
    {
        $userData = $this->twitchApi->users()->getUserByName($channelName);

        return $this->twitchApi->streams()->getStreamByUser($userData->getUsers()->first()->getId());
    }
}
