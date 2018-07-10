<?php

namespace Addons\NoAnswer\Model;

use Home\Model\WeixinModel;

/**
 * NoAnswer的微信模型
 */
class WeixinAddonModel extends WeixinModel {
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'NoAnswer' ); // 获取后台插件的配置参数
		if ($config ['data_type'] == 1) { // 人工客服
			$this->transferCustomer ();
		} else {
			$this->material_reply ( $config ['stype'] );
		}
	}
}
        	