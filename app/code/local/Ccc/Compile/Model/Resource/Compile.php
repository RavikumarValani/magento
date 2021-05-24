<?php
class Ccc_Compile_Model_Resource_Compile extends Mage_Eav_Model_Entity_Abstract
{

	const ENTITY = 'compile';
	
	public function __construct()
	{

		$this->setType(self::ENTITY)
			 ->setConnection('core_read', 'core_write');

	   parent::__construct();
    }

}