<?php

namespace Addons\Coupon\Controller;

use Think\WapBaseController;

class WapController extends WapBaseController {
	function _initialize() {
		parent::_initialize ();
		if (ACTION_NAME == 'lists' || ACTION_NAME == 'personal') {
			$uid = $this->mid;
			$token = get_token ();
			// 获取通知数
			$key = 'cardnotic_' . $token . '_' . $uid;
			$rrs = S ( $key );
			if ($rrs === false) {
				$beforetime = 7 * 24 * 60 * 60;
				$thetime = strtotime ( time_format ( time (), 'Y-m-d' ) ) - $beforetime;
				$cmap ['token'] = $token;
				$cmap ['uid'] = $uid;
				$cardMember = M ( 'card_member' )->where ( $cmap )->find ();
				if (! empty ( $cardMember ['level'] )) {
					$map ['cTime'] = array (
							'egt',
							$thetime 
					);
					$map ['token'] = $token;
					
					$notices = M ( 'card_notice' )->where ( $map )->select ();
					foreach ( $notices as $v ) {
						$gradeArr = explode ( ',', $v ['grade'] );
						if ($v ['to_uid'] == 0) {
							if (in_array ( 0, $gradeArr ) || in_array ( $cardMember ['level'], $gradeArr )) {
								$data [] = $v;
							}
						} else if ($v ['to_uid'] == $uid) {
							$data [] = $v;
						}
					}
					$rrs = count ( $data );
					S ( $key, $rrs );
				}
			} else if ($rrs <= 0) {
				$rrs = '';
			}
			$this->assign ( 'notice_num', $rrs );
		}
	}
	
