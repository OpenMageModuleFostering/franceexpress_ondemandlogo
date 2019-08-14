<?php

class Franceexpress_Ondemandlogo_Helper_Data extends Mage_Core_Helper_Abstract {

    const FRANCEEXPRESS_METHOD_1 = 'geodis_od_expert';
    const FRANCEEXPRESS_METHOD_2 = 'geodis_od_premium';
    const FRANCEEXPRESS_METHOD_1_NAME = 'FRANCE EXPRESS ON DEMAND premium';
    const FRANCEEXPRESS_METHOD_2_NAME = 'FRANCE EXPRESS ON DEMAND live';
    //en kg
    const FRANCEEXPRESS_POIDS_MAX = 1000;
    const XML_FRANCEEXPRESS_METHOD_1_ACTIVE = 'carriers/franceexpress_methods/method_1_active';
    const XML_FRANCEEXPRESS_METHOD_1_DESCRIPTION = 'carriers/franceexpress_methods/method_1_description';
    const XML_FRANCEEXPRESS_METHOD_1_PRICE = 'carriers/franceexpress_methods/method_1_price';
    const XML_FRANCEEXPRESS_METHOD_2_ACTIVE = 'carriers/franceexpress_methods/method_2_active';
    const XML_FRANCEEXPRESS_METHOD_2_DESCRIPTION = 'carriers/franceexpress_methods/method_2_description';
    const XML_FRANCEEXPRESS_METHOD_2_PRICE = 'carriers/franceexpress_methods/method_2_price';

    protected $geodisDestinationCountryMethod1 = array(1 => 'FR', 2 => 'MC');
    protected $geodisDestinationCountryMethod2 = array(1 => 'FR', 2 => 'MC', 3 => 'BE', 4 => 'LU');

    public function getGeodisMethod1() {
        return self::FRANCEEXPRESS_METHOD_1;
    }

    public function getGeodisMethod2() {
        return self::FRANCEEXPRESS_METHOD_2;
    }

    public function getGeodisMethod1Name() {
        return self::FRANCEEXPRESS_METHOD_1_NAME;
    }

    public function getGeodisMethod2Name() {
        return self::FRANCEEXPRESS_METHOD_2_NAME;
    }

    public function getMethod1IsActive() {
        return Mage::getStoreConfig(self::XML_FRANCEEXPRESS_METHOD_1_ACTIVE);
    }

    public function getMethod1Description() {
        return Mage::getStoreConfig(self::XML_FRANCEEXPRESS_METHOD_1_DESCRIPTION);
    }

    public function getMethod1Price() {
        return Mage::getStoreConfig(self::XML_FRANCEEXPRESS_METHOD_1_PRICE);
    }

    /*     * *************************************************************************************************************** */

    public function getMethod2IsActive() {
        return Mage::getStoreConfig(self::XML_FRANCEEXPRESS_METHOD_2_ACTIVE);
    }

    public function getMethod2Description() {
        return Mage::getStoreConfig(self::XML_FRANCEEXPRESS_METHOD_2_DESCRIPTION);
    }

    public function getMethod2Price() {
        return Mage::getStoreConfig(self::XML_FRANCEEXPRESS_METHOD_2_PRICE);
    }

    /*     * ************************************************************************************************************** */

    // verify if shipping method is enabled 
    public function verifyShippingDisponibility() {

        $return = array();
        $items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
        $PackageWeight = 0;
        foreach ($items as $item) {
            if (($item->getProductType() == "configurable") || ($item->getProductType() == "grouped")) {
                $PackageWeight += ($item->getWeight() * (((int) $item->getQty()) - 1));
            } else {
                $PackageWeight += ($item->getWeight() * ((int) $item->getQty()));
            }
        }

        $customerAdressCountryCode = $this->getCustomerCountry();
        //verify destination country
        $keyOdExpressDestinationCountry = array_search($customerAdressCountryCode, $this->geodisDestinationCountryMethod1);
        $keyOdMessagerieDestinationCountry = array_search($customerAdressCountryCode, $this->geodisDestinationCountryMethod2);


        if ($keyOdExpressDestinationCountry && ($PackageWeight < self::FRANCEEXPRESS_POIDS_MAX)) {
            $return['s_method_franceexpress_ondemandlogo_' . self::FRANCEEXPRESS_METHOD_1] = 1;
        } else {
            $return['s_method_franceexpress_ondemandlogo_' . self::FRANCEEXPRESS_METHOD_1] = 0;
        }

        if ($keyOdMessagerieDestinationCountry && ($PackageWeight < self::FRANCEEXPRESS_POIDS_MAX)) {
            $return['s_method_franceexpress_ondemandlogo_' . self::FRANCEEXPRESS_METHOD_2] = 1;
        } else {
            $return['s_method_franceexpress_ondemandlogo_' . self::FRANCEEXPRESS_METHOD_2] = 0;
        }

        return $return;
    }

    public function getCustomerCountry() {
        if (Mage::getSingleton('customer/session')->getCustomer()->getDefaultShipping()) {
            $customerAdressCountryCode = Mage::getSingleton('checkout/type_onepage')->getQuote()->getShippingAddress()->getCountryId();
        } else {
            $customerAdressCountryCode = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getCountryId();
        }
        return $customerAdressCountryCode;
    }

}
