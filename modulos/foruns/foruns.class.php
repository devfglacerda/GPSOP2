<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $config;
require_once ($Aplic->getClasseSistema('libmail'));
require_once ($Aplic->getClasseBiblioteca('PEAR/BBCodeParser'));
$bbparser = new HTML_BBCodeParser();
$filtros = array('');
if (isset($a) && $a == 'ver') array_push($filtros, 'Meus Acompanhados', 'Últimos 30 Dias');
else array_push($filtros, 'Meus Fóruns', 'Meus Acompanhados', 'Minh'.$config['genero_organizacao'].' '.$config['organizacao']);

class CForum extends CAplicObjeto {
	public $forum_id = null;
	public $forum_cia = null;
	public $forum_dept = null;
	public $forum_projeto = null;
	public $forum_tarefa = null;
	public $forum_pratica = null;
	public $forum_acao = null;
	public $forum_indicador = null;
	public $forum_tema = null;
	public $forum_objetivo = null;
	public $forum_estrategia = null;
	public $forum_meta = null;
	public $forum_fator = null;
	public $forum_perspectiva = null;
	public $forum_canvas = null;
	public $forum_status = null;
	public $forum_dono = null;
	public $forum_nome = null;
	public $forum_data_criacao = null;
	public $forum_ultima_data = null;
	public $forum_ultimo_id = null;
	public $forum_contagem_msg = null;
	public $forum_descricao = null;
	public $forum_moderador = null;
	public $forum_acesso = null;
	public $forum_cor = null;
	public $forum_ativo = null;
	public $forum_principal_indicador = null;
	public $forum_moeda = null;
	public $forum_aprovado = null;	
	
	public function __construct() {
		parent::__construct('foruns', 'forum_id');
		}

	public function join( $hash) {
		if (!is_array($hash))	return "CForum::unir falhou";
		else {
			$sql = new BDConsulta;
			$sql->unirLinhaAoObjeto($hash, $this);
			$sql->limpar();
			return null;
			}
		}

