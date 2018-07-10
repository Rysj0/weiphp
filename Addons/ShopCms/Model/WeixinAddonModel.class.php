<?php
        	
namespace Addons\ShopCms\Model;
use Home\Model\WeixinModel;
        	
/**
 * ShopCms的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'ShopCms' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	