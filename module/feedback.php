<?php
if (!defined('IN_HANFOX')) exit('Access Denied');

$pagename = '意见反馈';
$pageurl = '?mod=feedback';
$tplfile = 'feedback.html';

$smarty->caching = false;
	
if (!$smarty->isCached($tplfile)) {
	$smarty->assign('pagename', $pagename);
	$smarty->assign('site_title', $pagename.' - '.$options['site_name']);
	$smarty->assign('site_keywords', '意见反馈，问题反馈，用户反馈，意见与建议');
	$smarty->assign('site_description', '您的意见和建议，将帮助我们改进产品和服务，欢迎您提出宝贵建议和意见！');
	$smarty->assign('site_path', get_sitepath().' &nbsp;&rsaquo;&nbsp; '.$pagename);
	$smarty->assign('site_rss', get_rssfeed());
	
	if (!empty($_POST)) {
		$fb_nick = trim($_POST['nick']);
		$fb_email = trim($_POST['email']);
		$fb_content = trim($_POST['content']);
		$check_code = md5($_POST['checkcode']);
		
		$fb_date = time();
		if (empty($fb_nick)) {
			alert('请输入昵称！');
		}
		
		if (empty($fb_email)) {
			alert('请输入电子邮件！');
		} else {
			if (!is_valid_email($fb_email)) {
				alert('请输入正确的电子邮件地址！');
			}
		}
		
		if (empty($fb_content) || strlen($fb_content) < 20) {
			alert('请输入意见内容，且长度不能小于20个字符！');
		}
		
		if (empty($check_code) || $check_code != $_SESSION['code']) {
			unset($_SESSION['code']);
			alert('您输入的验证码不正确，请重新输入！');	
		}
			
		$data = array(
			'fb_nick' => $fb_nick,
			'fb_email' => $fb_email,
			'fb_content' => $fb_content,
			'fb_date' => $fb_date,
		);
		
		$DB->insert($DB->table('feedbacks'), $data);
		unset($_SESSION['code']);
				
		alert('您的意见已经提交，谢谢您对我们的支持！', './');
	}
}
		
smarty_output($tplfile);
?>