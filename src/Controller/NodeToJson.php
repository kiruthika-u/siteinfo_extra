<?php

namespace Drupal\siteinfo_extra\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\node\Entity\Node;
use Drupal\Core\Form\ConfigFormBase;

/**
* Defines NodeToJson class.
*/
class NodeToJson extends ControllerBase {


	/**
	* Stores the configuration factory.
	*
	* @var \Drupal\Core\Config\ConfigFactory
	*/

	protected $configFactory;

	/**
	* Stores the error code.
	*/

	protected $errorCode;

	/**
	* Stores the error code.
	*/

	protected $responseStatus;

	/**
	* Stores the response data.
	*/

	protected $date;


	/**
	* Stores the response data.
	*/

	protected $message;

	/**
	* Display the markup.
	*
	* @return array
	*   Return response as json array.
	*/

	public function getApiResponce($apikey,$nodeid)
	{

		$this->errorCode = '';
		$this->responseStatus = '';
		$this->data = '';

		$config = $this->config('system.site');
		$siteAPIKey = $config->get('siteapikey');

		$response = new Response();
		$response->headers->set('Content-Type', 'application/json');
		$serializer = \Drupal::service('serializer');

		if($siteAPIKey==$apikey)
		{
			$node = Node::load($nodeid);
			if($node)
			{
				$nodeType = $node->bundle();
				if($nodeType == 'page')
				{
					$this->data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
					$this->errorCode = '200';
					$this->responseStatus = 'OK';
					$this->message = 'Success';
				}
				else{
					$this->errorCode = '403';
					$this->responseStatus = 'OK';
					$this->message = 'Access denied'; //Invalid Node Type';
				}	
			}
			else{
				$this->errorCode = '404';
				$this->responseStatus = 'OK';
				$this->message = 'Access denied';//'Invalid Node ID';
			}
		}
		else{
			$this->errorCode = '403';
			$this->responseStatus = 'OK';
			$this->message = 'Access denied';//Invalid API Key;
		}

		$response->setContent(json_encode(array('status' => array('type'=>$this->responseStatus,'code'=>$this->errorCode,'message'=>$this->message), 'content' => $this->data)));
		return $response;
	}
}
