<?php
namespace GuildUser\Model;

use ZfcUser\Model\UserMapper as baseUserMapper;
use Zend\Db\TableGateway\TableGatewayInterface;


class UserMapper extends baseUserMapper {
	public function findAll()
	{
		$rowset = $this->getTableGateway()->select(); /* @var $rowset \Zend\Db\ResultSet\ResultSet */
		$this->events()->trigger(__FUNCTION__ . '.post', $this, array('rowset' => $rowset));
		return $rowset->toArray();
	}
}