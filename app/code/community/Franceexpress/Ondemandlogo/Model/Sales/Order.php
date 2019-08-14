<?php

class Franceexpress_Ondemandlogo_Model_Sales_Order extends Mage_Sales_Model_Order {

    //add geodis shipping information to order view 
    public function getShippingDescription() {
        $desc = parent::getShippingDescription();
        $geodisObjectFranceExpress = $this->getGeodisObjectFranceExpress();
        if ($geodisObjectFranceExpress) {
            $desc .= " | ";
            if ($geodisObjectFranceExpress->getPhone()) {
                $desc .= "Tél: " . $geodisObjectFranceExpress->getPhone() . " | ";
            }
            if ($geodisObjectFranceExpress->getMobile()) {
                $desc .= "Mobile : " . $geodisObjectFranceExpress->getMobile() . " | ";
            }
            if ($geodisObjectFranceExpress->getEmail()) {
                $desc .= "Email : " . $geodisObjectFranceExpress->getEmail() . "";
            }
        }
        return $desc;
    }

}
