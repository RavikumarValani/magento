<?php

class Ccc_Compile_Adminhtml_CompileController extends Mage_Adminhtml_Controller_Action
{

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('compile/compile');
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('compile');
        $this->_title('Compile Grid');

        $this->_addContent($this->getLayout()->createBlock('compile/adminhtml_compile'));

        $this->renderLayout();
    }

    protected function _initCompile()
    {
        $this->_title($this->__('compile'))
            ->_title($this->__('Manage Compile'));

        $compileId = (int) $this->getRequest()->getParam('id');
        $compile   = Mage::getModel('compile/compile')
            ->setStoreId($this->getRequest()->getParam('store', 0))
            ->load($compileId);

        Mage::register('current_compile', $compile);
        Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $compile;
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $compileId = (int) $this->getRequest()->getParam('id');
        $compile   = $this->_initCompile();

        if ($compileId && !$compile->getId()) {
            $this->_getSession()->addError(Mage::helper('compile')->__('This Compile no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        $this->_title($compile->getName());

        $this->loadLayout();

        $this->_setActiveMenu('compile/compile');

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->renderLayout();

    }

    public function saveAction()
    {

        try {

            $compileData = $this->getRequest()->getPost('account');

            $compile = Mage::getSingleton('compile/compile');

            if ($compileId = $this->getRequest()->getParam('id')) {

                if (!$compile->load($compileId)) {
                    throw new Exception("No Row Found");
                }
                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

            }

            $compile->addData($compileData);

            $compile->save();

            Mage::getSingleton('core/session')->addSuccess("Compile data added.");
            $this->_redirect('*/*/');

        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirect('*/*/');
        }

    }

    public function deleteAction()
    {
        try {

            $compileModel = Mage::getModel('compile/compile');

            if (!($compileId = (int) $this->getRequest()->getParam('id')))
                throw new Exception('Id not found');

            if (!$compileModel->load($compileId)) {
                throw new Exception('Compile does not exist');
            }

            if (!$compileModel->delete()) {
                throw new Exception('Error in delete record', 1);
            }

            Mage::getSingleton('core/session')->addSuccess($this->__('The Compile has been deleted.'));

        } catch (Exception $e) {
            Mage::logException($e);
            $Mage::getSingleton('core/session')->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/');
    }
}
