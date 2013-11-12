<?php
namespace RC\PHPCR\FileAttachInlineBundle\Event;


class FileErrorEvent extends FileInfoEvent  {

    protected $errors;
    protected $message;


    public function __construct($filename, $filepath, $size, $mimetype, $errors, $message){
        parent::__construct($filename, $filepath, $size, $mimetype);
        $this->errors = $errors;
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }




}