<?php
/**
 * @file UploadForm
 * @date Aug 1, 2015
 * @author Sandro Meier
 */
namespace SMCommon\Form;

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * A form that only contains a single file element. 
 * It can also be used to validate file uploads.
 */
class UploadForm extends Form
{
    protected $identifier;
	    
    public function __construct($name, $identifier = 'image-file')
    {
	// Construct the form
	parent::__construct($name);
	
	$this->identifier = $identifier;
	$this->addElements();
    }
	
    public function addElements()
    {
	$file = new Element\File($this->identifier);
	$file->setAttribute('id', $this->identifier);
	$file->setAttribute('required', 'required');
	$this->add($file);
    }

    public function getInputFilterSpecification()
    {
        return array(
            $this->identifier => array(
                'required' => true,
            ),
        );
    }
}