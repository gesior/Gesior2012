<?php
if (!defined('INITIALIZED'))
    exit;

class Player extends ObjectData
{
    const LOADTYPE_ID = 'player_id';
    const LOADTYPE_NAME = 'charname';
    const LOADTYPE_ACCOUNT_ID = 'account_id';

    public static $table = 'players';
    public $data = array('lastlogin' => 0, 'online' => 0, 'vocation' => 'none', 'hideprofile' => 0, 'playerdelete' => 0, 'level' => 1, 'residence' => 'Rookgaard', 'charIP' => '');
    public static $fields = array('player_id', 'charname', 'account_id', 'account_nr', 'creation', 'lastlogin',
        'gender', 'online', 'vocation', 'hideprofile', 'playerdelete', 'level', 'residence', 'oldname', 'comment', 'charIP',
        'create_ip', 'create_date');
    public $account;

    public function __construct($search_text = null, $search_by = self::LOADTYPE_ID)
    {
        if ($search_text != null)
            $this->load($search_text, $search_by);
    }

    public function load($search_text, $search_by = self::LOADTYPE_ID)
    {
        if (in_array($search_by, self::$fields))
            $search_string = $this->getDatabaseHandler()->fieldName($search_by) . ' = ' . $this->getDatabaseHandler()->quote($search_text);
        else
            new Error_Critic('', 'Wrong Player search_by type.');
        $fieldsArray = array();
        foreach (self::$fields as $fieldName)
            $fieldsArray[] = $this->getDatabaseHandler()->fieldName($fieldName);

        $this->data = $this->getDatabaseHandler()->query('SELECT ' . implode(', ', $fieldsArray) . ' FROM ' . $this->getDatabaseHandler()->tableName(self::$table) . ' WHERE ' . $search_string)->fetch();
    }

    public function loadById($id)
    {
        $this->load($id, self::LOADTYPE_ID);
    }

    public function loadByName($name)
    {
        $this->load($name, self::LOADTYPE_NAME);
    }

    public function isLoaded()
    {
        return isset($this->data['player_id']) && $this->data['player_id'] > 0;
    }

