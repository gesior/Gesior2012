<?php

if (!defined('INITIALIZED')) {
    exit;
}

/*
CREATE TABLE `coinbase_payments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `code` varchar(180) NOT NULL,
  `amount` varchar(10) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `updated_at` int(10) NOT NULL,
  `expires_at` int(10) NOT NULL,
  `payment_url` varchar(180) NOT NULL,
  `payment_data` text NOT NULL,
  `payment_timeline` text DEFAULT NULL,
  `points` int(10) NOT NULL,
  `points_delivered` int(10) NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coinbase_payments_UN` (`code`),
  KEY `coinbase_payments_account_id_IDX` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */

class CoinbasePayment extends ObjectData
{
    const LOADTYPE_ID = 'id';
    const LOADTYPE_CODE = 'code';

    public static $table = 'coinbase_payments';
    public $data = [
        'account_id' => null,
        'code' => null,
        'amount' => null,
        'currency' => null,
        'created_at' => null,
        'updated_at' => null,
        'expires_at' => null,
        'payment_url' => null,
        'payment_data' => null,
        'payment_timeline' => null,
        'points' => null,
        'points_delivered' => 0,
        'status' => null,
    ];
    public static $fields = [
        'id',
        'account_id',
        'code',
        'amount',
        'currency',
        'created_at',
        'updated_at',
        'expires_at',
        'payment_url',
        'payment_data',
        'payment_timeline',
        'points',
        'points_delivered',
        'status',
    ];
    public $account;

    public function __construct($search_text = null, $search_by = self::LOADTYPE_ID)
    {
        if ($search_text != null) {
            $this->load($search_text, $search_by);
        }
    }

    public function load($search_text, $search_by = self::LOADTYPE_ID)
    {
        if (in_array($search_by, self::$fields)) {
            $search_string = $this->getDatabaseHandler()->fieldName($search_by) . ' = ' . $this->getDatabaseHandler(
                )->quote($search_text);
        } else {
            throw new InvalidArgumentException('Wrong CoinbasePayment search_by type.');
        }
        $fieldsArray = array();
        foreach (self::$fields as $fieldName) {
            $fieldsArray[] = $this->getDatabaseHandler()->fieldName($fieldName);
        }

        $this->data = $this->getDatabaseHandler()->query(
            'SELECT ' . implode(', ', $fieldsArray) . ' FROM ' . $this->getDatabaseHandler()->tableName(
                self::$table
            ) . ' WHERE ' . $search_string
        )->fetch();
    }

    public function save($forceInsert = false)
    {
        if (!isset($this->data['id']) || $forceInsert) {
            $keys = array();
            $values = array();
            foreach (self::$fields as $key) {
                if ($key != 'id') {
                    $keys[] = $this->getDatabaseHandler()->fieldName($key);
                    $values[] = $this->getDatabaseHandler()->quote($this->data[$key]);
                }
            }
            $this->getDatabaseHandler()->query(
                'INSERT INTO ' . $this->getDatabaseHandler()->tableName(self::$table) . ' (' . implode(
                    ', ',
                    $keys
                ) . ') VALUES (' . implode(', ', $values) . ')'
            );
            $this->setID($this->getDatabaseHandler()->lastInsertId());
        } else {
            $updates = array();
            foreach (self::$fields as $key) {
                $updates[] = $this->getDatabaseHandler()->fieldName($key) . ' = ' . $this->getDatabaseHandler()->quote(
                        $this->data[$key]
                    );
            }
            $this->getDatabaseHandler()->query(
                'UPDATE ' . $this->getDatabaseHandler()->tableName(self::$table) . ' SET ' . implode(
                    ', ',
                    $updates
                ) . ' WHERE ' . $this->getDatabaseHandler()->fieldName('id') . ' = ' . $this->getDatabaseHandler(
                )->quote($this->data['id'])
            );
        }
    }

    /**
     * @param $forceReload
     * @return Account
     */
    public function getAccount($forceReload = false)
    {
        if (!isset($this->account) || $forceReload) {
            $this->account = new Account($this->getAccountId());
        }

        return $this->account;
    }

    public function setAccount($account)
    {
        $this->account = $account;
        $this->setAccountId($account->getID());
    }

    public function getID()
    {
        return $this->data['id'];
    }

    public function setID($value)
    {
        $this->data['id'] = $value;
    }

    public function getAccountId()
    {
        return $this->data['account_id'];
    }

    public function setAccountId($value)
    {
        $this->data['account_id'] = $value;
    }

    public function getCode()
    {
        return $this->data['code'];
    }

    public function setCode($value)
    {
        $this->data['code'] = $value;
    }

    public function getAmount()
    {
        return $this->data['amount'];
    }

    public function setAmount($value)
    {
        $this->data['amount'] = $value;
    }