	// 开始领取页面
	function prev() {
		$target_id = I ( 'id', 0, 'intval' );
		$list = D ( 'Common/SnCode' )->getMyList ( $this->mid, $target_id );
		if (! empty ( $_GET ['sn_id'] )) {
			$sn_id = I ( 'sn_id' );
			foreach ( $list as $v ) {
				if ($v ['id'] == $sn_id) {
					$res = $v;
				}
			}
			$list = array (
					$res 
			);
		}
		
		$this->assign ( 'my_sn_list', $list );
		
		$data = $this->_detail ( false, $list [0] );
		$tpl = isset ( $_GET ['has_get'] ) ? 'has_get' : 'prev';
		
		$info = get_followinfo ( $this->mid );
		$config = getAddonConfig ( 'UserCenter' );
		if ($config ['need_bind'] && (empty ( $info ['mobile'] ) || empty ( $info ['truename'] ))) {
			Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
			$url = addons_url ( 'UserCenter://Wap/bind_prize_info' );
		} else {
			$url = U ( 'set_sn_code', array (
					'id' => $data ['id'] 
			) );
		}
		
		$this->assign ( 'url', $url );
		
		$this->display ( $tpl );
	}
	function qr_code() {
		$id = I ( 'sn_id' );
		$map2 ['uid'] = $this->mid;
		
		$info = D ( 'Common/SnCode' )->getInfoById ( $id );
		if ($info ['uid'] != $this->mid) {
			$this->error ( '400144:非法访问' );
		}
		
		$this->assign ( 'info', $info );
		// dump ( $info );
		
		$this->display ();
	}
	function do_pay() {
		$cTime = I ( 'cTime', 0, 'intval' );
		
		if ($cTime > 0 && (NOW_TIME * 1000 - $cTime) > 30000) {
			$this->error ( '400145:二维码已过期' );
		}
		$id = I ( 'sn_id' );
		$info = D ( 'Common/SnCode' )->getInfoById ( $id );
		if (empty ( $info )) {
			$this->error ( '400146:扫描的二维码不对' );
		}
		
		$this->assign ( 'info', $info );
		$data = D ( 'Coupon' )->getInfo ( $info ['target_id'] );
		$data = $this->dealOverTime ( $data, $info ['cTime'] );
		$this->assign ( 'coupon', $data );
		// dump ( $info );
		if ($data ['over_time'] < NOW_TIME) {
			$this->error ( '400160:该券已过期' );
		}
		
		$check = D ( 'Addons://Servicer/Servicer' )->checkRule ( $this->mid, 2 );
		if (! $check) {
			$this->error ( '400147:你需要工作授权才能核销' );
		}
		
		$this->display ();
	}
	function do_pay_ok() {
		$msg = '';
		$dao = D ( 'Common/SnCode' );
		
		$id = I ( 'sn_id' );
		$info = $dao->getInfoById ( $id );
		$data = D ( 'Coupon' )->getInfo ( $info ['target_id'] );
		$data = $this->dealOverTime ( $data, $info ['cTime'] );
		$this->assign ( 'coupon', $data );
		$check = D ( 'Addons://Servicer/Servicer' )->checkRule ( $this->mid, 2 );
		if (! $check) {
			$msg = '你需要工作授权才能核销';
		}
		
		if (empty ( $msg )) {
			if ($info ['is_use']) {
				$msg = '该优惠券已经使用过，请不要重复使用';
			}
		}
		
		if (empty ( $msg )) {
			$info ['is_use'] = $save ['is_use'] = 1;
			$info ['use_time'] = $save ['use_time'] = time ();
			$save ['admin_uid'] = $this->mid;
			
			$res = $dao->update ( $id, $save );
			
			$map ['is_use'] = 1;
			$map ['target_id'] = $info ['target_id'];
			$map ['addon'] = 'Coupon';
			$data ['use_count'] = $save2 ['use_count'] = intval ( $dao->where ( $map )->count () );
			
			D ( 'Coupon' )->update ( $info ['target_id'], $save2 );
			
			$msg = '核销成功';
		}
		
		$this->assign ( 'msg', $msg );
		$this->assign ( 'conpon', $data );
		
		$this->display ();
	}
	// 过期提示页面
	function over() {
		$this->_detail ();
		$this->display ();
	}
	function show_error($error, $info = '') {
		empty ( $info ) && $info = D ( 'Coupon' )->getInfo ( $info );
		$this->assign ( 'info', $info );
		
		$this->assign ( 'error', $error );
		S ( 'set_sn_code_lock', 0 ); // 解锁
		$this->display ( 'over' );
		exit ();
	}
	function showIndex() {
		$id = I ( 'id', 0, 'intval' );
		
		$sn_id = I ( 'sn_id', 0, 'intval' );
		
		$list = D ( 'Common/SnCode' )->getMyList ( $this->mid, $id );
		
		if ($sn_id > 0) {
			foreach ( $list as $vo ) {
				$my_count += 1;
				$vo ['id'] == $sn_id && $sn = $vo;
			}
		} else {
			$sn = $list [0];
		}
		
		$maps ['coupon_id'] = $id;
		$list = M ( 'coupon_shop_link' )->where ( $maps )->select ();
		$shop_ids = getSubByKey ( $list, 'shop_id' );
		if (! empty ( $shop_ids )) {
			$map_shop ['id'] = array (
					'in',
					$shop_ids 
			);
			$shop_list = M ( 'coupon_shop' )->where ( $map_shop )->select ();
			$this->assign ( 'shop_list', $shop_list );
		}
		$this->assign ( 'sn', $sn );
		// dump($sn);
		
		$this->_detail ( $my_count, $sn );
		
		$this->display ();
	}
	function _detail($my_count = false, $sn = []) {
		$id = I ( 'id', 0, 'intval' );
		$data = D ( 'Coupon' )->getInfo ( $id );
		if (isset ( $sn ['cTime'] )) {
			$data = $this->dealOverTime ( $data, $sn ['cTime'] );
		}
		$this->assign ( 'data', $data );
		// dump ( $data );
		
		// 领取条件提示
		$follower_condtion [1] = '关注后才能领取';
		$follower_condtion [2] = '用户绑定后才能领取';
		$follower_condtion [3] = '领取会员卡后才能领取';
		$tips = condition_tips ( $data ['addon_condition'] );
		
		$condition = array ();
		$data ['max_num'] > 0 && $condition [] = '每人最多可领取' . $data ['max_num'] . '张';
		$data ['credit_conditon'] > 0 && $condition [] = '积分中金币值达到' . $data ['credit_conditon'] . '分才能领取';
		$data ['credit_bug'] > 0 && $condition [] = '领取后需扣除金币值' . $data ['credit_bug'] . '分';
		isset ( $follower_condtion [$data ['follower_condtion']] ) && $condition [] = $follower_condtion [$data ['follower_condtion']];
		empty ( $tips ) || $condition [] = $tips;
		
		$this->assign ( 'condition', $condition );
		// dump ( $condition );
		
		$this->_get_error ( $data, $my_count );
		
		return $data;
	}
	function _get_error($data, $my_count = false) {
		$error = '';
		
		// 抽奖记录
		$my_count === false && $my_count = count ( D ( 'Common/SnCode' )->getMyList ( $this->mid, $data ['id'] ) );
		
		// 权限判断
		$follow = get_followinfo ( $this->mid );
		// $is_admin = is_login ();
		
		if (! empty ( $data ['end_time'] ) && $data ['end_time'] <= NOW_TIME) {
			$error = '您来晚啦';
		} else if ($data ['max_num'] > 0 && $data ['max_num'] <= $my_count) {
			$error = '您的领取名额已用完啦';
		}
		
		// if ($data ['follower_condtion'] > intval ( $follow ['status'] ) && ! $is_admin) {
		// switch ($data ['follower_condtion']) {
		// case 1 :
		// $error = '关注后才能领取';
		// break;
		// case 2 :
		// $error = '用户绑定后才能领取';
		// break;
		// case 3 :
		// $error = '领取会员卡后才能领取';
		// break;
		// }
		// } else if ($data ['credit_conditon'] > intval ( $follow ['score'] ) && ! $is_admin) {
		// $error = '您的金币值不足';
		// } else if ($data ['credit_bug'] > intval ( $follow ['score'] ) && ! $is_admin) {
		// $error = '您的金币值不够扣除';
		// } else if (! empty ( $data ['addon_condition'] )) {
		// addon_condition_check ( $data ['addon_condition'] ) || $error = '权限不足';
		// }
		$this->assign ( 'error', $error );
		// dump ( $error );
		
		return $error;
	}
	
