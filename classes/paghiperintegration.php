<?php

class PagHiperIntegration
{
    const ACCEPT_TYPE = "application/json";
    const ACCEPT_CHARSET = "UTF-8";
    const DAYS_DUE_DATE = 5;
    const CREATE_URL = 'https://pix.paghiper.com/invoice/create/';
    const NOTIFICATION_URL = 'https://pix.paghiper.com/invoice/notification/';

    private $logFileDirectory = 'custom_scripts/paghiper/reported_ids/';
    private $apiKey;
    private $authToken;
    private $notifyUrl;
    private $configs;

    public function __construct($apiKey, $authToken, $notifyUrl, $configs)
    {
        $this->apiKey = $apiKey;
        $this->authToken = $authToken;
        $this->notifyUrl = $notifyUrl;
        $this->configs = $configs;
    }

    /**
     * @param Account $account
     * @param int $moneyAmount amount in cents
     * @param string $offerName name of sold item (ex. 100 Premium Points)
     * @param int $premiumPoints
     * @param string $payerEmail
     * @param string $payerName
     * @param string $payerCpf
     * @return string QR code PNG in base64
     */
    public function createPayment(
        Account $account,
        $moneyAmount,
        $offerName,
        $premiumPoints,
        $payerEmail,
        $payerName,
        $payerCpf
    ) {
        $data = [
            'apiKey' => $this->apiKey,
            'order_id' => $account->getId() . '_' . date('Y-m-d H:i:s'),
            'payer_email' => $payerEmail,
            'payer_name' => $payerName,
            'payer_cpf_cnpj' => $payerCpf,
            'notification_url' => $this->notifyUrl,
            'fixed_description' => true,
            'seller_description' => 'Account: ' . $account->getName() . ', Points: ' . $premiumPoints,
            'days_due_date' => self::DAYS_DUE_DATE,
            'items' => [
                [
                    'description' => $offerName,
                    'quantity' => 1,
                    'item_id' => '1',
                    'price_cents' => $moneyAmount
                ]
            ],
        ];

        $data = $this->getDataFromApi(self::CREATE_URL, $data);

        return $data['pix_create_request']['pix_code']['qrcode_base64'];
    }

    /**
     * @param string $apiKey
     * @param string $transactionId
     * @param string $notificationId
     * @return void
     */
    public function processNotification($apiKey, $transactionId, $notificationId): string
    {
        if ($apiKey !== $this->apiKey) {
            throw new LogicException('Invalid API key in notification');
        }

        $data = [
            'token' => $this->authToken,
            'apiKey' => $this->apiKey,
            'transaction_id' => $transactionId,
            'notification_id' => $notificationId,
        ];

        $notificationData = $this->getDataFromApi(self::NOTIFICATION_URL, $data);

        if (!isset($notificationData['status_request'])) {
            throw new LogicException('missing status_request');
        }
        $notification = $notificationData['status_request'];

        if (!isset($notification['order_id']) || !isset($notification['status'])) {
            throw new LogicException('missing order_id or status in notification');
        }
        if (!isset($notification['value_cents_paid']) || !in_array($notification['status'], ['completed', 'paid'])) {
            return 'ok, not a completed payment notification';
        }

        $userData = $notification['order_id'];
        $amountPaid = (int)$notification['value_cents_paid'];

        $logFile = $this->getLogFilePath($userData);
        // detects duplicated notifications about same payment = blocks duplicate points
        if (file_exists($logFile)) {
            return 'ok, repeated notification';
        }

        $transactionConfig = $this->getTransactionConfig($amountPaid);
        if (!$transactionConfig) {
            throw new LogicException('payment config not found for amount: ' . $amountPaid);
        }

        $accountID = $this->getAccountIdFromOrderId($userData);
        $account = new Account($accountID);
        if (!$account->isLoaded()) {
            throw new LogicException('account does not exist: ' . $accountID);
        }

        $logFileContents = 'accountID:' . $accountID . ',amount:' . $amountPaid . ',points:' . $transactionConfig['premium_points'];
        if (file_put_contents($logFile, $logFileContents) === false) {
            throw new LogicException('failed to save file: ' . $logFile);
        }

        $account->setPremiumPoints($account->getPremiumPoints() + $transactionConfig['premium_points']);
        $account->save();

        return 'ok, points added';
    }

    private function getLogFilePath($orderId): string
    {
        return $this->logFileDirectory . md5($orderId) . '.log';
    }

    private function getTransactionConfig($amountPaid)
    {
        foreach ($this->configs as $config) {
            if ($config['money_amount'] == $amountPaid) {
                return $config;
            }
        }

        return null;
    }

    private function getAccountIdFromOrderId($userData): string
    {
        $userDataParams = explode('_', $userData, 2);
        if (count($userDataParams) !== 2) {
            throw new LogicException('invalid order_id');
        }

        return $userDataParams[0];
    }

    private function getDataFromApi($url, $data)
    {
        $data_post = json_encode($data);

        $headers = array();
        $headers[] = "Accept: " . self::ACCEPT_TYPE;
        $headers[] = "Accept-Charset: " . self::ACCEPT_CHARSET;
        $headers[] = "Accept-Encoding: " . self::ACCEPT_TYPE;
        $headers[] = "Content-Type: " . self::ACCEPT_TYPE . ";charset=" . self::ACCEPT_CHARSET;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode != 201) {
            $errorData = @json_decode($result, true);
            if (isset($errorData['pix_create_request']['response_message'])) {
                throw new LogicException($errorData['pix_create_request']['response_message']);
            }

            throw new LogicException($result);
        }

        return json_decode($result, true);
    }
}
