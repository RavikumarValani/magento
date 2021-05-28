<?php
class Ccc_Order_Adminhtml_OrderController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('order');
        $this->renderLayout();
    }
    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $orderId = $this->getRequest()->getParam('id');

        $orderModel = Mage::getModel('order/order')->load($orderId);
        if ($orderModel->getId()) {
            Mage::register('order_data', $orderModel);
        }
        $this->loadLayout();
        $this->renderLayout();
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
        if ($order = $this->_initOrder()) {
            try {
                $order->cancel()
                    ->save();
                $this->_getSession()->addSuccess(
                    $this->__('The order has been cancelled.')
                );
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addError($this->__('The order has not been cancelled.'));
                Mage::logException($e);
            }
            $this->_redirect('*/*/index', array('order_id' => $order->getId()));
        }
    }

}
