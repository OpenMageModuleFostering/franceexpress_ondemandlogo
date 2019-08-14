<?php

class Franceexpress_Ondemandlogo_Model_Observer {

    /**
     * 
     * @param type $evt
     */
    public function saveShippingMethodFranceExpress($evt) {
        $request = $evt->getRequest();
        $quote = $evt->getQuote();
        $params = $request->getParams();
        $shippingMethode = $params['shipping_method'];
        $franceExpformInformations = $params[$shippingMethode];
        $franceExpformInformations['shipping_methode'] = $shippingMethode;
        $quote_id = $quote->getId();
        $data = array($quote_id => $franceExpformInformations);
        $data['shipping_methode'] = $shippingMethode;
        if ($franceExpformInformations && strpos($franceExpformInformations['shipping_methode'], 'franceexpress_ondemandlogo') !== false) {
            Mage::getSingleton('checkout/session')->setFormInformation($data);
        }
    }

    /**
     * 
     * @param type $evt
     */
    public function saveOrderAfterFranceExpress($evt) {
        $order = $evt->getOrder();
        $quote = $evt->getQuote();
        $quote_id = $quote->getId();
        $franceExpformInformations = Mage::getSingleton('checkout/session')->getFormInformation();
        if (strpos($franceExpformInformations['shipping_methode'], 'franceexpress_ondemandlogo') !== false) {
            if (isset($franceExpformInformations[$quote_id])) {
                $data = $franceExpformInformations[$quote_id];
                $data['order_id'] = $order->getId();
                $franceExpCollection = Mage::getModel('franceexpress_ondemandlogo/frshippment')
                        ->setData('order_id', $order->getId())
                        ->setData('store', Mage::app()->getStore()->getStoreId());
                if (isset($franceExpformInformations[$quote_id]['email'])) {
                    $franceExpCollection->setData('email', $franceExpformInformations[$quote_id]['email']);
                }
                if (isset($franceExpformInformations[$quote_id]['phone'])) {
                    $franceExpCollection->setData('phone', $franceExpformInformations[$quote_id]['phone']);
                }
                if (isset($franceExpformInformations[$quote_id]['mobile'])) {
                    $franceExpCollection->setData('mobile', $franceExpformInformations[$quote_id]['mobile']);
                }
                $franceExpCollection->save();
            }
        }
    }

    /**
     * 
     * @param type $evt
     */
    public function loadOrderAfterFranceExpress($evt) {
        $franceExpformInformations = Mage::getSingleton('checkout/session')->getFormInformation();
        if (strpos($franceExpformInformations['shipping_methode'], 'franceexpress_ondemandlogo') !== false) {
            $order = $evt->getOrder();
            if ($order->getId()) {
                $order_id = $order->getId();
                $shippingCollection = Mage::getModel('franceexpress_ondemandlogo/frshippment')->getCollection();
                $shippingCollection->addFieldToFilter('order_id', $order_id);
                $userInformation = $shippingCollection->getFirstItem();
                $order->setGeodisObjectFranceExpress($userInformation);
            }
        }
    }

    /**
     * 
     * @param type $observer
     */
    public function salesOrderGridCollectionLoadBefore($observer) {
        if (!Mage::helper('core')->isModuleEnabled('Geodis_Ondemandlogo')) {
            $collection = $observer->getOrderGridCollection();
            $select = $collection->getSelect();
            $select->join('sales_flat_order', 'main_table.entity_id = sales_flat_order.entity_id', array('shipping_description'));
        }
    }

}
