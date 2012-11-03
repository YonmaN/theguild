<?php

namespace GuildUser\Mapper;

class UserGame
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
         * @var string
         */
        protected $name;
	
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
     * @return UserGame
     */
    public function setUserId($userId)
    {
        $this->userId = (int) $userId;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getGameName()
    {
    	return $this->name;
    }
 
    /**
     * Set userId.
     *
     * @param string $userId the value to be set
     * @return UserGame
     */
    public function setGameName($name)
    {
        $this->name = $name;
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
     * @return UserGame
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
     * @return UserGame
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
     * @return UserGame
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
     * @return UserGame
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
     * @return UserGame
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
     * @return UserGame
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (boolean)$enabled;
        return $this;
    }
 
}
