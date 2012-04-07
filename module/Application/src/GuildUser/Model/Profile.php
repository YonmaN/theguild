<?php

namespace GuildUser\Model;

use ZfcUser\Model\UserInterface;

use DateTime,
    ZfcBase\Model\ModelAbstract;

class Profile extends ModelAbstract
{
	/**
	 * @var integer
	 */
	protected $userId;

    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $birthDate;

    /**
     * @var boolean
     */
    protected $visible;
    /**
     * @var boolean
     */
    protected $lfg;
    /**
     * @var string
     */
    protected $bio;
    /**
     * @var integer
     */
    protected $gender;
    /**
     * @var integer
     */
    protected $humour;
    /**
     * @var integer
     */
    protected $mobility;
    /**
     * @var integer
     */
    protected $teamplay;
    /**
     * @var integer
     */
    protected $leadership;
    /**
     * @var integer
     */
    protected $focus;
    /**
     * @var integer
     */
    protected $hospitality;
    /**
     * @var integer
     */
    protected $strictness;
    

    /**
     * Get userId.
     *
     * @return int userId
     */
    public function getUserId()
    {
    	return $this->userId;
    }
    
    /**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $birthDate
	 */
	public function getBirthDate() {
		return $this->birthDate;
	}

	/**
	 * @return the $visible
	 */
	public function getVisible() {
		return $this->visible;
	}

	/**
	 * @return the $lfg
	 */
	public function getLfg() {
		return $this->lfg;
	}

	/**
	 * @return the $bio
	 */
	public function getBio() {
		return $this->bio;
	}

	/**
	 * @return the $gender
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @return the $humour
	 */
	public function getHumour() {
		return $this->humour;
	}

	/**
	 * @return the $mobility
	 */
	public function getMobility() {
		return $this->mobility;
	}

	/**
	 * @return the $teamplay
	 */
	public function getTeamplay() {
		return $this->teamplay;
	}

	/**
	 * @return the $leadership
	 */
	public function getLeadership() {
		return $this->leadership;
	}

	/**
	 * @return the $focus
	 */
	public function getFocus() {
		return $this->focus;
	}

	/**
	 * @return the $hospitality
	 */
	public function getHospitality() {
		return $this->hospitality;
	}

	/**
	 * @return the $strictness
	 */
	public function getStrictness() {
		return $this->strictness;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param string $birthDate
	 */
	public function setBirthDate($birthDate) {
		$this->birthDate = $birthDate;
	}

	/**
	 * @param boolean $visible
	 */
	public function setVisible($visible) {
		$this->visible = $visible;
	}

	/**
	 * @param boolean $lfg
	 */
	public function setLfg($lfg) {
		$this->lfg = $lfg;
	}

	/**
	 * @param string $bio
	 */
	public function setBio($bio) {
		$this->bio = $bio;
	}

	/**
	 * @param number $gender
	 */
	public function setGender($gender) {
		$this->gender = $gender;
	}

	/**
	 * @param number $humour
	 */
	public function setHumour($humour) {
		$this->humour = $humour;
	}

	/**
	 * @param number $mobility
	 */
	public function setMobility($mobility) {
		$this->mobility = $mobility;
	}

	/**
	 * @param number $teamplay
	 */
	public function setTeamplay($teamplay) {
		$this->teamplay = $teamplay;
	}

	/**
	 * @param number $leadership
	 */
	public function setLeadership($leadership) {
		$this->leadership = $leadership;
	}

	/**
	 * @param number $focus
	 */
	public function setFocus($focus) {
		$this->focus = $focus;
	}

	/**
	 * @param number $hospitality
	 */
	public function setHospitality($hospitality) {
		$this->hospitality = $hospitality;
	}

	/**
	 * @param number $strictness
	 */
	public function setStrictness($strictness) {
		$this->strictness = $strictness;
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
 
}
