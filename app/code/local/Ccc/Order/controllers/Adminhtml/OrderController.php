<?php
class Ccc_Order_Adminhtml_OrderController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('order');
        $this->renderLayout();
    }
    protected function _initSession($orderId)
    {
        $session = $this->_getSession();
        $session->setOrderId($orderId);
        $this->_redirect('*/*/show');
    }

    public function startAction()
    {
        try {
            $orderId = $this->getRequest()->getParam('order_id');
            if(!$orderId)
            {
                throw new Exception("Order not Exist.");
            }
            $this->_initSession($orderId);
        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*/index');
        }
    }

    public function showAction()
    {
        try {
            $this->loadLayout();
            $this->getLayout()->getBlock('show')->setOrder($this->getOrder());
            $this->renderLayout();

        }
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*/index');
        }
    }

    protected function getOrder()
    {
        $order = Mage::getModel('order/order');
        $orderId = $this->_getSession()->getOrderId();
        $order->load($orderId);
        return $order;
    }



    public function saveAction()
    {
        $orderId = $this->getRequest()->getParam('id');
        $orderData = $this->getRequest()->getPost('order');
        $orderModel = Mage::getModel('order/order');
        $orderModel->setData($orderData);
        if ($orderId) {
            $orderModel->setId($orderId);
        }
        $orderModel->save();
        Mage::getSingleton('core/session')->addSuccess('Data Save Successfully...');
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        $orderId = $this->getRequest()->getParam('id');
        $orderModel = Mage::getModel('order/data');
        $orderModel->setDataId($orderId)->delete();
        Mage::getSingleton('core/session')->addSuccess('Data Delete Successfully...');
        $this->_redirect('*/*/');
    }

    protected function _initOrder()
    {
        $id = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('order/order')->load($id);

        if (!$order->getId()) {
            $this->_getSession()->addError($this->__('This order no longer exists.'));
            $this->_redirect('*/*/');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            return false;
        }
        Mage::register('order', $order);
        Mage::register('current_order', $order);
        return $order;
    }

    protected function _getSession()
    {
        return Mage::getSingleton('order/session_quote');
    }

    public function cancelAction()
       {
           $order = $this->getOrder();
           $order->delete();
           $this->_redirect('*/*/');
       }

}
