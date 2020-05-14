<?php
class Note implements JSONSerializable{
    
    private $id;
    private $userId;
    private $content;
    private $x;
    private $y;
    
    
     /**
      * constructor
      *
      * @param string $id 
      * @param string $userid 
      * @param string $content
      * @param string $posX
      * @param string $posY
      * 
      */
    public function __construct($id="", $userId="", $content="", $posX="", $posY=""){
        $this->id = $id;
        $this->userId = $userId;
        $this->content = $content;
        $this->x = $posX;
        $this->y = $posY;
        
    }
    
    /*
    * serialize object to json object
    * @return
    */
    function jsonSerialize() {
		return [
		    'id' => $this->id,
		    'userId' => $this->userId,
		    'content' => $this->content,
		    'posX' => $this->x,
		    'posY' => $this->y
		];
	}
	
	
	 public function getId(){
        return $this->id;
    }
	
    public function setId(string $id){
        $this->id = $id;
    }
    
    public function getUserId(){
        return $this->userId;
    }
	
    public function setUserId(string $id){
        $this->userId = $id;
    }
    
    public function getContent(){
        return $this->content;
    }
    
    public function getX(){
        return $this->x;
    }
	
    public function setX(int $x){
        $this->x = $x;
    }
    
     public function getY(){
        return $this->y;
    }
	
    public function setY(int $y){
        $this->y = $y;
    }
    
    public function __toString()
    {
        return $this->getId().' '.$this->getUserId().' '.$this->content.' '.$this->getX().' '.$this->getY();
    }
}

?>