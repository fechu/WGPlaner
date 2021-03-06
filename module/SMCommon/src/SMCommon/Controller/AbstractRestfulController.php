<?php
/**
 * @file BaseController.php
 * @date Aug 6, 2013 
 * @author Sandro Meier
 */
 
namespace SMCommon\Controller;


use Zend\Mvc\Controller\AbstractRestfulController as ZendRestfulController;
use Zend\Log\LoggerAwareInterface;
use SMCommon\Doctrine\EntityManagerAwareInterface;
use Zend\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;
use Zend\Validator\Digits;
use SMCommon\Log\Logger;
use Zend\View\Model\JsonModel;
/**
 * A base controller. 
 * It implements basic things like the logger that all controllers need.
 * 
 * @method \Application\Controller\Plugin\LoggedInUserPlugin loggedInUser() Returns the logged in user or NULL if no user is logged in.
 */
abstract class AbstractRestfulController extends ZendRestfulController implements LoggerAwareInterface, EntityManagerAwareInterface 
{
	/**
	 * The logger
	 * @var Logger
	 */
	protected $logger;
	
	/**
	 * The entity manager
	 * @var EntityManager
	 */
	protected $em;
	
	/**
	 * The prefix for the default ID. This is used together with the getId() and requireId() methods. 
	 * If you don't supply a prefix, this property will be checked and used instead (if it's not null).
	 */
	protected $defaultId;
	
	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}
	
	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}
	
	/**
	 * Convenience method to get the ID from the route. 
	 * 
	 * If there's no parameter in the route called "id" the method will also check the 
	 * query parameters for a 'id=123' parameter.
	 * 
	 * @param string $prefix	The prefix that should be added before the 'id'. Example: $prefix = 'user' will 
	 * 							check for the 'userid' in the route and then in the query parameters.
	 * 
	 * @return int	The id or 0 if no ID could be found.
	 * 
	 * @see $defaultId
	 */
	public function getId($prefix = NULL)
	{
		if ($prefix === NULL) {
			// Just use the default ID.
			$prefix = $this->defaultId;
		}
		
		// Prefix?
		$idKey = $prefix ? $prefix . 'id' : 'id';
		
		$id = $this->params()->fromRoute($idKey, false);
		
		if (!$id) {
			$id = $this->params()->fromQuery($idKey, 0);
		}
		
		if ($id) {
			// The validator is required because, query parameters can not be checked with a regex in 
			// during routing. If you write the route correctly with a constraint id => '[0-9]+', then 
			// an id from the route will never hit this Digits validator.
			$validator = new Digits();
			if (!$validator->isValid($id)) {
				$this->logger->notice('Supplied invalid ID to controller action. Do we have a scripting kiddy?', array('id' => $id));
				return 0; 	// not a valid id.
			}
		}
		
		return $id;
	}
	
	/**
	 * Checks a id is given (with getId()). If none is found, will redirect to a Not Found page.
	 * 
	 * @param string $prefix  See documentation of AbstractActionController#getId()
	 * 
	 * @see getId() for the documentation of the $prefix parameter
	 */
	public function requireId($prefix = NULL)
	{
		$id = $this->getId($prefix);
		if (!$id) {
			// Invalid ID!
			$this->getResponse()->setStatusCode(404);
			return false;
		}
		
		return $id;
	}
	
	/**
	 * Shorthand to get the configuration.
	 * Equal to $this->getServiceLocator()->get('config')
	 */
	public function getConfig()
	{
		return $this->getServiceLocator()->get('config');
	}
	
	protected function createdResponse()
	{
		/* @var $response \Zend\Http\Response */
		$response = $this->getResponse();
		
		$response->setStatusCode(201);
		return $response;
	}
	
	/**
	 * Modifies the response, that it indicates that the request has to be authorized.
	 * Includes information in the body on how to resolve this issue. 
	 * @return JsonModel The model containing further information.
	 */
	protected function unauthorizedResponse($details = null)
	{
		/* @var $response \Zend\Http\Response */
		$response = $this->getResponse();
		
		$response->setStatusCode(401);
		
		return $this->generateErrorViewModel(
				'The request is not authorized.',
				$details
		);
	}
	
	protected function badRequestResponse($details = null)
	{
		/* @var $response \Zend\Http\Response */
		$response = $this->getResponse();
		
		$response->setStatusCode(400); // Bad request
		
		return $this->generateErrorViewModel(
				'Your request was not understand by the server.',
				$details
		);
	}
	
	protected function generateErrorViewModel($error, $details = null) 
	{
		$errorData = array(
			'error' => $error,
		);
		
		if ($details) {
			$errorData['error_details'] = $details;
		}
		
		return new JsonModel($errorData);
	}
}