	public function check() {

		return null;
		}

	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$msg = $this->check();
		if ($msg) return 'CForum::checagem para armazenar falhou '.$msg;
		$sql = new BDConsulta();
		if ($this->forum_id) {
			$ret = $sql->atualizarObjeto('foruns', $this, 'forum_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('foruns', $this, 'forum_id');
			$sql->limpar();
			}

		$forum_usuarios=getParam($_REQUEST, 'forum_usuarios', null);
		$forum_usuarios=explode(',', $forum_usuarios);
		$sql->setExcluir('forum_usuario');
		$sql->adOnde('forum_usuario_forum = '.$this->forum_id);
		$sql->exec();
		$sql->limpar();
		foreach($forum_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('forum_usuario');
				$sql->adInserir('forum_usuario_forum', $this->forum_id);
				$sql->adInserir('forum_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'forum_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('forum_dept');
		$sql->adOnde('forum_dept_forum = '.$this->forum_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('forum_dept');
				$sql->adInserir('forum_dept_forum', $this->forum_id);
				$sql->adInserir('forum_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('forum_cia');
			$sql->adOnde('forum_cia_forum='.(int)$this->forum_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'forum_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('forum_cia');
						$sql->adInserir('forum_cia_forum', $this->forum_id);
						$sql->adInserir('forum_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('forum_gestao');
			$sql->adAtualizar('forum_gestao_forum', (int)$this->forum_id);
			$sql->adAtualizar('forum_gestao_uuid', null);
			$sql->adOnde('forum_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();



			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_forum', (int)$this->forum_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_forum', (int)$this->forum_id);
			$sql->adAtualizar('priorizacao_uuid', null);
			$sql->adOnde('priorizacao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();		
			}

		//verificar aprovacao
		if ($Aplic->profissional) {
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_forum='.(int)$this->forum_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_forum='.(int)$this->forum_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_forum='.(int)$this->forum_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('foruns');
			$sql->adAtualizar('forum_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('forum_id='.(int)$this->forum_id);
			$sql->exec();
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('foruns', $this->forum_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->forum_id);
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}



	public function excluir( $oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('forum_id', null)==$this->forum_id) $Aplic->setEstado('forum_id', null);
		parent::excluir();
		return null;
		}

	public function podeAcessar() {
		return true;
		}

	public function podeEditar() {
		return true;
		}

	}

class CForumMensagem {
	public $mensagem_id = null;
	public $mensagem_forum = null;
	public $mensagem_superior = null;
	public $mensagem_autor = null;
	public $mensagem_editor = null;
	public $mensagem_titulo = null;
	public $mensagem_data = null;
	public $mensagem_texto = null;
	public $mensagem_publicada = null;

	public function __construct() {
		// construtor vazio
		}

	public function join( $hash) {
		if (!is_array($hash)) return 'CForumMensagem::unir falhou';
		else {
			$sql = new BDConsulta;
			$sql->unirLinhaAoObjeto($hash, $this);
			$sql->limpar();
			return null;
			}
		}

	public function check() {
		if ($this->mensagem_id === null) return 'Id da mensagem está nulo';
		return null;
		}

	public function armazenar( $atualizarNulos = true) {
		$msg = $this->check();
		if ($msg) return 'CForumMensagem::checagem para armazenar falhou '.$msg;
		$sql = new BDConsulta;
		if ($this->mensagem_id) {
			$sql->setExcluir('forum_visitas');
			$sql->adOnde('visita_mensagem = '.(int)$this->mensagem_id);
			$sql->exec();
			$sql->limpar();
			$ret = $sql->atualizarObjeto('forum_mensagens', $this, 'mensagem_id', false);
			$sql->limpar();
			}
		else {
			$data = new CData();
			$this->mensagem_data = $data->format('%Y-%m-%d %H:%M:%S');
			$novo_id = $sql->inserirObjeto('forum_mensagens', $this, 'mensagem_id');
			echo db_error();
			$sql->limpar();
			$sql->adTabela('forum_mensagens');
			$sql->adCampo('count(mensagem_id) as qnt, MAX(mensagem_data) as data');
			$sql->adOnde('mensagem_forum = '.(int)$this->mensagem_forum);
			$resposta = $sql->linha();
			$sql->limpar();


			$sql->adTabela('foruns');
			$sql->adAtualizar('forum_contagem_msg', $resposta['qnt']);
			$sql->adAtualizar('forum_ultima_data', $resposta['data']);
			$sql->adAtualizar('forum_ultimo_id', $this->mensagem_id);
			$sql->adOnde('forum_id = '.$this->mensagem_forum);
			$sql->exec();
			$sql->limpar();

			return $this->enviarEmailAcompanhamento(false);
			}

		if (!$ret) return 'CForumMensagem::armazenar falhou '.db_error();
		else return null;
	}

	public function excluir( $oid = NULL) {
		$sql = new BDConsulta;
		$sql->setExcluir('forum_visitas');
		$sql->adOnde('visita_mensagem = '.(int)$this->mensagem_id);
		$sql->exec();
		$sql->limpar();
		$sql->adTabela('forum_mensagens');
		$sql->adCampo('mensagem_forum');
		$sql->adOnde('mensagem_id = '.(int)$this->mensagem_id);
		$forumId = $sql->Resultado();
		$sql->limpar();
		$sql->setExcluir('forum_mensagens');
		$sql->adOnde('mensagem_id = '.(int)$this->mensagem_id);
		if (!$sql->exec()) $resultado = db_error();
		else $resultado = null;
		$sql->limpar();
		$sql->adTabela('forum_mensagens');
		$sql->adCampo('COUNT(mensagem_id)');
		$sql->adOnde('mensagem_forum = '.(int)$forumId);
		$quantMensagens = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('foruns');
		$sql->adAtualizar('forum_contagem_msg', $quantMensagens);
		$sql->adOnde('forum_id = '.(int)$forumId);
		$sql->exec();
		$sql->limpar();
		return $resultado;
		}

	public function enviarEmailAcompanhamento( $depurar = false) {
		global $Aplic, $depurar, $config;
		$prefixo_assunto = 'Assunto do forum';
		$corpo_msg = 'Atividade detectada em um fórum que você acompanha';
		$sql = new BDConsulta;
		
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->adCampo('contato_email, contato_posto, contato_nomeguerra, usuario_id');
		$sql->adOnde('usuario_id = '.(int)$this->mensagem_autor);
		$linha = $sql->linha();
		$sql->limpar();
		if ($linha['contato_nomeguerra']) $mensagem_de = ($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']).'<'.$linha['contato_email'].'>';
		else $mensagem_de = ucfirst($config['usuario']).' desconhecido';
		
	
		$sql->adTabela('foruns');
		$sql->adCampo('forum_nome');
		$sql->adOnde('forum_id = \''.$this->mensagem_forum.'\'');
		$forum_nome = $sql->resultado();
		$sql->limpar();
		
		$sql->adTabela('forum_acompanhar');
		$sql->adCampo('count(acompanhar_id)');
		$sql->adOnde('acompanhar_usuario = 0 OR acompanhar_usuario IS NULL');
		$sql->adOnde('acompanhar_forum = 0 OR acompanhar_forum IS NULL');
		$sql->adOnde('acompanhar_topico = 0 OR acompanhar_topico IS NULL');
		$contarTodos = $sql->resultado();
		$sql->limpar();
		
		$sql->adTabela('usuarios');
		$sql->adCampo('DISTINCT contato_email, usuario_id, contato_posto, contato_nomeguerra, usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('forum_acompanhar', 'forum_acompanhar', 'forum_acompanhar.acompanhar_usuario = usuarios.usuario_id');
		if ($contarTodos < 1)	$sql->adOnde('usuario_id = acompanhar_usuario AND (acompanhar_forum = '.(int)$this->mensagem_forum.' OR acompanhar_topico = '.(int)$this->mensagem_superior.')');
		$res=$sql->lista();
		if (count($res) < 1) return;
		
		$email = new Mail;
		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$email->Assunto($prefixo_assunto.' '.$this->mensagem_titulo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
		$titulo=$prefixo_assunto.' '.$this->mensagem_titulo;
		$corpo = $corpo_msg;
		$corpo .= "\n\n<b>Forum:</b> ".$forum_nome;
		$corpo .= "\n<b>Assunto:</b> ".$this->mensagem_titulo;
		$corpo .= "\n<b>Mensagem de:</b> ".$mensagem_de;
		
		$corpo .= "\n\n".$this->mensagem_texto;
		
		
		$corpo_interno=$corpo;
		$corpo_externo=$corpo;
		$corpo_interno.='<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$this->mensagem_forum.'\');"><b>Clique para acessar o fórum</b></a>';

		

		msg_email_interno ('', $titulo, $corpo_interno,'',$linha['usuario_id']);
		if ($email->EmailValido($linha['contato_email']) && $config['email_ativo']) {
			
			if ($Aplic->profissional){
				require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
				$endereco=link_email_externo($linha['monitoramento_deliberacao_usuario'], 'm=foruns&a=ver&forum_id='.$this->mensagem_forum);
				if ($endereco) $corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o forum</b></a>';
				}
			
			$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
			$email->Para($linha['contato_email'], true);
			$email->Enviar();
			}
			
			
			
			
	
		}

	}
?>