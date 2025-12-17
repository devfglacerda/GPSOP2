<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CMeta extends CAplicObjeto {

	public $pg_meta_id = null;
  public $pg_meta_cia = null;
  public $pg_meta_dept = null;
  public $pg_meta_responsavel = null;
	public $pg_meta_perspectiva = null;
	public $pg_meta_tema = null;
	public $pg_meta_objetivo_estrategico = null;
	public $pg_meta_fator = null;
  public $pg_meta_estrategia = null;
  public $pg_meta_principal_indicador = null;
  public $pg_meta_nome = null;
  public $pg_meta_ordem = null;
  public $pg_meta_prazo = null;
  public $pg_meta_data = null;
  public $pg_meta_oque = null;
  public $pg_meta_descricao = null;
  public $pg_meta_onde = null;
  public $pg_meta_quando = null;
  public $pg_meta_como = null;
  public $pg_meta_porque = null;
  public $pg_meta_quanto = null;
  public $pg_meta_quem = null;
  public $pg_meta_controle = null;
  public $pg_meta_melhorias = null;
  public $pg_meta_metodo_aprendizado = null;
  public $pg_meta_desde_quando = null;
  public $pg_meta_cor = null;
  public $pg_meta_ativo = null;
  public $pg_meta_acesso = null;
  public $pg_meta_tipo = null;
 	public $pg_meta_tipo_pontuacao = null;
  public $pg_meta_percentagem = null;
  public $pg_meta_ponto_alvo = null;
	public $pg_meta_aprovado = null;
	public $pg_meta_moeda = null;
	
	public function __construct() {
		parent::__construct('metas', 'pg_meta_id');
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->pg_meta_id) {
			$ret = $sql->atualizarObjeto('metas', $this, 'pg_meta_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('metas', $this, 'pg_meta_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('metas', $this->pg_meta_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->pg_meta_id);


		$metas_usuarios=getParam($_REQUEST, 'metas_usuarios', null);
		$metas_usuarios=explode(',', $metas_usuarios);
		$sql->setExcluir('metas_usuarios');
		$sql->adOnde('pg_meta_id = '.$this->pg_meta_id);
		$sql->exec();
		$sql->limpar();
		foreach($metas_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('metas_usuarios');
				$sql->adInserir('pg_meta_id', $this->pg_meta_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'pg_meta_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('metas_depts');
		$sql->adOnde('pg_meta_id = '.$this->pg_meta_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('metas_depts');
				$sql->adInserir('pg_meta_id', $this->pg_meta_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('meta_cia');
			$sql->adOnde('meta_cia_meta='.(int)$this->pg_meta_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'meta_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('meta_cia');
						$sql->adInserir('meta_cia_meta', $this->pg_meta_id);
						$sql->adInserir('meta_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		if ($Aplic->profissional){
			$uuid=getParam($_REQUEST, 'uuid', null);
			if ($uuid){
				$sql->adTabela('meta_media');
				$sql->adAtualizar('meta_media_meta', (int)$this->pg_meta_id);
				$sql->adAtualizar('meta_media_uuid', null);
				$sql->adOnde('meta_media_uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();

				$sql->adTabela('meta_meta');
				$sql->adAtualizar('meta_meta_meta', (int)$this->pg_meta_id);
				$sql->adAtualizar('meta_meta_uuid', null);
				$sql->adOnde('meta_meta_uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();

				$sql->adTabela('meta_gestao');
				$sql->adAtualizar('meta_gestao_meta', (int)$this->pg_meta_id);
				$sql->adAtualizar('meta_gestao_uuid', null);
				$sql->adOnde('meta_gestao_uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();



				$sql->adTabela('assinatura');
				$sql->adAtualizar('assinatura_meta', (int)$this->pg_meta_id);
				$sql->adAtualizar('assinatura_uuid', null);
				$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();

				$sql->adTabela('priorizacao');
				$sql->adAtualizar('priorizacao_meta', (int)$this->pg_meta_id);
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
			$sql->adOnde('assinatura_meta='.(int)$this->pg_meta_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_meta='.(int)$this->pg_meta_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_meta='.(int)$this->pg_meta_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('metas');
			$sql->adAtualizar('pg_meta_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('pg_meta_id='.(int)$this->pg_meta_id);
			$sql->exec();
			$sql->limpar();
			}	
				
				

			//limpa as tabelas antigas
			$sql->setExcluir('meta_media');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo !=\''.$this->pg_meta_tipo_pontuacao.'\'');
			$sql->exec();
			$sql->limpar();

			//limpar obervador antigo
			$sql->adTabela('meta_media');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\''.$this->pg_meta_tipo_pontuacao.'\'');
			$sql->adCampo('meta_media_projeto');
			$sql->adOnde('meta_media_projeto > 0');
			$projetos=$sql->carregarColuna();
			$sql->limpar();
			$sql->setExcluir('projeto_observador');
			$sql->adOnde('projeto_observador_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('projeto_observador_acao =\'fisico\'');
			$sql->exec();
			$sql->limpar();
			if (count($projetos)){
				foreach($projetos as $projeto){
					$sql->adTabela('projeto_observador');
					$sql->adInserir('projeto_observador_projeto', $projeto);
					$sql->adInserir('projeto_observador_meta', $this->pg_meta_id);
					$sql->adInserir('projeto_observador_acao', 'fisico');
					$sql->adInserir('projeto_observador_metodo', 'calculo_percentagem');
					$sql->exec();
					$sql->limpar();
					}
				}

			$sql->adTabela('meta_media');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\''.$this->pg_meta_tipo_pontuacao.'\'');
			$sql->adCampo('meta_media_acao');
			$sql->adOnde('meta_media_acao > 0');
			$acoes=$sql->carregarColuna();
			$sql->limpar();
			$sql->setExcluir('plano_acao_observador');
			$sql->adOnde('plano_acao_observador_meta ='.(int)$this->pg_meta_id);
			$sql->exec();
			$sql->limpar();
			if (count($acoes)){
				foreach($acoes as $acao){
					$sql->adTabela('plano_acao_observador');
					$sql->adInserir('plano_acao_observador_plano_acao', $acao);
					$sql->adInserir('plano_acao_observador_meta', $this->pg_meta_id);
					$sql->adInserir('plano_acao_observador_acao', 'fisico');
					$sql->adInserir('plano_acao_observador_metodo', 'calculo_percentagem');
					$sql->exec();
					$sql->limpar();
					}
				}

			}

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	public function check() {
		return null;
		}


	public function podeAcessar() {
		$valor=permiteAcessarMeta($this->pg_meta_acesso, $this->pg_meta_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarMeta($this->pg_meta_acesso, $this->pg_meta_id);
		return $valor;
		}

	public function calculo_percentagem(){
		$tipo=$this->pg_meta_tipo_pontuacao;

		$sql = new BDConsulta;
		$porcentagem=null;
		if (!$tipo) $porcentagem=$this->pg_meta_percentagem;
		elseif($tipo=='media_ponderada'){
			$sql->adTabela('meta_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=meta_media_projeto');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=meta_media_acao');
			$sql->adCampo('
			projeto_percentagem,
			plano_acao_percentagem,
			meta_media_projeto,
			meta_media_acao,
			meta_media_peso
			');

			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'media_ponderada\'');
			$lista = $sql->lista();
			$sql->limpar();
			$numerador=0;
			$denominador=0;

			foreach($lista as $linha){
				if ($linha['meta_media_projeto']) $numerador+=($linha['projeto_percentagem']*$linha['meta_media_peso']);
				elseif ($linha['meta_media_acao']) $numerador+=($linha['plano_acao_percentagem']*$linha['meta_media_peso']);
				$denominador+=$linha['meta_media_peso'];
				}
			$porcentagem=($denominador ? $numerador/$denominador : 0);
			}
		elseif($tipo=='pontos_completos'){

			$sql->adTabela('meta_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=meta_media_projeto');
			$sql->adCampo('SUM(meta_media_ponto)');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'pontos_completos\'');
			$sql->adOnde('projeto_percentagem = 100');
			$sql->adOnde('meta_media_projeto > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('meta_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=meta_media_acao');
			$sql->adCampo('SUM(meta_media_ponto)');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'pontos_completos\'');
			$sql->adOnde('plano_acao_percentagem = 100');
			$sql->adOnde('meta_media_acao > 0');
			$pontos5 = $sql->Resultado();
			$sql->limpar();


			$porcentagem=($this->pg_meta_ponto_alvo ? (($pontos4+$pontos5)/$this->pg_meta_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='pontos_parcial'){
			$sql->adTabela('meta_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=meta_media_projeto');
			$sql->adCampo('SUM(meta_media_ponto*(projeto_percentagem/100))');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('meta_media_projeto > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('meta_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=meta_media_acao');
			$sql->adCampo('SUM(meta_media_ponto*(plano_acao_percentagem/100))');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('meta_media_acao > 0');
			$pontos5 = $sql->Resultado();
			$sql->limpar();

			$porcentagem=($this->pg_meta_ponto_alvo ? (($pontos4+$pontos5)/$this->pg_meta_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='indicador'){
			if ($this->pg_meta_principal_indicador) {
				include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
				$obj_indicador = new Indicador($this->pg_meta_principal_indicador);
				$porcentagem=$obj_indicador->Pontuacao();
				}
			else $porcentagem=0;
			}

		else $porcentagem=0; //caso nao previsto

		if ($porcentagem > 100) $porcentagem=100;
		if ($porcentagem!=$this->pg_meta_percentagem){
			$sql->adTabela('metas');
			$sql->adAtualizar('pg_meta_percentagem', $porcentagem);
			$sql->adOnde('pg_meta_id ='.(int)$this->pg_meta_id);
			$sql->exec();
			$sql->limpar();
			
			$this->disparo_observador('fisico');
			}
		return $porcentagem;
		}


	public function disparo_observador( $acao='fisico'){
		//Quem faz uso desta meta em cálculos de percntagem

		$sql = new BDConsulta;

		$sql->adTabela('meta_observador');
		$sql->adCampo('meta_observador.*');
		$sql->adOnde('meta_observador_meta ='.(int)$this->pg_meta_id);
		if ($acao) $sql->adOnde('meta_observador_acao =\''.$acao.'\'');
		$lista = $sql->lista();
		$sql->limpar();

		$qnt_objetivo=0;
		$qnt_me=0;
		$qnt_fator=0;

		foreach($lista as $linha){
			if ($linha['meta_observador_perspectiva']){
				if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
				$obj= new CPerspectiva();
				$obj->load($linha['meta_observador_perspectiva']);
				if (method_exists($obj, $linha['meta_observador_metodo'])){
					$obj->{$linha['meta_observador_metodo']}();
					}
				}
			elseif ($linha['meta_observador_tema']){
				if (!($qnt_tema++)) require_once BASE_DIR.'/modulos/praticas/tema.class.php';
				$obj= new CTema();
				$obj->load($linha['meta_observador_tema']);
				if (method_exists($obj, $linha['meta_observador_metodo'])){
					$obj->{$linha['meta_observador_metodo']}();
					}
				}
			elseif ($linha['meta_observador_objetivo']){
				if (!($qnt_objetivo++)) require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
				$obj= new CObjetivo();
				$obj->load($linha['meta_observador_objetivo']);
				if (method_exists($obj, $linha['meta_observador_metodo'])){
					$obj->{$linha['meta_observador_metodo']}();
					}
				}
			elseif ($linha['meta_observador_me']){
				if (!($qnt_me++)) require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
				$obj= new CMe();
				$obj->load($linha['meta_observador_me']);
				if (method_exists($obj, $linha['meta_observador_metodo'])){
					$obj->{$linha['meta_observador_metodo']}();
					}
				}
			elseif ($linha['meta_observador_fator']){
				if (!($qnt_fator++)) require_once BASE_DIR.'/modulos/praticas/fator.class.php';
				$obj= new CFator();
				$obj->load($linha['meta_observador_fator']);
				if (method_exists($obj, $linha['meta_observador_metodo'])){
					$obj->{$linha['meta_observador_metodo']}();
					}
				}
			elseif ($linha['meta_observador_estrategia']){
				if (!($qnt_fator++)) require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
				$obj= new CEstrategia();
				$obj->load($linha['meta_observador_estrategia']);
				if (method_exists($obj, $linha['meta_observador_metodo'])){
					$obj->{$linha['meta_observador_metodo']}();
					}
				}
			}
		}





	public function notificar( $post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('metas');
		$sql->adCampo('pg_meta_nome');
		$sql->adOnde('pg_meta_id ='.$this->pg_meta_id);
		$nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['metas_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['metas_usuarios'].')');
			$usuarios1 = $sql->Lista();
			$sql->limpar();
			}
		if ($post['email_outro']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_outro'].')');
			$usuarios2=$sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_responsavel'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->esqUnir('metas', 'metas', 'metas.pg_meta_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('pg_meta_id='.$this->pg_meta_id);
			$usuarios3=$sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_extras']) && $post['email_extras']){
			$extras=explode(',',$post['email_extras']);
			foreach($extras as $chave => $valor) $usuarios4[]=array('usuario_id' => 0, 'nome_usuario' =>'', 'contato_email'=> $valor);
			}



		$usuarios = array_merge((array)$usuarios1, (array)$usuarios2);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios3);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios4);


		$usado_usuario=array();
		$usado_email=array();

		if (isset($post['del']) && $post['del'])$tipo='excluido';
		elseif (isset($post['pg_meta_id']) && $post['pg_meta_id']) $tipo='atualizado';
		else $tipo='incluido';

		foreach($usuarios as $usuario){
			if (!isset($usado[$usuario['usuario_id']]) && !isset($usado[$usuario['contato_email']])){

				if ($usuario['usuario_id']) $usado[$usuario['usuario_id']]=1;
				if ($usuario['contato_email']) $usado[$usuario['contato_email']]=1;
				$email = new Mail;
				$email->De($config['email'], $Aplic->usuario_nome);

                if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                    $email->ResponderPara($Aplic->usuario_email);
                    }
                else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                    $email->ResponderPara($Aplic->usuario_email2);
                    }

				if ($tipo == 'excluido') $titulo=ucfirst($config['meta']).' excluíd'.$config['genero_meta'];
				elseif ($tipo=='atualizado') $titulo=($config['meta']).' atualizad'.$config['genero_meta'];
				else $titulo=($config['meta']).' inserid'.$config['genero_meta'];

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizad'.$config['genero_meta'].' '.$config['genero_meta'].' '.$config['meta'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluíd'.$config['genero_meta'].' '.$config['genero_meta'].' '.$config['meta'].': '.$nome.'<br>';
				else $corpo = 'Inserid'.$config['genero_meta'].' '.$config['genero_meta'].' '.$config['meta'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão d'.$config['genero_meta'].' '.$config['meta'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição d'.$config['genero_meta'].' '.$config['meta'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador d'.$config['genero_meta'].' '.$config['meta'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=meta_ver&pg_meta_id='.$this->pg_meta_id.'\');"><b>Clique para acessar '.$config['genero_meta'].' '.$config['meta'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=meta_ver&pg_meta_id='.$this->pg_meta_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_meta'].' '.$config['meta'].'</b></a>';
						}
					}

				$email->Corpo($corpo_externo, (isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : $localidade_tipo_caract));
				if ($usuario['usuario_id']!=$Aplic->usuario_id && $usuario['usuario_id']) {
					if ($usuario['usuario_id']) msg_email_interno('', $titulo, $corpo_interno,'',$usuario['usuario_id']);
					if ($email->EmailValido($usuario['contato_email']) && $config['email_ativo']) {
						$email->Para($usuario['contato_email'], true);
						$email->Enviar();
						}
					}
				}
			}
		}


	
	public function fisico_previsto($data=null){
		if ($this->pg_meta_tipo_pontuacao=='media_ponderada'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			
			$sql = new BDConsulta;
			$sql->adTabela('meta_media');
			$sql->adCampo('meta_media_projeto, meta_media_acao, meta_media_peso');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\''.$this->pg_meta_tipo_pontuacao.'\'');
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['meta_media_projeto']){
					$obj = new CProjeto();
					$obj->load($linha['meta_media_projeto']);
					$numerador+=$obj->fisico_previsto($data)*$linha['meta_media_peso'];
					$denominador+=$linha['meta_media_peso'];
					}
				elseif ($linha['meta_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['meta_media_acao']);
					$numerador+=$obj->fisico_previsto($data)*$linha['meta_media_peso'];
					$denominador+=$linha['meta_media_peso'];
					}
				}
	
			return ($denominador ? $numerador/$denominador : 0);
			}
		elseif ($this->pg_meta_tipo_pontuacao=='pontos_parcial' || $this->pg_meta_tipo_pontuacao=='pontos_completos'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			
			$sql = new BDConsulta;
			$sql->adTabela('meta_media');
			$sql->adCampo('meta_media_projeto, meta_media_acao, meta_media_ponto');
			$sql->adOnde('meta_media_meta ='.(int)$this->pg_meta_id);
			$sql->adOnde('meta_media_tipo =\''.$this->pg_meta_tipo_pontuacao.'\'');
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['meta_media_projeto']){
					$obj = new CProjeto();
					$obj->load($linha['meta_media_projeto']);
					$numerador+=$obj->fisico_previsto($data)*$linha['meta_media_ponto'];
					$denominador+=$linha['meta_media_ponto'];
					}
				elseif ($linha['meta_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['meta_media_acao']);
					$numerador+=$obj->fisico_previsto($data)*$linha['meta_media_ponto'];
					$denominador+=$linha['meta_media_ponto'];
					}
				}
			return ($denominador ? $numerador/$denominador : 0);
			}
		else return $this->pg_meta_percentagem;
		}


	public function fisico_velocidade($data=null){
		$fisico_previsto=$this->fisico_previsto($data);
		return ($fisico_previsto ? $this->pg_meta_percentagem/$fisico_previsto : 0);
		}




	}

?>