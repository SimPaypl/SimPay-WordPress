<?php
class SimPay
{
    protected $auth = array();
    
    protected $response = array();
    protected $call = array();
    protected $arrayCodes = array(
        array('7055', 0.25),
        array('7136', 0.5),
        array('7255', 1),
        array('7355', 1.5),
        array('7455', 2),
        array('7555', 2.5),
        array('7636', 3),
        array('77464', 3.5),
        array('78464', 4),
        array('7936', 4.5),
        array('91055', 5),
        array('91155', 5.5),
        array('91455', 7),
        array('91664', 8),
        array('91955', 9.5),
        array('92055', 10),
        array('92555', 12.5),
    );
    protected $arrayCodesAdult = array(
        array('70908', 0.25),
        array('71908', 0.5),
        array('72998', 1),
        array('73908', 1.5),
        array('75908', 2.5),
        array('76908', 3),
        array('79908', 4.5),
        array('91998', 9.5),
        array('92598', 12.5),
    );
    
    public function __construct($key = '', $secret = '')
    {
        $this->auth = array(
            "auth" => array(
                "key" => $key,
                "secret" => $secret,
            )
        );
    }
    
    //private function
    public function url($value, $params = array())
    {
        $data = json_encode(array('params' => array_merge($this->auth, $params)));
        $this->call = $this->request($data, "https://simpay.pl/api/" . $value);
        return $this->call;
    }
    
    public function getStatus($params)
    {
        $this->response = $this->url('status', $params);
        return $this->response;
    }
    
    public function getServices()
    {
        $this->response = $this->url('get_services');
        return $this->response;
    }
    
    public function getServicesDB()
    {
        $this->response = $this->url('get_services_db');
        return $this->response;
    }
    
    public function getTransactionsSMS()
    {
        $this->response = $this->url('transactions_sms');
        return $this->response;
    }
    
    public function getTransactionsDB()
    {
        $this->response = $this->url('transactions_db');
        return $this->response;
    }
    
    public function getIP()
    {
        $this->response = $this->url('get_ip');
        return $this->response;
    }
    
    public function check()
    {
        if (isset($this->response) and is_array($this->response)) {
            if (isset($this->response['respond']['status']) and $this->response['respond']['status'] == 'OK') {
                return true;
            } elseif (isset($this->response['error']) and is_array($this->response['error'])) {
                return false;
            }
        } else {
            throw new Exception('Brak informacji na temat ostatniego zapytania');
        }
    }
    
    public function getSMSNumberFrom()
    {
        if (isset($this->response) and is_array($this->response)) {
            if (isset($this->response['respond']['from'])) {
                return $this->response['respond']['from'];
            } elseif (isset($this->response['error']) and is_array($this->response['error'])) {
                return '';
            }
        } else {
            throw new Exception('Brak informacji na temat ostatniego zapytania');
        }
    }
    
    //private function getSMSValue($number) {
    public function getSMSValue($number)
    {
        for ($iPosition = 0; $iPosition < count($this->arrayCodes); $iPosition++) {
            if ($this->arrayCodes[$iPosition][0] == $number) {
                return $this->arrayCodes[$iPosition][1];
            }
        }
        for ($iPosition = 0; $iPosition < count($this->arrayCodesAdult); $iPosition++) {
            if ($this->arrayCodesAdult[$iPosition][0] == $number) {
                return $this->arrayCodesAdult[$iPosition][1];
            }
        }
        return 0;
    }
    
    public function error()
    {
        if (isset($this->response['error']) and is_array($this->response['error'])) {
            return true;
        } else {
            return false;
        }
    }
    
    public function showError()
    {
        if (isset($this->response['error']) and is_array($this->response['error'])) {
            return $this->response['error'];
        } else {
            throw new Exception('Brak bledu do pokazania');
        }
    }
    
    public function getRespondValue()
    {
        if (isset($this->response['respond']['status'], $this->response['respond']['value'])) {
            return $this->response['respond']['value'];
        } else {
            return false;
        }
    }
    
    public function getRespondNumber()
    {
        if (isset($this->response['respond']['status'], $this->response['respond']['number'])) {
            return $this->response['respond']['number'];
        } else {
            return false;
        }
    }
    
    private function request($data, $url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // developer only
        $call = curl_exec($curl);
        $response = json_decode($call, true);
        $error = curl_errno($curl);
        curl_close($curl);
        
        if ($error > 0) {
            throw new RuntimeException('CURL ERROR Code: ' . $error);
        }
        
        return $response;
    }
    
    public function response()
    {
        return $this->response;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function pre($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }
}
