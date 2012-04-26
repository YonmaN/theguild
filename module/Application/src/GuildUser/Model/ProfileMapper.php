<?php
namespace GuildUser\Model;

use Zend\Db\TableGateway\TableGatewayInterface;

use ZfcBase\Mapper\DbMapperAbstract;

class ProfileMapper extends DbMapperAbstract {

    
	protected $tableName = 'profile';
	protected $userIDField = 'user_id';
	/**
	 * @var \ZfcUser\Model\UserMapper
	 */
    protected $userMapper;
	/**
	 * @param integer $id
	 * @return \GuildUser\Model\Profile 
	 */
	public function findByUserId($id) {
		$rowset = $this->getTableGateway()->select('user_id = '. $id);
		$result = $rowset->toArray();
		if (0 < count($result)) {
			$profile = Profile::fromArray($result[0]);
		} else {
			$profile = new Profile();
			$profile->setNew();
		}
		$profile->setUserId($id);
		return $profile;
	}
	
	/**
	 * @param array $ids
	 * @return array
	 */
	public function findByUserIds($ids) {
		$rowset = $this->getTableGateway()->select('user_id in('. implode(',', $ids).')');
		
		$profileRows = $rowset->toArray();
		$output = array();
		foreach ($profileRows as $profileRow) {
			$output[$profileRow['user_id']] = $profileRow;
		}
		$profiles = Profile::fromArraySet($output);
		
		return $profiles;
	}
	
	public function persist(Profile $profile) {
		$data = $profile->toArray(); // or perhaps pass it by reference?
        $this->events()->trigger(__FUNCTION__ . '.pre', $this, array('data' => $data, 'profile' => $profile));
        if ($profile->isNew()) {
            $this->getTableGateway()->insert((array) $data);
        } else {
            $this->getTableGateway()->update((array) $data, array($this->userIDField => $profile->getUserId()));
        }
        return $profile;
	}
	
	public function getUserMapper() {
		return $this->userMapper;
	}
	
	public function setUserMapper($userMapper) {
		$this->userMapper = $userMapper;
		return $this;
	}
}

