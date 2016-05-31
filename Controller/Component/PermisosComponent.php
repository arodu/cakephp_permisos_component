<?php
App::uses('Component', 'Controller');
class PermisosComponent extends Component {

	private $permisos = array();

	public $settings = array(
		'fileConfig'=>'permisos',
		'arreglo'=>'Permisos',
		'defaultPublic' => false,
		'userRoot' => false,
		'errorMessage' => '',
	);

	public $components = array('Auth','Session');

	public function __construct(ComponentCollection $collection, $settings = array()){
		$this->settings = array_merge($this->settings, (array)$settings);

		$this->Controller = $collection->getController();
		$this->fileConfig();
		$this->settings['userRoot'] = ( Configure::read('debug') > 0 ? true : $this->settings['userRoot'] );
		$this->Controller->helpers['Permiso'] = array('component'=>$this); // <-- Cargar helper Permiso
		parent::__construct($collection, $this->settings);
	}

	private function fileConfig(){
		Configure::load($this->settings['fileConfig']);
		$this->permisos = Configure::read($this->settings['arreglo']); // Leer Arreglo de Permisos
	}

	public function isAuthorized($current = null, $showError = true){
		if($showError)
			return ( $this->__authorized($current) === true ? true : $this->error());
		else
			return $this->__authorized($current);
	}

	private function __authorized($current = null){

		if($current == null){
			$current = array(
				'controller'=>$this->Controller->params['controller'],
				'action'=>$this->Controller->params['action']
			);
		}

		$userPerfil = $this->recreatePerfil( $this->Auth->user('Perfil'));

		$currentPermisos = $this->getPermiso($current);

		if( $this->settings['userRoot']===true  &&  isset($userPerfil['root'])  &&  $userPerfil['root']===true ){
			// Si el usuario es ROOT tiene acceso
			return true;
		}

		if($currentPermisos == 'public'){  // Si el acceso el publico, el usuario tiene acceso
			return true;
		}elseif(is_array($currentPermisos)){
			foreach ($currentPermisos as $permiso){  // Si el acceso el publico, el usuario tiene acceso
				if($permiso == 'public'){
					return true;
				}
				if(isset($userPerfil[$permiso]) && $userPerfil[$permiso]){ // Si el usuario tiene permiso , el usuario tiene acceso
					return true;
				}
			}
		}
		return false;
	}

	public function hasPermission($perfils = array()){
		$userPerfil = $this->recreatePerfil( $this->Auth->user('Perfil') );
		foreach ($perfils as $perfil) {
			if(@$userPerfil[$perfil]){
				return true;
			}
		}
		return false;
	}

	public function getPermiso($current){
		$permiso = (isset($this->permisos[$current['controller']][$current['action']]) ?  $this->permisos[$current['controller']][$current['action']] : false );

		if($this->settings['defaultPublic'] && $permiso===false){
			return 'public';
		}else{
			return $permiso;
		}
	}

	public function error(){
		throw new ForbiddenException($this->settings['errorMessage']);
	}

	public function recreatePerfil($arrayPerfil){
		$aux = null;
		foreach($arrayPerfil as $perfil) {
			$aux[$perfil['code']] = true;
		}
		return $aux;
	}

	// Decrepated
	public function userInfo(){
		debug('funciton Permisos->userInfo() is decretated');
		if($this->Auth->user()){
			return $this->Session->read('userInfo');
		}else{
			return false;
		}
	}

}


?>
