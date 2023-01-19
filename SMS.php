<?php


namespace App\Notifications;


class SMS
{
    private static $username = 'sms panel username';
    private static $password = 'sms panel password';
    private static $wsdl = "http://ippanel.com/class/sms/wsdlservice/server.php?wsdl";

    public static function send($fromNum, $toNum = array(), $messageContent, $time = '')
    {
        // turn off the WSDL cache
        ini_set("soap.wsdl_cache_enabled", "0");
        $client = new \SoapClient(self::$wsdl, array('trace' => 1));
        try {
            $user = self::$username;
            $pass = self::$password;
            $op = "send";
            //If you want to send in the future  ==> $time = '2016-07-30' //$time = '2016-07-30 12:50:50'
            $time = '';

            $res = $client->SendSMS($fromNum, $toNum, $messageContent, $user, $pass, $time, $op);
            if (intval($res) > 0)
                return intval($res);
            else
                return false;

        } catch (\SoapFault $ex) {
            print_r($ex->faultstring);
        }
    }

    public static function sendPattern($fromNum, $toNum = array(), $patternCode, $inputData = array())
    {
        try {
            $client = new \SoapClient(self::$wsdl);
            $user = self::$username;
            $pass = self::$password;

            $res = $client->sendPatternSms($fromNum, $toNum, $user, $pass, $patternCode, $inputData);
            if (intval($res) > 0)
                return intval($res);
            else
                return false;
        } catch (\SoapFault $ex) {
            print_r($ex->faultstring);
        }
    }

    public static function sendVoice($repeat, $toNum = array(), $fileUrl, $type = "", $time = "")
    {
        // turn off the WSDL cache
        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $client = new \SoapClient(self::$wsdl);
            $user = self::$username;
            $pass = self::$password;
            $res = $client->sendvoice($user, $pass, $repeat, $toNum, $type, $fileUrl, $time);
            if (intval($res) > 0)
                return intval($res);
            else
                return false;
        } catch (\SoapFault $ex) {
            print_r($ex->faultstring);
        }
    }

    public static function getCredit()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        $client = new \SoapClient(self::$wsdl);

        $user = self::$username;
        $pass = self::$password;

        $res = $client->GetCredit($user, $pass);

        return intval($res);
    }
}