    public function getCreatedAt()
    {
        return $this->data['created_at'];
    }

    public function setCreatedAt($value)
    {
        $this->data['created_at'] = $value;
    }

    public function getUpdatedAt()
    {
        return $this->data['updated_at'];
    }

    public function setUpdatedAt($value)
    {
        $this->data['updated_at'] = $value;
    }

    public function getExpiresAt()
    {
        return $this->data['expires_at'];
    }

    public function setExpiresAt($value)
    {
        $this->data['expires_at'] = $value;
    }

    public function getCurrency()
    {
        return $this->data['currency'];
    }

    public function setCurrency($value)
    {
        $this->data['currency'] = $value;
    }

    public function getPaymentUrl()
    {
        return $this->data['payment_url'];
    }

    public function setPaymentUrl($value)
    {
        $this->data['payment_url'] = $value;
    }

    public function getPaymentData()
    {
        return $this->data['payment_data'];
    }

    public function setPaymentData($value)
    {
        $this->data['payment_data'] = $value;
    }

    public function getPaymentTimeline()
    {
        return $this->data['payment_timeline'];
    }

    public function setPaymentTimeline($value)
    {
        $this->data['payment_timeline'] = $value;
    }

    public function getPoints()
    {
        return $this->data['points'];
    }

    public function setPoints($value)
    {
        $this->data['points'] = $value;
    }

    public function getPointsDelivered()
    {
        return $this->data['points_delivered'];
    }

    public function updatePointsDelivered($value)
    {
        $change = $value - $this->data['points_delivered'];
        if ($change <= 0) {
            return false;
        }
        $account = $this->getAccount();
        if (!$account->isLoaded()) {
            return false;
        }

        $query = 'UPDATE ' . $this->getDatabaseHandler()->tableName(self::$table) .
            ' SET ' . $this->getDatabaseHandler()->fieldName('points_delivered') . ' = ' . $value .
            ' WHERE ' . $this->getDatabaseHandler()->fieldName('id') . ' = ' .
            $this->getDatabaseHandler()->quote($this->data['id']) .
            ' AND ' . $this->getDatabaseHandler()->fieldName('points_delivered') . ' = ' .
            $this->getDatabaseHandler()->quote($this->data['points_delivered']);

        $preparedQuery = Website::getDBHandle()->prepare($query);
        // synchronize points delivery using MySQL
        if ($preparedQuery->execute() && $preparedQuery->rowCount() == 1) {
            $accountQuery = 'UPDATE ' . $this->getDatabaseHandler()->tableName(Account::$table) .
                ' SET ' . $this->getDatabaseHandler()->fieldName('premium_points') . ' = ' .
                $this->getDatabaseHandler()->fieldName('premium_points') . ' + ' . $change .
                ' WHERE ' . $this->getDatabaseHandler()->fieldName('id') . ' = ' .
                $this->getDatabaseHandler()->quote($this->data['account_id']);
            Website::getDBHandle()->query($accountQuery);
            $this->data['points_delivered'] = $value;
            return true;
        }

        return false;
    }

    public function getStatus()
    {
        return $this->data['status'];
    }

    public function setStatus($value)
    {
        $this->data['status'] = $value;
    }

    /**
     * @param Account $account
     * @param int $limit
     * @return DatabaseList|CoinbasePayment[]
     */
    public static function getByAccount(Account $account, $limit = 100)
    {
        $payments = new DatabaseList('CoinbasePayment');
        $payments->setFilter(new SQL_Filter(new SQL_Field('account_id'), SQL_Filter::EQUAL, $account->getID()));
        $payments->addOrder(new SQL_Order(new SQL_Field('id'), SQL_Order::DESC));
        $payments->setLimit($limit);

        return $payments;
    }

    /**
     * @param int $limit
     * @return DatabaseList|CoinbasePayment[]
     */
    public static function getAll($type = '', $limit = 100)
    {
        $payments = new DatabaseList('CoinbasePayment');

        if ($type == 'completed') {
            $payments->setFilter(new SQL_Filter(new SQL_Field('status'), SQL_Filter::LIKE, 'COMPLETED%'));
        } elseif ($type == 'unresolved') {
            $payments->setFilter(new SQL_Filter(new SQL_Field('status'), SQL_Filter::LIKE, 'UNRESOLVED%'));
        } elseif ($type == 'resolved') {
            $payments->setFilter(new SQL_Filter(new SQL_Field('status'), SQL_Filter::LIKE, 'RESOLVED%'));
        } elseif ($type == 'refunded') {
            $payments->setFilter(new SQL_Filter(new SQL_Field('status'), SQL_Filter::LIKE, 'REFUNDED%'));
        }

        $payments->addOrder(new SQL_Order(new SQL_Field('id'), SQL_Order::DESC));
        $payments->setLimit($limit);

        return $payments;
    }
}
