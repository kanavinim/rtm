<?php

namespace Ipol\OzonPay\SDK\Client;

interface ApiClient
{

	public function __construct(int $timeout = 15, array $config = []);

	public function setOpt(int $opt, $val);

	public function config(array $args);

	public function setUrl(string $url);
	public function getUrl(): string;

	public function get(array $data = []);
	public function post(string $data = '', bool $doNotClose = false);
	public function put(string $data = '');

	public function getAnswer();
	public function getCode();
	public function getCurlErrNum(): int;

}
