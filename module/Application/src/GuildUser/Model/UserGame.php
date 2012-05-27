<?php

namespace GuildUser\Model;

use ZfcUser\Model\UserInterface;
use ZfcUser\Model\UserMetaInterface;
use DateTime,
    ZfcBase\Model\ModelAbstract;

class UserGame extends ModelAbstract
{
	/**
	 * @var integer
	 */
	protected $userId;
	
	/**
	 * @var integer
	 */
	protected $gameId;
	
	/**
	 * @var string
	 */
	protected $comments = '';
	/**
	 * @var integer
	 */
	protected $xp = 0;
	/**
	 * @var integer
	 */
	protected $gm = 0;
	/**
	 * @var integer
	 */
	protected $learn = 0;
	/**
	 * @var integer
	 */
	protected $enabled;
	
	
    /**
     * Get userId.
     *
     * @return integer
     */
    public function getUserId()
    {
    	return $this->userId;
    }
 
    /**
     * Set userId.
     *
     * @param int $userId the value to be set
     * @return UserBase
     */
    public function setUserId($userId)
    {
        $this->userId = (int) $userId;
        return $this;
    }
	
    /**
     * @return integer
     */
    public function getGameId()
    {
    	return $this->gameId;
    }
 
    /**
     * @param int $userId the value to be set
     * @return UserBase
     */
    public function setGameId($gameId)
    {
        $this->gameId = (int) $gameId;
        return $this;
    }
    /**
     * @return integer
     */
    public function getComments()
    {
    	return $this->comments;
    }
 
    /**
     * @param $comments
     * @return UserBase
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }
    /**
     * @return integer
     */
    public function getXp()
    {
    	return $this->xp;
    }
 
    /**
     * @param $comments
     * @return UserBase
     */
    public function setXp($xp)
    {
        $this->xp = $xp;
        return $this;
    }
    /**
     * @return integer
     */
    public function getGm()
    {
    	return $this->gm;
    }
 
    /**
     * @param $gm
     * @return UserBase
     */
    public function setGm($gm)
    {
        $this->gm = $gm;
        return $this;
    }
    /**
     * @return integer
     */
    public function getLearn()
    {
    	return $this->learn;
    }
 
    /**
     * @param $learn
     * @return UserBase
     */
    public function setLearn($learn)
    {
        $this->learn = $learn;
        return $this;
    }
    /**
     * @return boolean
     */
    public function isEnabled()
    {
    	return $this->enabled;
	}
    /**
     * @param $learn
     * @return UserBase
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (boolean)$enabled;
        return $this;
    }
 
	
	/**
     * Set properties from an array
     *
     * @param array $array
     * @return ZfcBase\Model\ModelAbstract
     */
    public function setFromArray($array)
    {
        if (!is_array($array) && !$array instanceof Traversable) {
            return false;
        } 
        foreach ($array as $key => $value) {
            $setter = static::fieldToSetterMethod($key);
            if (is_callable(array($this, $setter))) {
                $this->$setter($value);
            }
        }
        return $this;
    }
}
