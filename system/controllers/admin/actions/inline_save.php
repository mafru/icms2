<?php

class actionAdminInlineSave extends cmsAction {

    public function run($table = null, $item_id = null) {

        header('X-Frame-Options: DENY');

        if (!$this->request->isAjax()) { return cmsCore::error404(); }

		if (!$item_id || !$table || !is_numeric($item_id) || $this->validate_regexp('/^([a-z0-9\_{}]*)$/', urldecode($table)) !== true){
			return $this->cms_template->renderJSON(array(
				'error' => LANG_ERROR.' #validate'
			));
		}

        $data = $this->request->get('data', []);
        if(!$data){
			return $this->cms_template->renderJSON(array(
				'error' => LANG_ERROR.' #empty data'
			));
        }

		if (!$this->model->db->isTableExists($table)){
			return $this->cms_template->renderJSON(array(
				'error' => LANG_ERROR.' #table'
			));
		}

        $i = $this->model->getItemByField($table, 'id', $item_id);
		if (!$i){
			return $this->cms_template->renderJSON(array(
				'error' => LANG_ERROR.' #404'
			));
		}

        $table_fields = $this->model->db->getTableFieldsTypes($table);

        $_data = [];

        foreach ($data as $field => $value) {
            if(!array_key_exists($field, $i) || is_array($data[$field])){
                unset($data[$field]);
            } else {

                if($value){

                    $table_field_type = $table_fields[$field];
                    if(in_array($table_field_type, ['tinyint','smallint','mediumint','int','bigint'])){
                        $_data[$field] = $data[$field] = intval($value);
                    } elseif(in_array($table_field_type, ['decimal','float','double'])){
                        $_data[$field] = $data[$field] = floatval(str_replace(',', '.', trim($value)));
                    } else {
                        $data[$field] = strip_tags($value);
                        $_data[$field] = htmlspecialchars($data[$field]);
                    }

                } else {
                    $_data[$field] = $value;
                }

            }
        }

        if(empty($data)){
			return $this->cms_template->renderJSON(array(
				'error' => LANG_ERROR.' #empty data'
			));
        }

        list($data, $_data, $i) = cmsEventsManager::hook('admin_inline_save', array($data, $_data, $i));
        list($data, $_data, $i) = cmsEventsManager::hook('admin_inline_save_'.str_replace(['{','}'], '', $table), array($data, $_data, $i));

		$this->model->update($table, $item_id, $data);

        list($data, $_data, $i) = cmsEventsManager::hook('admin_inline_save_after', array($data, $_data, $i));
        list($data, $_data, $i) = cmsEventsManager::hook('admin_inline_save_after_'.str_replace(['{','}'], '', $table), array($data, $_data, $i));

		return $this->cms_template->renderJSON(array(
			'error'  => false,
			'info'   => LANG_SUCCESS_MSG,
			'values' => $_data
		));

    }

}
