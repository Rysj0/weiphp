<?php

namespace Addons\ShopCms\Controller;
use Think\ManageBaseController;

class ShopCmsController extends ManageBaseController{
	/**	
	* 商品数据Api
	* url=> http://localhost/weiphp/index.php?s=/w16/ShopCms/ShopCms/getGoodsList
	*/
	function getGoodsList(){
		
		$GoodsData = M("shop_cms")->field("id,goodsName,goodsImage,goodsAddress,goodsPrice")->select();
		
		foreach($GoodsData as &$vo){
			$vo['goodsImage'] = get_cover_url($vo['goodsImage']);

			}
		//print_r($GoodsData);
		echo api_return(0,$GoodsData );
		}

//http://localhost/weiphp/index.php?s=/w16/ShopCms/ShopCms/getGoodsId/id/1

	function getGoodsId(){
		$goodsId['id'] = $_GET["id"];
		$GoodsData = M("shop_cms")->where($goodsId)->select();
		
		foreach($GoodsData as &$vo){
			$vo['goodsImage'] = get_cover_url($vo['goodsImage']);
			$vo["production"] = time_format($vo["production"]);
			$ImgsArr = explode(",",$vo['goodsImgs']);
			$GoodsImgArr = [];
			foreach($ImgsArr as $key=>$va){
				$GoodsImgArr[$key] = get_cover_url($va);
				}
			$vo['goodsImgs'] = $GoodsImgArr;
			}
		//print_r($GoodsData);
		echo api_return(0,$GoodsData );
		}

}
