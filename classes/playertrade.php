<?php

/*
CREATE TABLE `player_trades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_seller_id` int(11) NOT NULL,
  `account_buyer_id` int(11) DEFAULT NULL,
  `player_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `create_date` int(11) NOT NULL,
  `price_seller` int(11) NOT NULL,
  `price_buyer` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
*/

class PlayerTrade extends ObjectData
{
    const LOADTYPE_ID = 'id';

    const STATUS_ACTIVE = 1;
    const STATUS_SOLD = 2;
    const STATUS_CANCELED = 3;

    const TYPE_PUBLIC = 1;
    const TYPE_PRIVATE = 2;

    public static $statusNames = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_SOLD => 'Sold',
        self::STATUS_CANCELED => 'Canceled',
    ];

    public static $table = 'player_trades';
    public $data = array(
        'account_seller_id' => null,
        'account_buyer_id' => null,
        'player_id' => null,
        'status' => null,
        'type' => null,
        'create_date' => null,
        'price_seller' => null,
        'price_buyer' => null
    );
    public static $fields = array(
        'id',
        'account_seller_id',
        'account_buyer_id',
        'player_id',
        'status',
        'type',
        'create_date',
        'price_seller',
        'price_buyer'
    );

    public static function getIdFromSecretCode($secretCode)
    {
        $data = explode('_', $secretCode);
        if (isset($data[0])) {
            return intval(($data[0]));
        } else {
            return -1;
        }
    }

    public function getSecretCode()
    {
        return $this->getId() . '_' . substr(md5(serialize([
                (int)$this->getPlayerId(),
                (int)$this->getCreateDate(),
                (int)$this->getAccountSellerId(),
                (int)$this->getPriceSeller(),
                (int)$this->getPriceBuyer()
            ])), 2, 10);
    }

    public function isValidSecretCode($secretCode)
    {
        return strtolower($this->getSecretCode()) == strtolower($secretCode);
    }

    public function isActive()
    {
        return $this->getStatus() == self::STATUS_ACTIVE;
    }

    public function isPublic()
    {
        return $this->getType() == self::TYPE_PUBLIC;
    }

    public function isPrivate()
    {
        return $this->getType() == self::TYPE_PRIVATE;
    }

    public function getStatusName()
    {
        return self::$statusNames[$this->getStatus()];
    }

    public function __construct($search_text = null, $search_by = self::LOADTYPE_ID)
    {
        if ($search_text != null) {
            $this->load($search_text, $search_by);
        }
    }

    public function load($search_text, $search_by = self::LOADTYPE_ID)
    {
        if (in_array($search_by, self::$fields)) {
            $search_string = $this->getDatabaseHandler()->fieldName($search_by) . ' = ' . $this->getDatabaseHandler()->quote($search_text);
        } else {
            throw new RuntimeException('Wrong Account search_by type.');
        }
        $fieldsArray = array();
        foreach (self::$fields as $fieldName) {
            $fieldsArray[$fieldName] = $this->getDatabaseHandler()->fieldName($fieldName);
        }
        $this->data = $this->getDatabaseHandler()->query('SELECT ' . implode(', ',
                $fieldsArray) . ' FROM ' . $this->getDatabaseHandler()->tableName(self::$table) . ' WHERE ' . $search_string)->fetch();
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
            $this->getDatabaseHandler()->query('INSERT INTO ' . $this->getDatabaseHandler()->tableName(self::$table) . ' (' . implode(', ',
                    $keys) . ') VALUES (' . implode(', ', $values) . ')');
            $this->setId($this->getDatabaseHandler()->lastInsertId());
        } else {
            $updates = array();
            foreach (self::$fields as $key) {
                if ($key != 'id') {
                    $updates[] = $this->getDatabaseHandler()->fieldName($key) . ' = ' . $this->getDatabaseHandler()->quote($this->data[$key]);
                }
            }
            $this->getDatabaseHandler()->query('UPDATE ' . $this->getDatabaseHandler()->tableName(self::$table) . ' SET ' . implode(', ',
                    $updates) . ' WHERE ' . $this->getDatabaseHandler()->fieldName('id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['id']));
        }
    }

    public function setId($value)
    {
        $this->data['id'] = $value;
    }

    public function getAccountSellerId()
    {
        return $this->data['account_seller_id'];
    }

    public function setAccountSellerId($value)
    {
        $this->data['account_seller_id'] = $value;
    }

    public function getAccountBuyerId()
    {
        return $this->data['account_buyer_id'];
    }

    public function setAccountBuyerId($value)
    {
        $this->data['account_buyer_id'] = $value;
    }

    public function getPlayerId()
    {
        return $this->data['player_id'];
    }

    public function setPlayerId($value)
    {
        $this->data['player_id'] = $value;
    }

    public function getStatus()
    {
        return $this->data['status'];
    }

    public function setStatus($value)
    {
        $this->data['status'] = $value;
    }

    public function getType()
    {
        return $this->data['type'];
    }

    public function setType($value)
    {
        $this->data['type'] = $value;
    }

    public function getCreateDate()
    {
        return $this->data['create_date'];
    }

    public function setCreateDate($value)
    {
        $this->data['create_date'] = $value;
    }

    public function getPriceSeller()
    {
        return $this->data['price_seller'];
    }

    public function setPriceSeller($value)
    {
        $this->data['price_seller'] = $value;
    }

    public function getPriceBuyer()
    {
        return $this->data['price_buyer'];
    }

    public function setPriceBuyer($value)
    {
        $this->data['price_buyer'] = $value;
    }
}
