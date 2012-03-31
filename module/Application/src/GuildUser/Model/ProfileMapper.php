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
	
}