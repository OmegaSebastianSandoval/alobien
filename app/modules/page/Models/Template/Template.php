<?php

/**
 * 
 */
class Page_Model_Template_Template
{

	protected $_view;
	function __construct($view)
	{
		$this->_view = $view;
		$this->_view->selected_lang = isset($_COOKIE['user_lang']) ? $_COOKIE['user_lang'] : 'es';
	}

	public function getContentseccion($seccion)
	{

		$contenidoModel = new Page_Model_DbTable_Contenido();
		$contenidos = [];
		// Obtener contenidos principales de la sección
		$rescontenidos = $contenidoModel->getList(
			"contenido_estado='1' AND contenido_seccion = '$seccion' AND contenido_padre = '0'", 
			"orden ASC"
		);
		
		foreach ($rescontenidos as $key => $contenido) {
			$contenidos[$key] = [
				'detalle' => $contenido,
				'hijos' => $this->buildContentHierarchy($contenidoModel, $contenido->contenido_id, 1)
			];
		}
		$this->_view->contenidos = $contenidos;
		return $this->_view->getRoutPHP("modules/page/Views/template/contenedor.php");
	}

	/**
	 * Construye la jerarquía de contenidos de forma recursiva
	 * @param object $contenidoModel Modelo de contenido
	 * @param string $padreId ID del contenido padre
	 * @param int $nivel Nivel actual de profundidad (para control de recursión)
	 * @param int $maxNivel Nivel máximo permitido (por defecto 6)
	 * @return array Estructura jerárquica de contenidos
	 */
	private function buildContentHierarchy($contenidoModel, $padreId, $nivel = 1, $maxNivel = 6)
	{
		// Prevenir recursión infinita
		if ($nivel > $maxNivel) {
			return [];
		}

		$hijos = $contenidoModel->getList(
			"contenido_estado='1' AND contenido_padre = '$padreId'", 
			"orden ASC"
		);

		if (empty($hijos)) {
			return [];
		}

		$estructura = [];
		foreach ($hijos as $key => $hijo) {
			$estructura[$key] = [
				'detalle' => $hijo,
				'hijos' => $this->buildContentHierarchy($contenidoModel, $hijo->contenido_id, $nivel + 1, $maxNivel)
			];
			// Mantener compatibilidad con el array plano para niveles específicos
			$this->_view->contenidos['hijos_' . $hijo->contenido_id] = $estructura[$key]['hijos'];
		}

		return $estructura;
	}

	public function bannerPrincipalInd($seccion)
	{
		$this->_view->seccionbanner = $seccion;
		$publicidadModel = new Page_Model_DbTable_Publicidad();
		$banners = $publicidadModel->getList("publicidad_seccion = '$seccion' AND publicidad_estado = '1' AND publicidad_padre = '0'", "orden ASC");
		foreach($banners as $banner){
			$subBanners = $publicidadModel->getList("publicidad_seccion = '$seccion' AND publicidad_estado = '1' AND publicidad_padre = '$banner->publicidad_id'", "orden ASC");
			$banner->subBanners = $subBanners;
		}
		$this->_view->banners = $banners;
		// print_r($banners);
		return $this->_view->getRoutPHP("modules/page/Views/template/bannerprincipalind.php");
	}
	public function banner($seccion)
	{
		$this->_view->seccionbanner = $seccion;
		$publicidadModel = new Page_Model_DbTable_Publicidad();
		$this->_view->banners = $publicidadModel->getList("publicidad_seccion = '$seccion' AND publicidad_estado = '1'", "orden ASC");

		return $this->_view->getRoutPHP("modules/page/Views/template/bannerinternas.php");
	}
}