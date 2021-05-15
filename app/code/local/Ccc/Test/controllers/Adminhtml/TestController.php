<?php 
    class Ccc_Test_Adminhtml_TestController extends Mage_Adminhtml_Controller_Action
    {
        public function indexAction()
        {
            $this->loadLayout();
            $this->_addContent($this->getLayout()->createBlock('test/adminhtml_test'));
            $this->renderLayout();
        }

        public function newAction()
        {
            $this->_forward('edit');
        }

        public function editAction()
        {
            $testId = $this->getRequest()->getParam('id');

            $testModel = Mage::getModel('test/test')->load($testId);

            if ($testId && !$testModel->getId()) {
                $this->_getSession()->addError(Mage::helper('test')->__('This test no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }

            if($testModel->getId())
            {
                Mage::register('test_data', $testModel);
            }

            $this->loadLayout();
            $this->renderLayout();

        }

        public function saveAction()
        {
            try {

                $testData = $this->getRequest()->getPost('test');

                $testId = $this->getRequest()->getParam('id');
    
                $test = Mage::getModel('test/test');
    
                if ($testId) {
    
                    if (!$test->load($testId)) {
                        throw new Exception("No Row Found");
                    }
    
                }
                
    
                $test->addData($testData);
                
                $test->save();
                Mage::getSingleton('core/session')->addSuccess("test data added.");
                $this->_redirect('*/*/');
    
            } catch (Exception $e) {
                Mage::getSingleton('core/session')->addError($e->getMessage());
                $this->_redirect('*/*/');
            }

        }

        public function deleteAction()
        {
            try {

                $testModel = Mage::getModel('test/test');

                if (!($testId = (int) $this->getRequest()->getParam('id')))
                    throw new Exception('Id not found');

                if (!$testModel->load($testId)) {
                    throw new Exception('test does not exist');
                }

                if (!$testModel->delete()) {
                    throw new Exception('Error in delete record', 1);
                }

                Mage::getSingleton('core/session')->addSuccess($this->__('The test has been deleted.'));

            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('core/session')->addError($e->getMessage());
            }
            
            $this->_redirect('*/*/');
        }
    }
?>