<?php
namespace RC\PHPCR\FileAttachInlineBundle\Validator;
 
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\MaxLength;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\True;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\File\File as archivo;
use Symfony\Component\HttpKernel\Exception\HttpException;

class filevalidator {
	
	protected $container;
	
	protected $defaults = array(
				'maxSize' => '3072k',
				'mimeTypes' => array('application/pdf', 'application/x-pdf', 'application/zip'),
				'mimeTypesMessage' => "El Tipo de fichero ({{ type }}) no está permitido"			
			);
	
	
	
	public function __construct($container, $options, $test){
		$this->container = $container;
		$this->setDefaults($options);
	}
	
	private function setDefaults($values){
		$this->defaults = array_merge($this->defaults, $values);
	}
	
   /**
     * Validates Register Data passed as an array to be reused
     * 
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @param array $registerData
     */
    public function validateFile(array $registerData)
    {
    	
        $collectionConstraint = new Collection(array(
            'filevalidator' => array(
                         new NotBlank(),
                         new File($this->defaults),
                         new Callback(array('methods' => array(
                                         array($this, 'isValidFile')
                                      ))),
                         ),
            
        ));
        
 
        foreach($registerData as $file){
        	if(!$this->doValid( new archivo($file), $collectionConstraint)) return false;
        }
        return true;
        
    }
    protected function doValid($file, $collectionConstraint){
    	
    	$errors = $this->container->get('validator')->validateValue(array('filevalidator'=> $file), $collectionConstraint);
    	if (count($errors) !== 0) {
    		throw new HttpException(400, $errors[0]->getPropertyPath() . ':' . $this->container->get('translator')->trans($errors[0]->getMessage(), array(), 'validators'));
    		return false;
    	}
    	return true;
    }
 
   
    public function isValidFile($arg, ExecutionContext $context){
    	/* TODO */
    	return true;
    }
}