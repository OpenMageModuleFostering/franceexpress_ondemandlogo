<?php

class Franceexpress_Ondemandlogo_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface {

    protected $_code = 'franceexpress_ondemandlogo';
    protected $geodisOriginCountryMethod1 = array(1 => 'FR', 2 => 'MC');
    protected $geodisOriginCountryMethod2 = array(1 => 'FR', 2 => 'MC', 3 => 'BE');

    /**
     * 
     * @return boolean 
     */
    public function getIfFranceExpressModule() {
        return TRUE;
    }

    /**
     * Returns available shipping rates for Geodis Ondemandlogo carrier
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request) {

        $_shippingDisponibility = Mage::helper('franceexpress_ondemandlogo')->verifyShippingDisponibility();

        $method1 = Mage::helper('franceexpress_ondemandlogo')->getGeodisMethod1();
        $method2 = Mage::helper('franceexpress_ondemandlogo')->getGeodisMethod2();


        $originCountryCode = Mage::getStoreConfig('shipping/origin/country_id');

        $keyOdExpressOriginCountry = array_search($originCountryCode, $this->geodisOriginCountryMethod1);
        $keyOdMessagerieOriginCountry = array_search($originCountryCode, $this->geodisOriginCountryMethod2);

        /** @var Mage_Shipping_Model_Rate_Result $result */
        $result = Mage::getModel('shipping/rate_result');

        if ($keyOdExpressOriginCountry && Mage::helper('franceexpress_ondemandlogo')->getMethod1Price() !== '' && Mage::helper('franceexpress_ondemandlogo')->getMethod1Price() !== NULL && Mage::helper('franceexpress_ondemandlogo')->getMethod1Price() >= 0 && Mage::helper('franceexpress_ondemandlogo')->getMethod1IsActive()) {
            if (Mage::app()->getRequest()->getControllerName() == 'cart') {
                if ($_shippingDisponibility['s_method_franceexpress_ondemandlogo_' . $method1] != 0) {
                    $result->append($this->_getMethod1Rate());
                }
            } else {
                $result->append($this->_getMethod1Rate());
            }
        }
        if ($keyOdMessagerieOriginCountry && Mage::helper('franceexpress_ondemandlogo')->getMethod1Price() !== '' && Mage::helper('franceexpress_ondemandlogo')->getMethod1Price() !== NULL && Mage::helper('franceexpress_ondemandlogo')->getMethod1Price() >= 0 && Mage::helper('franceexpress_ondemandlogo')->getMethod2IsActive()) {
            if (Mage::app()->getRequest()->getControllerName() == 'cart') {
                if ($_shippingDisponibility['s_method_franceexpress_ondemandlogo_' . $method2] != 0) {
                    $result->append($this->_getMethod2Rate());
                }
            } else {
                $result->append($this->_getMethod2Rate());
            }
        }
        return $result;
    }

    /**
     * Returns Allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods() {
        return array(
            Mage::helper('franceexpress_ondemandlogo')->getGeodisMethod1() => Mage::helper('franceexpress_ondemandlogo')->getGeodisMethod1(),
            Mage::helper('franceexpress_ondemandlogo')->getGeodisMethod2() => Mage::helper('franceexpress_ondemandlogo')->getGeodisMethod2(),
        );
    }

    /**
     * Get On Demand Epress rate object
     *
     * @return Mage_Shipping_Model_Rate_Result_Method
     */
    protected function _getMethod1Rate() {
        /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
        $rate = Mage::getModel('shipping/rate_result_method');

        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle('France Express On Demand Logo');
        $rate->setMethod(Mage::helper('franceexpress_ondemandlogo')->getGeodisMethod1());
        $rate->setMethodTitle(Mage::helper('franceexpress_ondemandlogo')->getGeodisMethod1Name());
        $rate->setMethodDescription(Mage::helper('franceexpress_ondemandlogo')->getMethod1Description());
        $rate->setPrice(Mage::helper('franceexpress_ondemandlogo')->getMethod1Price());
        $rate->setCost(Mage::helper('franceexpress_ondemandlogo')->getMethod1Price());

        return $rate;
    }

    /**
     * Get On Demand Messagerie rate object
     *
     * @return Mage_Shipping_Model_Rate_Result_Method
     */
    protected function _getMethod2Rate() {
        /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
        $rate = Mage::getModel('shipping/rate_result_method');
        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle('France Express On Demand Logo');
        $rate->setMethod(Mage::helper('franceexpress_ondemandlogo')->getGeodisMethod2());
        $rate->setMethodTitle(Mage::helper('franceexpress_ondemandlogo')->getGeodisMethod2Name());
        $rate->setMethodDescription(Mage::helper('franceexpress_ondemandlogo')->getMethod2Description());
        $rate->setPrice(Mage::helper('franceexpress_ondemandlogo')->getMethod2Price());
        $rate->setCost(Mage::helper('franceexpress_ondemandlogo')->getMethod2Price());

        return $rate;
    }

}
