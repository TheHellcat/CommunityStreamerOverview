<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Form\AddScheduleType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="app_default_index")
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
     * @Route("/editschedule", name="app_schedule_edit")
     * @Template()
     */
    public function editScheduleAction(Request $request)
    {
        $form = $this->createForm(AddScheduleType::class, null, []);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ) {
            $formData = $form->getData();
dump($formData);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/add", name="app_streamer_add")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $authData = $this->authenticate($request);

        if( (!$authData['success']) && (null !== $authData['redirect'])) {
            return $authData['redirect'];
        }

        return [
            'success' => $authData['success'],
            'message' => $authData['message']
        ];
    }

    /**
     * @param Request $request
     * @return array
     *
     * TODO: this is überhaupt not schön. please dringend umbuilden!!!!!!11!!11!111eins!!11elf
     *
     * !!!! DON'T TRY THIS AT HOME !!!!
     *
     */
    private function authenticate(Request $request)
    {
        $streamerService = $this->get('service_streamer');
        $twitchAuth = $this->get('hellcat_twitch_authhandler');
        $session = $this->get('session');

        if (!$session->isStarted()) {
            $session->start();
        }

        if( $session->has('twitchUserId') && (strlen( $session->get('twitchUserId', '') ) > 1) ) {
            $returnArray['success'] = true;
            $returnArray['twitchUserId'] = $session->get('twitchUserId');
            $returnArray['message'] = '';
            $returnArray['redirect'] = null;

            return $returnArray;
        }

        $redirUrl = $request->getSchemeAndHttpHost() . $this->generateUrl('app_streamer_add');
        $returnArray = [];

        if ($request->query->has('code')) {
            // TODO: try/catch this and handle possible API call errors
            $twitchUserData = $twitchAuth->fetchToken($request->query->get('code', ''), $redirUrl);
            $streamerService->addStreamer($twitchUserData);
            $returnArray['success'] = true;
            $returnArray['twitchUserId'] = $twitchUserData->getUserId();
            $returnArray['message'] = '';
            $returnArray['redirect'] = null;
            $session->set('twitchUserId', $returnArray['twitchUserId']);
        } else if ($request->query->has('error')) {
            $returnArray['success'] = false;
            $returnArray['twitchUserId'] = null;
            $returnArray['message'] = $request->query->get('error_description', 'keiner weiss was....');
            $returnArray['redirect'] = null;
        } else {
            $returnArray['success'] = false;
            $returnArray['twitchUserId'] = null;
            $returnArray['message'] = '';
            $returnArray['redirect'] = $this->redirect($twitchAuth->init($redirUrl));
        }

        return $returnArray;
    }
}
