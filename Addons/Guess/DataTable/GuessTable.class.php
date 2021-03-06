<?php
/**
 * Guess数据模型
 */
class GuessTable {
	// 数据表模型配置
	public $config = [
			'name' => 'guess',
			'title' => '竞猜',
			'search_key' => 'title:活动名称',
			'add_button' => 1,
			'del_button' => 1,
			'search_button' => 1,
			'check_all' => 1,
			'list_row' => 20,
			'addon' => 'Guess'
	];
	
	// 列表定义
	public $list_grid = [
			'title' => [
					'title' => '活动名称',
					'come_from' => 0,
					'width' => '',
					'is_sort' => 0
			],
			'start_time' => [
					'title' => '开始时间',
					'come_from' => 0,
					'width' => '',
					'is_sort' => 0
			],
			'end_time' => [
					'title' => '结束时间',
					'come_from' => 0,
					'width' => '',
					'is_sort' => 0
			],
			'guess_count' => [
					'title' => '参与人数',
					'come_from' => 0,
					'width' => '',
					'is_sort' => 0
			],
			'urls' => [
					'title' => '操作',
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
									'title' => '竞猜选项',
									'url' => 'guessOption&guess_id=[id]&target=_blank'
							],
							'3' => [
									'title' => '竞猜记录',
									'url' => 'guessLog&guess_id=[id]&target=_blank'
							],
							'4' => [
									'title' => '预览',
									'url' => 'preview?id=[id]&target=_blank'
							],
							'5' => [
									'title' => '复制链接',
									'url' => 'Guess/Wap/index&id=[id]'
							]
					]
			]
	];
	
	// 字段定义
	public $fields = [
			'title' => [
					'title' => '竞猜标题',
					'field' => 'varchar(255) NULL',
					'type' => 'string',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'desc' => [
					'title' => '活动说明',
					'field' => 'text NULL',
					'type' => 'textarea',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'start_time' => [
					'title' => '开始时间',
					'field' => 'int(10) NULL',
					'type' => 'datetime',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'end_time' => [
					'title' => '结束时间',
					'field' => 'int(10) NULL',
					'type' => 'datetime',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'create_time' => [
					'title' => '创建时间',
					'field' => 'int(10) NULL',
					'type' => 'datetime',
					'is_show' => 4,
					'placeholder' => '请输入内容'
			],
			'guess_count' => [
					'field' => 'int(10) unsigned NULL',
					'type' => 'num',
					'is_show' => 4,
					'placeholder' => '请输入内容'
			],
			'token' => [
					'title' => 'Token',
					'field' => 'varchar(255) NULL',
					'type' => 'string',
					'auto_rule' => 'get_token',
					'auto_time' => 1,
					'auto_type' => 'function',
					'placeholder' => '请输入内容'
			],
			'template' => [
					'title' => '素材模板',
					'field' => 'varchar(255) NULL',
					'type' => 'string',
					'value' => 'default',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			],
			'cover' => [
					'title' => '主题图片',
					'field' => 'int(10) unsigned NULL',
					'type' => 'picture',
					'is_show' => 1,
					'placeholder' => '请输入内容'
			]
	];
}	