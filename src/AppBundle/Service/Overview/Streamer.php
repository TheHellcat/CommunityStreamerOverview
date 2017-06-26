<?php

namespace AppBundle\Service\Overview;

use Doctrine\Bundle\DoctrineBundle\Registry as DoctrineRegistry;
use Doctrine\Common\Persistence\ObjectManager as DoctrineManager;
use Hellcat\TwitchApiBundle\Twitch\Twitch;
use Hellcat\TwitchApiBundle\Model\Twitch\Response\Stream\StreamResponse;
use AppBundle\Entity\TwitchChannels as TwitchChannelsEntity;
use Hellcat\TwitchApiBundle\Model\Twitch\User\User as TwitchUserData;
use Hellcat\TwitchApiBundle\Model\Twitch\Response\User\UserResponse as TwitchUserResponse;

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
     * @var DoctrineRegistry
     */
    private $doctrine;

    /**
     * @var DoctrineManager
     */
    private $dbManager;

    /**
     * Streamer constructor.
     * @param Twitch $twitch
     * @param DoctrineRegistry $doctrine
     */
    public function __construct(
        Twitch $twitch,
        DoctrineRegistry $doctrine
    )
    {
        $this->twitchApi = $twitch->api();
        $this->doctrine = $doctrine;
        $this->dbManager = $this->doctrine->getManager();
    }

    /**
     * @return array
     */
    public function getCommunityStreamers()
    {
        $twitchUsers = $this->dbManager->getRepository(TwitchChannelsEntity::class)->findAll();

        // TODO: This has to be done properly, only for now and getting things done at all
        if( null === $twitchUsers ) {
            throw new \Exception(__METHOD__ . ': Something during our DB query went horribly wrong!!!');
        }

        $streamerData = [];

        foreach ($twitchUsers as $streamer) {
            /** @var StreamResponse $streamData */
            $streamData = $this->fetchStreamData($streamer);
            $streamerDetails = [];
            $streamerDetails['isLive'] = null !== $streamData->getStream();
            $streamerDetails['data'] = $streamData;
            $streamerDetails['profile'] = $this->fetchProfileData($streamer->getChannelName());
//            $streamerDetails['profile'] = $this->fetchProfileData($streamer->getTwitchUserId());
            $streamerData[$streamer->getChannelName()] = $streamerDetails;
        }

        $this->dbManager->flush();

        return $streamerData;
    }

    /**
     * @param string $username
     * @return TwitchUserResponse
     */
    private function fetchProfileData($username)
    {
        // TODO: maybe change to get user by ID
        // TODO: cache profile data for 24h
        return $this->twitchApi->users()->getUserByName($username)
            ->getUsers()->first();
    }

    /**
     * @param TwitchChannelsEntity $channelName
     * @return StreamResponse
     */
    private function fetchStreamData(TwitchChannelsEntity $twitchUser)
    {
        if( (null === $twitchUser->getTwitchUserId()) || (strlen($twitchUser->getTwitchUserId()) == 0) ) {
            $userData = $this->twitchApi->users()->getUserByName($twitchUser->getChannelName());
            $userId = $userData->getUsers()->first()->getId();

            $twitchUser->setTwitchUserId($userId);
            $this->dbManager->persist($twitchUser);
        } else {
            $userId = $twitchUser->getTwitchUserId();
        }

        return $this->twitchApi->streams()->getStreamByUser($userId);
    }

    /**
     * @param TwitchUserData $twitchUser
     */
    public function addStreamer(TwitchUserData $twitchUser)
    {
        // TODO: add only if user doesn't exist already
        $newUser = new TwitchChannelsEntity();

        $newUser
            ->setChannelName($twitchUser->getDisplayName())
            ->setTwitchUserId($twitchUser->getUserId())
            ->setAdded(time());

        $this->dbManager->persist($newUser);
        $this->dbManager->flush();
    }
}
