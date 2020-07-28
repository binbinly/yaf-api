<?php

use Yaf\Loader;
use Yaf\Registry;
use Yaf\Application;
use Yaf\Dispatcher;
use Yaf\Bootstrap_ABstract;
 
class Bootstrap extends Bootstrap_Abstract{

    protected $config;

    public function _initConfig() {
		$this->config = Application::app()->getConfig()->toArray();

		Registry::set('config', $this->config);
	}

	public function _initPlugin(Dispatcher $dispatcher) {
		$objSamplePlugin = new SamplePlugin();
		$dispatcher->registerPlugin($objSamplePlugin);
	}

	public function _initLoader(Dispatcher $dispatcher)
	{
		Loader::import(APPLICATION_PATH . '/vendor/autoload.php');

        Sentry\init(['dsn' => 'http://06130970d43a40818528d78f320c4602@159.138.145.44:9000/4' ]);

        $client = new Predis\Client([
            'scheme' => 'tcp',
            'host'   => $this->config['redis']['host'],
            'port'   => $this->config['redis']['port'],
        ]);
        if ($this->config['redis']['auth']) {
            $client->auth($this->config['redis']['auth']);
        }
        Registry::set('redis', $client);
	}

	public function _initDb(Dispatcher $dispatcher){

    }
}
