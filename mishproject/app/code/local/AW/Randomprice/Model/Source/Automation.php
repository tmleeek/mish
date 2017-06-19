<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Randomprice
 * @version    1.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */


class AW_Randomprice_Model_Source_Automation {
    const NO = 0;
    const NO_LABEL = 'No';

    const INSIDE_PRODUCT_PAGE = 1;
    const INSIDE_PRODUCT_PAGE_LABEL = 'Inside product page';

    public function getOptionArray() {
        $_helper = Mage::helper('awrandomprice');
        return array(
            self::NO => $_helper->__(self::NO_LABEL),
            self::INSIDE_PRODUCT_PAGE => $_helper->__(self::INSIDE_PRODUCT_PAGE_LABEL),
        );
    }

}