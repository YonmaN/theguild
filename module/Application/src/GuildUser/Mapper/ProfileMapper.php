<?php
namespace GuildUser\Mapper;

class ProfileMapper  extends \ZfcBase\Mapper\AbstractDbMapper  {
    protected $tableName  = 'profile';
    
    public function findAll() {
        $select = $this->getSelect()
                       ->from($this->tableName)
                        ->join('user', 'profile.user_id = user.user_id', 'display_name');

        $profiles = $this->select($select);
        $this->getEventManager()->trigger('find', $this, array('profiles' => $profiles));
        return $profiles;
    }
    
	/**
	 * @param integer $id
	 * @return \GuildUser\Model\Profile 
	 */
	public function findByUserId($id) {
            $select = $this->getSelect()
                       ->from($this->tableName)
                       ->where(array('user_id' => $id));

        $profile = $this->select($select)->current();
        $this->getEventManager()->trigger('find', $this, array('profile' => $profile));
        return $profile;
            
	}
	
	/**
	 * @param array $ids
	 * @return array
	 */
	public function findByUserIds($ids) {
		$select = $this->getSelect()
                       ->from($this->tableName)
                       ->where(array('user_id' => $id));

            $profiles = $this->select($select);
            $this->getEventManager()->trigger('find', $this, $profiles);
            return $profiles;
	}
//	
//	public function persist(Profile $profile) {
//		$data = $profile->toArray(); // or perhaps pass it by reference?
//        if ($profile->isNew()) {
//            $this->getTableGateway()->insert((array) $data);
//        } else {
//            $this->getTableGateway()->update((array) $data, array($this->userIDField => $profile->getUserId()));
//        }
//        return $profile;
//	}
//	
}