    public function save($forceInsert = false)
    {
        if (!isset($this->data['player_id']) || $forceInsert) {
            $keys = array();
            $values = array();
            foreach (self::$fields as $key)
                if ($key != 'player_id') {
                    $keys[] = $this->getDatabaseHandler()->fieldName($key);
                    $values[] = $this->getDatabaseHandler()->quote($this->data[$key]);
                }
            $this->getDatabaseHandler()->query('INSERT INTO ' . $this->getDatabaseHandler()->tableName(self::$table) . ' (' . implode(', ', $keys) . ') VALUES (' . implode(', ', $values) . ')');
            $this->setID($this->getDatabaseHandler()->lastInsertId());
        } else {
            $updates = array();
            foreach (self::$fields as $key)
                $updates[] = $this->getDatabaseHandler()->fieldName($key) . ' = ' . $this->getDatabaseHandler()->quote($this->data[$key]);
            $this->getDatabaseHandler()->query('UPDATE ' . $this->getDatabaseHandler()->tableName(self::$table) . ' SET ' . implode(', ', $updates) .
                ' WHERE ' . $this->getDatabaseHandler()->fieldName('player_id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['player_id']));
        }
    }

    public function loadAccount()
    {
        $this->account = new Account($this->getAccountID());
    }

    public function getAccount($forceReload = false)
    {
        if (!isset($this->account) || $forceReload)
            $this->loadAccount();

        return $this->account;
    }

    public function setAccount(Account $account)
    {
        $this->account = $account;
        $this->setAccountID($account->getID());
        $this->setAccountNr($account->getLogin());
    }

    public function getDelete()
    {
        return $this->isDeleted();
    }

    public function isDeleted()
    {
        return $this->data['playerdelete'] > 0 && $this->data['playerdelete'] <= time();
    }

    public function delete()
    {
        $this->data['playerdelete'] = time();
    }

    public function undelete()
    {
        $this->data['playerdelete'] = 0;
    }

    /*
     * default rlots fields
    */
    public function setID($value)
    {
        $this->data['player_id'] = $value;
    }

    public function getID()
    {
        return $this->data['player_id'];
    }

    public function setAccountID($value)
    {
        $this->data['account_id'] = $value;
    }

    public function getAccountID()
    {
        return $this->data['account_id'];
    }

    public function setAccountNr($value)
    {
        $this->data['account_nr'] = $value;
    }

    public function getAccountNr()
    {
        return $this->data['account_nr'];
    }

    public function setName($value)
    {
        $this->data['charname'] = $value;
    }

    public function getName()
    {
        return $this->data['charname'];
    }

    public function setVocation($value)
    {
        $this->data['vocation'] = $value;
    }

    public function getVocation()
    {
        return $this->data['vocation'];
    }

    public function setLevel($value)
    {
        $this->data['level'] = $value;
    }

    public function getLevel()
    {
        return $this->data['level'];
    }

    public function setSex($value)
    {
        $this->data['gender'] = $value;
    }

    public function getSex()
    {
        return $this->data['gender'];
    }

    public function setLastLogin($value)
    {
        $this->data['lastlogin'] = $value;
    }

    public function getLastLogin()
    {
        return $this->data['lastlogin'];
    }

    public function setTown($value)
    {
        $this->data['residence'] = $value;
    }

    public function getTown()
    {
        return $this->data['residence'];
    }

    // TODO: load data from 'usr' file
    /*
        public function getSkill($id)
        {
            if (isset(self::$skillNames[$id]))
                return $this->data['skill_' . self::$skillNames[$id]];
            else
                new Error_Critic('', 'Player::getSkill() - Skill ' . htmlspecialchars($id) . ' does not exist');
        }

        public function setSkill($id, $value)
        {
            if (isset(self::$skillNames[$id]))
                $this->data['skill_' . self::$skillNames[$id]] = $value;
        }

        public function getSkillCount($id)
        {
            if (isset(self::$skillNames[$id]))
                return $this->data['skill_' . self::$skillNames[$id] . '_tries'];
            else
                new Error_Critic('', 'Player::getSkillCount() - Skill ' . htmlspecialchars($id) . ' does not exist');
        }

        public function setSkillCount($id, $count)
        {
            if (isset(self::$skillNames[$id]))
                $this->data['skill_' . self::$skillNames[$id] . '_tries'] = $value;
        }

        public function setSkull($value)
        {
            $this->data['skull'] = $value;
        }

        public function setLastIP($value)
        {
            $this->data['lastip'] = $value;
        }

        public function getLastIP()
        {
            return $this->data['lastip'];
        }

        public function getSkull()
        {
            return $this->data['skull'];
        }

        public function setSkullTime($value)
        {
            $this->data['skulltime'] = $value;
        }

        public function getSkullTime()
        {
            return $this->data['skulltime'];
        }

        public function setExperience($value)
        {
            $this->data['experience'] = $value;
        }

        public function getExperience()
        {
            return $this->data['experience'];
        }

        public function setHealth($value)
        {
            $this->data['health'] = $value;
        }

        public function getHealth()
        {
            return $this->data['health'];
        }

        public function setHealthMax($value)
        {
            $this->data['healthmax'] = $value;
        }

        public function getHealthMax()
        {
            return $this->data['healthmax'];
        }

        public function setMana($value)
        {
            $this->data['mana'] = $value;
        }

        public function getMana()
        {
            return $this->data['mana'];
        }

        public function setManaMax($value)
        {
            $this->data['manamax'] = $value;
        }

        public function getManaMax()
        {
            return $this->data['manamax'];
        }

        public function setMagLevel($value)
        {
            $this->data['maglevel'] = $value;
        }

        public function getMagLevel()
        {
            return $this->data['maglevel'];
        }

        public function setManaSpent($value)
        {
            $this->data['manaspent'] = $value;
        }

        public function getManaSpent()
        {
            return $this->data['manaspent'];
        }

        public function setPosX($value)
        {
            $this->data['posx'] = $value;
        }

        public function getPosX()
        {
            return $this->data['posx'];
        }

        public function setPosY($value)
        {
            $this->data['posy'] = $value;
        }

        public function getPosY()
        {
            return $this->data['posy'];
        }

        public function setPosZ($value)
        {
            $this->data['posz'] = $value;
        }

        public function getPosZ()
        {
            return $this->data['posz'];
        }

        public function setCapacity($value)
        {
            $this->data['cap'] = $value;
        }

        public function getCapacity()
        {
            return $this->data['cap'];
        }

        public function setSoul($value)
        {
            $this->data['soul'] = $value;
        }

        public function getSoul()
        {
            return $this->data['soul'];
        }

        public function setLookBody($value)
        {
            $this->data['lookbody'] = $value;
        }

        public function getLookBody()
        {
            return $this->data['lookbody'];
        }

        public function setLookFeet($value)
        {
            $this->data['lookfeet'] = $value;
        }

        public function getLookFeet()
        {
            return $this->data['lookfeet'];
        }

        public function setLookHead($value)
        {
            $this->data['lookhead'] = $value;
        }

        public function getLookHead()
        {
            return $this->data['lookhead'];
        }

        public function setLookLegs($value)
        {
            $this->data['looklegs'] = $value;
        }

        public function getLookLegs()
        {
            return $this->data['looklegs'];
        }

        public function setLookType($value)
        {
            $this->data['looktype'] = $value;
        }

        public function getLookType()
        {
            return $this->data['looktype'];
        }

        public function setLookAddons($value)
        {
            $this->data['lookaddons'] = $value;
        }

        public function getLookAddons()
        {
            return $this->data['lookaddons'];
        }
    */
    /*
     * Custom AAC fields
     * create_ip , INT, default 0
     * comment , TEXT, default ''
    */
    public function setCreateIP($value)
    {
        $this->data['create_ip'] = $value;
    }

    public function getCreateIP()
    {
        return $this->data['create_ip'];
    }

    public function setCreateDate($value)
    {
        $this->data['creation'] = $value;
    }

    public function getCreateDate()
    {
        return $this->data['creation'];
    }

    public function setHidden($value)
    {
        $this->data['hideprofile'] = (int)$value;
    }

    public function isHidden()
    {
        return (bool)$this->data['hideprofile'];
    }

    public function setComment($value)
    {
        $this->data['comment'] = $value;
    }

    public function getComment()
    {
        return $this->data['comment'];
    }

    /*
     * for compability with old scripts
    */

    public function getCreated()
    {
        return $this->getCreateDate();
    }

    public function setCreated($value)
    {
        $this->setCreateDate($value);
    }

    public function getHideChar()
    {
        return $this->isHidden();
    }

    public function find($name)
    {
        $this->loadByName($name);
    }

    public function isOnline()
    {
        return $this->data['online'];
    }

    public function getOnline()
    {
        return $this->isOnline();
    }
}
