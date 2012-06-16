<?php
namespace GuildUser\Model;

use Zend\Db\TableGateway\TableGatewayInterface;

use Zend\Db\Sql\Predicate\Predicate;

use Zend\Db\Sql\Where;

use ZfcBase\Mapper\DbMapperAbstract;

class GameMapper extends DbMapperAbstract {

	protected $tableName         = 'user_game';
	protected $gameIDField       = 'game_id';
	
	public function findByUserId($id) {
		$gateway = $this->getTableGateway(); /* @var $gateway \Zend\Db\TableGateway\TableGateway */
		// TODO protect from injection
		$select = new \Zend\Db\Sql\Select($gateway->getTable());
		$select->join('user_game', "user_game.game_id = game.game_id AND user_id = {$id} AND user_game.enabled = 1");
		$rowset = $gateway->select($select)->toArray();
		
		$gamesArray = array();
		foreach ($rowset as $game) {
			$gamesArray[$game['game_id']] = $game;
		}
		
		return $gamesArray;
	}
	
	public function findAllGames() {
		$games = $this->getTableGateway()->select()->toArray();
		return $games;
	}
	
	/**
	 * @param integer $userId
	 * @param integer $gameId
	 * @return \GuildUser\Model\UserGame
	 */
	public function findUserGame($userId, $gameId) {
		$gameArray = current($this->getTableGateway()->select("user_id = {$userId} AND game_id = {$gameId}")->toArray());
		if (! $gameArray) {
			return new UserGame();
		}
		return UserGame::fromArray($gameArray);
	}
	
	public function persist(UserGame $userGame)
    {
		$enabled = intval($userGame->isEnabled());
        $stmt = $this->getTableGateway()->getAdapter()->query("REPLACE INTO user_game (user_id, game_id, comments, xp, gm, learn, enabled)
			VALUES({$userGame->getUserId()},{$userGame->getGameId()},'{$userGame->getComments()}', {$userGame->getXp()},{$userGame->getGm()},
			{$userGame->getLearn()},{$enabled})");
		$stmt->execute();
        return $userGame;
    }
	
}