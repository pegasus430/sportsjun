<?php

namespace App\Helpers;

use App\Model\Team;
use App\Model\Sport;
use App\Model\UserStatistic;
use App\Model\Notifications;
use App\Model\Tournaments;
use App\Helpers\AllRequests;
use App\User;
use Auth;
use DB;
use Carbon\Carbon;
use App\Helpers\SendMail;

class Esports
{
    public static function create_smite_session($signature) {
        $s = "/";
        $utc_timestamp = date('YmdHis',time());
        $session_url = Constants::SMITE_API_URL.Constants::SMITE_SESSION."json/".Constants::SMITE_DEV_ID.$s.$signature.$s.$utc_timestamp;

        var_dump($session_url);

        $ch = curl_init($session_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $session = curl_exec($ch);
        curl_close($ch);
        $session = json_decode($session);

        var_dump($session);

        return $session->session_id;
    }

    public static function create_smite_signature($endpoint) {
        $utc_timestamp = date('YmdHis',time());
        $s = "/";
        $md5_string = Constants::SMITE_DEV_ID.$endpoint.Constants::SMITE_AUTH_KEY.$utc_timestamp;
        $signature = md5($md5_string);
        return $signature;
    }
}