	// 记录中奖数据到数据库
	function set_sn_code() {
		$id = $param ['id'] = I ( 'id', 0, 'intval' );
		
		$lock = S ( 'set_sn_code_lock' );
		if ($lock == 1 || isset ( $_GET ['format'] )) {
			$param ['publicid'] = I ( 'publicid' );
			$param ['rand'] = NOW_TIME . rand ( 10, 99 );
			
			$this->error ( '400149:排队领取中', U ( 'set_sn_code', $param ) );
		} else {
			S ( 'set_sn_code_lock', 1, 30 );
		}
		
		$follow = get_followinfo ( $this->mid );
		$config = getAddonConfig ( 'UserCenter' );
		// dump ( $config );
		// S ( 'set_sn_code_lock', 0 );
		// exit ();
		if ($config ['need_bind'] && ! (defined ( 'IN_WEIXIN' ) && IN_WEIXIN) && ! isset ( $_GET ['is_stree'] ) && $this->mid != 1 && (empty ( $follow ['mobile'] ) || empty ( $follow ['truename'] ))) {
			Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
			S ( 'set_sn_code_lock', 0 ); // 解锁
			redirect ( addons_url ( 'UserCenter://Wap/bind_prize_info' ) );
		}
		
		$info = D ( 'Coupon' )->getInfo ( $id );
		$member = explode ( ',', $info ['member'] );
		if (! in_array ( 0, $member )) {
			// 判断是否为会员
			$card_map ['token'] = get_token ();
			$card_map ['uid'] = $this->mid;
			$card = M ( 'card_member' )->where ( $card_map )->find ();
			if ($card ['member'] != 0 && ! in_array ( - 1, $member ) && ! in_array ( $card ['level'], $member )) {
				$msg = '您的等级未满足，还不能领取该优惠券！';
				$this->show_error ( $msg, $info );
			}
		}
		
		if ($info ['collect_count'] >= $info ['num']) {
			$msg = empty ( $info ['empty_prize_tips'] ) ? '您来晚了，优惠券已经领取完' : $info ['empty_prize_tips'];
			$this->show_error ( $msg, $info );
		}
		
		if (! empty ( $info ['start_time'] ) && $info ['start_time'] > NOW_TIME) {
			$msg = empty ( $info ['start_tips'] ) ? '活动在' . time_format ( $info ['start_time'] ) . '开始，请到时再来' : $info ['start_tips'];
			$this->show_error ( $msg, $info );
		}
		if (! empty ( $info ['end_time'] ) && $info ['end_time'] < NOW_TIME) {
			$msg = empty ( $info ['end_tips'] ) ? '您来晚了，活动已经结束' : $info ['end_tips'];
			$this->show_error ( $msg, $info );
		}
		
		$list = D ( 'Common/SnCode' )->getMyList ( $this->mid, $id );
		$this->assign ( 'my_sn_list', $list );
		
		$my_count = count ( $list );
		$error = $this->_get_error ( $info, $my_count );
		if (! empty ( $error )) {
			S ( 'set_sn_code_lock', 0 ); // 解锁
			$this->display ( 'over' );
			exit ();
		}
		
		$data ['target_id'] = $id;
		$data ['uid'] = $this->mid;
		$data ['addon'] = 'Coupon';
		$data ['sn'] = uniqid ();
		$data ['cTime'] = NOW_TIME;
		$data ['token'] = $info ['token'];
		$res = D ( 'Common/SnCode' )->delayAdd ( $data );
		S ( 'set_sn_code_lock', 0 ); // 解锁
		                             
		// 扣除积分
		if (! empty ( $info ['credit_bug'] )) {
			$credit ['score'] = $info ['credit_bug'];
			$credit ['experience'] = 0;
			add_credit ( 'coupon_credit_bug', 5, $credit );
		}
		if (isset ( $_GET ['is_stree'] ))
			return false;
		
		redirect ( U ( 'showIndex', $param ) );
	}
	function coupon_detail() {
		$id = $param ['id'] = I ( 'id', 0, 'intval' );
		$info = D ( 'Coupon' )->getInfo ( $id );
		$this->assign ( 'info', $info );
		$this->display ();
	}
	function store_list() {
		$id = $param ['id'] = I ( 'id', 0, 'intval' );
		$maps ['coupon_id'] = $id;
		$list = M ( 'coupon_shop_link' )->where ( $maps )->select ();
		$shop_ids = getSubByKey ( $list, 'shop_id' );
		if (! empty ( $shop_ids )) {
			$map_shop ['id'] = array (
					'in',
					$shop_ids 
			);
			$shop_list = M ( 'coupon_shop' )->where ( $map_shop )->select ();
			foreach ( $shop_list as &$s ) {
				$gpsArr = wp_explode ( $s ['gps'] );
				$s ['gps'] = $gpsArr [0] . ',' . $gpsArr [1];
			}
			$this->assign ( 'shop_list', $shop_list );
		}
		$this->display ();
	}
	function get_sn_status() {
		$id = I ( 'sn_id', 0, 'intval' );
		$is_use = D ( 'Common/SnCode' )->getInfoById ( $id, 'is_use' );
		echo $is_use;
	}
	function index() {
		$param ['id'] = $id = I ( 'id' );
		
		// 已领取的直接进入详情页面，不需要再领取（TODO：仅为不需要多次领取的客户使用）
		$mylist = D ( 'Common/SnCode' )->getMyList ( $this->mid, $id );
		if (! empty ( $mylist [0] )) {
			$param ['sn_id'] = $mylist [0] ['id'];
			redirect ( U ( 'showIndex', $param ) );
		}
		
		$info = $public_info = get_token_appinfo ();
		$param ['publicid'] = $info ['id'];
		
		$url = addons_url ( "Coupon://Wap/set_sn_code", $param );
		$this->assign ( 'jumpURL', $url );
		
		$maps ['coupon_id'] = $id;
		$list = M ( 'coupon_shop_link' )->where ( $maps )->select ();
		$shop_ids = getSubByKey ( $list, 'shop_id' );
		if (! empty ( $shop_ids )) {
			$map_shop ['id'] = array (
					'in',
					$shop_ids 
			);
			$shop_list = M ( 'coupon_shop' )->where ( $map_shop )->select ();
			$this->assign ( 'shop_list', $shop_list );
		}
		
		$info = D ( 'Coupon' )->getInfo ( $id );
		$this->assign ( 'info', $info );
		$this->assign ( 'public_info', $public_info );
		
		$this->display ();
	}
	// 统一处理优惠券有效期
	private function dealOverTime($coupon, $get_time) {
		if ($coupon ['use_time_type'] == 1) {
			$coupon ['over_time'] = $get_time + $coupon ['use_time_limit'] * 86400;
		}
		return $coupon;
	}
	function personal() {
		$isUse = I ( 'get.use' );
		if ($isUse != '') {
			$can_use = $isUse;
		}
		
		$list = D ( 'Common/SnCode' )->getMyAll ( $this->mid, 'Coupon', false, '', $can_use );
		if (! empty ( $list )) {
			foreach ( $list as $k => $v ) {
				$coupon = ( array ) D ( 'Addons://Coupon/Coupon' )->getInfo ( $v ['target_id'] );
				$coupon = $this->dealOverTime ( $coupon, $v ['cTime'] );
				if ($coupon && $coupon ['over_time'] > NOW_TIME) {
					$key = $v ['is_use'] == 0 ? 0 : 1;
					$v ['sn_id'] = $v ['id'];
					$res [$key] [] = array_merge ( $v, $coupon );
				}
			}
		}
		// dump ( $list );
		$this->assign ( 'list', $res );
		
		$this->display ();
	}
	function sn() {
		$map ['token'] = get_token ();
		$map ['target_id'] = I ( 'coupon_id' );
		$map ['addon'] = 'Coupon';
		
		$key = I ( 'search' );
		if (! empty ( $key )) {
			$map ['sn'] = array (
					'like',
					'%' . $key . '%' 
			);
		}
		$is_use = I ( 'is_use' );
		if ($is_use == 1) {
			$map ['is_use'] = $is_use;
		}
		
		$code = M ( 'sn_code' )->where ( $map )->selectPage ();
		// dump($code);
		$this->assign ( $code );
		$this->assign ( 'is_use', $map ['is_use'] );
		
		$this->display ();
	}
	function sn_set() {
		$map ['id'] = I ( 'id' );
		$map ['token'] = get_token ();
		$data = M ( 'sn_code' )->where ( $map )->find ();
		if (! $data) {
			$this->error ( '400150:数据不存在' );
		}
		
		if ($data ['is_use']) {
			$data ['is_use'] = 0;
			$data ['use_time'] = '';
		} else {
			$data ['is_use'] = 1;
			$data ['use_time'] = time ();
			$data ['admin_uid'] = $this->mid;
		}
		
		$res = M ( 'sn_code' )->where ( $map )->save ( $data );
		if ($res) {
			if ($data ['addon'] == 'Coupon') {
				$map2 ['target_id'] = $maps ['id'] = $data ['target_id'];
				$map2 ['addon'] = 'Coupon';
				
				$info = M ( 'sn_code' )->where ( $map2 )->field ( 'sum(is_use) as use_count,count(id) as num' )->find ();
				
				$save ['use_count'] = $info ['use_count'];
				$save ['collect_count'] = $info ['num'];
				M ( 'coupon' )->where ( $maps )->save ( $save );
			}
			$this->success ( '设置成功' );
		} else {
			$this->error ( '400151:设置失败' );
		}
	}
	function lists() {
		// 更新延时插入的缓存
		D ( 'Common/SnCode' )->delayAdd ();
		
		$dao = D ( 'Coupon' );
		$page = I ( 'p', 1, 'intval' ); // 默认显示第一页数据
		$order = 'id desc';
		$model = $this->getModel ();
		// 解析列表规则
		$list_data = $this->_list_grid ( $model );
		
		// 搜索条件
		$row = empty ( $model ['list_row'] ) ? 20 : $model ['list_row'];
		
		$map = $dao->getUnCollectWhere ( $this->mid, 0 );
		$list = $dao->field ( 'id' )->where ( $map )->order ( $order )->page ( $page, $row )->select ();
		$datas = [ ];
		foreach ( $list as $d ) {
			$datas [] = $dao->getInfo ( $d ['id'] );
		}
		
		/* 查询记录总数 */
		$count = $dao->where ( $map )->count ();
		$list_data ['list'] = $datas;
		
		// 分页
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$list_data ['_page'] = $page->show ();
		}
		$this->assign ( $list_data );
		
		$this->display ();
	}
	// 一键领取全部优惠券
	function collect_all() {
		$config = getAddonConfig ( 'UserCenter' );
		$follow = get_followinfo ( $this->mid );
		if ($config ['need_bind'] && ! (defined ( 'IN_WEIXIN' ) && IN_WEIXIN) && ! isset ( $_GET ['is_stree'] ) && $this->mid != 1 && (empty ( $follow ['mobile'] ) || empty ( $follow ['truename'] ))) {
			Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
			redirect ( addons_url ( 'UserCenter://Wap/bind_prize_info' ) );
		}
		
		$dao = D ( 'Coupon' );
		$map = $dao->getUnCollectWhere ( $this->mid );
		$lists = $dao->where ( $map )->select ();
		foreach ( $lists as $info ) {
			$data ['target_id'] = $info ['id'];
			$data ['uid'] = $this->mid;
			$data ['addon'] = 'Coupon';
			$data ['sn'] = uniqid ();
			$data ['cTime'] = NOW_TIME;
			$data ['token'] = $info ['token'];
			$res = D ( 'Common/SnCode' )->delayAdd ( $data );
		}
		redirect ( U ( 'personal' ) );
	}
}
