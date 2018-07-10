<?php

namespace Addons\NoAnswer;

use Common\Controller\Addon;

/**
 * 没回答的回复插件
 *
 * @author 凡星
 */
class NoAnswerAddon extends Addon {
	public $custom_config = 'config.html';
	public $info = array (
			'name' => 'NoAnswer',
			'title' => '微信客服',
			'description' => '注意：用户回复的内容优先匹配设有关键词的内容，如自动回复，匹配不上才转到本客服',
			'status' => 1,
			'author' => '凡星',
			'version' => '0.1',
			'has_adminlist' => 0 
	);
	public function install() {
		return true;
	}
	public function uninstall() {
		return true;
	}
	
	// 实现的weixin钩子方法
	public function weixin($param) {
	}
}