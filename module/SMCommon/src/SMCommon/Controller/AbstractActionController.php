<?php
/**
 * @file BaseController.php
 * @date Aug 6, 2013 
 * @author Sandro Meier
 */
 
namespace SMCommon\Controller;


use Zend\Mvc\Controller\AbstractActionController as ZendActionController;
use Zend\Log\LoggerAwareInterface;
use SMCommon\Doctrine\EntityManagerAwareInterface;
use Zend\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;
/**
 * A base controller. 
 * It implements basic things like the logger that all controllers need.
 * 
 * @method \Application\Controller\Plugin\LoggedInUserPlugin loggedInUser() Returns the logged in user or NULL if no user is logged in.
 */
abstract class AbstractActionController extends ZendActionController implements LoggerAwareInterface, EntityManagerAwareInterface 
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
	 * @return int	The id or 0 if no ID could be found.
	 */
	public function getId()
	{
		$id = $this->params()->fromRoute('id', false);
		
		if (!$id) {
			$id = $this->params()->fromQuery('id', 0);
		}
		if ($id) {
			$validator = new Digits();
			if (!$validator->isValid($id)) {
				$this->logger->notice('Supplied invalid ID to controller action. Do we have a scripting kiddy?', array('id' => $id));
				return 0; 	// not a valid id.
			}
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
}