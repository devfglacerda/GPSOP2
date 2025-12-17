<?php


/*
<pre class='xdebug-var-dump' dir='ltr'>
<small>C:\xampp\htdocs\gpwebpro\server\arquivos\processar.php:2:</small>
<b>array</b> <i>(size=1)</i>
  'upload' <font color='#888a85'>=&gt;</font> 
    <b>array</b> <i>(size=5)</i>
      'name' 'image.png'</font> <i>(length=9)</i>
      'type' 'image/png'</font> <i>(length=9)</i>
      'tmp_name' 'C:\xampp\tmp\php6E6E.tmp'</font> <i>(length=24)</i>
      'error' 0</font>
      'size' 1748</font>
</pre>

*/




require_once '../base.php';
require_once BASE_DIR.'/config.php';
if (!isset($GLOBALS['OS_WIN'])) $GLOBALS['OS_WIN'] = (stristr(PHP_OS, 'WIN') !== false);

define('BASE_URL', get_base_url());

require_once BASE_DIR.'/incluir/funcoes_principais.php';
require_once BASE_DIR.'/incluir/db_adodb.php';
require_once BASE_DIR.'/classes/BDConsulta.class.php';


$url=grava_ckeditor('upload');
$message = '';
echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction(1, '$url', '$message');</script>";


?>