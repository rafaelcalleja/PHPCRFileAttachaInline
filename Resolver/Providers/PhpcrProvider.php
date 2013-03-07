<?php
namespace RC\PHPCR\FileAttachInlineBundle\Resolver\Providers;
  
use RC\PHPCR\FileAttachInlineBundle\Resolver\FileNameInterface;

class PhpcrProvider implements FileNameInterface{
	
	protected $multilang, $locales, $field, $field_title, $dm, $localemanager, $request;
	
	public function __construct($options, $dm, $localemanager, $container){
		
		foreach($options as $k => $o){
			$this->{$k} = $o;
		}
		$this->dm = $dm;
		$this->localemanager = $localemanager;
		$this->request = $container->get('request');
	}
	
	protected function getFieldTitle($locale){
		if(!$this->multilang) return $this->field_title;
		if(in_array($locale, $this->locales)) return str_replace('{_locale}', $locale, $this->field_title);
		return $this->field_title;
	}
	
	public function getName($filepath){
		try{

			$fieldtitle = $this->getFieldTitle($this->localemanager->runLocaleGuessing($this->request));
			$sql = "SELECT ".$fieldtitle." from [nt:unstructured] where ".$this->field." = '".basename($filepath)."'";
			$info = pathinfo($filepath);
			$extension = (isset($info['extension'])) ? '.'. $info['extension'] : '' ;
			$results = $this->dm->createPhpcrQuery($sql, 'JCR-SQL2')->execute();
			$row = $results->getRows();
			$title = (count($row) > 0) ?  $row->current()->getValue($fieldtitle) : false;
			return (!empty($title)) ? $title.$extension : false;
		}catch(Exception $e){
			return false;
		}
		
		return false;	
	}
	
	
}