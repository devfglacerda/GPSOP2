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

class CGestao extends CAplicObjeto {

	public $pg_id = null;
  public $pg_nome = null;
	public $pg_usuario = null;
  public $pg_cia = null;
  public $pg_dept = null;
  public $pg_usuario_ultima_alteracao = null;
  public $pg_descricao = null;
  public $pg_ano = null;
  public $pg_inicio = null;
  public $pg_fim = null;
  public $pg_modelo = null;
  public $pg_estrut_org = null;
  public $pg_fornecedores = null;
  public $pg_ultima_alteracao = null;
  public $pg_processos_apoio = null;
  public $pg_processos_finalistico = null;
  public $pg_produtos_servicos = null;
  public $pg_clientes = null;
  public $pg_posgraduados = null;
  public $pg_graduados = null;
  public $pg_nivelmedio = null;
  public $pg_nivelfundamental = null;
  public $pg_semescolaridade = null;
  public $pg_pessoalinterno = null;
  public $pg_programas_acoes = null;
  public $pg_premiacoes = null;
	public $pg_acesso = null;
	public $pg_cor = null;
	public $pg_ativo = null;
	public $pg_aprovado = null;
	public $pg_moeda = null;
	public $pg_tipo_pontuacao = null;
	public $pg_percentagem = null;
	public $pg_ponto_alvo = null;
	
