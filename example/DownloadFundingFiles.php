<?php

require_once(dirname(__FILE__).'/base.php');


// 


$pageCount = 1; // We assume there is at least one page (then we correct it later)
for($page = 1; $page <= $pageCount; $page++)
{
	$response = $api->getFundingList($page);
	if($response->wasSuccessful())
	{
		foreach($response->getFundings() as $funding) /* @var $funding PensioAPIFunding */
		{
			print("We have a funding of ".$funding->getAmount()." ".$funding->getCurrency()." made on the date ".$funding->getFundingDate()."\n");
			$csv = $api->downloadFundingCSV($funding);
			
			print("The CSV Downloaded is:\n".$csv."\n");
		}
		$pageCount = $response->getNumberOfPages();
	}
	else
	{
		throw new Exception("We could not get the funding list: ".$response->getErrorCode());
	}
}
