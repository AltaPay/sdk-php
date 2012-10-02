<?php
require_once(dirname(__FILE__).'/IPensioHttpUtils.class.php');

class PensioFOpenBasedHttpUtils implements IPensioHttpUtils
{
	/**
	 * @return PensioHttpResponse
	 */
	public function requestURL(PensioHttpRequest $request)
	{
		global $http_response_header;
		$context = $this->createContext($request);
		
		$content = @file_get_contents($request->getUrl(), false, $context);
		$response = new PensioHttpResponse();
		$response->setHeader($http_response_header);
		if($content !== false)
		{
			$response->setContent($content);
			$response->setInfo(array('http_code'=>200));
		}
		else
		{
			$response->setInfo(array('http_code'=>500));
		}
		return $response;
	}
	
	private function createContext(PensioHttpRequest $request)
	{
		return stream_context_create(
				array(
						'http' => array(
								'method'  => 'POST',
								'header'  => sprintf("Authorization: Basic %s\r\n", base64_encode($request->getUser().':'.$request->getPass())).
								"Content-type: application/x-www-form-urlencoded\r\n",
								'timeout' => 5,
								'ignore_errors' => true,
								'content' => http_build_query($request->getParameters()),
						),
				));
	}
	
}