<?php

class VES_VendorsLiveChat_Model_Mysql4_Session extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        // Note that the vendorslivechat_id refers to the key field in your database table.
        $this->_init('vendorslivechat/session', 'session_id');
    }
}