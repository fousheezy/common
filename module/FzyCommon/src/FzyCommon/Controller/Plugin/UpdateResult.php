<?php

namespace FzyCommon\Controller\Plugin;

use FzyCommon\Service\Update;
use Zend\Http\PhpEnvironment\Response;
use Zend\View\Model\JsonModel;

class UpdateResult extends Base
{
    public function __invoke(Update $updater)
    {
        $redirect = null;
        if ($updater->getValid()) {
            $this->flashMessenger()->addSuccessMessage($updater->getFormattedSuccessMessage());
            $redirect = $this->url()->fromRoute($updater->getSuccessRedirectRouteName(), $updater->getSuccessRedirectRouteParams(), $updater->getSuccessRedirectRouteOptions());
        } else {
	        // set error
	        /* @var $response \Zend\Http\PhpEnvironment\Response */
	        $response = $this->getService('Response');
	        $response->setStatusCode(Response::STATUS_CODE_400);
        }

        return array(
            'success' => $updater->getValid(),
            'messages' => $updater->getErrorMessages(),
            'models' => $updater->getEntitiesAsJson(),
            'redirect' => $redirect,
        );
    }

    /**
     * @return \Zend\Mvc\Controller\Plugin\FlashMessenger
     */
    protected function flashMessenger()
    {
        return $this->getService('ControllerPluginManager')
            ->get('flashmessenger');
    }

    /**
     * @return \Zend\Mvc\Controller\Plugin\Url
     */
    protected function url()
    {
        return $this->getService('ControllerPluginManager')
            ->get('url');
    }

}
