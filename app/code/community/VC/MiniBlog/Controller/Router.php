<?php
class VC_MiniBlog_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    public function initControllerRouters($observer)
    {
		if (Mage::getStoreConfig('vc_miniblog/blog/enable')) {
			$front = $observer->getEvent()->getFront();
			$vcRouter = new VC_MiniBlog_Controller_Router();
			$front->addRouter('vc_miniblog', $vcRouter);
		}
    }

    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::app()->isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
		
		
		$pathInfo = trim($request->getPathInfo(), '/');
		//echo $pathInfo;die();
		$pathInfoAr = explode('/', $pathInfo);
		if ($pathInfoAr[0] == Mage::getStoreConfig('vc_miniblog/blog/router')) {
			if (isset($pathInfoAr[1])) {
				switch ($pathInfoAr[1]) {
					case 'tag':
						$request->setModuleName('vc_miniblog')
							->setControllerName('index')
							->setActionName('index');
							
						$request->setParams(array('tag' => $pathInfoAr[2]));	
						$request->setParams(array('tag' => $pathInfoAr[2]));
					break;
					case 'cat':
						$request->setModuleName('vc_miniblog')
							->setControllerName('index')
							->setActionName('index');
							
						$request->setParams(array('cat' => $pathInfoAr[2]));
					break;
					default:
						$request->setModuleName('vc_miniblog')
							->setControllerName('index')
							->setActionName('postDetail');
					
						$request->setParams(array('identifier' => $pathInfoAr[1]));
					break;
				}
			} else {
				$request->setModuleName('vc_miniblog')
					->setControllerName('index')
					->setActionName('index');
			}
            return true;		
		}
        return false;
    }
}