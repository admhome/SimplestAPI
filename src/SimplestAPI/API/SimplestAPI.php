<?php

namespace API/SimplestAPI;

class SimplestAPI {
	protected $apiURL;
	protected $apiKey;

	private $opts;

	public function __construct($apiURL, $apiKey) {
		$this->apiURL = $apiURL;
		$this->apiKey = $apiKey;

		if (empty($this->apiURL) || empty($this->apiKey)) {
			throw new Exception("[ERROR] apiURL and apiKey must not be empty");
		}
	}

	/*
	 * Сохранить массив опций
	 */
	protected function buildOpts($method = 'GET', $requestData = [], $debug = false) {
		$opts = array('http' =>
			array(
				'method'  => $method,
				'header'  => 'Content-type: application/x-www-form-urlencoded'
			)
		);

		if ($method == 'POST') {
			$opts['http']['content'] = http_build_query($requestData);
		}

		return ($debug == false)
			? $this->opts = $opts
			: $opts;
	}

	/*
	 * Выполнить рандоный метод
	 */
	public function execute($action, $method = 'GET', $requestData = []) {
		$this->buildOpts($method, $requestData);
		$context  = stream_context_create($this->opts);
		$addAction = ($method == 'GET')
			? '?' . http_build_query($requestData)
			: '';
		return json_decode(
			file_get_contents(
				$this->apiURL . $action . $addAction,
				false,
				$context
			),
			true
		);
	}

	public function showExecuteString($action, $method = 'GET', $requestData = []) {
		$opts = $this->buildOpts($method, $requestData, true);
		return $this->apiURL . $action . '?' . http_build_query($requestData);
	}
}

?>
