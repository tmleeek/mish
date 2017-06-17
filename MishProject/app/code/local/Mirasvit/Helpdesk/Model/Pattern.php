<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Help Desk MX
 * @version   1.1.0
 * @build     1285
 * @copyright Copyright (C) 2015 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_Helpdesk_Model_Pattern extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('helpdesk/pattern');
    }

    public function toOptionArray($emptyOption = false)
    {
    	return $this->getCollection()->toOptionArray($emptyOption);
    }
 
	/************************/

    public function checkEmail($email) {
        $subject = '';
        switch($this->getScope()) {
        case 'headers':
            $subject = $email->getHeaders();
            break;
        case 'subject':
            $subject = $email->getSubject();
            break;
        case 'body':
            $subject = $email->getBody();
            break;
        }
        $matches = array();
        preg_match($this->getPattern(), $subject, $matches);
        if (count($matches) > 0) {
            return true;
        } else {
            return false;
        }
    }
}