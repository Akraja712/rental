<?php
include('includes/crud.php');
include('includes/custom-functions.php');
include_once('includes/variables.php');


/* 
    1. get_credentials()
    2. create_order($amount,$receipt='')
    3. fetch_payments($id ='')
    4. capture_payment($amount, $id, $currency = "INR")
    5. verify_payment($order_id, $razorpay_payment_id, $razorpay_signature)

    0. curl($url, $method = 'GET', $data = [])
*/
class Shiprocket
{
    private $email = "";
    private $password = "";
    private $url = "";

    function __construct()
    {
        $db = new Database();
        $db->connect();
        $fn = new custom_functions();
        $settings = $fn->get_settings('shiprocket', true);

        $this->email = (isset($settings['shiprocket_email'])) ? $settings['shiprocket_email'] : "";
        $this->password = (isset($settings['shiprocket_password'])) ? $settings['shiprocket_password'] : "";
    }
    public function get_credentials()
    {
        $data['email'] = $this->email;
        $data['password'] = $this->password;
        return $data;
    }
    public function generate_token()
    {
        $data = array(
            'email' => $this->email,
            'password' => $this->password
        );
        $url = 'https://apiv2.shiprocket.in/v1/external/auth/login';
        $method = 'POST';
        $response = $this->curl($url, $method, $data);
        $res = json_decode($response['body'], true);
        return $res;
    }
    public function create_order($data, $token)
    {
        // firebase server url to send the curl request
        $url = 'https://apiv2.shiprocket.in/v1/external/orders/create/adhoc';

        //building headers for the request
        $headers = array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //adding the fields in json format 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        //finally executing the curl request 
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        //Now close the connection
        curl_close($ch);
        // print_r($result);

        //and return the result 
        return $result;
    }
    public function check_serviceability($data, $token)
    {
        $qry_str = "?weight=" . $data['weight'] . "&length=" . $data['length'] . "&breadth=" . $data['breadth'] . "&height=" . $data['height'] . "&cod=" . $data['cod'] . "&pickup_postcode=" . $data['pickup_postal_code'] . "&delivery_postcode=" . $data['billing_pincode'];
        // firebase server url to send the curl request
        $url = 'https://apiv2.shiprocket.in/v1/external/courier/serviceability/' . $qry_str;

        //building headers for the request
        $headers = array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);

        //setting the method as post


        //adding headers 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //finally executing the curl request 
        $result = curl_exec($ch);
        // print_r($result); return false;
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function add_pickup_location($data, $token)
    {
        // firebase server url to send the curl request
        $url = 'https://apiv2.shiprocket.in/v1/external/settings/company/addpickup';

        //building headers for the request
        $headers = array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //adding the fields in json format 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        //finally executing the curl request 
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        //Now close the connection
        curl_close($ch);
        // print_r($result);

        //and return the result 
        return $result;
    }

    public function send_pickup_request($data, $data_awb, $token)
    {
        // firebase server url to send the curl request

        $url = 'https://apiv2.shiprocket.in/v1/external/courier/assign/awb';
        //building headers for the request
        $headers = array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //adding the fields in json format 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_awb));
        //finally executing the curl request 
        $result = curl_exec($ch);
        print_r($result); return false;

        $url = 'https://apiv2.shiprocket.in/v1/external/courier/generate/pickup';

        //building headers for the request
        $headers = array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //adding the fields in json format 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        //finally executing the curl request 
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        //Now close the connection
        curl_close($ch);
        // print_r($result);

        //and return the result 
        return $result;
    }


    public function capture_payment($amount, $id, $currency = "INR")
    {
        $data = array(
            'amount' => $amount,
            'currency' => $currency,
        );
        $url = $this->url . 'payments/' . $id . '/capture';
        $method = 'POST';
        $response = $this->curl($url, $method, $data);
        $res = json_decode($response['body'], true);
        return $res;
    }

    public function verify_payment($order_id, $razorpay_payment_id, $razorpay_signature)
    {
        $generated_signature = hash_hmac('sha256', $order_id . "|" . $razorpay_payment_id, $this->secret_key);
        if ($generated_signature == $razorpay_signature) {
            return true;
        } else {
            return false;
        }
    }

    public function curl($url, $method = 'GET', $data = [])
    {
        $ch = curl_init();
        $curl_options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . base64_encode($this->key_id . ':' . $this->secret_key)
            )
        );
        if (strtolower($method) == 'post') {
            $curl_options[CURLOPT_POST] = 1;
            $curl_options[CURLOPT_POSTFIELDS] = http_build_query($data);
        } else {
            $curl_options[CURLOPT_CUSTOMREQUEST] = 'GET';
        }
        curl_setopt_array($ch, $curl_options);
        $result = array(
            'body' => curl_exec($ch),
            'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        );
        return $result;
    }
}
