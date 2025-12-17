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

class CCia extends CAplicObjeto {
	public $cia_id = null;
	public $cia_nome = null;
	public $cia_nome_completo = null;
	public $cia_superior = null;
	public $cia_codigo = null;
	public $cia_cnpj = null;
	public $cia_inscricao_estadual = null;
	public $cia_tel1 = null;
	public $cia_tel2 = null;
	public $cia_fax = null;
	public $cia_endereco1 = null;
	public $cia_endereco2 = null;
	public $cia_cidade = null;
	public $cia_estado = null;
	public $cia_cep = null;
	public $cia_pais = null;
	public $cia_email = null;
	public $cia_url = null;
	public $cia_responsavel = null;
	public $cia_descricao = null;
	public $cia_tipo = null;
	public $cia_contatos = null;
	public $cia_acesso = null;
	public $cia_ug = null;
  public $cia_ug2 = null;
	public $cia_nup = null;
	public $cia_qnt_nup = null;
	public $cia_qnt_nr = null;
	public $cia_prefixo = null;
	public $cia_sufixo = null;
	public $cia_cabacalho = null;
	public $cia_logo = null;
	public $cia_ativo = null;
	
	
	public function __construct() {
		parent::__construct('cias', 'cia_id');
		}

	public function check() {
		global $config;
		if ($this->cia_id === null) return 'Id d'.$config['genero_organizacao'].' '.$config['organizacao'].' está nulo';
		return null; 
		}

	
	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta;
		if ($this->cia_id) {
			$ret = $sql->atualizarObjeto('cias', $this, 'cia_id', false);
			$sql->limpar();
			} 
		else {
			
			$ret = $sql->inserirObjeto('cias', $this, 'cia_id');
			$sql->limpar();
			}
			
			
		$cia_usuarios=getParam($_REQUEST, 'cia_usuarios', null);
		$cia_usuarios=explode(',', $cia_usuarios);
		$sql->setExcluir('cia_usuario');
		$sql->adOnde('cia_usuario_cia = '.$this->cia_id);
		$sql->exec();
		$sql->limpar();
		foreach($cia_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('cia_usuario');
				$sql->adInserir('cia_usuario_cia', $this->cia_id);
				$sql->adInserir('cia_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}	
			
			
			
		$sql->setExcluir('cia_contatos');
		$sql->adOnde('cia_contato_cia='.(int)$this->cia_id);
		$sql->exec();
		$sql->limpar();
		$cia_contatos=getParam($_REQUEST, 'cia_contatos', null);
		if ($cia_contatos) {
			$contatos = explode(',', $cia_contatos);
			foreach ($contatos as $contato) {
				if ($contato){
					$sql->adTabela('cia_contatos');
					$sql->adInserir('cia_contato_cia', $this->cia_id);
					$sql->adInserir('cia_contato_contato', $contato);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('cias', $this->cia_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->cia_id);	
			
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	public function podeExcluir( &$msg='', $oid = null, $unioes = null) {
		global $config;
		$tabelas[] = array('rotulo' => 'projetos', 'nome' => 'projetos', 'campo_id' => 'projeto_id', 'campo_uniao' => 'projeto_cia');
		$tabelas[] = array('rotulo' => strtolower($config['departamentos']), 'nome' => 'depts', 'campo_id' => 'dept_id', 'campo_uniao' => 'dept_cia');
		$tabelas[] = array('rotulo' => 'contatos', 'nome' => 'cia_contatos', 'campo_id' => 'cia_contato_cia', 'campo_uniao' => 'cia_contato_cia');
		$tabelas[] = array('rotulo' => 'integrantes', 'nome' => 'contatos', 'campo_id' => 'contato_id', 'campo_uniao' => 'contato_cia');
		return CAplicObjeto::podeExcluir($msg, $oid, $tabelas);
		}
		
		
	public function getListaCias( $Aplic, $cia_tipo = -1, $texto_procura = '', $dono_id = 0, $ordem_por = 'cia_nome', $ordem_direcao = 'ASC') {
  	$sql = new BDConsulta;
  	$sql->adTabela('cias', 'c');
  	$sql->adCampo('c.cia_id, c.cia_nome, c.cia_nome_completo, c.cia_tipo, c.cia_descricao, count(distinct p.projeto_id) as countp, count(distinct p2.projeto_id) as inativo, con.contato_posto, con.contato_nomeguerra');
  	$sql->esqUnir('projetos', 'p', 'c.cia_id = p.projeto_cia AND p.projeto_ativo = 1 AND p.projeto_template = 0');
  	$sql->esqUnir('usuarios', 'u', 'c.cia_responsavel = u.usuario_id');
  	$sql->esqUnir('contatos', 'con', 'u.usuario_contato = con.contato_id');
  	$sql->esqUnir('projetos', 'p2', 'c.cia_id = p2.projeto_cia AND p2.projeto_ativo = 0');
  	if ($cia_tipo > -1) $sql->adOnde('c.cia_tipo = '.(int)$cia_tipo);
  	if ($texto_procura != '')	$sql->adOnde('c.cia_nome LIKE \'%'.$texto_procura.'%\' OR c.cia_nome_completo LIKE \'%'.$texto_procura.'%\'');
  	if ($dono_id > 0) $sql->adOnde('c.cia_responsavel = '.(int)$dono_id);
  	$sql->adGrupo('c.cia_id');
  	$sql->adOrdem($ordem_por.' '.$ordem_direcao);
  	return $sql->Lista();
  	}	
		
		
	}
?>