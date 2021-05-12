<?php

class Ccc_Compile_Block_Adminhtml_Compile_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{


    public function __construct()
    {
      parent::__construct();
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('compile')->__('Compile Information'));
    }
    public function getCompile()
    {
        return Mage::registry('current_compile');
    }

    protected function _beforeToHtml()
    {

        $compileAttributes = Mage::getResourceModel('compile/compile_attribute_collection');

        if (!$this->getCompile()->getId()) {
            foreach ($compileAttributes as $attribute) {
                $default = $attribute->getDefaultValue();
                if ($default != '') {
                    $this->getCompile()->setData($attribute->getAttributeCode(), $default);
                }
            }
        }

        $attributeSetId = $this->getCompile()->getResource()->getEntityType()->getDefaultAttributeSetId();



        // $attributeSetId = 21;
        
        $groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
            ->setAttributeSetFilter($attributeSetId)
            ->setSortOrder()
            ->load();

        $defaultGroupId = 0;
        foreach ($groupCollection as $group) {
            if ($defaultGroupId == 0 or $group->getIsDefault()) {
                $defaultGroupId = $group->getId();
            }

        }	


        foreach ($groupCollection as $group) {
            $attributes = array();
            foreach ($compileAttributes as $attribute) {
                if ($this->getCompile()->checkInGroup($attribute->getId(),$attributeSetId, $group->getId())) {
                    $attributes[] = $attribute;
                }
            }

            if (!$attributes) {
                continue;
            }
            $active = $defaultGroupId == $group->getId();
            $block = $this->getLayout()->createBlock('compile/adminhtml_compile_edit_tab_attributes')
                ->setGroup($group)
                ->setAttributes($attributes)
                ->setAddHiddenFields($active)
                ->toHtml();
            $this->addTab('group_' . $group->getId(), array(
                'label'     => Mage::helper('compile')->__($group->getAttributeGroupName()),
                'content'   => $block,
                'active'    => $active
            ));
        }
      return parent::_beforeToHtml();
    }
}
