<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/



class CTermoAbertura extends CAplicObjeto {
	public $projeto_abertura_id = null;
	public $projeto_abertura_cia = null;
	public $projeto_abertura_dept = null;
	public $projeto_abertura_projeto = null;
	public $projeto_abertura_demanda = null;
	public $projeto_abertura_nome = null;
	public $projeto_abertura_codigo = null;
	public $projeto_abertura_setor = null;
	public $projeto_abertura_segmento = null;
	public $projeto_abertura_intervencao = null;
	public $projeto_abertura_tipo_intervencao = null;
	public $projeto_abertura_ano = null;
	public $projeto_abertura_sequencial = null;
	public $projeto_abertura_responsavel = null;
	public $projeto_abertura_autoridade = null;
	public $projeto_abertura_gerente_projeto = null;
	public $projeto_abertura_acesso = null;
	public $projeto_abertura_justificativa = null;
	public $projeto_abertura_objetivo = null;
	public $projeto_abertura_escopo = null;
	public $projeto_abertura_nao_escopo = null;
	public $projeto_abertura_tempo = null;
	public $projeto_abertura_custo = null;
	public $projeto_abertura_premissas = null;
	public $projeto_abertura_restricoes = null;
	public $projeto_abertura_riscos = null;
	public $projeto_abertura_infraestrutura = null;
	public $projeto_abertura_descricao = null;
	public $projeto_abertura_objetivos = null;
	public $projeto_abertura_como = null;
	public $projeto_abertura_localizacao = null;
	public $projeto_abertura_beneficiario = null;
	public $projeto_abertura_objetivo_especifico = null;
	public $projeto_abertura_orcamento = null;
	public $projeto_abertura_beneficio = null;
	public $projeto_abertura_produto = null;
	public $projeto_abertura_requisito = null;
	public $projeto_abertura_cor = null;
	public $projeto_abertura_aprovado = null;
	public $projeto_abertura_data = null;
 	public $projeto_abertura_observacao = null;
 	public $projeto_abertura_recusa = null;
 	public $projeto_abertura_aprovacao = null;
 	public $projeto_abertura_ativo = null;
 	public $projeto_abertura_moeda = null;

	public function __construct() {
		parent::__construct('projeto_abertura', 'projeto_abertura_id');
		}

