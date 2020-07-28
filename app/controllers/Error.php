<?php

class ErrorController extends BaseController
{
	public function errorAction($exception)
	{
        /** @var Exception $exception */
        Sentry\captureException($exception);

        $this->error($exception->getMessage(), $exception->getCode());
	}
}
