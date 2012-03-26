<?php
namespace Game\Model;

use Zend\Db\TableGateway\TableGatewayInterface;

use Zend\Db\Sql\Predicate\Predicate;

use Zend\Db\Sql\Where;

use ZfcBase\Mapper\DbMapperAbstract;

class GameMapper extends DbMapperAbstract {

	protected $tableName         = 'game';
	protected $gameIDField       = 'game_id';
	
	public function findByUserId($id) {
		$gateway = $this->getTableGateway(); /* @var $gateway \Zend\Db\TableGateway\TableGateway */
		// TODO protect from injection
		$gateway->getSqlSelect()->join('user_game', 'user_game.game_id = game.game_id AND user_id = ' . $id);
		$rowset = $gateway->select();
		
		$gamesArray = array();
		foreach ($rowset as $game) {
			$gamesArray[] = $game;
		}
		
		return $gamesArray;
	}
	
}