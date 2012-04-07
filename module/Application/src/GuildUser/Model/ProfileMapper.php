<?php
namespace GuildUser\Model;

use ZfcUser\Model\UserMetaMapper;

use Zend\Db\TableGateway\TableGatewayInterface;

use ZfcBase\Mapper\DbMapperAbstract;

class ProfileMapper extends UserMetaMapper {

	public function findByUserId($id) {
		$rowset = $this->getTableGateway()->select('user_id = '. $id);
		$profileRows = $rowset->toArray();
		$output = array();
		foreach ($profileRows as $profileRow) {
			$output[$profileRow['meta_key']] = $profileRow['meta']; 
		}
		$profile = Profile::fromArray($output);
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
			$output[$profileRow['user_id']][$profileRow['meta_key']] = $profileRow['meta'];
		}
		$profiles = Profile::fromArraySet($output);
		
		return $profiles;
	}
	
}