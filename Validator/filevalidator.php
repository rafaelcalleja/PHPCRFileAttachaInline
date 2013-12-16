<?php
namespace RC\PHPCR\FileAttachInlineBundle\Validator;
 
use RC\PHPCR\FileAttachInlineBundle\Event\FileResponseEvent;
use Symfony\Component\Finder\Finder;
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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use RC\PHPCR\FileAttachInlineBundle\Event\FAIEvent;
use RC\PHPCR\FileAttachInlineBundle\Event\FileErrorEvent;

class filevalidator {
	
	protected $container;

    protected $filename;

    protected $ignore_files;


	
	protected $defaults = array(
				'maxSize' => '3072k',
				'mimeTypes' => array('application/pdf', 'application/x-pdf', 'application/zip'),
				'mimeTypesMessage' => "El Tipo de fichero ({{ type }}) no está permitido"			
			);
	
	
	
	public function __construct($container, $options, $ignore_files = array() ){
		$this->container = $container;
		$this->setDefaults($options);
        $this->ignore_files = $ignore_files;
	}
	
	private function setDefaults($values){
		$this->defaults = array_merge($this->defaults, $values);
	}

    private function getRealPath($file){

        try{

            $filename = pathinfo($file, PATHINFO_BASENAME) ;
            $directory = dirname($file) ;

            if(!file_exists($directory)){
                // TODO
                $directory = str_replace('publicaciones-notas-tecnicas/', '', $directory);

            }

            $files = Finder::create()->in($directory)->name('/'.str_replace(' ', '.', $filename.'/'))->files();

            if( $files->count() == 1){
                foreach($files as $f) {
                    $this->filename = $f->getPathname();
                    return $f->getPathname();
                }
            }

            return $file;

        }catch(\Exception $e){
            return $file;
        }
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
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

        	if(!$this->doValid( new archivo($this->getRealPath($file), false), $collectionConstraint)) return false;
        }
        return true;
        
    }
    protected function doValid($file, $collectionConstraint){
    	
    	$errors = $this->container->get('validator')->validateValue(array('filevalidator'=> $file), $collectionConstraint);
    	if (count($errors) !== 0) {

            $filesize = (file_exists($file->getPathname())) ?  $file->getSize() : 0 ;
            $mimetype = (file_exists($file->getPathname())) ?  $file->getMimeType() : false ;

            $event = new FileErrorEvent($file->getFileName(), $file->getPathname(), $filesize, $mimetype, $errors,  $this->container->get('translator')->trans($errors[0]->getMessage(), array(), 'validators') );
            $this->container->get('event_dispatcher')->dispatch(FAIEvent::FAI_FAILED_VALIDATION, $event );

    		//throw new HttpException(404, $errors[0]->getPropertyPath() . ':' . $this->container->get('translator')->trans($errors[0]->getMessage(), array(), 'validators'));
            //throw new NotFoundHttpException($errors[0]->getPropertyPath() . ':' . $this->container->get('translator')->trans($errors[0]->getMessage(), array(), 'validators'));
    		return false;
    	}
    	return true;
    }
 
   
    public function isValidFile($arg, ExecutionContext $context){

        if($arg instanceof \Symfony\Component\HttpFoundation\File\File ){
            if(in_array($arg->getFilename(), $this->ignore_files)) {

                $context->addViolationAt('filename', 'El archivo esta en la lista de ignorados', array(), null);

                $filesize = (file_exists($arg->getPathname())) ?  $arg->getSize() : 0 ;
                $mimetype = (file_exists($arg->getPathname())) ?  $arg->getMimeType() : false ;

                $event = new FileResponseEvent($arg->getFileName(), $arg->getPathname(), $filesize, $mimetype );
                $this->container->get('event_dispatcher')->dispatch(FAIEvent::FAI_RENDER_INLINE, $event );
                return false;
            }
        }
        //die(var_dump($arg->getFilename()));
    	/* TODO */
    	return true;
    }
}