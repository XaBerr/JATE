<?php
	class WebApp extends Module {
		protected $pages;
		protected $defaultPage;
		public $currentPage;
		public $connection;
		public function __construct() {
			parent::__construct();
			$this->pages = [];
			$this->defaultPage	= ["Page404",[]];
			$this->currentPage	= null;
			$this->connection		= null;
		}
		public function addPage( $_page ) {
			$path		= "";
			$class	= "";
			$param	= [];
			if(is_array($_page)) {
				$path		= $_page[0];
				$class	= $_page[1];
				if(isset($_page[2]))
					$param = $_page[2];
			} else {
				$path		= $_page;
				$class	= $_page;
			}
			$this->pages[$path] = [$class,$param];
		}
		public function addPages( $_pages ) {
			foreach ($_pages as $i)
				$this->addPage($i);
		}
		public function fetchPage( $_stack ) {
			$parameters = [];
			$temp	= $this->defaultPage;
			$variables = null;
			foreach ($this->pages as $key => $value) {
				$variables = $this->pathSeeker(explode("/", $key), $_stack);
				if(is_array($variables)) {
					$temp = $value;
					$parameters = $variables;
					break;
				}
			}
			if( isset($temp[1]) && is_array($temp[1]) )
				$temp[1] = array_merge($temp[1], $parameters);
			else
				$temp[1] = $parameters;
			$this->currentPage = new $temp[0]($temp[1]);
			return $this->currentPage;
		}
		public function setDefaultPage( $_page ) {
			$this->defaultPage = $_page;
		}
		public function draw() {
			$this->currentPage->uniforma();
			$gui = new GUI();
			$gui->init($this->currentPage);
			$gui->draw($this->currentPage->data["template"]);
		}
		public function pathSeeker( $_path, $_url ) {
			$urlLength = count($_url);
			$cont = 0;
			$variables = [];
			$pathLength = count($_path);
			if($urlLength == $pathLength) {
				while($cont < $urlLength) {
					if( $_path[$cont] == $_url[$cont] )
						$cont++;
					else if( strpos($_path[$cont], "\$") !== false ) {
						$variables[str_replace('$', "", $_path[$cont])] = $_url[$cont];
						$cont++;
					} else break;
				}
				if($cont == $urlLength)
					return $variables;
			}
			return null;
		}
	}
?>
