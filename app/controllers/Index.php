<?php 

use Yaf\Dispatcher;

class IndexController extends BaseController
{
	public function indexAction()
	{
        $this->getResponse()->setBody('not found');
	}
}