	public function excluir( $oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('projeto_abertura_id', null)==$this->projeto_abertura_id) $Aplic->setEstado('projeto_abertura_id', null);
		parent::excluir();
		return null;
		
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->projeto_abertura_id) {
			$ret = $sql->atualizarObjeto('projeto_abertura', $this, 'projeto_abertura_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('projeto_abertura', $this, 'projeto_abertura_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('termo_abertura', $this->projeto_abertura_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->projeto_abertura_id);

		$projeto_abertura_usuarios=getParam($_REQUEST, 'projeto_abertura_usuarios', '');
		$projeto_abertura_usuarios=explode(',', $projeto_abertura_usuarios);
		$sql->setExcluir('projeto_abertura_usuarios');
		$sql->adOnde('projeto_abertura_id = '.$this->projeto_abertura_id);
		$sql->exec();
		$sql->limpar();
		foreach($projeto_abertura_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('projeto_abertura_usuarios');
				$sql->adInserir('projeto_abertura_id', $this->projeto_abertura_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}


		$patrocinadores_selecionados=getParam($_REQUEST, 'projeto_abertura_patrocinadores', '');
		$patrocinadores_selecionados=explode(',', $patrocinadores_selecionados);
		$sql->setExcluir('projeto_abertura_patrocinadores');
		$sql->adOnde('projeto_abertura_id = '.$this->projeto_abertura_id);
		$sql->exec();
		$sql->limpar();
		foreach($patrocinadores_selecionados as $chave => $contato_id){
			if($contato_id){
				$sql->adTabela('projeto_abertura_patrocinadores');
				$sql->adInserir('projeto_abertura_id', $this->projeto_abertura_id);
				$sql->adInserir('contato_id', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		$interessados_selecionados=getParam($_REQUEST, 'projeto_abertura_interessados', '');
		$interessados_selecionados=explode(',', $interessados_selecionados);
		$sql->setExcluir('projeto_abertura_interessados');
		$sql->adOnde('projeto_abertura_id = '.$this->projeto_abertura_id);
		$sql->exec();
		$sql->limpar();
		foreach($interessados_selecionados as $chave => $contato_id){
			if($contato_id){
				$sql->adTabela('projeto_abertura_interessados');
				$sql->adInserir('projeto_abertura_id', $this->projeto_abertura_id);
				$sql->adInserir('contato_id', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		$sql->adTabela('demandas');
		$sql->adAtualizar('demanda_termo_abertura', (int)$this->projeto_abertura_id);
		$sql->adOnde('demanda_id='.(int)$this->projeto_abertura_demanda);
		$sql->exec();
		$sql->limpar();

		$projeto_abertura_depts=getParam($_REQUEST, 'projeto_abertura_depts', null);
		$projeto_abertura_depts=explode(',', $projeto_abertura_depts);
		$sql->setExcluir('projeto_abertura_dept');
		$sql->adOnde('projeto_abertura_dept_projeto_abertura = '.$this->projeto_abertura_id);
		$sql->exec();
		$sql->limpar();
		foreach($projeto_abertura_depts as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('projeto_abertura_dept');
				$sql->adInserir('projeto_abertura_dept_projeto_abertura', $this->projeto_abertura_id);
				$sql->adInserir('projeto_abertura_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('projeto_abertura_cia');
			$sql->adOnde('projeto_abertura_cia_projeto_abertura='.(int)$this->projeto_abertura_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'projeto_abertura_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('projeto_abertura_cia');
						$sql->adInserir('projeto_abertura_cia_projeto_abertura', $this->projeto_abertura_id);
						$sql->adInserir('projeto_abertura_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('projeto_abertura_gestao');
			$sql->adAtualizar('projeto_abertura_gestao_projeto_abertura', (int)$this->projeto_abertura_id);
			$sql->adAtualizar('projeto_abertura_gestao_uuid', null);
			$sql->adOnde('projeto_abertura_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}
			
		if ($uuid && $Aplic->profissional){
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_abertura', (int)$this->projeto_abertura_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}
			
			
			
			
		//verificar aprovacao
		if ($Aplic->profissional) {
		
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_abertura='.(int)$this->projeto_abertura_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_abertura='.(int)$this->projeto_abertura_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_abertura='.(int)$this->projeto_abertura_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('projeto_abertura');
			$sql->adAtualizar('projeto_abertura_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('projeto_abertura_id='.(int)$this->projeto_abertura_id);
			$sql->exec();
			$sql->limpar();
			}


		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}
		
	public function custo_estimado(){
		global $Aplic, $config;
		$sql = new BDConsulta;
		$sql->adTabela('projeto_abertura_custo');
		$sql->adCampo('SUM((projeto_abertura_custo_quantidade*projeto_abertura_custo_custo*projeto_abertura_custo_cotacao)*((100+projeto_abertura_custo_bdi)/100)) AS total');
		$sql->adOnde('projeto_abertura_custo_projeto_abertura ='.(int)$this->projeto_abertura_id);
		if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('projeto_abertura_custo_aprovado = 1');
		$total=$sql->Resultado();
		$sql->limpar();
		
		if ($this->projeto_abertura_moeda!=1) $total=$total/cotacao($this->projeto_abertura_moeda, date('Y-m-d'));

		return $total;
		}	

	public function check() {
		return null;
		}

	public function podeAcessar() {
		$valor=permiteAcessarTermoAbertura($this->projeto_abertura_acesso, $this->projeto_abertura_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarTermoAbertura($this->projeto_abertura_acesso, $this->projeto_abertura_id);
		return $valor;
		}

public function getCodigo( $completo=true){
		if ($this->projeto_abertura_tipo_intervencao && $this->projeto_abertura_ano && $this->projeto_abertura_sequencial){
			if ($this->projeto_abertura_sequencial<10) $sequencial='000'.$this->projeto_abertura_sequencial;
			elseif ($this->projeto_abertura_sequencial<100) $sequencial='00'.$this->projeto_abertura_sequencial;
			elseif ($this->projeto_abertura_sequencial<1000) $sequencial='0'.$this->projeto_abertura_sequencial;
			else $sequencial=$this->projeto_abertura_sequencial;
			return substr($this->projeto_abertura_tipo_intervencao, 0, 2).($completo ? '.' : '').substr($this->projeto_abertura_tipo_intervencao, 2, 2).($completo ? '.' : '').substr($this->projeto_abertura_tipo_intervencao, 4, 2).($completo ? '.' : '').substr($this->projeto_abertura_tipo_intervencao, 6, 3).($completo ? '.' : '').$sequencial.($completo ? '/' : '').$this->projeto_abertura_ano;
			}
		else return '';
		}


	public function setSequencial(){
		if (!$this->projeto_abertura_sequencial){
			$sql = new BDConsulta;
			$sql->adTabela('projeto_abertura');
			$sql->adCampo('max(projeto_abertura_sequencial)');
			$sql->adOnde('projeto_abertura_cia='.(int)$this->projeto_abertura_cia);
			$maior_sequencial= (int)$sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_abertura');
			$sql->adAtualizar('projeto_abertura_sequencial', ($maior_sequencial+1));
			$sql->adOnde('projeto_abertura_id = '.$this->projeto_abertura_id);
			$retorno=$sql->exec();
			$sql->limpar();
			return $retorno;
			}
		}

	public function getSetor(){
		if ($this->projeto_abertura_setor){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Setor"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_abertura_setor.'"');
			$projeto_abertura_setor= $sql->Resultado();
			$sql->limpar();
			return $projeto_abertura_setor;
			}
		else return '';
		}

	public function getSegmento(){
		if ($this->projeto_abertura_segmento){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Segmento"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_abertura_segmento.'"');
			$projeto_abertura_segmento= $sql->Resultado();
			$sql->limpar();
			return $projeto_abertura_segmento;
			}
		else return '';
		}

	public function getIntervencao(){
		if ($this->projeto_abertura_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Intervencao"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_abertura_intervencao.'"');
			$projeto_abertura_intervencao= $sql->Resultado();
			$sql->limpar();
			return $projeto_abertura_intervencao;
			}
		else return '';
		}

	public function getTipoIntervencao(){
		if ($this->projeto_abertura_tipo_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_abertura_tipo_intervencao.'"');
			$projeto_abertura_tipo_intervencao= $sql->Resultado();
			$sql->limpar();
			return $projeto_abertura_tipo_intervencao;
			}
		else return '';
		}

	public function notificar( $post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('projeto_abertura');
		$sql->adCampo('projeto_abertura_nome');
		$sql->adOnde('projeto_abertura_id ='.$this->projeto_abertura_id);
		$projeto_abertura_nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if (isset($post['projeto_abertura_usuarios']) && $post['projeto_abertura_usuarios'] && isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['projeto_abertura_usuarios'].')');
			$usuarios1 = $sql->Lista();
			$sql->limpar();
			}
		if (isset($post['email_outro']) && $post['email_outro']){
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
			$sql->esqUnir('projeto_abertura', 'projeto_abertura', 'projeto_abertura.projeto_abertura_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('projeto_abertura_id='.$this->projeto_abertura_id);
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

		if (isset($post['excluir']) && $post['excluir'])$tipo='excluido';
		elseif (isset($post['projeto_abertura_id']) && $post['projeto_abertura_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') {
					$email->Assunto('Excluído termo de abertura', $localidade_tipo_caract);
					$titulo='Excluído termo de abertura';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado termo de abertura', $localidade_tipo_caract);
					$titulo='Atualizado termo de abertura';
					}
				else {
					$email->Assunto('Inserido termo de abertura', $localidade_tipo_caract);
					$titulo='Inserido termo de abertura';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado termo de abertura: '.$projeto_abertura_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído termo de abertura: '.$projeto_abertura_nome.'<br>';
				else $corpo = 'Inserido termo de abertura: '.$projeto_abertura_nome.'<br>';

				
				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do termo de abertura:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do termo de abertura:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do termo de abertura:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				
				$corpo_interno=$corpo;
				$corpo_externo=$corpo;
				
				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$this->projeto_abertura_id.'\');"><b>Clique para acessar o termo de abertura</b></a>';
					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=projetos&a=termo_abertura_ver&projeto_abertura_id='.$this->projeto_abertura_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o termo de abertura</b></a>';
						}
					}
				$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
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

	}



function termo_abertura_quantidade(
	$a=null,
	$tab=null, 
	$envolvimento=null, 
	$cia_id=null, 
	$lista_cias=null, 
	$dept_id=null, 
	$lista_depts=null, 
	$pesquisar_texto=null, 
	$responsavel=null, 
	$projeto_abertura_setor=null, 
	$projeto_abertura_segmento=null, 
	$projeto_abertura_intervencao=null, 
	$projeto_abertura_tipo_intervencao=null,
	
	$projeto_id=null,
	$tarefa_id=null,
	$pg_perspectiva_id=null,
	$tema_id=null,
	$objetivo_id=null,
	$fator_id=null,
	$pg_estrategia_id=null,
	$pg_meta_id=null,
	$pratica_id=null,
	$pratica_indicador_id=null,
	$plano_acao_id=null,
	$canvas_id=null,
	$risco_id=null,
	$risco_resposta_id=null,
	$calendario_id=null,
	$monitoramento_id=null,
	$ata_id=null,
	$mswot_id=null,
	$swot_id=null,
	$operativo_id=null,
	$instrumento_id=null,
	$recurso_id=null,
	$problema_id=null,
	$demanda_id=null,
	$programa_id=null,
	$licao_id=null,
	$evento_id=null,
	$link_id=null,
	$avaliacao_id=null,
	$tgn_id=null,
	$brainstorm_id=null,
	$gut_id=null,
	$causa_efeito_id=null,
	$arquivo_id=null,
	$forum_id=null,
	$checklist_id=null,
	$agenda_id=null,
	$agrupamento_id=null,
	$patrocinador_id=null,
	$template_id=null,
	$painel_id=null,
	$painel_odometro_id=null,
	$painel_composicao_id=null,
	$tr_id=null,
	$me_id=null,
	$plano_acao_item_id=null,
	$beneficio_id=null,
	$painel_slideshow_id=null,
	$projeto_viabilidade_id=null,
	$projeto_abertura_id=null,
	$pg_id=null,
	$ssti_id=null,
	$laudo_id=null,
	$trelo_id=null,
	$trelo_cartao_id=null,
	$pdcl_id=null,
	$pdcl_item_id=null
	){
	global $Aplic;
	
	$sql = new BDConsulta;
	
	$sql->adTabela('projeto_abertura');
	$sql->adCampo('count(projeto_abertura.projeto_abertura_id)');
	

	if (trim($pesquisar_texto)) $sql->adOnde('projeto_abertura_nome LIKE \'%'.$pesquisar_texto.'%\' OR projeto_abertura_observacao LIKE \'%'.$pesquisar_texto.'%\'');
	
	if ($dept_id && !$lista_depts) {
		$sql->esqUnir('projeto_abertura_dept','projeto_abertura_dept', 'projeto_abertura_dept_projeto_abertura=projeto_abertura.projeto_abertura_id');
		$sql->adOnde('projeto_abertura_dept='.(int)$dept_id.' OR projeto_abertura_dept_dept='.(int)$dept_id);
		}
	elseif ($lista_depts) {
		$sql->esqUnir('projeto_abertura_dept','projeto_abertura_dept', 'projeto_abertura_dept_projeto_abertura=projeto_abertura.projeto_abertura_id');
		$sql->adOnde('projeto_abertura_dept IN ('.$lista_depts.') OR projeto_abertura_dept_dept IN ('.$lista_depts.')');
		}	
	elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('projeto_abertura_cia', 'projeto_abertura_cia', 'projeto_abertura.projeto_abertura_id=projeto_abertura_cia_projeto_abertura');
		$sql->adOnde('projeto_abertura_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR projeto_abertura_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}
	elseif ($cia_id && !$lista_cias) $sql->adOnde('projeto_abertura_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('projeto_abertura_cia IN ('.$lista_cias.')');
	
	if ($projeto_abertura_setor) $sql->adOnde('projeto_abertura_setor IN ('.$projeto_abertura_setor.')');
	if ($projeto_abertura_segmento) $sql->adOnde('projeto_abertura_segmento IN ('.$projeto_abertura_segmento.')');
	if ($projeto_abertura_intervencao) $sql->adOnde('projeto_abertura_intervencao IN ('.$projeto_abertura_intervencao.')');
	if ($projeto_abertura_tipo_intervencao) $sql->adOnde('projeto_abertura_tipo_intervencao IN ('.$projeto_abertura_tipo_intervencao.')');
	
	if($responsavel) {
		$sql->esqUnir('projeto_abertura_usuarios', 'projeto_abertura_usuarios', 'projeto_abertura_usuarios.projeto_abertura_id = projeto_abertura.projeto_abertura_id');
		$sql->adOnde('projeto_abertura_responsavel IN ('.$responsavel.') OR projeto_abertura_usuarios.usuario_id IN ('.$responsavel.')');
		}
	

	$sql->esqUnir('projeto_abertura_gestao','projeto_abertura_gestao','projeto_abertura_gestao_projeto_abertura = projeto_abertura.projeto_abertura_id');
	if ($tarefa_id) $sql->adOnde('projeto_abertura_gestao_tarefa IN ('.$tarefa_id.')');
	elseif ($projeto_id){
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=projeto_abertura_gestao_tarefa');
		$sql->adOnde('projeto_abertura_gestao_projeto IN ('.$projeto_id.') OR tarefa_projeto IN ('.$projeto_id.')');
		}
	elseif ($pg_perspectiva_id) $sql->adOnde('projeto_abertura_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('projeto_abertura_gestao_tema IN ('.$tema_id.')');
	elseif ($objetivo_id) $sql->adOnde('projeto_abertura_gestao_objetivo IN ('.$objetivo_id.')');
	elseif ($fator_id) $sql->adOnde('projeto_abertura_gestao_fator IN ('.$fator_id.')');
	elseif ($pg_estrategia_id) $sql->adOnde('projeto_abertura_gestao_estrategia IN ('.$pg_estrategia_id.')');
	elseif ($pg_meta_id) $sql->adOnde('projeto_abertura_gestao_meta IN ('.$pg_meta_id.')');
	elseif ($pratica_id) $sql->adOnde('projeto_abertura_gestao_pratica IN ('.$pratica_id.')');
	elseif ($pratica_indicador_id) $sql->adOnde('projeto_abertura_gestao_indicador IN ('.$pratica_indicador_id.')');
	elseif ($plano_acao_id) $sql->adOnde('projeto_abertura_gestao_acao IN ('.$plano_acao_id.')');
	elseif ($canvas_id) $sql->adOnde('projeto_abertura_gestao_canvas IN ('.$canvas_id.')');
	elseif ($risco_id) $sql->adOnde('projeto_abertura_gestao_risco IN ('.$risco_id.')');
	elseif ($risco_resposta_id) $sql->adOnde('projeto_abertura_gestao_risco_resposta IN ('.$risco_resposta_id.')');
	elseif ($calendario_id) $sql->adOnde('projeto_abertura_gestao_calendario IN ('.$calendario_id.')');
	elseif ($monitoramento_id) $sql->adOnde('projeto_abertura_gestao_monitoramento IN ('.$monitoramento_id.')');
	elseif ($ata_id) $sql->adOnde('projeto_abertura_gestao_ata IN ('.$ata_id.')');
	elseif ($mswot_id) $sql->adOnde('projeto_abertura_gestao_mswot IN ('.$mswot_id.')');
	elseif ($swot_id) $sql->adOnde('projeto_abertura_gestao_swot IN ('.$swot_id.')');
	elseif ($operativo_id) $sql->adOnde('projeto_abertura_gestao_operativo IN ('.$operativo_id.')');
	elseif ($instrumento_id) $sql->adOnde('projeto_abertura_gestao_instrumento IN ('.$instrumento_id.')');
	elseif ($recurso_id) $sql->adOnde('projeto_abertura_gestao_recurso IN ('.$recurso_id.')');
	elseif ($problema_id) $sql->adOnde('projeto_abertura_gestao_problema IN ('.$problema_id.')');
	elseif ($demanda_id) $sql->adOnde('projeto_abertura_gestao_demanda IN ('.$demanda_id.')');
	elseif ($programa_id) $sql->adOnde('projeto_abertura_gestao_programa IN ('.$programa_id.')');
	elseif ($licao_id) $sql->adOnde('projeto_abertura_gestao_licao IN ('.$licao_id.')');
	elseif ($evento_id) $sql->adOnde('projeto_abertura_gestao_evento IN ('.$evento_id.')');
	elseif ($link_id) $sql->adOnde('projeto_abertura_gestao_link IN ('.$link_id.')');
	elseif ($avaliacao_id) $sql->adOnde('projeto_abertura_gestao_avaliacao IN ('.$avaliacao_id.')');
	elseif ($tgn_id) $sql->adOnde('projeto_abertura_gestao_tgn IN ('.$tgn_id.')');
	elseif ($brainstorm_id) $sql->adOnde('projeto_abertura_gestao_brainstorm IN ('.$brainstorm_id.')');
	elseif ($gut_id) $sql->adOnde('projeto_abertura_gestao_gut IN ('.$gut_id.')');
	elseif ($causa_efeito_id) $sql->adOnde('projeto_abertura_gestao_causa_efeito IN ('.$causa_efeito_id.')');
	elseif ($arquivo_id) $sql->adOnde('projeto_abertura_gestao_arquivo IN ('.$arquivo_id.')');
	elseif ($forum_id) $sql->adOnde('projeto_abertura_gestao_forum IN ('.$forum_id.')');
	elseif ($checklist_id) $sql->adOnde('projeto_abertura_gestao_checklist IN ('.$checklist_id.')');
	elseif ($agenda_id) $sql->adOnde('projeto_abertura_gestao_agenda IN ('.$agenda_id.')');
	elseif ($agrupamento_id) $sql->adOnde('projeto_abertura_gestao_agrupamento IN ('.$agrupamento_id.')');
	elseif ($patrocinador_id) $sql->adOnde('projeto_abertura_gestao_patrocinador IN ('.$patrocinador_id.')');
	elseif ($template_id) $sql->adOnde('projeto_abertura_gestao_template IN ('.$template_id.')');
	elseif ($painel_id) $sql->adOnde('projeto_abertura_gestao_painel IN ('.$painel_id.')');
	elseif ($painel_odometro_id) $sql->adOnde('projeto_abertura_gestao_painel_odometro IN ('.$painel_odometro_id.')');
	elseif ($painel_composicao_id) $sql->adOnde('projeto_abertura_gestao_painel_composicao IN ('.$painel_composicao_id.')');
	elseif ($tr_id) $sql->adOnde('projeto_abertura_gestao_tr IN ('.$tr_id.')');
	elseif ($me_id) $sql->adOnde('projeto_abertura_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('projeto_abertura_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('projeto_abertura_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('projeto_abertura_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('projeto_abertura_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('projeto_abertura_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('projeto_abertura_gestao_plano_gestao IN ('.$pg_id.')');
	elseif ($ssti_id) $sql->adOnde('projeto_abertura_gestao_ssti IN ('.$ssti_id.')');
	elseif ($laudo_id) $sql->adOnde('projeto_abertura_gestao_laudo IN ('.$laudo_id.')');
	elseif ($trelo_id) $sql->adOnde('projeto_abertura_gestao_trelo IN ('.$trelo_id.')');
	elseif ($trelo_cartao_id) $sql->adOnde('projeto_abertura_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
	elseif ($pdcl_id) $sql->adOnde('projeto_abertura_gestao_pdcl IN ('.$pdcl_id.')');
	elseif ($pdcl_item_id) $sql->adOnde('projeto_abertura_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
	
	if ($a!='termo_abertura_lista') $sql->adOnde('projeto_abertura_ativo=1'); 	
	elseif ($tab==0) $sql->adOnde('projeto_abertura_aprovado=0');	
	elseif ($tab==1) $sql->adOnde('projeto_abertura_aprovado=1');	
	elseif ($tab==2) $sql->adOnde('projeto_abertura_aprovado=-1');	
	elseif ($tab==3) $sql->adOnde('projeto_abertura_projeto IS NOT NULL');	
	elseif ($tab==4) $sql->adOnde('projeto_abertura_ativo=1');
	elseif ($tab==5) $sql->adOnde('projeto_abertura_ativo!=1');
	
	$qnt=$sql->Resultado();
	$sql->limpar();
	return $qnt;
	}
?>