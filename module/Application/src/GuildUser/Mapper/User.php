<?php
namespace GuildUser\Mapper;
use ZfcUser\Mapper\User as baseUserMapper;

class User extends baseUserMapper {

    /**
     * @return \Zend\Db\ResultSet\HydratingResultSet
     */
    public function findAll()
	{
            $select = $this->getSelect()
                       ->from($this->tableName)
                        ->limit(10);

        $entities = $this->select($select);
        $this->getEventManager()->trigger('find', $this, $entities);
        return $entities;
	}

        /**
         * @param \ZfcUser\Mapper\User $baseUserMapper
         * @return \self
         */
        public static function fromZfcUser(baseUserMapper $baseUserMapper) {
            $userMapper = new self();
            $userMapper->setDbAdapter($baseUserMapper->getDbAdapter());
            $userMapper->setEntityPrototype($baseUserMapper->getEntityPrototype());
            $userMapper->setEventManager($baseUserMapper->getEventManager());
            $userMapper->setHydrator($baseUserMapper->getHydrator());
            return $userMapper;
        }
        
}