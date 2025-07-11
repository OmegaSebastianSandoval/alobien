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
	}

	public function getContentseccion($seccion)
	{

		$contenidoModel = new Page_Model_DbTable_Contenido();
		$contenidos = [];
		$rescontenidos = $contenidoModel->getList("contenido_estado='1' AND contenido_seccion = '$seccion' AND contenido_padre = '0' ", "orden ASC");
		foreach ($rescontenidos as $key => $contenido) {
			$contenidos[$key] = [];
			$contenidos[$key]['detalle'] = $contenido;
			$padre = $contenido->contenido_id;
			$hijos = $contenidoModel->getList("contenido_estado='1' AND contenido_padre = '$padre' ", "orden ASC");
			foreach ($hijos as $key2 => $hijo) {
				$padre = $hijo->contenido_id;
				$contenidos[$key]['hijos'][$key2] = [];
				$contenidos[$key]['hijos'][$key2]['detalle'] = $hijo;
				$nietos = $contenidoModel->getList("contenido_padre = '$padre' ", "orden ASC");
				if ($nietos) {
					$contenidos[$key]['hijos'][$key2]['hijos'] = $nietos;
					foreach ($nietos as $key3 => $subnietos) {
						$padre = $subnietos->contenido_id;

						$contenidos[$key]['hijos'][$key2]['hijos'][$key3] = [];
						$contenidos[$key]['hijos'][$key2]['hijos'][$key3]['nietos'] = $subnietos;
						$subnietos2 = $contenidoModel->getList("contenido_padre = '$padre' AND contenido_estado = '1'", "orden ASC");

						if ($subnietos2) {
							$contenidos[$key]['hijos'][$key2]['hijos'][$key3]['subnietos'] = $subnietos2;
							//documentos y carpetas nivel3
							foreach ($subnietos2 as $key4 => $subsubnietos) {

								$padre = $subsubnietos->contenido_id;
								$contenidos[$key]['hijos'][$key2]['hijos'][$key3]['subnietos'][$key4] = [];
								$contenidos[$key]['hijos'][$key2]['hijos'][$key3]['detalle'][$key4]['subsubnietos'] = $subsubnietos;
								$subsubnietos2 = $contenidoModel->getList("contenido_padre = '$padre' AND contenido_estado = '1'", "orden ASC");

								$contenidos['hijos_' . $padre] = $subsubnietos2;

								if ($subsubnietos2) {
									$contenidos[$key]['hijos'][$key2]['hijos'][$key3]['subnietos'][$key4]['subsubnietos'] = $subsubnietos2;
									//documentos y carpetas nivel4
									foreach ($subsubnietos2 as $key5 => $bisnietos) {
										$padre = $bisnietos->contenido_id;
										$contenidos[$key]['hijos'][$key2]['hijos'][$key3]['hijos'][$key4]['subsubnietos'][$key5] = [];
										$contenidos[$key]['hijos'][$key2]['hijos'][$key3]['hijos'][$key4]['detalle'][$key5]['bisnietos'] = $bisnietos;
										$bisnietos = $contenidoModel->getList("contenido_padre = '$padre' AND contenido_estado = '1'", "orden ASC");

										$contenidos['hijos_' . $padre] = $bisnietos;

										if ($bisnietos) {
											//documentos y carpetas nivel5
											$contenidos[$key]['hijos'][$key2]['hijos'][$key3]['hijos'][$key4]['subsubnietos'][$key5]['bisnietos'] = $bisnietos;


											foreach ($bisnietos as $key6 => $bisnietos2) {
												$padre = $bisnietos2->contenido_id;
												$contenidos[$key]['hijos'][$key2]['hijos'][$key3]['hijos'][$key4]['subsubnietos'][$key5]['bisnietos'][$key6] = [];
												$contenidos[$key]['hijos'][$key2]['hijos'][$key3]['hijos'][$key4]['subsubnietos'][$key5]['bisnietos']['detalle'][$key6]['bisnietos2'] = $bisnietos2;
												$bisnietos2 = $contenidoModel->getList("contenido_padre = '$padre' AND contenido_estado = '1'", "orden ASC");

												$contenidos['hijos_' . $padre] = $bisnietos2;

												if ($bisnietos2) {
													//documentos y carpetas nivel6
													$contenidos[$key]['hijos'][$key2]['hijos'][$key3]['hijos'][$key4]['subsubnietos'][$key5]['bisnietos'][$key6]['bisnietos2'] = $bisnietos2;


													foreach ($bisnietos2 as $key7 => $bisnietos3) {
														$padre = $bisnietos3->contenido_id;
														$bisnietos3 = $contenidoModel->getList("contenido_padre = '$padre' AND contenido_estado = '1'", "orden ASC");
														$contenidos['hijos_' . $padre] = $bisnietos3;
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		$this->_view->contenidos = $contenidos;
		return $this->_view->getRoutPHP("modules/page/Views/template/contenedor.php");
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

		return $this->_view->getRoutPHP("modules/page/Views/template/bannerprincipal.php");
	}
}