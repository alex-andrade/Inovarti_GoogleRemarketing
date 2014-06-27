<?php
/**
 *
 * @category   Inovarti
 * @package    Inovarti_GoogleRemarketing
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_GoogleRemarketing_Block_Block extends Mage_Core_Block_Abstract {

    public function __construct() {
        parent::__construct();
        $this->setGoogleConversionId(Mage::getStoreConfig('inovarti_googleremarketing/googleremarketing/google_conversion_id'));
    }

    protected function _toHtml() {
        $_helper = Mage::helper('inovarti_googleremarketing');
        $html = "";
        
        if (Mage::helper('inovarti_googleremarketing')->isTrackingAllowed()) {
            $_pagetype      =   $_helper->getPageType();
            $_product       =   Mage::registry('current_product');
            $_productId     =   '';
            $_productPrice  =   '';
            $tagproductId   =   '';
            $tagproductPrice=   '';
            $tagecommPcat   =   $_helper->getPageNamecategory();
            
            if ($_product) {
                $_productId = $_product->getSku();

                //VERIFICA TIPO DE PRODUTO E DESCONTO
                $_finalPriceInclTax = $this->helper('tax')->getPrice($_product, $_product->getFinalPrice(), true);
                $_minimalPriceInclTax = $_maximalPriceInclTax = false;
                $_priceModel = $_product->getPriceModel();
                if ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
                    list($_minimalPriceInclTax, $_maximalPriceInclTax) = $_priceModel->getPrices($_product, null, true, false);
                } elseif ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_GROUPED) {
                    $_minimalPriceValue = $_product->getMinimalPrice();
                }
                if ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE):
                    $_productPrice = $_minimalPriceValue;
                elseif ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_GROUPED):
                    $_productPrice = $_product->getGroupedMinimalPrice();
                else:
                    $_productPrice = $_finalPriceInclTax;
                endif;
            }
            $_conversionId = $this->getGoogleConversionId();

            if (!empty($_productId))       $tagproductId = "ecomm_prodid: '".$_productId."',";
            if (!empty($_productPrice))    $tagproductPrice = "ecomm_totalvalue: '".$_productPrice."',";
            if (!empty($tagecommPcat))    $tagecommPcat = "ecomm_pcat: '".$tagecommPcat."'";
            
            $html .= "<script type=text/javascript>
                    var google_tag_params = {
                        ".$tagproductId."
                        ecomm_pagetype: '".$_pagetype."',
                        ".$tagproductPrice."
                        ".$tagecommPcat."
                    };
                    </script>
                    <script type=text/javascript>
                    /* <![CDATA[ */
                    var google_conversion_id = ".$_conversionId.";
                    var google_custom_params = window.google_tag_params;
                    var google_remarketing_only = true;
                    /* ]]> */
                    </script>
                    <script type=\"text/javascript\" src=\"//www.googleadservices.com/pagead/conversion.js\"></script>
                    <noscript><div style=\"display:inline;\"><img height=\"1\" width=\"1\" style=\"border-style:none;\" alt=\"googleads\" src=\"//googleads.g.doubleclick.net/pagead/viewthroughconversion/".$_conversionId."/?value=0&amp;guid=ON&amp;script=0\"/></div></noscript>";
        }
        return $html;
    }
}
