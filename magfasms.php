<?php
//////////////////////////////////////
// My first repository in github.com
// By Amin Mollaee
// Call : +989137132664
// Email : mollayii.ir@gmail.com
// Website : mollayii.ir
//////////////////////////////////////

namespace common\sms;

use backend\models\SiteSetting;

/// Sending SMS class
class SMS {

    public $username = "not_set";
    public $password = "not_set";
    private $url = "http://ippanel.com/class/sms/wsdlservice/server.php?wsdl";
    public $params;
    public $res_data;

    public function __construct($username = '', $password = '') {
        $ss = SiteSetting::find()->one();
        $this->username = ($ss->smspanel_username) ? $ss->smspanel_username : $username;
        $this->password = ($ss->smspanel_password) ? $ss->smspanel_password : $password;
    }

    public function send($from, $message, $to = array()) {
        // turn off the WSDL cache
        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $client = new SoapClient($this->url);
            $user = $this->username;
            $pass = $this->password;
            $fromNum = ($from == '') ? '11111111111111' : $from;
            $toNum = $to;
            $messageContent = $message;
            $op  = "send";
            //If you want to send in the future  ==> $time = '2016-07-30' //$time = '2016-07-30 12:50:50'
            $time = '';
            
            echo $client->SendSMS($fromNum,$toNum,$messageContent,$user,$pass,$time,$op);
            echo $status;
        } catch (SoapFault $ex) {
            echo $ex->faultstring;
        }
    }

    public function getDeliver($unique_id = '') {
        $this->params = array
            (
            'uname' => $this->sp_username,
            'pass' => $this->sp_password,
            'op' => 'delivery',
            'uinqid' => $unique_id,
        );

        // Run CURL
        $this->curlFunc();
    }

    public function getCredit() {
        $this->params = array
            (
            'uname' => $this->sp_username,
            'pass' => $this->sp_password,
            'op' => 'credit',
        );

        // Run CURL
        $this->curlFunc();
    }

    public function curlFunc() {
        ini_set("soap.wsdl_cache_enabled", "0");
        $handler = @curl_init($this->sp_url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $this->params);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $response2 = @curl_exec($handler);

        $response2 = json_decode($response2);
        $res_code = $response2[0];
        $this->res_data = $response2[1];
    }

} //end of class
