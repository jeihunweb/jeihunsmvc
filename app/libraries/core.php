<?php 

  /*
  * App Core Class
  * Creates URL and loads Core controller
  * URL FORMAT - /controller/method/params
  */

class Core {
	protected $currentController = 'Pages';
	protected $currentMethod = 'index';
	protected $params = [];

	public function __construct() {
		$url = $this->getUrl();
		//Look in controllers for the first value
		if(file_exists('../app/controllers/' . ucwords($url[0]) . '.php')){
			//If exists, set as controller 
			$this->currentController = ucwords($url[0]);
			//Unset 0 Index
			unset($url[0]);
		}
		//Require the controller
			require_once '../app/controllers/' . $this->currentController . '.php';
		//Instantiate controller class
		$this->currentController = new $this->currentController;

		//Check for second part of the url
		if(isset($url[1])) {
		//check to see if the method exists in controller 
		if(method_exists($this->currentController, $url[1])) {
			$this->currentMethod = $url[1];
			//Unset 1 index
			unset($url[1]);
		}
	}

		// get params
		$this->params = $url ? array_values($url) : [];

		//Call a callback with array of params
		call_user_func_array([$this->currentController,
			$this->currentMethod], $this->params);

	}
	public function getUrl(){
		if(isset($_GET['url'])){
			//remove character from right side of string
			$url = rtrim($_GET['url'], '/');
			//checking the url is actually a url
			$url = filter_var($url, FILTER_SANITIZE_URL);
			//breaking string into an array
			$url = explode('/', $url);
			return $url;
		}
	}

}



 ?>