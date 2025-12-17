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

require_once ($Aplic->getClasseSistema('aplic'));
require_once ($Aplic->getClasseSistema('libmail'));

class CContato extends CAplicObjeto {
	public $contato_id = null;
	public $contato_cia = null;
	public $contato_dept = null;
	public $contato_dono = null;
	public $contato_posto_valor = null;
	public $contato_posto = null;
	public $contato_nomeguerra = null;
	public $contato_nomecompleto = null;
	public $contato_ordem = null;
	public $contato_arma = null;
	public $contato_nascimento = null;
	public $contato_funcao = null;
	public $contato_codigo = null;
	public $contato_tipo = null;
	public $contato_matricula = null;
	public $contato_identidade = null;
	public $contato_cpf = null;
	public $contato_cnpj = null;
	public $contato_email = null;
	public $contato_email2 = null;
	public $contato_url = null;
	public $contato_tel = null;
	public $contato_tel2 = null;
	public $contato_cel = null;
	public $contato_endereco1 = null;
	public $contato_endereco2 = null;
	public $contato_cidade = null;
	public $contato_estado = null;
	public $contato_cep = null;
	public $contato_pais = null;
	public $contato_notas = null;
	public $contato_skype = null;
	public $contato_religiao = null;
	public $contato_sangue = null;
	public $contato_vivo = null;
	public $contato_natural_cidade = null;
	public $contato_natural_estado = null;
	public $contato_natural_pais = null;
	public $contato_grau_instrucao = null;
	public $contato_formacao = null;
	public $contato_profissao = null;
	public $contato_ocupacao = null;
	public $contato_especialidade = null;
	public $contato_icone = null;
	public $contato_privado = null;
	public $contato_chave_atualizacao = null;
	public $contato_ultima_atualizacao = null;
	public $contato_pedido_atualizacao = null;
	public $contato_hora_custo = null;
	public $contato_foto = null;
	public $contato_ativo = null;

	public function __construct() {
		parent::__construct('contatos', 'contato_id');
		}

	public function check() {
		$this->contato_privado = intval($this->contato_privado);
		return null;
		}

	public function armazenar( $atualizarNulos = true) {
		global $Aplic, $_REQUEST;
		$sql = new BDConsulta();

		//evitar problema de chave estrangeira
		if (!isset($_REQUEST['contato_cia']) || (isset($_REQUEST['contato_cia']) && !$_REQUEST['contato_cia'])) $_REQUEST['contato_cia']=null;
		if (!isset($_REQUEST['contato_dept']) || (isset($_REQUEST['contato_dept']) && !$_REQUEST['contato_dept'])) $_REQUEST['contato_dept']=null;
		if (!isset($_REQUEST['contato_dono']) || (isset($_REQUEST['contato_dono']) && !$_REQUEST['contato_dono'])) $_REQUEST['contato_dono']=null;

		if ($_REQUEST['contato_id']) {
			$ret = $sql->atualizarObjeto('contatos', $this, 'contato_id', $atualizarNulos);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('contatos', $this, 'contato_id');
			$sql->limpar();
			}
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('contatos', $this->contato_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->contato_id);
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	public function podeExcluir( &$msg='', $contato_id = null, $unioes = null) {
		global $Aplic,$config;
		if ($contato_id) {
			$sql = new BDConsulta;
			$sql->adTabela('usuarios');
			$sql->adCampo('count(usuario_id) as usuario_contagem');
			$sql->adOnde('usuario_contato = '.(int)$contato_id);
			$usuario_contagem = $sql->Resultado();
			$sql->limpar();
			if ($usuario_contagem > 0) {
				$msg = ucfirst($config['contato']).' pertence a um '.$config['usuario'].' e não pode ser excluíd'.$config['genero_contato'];
				return false;
				}
			}
		return true;	
		//return parent::podeExcluir($msg, $oid, $unioes);
		}

	public function ehUsuario( $contato_id = null) {
		global $Aplic;
		if (!$contato_id) $contato_id = $this->contato_id;
		if ($contato_id) {
			$sql = new BDConsulta;
			$sql->adTabela('usuarios');
			$sql->adCampo('usuario_id');
			$sql->adOnde('usuario_contato = '.(int)$contato_id);
			$usuario_id = $sql->Resultado();
			$sql->limpar();
			if ($usuario_id > 0) return $usuario_id;
			else return false;
			}
		else return false;
		}

	public function eh_alpha( $val) {
		$numval = strtr($val, '012345678', '999999999');
		if (count_chars($numval, 3) == '9') return false;
		return true;
		}

	public function getCiaId() {
		$q = new BDConsulta;
		$q->adTabela('cias');
		$q->adCampo('cia_id');
		$q->adOnde('cia_nome = '.(int)$this->contato_cia);
		$cia_id = $q->Resultado();
		$q->limpar();
		return $cia_id;
		}

	public function getCiaNome() {
		$q = new BDConsulta;
		$q->adTabela('cias');
		$q->adCampo('cia_nome');
		$q->adOnde('cia_id = '.(int)$this->contato_cia);
		$cia_nome = $q->Resultado();
		$q->limpar();
		return $cia_nome;
		}

	public function getChaveAtualizada() {
		$q = new BDConsulta;
		$q->adTabela('contatos');
		$q->adCampo('contato_chave_atualizacao');
		$q->adOnde('contato_id = '.(int)$this->contato_id);
		$chave_atual = $q->Resultado();
		$q->limpar();
		return $chave_atual;
		}

	public function atualizarNotificar() {
		global $Aplic, $config, $localidade_tipo_caract;
		$email = new Mail;
		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$email->Assunto('Saudações', $localidade_tipo_caract);
		if ($this->contato_email) {
			$q = new BDConsulta;
			$q->adTabela('cias');
			$q->adCampo('cia_id, cia_nome');
			$q->adOnde('cia_id = '.(int)$this->contato_cia);
			$contato_cia = $q->ListaChave();
			$q->limpar();
			$corpo = "$this->contato_posto $this->contato_nomeguerra,";
			$corpo .= "\n\nPara nós é importante termos seus dados cadastrais atualizados.";
			$corpo .= "\n\nPoderá verificar seus dados no endereço abaixo:";
			$corpo .= "\n\n\n\n".'<a href="'.$config['dominio_site'].($Aplic->profissional ? '/server' : '').'/codigo/contato_atualizado.php?chave_atual='.$this->contato_chave_atualizacao.'">'.$config['dominio_site'].'/contato_atualizado.php?chave_atual='.$this->contato_chave_atualizacao.'</a>';
			$email->Corpo($corpo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
			}
		msg_email_interno($this->contato_email, 'Saudações', $corpo);
		if ($email->EmailValido($this->contato_email) && $config['email_ativo']) {
			$email->Para($this->contato_email, true);
			$email->Enviar();
			}
		}
	}
?>