<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_Inventorydashboard
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Inventorydashboard Observer Model
 * 
 * @category    Magestore
 * @package     Magestore_Inventorydashboard
 * @author      Magestore Developer
 */
class Magestore_Inventorydashboard_Model_Observer
{

    public function changeDashboard( $observer )
    {
        Mage::log($observer);
        if (Mage::helper('inventoryplus')->isDataInstalled()) {
            Mage::app()->getResponse()
                ->setRedirect(
                    Mage::helper("adminhtml")
                        ->getUrl(
                            "adminhtml/ind_dashboard",
                            array(
                                "_secure" => Mage::app()->getStore()->isCurrentlySecure()
                            )
                        )
                )->sendResponse();
        }
    }
}