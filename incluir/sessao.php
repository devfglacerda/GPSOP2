<?php 
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\incluir\sessao.php		

Funções utilizadas para a criação da sessão no sistema
																																												
********************************************************************************************/ 
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

require_once BASE_DIR.'/incluir/funcoes_principais.php';
require_once BASE_DIR.'/incluir/db_adodb.php';
require_once BASE_DIR.'/classes/BDConsulta.class.php';
require_once BASE_DIR.'/classes/ui.class.php';
require_once BASE_DIR.'/classes/evento_recorrencia.class.php';

function sessaoAbrir($save_path, $session_name) {
	return true;
	}

function sessaoFechar() {
	return true;
	}


function sessaoLer($id) {
	$q = new BDConsulta;
	$q->adTabela('sessoes');
	$q->adCampo('sessao_data');
	$q->adOnde('sessao_id = \''.$id.'\'');
	$qid=$q->Linha();
	$q->limpar();

	if (!$qid || !$qid['sessao_data']) {
		$data = '';
		}
	else {
        $data = $qid['sessao_data'];
		}

	return $data;
	}
	
	
function sessaoEscrever($id, $data) {
	global $Aplic;
	$q = new BDConsulta;
	$q->setExcluir('sessoes');
	$q->adOnde('sessao_id = \''.$id.'\'');
	$q->exec();
	$q->limpar();
	
	$q->adTabela('sessoes');
	$q->adInserir('sessao_id', $id);
	$q->adInserir('sessao_usuario', $Aplic->usuario_id);
	$q->adInserir('sessao_data', $data);
	$q->adInserir('sessao_criada', date('Y-m-d H:i:s'));
	$q->exec();
	$q->limpar();

	return true;
	}

function sessaoDestruir($id) {
	$q = new BDConsulta;
	
	$q->adTabela('sessoes');
	$q->adCampo('sessao_usuario');
	$q->adOnde('sessao_id = \''.$id.'\'');
	$sessao_usuario=$q->resultado();
	$q->limpar();
		
	if($sessao_usuario) {
        $q->adTabela( 'usuario_reg_acesso' );
        $q->adAtualizar( 'saiu', date( 'Y-m-d H:i:s' ) );
        $q->adOnde( 'usuario_id = ' . (int) $sessao_usuario );
        $q->adOnde( 'saiu IS NULL' );
        $q->exec();
        $q->limpar();
    }

	$q->setExcluir('sessoes');
	$q->adOnde('sessao_id = \''.$id.'\'');
	$q->exec();
	$q->limpar();
	
	return true;
	}


function sessaoGC($tempMaxVida) {
	global $Aplic;
	
	return true;
	}



function sessaoConverterTempo($chave) {
	$chave = 'session_'.$chave;
	if (config($chave) == null || config($chave) == null) return 86400;
	$numpart = (int)config($chave);
	$modificador = substr(config($chave), -1);
	if (!is_numeric($modificador)) {
		switch ($modificador) {
			case 'h':
				$numpart *= 3600;
				break;
			case 'd':
				$numpart *= 86400;
				break;
			case 'm':
				$numpart *= (86400 * 30);
				break;
			case 'y':
				$numpart *= (86400 * 365);
				break;
			}
		}
	return $numpart;
	}

function sessaoIniciar($variaveis_inicio = 'Aplic') {
	//session_name(config('nomeBd'));
	session_name('gpweb');
	if (ini_get('session.auto_start') > 0) session_write_close();
	if (config('administrando_sessao') == 'app') {
		//Evandro olhar
		//ini_set('session.save_handler', 'user');
		if (version_compare(phpversion(), '5.0.0', '>=')) register_shutdown_function('session_write_close');
		session_set_save_handler('sessaoAbrir', 'sessaoFechar', 'sessaoLer', 'sessaoEscrever', 'sessaoDestruir', 'sessaoGC');
		} 
	
	$diretoriogpweb = '/';
	
	$pathInfo = safe_get_env('PATH_INFO');
  if ($pathInfo) $path .= str_replace('\\', '/', dirname($pathInfo));
	else $path = str_replace('\\', '/', dirname(safe_get_env('SCRIPT_NAME')));
	$path = preg_replace('#/$#D', '', $path);
	if(substr($path,0,1) != '/') $path = '/'.$path;
	$path = explode('/', $path);
	$ct = count($path);
	if($ct>0 && file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
		if(strtolower($path[--$ct]) == 'server') array_pop($path);
		}
	$diretoriogpweb = implode('/',$path);
	if(substr($diretoriogpweb, -1) != '/') $diretoriogpweb .= '/';
	session_set_cookie_params(0, $diretoriogpweb);
	session_start();
	}
?>