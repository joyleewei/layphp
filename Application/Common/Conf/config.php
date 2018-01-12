<?php
return array(
	//'配置项'=>'配置值'
    'MODULE_DENY_LIST'=>array('Common'),
    'MODULE_ALLOW_LIST'=>array('Home','Admin','Api'),
    'TMPL_L_DELIM'=>'<{',
    'TMPL_R_DELIM'=>'}>',
    'URL_CASE_INSENSITIVE'  =>  true,
    'URL_MODEL'=>2,
    'DEFAULT_MODULE' => 'Home',
    'DEFAULT_CONTROLLER' =>'Index',
    'DEFAULT_ACTION' =>'index',
    'DB_TYPE'=>'mysql',
    'DB_HOST'=>'localhost',
    'DB_NAME'=>'layphp',
    'DB_USER'=>'root',
    'DB_PWD'=>'root123',
    'DB_PORT'=>3306,
    'DB_PREFIX'=>'db_'
);