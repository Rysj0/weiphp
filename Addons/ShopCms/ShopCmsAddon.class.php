<?php

namespace Addons\ShopCms;
use Common\Controller\Addon;

/**
 * 微信商城插件
 * @author 李哥
 */

    class ShopCmsAddon extends Addon{

        public $info = array(
            'name'=>'ShopCms',
            'title'=>'微信商城',
            'description'=>'微信商城小程序模块',
            'status'=>1,
            'author'=>'李哥',
            'version'=>'1.0',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/ShopCms/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/ShopCms/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }