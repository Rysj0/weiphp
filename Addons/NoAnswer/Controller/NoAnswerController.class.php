<?php

namespace Addons\NoAnswer\Controller;

use Think\ManageBaseController;

class NoAnswerController extends ManageBaseController {
	public function config() {
		$normal_tips = '注意：用户回复的内容优先匹配设有关键词的内容，如自动回复，匹配不上才转到本客服';
		$this->assign ( 'normal_tips', $normal_tips );
		
		$this->getModel ();
		$map ['name'] = MODULE_NAME;
		$addon = M ( 'addons' )->where ( $map )->find ();
		if (! $addon)
			$this->error ( '400300:插件未安装' );
		$addon_class = get_addon_class ( $addon ['name'] );
		if (! class_exists ( $addon_class ))
			trace ( "插件{$addon['name']}无法实例化,", 'ADDONS', 'ERR' );
		$data = new $addon_class ();
		$addon ['addon_path'] = $data->addon_path;
		$addon ['custom_config'] = $data->custom_config;
		$this->meta_title = '设置插件-' . $data->info ['title'];
		$db_config = D ( 'Common/AddonConfig' )->get ( MODULE_NAME );
		$addon ['config'] = include $data->config_file;
		if (IS_POST) {
			foreach ( $addon ['config'] as $k => $vv ) {
				if ($vv ['type'] == 'material') {
					$_POST ['config'] [$k] = $_POST [$k];
				}
			}
			$flag = D ( 'Common/AddonConfig' )->set ( MODULE_NAME, I ( 'config' ) );
			
			if ($flag !== false) {
				$this->success ( '保存成功', Cookie ( '__forward__' ) );
			} else {
				$this->error ( '400301:保存失败' );
			}
		}
		if ($db_config) {
			foreach ( $addon ['config'] as $key => $value ) {
				! isset ( $db_config [$key] ) || $addon ['config'] [$key] ['value'] = $db_config [$key];
			}
		}
		$this->assign ( 'data', $addon );
		$this->display ();
	}
}
