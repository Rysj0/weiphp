<?php if (!defined('THINK_PATH')) exit();?><meta charset="UTF-8">
<meta content="<?php echo C('WEB_SITE_KEYWORD');?>" name="keywords"/>
<meta content="<?php echo C('WEB_SITE_DESCRIPTION');?>" name="description"/>
<link rel="shortcut icon" href="<?php echo SITE_URL;?>/favicon.ico">
<title><?php echo empty($page_title) ? C('WEB_SITE_TITLE') : $page_title; ?></title>
<link href="/weiphp/Public/static/font-awesome/css/font-awesome.min.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet">
<link href="/weiphp/Public/Home/css/base.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet">
<link href="/weiphp/Public/Home/css/module.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet">
<link href="/weiphp/Public/Home/css/weiphp.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet">
<link href="/weiphp/Public/static/emoji.css?v=<?php echo SITE_VERSION;?>" rel="stylesheet">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="/weiphp/Public/static/bootstrap/js/html5shiv.js?v=<?php echo SITE_VERSION;?>"></script>
<![endif]-->

<!--[if lt IE 9]>
<script type="text/javascript" src="/weiphp/Public/static/jquery-1.10.2.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script type="text/javascript" src="/weiphp/Public/static/jquery-2.0.3.min.js"></script>
<!--<![endif]-->
<script type="text/javascript" src="/weiphp/Public/static/bootstrap/js/bootstrap.min.js"></script>
<!--引入JS-->
<script type="text/javascript" src="/weiphp/Public/static/webuploader-0.1.5/webuploader.min.js"></script>

<script type="text/javascript" src="/weiphp/Public/static/clipboard.min.js"></script>
<script type="text/javascript" src="/weiphp/Public/Home/js/dialog.js?v=<?php echo SITE_VERSION;?>"></script>
<script type="text/javascript" src="/weiphp/Public/Home/js/admin_common.js?v=<?php echo SITE_VERSION;?>"></script>
<script type="text/javascript" src="/weiphp/Public/Home/js/admin_image.js?v=<?php echo SITE_VERSION;?>"></script>
<script type="text/javascript" src="/weiphp/Public/static/masonry/masonry.pkgd.min.js"></script>
<script type="text/javascript" src="/weiphp/Public/static/jquery.dragsort-0.5.2.min.js"></script> 
<script type="text/javascript">
var  IMG_PATH = "/weiphp/Public/Home/images";
var  STATIC = "/weiphp/Public/static";
var  ROOT = "/weiphp";
var  UPLOAD_PICTURE = "<?php echo U('Home/File/uploadPicture',array('session_id'=>session_id()));?>";
var  UPLOAD_FILE = "<?php echo U('Home/File/upload',array('session_id'=>session_id()));?>";
var  UPLOAD_DIALOG_URL = "<?php echo U('Home/File/uploadDialog',array('session_id'=>session_id()));?>";
</script>
<!-- 页面header钩子，一般用于加载插件CSS文件和代码 -->
<?php echo hook('pageHeader');?>

<!-- 提示 -->
<div id="top-alert" class="top-alert-tips alert-error" style="display:none">
  <?php if(!empty($code)): ?><a class="code" href="">解决方法 ></a><?php endif; ?>
  <div class="code_box"></div>
  <a class="close" href="javascript:;"><b class="fa fa-times-circle"></b></a>
  <div class="alert-content"></div>
</div>	
<script type="text/javascript" src="/weiphp/Public/static/qrcode/qrcode.js"></script>
<script type="text/javascript" src="/weiphp/Public/static/qrcode/jquery.qrcode.js"></script>
<style type="text/css">
#phone{ margin:0 auto; width:510px; height:814px; background:url(/weiphp/Public/Home/images/preview_phone.jpg) no-repeat;}
#frame{ margin:100px 0 0 97px; width:320px; border:2px solid #333;}
#qrcode{ position:absolute; top:200px; left:50%; margin-left:230px; width:200px; height:300px; text-align:center; line-height:30px;}
</style>
</head>
<body style="background:#fff">
	<div id="phone">
	<iframe id="frame" src="<?php echo ($url); ?>" height="565" width="320" frameborder="0"></iframe>
    </div>
    <div id="qrcode">
    	<div id="canvas" style="height:200px; width:200px;"></div>
    	用微信扫一扫预览
    </div>
    <script type="text/javascript">
    	var url = "<?php echo ($url); ?>";
        $('#canvas').qrcode({width:200,height:200,text:url}); 
    </script>
</body>
</html>