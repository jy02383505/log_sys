<?php
return array(
	'SHOW_PAGE_TRACE'=>true,
	'URL_MODEL'=>0,
	'URL_CASE_INSENSITIVE' =>true,
	'TMPL_ACTION_ERROR' => 'Public:message', 
	'TMPL_ACTION_SUCCESS' => 'Public:message',
	'TMPL_EXCEPTION_FILE'=>'./App/Tpl/Public/exception.html',
	'DEFAULT_TIMEZONE' => 'PRC',
	'LOAD_EXT_CONFIG' => 'db,version',
	'LOG_RECORD' => true,
	'LOG_LEVEL'  =>'EMERG',
	'OUTPUT_ENCODE' => false,
    'LANG_SWITCH_ON' => true,
    'LANG_AUTO_DETECT' => true,
    'LANG_LIST' => 'zh-cn',
    'VAR_LANGUAGE' => 'l',
    'COOKIE_PATH' => __ROOT__,
    'SESSION_OPTIONS'=>array('cookie_path'=>__ROOT__),
);
?>