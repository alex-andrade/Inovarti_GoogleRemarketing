<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_GoogleRemarketing
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_GoogleRemarketing_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isTrackingAllowed()
    {
        return Mage::getStoreConfigFlag('inovarti_googleremarketing/googleremarketing/enabled');
    }
    public function isHomepage() {
        return (Mage::getSingleton('cms/page')->getIdentifier() == 'petnanet_home' ? true : false);
    }

    public function getPageType()
    {
        $_pagetype = 'siteview';
        $_controllerName = Mage::app()->getRequest()->getControllerName();
        $_frontcontroller = Mage::app()->getFrontController()->getRequest()->getRouteName();
        if ($_frontcontroller=='onepagecheckout'){
            $_controllerName = 'onepagecheckout';
        }
        
        $_actionName = Mage::app()->getRequest()->getActionName();
        switch ($_controllerName) {
            case 'index':
                if ($this->isHomepage())
                    $_pagetype = 'home';
                break;
            case 'category':
                $_pagetype = 'category';
                break;
            case 'product':
                $_pagetype = 'product';
                break;
            case 'cart':
                $_pagetype = 'cart';
                break;
            case 'onepagecheckout':
                if ($_actionName == 'success') {
                $_pagetype = 'purchase';
                }
                break;
            case 'onepage':
                if ($_actionName == 'success') {
                $_pagetype = 'purchase';
                }
                break;
            default:
                // siteview
                break;

        }
        return $_pagetype;
    }
    public function getPageNamecategory()
    {
        $Namecategory = '';
        if (Mage::registry('current_category')) {
            $Namecategory = Mage::registry('current_category')->getName();
        }
        return $Namecategory;
    }
}
