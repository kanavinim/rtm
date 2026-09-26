<?php

namespace Ipol\OzonPay\SDK\Client;

class WordpressRequests implements ApiClient {

	private $url = '';
	private $timeout = 15000;

	/**
	 * @var array
	 */
	protected $headers = [];

	private $getting_headers;
	private $data_request;
	private $answer;

	private $code;

	public function __construct(int $timeout = 15, array $config = []) {
		$this->timeout = $timeout * 1000;
	}

	public function setOpt( int $opt, $val ): ApiClient {
		return $this;
	}

	public function config( array $args ): ApiClient {
		foreach ($args as $key => $value) {
			if ($key === CURLOPT_HTTPHEADER) $this->headers = array_merge($this->headers,$value);
		}
		return $this;
	}

	public function setUrl( string $url ): ApiClient {
		$this->url = $url;
		return $this;
	}

	public function getUrl(): string {
		return $this->url;
	}

	public function get( array $data = [] ): ApiClient {
		$this->wp_request('GET',$data);
		return $this;
	}

	public function post( string $data = '', bool $doNotClose = false ): ApiClient {
		$this->wp_request('POST',$data);
		return $this;
	}

	public function put( string $data = '' ): ApiClient {
		return $this;
	}

	public function getAnswer() {
		return $this->answer;
	}

	public function getCode() {
		return $this->code;
	}

	public function getCurlErrNum(): int {
		return 0;
	}

	private function wp_request($method='POST',$reqdata='') {
		$headers=[]; //prepare headers
		foreach ($this->headers as $hdr) {
			$pair=explode(': ',$hdr,2);
			$headers[trim($pair[0])]=trim($pair[1]);
		}
		$return = wp_remote_request($this->url,[
			'method'=>$method,
			'headers'=>$headers,
			'cookies'=>[],
			'body'=>$reqdata,
		]);

		if ($return instanceof \WP_Error) {
            $errstr = implode('', $return->get_error_messages());
			$this->code = 500;
			$this->answer = $errstr;
			return;
		}

		$this->data_request = $return['response'];

		//collect headers
		$res_headers = $return['headers']->getAll();
		$this->getting_headers=$res_headers;

		$this->code = $this->data_request['code'];
		$this->answer=$return['body'];
	}

}
