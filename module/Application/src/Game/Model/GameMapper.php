<?php
namespace Game\Model;

use Zend\Db\TableGateway\TableGatewayInterface;

use Zend\Db\Sql\Predicate\Predicate;

use Zend\Db\Sql\Where;

use ZfcBase\Mapper\AbstractDbMapper as DbMapperAbstract;

class GameMapper extends DbMapperAbstract {

	protected $tableName         = 'game';
	protected $gameIDField       = 'game_id';
	
	public function findAllGames() {
		$games = $this->getTableGateway()->select()->toArray();
		return $games;
	}
	
}