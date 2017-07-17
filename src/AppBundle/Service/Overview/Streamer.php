<?php

namespace AppBundle\Service\Overview;

use Doctrine\Bundle\DoctrineBundle\Registry as DoctrineRegistry;
use Doctrine\Common\Persistence\ObjectManager as DoctrineManager;
use Hellcat\TwitchApiBundle\Twitch\Twitch;
use Hellcat\TwitchApiBundle\Model\Twitch\Response\Stream\StreamResponse;
use AppBundle\Entity\TwitchChannels as TwitchChannelsEntity;
use Hellcat\TwitchApiBundle\Model\Twitch\User\User as TwitchUserData;
use Hellcat\TwitchApiBundle\Model\Twitch\Response\User\UserResponse as TwitchUserResponse;
use Hellcat\TwitchApiBundle\Model\Twitch\Response\Stream\StreamType;

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
        if (null === $twitchUsers) {
            throw new \Exception(__METHOD__ . ': Something during our DB query went horribly wrong!!!');
        }

        $streamerDataLive = [];
        $streamerDataOffline = [];

        /** @var TwitchChannelsEntity $streamer */
        foreach ($twitchUsers as $streamer) {
            /** @var StreamResponse $streamData */
            $streamData = $this->fetchStreamData($streamer);
            $streamerDetails = [];
            $streamerDetails['isLive'] = null !== $streamData->getStream();
            $streamerDetails['data'] = $streamData;
            $streamerDetails['profile'] = $this->fetchProfileData($streamer->getChannelName());
//            $streamerDetails['profile'] = $this->fetchProfileData($streamer->getTwitchUserId());
            $streamerDetails['uptime'] = null === $streamData->getStream() ? '0m' : $this->calcUptime($streamData->getStream());
            if ($streamerDetails['isLive']) {
                $streamerDataLive[strtolower($streamer->getChannelName())] = $streamerDetails;
            } else {
                $streamerDataOffline[strtolower($streamer->getChannelName())] = $streamerDetails;
            }
        }

        $this->dbManager->flush();

        ksort($streamerDataLive);
        ksort($streamerDataOffline);
        return array_merge($streamerDataLive, $streamerDataOffline);
    }

    /**
     * @param StreamType $streamData
     * @return string
     */
    private function calcUptime(StreamType $streamData)
    {
        // get some nice datetime objects
        $startDate = new \DateTime($streamData->getCreatedAt());
        $now = new \DateTime();

        // make sure they're in the same timezone
        $startDate->setTimezone(new \DateTimeZone('Z'));
        $now->setTimezone(new \DateTimeZone('Z'));

        // and in the end, it all boils down to unix timestamps again.... xD
        $upSeconds = $now->getTimestamp() - $startDate->getTimestamp();

        // make the bare seconds into something more readably nice
        $mins = ($upSeconds - ($upSeconds % 60)) / 60;  // looking weird? yeah, it's a mathematical perfect way of getting
        // a guaranteed "integer" without needing to cast and having weird
        // side effects / wrong numbers due to roundings or other things
        // a cast from float/double to integer may cause.
        // some more simple number juggling to get hours and minutes
        $minsR = $mins % 60;
        $minsH = $mins - ($minsR);
        $hours = $minsH / 60;
        if ($hours > 0) {
            $uptime = $hours . 'h ' . $minsR . 'm';
        } else {
            $uptime = $minsR . 'm';
        }

        return $uptime;
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
        if (
            (null === $twitchUser->getTwitchUserId())
            || (strlen($twitchUser->getTwitchUserId()) == 0)
            || ($twitchUser->getTwitchUserId() == 0)
        ) {
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
        // TODO: only add if user doesn't already exist
        $newUser = new TwitchChannelsEntity();

        $newUser
            ->setChannelName($twitchUser->getDisplayName())
            ->setTwitchUserId($twitchUser->getUserId())
            ->setAdded(time());

        $this->dbManager->persist($newUser);
        $this->dbManager->flush();
    }

    /**
     * @param $twitchUserId
     * @return TwitchChannelsEntity|null
     */
    public function fetchLocalChannelData($twitchUserId)
    {
        return $this->dbManager->getRepository(TwitchChannelsEntity::class)->findOneBy(
            [
                'twitchUserId' => $twitchUserId
            ]
        );
    }
}
