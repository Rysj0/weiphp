<?php
/**
 * ShopCms数据模型
 */
class ShopCmsTable {
	// 数据表模型配置
	public $config = [
			'name' => 'shop_cms',
			'title' => '商品详情',
			'search_key' => '',
			'add_button' => 1,
			'del_button' => 1,
			'search_button' => 1,
			'check_all' => 1,
			'list_row' => 20,
			'addon' => 'ShopCms'
	];
	
	// 列表定义
	public $list_grid = [
			'goodsName' => [
					'title' => '商品名称',
					'come_from' => 0,
					'width' => '',
					'is_sort' => 0,
					'name' => 'goodsName',
					'function' => '',
					'href' => [ ]
			],
			'goodsImage' => [
					'title' => '缩略图',
					'come_from' => 0,
					'width' => '',
					'is_sort' => 0,
					'name' => 'goodsImage',
					'function' => '',
					'href' => [ ]
			],
			'goodsOldPrice' => [
					'title' => '商品旧价格',
					'come_from' => 0,
					'width' => '',
					'is_sort' => 0,
					'name' => 'goodsOldPrice',
					'function' => '',
					'href' => [ ]
			],
			'urls' => [
					'title' => '商品名称',
					'come_from' => 1,
					'width' => '',
					'is_sort' => 0,
					'href' => [
							'0' => [
									'title' => '编辑',
									'url' => '[EDIT]'
							],
							'1' => [
									'title' => '删除',
									'url' => '[DELETE]'
							],
							'2' => [
									'title' => '预览',
									'url' => 'preview?id=[\'id\']'
							]
					],
					'name' => 'urls',
					'function' => ''
			]
	];
	
	// 字段定义
	public $fields = [
			'goodsName' => [
					'title' => '商品名称',
					'type' => 'string',
					'field' => 'varchar(255) NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'goodsImage' => [
					'title' => '缩略图',
					'type' => 'picture',
					'field' => 'int(10) UNSIGNED NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'goodsImgs' => [
					'title' => '商品图片',
					'type' => 'mult_picture',
					'field' => 'varchar(255) NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'goodsAddress' => [
					'title' => '地址',
					'type' => 'string',
					'field' => 'varchar(255) NULL',
					'is_show' => 1,
					'is_must' => 0
			],
			'goodsPrice' => [
					'title' => '商品价格',
					'type' => 'num',
					'field' => 'int(10) NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'goodsOldPrice' => [
					'title' => '商品旧价格',
					'type' => 'num',
					'field' => 'int(10) NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'iscollect' => [
					'title' => '收藏',
					'type' => 'bool',
					'field' => 'tinyint(2) NULL',
					'extra' => 'true
false',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'goodsContent' => [
					'title' => '商品描述',
					'type' => 'textarea',
					'field' => 'text NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'brand' => [
					'title' => '品牌',
					'type' => 'string',
					'field' => 'varchar(255) NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'yieldly' => [
					'title' => '产地',
					'type' => 'string',
					'field' => 'varchar(255) NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'size' => [
					'title' => '净含量',
					'type' => 'string',
					'field' => 'varchar(255) NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'times' => [
					'title' => '保质期',
					'type' => 'string',
					'field' => 'varchar(255) NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'production' => [
					'title' => '生产日期',
					'type' => 'date',
					'field' => 'int(10) NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'savings' => [
					'title' => '储蓄方法',
					'type' => 'string',
					'field' => 'varchar(255) NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'apply' => [
					'title' => '适用人群',
					'type' => 'string',
					'field' => 'varchar(255) NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'eat' => [
					'title' => '食用方法',
					'type' => 'string',
					'field' => 'varchar(255) NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'cozy' => [
					'title' => '温馨提示',
					'type' => 'string',
					'field' => 'varchar(255) NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'goodsDetails' => [
					'title' => '商品详情',
					'type' => 'editor',
					'field' => 'text  NULL',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			]
	];
}	