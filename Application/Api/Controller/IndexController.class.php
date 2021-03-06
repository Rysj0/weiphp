<?php

namespace Api\Controller;

use Think\Controller;

class IndexController extends Controller {
	function index() {
		$plist = M ( 'api_param' )->order ( 'id asc' )->select ();
		$params = [ ];
		foreach ( $plist as $v ) {
			$params [$v ['api_id']] [$v ['param_type']] [] = $v;
		}
		unset ( $plist );
		
		$list = M ( 'api' )->order ( 'id asc' )->select ();
		$typeArr = [ 
				'select' => '查询',
				'add' => '增加',
				'del' => '删除',
				'update' => '更新' 
		];
		foreach ( $list as &$vo ) {
			$vo ['method'] = $vo ['method'] == 1 ? 'GET' : 'POST';
			$vo ['type'] = $typeArr [$vo ['type']];
			if (isset ( $params [$vo ['id']] )) {
				$vo = array_merge ( $params [$vo ['id']], $vo );
			}
		}
		$this->assign ( 'list', $list );
		// dump ( $list );
		// exit ();
		$this->display ();
	}
}

