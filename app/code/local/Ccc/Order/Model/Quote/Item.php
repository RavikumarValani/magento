<?php
class Ccc_Order_Model_Quote_Item extends Mage_Core_Model_Abstract
{
    protected $cart;
    protected $product;

    protected function _construct()
    {
        $this->_init('order/quote_item');
    }

    public function setQuote(Ccc_Order_Model_Quote $quote)
    {
        $this->quote = $quote;
        return $this;
    }

    public function getQuote()
    {
        return $this->quote;
    }

    public function setProduct(Mage_Catalog_Model_Product $product)
    {
        $this->product = $product;
        return $this;
    }

    public function getProduct()
    {
        if(!$this->getProductId())
        {
            return false;
        }
        $product = Mage::getModel('catalog/product')->load($this->getProductId());
        $this->setProduct($product);
        return $this->product;
    }

}
