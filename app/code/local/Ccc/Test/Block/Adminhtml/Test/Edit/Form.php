<?php 
    class Ccc_Test_Block_Adminhtml_Test_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
    {
        public function _prepareForm()
        {
            $form = new Varien_Data_Form(
                array(
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/save',array('id' => $this->getRequest()->getParam('id'))),
                    'method' => 'post'
                )
            ); 

            $form->setUseContainer(true);
            $this->setForm($form);

            $filedSet = $form->addFieldset('display', array(
                'legend' => $this->__('Test Information'),
                'class' => 'filedset'
            ));

            $filedSet->addField('first_name', 'text', array(
                'name' => 'test[first_name]',
                'label' => $this->__('First Name'),
                'required' => true,
            ));
    
            $filedSet->addField('last_name', 'text', array(
                'name' => 'test[last_name]',
                'label' => $this->__('Last Name'),
                'required' => true,
            ));


            if(Mage::registry('test_data'))
            {
                $form->setValues(Mage::registry('test_data')->getData());
            }

            return parent::_prepareForm();
        }
    }
?>