<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Carbon\Carbon;
use App\Model\Account;

class GoogleController extends Controller
{
    protected $client;

    public function __construct(Google_Client $client)
    {
        $this->client = $client;
        $this->client->setAuthConfig('client_secret.json');
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
    }

    public function index()
    {
        try {
            session_start();
            if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                $this->client->setAccessToken($_SESSION['access_token']);
                $service = new Google_Service_Calendar($this->client);

                $calendarId = 'primary';

                $results = $service->events->listEvents($calendarId);
                return $results->getItems();
            } else {
                return redirect()->route('oauthCallback');
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function oauth(Request $request)
    {
        try {
            session_start();
            $rurl = 'https://dev.agilecyber.com/multicalendar/';
            $this->client->setRedirectUri($rurl);
            if (!$request->has('code')) {
                $auth_url = $this->client->createAuthUrl();
                return redirect()->away($auth_url);
            } else {
                $this->client->authenticate($request->input('code'));
                $_SESSION['access_token'] = $this->client->getAccessToken();
                $oauth2Service = new \Google_Service_Oauth2($this->client);
                $userInfo = $oauth2Service->userinfo->get();
                $user = Auth::user();
                Account::create([
                    'auth_type' => 'google',
                    'mail' => $userInfo->getEmail(),
                    'access_token' => $_SESSION['access_token'],
                    'user_id' => $user->id
                ]);
                return redirect()->route('gcalendar.index');
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function create()
    {
        return view('calendar.createEvent');
    }

    public function store(Request $request)
    {
        try {
            session_start();
            $startDateTime = $request->start_date;
            $endDateTime = $request->end_date;

            if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                $this->client->setAccessToken($_SESSION['access_token']);
                $service = new Google_Service_Calendar($this->client);

                $calendarId = 'primary';
                $event = new Google_Service_Calendar_Event([
                    'summary' => $request->title,
                    'description' => $request->description,
                    'start' => ['dateTime' => $startDateTime],
                    'end' => ['dateTime' => $endDateTime],
                    'reminders' => ['useDefault' => true],
                ]);
                $results = $service->events->insert($calendarId, $event);
                if (!$results) {
                    return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
                }
                return response()->json(['status' => 'success', 'message' => 'Event Created']);
            } else {
                return redirect()->route('oauthCallback');
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function show($eventId)
    {
        try {
            session_start();
            if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                $this->client->setAccessToken($_SESSION['access_token']);
                $service = new Google_Service_Calendar($this->client);
                $event = $service->events->get('primary', $eventId);

                if (!$event) {
                    return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
                }
                return response()->json(['status' => 'success', 'data' => $event]);
            } else {
                return redirect()->route('oauthCallback');
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $eventId)
    {
        try {
            session_start();
            if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                $this->client->setAccessToken($_SESSION['access_token']);
                $service = new Google_Service_Calendar($this->client);

                $startDateTime = Carbon::parse($request->start_date)->toRfc3339String();

                $eventDuration = 30; //minutes

                if ($request->has('end_date')) {
                    $endDateTime = Carbon::parse($request->end_date)->toRfc3339String();
                } else {
                    $endDateTime = Carbon::parse($request->start_date)->addMinutes($eventDuration)->toRfc3339String();
                }

                $event = $service->events->get('primary', $eventId);

                $event->setSummary($request->title);
                $event->setDescription($request->description);

                $start = new Google_Service_Calendar_EventDateTime();
                $start->setDateTime($startDateTime);
                $event->setStart($start);

                $end = new Google_Service_Calendar_EventDateTime();
                $end->setDateTime($endDateTime);
                $event->setEnd($end);

                $updatedEvent = $service->events->update('primary', $event->getId(), $event);

                if (!$updatedEvent) {
                    return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
                }
                return response()->json(['status' => 'success', 'data' => $updatedEvent]);
            } else {
                return redirect()->route('oauthCallback');
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($eventId)
    {
        try {
            session_start();
            if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                $this->client->setAccessToken($_SESSION['access_token']);
                $service = new Google_Service_Calendar($this->client);
                $service->events->delete('primary', $eventId);
            } else {
                return redirect()->route('oauthCallback');
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function oauthCallback(){
        return redirect()->route('oauth');
    }
}
