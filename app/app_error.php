<?php
class AppError extends ErrorHandler {

	//public function error($code, $name, $message)
	public function error($params)
	{
		if (empty($params['code']))
			$params['code'] = '';
		$this->controller->set('params', $params);
		switch ($params['code'])
		{
			case 404:
				$this->_outputMessage('error404');
			break;
			case 503:
				$this->_outputMessage('error503');
			break;
			default:
				$this->controller->set('params', $_SERVER);
				$this->_outputMessage('myerror');
		}
	}

	public function error404($params)
	{
		$this->error($params);
	}

	public function missingController($params)
	{
		$this->error($params);
	}
}