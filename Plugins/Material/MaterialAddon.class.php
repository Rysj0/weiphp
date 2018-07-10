<?php

namespace Plugins\Material;

use Common\Controller\Plugin;

/**
 * 素材选择插件
 *
 * @author 凡星
 */
class MaterialAddon extends Plugin {
	public $info = array (
			'name' => 'Material',
			'title' => '素材选择',
			'description' => '支持动态从素材库里选择素材',
			'status' => 1,
			'author' => '凡星',
			'version' => '0.1',
			'has_adminlist' => 0,
			'type' => 0 
	);
	public function install() {
		return true;
	}
	public function uninstall() {
		return true;
	}
	
	/**
	 * 编辑器挂载的后台文档模型文章内容钩子
	 *
	 * table=addons&type=1&value_field=name&title_field=title&order=id desc
	 */
	public function material($data) {
		$data ['default_value'] = $data ['value'];
		isset ( $data ['default_type'] ) || $data ['default_type'] = '';
		isset ( $data ['default_id'] ) || $data ['default_id'] = '';
		isset ( $data ['text_id'] ) || $data ['text_id'] = '';
		isset ( $data ['news_id'] ) || $data ['news_id'] = '';
		isset ( $data ['img_id'] ) || $data ['img_id'] = '';
		isset ( $data ['voice_id'] ) || $data ['voice_id'] = '';
		isset ( $data ['video_id'] ) || $data ['video_id'] = '';
		if (! empty ( $data ['value'] )) {
			$valArr = wp_explode ( $data ['value'], ':' );
			$data [$valArr [0] . '_id'] = isset ( $valArr [1] ) ? $valArr [1] : '';
		}
		$this->assign ( $data );
		$this->display ( 'content' );
	}
}