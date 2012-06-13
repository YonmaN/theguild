<?php
namespace GuildUser\Model;

use ZfcUser\Model\User;
use ZfcUser\Module;

use ZfcUser\Model\UserMapper as baseUserMapper;
use Zend\Db\TableGateway\TableGatewayInterface;


class UserMapper extends baseUserMapper {
	public function findAll()
	{
		$rowset = $this->getTableGateway()->select(); /* @var $rowset \Zend\Db\ResultSet\ResultSet */
		$this->events()->trigger(__FUNCTION__ . '.post', $this, array('rowset' => $rowset));
		$rows = $rowset->toArray();
		$userModelClass = Module::getOption('user_model_class');
		$users = $userModelClass::fromArraySet($rows);
		return $users;
	}
}