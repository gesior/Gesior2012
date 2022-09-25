<?php

if (!defined('INITIALIZED')) {
    exit;
}

class Coinbase
{
    private $apiKey = '';
    private $apiVersion = '2018-03-22';
    private $apiRequestUrl = 'https://api.commerce.coinbase.com/charges';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function createPayment($points, $amount, $currency, Account $account)
    {
        $post = [
            "name" => $points . ' points',
            "description" => $points . ' points',
            "pricing_type" => "fixed_price",
            "local_price" => [
                "amount" => $amount,
                "currency" => $currency
            ],
            "metadata" => [
                "account_id" => $account->getID()
            ]
        ];

        $response = $this->sendRequest($this->apiRequestUrl, $post);
        if ($response === false) {
            // 'sendRequest' logs own errors
            return false;
        }

        if (!isset($response['data'])) {
            error_log("[Coinbase::createPayment] Missing 'data' field");
            return false;
        }
        if (!isset($response['data']['addresses']) ||
            !isset($response['data']['code']) ||
            !isset($response['data']['created_at']) ||
            !isset($response['data']['expires_at']) ||
            !isset($response['data']['hosted_url']) ||
            !isset($response['data']['pricing']) ||
            !isset($response['data']['timeline'])) {
            error_log(
                "[Coinbase::createPayment] Missing 'addresses', 'code', 'created_at', 'expires_at', " .
                "'hosted_url', 'pricing' or 'timeline' field"
            );
            return false;
        }

        $code = $response['data']['code'];
        $createdAt = strtotime($response['data']['created_at']);
        $expiresAt = strtotime($response['data']['expires_at']);
        $paymentUrl = $response['data']['hosted_url'];
        $timeline = json_encode($response['data']['timeline']);
        $status = 'NEW';

        $paymentData = [];
        foreach ($response['data']['addresses'] as $cryptoCurrencyName => $walletNumber) {
            if (isset($response['data']['pricing'][$cryptoCurrencyName])) {
                $pricing = $response['data']['pricing'][$cryptoCurrencyName];
                $paymentData[] = [
                    'amount' => $pricing['amount'],
                    'currency' => $pricing['currency'],
                    'wallet' => $walletNumber,
                ];
            }
        }
        $paymentDataJson = json_encode($paymentData);

        $coinbasePayment = new CoinbasePayment();
        $coinbasePayment->setAccount($account);
        $coinbasePayment->setCode($code);
        $coinbasePayment->setAmount($amount);
        $coinbasePayment->setCurrency($currency);
        $coinbasePayment->setCreatedAt($createdAt);
        $coinbasePayment->setUpdatedAt($createdAt);
        $coinbasePayment->setExpiresAt($expiresAt);
        $coinbasePayment->setPaymentUrl($paymentUrl);
        $coinbasePayment->setPaymentData($paymentDataJson);
        $coinbasePayment->setPaymentTimeline($timeline);
        $coinbasePayment->setPoints($points);
        $coinbasePayment->setStatus($status);
        $coinbasePayment->save();

        if ($coinbasePayment->getID() == 0) {
            error_log("[Coinbase::createPayment] Failed to save payment in database");
            return false;
        }

        return $coinbasePayment;
    }

    /**
     * @param CoinbasePayment $coinbasePayment
     * @return bool - false = failed to get status from coinbase API
     */
    public function updatePaymentStatus(CoinbasePayment $coinbasePayment)
    {
        $url = $this->apiRequestUrl . '/' . $coinbasePayment->getCode();
        $response = $this->sendRequest($url, null);
        if ($response === false) {
            // 'sendRequest' logs own errors
            return false;
        }

        if (isset($response['data']['timeline']) && is_array($response['data']['timeline'])) {
            $lastStatusData = end($response['data']['timeline']);
            $lastStatus = $lastStatusData['status'];
            if (!empty($lastStatusData['context'])) {
                $lastStatus .= ' - ' . $lastStatusData['context'];
            }

            if ($lastStatus == 'COMPLETED') {
                if ($coinbasePayment->getPointsDelivered() == 0) {
                    // To prevent duplication of points,
                    // 'updatePointsDelivered' synchronizes multiple PHP processes using MySQL.
                    // If it failed to add points, given process cannot 'save' any changes in CoinbasePayment.
                    if ($coinbasePayment->updatePointsDelivered($coinbasePayment->getPoints())) {
                        $coinbasePayment->setStatus($lastStatus);
                        $coinbasePayment->setPaymentTimeline(json_encode($response['data']['timeline']));
                        $coinbasePayment->setUpdatedAt(time());
                        $coinbasePayment->save();
                    }
                }
            } else {
                $coinbasePayment->setStatus($lastStatus);
                $coinbasePayment->setPaymentTimeline(json_encode($response['data']['timeline']));
                $coinbasePayment->setUpdatedAt(time());
                $coinbasePayment->save();
            }
        }

        return true;
    }

    /**
     * @param string $url
     * @param array|null $post
     * @return bool|array
     */
    private function sendRequest($url, $post)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        if ($post !== null) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: PHP-IPN-Verification-Script',
            'Connection: Close',
            'X-CC-Api-Key: ' . $this->apiKey,
            'X-CC-Version: ' . $this->apiVersion,
        ));

        $response = curl_exec($ch);

        if ($response === false) {
            $errorNo = curl_errno($ch);
            $errorString = curl_error($ch);
            error_log("[Coinbase] Request to API failed with cURL code '{$errorNo}' and message '{$errorString}'");
            curl_close($ch);
            return false;
        }

        $info = curl_getinfo($ch);
        curl_close($ch);

        $httpCode = $info['http_code'];
        if (!in_array($httpCode, [200, 201], true)) {
            $shortApiResponse = substr($response, 0, 1000);
            error_log(
                "[Coinbase] Request to API returned invalid HTTP code '{$httpCode}', HTTP body: '{$shortApiResponse}'"
            );
            return false;
        }

        $responseData = @json_decode($response, true);
        if (!is_array($responseData)) {
            $shortApiResponse = substr($response, 0, 1000);
            error_log("[Coinbase] API response is not JSON array: '{$shortApiResponse}'");
            return false;
        }

        return $responseData;
    }
}
