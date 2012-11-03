<?php
namespace GuildUser\Mapper;

use Zend\Db\TableGateway\TableGatewayInterface;

use Zend\Db\Sql\Predicate\Predicate;

use Zend\Db\Sql\Where;


class Game extends \ZfcBase\Mapper\AbstractDbMapper  {

	protected $tableName         = 'user_game';
	
	public function findByUserId($id) {
            
             $select = $this->getSelect()
                       ->from($this->tableName)
                     ->join('game', "user_game.game_id = game.game_id AND user_id = {$id} AND user_game.enabled = 1");

            $games = $this->select($select);
            $this->getEventManager()->trigger('find', $this, array('games' => $games));
            return $games;
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