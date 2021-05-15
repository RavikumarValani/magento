<?php
class Ccc_Compile_Model_Resource_Compile_Collection extends Mage_Catalog_Model_Resource_Collection_Abstract
{
	public function __construct()
	{
		$this->setEntity('compile');
		parent::__construct();
		
	}
}