	public function __construct() {
		parent::__construct('plano_gestao', 'pg_id');
		}
		
	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->pg_id) {
			$ret = $sql->atualizarObjeto('plano_gestao', $this, 'pg_id', false);
			$sql->limpar();
			} 
		else {
			$ret = $sql->inserirObjeto('plano_gestao', $this, 'pg_id');
			$sql->limpar();
			}
		
	
		
		$pg_usuarios=getParam($_REQUEST, 'pg_usuarios', '');
		$pg_usuarios=explode(',', $pg_usuarios);
		$sql->setExcluir('plano_gestao_usuario');
		$sql->adOnde('plano_gestao_usuario_plano = '.$this->pg_id);
		$sql->exec();
		$sql->limpar();
		foreach($pg_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('plano_gestao_usuario');
				$sql->adInserir('plano_gestao_usuario_plano', $this->pg_id);
				$sql->adInserir('plano_gestao_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}
			
		$pg_depts=getParam($_REQUEST, 'pg_depts', null);
		$pg_depts=explode(',', $pg_depts);
		$sql->setExcluir('plano_gestao_dept');
		$sql->adOnde('plano_gestao_dept_plano = '.$this->pg_id);
		$sql->exec();
		$sql->limpar();
		foreach($pg_depts as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('plano_gestao_dept');
				$sql->adInserir('plano_gestao_dept_plano', $this->pg_id);
				$sql->adInserir('plano_gestao_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}	
			
		if ($Aplic->profissional){
			$sql->setExcluir('plano_gestao_cia');
			$sql->adOnde('plano_gestao_cia_plano='.(int)$this->pg_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'plano_gestao_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('plano_gestao_cia');
						$sql->adInserir('plano_gestao_cia_plano', $this->pg_id);
						$sql->adInserir('plano_gestao_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}	
			}
			
		
		
		
		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_plano_gestao', (int)$this->pg_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_plano_gestao', (int)$this->pg_id);
			$sql->adAtualizar('priorizacao_uuid', null);
			$sql->adOnde('priorizacao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			
			$sql->adTabela('plano_gestao_gestao');
			$sql->adAtualizar('plano_gestao_gestao_plano_gestao', (int)$this->pg_id);
			$sql->adAtualizar('plano_gestao_gestao_uuid', null);
			$sql->adOnde('plano_gestao_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			
			
			
			
			
			
			
			$sql->adTabela('plano_gestao_media');
			$sql->adAtualizar('plano_gestao_media_plano_gestao', (int)$this->pg_id);
			$sql->adAtualizar('plano_gestao_media_uuid', null);
			$sql->adOnde('plano_gestao_media_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('plano_acao_observador');
			$sql->adAtualizar('plano_acao_observador_plano_gestao', (int)$this->pg_id);
			$sql->adAtualizar('plano_acao_observador_uuid', null);
			$sql->adOnde('plano_acao_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('projeto_observador');
			$sql->adAtualizar('projeto_observador_plano_gestao', (int)$this->pg_id);
			$sql->adAtualizar('projeto_observador_uuid', null);
			$sql->adOnde('projeto_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('perspectiva_observador');
			$sql->adAtualizar('perspectiva_observador_plano_gestao', (int)$this->pg_id);
			$sql->adAtualizar('perspectiva_observador_uuid', null);
			$sql->adOnde('perspectiva_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			
			
			
			
			
			
			
			
			}

		//verificar aprovacao
		$sql->adTabela('assinatura');
		$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
		$sql->adCampo('count(assinatura_id)');
		$sql->adOnde('assinatura_plano_gestao='.(int)$this->pg_id);
		$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
		$sql->adOnde('assinatura_aprova=1');
		$sql->adOnde('assinatura_atesta_opcao > 0');
		$nao_aprovado1 = $sql->resultado();
		$sql->limpar();
		
		
		$sql->adTabela('assinatura');
		$sql->adCampo('count(assinatura_id)');
		$sql->adOnde('assinatura_plano_gestao='.(int)$this->pg_id);
		$sql->adOnde('assinatura_aprova=1');
		$sql->adOnde('assinatura_atesta IS NULL');
		$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
		$nao_aprovado2 = $sql->resultado();
		$sql->limpar();
		
		//assinatura que tem despacho mas nem assinou
		$sql->adTabela('assinatura');
		$sql->adCampo('count(assinatura_id)');
		$sql->adOnde('assinatura_plano_gestao='.(int)$this->pg_id);
		$sql->adOnde('assinatura_aprova=1');
		$sql->adOnde('assinatura_atesta IS NOT NULL');
		$sql->adOnde('assinatura_atesta_opcao IS NULL');
		$nao_aprovado3 = $sql->resultado();
		$sql->limpar();
		
		$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
		
		$sql->adTabela('plano_gestao');
		$sql->adAtualizar('pg_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('pg_id='.(int)$this->pg_id);
		$sql->exec();
		$sql->limpar();	
			
			
			
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}	
		
		
	public function check() {
		$this->pg_id = intval($this->pg_id);
		return null; 
		}
		
	public function excluir( $id=null) {
		global $Aplic;
		$this->_mensagem = "excluido";
		if ($Aplic->getEstado('pg_id', null)==$this->pg_id) $Aplic->setEstado('pg_id', null);
		parent::excluir();
		return null;
		}
	
	
	public function podeAcessar() {
		$valor=permiteAcessarPlanoGestao($this->pg_acesso, $this->pg_id);
		return $valor;
		}
	
	public function podeEditar() {
		$valor=permitePlanoGestao($this->pg_acesso, $this->pg_id);
		return $valor;
		}
		
	
	
	
	
	public function calculo_percentagem(){
		$tipo=$this->pg_tipo_pontuacao;

		$sql = new BDConsulta;
		$porcentagem=null;
		if (!$tipo) $porcentagem=$this->pg_percentagem;
		elseif($tipo=='media_ponderada'){
			$sql->adTabela('plano_gestao_media');
			$sql->esqUnir('perspectivas', 'perspectivas', 'pg_perspectiva_id=plano_gestao_media_perspectiva');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=plano_gestao_media_projeto');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=plano_gestao_media_acao');
			$sql->adCampo('
			pg_perspectiva_percentagem,
			projeto_percentagem,
			plano_acao_percentagem,
			plano_gestao_media_perspectiva,
			plano_gestao_media_projeto,
			plano_gestao_media_acao,
			plano_gestao_media_peso
			');

			$sql->adOnde('plano_gestao_media_plano_gestao ='.(int)$this->pg_id);
			$sql->adOnde('plano_gestao_media_tipo =\'media_ponderada\'');
			$lista = $sql->lista();
			$sql->limpar();
			$numerador=0;
			$denominador=0;

			foreach($lista as $linha){
				if ($linha['plano_gestao_media_perspectiva']) $numerador+=($linha['pg_perspectiva_percentagem']*$linha['plano_gestao_media_peso']);
				elseif ($linha['plano_gestao_media_projeto']) $numerador+=($linha['projeto_percentagem']*$linha['plano_gestao_media_peso']);
				elseif ($linha['plano_gestao_media_acao']) $numerador+=($linha['plano_acao_percentagem']*$linha['plano_gestao_media_peso']);
				$denominador+=$linha['plano_gestao_media_peso'];
				}
			$porcentagem=($denominador ? $numerador/$denominador : 0);
			}
		elseif($tipo=='pontos_completos'){


			$sql->adTabela('plano_gestao_media');
			$sql->esqUnir('perspectivas', 'perspectivas', 'pg_perspectiva_id=plano_gestao_media_perspectiva');
			$sql->adCampo('SUM(plano_gestao_media_ponto)');
			$sql->adOnde('plano_gestao_media_plano_gestao ='.(int)$this->pg_id);
			$sql->adOnde('plano_gestao_media_tipo =\'pontos_completos\'');
			$sql->adOnde('pg_perspectiva_percentagem = 100');
			$sql->adOnde('plano_gestao_media_perspectiva > 0');
			$pontos3 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_gestao_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=plano_gestao_media_projeto');
			$sql->adCampo('SUM(plano_gestao_media_ponto)');
			$sql->adOnde('plano_gestao_media_plano_gestao ='.(int)$this->pg_id);
			$sql->adOnde('plano_gestao_media_tipo =\'pontos_completos\'');
			$sql->adOnde('projeto_percentagem = 100');
			$sql->adOnde('plano_gestao_media_projeto > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_gestao_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=plano_gestao_media_acao');
			$sql->adCampo('SUM(plano_gestao_media_ponto)');
			$sql->adOnde('plano_gestao_media_plano_gestao ='.(int)$this->pg_id);
			$sql->adOnde('plano_gestao_media_tipo =\'pontos_completos\'');
			$sql->adOnde('plano_acao_percentagem = 100');
			$sql->adOnde('plano_gestao_media_acao > 0');
			$pontos5 = $sql->Resultado();
			$sql->limpar();


			$porcentagem=($this->pg_ponto_alvo ? (($pontos3+$pontos4+$pontos5)/$this->pg_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='pontos_parcial'){
			$sql->adTabela('plano_gestao_media');
			$sql->esqUnir('perspectivas', 'perspectivas', 'pg_perspectiva_id=plano_gestao_media_perspectiva');
			$sql->adCampo('SUM(plano_gestao_media_ponto*(pg_perspectiva_percentagem/100))');
			$sql->adOnde('plano_gestao_media_plano_gestao ='.(int)$this->pg_id);
			$sql->adOnde('plano_gestao_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('plano_gestao_media_perspectiva > 0');
			$pontos3 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_gestao_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=plano_gestao_media_projeto');
			$sql->adCampo('SUM(plano_gestao_media_ponto*(projeto_percentagem/100))');
			$sql->adOnde('plano_gestao_media_plano_gestao ='.(int)$this->pg_id);
			$sql->adOnde('plano_gestao_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('plano_gestao_media_projeto > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_gestao_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=plano_gestao_media_acao');
			$sql->adCampo('SUM(plano_gestao_media_ponto*(plano_acao_percentagem/100))');
			$sql->adOnde('plano_gestao_media_plano_gestao ='.(int)$this->pg_id);
			$sql->adOnde('plano_gestao_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('plano_gestao_media_acao > 0');
			$pontos5 = $sql->Resultado();
			$sql->limpar();

			$porcentagem=($this->plano_gestao_ponto_alvo ? (($pontos3+$pontos4+$pontos5)/$this->pg_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='indicador'){
			if ($this->pg_principal_indicador) {
				include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
				$obj_indicador = new Indicador($this->pg_principal_indicador);
				$porcentagem=$obj_indicador->Pontuacao();
				}
			else $porcentagem=0;
			}

		else $porcentagem=0; //caso nao previsto

		if ($porcentagem > 100) $porcentagem=100;
		if ($porcentagem!=$this->pg_percentagem){
			$sql->adTabela('plano_gestao');
			$sql->adAtualizar('pg_percentagem', $porcentagem);
			$sql->adOnde('pg_id ='.(int)$this->pg_id);
			$sql->exec();
			$sql->limpar();
			//$this->disparo_observador('fisico');
			}
		
		return $porcentagem;
		}
	
	
	public function fisico_previsto($data=null){
		if ($this->pg_tipo_pontuacao=='media_ponderada'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
			$sql = new BDConsulta;
			$sql->adTabela('plano_gestao_media');
			$sql->adCampo('plano_gestao_media_projeto, plano_gestao_media_acao, plano_gestao_media_perspectiva, plano_gestao_media_peso');
			$sql->adOnde('plano_gestao_media_plano_gestao ='.(int)$this->pg_id);
			$sql->adOnde('plano_gestao_media_tipo =\''.$this->pg_tipo_pontuacao.'\'');
			
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['plano_gestao_media_projeto']){
					$obj = new CProjeto();
					$obj->load($linha['plano_gestao_media_projeto']);
					$numerador+=$obj->fisico_previsto($data)*$linha['plano_gestao_media_peso'];
					$denominador+=$linha['plano_gestao_media_peso'];
					}
				elseif ($linha['plano_gestao_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['plano_gestao_media_acao']);
					$numerador+=$obj->fisico_previsto($data)*$linha['plano_gestao_media_peso'];
					$denominador+=$linha['plano_gestao_media_peso'];
					}
				elseif ($linha['plano_gestao_media_perspectiva']){
					$obj = new CPerspectiva();
					$obj->load($linha['plano_gestao_media_perspectiva']);
					$numerador+=$obj->fisico_previsto($data)*$linha['plano_gestao_media_peso'];
					$denominador+=$linha['plano_gestao_media_peso'];
					}		
				}
			return ($denominador ? $numerador/$denominador : 0);
			}
		elseif ($this->pg_tipo_pontuacao=='pontos_parcial' || $this->pg_tipo_pontuacao=='pontos_completos'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
			$sql = new BDConsulta;
			$sql->adTabela('plano_gestao_media');
			$sql->adCampo('plano_gestao_media_projeto, plano_gestao_media_acao, plano_gestao_media_perspectiva, plano_gestao_media_ponto');
			$sql->adOnde('plano_gestao_media_plano_gestao ='.(int)$this->pg_id);
			$sql->adOnde('plano_gestao_media_tipo =\''.$this->pg_tipo_pontuacao.'\'');
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['plano_gestao_media_projeto']){
					$obj = new CProjeto();
					$obj->load($linha['plano_gestao_media_projeto']);
					$numerador+=$obj->fisico_previsto($data)*$linha['plano_gestao_media_ponto'];
					$denominador+=$linha['plano_gestao_media_ponto'];
					}
				elseif ($linha['plano_gestao_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['plano_gestao_media_acao']);
					$numerador+=$obj->fisico_previsto($data)*$linha['plano_gestao_media_ponto'];
					$denominador+=$linha['plano_gestao_media_ponto'];
					}
				elseif ($linha['plano_gestao_media_perspectiva']){
					$obj = new CPerspectiva();
					$obj->load($linha['plano_gestao_media_perspectiva']);
					$numerador+=$obj->fisico_previsto($data)*$linha['plano_gestao_media_ponto'];
					$denominador+=$linha['plano_gestao_media_ponto'];
					}		
				}
			return ($denominador ? $numerador/$denominador : 0);
			}	
		else return $this->pg_percentagem;	
		}
		
		
		
		
		
		
		
		
		
		
	public function fisico_executado($data=null){
		if ($this->pg_tipo_pontuacao=='media_ponderada'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
			$sql = new BDConsulta;
			$sql->adTabela('plano_gestao_media');
			$sql->adCampo('plano_gestao_media_projeto, plano_gestao_media_acao, plano_gestao_media_perspectiva, plano_gestao_media_peso');
			$sql->adOnde('plano_gestao_media_plano_gestao ='.(int)$this->pg_id);
			$sql->adOnde('plano_gestao_media_tipo =\''.$this->pg_tipo_pontuacao.'\'');
			
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['plano_gestao_media_projeto']){
					$obj = new CProjeto(null, null, $data, $linha['plano_gestao_media_projeto']);
					$obj->load($linha['plano_gestao_media_projeto']);
					$numerador+=$obj->projeto_percentagem*$linha['plano_gestao_media_peso'];
					$denominador+=$linha['plano_gestao_media_peso'];
					}
				elseif ($linha['plano_gestao_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['plano_gestao_media_acao']);
					$numerador+=$obj->plano_acao_percentagem*$linha['plano_gestao_media_peso'];
					$denominador+=$linha['plano_gestao_media_peso'];
					}
				elseif ($linha['plano_gestao_media_perspectiva']){
					$obj = new CPerspectiva();
					$obj->load($linha['plano_gestao_media_perspectiva']);
					$numerador+=$obj->fisico_executado($data)*$linha['plano_gestao_media_peso'];
					$denominador+=$linha['plano_gestao_media_peso'];
					}		
				}
			return ($denominador ? $numerador/$denominador : 0);
			}
		elseif ($this->pg_tipo_pontuacao=='pontos_parcial' || $this->pg_tipo_pontuacao=='pontos_completos'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
			$sql = new BDConsulta;
			$sql->adTabela('plano_gestao_media');
			$sql->adCampo('plano_gestao_media_projeto, plano_gestao_media_acao, plano_gestao_media_perspectiva, plano_gestao_media_ponto');
			$sql->adOnde('plano_gestao_media_plano_gestao ='.(int)$this->pg_id);
			$sql->adOnde('plano_gestao_media_tipo =\''.$this->pg_tipo_pontuacao.'\'');
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['plano_gestao_media_projeto']){
					$obj = new CProjeto(null, null, $data, $linha['plano_gestao_media_projeto']);
					$obj->load($linha['plano_gestao_media_projeto']);
					$numerador+=$obj->projeto_percentagem*$linha['plano_gestao_media_ponto'];
					$denominador+=$linha['plano_gestao_media_ponto'];
					}
				elseif ($linha['plano_gestao_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['plano_gestao_media_acao']);
					$numerador+=$obj->plano_acao_percentagem*$linha['plano_gestao_media_ponto'];
					$denominador+=$linha['plano_gestao_media_ponto'];
					}
				elseif ($linha['plano_gestao_media_perspectiva']){
					$obj = new CPerspectiva();
					$obj->load($linha['plano_gestao_media_perspectiva']);
					$numerador+=$obj->fisico_executado($data)*$linha['plano_gestao_media_ponto'];
					$denominador+=$linha['plano_gestao_media_ponto'];
					}		
				}
			return ($denominador ? $numerador/$denominador : 0);
			}	
		else return $this->pg_percentagem;	
		}	
		
		
		
		
		
		
		
		
		

	public function fisico_velocidade($data=null){
		$fisico_previsto=$this->fisico_previsto($data);
		$fisico_executado=$this->fisico_executado($data);
		return ($fisico_previsto ? $fisico_executado/$fisico_previsto : 0);
		}
	
	
	}
	
	

?>