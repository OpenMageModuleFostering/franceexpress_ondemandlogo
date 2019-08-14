<?php

class Franceexpress_Ondemandlogo_Block_Sales_Order_View_Tab_Info extends Mage_Adminhtml_Block_Sales_Order_View_Tab_Info {

    /**
     * 
     * get france express shipping information
     */
    public function getFranceExpressShippingInformations() {
        $order_id = $this->getOrder()->getId();
        $shippingCollection = Mage::getModel('franceexpress_ondemandlogo/frshippment')->getCollection()->addFieldToFilter('order_id', $order_id);
        return $shippingCollection->getFirstItem();
    }

}
