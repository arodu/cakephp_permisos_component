<?php
class PermisoHelper extends AppHelper {

	public $helpers = array('Html');
  private $Component = null;
	private $permisosBuffer = null;


	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
    $this->Component = $settings['component'];
	}

  public function link($title, $url = array(), $options = array(), $confirmMessage = false) {
    if($this->Component->isAuthorized($url,false)){
      return $this->Html->link($title, $url, $options, $confirmMessage);
    }else{
      return '';
    }
  }

	//public function codeStart($permiso = array(), $options = array()){
	//	$this->permisosBuffer[] = array('permiso'=>$permiso, 'options'=>$options);
	//	return ob_start();
	//}

	//public function codeEnd(){
	//	debug($this->permisosBuffer);
	//	//return ob_get_contents();
	//	 //$current = array_pop($this->permisosBuffer);
	//	 //if($this->Component->isAuthorized($current['permiso'],false)){
	//	 //}
	//	 //debug(ob_get_contents());
	//	 //ob_end_clean();
	//}

}
