<?php
class VC_MiniBlog_Model_Session extends Mage_Core_Model_Session_Abstract
{
    public function __construct()
    {
        $this->init('vc_miniblog');
    }

    public function getDisplayMode()
    {
        return $this->_getData('display_mode');
    }

}
