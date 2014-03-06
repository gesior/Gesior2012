<?php
if(!defined('INITIALIZED'))
	exit;

/*-----------------------------------------------------------------------
  Name         : Zaypay.class.php
  Version      : 1.2.1-PHP5
  Description  : Create and Check payments at Zaypay.com
  Date         : July 2009, Amsterdam, The Netherlands
  By           : Zaypay International B.V. 2008 - 2009 (RDF)
  Last changes : Added optional amount to create_payment and list_locales 
                 functions
  -----------------------------------------------------------------------*/

  error_reporting(E_ALL);

  class Zaypay {
    private $price_setting_id    = 0;
    private $price_setting_key   = null;

    private $locale_consumer     = null;

    private $payment_id          = 0;

    private $request_method      = 'fsock'; // CURL or fsock
    private $request_url         = 'https://secure.zaypay.com';
    private $request_url_scheme  = null;
    private $request_url_host    = null;
    private $request_url_port    = null;

    private $error               = null;

/*-----------------------------------------------------------------------
  Construct
  -----------------------------------------------------------------------*/

    public function __construct ($price_setting_id = null, $price_setting_key = null) {
      if ($price_setting_id !== null and is_numeric($price_setting_id)) {
        $this->price_setting_id = $price_setting_id;
      }
      if ($price_setting_key !== null) {
        $this->price_setting_key = $price_setting_key;
      }
    }

/*-----------------------------------------------------------------------
  Public Functions
  -----------------------------------------------------------------------*/

    /**
     * locale_for_ip function.
     *
     * @access public
     * @param string $ip_address
     * @return string locale of ip address
     */
    public function locale_for_ip ($ip_address) {
      $XML  = $this->RequestURL("/{$ip_address}/pay/{$this->price_setting_id}/locale_for_ip?key={$this->price_setting_key}");
      $oXML = $this->parseXML($XML);

      if (isset($oXML->locale)) {
        return ($this->setLocale((string) $oXML->locale));
      }
      else {
        return null;
      }
    }

    /**
     * list_locales function.
     *
     * @access public
     * @return array with available countries and languages
     */
    public function list_locales ($optional_amount = null) {
      $XML  = $this->RequestURL("/{$optional_amount}/pay/{$this->price_setting_id}/list_locales?key={$this->price_setting_key}");
      $oXML = $this->parseXML($XML);

      if ($this->checkXMLError($oXML)) {
        return false;
      }

      foreach ($oXML->languages->language AS $language) {
        $language = (array) $language;

        $language_list[] = array(
          'code' => $language['code'],
          'english-name' => $language['english-name'],
          'native-name' => $language['native-name']
          );
      }

      foreach ($oXML->countries->country As $country) {
        $country = (array) $country;

        $country_list[] = array(
          'code' => $country['code'],
          'name' => $country['name']
          );
      }

      return array('languages' => $language_list, 'countries' => $country_list);
    }

    /**
     * list_payment_methods function.
     *
     * @access public
     * @param string $locale
     * @return array with payment methods
     */
    public function list_payment_methods ($locale, $optional_amount = null) {    
      $XML  = $this->RequestURL("/{$optional_amount}/{$locale}/pay/{$this->price_setting_id}/payments/new?key={$this->price_setting_key}");
      $oXML = $this->parseXML($XML);
      
      $this->setLocale($locale);

      if ($this->checkXMLError($oXML)) {
        return false;
      }

      if ($oXML->{'payment-methods'}->{'payment-method'}) {
        $payment_method_list = array();

        foreach ($oXML->{'payment-methods'}->{'payment-method'} AS $payment_method) {
          $payment_method = (array) $payment_method;
          $payment_method_list[$payment_method['payment-method-id']] = $payment_method;
        }

        return $payment_method_list;
      }
      else {
        $this->error = $XML;
        return false;
      }

    }

    /**
     * create_payment function.
     *
     * @access public
     * @param string $locale
     * @param int $method_id
     * @return array with payment data
     */
    public function create_payment ($locale, $method_id, $optional_amount = null) {
      $XML  = $this->RequestURL("/{$optional_amount}/{$locale}/pay/{$this->price_setting_id}/payments/create?key={$this->price_setting_key}&payment_method_id={$method_id}");
      $oXML = $this->parseXML($XML);

      $this->setLocale($locale);

      return $this->parsePayment($oXML);
    }

    /**
     * show_payment function.
     *
     * @access public
     * @param int $payment_id
     * @return array with payment data
     */
    public function show_payment ($payment_id) {
      $XML  = $this->RequestURL("///pay/{$this->price_setting_id}/payments/{$payment_id}?key={$this->price_setting_key}");
      $oXML = $this->parseXML($XML);

      return $this->parsePayment($oXML);
    }

    /**
     * verification_code function.
     *
     * @access public
     * @param int $payment_id
     * @param string $verification_code
     * @return array with payment data
     */
    public function verification_code ($payment_id, $verification_code) {
      $XML  = $this->RequestURL("///pay/{$this->price_setting_id}/payments/{$payment_id}/verification_code?key={$this->price_setting_key}&verification_code={$verification_code}");
      $oXML = $this->parseXML($XML);

      return $this->parsePayment($oXML);
    }

    /**
     * mark_payload_provided function.
     *
     * @access public
     * @param int $payment_id
     * @return array with payment data
     */
    public function mark_payload_provided ($payment_id) {
      $XML  = $this->RequestURL("///pay/{$this->price_setting_id}/payments/{$payment_id}/mark_payload_provided?key={$this->price_setting_key}");
      $oXML = $this->parseXML($XML);

      return $this->parsePayment($oXML);
    }

/*-----------------------------------------------------------------------
  Private Functions
  -----------------------------------------------------------------------*/

    /**
    * @access private
    * @param string $uri
    * @return content of URL
    */
    private function RequestURL ($uri) {
      if (function_exists('curl_init')) {
        $this->request_method = 'CURL';
      }

      $url = parse_url($this->request_url);
      $this->request_url_scheme = (isset($url['scheme'])) ? $url['scheme'] : 'http://';
      $this->request_url_host   = $url['host'];
      $this->request_url_port   = (isset($url['port'])) ? $url['port'] : (($this->request_url_scheme == 'https') ? 443 : 80);

      if ($this->request_method == 'CURL') {
        return $this->RequestURLCURL ($uri);
      }
      else {
        return $this->RequestURLFsock ($uri);
      }
    }

    /**
    * @access private
    * @param string $uri
    * @return content of URL fetched by fsock
    */
    private function RequestURLFsock ($uri) {
      $buf    = '';

      $scheme = ($this->request_url_scheme == 'https') ? 'ssl://' : '';

      $fp = fsockopen($scheme . $this->request_url_host, $this->request_url_port, $errno, $errstr, 10);
    	if ($fp) {
    		$push = "GET {$uri} HTTP/1.1" . "\r\n" .
    				    "Host: {$this->request_url_host}" . "\r\n" .
    				    "Accept: application/xml" . "\r\n" .
    				    "Connection: close" . "\r\n\r\n";

    		fputs($fp, $push);
    		while (!feof($fp))
    		  $buf .= fgets($fp, 128);
    		fclose($fp);

        list($headers, $content) = preg_split("/(\r?\n){2}/", $buf, 2);

        return $content;
    	}
    	else {
    	  return null;
    	}
    }

    /**
    * @access private
    * @param string $uri
    * @return content of URL fetched by CURL
    */
    private function RequestURLCURL ($uri) {
    	$ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $this->request_url_scheme . '://' . $this->request_url_host . $uri);
      curl_setopt($ch, CURLOPT_PORT, $this->request_url_port);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml'));
      curl_setopt($ch, CURLOPT_HEADER, false);

      $content = curl_exec($ch);

      curl_close($ch);

      return $content;
    }

    /**
    * @access private
    * @param string $XML
    * @return XML in Object
    */
    private function parseXML ($XML) {
      try {
        $data = new SimpleXMLElement($XML);
  		  if ($data == false) {
  		    return false;
  		  }

    		return $data;
      }
      catch (Exception $e) {
        return false;
      }
    }

    /**
    * @access private
    * @param object $oXML
    * @return array with payment_info
    */
    private function parsePayment ($oXML) {
      if ($this->checkXMLError($oXML)) {
        return false;
      }
      elseif ($oXML->payment) {
        return $this->object2array($oXML);
      }
      else {
        return false;
      }
    }

    /**
    * @access private
    * @param object $payment_object
    * @return array with payment_info
    */
    private function object2array ($payment_object) {
      $this->payment_id         = $payment_object->payment->id;
      $payment_array            = (array) $payment_object;
      $payment_array['payment'] = (array) $payment_object->payment;

      unset($payment_array['@attributes']);

      return ($this->payment_method_info = $payment_array);
    }

    /**
    * @access private
    * @param object $oXML
    * @return true when error object is present
    */
    private function checkXMLError ($oXML) {    
      if (isset($oXML->error)) {
        $this->error = $oXML->error;

        return true;
      }
      else {
        return false;
      }
    }

