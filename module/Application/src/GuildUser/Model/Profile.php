<?php

namespace GuildUser\Model;

use ZfcUser\Model\UserInterface;
use ZfcUser\Model\UserMetaInterface;
use DateTime,
    ZfcBase\Model\ModelAbstract;

class Profile extends ModelAbstract
{
	/**
	 * @var boolean
	 */
	private $new = false;
	/**
	 * @var integer
	 */
	protected $userId;

    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var integer
     */
    protected $birthDate;

    /**
     * @var boolean
     */
    protected $visible;
    /**
     * @var boolean
     */
    protected $lfg = true;
    /**
     * @var string
     */
    protected $bio;
    /**
     * @var boolean
     */
    protected $gender;
    /**
     * @var integer
     */
    protected $humour = 0;
    /**
     * @var integer
     */
    protected $mobility = 0;
    /**
     * @var integer
     */
    protected $teamplay = 0;
    /**
     * @var integer
     */
    protected $hospitality = 0;
    /**
     * @var integer
     */
    protected $strictness = 0;

	public function isNew() {
		return $this->new;
	}
	
	public function setNew($new = true) {
		$this->new = $new;
		return $this;
	}
	
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
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return integer
	 */
	public function getBirthDate() {
		return $this->birthDate;
	}

	/**
	 * @return boolean
	 */
	public function getVisible() {
		return $this->visible;
	}

	/**
	 * @return boolean
	 */
	public function getLfg() {
		return $this->lfg;
	}

	/**
	 * @return string
	 */
	public function getBio() {
		return $this->bio;
	}

	/**
	 * @return string
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @return integer
	 */
	public function getHumour() {
		return $this->humour;
	}

	/**
	 * @return integer
	 */
	public function getMobility() {
		return $this->mobility;
	}

	/**
	 * @return integer
	 */
	public function getTeamplay() {
		return $this->teamplay;
	}

	/**
	 * @return integer
	 */
	public function getHospitality() {
		return $this->hospitality;
	}

	/**
	 * @return integer
	 */
	public function getStrictness() {
		return $this->strictness;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = ($name);
	}

	/**
	 * @param string $birthDate
	 */
	public function setBirthDate($birthDate) {
		$this->birthDate = ($birthDate);
	}

	/**
	 * @param boolean $visible
	 */
	public function setVisible($visible) {
		$this->visible = ($visible);
	}

	/**
	 * @param boolean $lfg
	 */
	public function setLfg($lfg) {
		$this->lfg = ($lfg);
	}

	/**
	 * @param string $bio
	 */
	public function setBio($bio) {
		$this->bio = ($bio);
	}

	/**
	 * @param number $gender
	 */
	public function setGender($gender) {
		$this->gender = ($gender);
	}

	/**
	 * @param number $humour
	 */
	public function setHumour($humour) {
		$this->humour = ($humour);
	}

	/**
	 * @param number $mobility
	 */
	public function setMobility($mobility) {
		$this->mobility = ($mobility);
	}

	/**
	 * @param number $teamplay
	 */
	public function setTeamplay($teamplay) {
		$this->teamplay = ($teamplay);
	}

	/**
	 * @param number $hospitality
	 */
	public function setHospitality($hospitality) {
		$this->hospitality = ($hospitality);
	}

	/**
	 * @param number $strictness
	 */
	public function setStrictness($strictness) {
		$this->strictness = ($strictness);
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
