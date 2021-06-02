<?php
class Ccc_Order_Block_Adminhtml_Order_Show_Status extends Mage_Core_Block_Template
{
    protected $order = null;

    public function setOrder(Ccc_Order_Model_Order $order)
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getStatuses()
    {
        $statuses = Mage::getResourceModel('sales/order_status_collection')
            ->toOptionHash();
        unset($statuses['fraud']);
        unset($statuses['closed']);
        unset($statuses['processing']);
        unset($statuses['payment_review']);
        unset($statuses['paypal_canceled_reversal']);
        unset($statuses['paypal_reversed']);
        unset($statuses['pending_payment']);
        unset($statuses['pending_paypal']);
        if($this->getOrder()->getStatus() == 'complete')
        {
            unset($statuses['canceled']);
            unset($statuses['holded']);
            unset($statuses['pending']);
        }
        if($this->getOrder()->getStatus() == 'canceled')
        {
            unset($statuses['complete']);
            unset($statuses['holded']);
            unset($statuses['pending']);
        }
        if($this->getOrder()->getStatus() == 'holded')
        {
            unset($statuses['pending']);
        }
        return $statuses;
    }

}
