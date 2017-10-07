<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Form\AddScheduleType;
use AppBundle\Model\Form\AddSchedule;
use AppBundle\Entity\TwitchChannels;
use AppBundle\Entity\TwitchSchedules;

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
        $authData = $this->authenticate($request);
        if( (!$authData['success']) && (null !== $authData['redirect'])) {
            return $authData['redirect'];
        }

        $streamerService = $this->get('service_streamer');
        $doctrine = $this->get('doctrine');

        /** @var TwitchChannels $channelData */
        $channelData = $streamerService->fetchLocalChannelData( $authData['twitchUserId'] );

        $form = $this->createForm(AddScheduleType::class, null, []);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ) {
            /** @var AddSchedule $formData */
            $formData = $form->getData();

            $newSchedule = $channelData->getNewSchedule();
            $newSchedule
                ->setDayOfWeek($formData->getDayOfWeek())
                ->setTimeStart($formData->getTimeStart())
                ->setTimeEnd($formData->getTimeEnd())
                ->setTopic($formData->getTopic())
                ->setTwitchUserId($authData['twitchUserId'])
                ->setTwitchUser( $channelData );

            $doctrine->getManager()->persist( $newSchedule );
            $doctrine->getManager()->flush();
        }

        // TODO: move this into own action like /editschedule/delete/XXXXXX
        // 'cause diz aint f'ing RESTful - nuff sed :-p
        if( $request->query->has( 'deleteEntry' ) ) {
            $scheduleEntry = $doctrine->getManager()->getRepository(TwitchSchedules::class)->findOneBy(
                [
                    'id' => $request->query->get('deleteEntry')
                ]
            );

            if( null !== $scheduleEntry ) {
                $doctrine->getManager()->remove($scheduleEntry);
                $doctrine->getManager()->flush();
            }
        }

        return [
            'form' => $form->createView(),
            'channelData' => $channelData
        ];
    }
	
	/**
	 * @Route("/showschedule", name="app_schedule_show")
	 * @Template()
	 */
    public function showScheduleAction(Request $request) {
		$doctrine = $this->get('doctrine');
		$twitchChannel = $doctrine->getRepository(TwitchChannels::class);
		
		// List of all Days
		$dayList = $this->getDaysOfMonths(date("Y"), date("m"), 1, 1);
		
		// List of all events
		$scheduleList = [];
		
		// Load and iterate all Schedule to convert in new format
		$streamerScheduleList = $doctrine->getRepository(TwitchSchedules::class)->findAll();
		foreach ($streamerScheduleList AS $streamScheduleItem) {
			
			$channelData = $twitchChannel->findOneBy([
				"id" => $streamScheduleItem->getLocalChannelId()
			]);
			
			foreach ($dayList AS $day) {
				if ($day["weekday"] == $streamScheduleItem->getDayOfWeek()) {
				
					// Make hour with 0 begin
					$startHour	= $streamScheduleItem->getTimeStart();
					$endHour	= $streamScheduleItem->getTimeEnd();
					if ($startHour < 10) {
						$startHour = "0".$startHour;
					}
					if ($endHour < 10) {
						$endHour = "0".$endHour;
					}
				
					// Save Date in calendar format
					$scheduleList[] = [
						"title"		=> $channelData->getChannelName()." > ".$streamScheduleItem->getTopic(),
						"start"		=> $day["year"]."-".$day["month"]."-".$day["day"]."T".$startHour.":00:00",
						"end"		=> $day["year"]."-".$day["month"]."-".$day["day"]."T".$endHour.":00:00",
					];
				}
			}
		}
		
		return [
			'scheduleList' => $scheduleList
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
	
	/**
	 * Get Dates of a range
	 * @param	integer|string 	$year			Start year
	 * @param	integer|string 	$month			Start month
	 * @param 	integer			$monthBefor		Amount how many month befor the range sould be start
	 * @param	integer			$monthAfter		Amount how many month after the range sould be end
	 * @return	array							List if all Dates in the range in format => ['year','month','day','weekday']
	 */
	private function getDaysOfMonths($year, $month, $monthBefor, $monthAfter) {
    	
    	$days = [];
    	for ($i=($month-$monthBefor); $i<=($month+$monthAfter); $i++) {
    		$currentYear	= $year;
    		$currentMonth	= $i;
    		
    		if ($i < 1) {
    			$currentYear--;
    			$currentMonth = (12-($i*1));
			}
			if ($i > 12) {
				$currentYear++;
				$currentMonth = ($i-12);
			}
		
			$_days = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
    		for ($d=1; $d<=$_days; $d++) {
    		
    			// Get weekday of date (monday = 0, sunday = 6)
				$weekday = (date("w", strtotime($currentYear."-".$currentMonth."-".$d))-1);
				if ($weekday < 0) {
					$weekday = 6;
				}
    
				// Month and day begin with 0
				if ($currentMonth < 10 && strlen($currentMonth) < 2) {
					$currentMonth = "0".$currentMonth;
				}
				if ($d < 10) {
					$d = "0".$d;
				}
				
				// Save Datedata
				$days[] = [
					"year"		=> $currentYear,
					"month"		=> $currentMonth,
					"day"		=> $d,
					"weekday"	=> $weekday
				];
			}
		}

		return $days;
	}
}