/*-----------------------------------------------------------------------
  SET and GET functions
  -----------------------------------------------------------------------*/

    /**
    * @param string $request_method
    * @return true
    */
    public function setRequestMethod ($request_method) {
      if ($request_method == 'CURL' or  $request_method == 'curl') {
        $this->request_method = 'CURL';
      }
      elseif ($request_method == 'fsock') {
        $this->request_method = 'fsock';
      }

      return true;
    }

    /**
    * @param string $locale
    * @return true when xx-XX
    */
    public function setLocale ($locale) {
      if (preg_match('/^([a-z]{2})-([A-Z]{2})$/', $locale)) {      
        return ($this->locale_consumer = $locale);
      }
      else {
        return false;
      }
    }

    /**
    * @return locale of consumer
    */
    public function getLocale ($type = null) {
      if ($type !== null) {
        if (strstr($this->locale_consumer, '-')) {
          list($locale['language'], $locale['country']) = explode('-', $this->locale_consumer);
          
          return (isset($locale[$type])) ? $locale[$type] : null;
        }
        else {
          return null;
        }
      }
    
      return $this->locale_consumer;
    }

    /**
    * @return payment information
    */
    public function getPaymentInfo () {
      return $this->payment_method_info;
    }

    /**
    * @return payment ID
    */
    public function getPaymentId () {
      return $this->payment_id;
    }

    /**
    * @return error (if occured)
    */
    public function getError () {
      return $this->error;
    }

  }