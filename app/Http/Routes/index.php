<?php 


class Person{
	private $color; 
	private $age; 
	private $height;
	private $size; 
	public $name; 


	function setSize($value){
		$this->size = $value; 
	}

	function getSize(){
		return $this->size; 
	}

	function getHeight(){
		return $this->height; 
	}

	function setHeight($value){
		$this->height = $value; 
	}

}







