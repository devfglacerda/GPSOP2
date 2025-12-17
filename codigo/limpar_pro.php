<?php 
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (file_exists('../pdfimg.php')) @unlink('../pdfimg.php');

remover_arquivos_pro('../');
rrmdir('../lib/codigobarra');
rrmdir('../lib/extgantt');
rrmdir('../lib/extjs');
rrmdir('../lib/SlickGrid');
rrmdir('../lib/PHPExcel');
rrmdir('../lib/mpdf');
rrmdir('../lib/highcharts3');
rrmdir('../modulos/atas');
rrmdir('../modulos/graficos');
rrmdir('../modulos/sistema/menu');
rrmdir('../modulos/problema');
rrmdir('../modulos/operativo');
rrmdir('../modulos/projetos/eb');
rrmdir('../modulos/projetos/gantt_agil');
rrmdir('../modulos/agrupamento');
rrmdir('../modulos/financeiro');
rrmdir('../modulos/swot');
rrmdir('../modulos/tr');
rrmdir('../modulos/ssti');
rrmdir('../modulos/registro');
rrmdir('../modulos/aviso');
rrmdir('../modulos/sistema/pauta');
rrmdir('../modulos/sistema/ator');
rrmdir('../modulos/pdcl');
rrmdir('../modulos/trelo');
rrmdir('../modulos/sistema/nd');
echo 'Terminado em '.date('d/m/Y H:i:s');


function remover_arquivos_pro($dir = '../'){
	$files = scandir($dir);
	if(!$files) return;
	
	foreach($files as $file){
		$fullPath = $dir.$file;
		if(is_dir($fullPath) && $file != '.' && $file != '..'){
			remover_arquivos_pro($fullPath.'/');
			}
		else if((stripos($file, '_pro.') !== false || stripos($file, '_pro_') !== false) && $file !='limpar_pro.php'){
			@unlink($fullPath);
			}
		}
	}

function rrmdir($dir) {
	if (is_dir($dir)){
		$objects = scandir($dir);
		foreach ($objects as $object){
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
				}
			}
		reset($objects);
		rmdir($dir);
		}
	} 
?>