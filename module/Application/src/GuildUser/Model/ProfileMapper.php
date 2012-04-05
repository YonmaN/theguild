<?php
namespace GuildUser\Model;

use Zend\Db\TableGateway\TableGatewayInterface;

use ZfcBase\Mapper\DbMapperAbstract;

class ProfileMapper extends DbMapperAbstract {

	protected $tableName         = 'profile';
	protected $userIDField       = 'user_id';
	
	public function findByUserId($id) {
		$rowset = $this->getTableGateway()->select('user_id = '. $id);
		return $rowset->current()->getArrayCopy();
	}
	
	/**
	 * @param array $ids
	 * @return array
	 */
	public function findByUserIds($ids) {
		$rowset = $this->getTableGateway()->select('user_id in('. implode(',', $ids).')');
		$data = $rowset->toArray();
		return array_combine(array_map(function ($value) {return $value['user_id'];}, $data), array_values($data));
	}
	
}