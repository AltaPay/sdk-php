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
		
		$url = ($request->getMethod() == 'GET') ? $this->appendToUrl($request->getUrl(), $request->getParameters()) : $request->getUrl(); 
		$content = @file_get_contents($url, false, $context);
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
		$args = array(
						'http' => array(
								'method'  => $request->getMethod(),
								'header'  => sprintf("Authorization: Basic %s\r\n", base64_encode($request->getUser().':'.$request->getPass())).
								"Content-type: application/x-www-form-urlencoded\r\n",
								'timeout' => 5,
								'ignore_errors' => true,
						),
				);
		if($request->getMethod() == 'POST')
		{
			$args['http']['content'] = http_build_query($request->getParameters());
		}
		return stream_context_create($args);
	}
	
	/**
	 * This method will append the given parameters to the URL. Using a ? or a & depending on the url
	 *
	 * @param string$url
	 * @param array $parameters
	 * @return string - the URL with the new parameters appended
	 */
	public function appendToUrl($url, array $parameters)
	{
		if(count($parameters) > 0)
		{
			$append = http_build_query($parameters);
			return $url.(strstr($url, "?") ? "&" : "?").$append;
		}
		return $url;
	}
}