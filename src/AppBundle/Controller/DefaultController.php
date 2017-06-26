<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $streamerService = $this->get('service_streamer');

        $streamerData = $streamerService->getCommunityStreamers();

        return [
            'streamerData' => $streamerData
        ];
    }

    /**
     * @Route("/add", name="app_streamer_add")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $streamerService = $this->get('service_streamer');
        $twitchAuth = $this->get('hellcat_twitch_authhandler');

        $success = false;
        $message = '';

        $redirUrl = $request->getSchemeAndHttpHost() . $this->generateUrl('app_streamer_add');

        if ($request->query->has('code')) {
            // TODO: try/catch this and handle possible API call errors
            $twitchUserData = $twitchAuth->fetchToken($request->query->get('code', ''), $redirUrl);
            $streamerService->addStreamer($twitchUserData);
            $success = true;
        } else if ($request->query->has('error')) {
            $message = $request->query->get('error_description', 'keiner weiss was....');
            $success = false;
        } else {
            return $this->redirect($twitchAuth->init($redirUrl));
        }

        return [
            'success' => $success,
            'message' => $message
        ];
    }
}
