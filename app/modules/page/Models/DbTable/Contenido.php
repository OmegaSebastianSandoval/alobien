<?php

/**
 * 
 */
class Page_Model_DbTable_Contenido extends Db_Table
{
	protected $_name = 'contenido';
	protected $_id = 'contenido_id';

	public function getList($filters = '', $order = '')
	{
		$selected_lang = isset($_COOKIE['user_lang']) ? $_COOKIE['user_lang'] : 'es';
		
		// Construir la selección de columnas según el idioma
		$columns = '*';
		if ($selected_lang == 'en') {
			$columns = '*, contenido_descripcion_en as contenido_descripcion';
		}
		
		$filter = '';
		if ($filters != '') {
			$filter = ' WHERE ' . $filters;
		}
		$orders = "";
		if ($order != '') {
			$orders = ' ORDER BY ' . $order;
		}
		$select = 'SELECT ' . $columns . ' FROM ' . $this->_name . ' ' . $filter . ' ' . $orders;
		$res = $this->_conn->query($select)->fetchAsObject();
		return $res;
	}
}
