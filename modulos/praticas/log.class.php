<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CLog extends CAplicObjeto {
	public $log_id = null;
	public $log_projeto = null;
	public $log_tarefa = null;
	public $log_perspectiva = null;
	public $log_tema = null;
	public $log_objetivo = null;
	public $log_fator = null;
	public $log_estrategia = null;
	public $log_meta = null;
	public $log_pratica = null;
	public $log_indicador = null;
	public $log_acao = null;
	public $log_canvas = null;
	public $log_risco = null;
	public $log_risco_resposta = null;
	public $log_calendario = null;
	public $log_monitoramento = null;
	public $log_ata = null;
	public $log_mswot = null;
	public $log_swot = null;
	public $log_operativo = null;
	public $log_instrumento = null;
	public $log_recurso = null;
	public $log_problema = null;
	public $log_demanda = null;
	public $log_programa = null;
	public $log_licao = null;
	public $log_evento = null;
	public $log_link = null;
	public $log_avaliacao = null;
	public $log_tgn = null;
	public $log_brainstorm = null;
	public $log_gut = null;
	public $log_causa_efeito = null;
	public $log_arquivo = null;
	public $log_forum = null;
	public $log_checklist = null;
	public $log_agenda = null;
	public $log_agrupamento = null;
	public $log_patrocinador = null;
	public $log_template = null;
	public $log_painel = null;
	public $log_painel_odometro = null;
	public $log_painel_composicao = null;
	public $log_tr = null;
	public $log_me = null;
	public $log_acao_item = null;
	public $log_beneficio = null;
	public $log_painel_slideshow = null;
	public $log_projeto_viabilidade = null;
	public $log_projeto_abertura = null;
	public $log_plano_gestao = null;
	public $log_ssti = null;
  public $log_laudo = null;
  public $log_trelo = null;
  public $log_trelo_cartao = null;
  public $log_pdcl = null;
  public $log_pdcl_item = null;
	public $log_tipo_problema = null;
	public $log_criador = null;
	public $log_correcao = null;
	public $log_horas = null;
	public $log_descricao = null;
	public $log_corrigir = null;
	public $log_referencia = null;
	public $log_nome = null;
	public $log_data = null;
	public $log_url_relacionada = null;
	public $log_acesso = null;
	public $log_reg_mudanca_percentagem = null;
	public $log_reg_mudanca_status = null;
	public $log_reg_mudanca_fase = null;
	public $log_tipo = null;

	public function __construct() {
		global $Aplic;
		parent::__construct('log', 'log_id');
		}

	public function arrumarTodos() {
		$descricaoComEspacos = $this->log_descricao;
		parent::arrumarTodos();
		$this->log_descricao = $descricaoComEspacos;
		}

	public function check() {
		return null;
		}


	public function podeAcessar() {
		if ($this->log_acao)$podeAcessar=permiteAcessarPlanoAcao($this->log_acesso,$this->log_acao);
		elseif ($this->log_agenda)$podeAcessar=true;
		elseif ($this->log_agrupamento)$podeAcessar=permiteAcessarAgrupamento($this->log_acesso,$this->log_agrupamento);
		elseif ($this->log_arquivo)$podeAcessar=permiteAcessarArquivo($this->log_acesso,$this->log_arquivo);
		elseif ($this->log_ata)$podeAcessar=permiteAcessarAta($this->log_acesso,$this->log_ata);
		elseif ($this->log_avaliacao)$podeAcessar=permiteAcessarAvaliacao($this->log_acesso,$this->log_avaliacao);
		elseif ($this->log_brainstorm)$podeAcessar=permiteAcessarBrainstorm($this->log_acesso,$this->log_brainstorm);
		elseif ($this->log_calendario)$podeAcessar=permiteAcessarCalendario($this->log_acesso,$this->log_calendario);
		elseif ($this->log_canvas)$podeAcessar=permiteAcessarCanvas($this->log_acesso,$this->log_canvas);
		elseif ($this->log_causa_efeito)$podeAcessar=permiteAcessarCausa_efeito($this->log_acesso,$this->log_causa_efeito);
		elseif ($this->log_checklist)$podeAcessar=permiteAcessarChecklist($this->log_acesso,$this->log_checklist);
		elseif ($this->log_demanda)$podeAcessar=permiteAcessarDemanda($this->log_acesso,$this->log_demanda);
		elseif ($this->log_estrategia)$podeAcessar=permiteAcessarEstrategia($this->log_acesso,$this->log_estrategia);
		elseif ($this->log_evento)$podeAcessar=permiteAcessarEvento($this->log_acesso,$this->log_evento);
		elseif ($this->log_fator)$podeAcessar=permiteAcessarFator($this->log_acesso,$this->log_fator);
		elseif ($this->log_forum)$podeAcessar=permiteAcessarForum($this->log_acesso,$this->log_forum);
		elseif ($this->log_gut)$podeAcessar=permiteAcessarGut($this->log_acesso,$this->log_gut);
		elseif ($this->log_indicador)$podeAcessar=permiteAcessarIndicador($this->log_acesso,$this->log_indicador);
		elseif ($this->log_instrumento)$podeAcessar=permiteAcessarInstrumento($this->log_acesso,$this->log_instrumento);
		elseif ($this->log_licao)$podeAcessar=permiteAcessarLicao($this->log_acesso,$this->log_licao);
		elseif ($this->log_link)$podeAcessar=permiteAcessarLink($this->log_acesso,$this->log_link);
		elseif ($this->log_meta)$podeAcessar=permiteAcessarMeta($this->log_acesso,$this->log_meta);
		elseif ($this->log_monitoramento)$podeAcessar=permiteAcessarMonitoramento($this->log_acesso,$this->log_monitoramento);
		elseif ($this->log_objetivo)$podeAcessar=permiteAcessarObjetivo($this->log_acesso,$this->log_objetivo);
		elseif ($this->log_operativo)$podeAcessar=permiteAcessarOperativo($this->log_acesso,$this->log_operativo);
		elseif ($this->log_patrocinador)$podeAcessar=permiteAcessarPatrocinador($this->log_acesso,$this->log_patrocinador);
		elseif ($this->log_problema)$podeAcessar=permiteAcessarProblema($this->log_acesso,$this->log_problema);
		elseif ($this->log_perspectiva)$podeAcessar=permiteAcessarPerspectiva($this->log_acesso,$this->log_perspectiva);
		elseif ($this->log_pratica)$podeAcessar=permiteAcessarPratica($this->log_acesso,$this->log_pratica);
		elseif ($this->log_programa)$podeAcessar=permiteAcessarPrograma($this->log_acesso,$this->log_programa);
		elseif ($this->log_recurso)$podeAcessar=permiteAcessarRecurso($this->log_acesso,$this->log_recurso);
		elseif ($this->log_risco)$podeAcessar=permiteAcessarRisco($this->log_acesso,$this->log_risco);
		elseif ($this->log_risco_resposta)$podeAcessar=permiteAcessarRiscoResposta($this->log_acesso,$this->log_risco_resposta);
		elseif ($this->log_mswot)$podeAcessar=permiteAcessarMSWOT($this->log_acesso,$this->log_mswot);
		elseif ($this->log_swot)$podeAcessar=permiteAcessarSWOT($this->log_acesso,$this->log_swot);
		elseif ($this->log_tarefa)$podeAcessar=permiteAcessar($this->log_acesso,$this->log_projeto, $this->log_tarefa);
		elseif ($this->log_projeto)$podeAcessar=permiteAcessar($this->log_acesso,$this->log_projeto);
		elseif ($this->log_tema)$podeAcessar=permiteAcessarTema($this->log_acesso,$this->log_tema);
		elseif ($this->log_template)$podeAcessar=permiteAcessarTemplate($this->log_acesso,$this->log_template);
		elseif ($this->log_tgn)$podeAcessar=permiteAcessarTgn($this->log_acesso,$this->log_tgn);
		elseif ($this->log_painel)$podeAcessar=permiteAcessarPainel($this->log_acesso,$this->log_painel);
		elseif ($this->log_painel_odometro)$podeAcessar=permiteAcessarOdometro($this->log_acesso,$this->log_painel_odometro);
		elseif ($this->log_painel_composicao)$podeAcessar=permiteAcessarPainelComposicao($this->log_acesso,$this->log_painel_composicao);
		elseif ($this->log_tr)$podeAcessar=permiteAcessarTR($this->log_acesso,$this->log_tr);
		elseif ($this->log_me)$podeAcessar=permiteAcessarMe($this->log_acesso,$this->log_me);
		elseif ($this->log_acao_item)$podeAcessar=permiteAcessarPlanoAcaoItem($this->log_acesso,$this->log_acao_item);
		elseif ($this->log_beneficio)$podeAcessar=permiteAcessarBeneficio($this->log_acesso,$this->log_beneficio);
		elseif ($this->log_painel_slideshow)$podeAcessar=permiteAcessarPainelSlideShow($this->log_acesso,$this->log_painel_slideshow);
		elseif ($this->log_projeto_viabilidade)$podeAcessar=permiteAcessarViabilidade($this->log_acesso,$this->log_projeto_viabilidade);
		elseif ($this->log_projeto_abertura)$podeAcessar=permiteAcessarTermoAbertura($this->log_acesso,$this->log_projeto_abertura);
		elseif ($this->log_plano_gestao)$podeAcessar=permiteAcessarPlanoGestao($this->log_acesso,$this->log_plano_gestao);
		elseif ($this->log_ssti)$podeAcessar=permiteAcessarSSTI($this->log_acesso,$this->log_ssti);
		elseif ($this->log_laudo)$podeAcessar=permiteAcessarLaudo($this->log_acesso,$this->log_laudo);
		elseif ($this->log_trelo)$podeAcessar=permiteAcessarTrelo($this->log_acesso,$this->log_trelo);
		elseif ($this->log_trelo_cartao)$podeAcessar=permiteAcessarTreloCartao($this->log_acesso,$this->log_trelo_cartao);
		elseif ($this->log_pdcl)$podeAcessar=permiteAcessarPDCL($this->log_acesso,$this->log_pdcl);
		elseif ($this->log_pdcl_item)$podeAcessar=permiteAcessarPDCLItem($this->log_acesso,$this->log_pdcl_item);
		else $podeAcessar=false;
		return $podeAcessar;
		}

	public function podeEditar() {
		if ($this->log_acao)$podeEditar=permiteEditarPlanoAcao($this->log_acesso,$this->log_acao);
		elseif ($this->log_agenda)$podeEditar=true;
		elseif ($this->log_agrupamento)$podeEditar=permiteEditarAgrupamento($this->log_acesso,$this->log_agrupamento);
		elseif ($this->log_arquivo)$podeEditar=permiteEditarArquivo($this->log_acesso,$this->log_arquivo);
		elseif ($this->log_ata)$podeEditar=permiteEditarAta($this->log_acesso,$this->log_ata);
		elseif ($this->log_avaliacao)$podeEditar=permiteEditarAvaliacao($this->log_acesso,$this->log_avaliacao);
		elseif ($this->log_tgn)$podeEditar=permiteEditarTgn($this->log_acesso,$this->log_tgn);
		elseif ($this->log_brainstorm)$podeEditar=permiteEditarBrainstorm($this->log_acesso,$this->log_brainstorm);
		elseif ($this->log_calendario)$podeEditar=permiteEditarCalendario($this->log_acesso,$this->log_calendario);
		elseif ($this->log_canvas)$podeEditar=permiteEditarCanvas($this->log_acesso,$this->log_canvas);
		elseif ($this->log_causa_efeito)$podeEditar=permiteEditarCausa_efeito($this->log_acesso,$this->log_causa_efeito);
		elseif ($this->log_checklist)$podeEditar=permiteEditarChecklist($this->log_acesso,$this->log_checklist);
		elseif ($this->log_demanda)$podeEditar=permiteEditarDemanda($this->log_acesso,$this->log_demanda);
		elseif ($this->log_estrategia)$podeEditar=permiteEditarEstrategia($this->log_acesso,$this->log_estrategia);
		elseif ($this->log_evento)$podeEditar=permiteEditarEvento($this->log_acesso,$this->log_evento);
		elseif ($this->log_fator)$podeEditar=permiteEditarFator($this->log_acesso,$this->log_fator);
		elseif ($this->log_forum)$podeEditar=permiteEditarForum($this->log_acesso,$this->log_forum);
		elseif ($this->log_gut)$podeEditar=permiteEditarGut($this->log_acesso,$this->log_gut);
		elseif ($this->log_indicador)$podeEditar=permiteEditarIndicador($this->log_acesso,$this->log_indicador);
		elseif ($this->log_instrumento)$podeEditar=permiteEditarInstrumento($this->log_acesso,$this->log_instrumento);
		elseif ($this->log_licao)$podeEditar=permiteEditarLicao($this->log_acesso,$this->log_licao);
		elseif ($this->log_link)$podeEditar=permiteEditarLink($this->log_acesso,$this->log_link);
		elseif ($this->log_meta)$podeEditar=permiteEditarMeta($this->log_acesso,$this->log_meta);
		elseif ($this->log_monitoramento)$podeEditar=permiteEditarMonitoramento($this->log_acesso,$this->log_monitoramento);
		elseif ($this->log_objetivo)$podeEditar=permiteEditarObjetivo($this->log_acesso,$this->log_objetivo);
		elseif ($this->log_operativo)$podeEditar=permiteEditarOperativo($this->log_acesso,$this->log_operativo);
		elseif ($this->log_patrocinador)$podeEditar=permiteEditarPatrocinador($this->log_acesso,$this->log_patrocinador);
		elseif ($this->log_problema)$podeEditar=permiteEditarProblema($this->log_acesso,$this->log_problema);
		elseif ($this->log_perspectiva)$podeEditar=permiteEditarPerspectiva($this->log_acesso,$this->log_perspectiva);
		elseif ($this->log_pratica)$podeEditar=permiteEditarPratica($this->log_acesso,$this->log_pratica);
		elseif ($this->log_programa)$podeEditar=permiteEditarPrograma($this->log_acesso,$this->log_programa);
		elseif ($this->log_recurso)$podeEditar=permiteEditarRecurso($this->log_acesso,$this->log_recurso);
		elseif ($this->log_risco)$podeEditar=permiteEditarRisco($this->log_acesso,$this->log_risco);
		elseif ($this->log_risco_resposta)$podeEditar=permiteEditarRiscoResposta($this->log_acesso,$this->log_risco_resposta);
		elseif ($this->log_mswot)$podeEditar=permiteEditarMSWOT($this->log_acesso,$this->log_mswot);
		elseif ($this->log_swot)$podeEditar=permiteEditarSWOT($this->log_acesso,$this->log_swot);
		elseif ($this->log_tarefa)$podeEditar=permiteEditar($this->log_acesso,$this->log_projeto, $this->log_tarefa);
		elseif ($this->log_projeto)$podeEditar=permiteEditar($this->log_acesso,$this->log_projeto);
		elseif ($this->log_tema)$podeEditar=permiteEditarTema($this->log_acesso,$this->log_tema);
		elseif ($this->log_template)$podeEditar=permiteEditarTemplate($this->log_acesso,$this->log_template);
		elseif ($this->log_painel)$podeEditar=permiteEditarPainel($this->log_acesso,$this->log_painel);
		elseif ($this->log_painel_odometro)$podeEditar=permiteEditarOdometro($this->log_acesso,$this->log_painel_odometro);
		elseif ($this->log_painel_composicao)$podeEditar=permiteEditarPainelComposicao($this->log_acesso,$this->log_painel_composicao);
		elseif ($this->log_tr)$podeEditar=permiteEditarTR($this->log_acesso,$this->log_tr);
		elseif ($this->log_me)$podeEditar=permiteEditarMe($this->log_acesso,$this->log_me);
		elseif ($this->log_acao_item)$podeEditar=permiteEditarPlanoAcaoItem($this->log_acesso,$this->log_acao_item);
		elseif ($this->log_beneficio)$podeEditar=permiteEditarBeneficio($this->log_acesso,$this->log_beneficio);
		elseif ($this->log_painel_slideshow)$podeEditar=permiteEditarPainelSlideShow($this->log_acesso,$this->log_painel_slideshow);
		elseif ($this->log_projeto_viabilidade)$podeEditar=permiteEditarViabilidade($this->log_acesso,$this->log_projeto_viabilidade);
		elseif ($this->log_projeto_abertura)$podeEditar=permiteEditarTermoAbertura($this->log_acesso,$this->log_projeto_abertura);
		elseif ($this->log_plano_gestao)$podeEditar=permiteEditarPlanoGestao($this->log_acesso,$this->log_plano_gestao);
		elseif ($this->log_ssti)$podeEditar=permiteEditarSSTI($this->log_acesso,$this->log_ssti);
		elseif ($this->log_laudo)$podeEditar=permiteEditarLaudo($this->log_acesso,$this->log_laudo);
		elseif ($this->log_trelo)$podeEditar=permiteEditarTrelo($this->log_acesso,$this->log_trelo);
		elseif ($this->log_trelo_cartao)$podeEditar=permiteEditarTreloCartao($this->log_acesso,$this->log_trelo_cartao);
		elseif ($this->log_pdcl)$podeEditar=permiteEditarPDCL($this->log_acesso,$this->log_pdcl);
		elseif ($this->log_pdcl_item)$podeEditar=permiteEditarPDCLItem($this->log_acesso,$this->log_pdcl_item);

		else $podeEditar=false;
		return $podeEditar;
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic, $config;
		$sql = new BDConsulta();
		if ($this->log_id) {
			$ret = $sql->atualizarObjeto('log', $this, 'log_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('log', $this, 'log_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('log', $this->log_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->log_id);



		if (isset($_REQUEST['uuid']) && $_REQUEST['uuid']){
			$sql->adTabela('custo');
			$sql->adAtualizar('custo_log', (int)$this->log_id);
			$sql->adAtualizar('custo_uuid', null);
			$sql->adOnde('custo_uuid=\''.getParam($_REQUEST, 'uuid', null).'\'');
			$sql->exec();
			$sql->limpar();
			}



		//checar anexo
		$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
		if(isset($_FILES['arquivo']['name'])){
			foreach($_FILES['arquivo']['name'] as $chave => $linha){
				if (file_exists($_FILES['arquivo']['tmp_name'][$chave]) && !empty($_FILES['arquivo']['tmp_name'][$chave])){
				  $pasta='log';
				  $tipo=strtolower(pathinfo($_FILES['arquivo']['name'][$chave], PATHINFO_EXTENSION));
				  $tamanho=explode('/',$_FILES['arquivo']['size'][$chave]);
				  $permitido=getSisValor('downloadPermitido');
				  $proibido=getSisValor('downloadProibido');
				  $verificar_malicioso=explode('.',$_FILES['arquivo']['name'][$chave]);
				 	$malicioso=false;
				 	foreach($verificar_malicioso as $extensao) {
				 		if (in_array(strtolower($extensao), $proibido)) {
				 			$malicioso=$extensao;
				 			break;
				 			}
				 		}
				 	if ($malicioso) {
				  	$Aplic->setMsg('Extensão '.$malicioso.' não é permitida!', UI_MSG_ERRO);
				  	}
				  elseif (!in_array($tipo, $permitido)) {
				  	$Aplic->setMsg('Extensão '.$tipo.' não é permitida! Precisa ser '.implode(', ',$permitido).'. Para incluir nova extensão o administrador precisa ir em Menu=>Sistema=>Valores de campos do sistema=>downloadPermitido', UI_MSG_ERRO);
				  	}
				  else {
						$sql = new BDConsulta;
					 	$sql->adTabela('log_arquivo');
						$sql->adCampo('count(log_arquivo_id) AS soma');
						$sql->adOnde('log_arquivo_log ='.$this->log_id);
					  $soma_total = 1+(int)$sql->Resultado();
					  $sql->limpar();
					  $caminho = $soma_total.'_'.$_FILES['arquivo']['name'][$chave];
					  $caminho = removerSimbolos($caminho);
					  $caminho = removerSimbolos($caminho);
					  $caminho = removerSimbolos($caminho);
					 	if (!is_dir($base_dir)){
							$res = mkdir($base_dir, 0777);
							if (!$res) {
								$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões na raiz de '.$base_dir, UI_MSG_ALERTA);
								return false;
								}
							}
					 	if (!is_dir($base_dir.'/arquivos')){
							$res = mkdir($base_dir.'/arquivos', 0777);
							if (!$res) {
								$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\.', UI_MSG_ALERTA);
								return false;
								}
							}
					 	if (!is_dir($base_dir.'/arquivos/log')){
							$res = mkdir($base_dir.'/arquivos/log', 0777);
							if (!$res) {
								$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos.', UI_MSG_ALERTA);
								return false;
								}
							}
					 	if (!is_dir($base_dir.'/arquivos/log/'.$this->log_id)){
							$res = mkdir($base_dir.'/arquivos/log/'.$this->log_id, 0777);
							if (!$res) {
								$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos\\log\.', UI_MSG_ALERTA);
								return false;
								}
							}
					  // move o arquivo para o destino
					  $caminho_completo = $base_dir.'/arquivos/log/'.$this->log_id.'/'.$caminho;
					  move_uploaded_file($_FILES['arquivo']['tmp_name'][$chave], $caminho_completo);
					  if (file_exists($caminho_completo)) {
					  	$tipo=explode('/',$_FILES['arquivo']['type'][$chave]);
					  	$sql->adTabela('log_arquivo');
							$sql->adInserir('log_arquivo_log', $this->log_id);
							$sql->adInserir('log_arquivo_nome', $_FILES['arquivo']['name'][$chave]);
							$sql->adInserir('log_arquivo_endereco', $this->log_id.'/'.$caminho);
							$sql->adInserir('log_arquivo_usuario', $Aplic->usuario_id);
							$sql->adInserir('log_arquivo_data', date('Y-m-d H:i:s'));
							$sql->adInserir('log_arquivo_ordem', $soma_total);
							$sql->adInserir('log_arquivo_tipo', $tipo[0]);
							$sql->adInserir('log_arquivo_extensao', $tipo[1]);
							if (!$sql->exec()) $Aplic->setMsg('Não foi possível inserir o anexos na tabela log_arquivo!', UI_MSG_ERRO);
							$sql->limpar();
					  	}
					  }
					}
				}
			}



		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}




	public function notificar( $post=array()){
		global $Aplic, $config, $localidade_tipo_caract;


		require_once ($Aplic->getClasseSistema('libmail'));
		$endereco='';
		if (isset($post['log_tarefa']) && $post['log_tarefa']) $endereco='m=tarefas&a=ver&tab=0&tarefa_id='.(int)$post['log_tarefa'];
		elseif (isset($post['log_projeto']) && $post['log_projeto']) $endereco='m=projetos&a=ver&tab=0&projeto_id='.(int)$post['log_projeto'];
		elseif (isset($post['log_perspectiva']) && $post['log_perspectiva']) $endereco='m=praticas&a=perspectiva_ver&tab=0&pg_perspectiva_id='.(int)$post['log_perspectiva'];
		elseif (isset($post['log_tema']) && $post['log_tema']) $endereco='m=praticas&a=tema_ver&tab=0&tema_id='.(int)$post['log_tema'];
		elseif (isset($post['log_objetivo']) && $post['log_objetivo']) $endereco='m=praticas&a=obj_estrategico_ver&tab=0&objetivo_id='.(int)$post['log_objetivo'];
		elseif (isset($post['log_fator']) && $post['log_fator']) $endereco='m=praticas&a=fator_ver&tab=0&fator_id='.(int)$post['log_fator'];
		elseif (isset($post['log_estrategia']) && $post['log_estrategia']) $endereco='m=praticas&a=estrategia_ver&tab=0&pg_estrategia_id='.(int)$post['log_estrategia'];
		elseif (isset($post['log_meta']) && $post['log_meta']) $endereco='m=praticas&a=meta_ver&tab=0&pg_meta_id='.(int)$post['log_meta'];
		elseif (isset($post['log_pratica']) && $post['log_pratica']) $endereco='m=praticas&a=pratica_ver&tab=0&pratica_id='.(int)$post['log_pratica'];
		elseif (isset($post['log_indicador']) && $post['log_indicador']) $endereco='m=praticas&a=indicador_ver&tab=0&pratica_indicador_id='.(int)$post['log_indicador'];
		elseif (isset($post['log_acao']) && $post['log_acao']) $endereco='m=praticas&a=plano_acao_ver&tab=0&plano_acao_id='.(int)$post['log_acao'];
		elseif (isset($post['log_canvas']) && $post['log_canvas']) $endereco='m=praticas&a=canvas_pro_ver&tab=0&canvas_id='.(int)$post['log_canvas'];
		elseif (isset($post['log_risco']) && $post['log_risco']) $endereco='m=praticas&a=risco_pro_ver&tab=0&risco_id='.(int)$post['log_risco'];
		elseif (isset($post['log_risco_resposta']) && $post['log_risco_resposta']) $endereco='m=praticas&a=risco_resposta_pro_ver&tab=0&risco_resposta_id='.(int)$post['log_risco_resposta'];
		elseif (isset($post['log_calendario']) && $post['log_calendario']) $endereco='m=sistema&u=calendario&a=calendario_ver&tab=0&calendario_id='.(int)$post['log_calendario'];
		elseif (isset($post['log_monitoramento']) && $post['log_monitoramento']) $endereco='m=praticas&a=monitoramento_ver_pro&tab=0&monitoramento_id='.(int)$post['log_monitoramento'];
		elseif (isset($post['log_ata']) && $post['log_ata']) $endereco='m=atas&a=ata_ver&tab=0&ata_id='.(int)$post['log_ata'];
		elseif (isset($post['log_mswot']) && $post['log_mswot']) $endereco='m=swot&a=mswot_ver&tab=0&mswot_id='.(int)$post['log_mswot'];
		elseif (isset($post['log_swot']) && $post['log_swot']) $endereco='m=swot&a=swot_ver&tab=0&swot_id='.(int)$post['log_swot'];
		elseif (isset($post['log_operativo']) && $post['log_operativo']) $endereco='m=operativo&a=operativo_ver&tab=0&operativo_id='.(int)$post['log_operativo'];
		elseif (isset($post['log_instrumento']) && $post['log_instrumento']) $endereco='m=instrumento&a=instrumento_ver&tab=0&instrumento_id='.(int)$post['log_instrumento'];
		elseif (isset($post['log_recurso']) && $post['log_recurso']) $endereco='m=recursos&a=ver&tab=0&recurso_id='.(int)$post['log_recurso'];
		elseif (isset($post['log_problema']) && $post['log_problema']) $endereco='m=problema&a=problema_ver&tab=0&problema_id='.(int)$post['log_problema'];
		elseif (isset($post['log_demanda']) && $post['log_demanda']) $endereco='m=projetos&a=demanda_ver&tab=0&demanda_id='.(int)$post['log_demanda'];
		elseif (isset($post['log_programa']) && $post['log_programa']) $endereco='m=projetos&a=programa_ver&tab=0&programa_id='.(int)$post['log_programa'];
		elseif (isset($post['log_licao']) && $post['log_licao']) $endereco='m=projetos&a=licao_ver&tab=0&licao_id='.(int)$post['log_licao'];
		elseif (isset($post['log_evento']) && $post['log_evento']) $endereco='m=calendario&a=ver&tab=0&evento_id='.(int)$post['log_evento'];
		elseif (isset($post['log_link']) && $post['log_link']) $endereco='m=links&a=ver&tab=0&link_id='.(int)$post['log_link'];
		elseif (isset($post['log_avaliacao']) && $post['log_avaliacao']) $endereco='m=praticas&a=avaliacao_ver&tab=0&avaliacao_id='.(int)$post['log_avaliacao'];
		elseif (isset($post['log_tgn']) && $post['log_tgn']) $endereco='m=praticas&a=tgn_pro_ver&tab=0&tgn_id='.(int)$post['log_tgn'];
		elseif (isset($post['log_brainstorm']) && $post['log_brainstorm']) $endereco='m=praticas&a=brainstorm_ver&tab=0&brainstorm_id='.(int)$post['log_brainstorm'];
		elseif (isset($post['log_gut']) && $post['log_gut']) $endereco='m=praticas&a=gut_ver&tab=0&gut_id='.(int)$post['log_gut'];
		elseif (isset($post['log_causa_efeito']) && $post['log_causa_efeito']) $endereco='m=praticas&a=causa_efeito_ver&tab=0&causa_efeito_id='.(int)$post['log_causa_efeito'];
		elseif (isset($post['log_arquivo']) && $post['log_arquivo']) $endereco='m=arquivos&a=ver&tab=0&arquivo_id='.(int)$post['log_arquivo'];
		elseif (isset($post['log_forum']) && $post['log_forum']) $endereco='m=foruns&a=ver&tab=0&forum_id='.(int)$post['log_forum'];
		elseif (isset($post['log_checklist']) && $post['log_checklist']) $endereco='m=praticas&a=checklist_ver&tab=0&checklist_id='.(int)$post['log_checklist'];
		elseif (isset($post['log_agenda']) && $post['log_agenda']) $endereco='m=email&a=ver_compromisso&tab=0&agenda_id='.(int)$post['log_agenda'];
		elseif (isset($post['log_agrupamento']) && $post['log_agrupamento']) $endereco='m=agrupamento&a=agrupamento_ver&tab=0&agrupamento_id='.(int)$post['log_agrupamento'];
		elseif (isset($post['log_patrocinador']) && $post['log_patrocinador']) $endereco='m=patrocinadores&a=patrocinador_ver&tab=0&patrocinador_id='.(int)$post['log_patrocinador'];
		elseif (isset($post['log_template']) && $post['log_template']) $endereco='m=projetos&a=template_pro_ver&tab=0&template_id='.(int)$post['log_template'];
		elseif (isset($post['log_painel']) && $post['log_painel']) $endereco='m=praticas&a=painel_pro_ver&tab=0&painel_id='.(int)$post['log_painel'];
		elseif (isset($post['log_painel_odometro']) && $post['log_painel_odometro']) $endereco='m=praticas&a=odometro_pro_ver&&tab=0painel_odometro_id='.(int)$post['log_painel_odometro'];
		elseif (isset($post['log_painel_composicao']) && $post['log_painel_composicao']) $endereco='m=praticas&a=painel_composicao_pro_ver&tab=0&painel_composicao_id='.(int)$post['log_painel_composicao'];
		elseif (isset($post['log_tr']) && $post['log_tr']) $endereco='m=tr&a=tr_ver&tab=0&tr_id='.(int)$post['log_tr'];
		elseif (isset($post['log_me']) && $post['log_me']) $endereco='m=praticas&a=me_ver_pro&tab=0&me_id='.(int)$post['log_me'];
		elseif (isset($post['log_acao_item']) && $post['log_acao_item']) $endereco='m=praticas&a=plano_acao_item_ver&tab=0&plano_acao_item_id='.(int)$post['log_acao_item'];
		elseif (isset($post['log_beneficio']) && $post['log_beneficio']) $endereco='m=projetos&a=beneficio_pro_ver&tab=0&beneficio_id='.(int)$post['log_beneficio'];
		elseif (isset($post['log_painel_slideshow']) && $post['log_painel_slideshow']) $endereco='m=praticas&a=painel_slideshow_pro_ver&tab=0&jquery=1&painel_slideshow_id='.(int)$post['log_painel_slideshow'];
		elseif (isset($post['log_projeto_viabilidade']) && $post['log_projeto_viabilidade']) $endereco='m=projetos&a=viabilidade_ver&tab=0&projeto_viabilidade_id='.(int)$post['log_projeto_viabilidade'];
		elseif (isset($post['log_projeto_abertura']) && $post['log_projeto_abertura']) $endereco='m=projetos&a=termo_abertura_ver&tab=0&projeto_abertura_id='.(int)$post['log_projeto_abertura'];
		elseif (isset($post['log_plano_gestao']) && $post['log_plano_gestao']) $endereco='m=praticas&u=gestao&a=menu&tab=0&pg_id='.(int)$post['log_plano_gestao'];
		elseif (isset($post['log_ssti']) && $post['log_ssti']) $endereco='m=ssti&a=ssti_ver&ssti_id='.(int)$post['log_ssti'];
		elseif (isset($post['log_laudo']) && $post['log_laudo']) $endereco='m=ssti&a=laudo_ver&laudo_id='.(int)$post['log_laudo'];
		elseif (isset($post['log_trelo']) && $post['log_trelo']) $endereco='m=trelo&a=trelo_ver&trelo_id='.(int)$post['log_trelo'];
		elseif (isset($post['log_trelo_cartao']) && $post['log_trelo_cartao']) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.(int)$post['log_trelo_cartao'];
		elseif (isset($post['log_pdcl']) && $post['log_pdcl']) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.(int)$post['log_pdcl'];
		elseif (isset($post['log_pdcl_item']) && $post['log_pdcl_item']) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.(int)$post['log_pdcl_item'];
		
		$sql = new BDConsulta;

		$nome ='';

		if (isset($post['log_tarefa']) && $post['log_tarefa'])$nome = nome_tarefa($post['log_tarefa']);
		elseif (isset($post['log_projeto']) && $post['log_projeto'])$nome = nome_projeto($post['log_projeto']);
		elseif (isset($post['log_perspectiva']) && $post['log_perspectiva'])$nome = nome_perspectiva($post['log_perspectiva']);
		elseif (isset($post['log_tema']) && $post['log_tema'])$nome = nome_tema($post['log_tema']);
		elseif (isset($post['log_objetivo']) && $post['log_objetivo'])$nome = nome_objetivo($post['log_objetivo']);
		elseif (isset($post['log_fator']) && $post['log_fator'])$nome = nome_fator($post['log_fator']);
		elseif (isset($post['log_estrategia']) && $post['log_estrategia'])$nome = nome_estrategia($post['log_estrategia']);
		elseif (isset($post['log_meta']) && $post['log_meta'])$nome = nome_meta($post['log_meta']);
		elseif (isset($post['log_pratica']) && $post['log_pratica'])$nome = nome_pratica($post['log_pratica']);
		elseif (isset($post['log_indicador']) && $post['log_indicador'])$nome = nome_indicador($post['log_indicador']);
		elseif (isset($post['log_acao']) && $post['log_acao'])$nome = nome_acao($post['log_acao']);
		elseif (isset($post['log_canvas']) && $post['log_canvas'])$nome = nome_canvas($post['log_canvas']);
		elseif (isset($post['log_risco']) && $post['log_risco'])$nome = nome_risco($post['log_risco']);
		elseif (isset($post['log_risco_resposta']) && $post['log_risco_resposta'])$nome = nome_risco_resposta($post['log_risco_resposta']);
		elseif (isset($post['log_calendario']) && $post['log_calendario'])$nome = nome_calendario($post['log_calendario']);
		elseif (isset($post['log_monitoramento']) && $post['log_monitoramento'])$nome = nome_monitoramento($post['log_monitoramento']);
		elseif (isset($post['log_ata']) && $post['log_ata'])$nome = nome_ata($post['log_ata']);
		elseif (isset($post['log_mswot']) && $post['log_mswot'])$nome = nome_mswot($post['log_mswot']);
		elseif (isset($post['log_swot']) && $post['log_swot'])$nome = nome_swot($post['log_swot']);
		elseif (isset($post['log_operativo']) && $post['log_operativo'])$nome = nome_operativo($post['log_operativo']);
		elseif (isset($post['log_instrumento']) && $post['log_instrumento'])$nome = nome_instrumento($post['log_instrumento']);
		elseif (isset($post['log_recurso']) && $post['log_recurso'])$nome = nome_recurso($post['log_recurso']);
		elseif (isset($post['log_problema']) && $post['log_problema'])$nome = nome_problema($post['log_problema']);
		elseif (isset($post['log_demanda']) && $post['log_demanda'])$nome = nome_demanda($post['log_demanda']);
		elseif (isset($post['log_programa']) && $post['log_programa'])$nome = nome_programa($post['log_programa']);
		elseif (isset($post['log_licao']) && $post['log_licao'])$nome = nome_licao($post['log_licao']);
		elseif (isset($post['log_evento']) && $post['log_evento'])$nome = nome_evento($post['log_evento']);
		elseif (isset($post['log_link']) && $post['log_link'])$nome = nome_link($post['log_link']);
		elseif (isset($post['log_avaliacao']) && $post['log_avaliacao'])$nome = nome_avaliacao($post['log_avaliacao']);
		elseif (isset($post['log_tgn']) && $post['log_tgn'])$nome = nome_tgn($post['log_tgn']);
		elseif (isset($post['log_brainstorm']) && $post['log_brainstorm'])$nome = nome_brainstorm($post['log_brainstorm']);
		elseif (isset($post['log_gut']) && $post['log_gut'])$nome = nome_gut($post['log_gut']);
		elseif (isset($post['log_causa_efeito']) && $post['log_causa_efeito'])$nome = nome_causa_efeito($post['log_causa_efeito']);
		elseif (isset($post['log_arquivo']) && $post['log_arquivo'])$nome = nome_arquivo($post['log_arquivo']);
		elseif (isset($post['log_forum']) && $post['log_forum'])$nome = nome_forum($post['log_forum']);
		elseif (isset($post['log_checklist']) && $post['log_checklist'])$nome = nome_checklist($post['log_checklist']);
		elseif (isset($post['log_agenda']) && $post['log_agenda'])$nome = nome_agenda($post['log_agenda']);
		elseif (isset($post['log_agrupamento']) && $post['log_agrupamento'])$nome = nome_agrupamento($post['log_agrupamento']);
		elseif (isset($post['log_patrocinador']) && $post['log_patrocinador'])$nome = nome_patrocinador($post['log_patrocinador']);
		elseif (isset($post['log_template']) && $post['log_template'])$nome = nome_template($post['log_template']);
		elseif (isset($post['log_painel']) && $post['log_painel'])$nome = nome_painel($post['log_painel']);
		elseif (isset($post['log_painel_odometro']) && $post['log_painel_odometro'])$nome = nome_painel_odometro($post['log_painel_odometro']);
		elseif (isset($post['log_painel_composicao']) && $post['log_painel_composicao'])$nome = nome_painel_composicao($post['log_painel_composicao']);
		elseif (isset($post['log_tr']) && $post['log_tr'])$nome = nome_tr($post['log_tr']);
		elseif (isset($post['log_me']) && $post['log_me'])$nome = nome_me($post['log_me']);
		elseif (isset($post['log_acao_item']) && $post['log_acao_item']) $nome = nome_acao_item($post['log_acao_item']);
		elseif (isset($post['log_beneficio']) && $post['log_beneficio']) $nome = nome_beneficio($post['log_beneficio']);
		elseif (isset($post['log_painel_slideshow']) && $post['log_painel_slideshow']) $nome = nome_painel_slideshow($post['log_painel_slideshow']);
		elseif (isset($post['log_projeto_viabilidade']) && $post['log_projeto_viabilidade']) $nome = nome_viabilidade($post['log_projeto_viabilidade']);
		elseif (isset($post['log_projeto_abertura']) && $post['log_projeto_abertura']) $nome = nome_termo_abertura($post['log_projeto_abertura']);
		elseif (isset($post['log_plano_gestao']) && $post['log_plano_gestao']) $nome = nome_plano_gestao($post['log_plano_gestao']);
		elseif (isset($post['log_ssti']) && $post['log_ssti'])$nome = nome_ssti($post['log_ssti']);
		elseif (isset($post['log_laudo']) && $post['log_laudo'])$nome = nome_laudo($post['log_laudo']);
		elseif (isset($post['log_trelo']) && $post['log_trelo'])$nome = nome_trelo($post['log_trelo']);
		elseif (isset($post['log_trelo_cartao']) && $post['log_trelo_cartao'])$nome = nome_trelo_cartao($post['log_trelo_cartao']);
		elseif (isset($post['log_pdcl']) && $post['log_pdcl'])$nome = nome_pdcl($post['log_pdcl']);
		elseif (isset($post['log_pdcl_item']) && $post['log_pdcl_item'])$nome = nome_pdcl_item($post['log_pdcl_item']);
		
		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if (isset($post['email_designados']) && $post['email_designados']){

			if (isset($post['log_tarefa']) && $post['log_tarefa']){
				$sql->adTabela('tarefa_designados');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = tarefa_designados.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('tarefa_id ='.(int)$post['log_tarefa']);
				}
			elseif (isset($post['log_projeto']) && $post['log_projeto']){
				$sql->adTabela('projeto_integrantes');
				$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = projeto_integrantes.contato_id');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = contatos.contato_id');
				$sql->adOnde('projeto_id ='.(int)$post['log_projeto']);
				}
			elseif (isset($post['log_perspectiva']) && $post['log_perspectiva']){
				$sql->adTabela('perspectivas_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = perspectivas_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('pg_perspectiva_id ='.(int)$post['log_perspectiva']);
				}
			elseif (isset($post['log_tema']) && $post['log_tema']){
				$sql->adTabela('tema_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = tema_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('tema_id ='.(int)$post['log_tema']);
				}
			elseif (isset($post['log_objetivo']) && $post['log_objetivo']){
				$sql->adTabela('objetivo_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = objetivo_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('objetivo_usuario_objetivo ='.(int)$post['log_objetivo']);
				}
			elseif (isset($post['log_fator']) && $post['log_fator']){
				$sql->adTabela('fator_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = fator_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('fator_usuario_fator ='.(int)$post['log_fator']);
				}
			elseif (isset($post['log_estrategia']) && $post['log_estrategia']){
				$sql->adTabela('estrategias_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = estrategias_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('pg_estrategia_id ='.(int)$post['log_estrategia']);
				}
			elseif (isset($post['log_meta']) && $post['log_meta']){
				$sql->adTabela('metas_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = metas_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('pg_meta_id ='.(int)$post['log_meta']);
				}
			elseif (isset($post['log_pratica']) && $post['log_pratica']){
				$sql->adTabela('pratica_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = pratica_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('pratica_id ='.(int)$post['log_pratica']);
				}
			elseif (isset($post['log_indicador']) && $post['log_indicador']){
				$sql->adTabela('pratica_indicador_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = pratica_indicador_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('pratica_indicador_id ='.(int)$post['log_indicador']);
				}
			elseif (isset($post['log_acao']) && $post['log_acao']){
				$sql->adTabela('plano_acao_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = plano_acao_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('plano_acao_usuario_acao ='.(int)$post['log_acao']);
				}
			elseif (isset($post['log_canvas']) && $post['log_canvas']){
				$sql->adTabela('canvas_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = canvas_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('canvas_usuario_canvas ='.(int)$post['log_canvas']);
				}
			elseif (isset($post['log_risco']) && $post['log_risco']){
				$sql->adTabela('risco_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = risco_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('risco_id ='.(int)$post['log_risco']);
				}
			elseif (isset($post['log_risco_resposta']) && $post['log_risco_resposta']){
				$sql->adTabela('risco_resposta_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = risco_resposta_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('risco_resposta_id ='.(int)$post['log_risco_resposta']);
				}
			elseif (isset($post['log_calendario']) && $post['log_calendario']){
				$sql->adTabela('calendario_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = calendario_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('calendario_usuario_calendario ='.(int)$post['log_calendario']);
				}
			elseif (isset($post['log_monitoramento']) && $post['log_monitoramento']){
				$sql->adTabela('monitoramento_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = monitoramento_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('monitoramento_id ='.(int)$post['log_monitoramento']);
				}
			elseif (isset($post['log_ata']) && $post['log_ata']){
				$sql->adTabela('ata_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = ata_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('ata_usuario_ata ='.(int)$post['log_ata']);
				}
			elseif (isset($post['log_mswot']) && $post['log_mswot']){
				$sql->adTabela('mswot_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = mswot_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('mswot_usuario_mswot ='.(int)$post['log_mswot']);
				}
			elseif (isset($post['log_swot']) && $post['log_swot']){
				$sql->adTabela('swot_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = swot_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('swot_id ='.(int)$post['log_swot']);
				}	
			elseif (isset($post['log_operativo']) && $post['log_operativo']){
				$sql->adTabela('operativo_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = operativo_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('operativo_id ='.(int)$post['log_operativo']);
				}
			elseif (isset($post['log_instrumento']) && $post['log_instrumento']){
				$sql->adTabela('instrumento_designados');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = instrumento_designados.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('instrumento_id ='.(int)$post['log_instrumento']);
				}
			elseif (isset($post['log_recurso']) && $post['log_recurso']){
				$sql->adTabela('recurso_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = recurso_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('recurso_id ='.(int)$post['log_recurso']);
				}
			elseif (isset($post['log_problema']) && $post['log_problema']){
				$sql->adTabela('problema_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = problema_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('problema_id ='.(int)$post['log_problema']);
				}
			elseif (isset($post['log_demanda']) && $post['log_demanda']){
				$sql->adTabela('demanda_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = demanda_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('demanda_id ='.(int)$post['log_demanda']);
				}
			elseif (isset($post['log_programa']) && $post['log_programa']){
				$sql->adTabela('programa_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = programa_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('programa_usuario_programa ='.(int)$post['log_programa']);
				}
			elseif (isset($post['log_licao']) && $post['log_licao']){
				$sql->adTabela('licao_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = licao_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('licao_usuario_licao ='.(int)$post['log_licao']);
				}
			elseif (isset($post['log_evento']) && $post['log_evento']){
				$sql->adTabela('evento_participante');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = evento_participante_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('evento_participante_evento ='.(int)$post['log_evento']);
				}
			elseif (isset($post['log_link']) && $post['log_link']){
				$sql->adTabela('link_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = link_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('link_id ='.(int)$post['log_link']);
				}
			elseif (isset($post['log_avaliacao']) && $post['log_avaliacao']){
				$sql->adTabela('avaliacao_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = avaliacao_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('avaliacao_id ='.(int)$post['log_avaliacao']);
				}
			elseif (isset($post['log_tgn']) && $post['log_tgn']){
				$sql->adTabela('tgn_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = tgn_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('tgn_usuario_tgn ='.(int)$post['log_tgn']);
				}
			elseif (isset($post['log_brainstorm']) && $post['log_brainstorm']){
				$sql->adTabela('brainstorm_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = brainstorm_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('brainstorm_id ='.(int)$post['log_brainstorm']);
				}
			elseif (isset($post['log_gut']) && $post['log_gut']){
				$sql->adTabela('gut_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = gut_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('gut_id ='.(int)$post['log_gut']);
				}
			elseif (isset($post['log_causa_efeito']) && $post['log_causa_efeito']){
				$sql->adTabela('causa_efeito_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = causa_efeito_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('causa_efeito_id ='.(int)$post['log_causa_efeito']);
				}
			elseif (isset($post['log_arquivo']) && $post['log_arquivo']){
				$sql->adTabela('arquivo_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = arquivo_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('arquivo_usuario_arquivo ='.(int)$post['log_arquivo']);
				}
			elseif (isset($post['log_forum']) && $post['log_forum']){
				$sql->adTabela('forum_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = forum_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('forum_usuario_forum ='.(int)$post['log_forum']);
				}
			elseif (isset($post['log_checklist']) && $post['log_checklist']){
				$sql->adTabela('checklist_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = checklist_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('checklist_id ='.(int)$post['log_checklist']);
				}
			elseif (isset($post['log_agenda']) && $post['log_agenda']){
				$sql->adTabela('agenda_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = agenda_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('agenda_id ='.(int)$post['log_agenda']);
				}
			elseif (isset($post['log_agrupamento']) && $post['log_agrupamento']){
				$sql->adTabela('agrupamento_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = agrupamento_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('agrupamento_usuario_agrupamento ='.(int)$post['log_agrupamento']);
				}
			elseif (isset($post['log_patrocinador']) && $post['log_patrocinador']){
				$sql->adTabela('patrocinadores_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = patrocinadores_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('patrocinador_id ='.(int)$post['log_patrocinador']);
				}
			elseif (isset($post['log_template']) && $post['log_template']){
				$sql->adTabela('template_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = template_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('template_id ='.(int)$post['log_template']);
				}
			elseif (isset($post['log_painel']) && $post['log_painel']){
				$sql->adTabela('painel_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = painel_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('painel_usuario_painel ='.(int)$post['log_painel']);
				}
			elseif (isset($post['log_painel_odometro']) && $post['log_painel_odometro']){
				$sql->adTabela('painel_odometro_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = painel_odometro_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('painel_odometro_usuario_painel_odometro ='.(int)$post['log_painel_odometro']);
				}
			elseif (isset($post['log_painel_composicao']) && $post['log_painel_composicao']){
				$sql->adTabela('painel_composicao_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = painel_composicao_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('painel_composicao_usuario_painel_composicao ='.(int)$post['log_painel_composicao']);
				}
			elseif (isset($post['log_tr']) && $post['log_tr']){
				$sql->adTabela('tr_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = tr_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('tr_usuario_tr ='.(int)$post['log_tr']);
				}
			elseif (isset($post['log_me']) && $post['log_me']){
				$sql->adTabela('me_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = me_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('me_usuario_me ='.(int)$post['log_me']);
				}

			elseif (isset($post['log_acao_item']) && $post['log_acao_item']){
				$sql->adTabela('plano_acao_item_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = plano_acao_item_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('plano_acao_item_usuario_item = '.(int)$post['log_acao_item']);
				}		
			elseif (isset($post['log_beneficio']) && $post['log_beneficio']){
				$sql->adTabela('beneficio_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = beneficio_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('beneficio_usuario_beneficio = '.(int)$post['log_beneficio']);
				}				
			elseif (isset($post['log_painel_slideshow']) && $post['log_painel_slideshow']){
				$sql->adTabela('painel_slideshow_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = painel_slideshow_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('painel_slideshow_usuario_slideshow = '.(int)$post['log_painel_slideshow']);
				}			
			elseif (isset($post['log_projeto_viabilidade']) && $post['log_projeto_viabilidade']){
				$sql->adTabela('projeto_viabilidade_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = projeto_viabilidade_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('projeto_viabilidade_usuarios.projeto_viabilidade_id = '.(int)$post['log_projeto_viabilidade']);
				}		
			elseif (isset($post['log_projeto_abertura']) && $post['log_projeto_abertura']){
				$sql->adTabela('projeto_abertura_usuarios');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = projeto_abertura_usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('projeto_abertura_usuarios.projeto_abertura_id = '.(int)$post['log_projeto_abertura']);
				}				
			elseif (isset($post['log_plano_gestao']) && $post['log_plano_gestao']){
				$sql->adTabela('plano_gestao_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = plano_gestao_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('plano_gestao_usuario_plano = '.(int)$post['log_plano_gestao']);
				}				
			elseif (isset($post['log_ssti']) && $post['log_ssti']){
				$sql->adTabela('ssti_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = ssti_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('ssti_usuario_ssti = '.(int)$post['log_ssti']);
				}						
			elseif (isset($post['log_laudo']) && $post['log_laudo']){
				$sql->adTabela('laudo_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = laudo_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('laudo_usuario_laudo = '.(int)$post['log_laudo']);
				}						
			elseif (isset($post['log_trelo']) && $post['log_trelo']){
				$sql->adTabela('trelo_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = trelo_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('trelo_usuario_trelo = '.(int)$post['log_trelo']);
				}						
			elseif (isset($post['log_trelo_cartao']) && $post['log_trelo_cartao']){
				$sql->adTabela('trelo_cartao_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = trelo_cartao_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('trelo_cartao_usuario_trelo_cartao = '.(int)$post['log_trelo_cartao']);
				}						
			elseif (isset($post['log_pdcl']) && $post['log_pdcl']){
				$sql->adTabela('pdcl_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = pdcl_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('pdcl_usuario_pdcl = '.(int)$post['log_pdcl']);
				}						
			elseif (isset($post['log_pdcl_item']) && $post['log_pdcl_item']){
				$sql->adTabela('pdcl_item_usuario');
				$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = pdcl_item_usuario_usuario');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('pdcl_item_usuario_item = '.(int)$post['log_pdcl_item']);
				}						
					
					
			else{
				//esta é para forçar ir em branco se esqueci algum objeto
				$sql->adTabela('usuarios');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adOnde('usuario_id = 0');
				}
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$usuarios1 = $sql->Lista();
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

			if (isset($post['log_tarefa']) && $post['log_tarefa']){
				$sql->esqUnir('tarefas', 'tarefas', 'tarefa_dono = usuarios.usuario_id');
				$sql->adOnde('tarefa_id='.(int)$post['log_tarefa']);
				}
			elseif (isset($post['log_projeto']) && $post['log_projeto']){
				$sql->esqUnir('projetos', 'projetos', 'projeto_responsavel = usuarios.usuario_id');
				$sql->adOnde('projeto_id='.(int)$post['log_projeto']);
				}
			elseif (isset($post['log_perspectiva']) && $post['log_perspectiva']){
				$sql->esqUnir('perspectivas', 'perspectivas', 'pg_perspectiva_usuario = usuarios.usuario_id');
				$sql->adOnde('pg_perspectiva_id='.(int)$post['log_perspectiva']);
				}
			elseif (isset($post['log_tema']) && $post['log_tema']){
				$sql->esqUnir('tema', 'tema', 'tema_usuario = usuarios.usuario_id');
				$sql->adOnde('tema_id='.(int)$post['log_tema']);
				}
			elseif (isset($post['log_objetivo']) && $post['log_objetivo']){
				$sql->esqUnir('objetivo', 'objetivo', 'objetivo_usuario = usuarios.usuario_id');
				$sql->adOnde('objetivo_id='.(int)$post['log_objetivo']);
				}
			elseif (isset($post['log_fator']) && $post['log_fator']){
				$sql->esqUnir('fator', 'fator', 'fator_usuario = usuarios.usuario_id');
				$sql->adOnde('fator_id='.(int)$post['log_fator']);
				}
			elseif (isset($post['log_estrategia']) && $post['log_estrategia']){
				$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_usuario = usuarios.usuario_id');
				$sql->adOnde('pg_estrategia_id='.(int)$post['log_estrategia']);
				}
			elseif (isset($post['log_meta']) && $post['log_meta']){
				$sql->esqUnir('metas', 'metas', 'pg_meta_responsavel = usuarios.usuario_id');
				$sql->adOnde('pg_meta_id='.(int)$post['log_meta']);
				}
			elseif (isset($post['log_pratica']) && $post['log_pratica']){
				$sql->esqUnir('praticas', 'praticas', 'pratica_responsavel = usuarios.usuario_id');
				$sql->adOnde('pratica_id='.(int)$post['log_pratica']);
				}
			elseif (isset($post['log_indicador']) && $post['log_indicador']){
				$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_responsavel = usuarios.usuario_id');
				$sql->adOnde('pratica_indicador_id='.(int)$post['log_indicador']);
				}
			elseif (isset($post['log_acao']) && $post['log_acao']){
				$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_responsavel = usuarios.usuario_id');
				$sql->adOnde('plano_acao_id='.(int)$post['log_acao']);
				}
			elseif (isset($post['log_canvas']) && $post['log_canvas']){
				$sql->esqUnir('canvas', 'canvas', 'canvas_usuario = usuarios.usuario_id');
				$sql->adOnde('canvas_id='.(int)$post['log_canvas']);
				}
			elseif (isset($post['log_risco']) && $post['log_risco']){
				$sql->esqUnir('risco', 'risco', 'risco_usuario = usuarios.usuario_id');
				$sql->adOnde('risco_id='.(int)$post['log_risco']);
				}
			elseif (isset($post['log_risco_resposta']) && $post['log_risco_resposta']){
				$sql->esqUnir('risco_resposta', 'risco_resposta', 'risco_resposta_usuario = usuarios.usuario_id');
				$sql->adOnde('risco_resposta_id='.(int)$post['log_risco_resposta']);
				}
			elseif (isset($post['log_calendario']) && $post['log_calendario']){
				$sql->esqUnir('calendario', 'calendario', 'calendario_usuario = usuarios.usuario_id');
				$sql->adOnde('calendario_id='.(int)$post['log_calendario']);
				}
			elseif (isset($post['log_monitoramento']) && $post['log_monitoramento']){
				$sql->esqUnir('monitoramento', 'monitoramento', 'monitoramento_usuario = usuarios.usuario_id');
				$sql->adOnde('monitoramento_id='.(int)$post['log_monitoramento']);
				}
			elseif (isset($post['log_ata']) && $post['log_ata']){
				$sql->esqUnir('ata', 'ata', 'ata_responsavel = usuarios.usuario_id');
				$sql->adOnde('ata_id='.(int)$post['log_ata']);
				}
			elseif (isset($post['log_mswot']) && $post['log_mswot']){
				$sql->esqUnir('mswot', 'mswot', 'mswot_responsavel = usuarios.usuario_id');
				$sql->adOnde('mswot_id='.(int)$post['log_mswot']);
				}
			elseif (isset($post['log_swot']) && $post['log_swot']){
				$sql->esqUnir('swot', 'swot', 'swot_responsavel = usuarios.usuario_id');
				$sql->adOnde('swot_id='.(int)$post['log_swot']);
				}	
			elseif (isset($post['log_operativo']) && $post['log_operativo']){
				$sql->esqUnir('operativo', 'operativo', 'operativo_usuario = usuarios.usuario_id');
				$sql->adOnde('operativo_id='.(int)$post['log_operativo']);
				}
			elseif (isset($post['log_instrumento']) && $post['log_instrumento']){
				$sql->esqUnir('instrumento', 'instrumento', 'instrumento_responsavel = usuarios.usuario_id');
				$sql->adOnde('instrumento_id='.(int)$post['log_instrumento']);
				}
			elseif (isset($post['log_recurso']) && $post['log_recurso']){
				$sql->esqUnir('recursos', 'recursos', 'recurso_responsavel = usuarios.usuario_id');
				$sql->adOnde('recurso_id='.(int)$post['log_recurso']);
				}
			elseif (isset($post['log_problema']) && $post['log_problema']){
				$sql->esqUnir('problema', 'problema', 'problema_responsavel = usuarios.usuario_id');
				$sql->adOnde('problema_id='.(int)$post['log_problema']);
				}
			elseif (isset($post['log_demanda']) && $post['log_demanda']){
				$sql->esqUnir('demandas', 'demandas', 'demanda_usuario = usuarios.usuario_id');
				$sql->adOnde('demanda_id='.(int)$post['log_demanda']);
				}
			elseif (isset($post['log_licao']) && $post['log_licao']){
				$sql->esqUnir('licao', 'licao', 'licao_responsavel = usuarios.usuario_id');
				$sql->adOnde('licao_id='.(int)$post['log_licao']);
				}
			elseif (isset($post['log_evento']) && $post['log_evento']){
				$sql->esqUnir('eventos', 'eventos', 'evento_dono = usuarios.usuario_id');
				$sql->adOnde('evento_id='.(int)$post['log_evento']);
				}
			elseif (isset($post['log_link']) && $post['log_link']){
				$sql->esqUnir('links', 'links', 'link_dono = usuarios.usuario_id');
				$sql->adOnde('link_id='.(int)$post['log_link']);
				}
			elseif (isset($post['log_avaliacao']) && $post['log_avaliacao']){
				$sql->esqUnir('avaliacao', 'avaliacao', 'avaliacao_responsavel = usuarios.usuario_id');
				$sql->adOnde('avaliacao_id='.(int)$post['log_avaliacao']);
				}
			elseif (isset($post['log_tgn']) && $post['log_tgn']){
				$sql->esqUnir('tgn', 'tgn', 'tgn_usuario = usuarios.usuario_id');
				$sql->adOnde('tgn_id='.(int)$post['log_tgn']);
				}
			elseif (isset($post['log_brainstorm']) && $post['log_brainstorm']){
				$sql->esqUnir('brainstorm', 'brainstorm', 'brainstorm_responsavel = usuarios.usuario_id');
				$sql->adOnde('brainstorm_id='.(int)$post['log_brainstorm']);
				}
			elseif (isset($post['log_gut']) && $post['log_gut']){
				$sql->esqUnir('gut', 'gut', 'gut_responsavel = usuarios.usuario_id');
				$sql->adOnde('gut_id='.(int)$post['log_gut']);
				}
			elseif (isset($post['log_causa_efeito']) && $post['log_causa_efeito']){
				$sql->esqUnir('causa_efeito', 'causa_efeito', 'causa_efeito_responsavel = usuarios.usuario_id');
				$sql->adOnde('causa_efeito_id='.(int)$post['log_causa_efeito']);
				}
			elseif (isset($post['log_arquivo']) && $post['log_arquivo']){
				$sql->esqUnir('arquivo', 'arquivo', 'arquivo_dono = usuarios.usuario_id');
				$sql->adOnde('arquivo_id='.(int)$post['log_arquivo']);
				}
			elseif (isset($post['log_forum']) && $post['log_forum']){
				$sql->esqUnir('foruns', 'foruns', 'forum_dono = usuarios.usuario_id');
				$sql->adOnde('forum_id='.(int)$post['log_forum']);
				}
			elseif (isset($post['log_checklist']) && $post['log_checklist']){
				$sql->esqUnir('checklist', 'checklist', 'checklist_responsavel = usuarios.usuario_id');
				$sql->adOnde('checklist_id='.(int)$post['log_checklist']);
				}
			elseif (isset($post['log_agenda']) && $post['log_agenda']){
				$sql->esqUnir('agenda', 'agenda', 'agenda_dono = usuarios.usuario_id');
				$sql->adOnde('agenda_id='.(int)$post['log_agenda']);
				}
			elseif (isset($post['log_agrupamento']) && $post['log_agrupamento']){
				$sql->esqUnir('agrupamento', 'agrupamento', 'agrupamento_usuario = usuarios.usuario_id');
				$sql->adOnde('agrupamento_id='.(int)$post['log_agrupamento']);
				}
			elseif (isset($post['log_patrocinador']) && $post['log_patrocinador']){
				$sql->esqUnir('patrocinadores', 'patrocinadores', 'patrocinador_responsavel = usuarios.usuario_id');
				$sql->adOnde('patrocinador_id='.(int)$post['log_patrocinador']);
				}
			elseif (isset($post['log_template']) && $post['log_template']){
				$sql->esqUnir('template', 'template', 'template_responsavel = usuarios.usuario_id');
				$sql->adOnde('template_id='.(int)$post['log_template']);
				}
			elseif (isset($post['log_painel']) && $post['log_painel']){
				$sql->esqUnir('painel', 'painel', 'painel_responsavel = usuarios.usuario_id');
				$sql->adOnde('painel_id='.(int)$post['log_painel']);
				}
			elseif (isset($post['log_painel_odometro']) && $post['log_painel_odometro']){
				$sql->esqUnir('painel_odometro', 'painel_odometro', 'painel_odometro_responsavel = usuarios.usuario_id');
				$sql->adOnde('painel_odometro_id='.(int)$post['log_painel_odometro']);
				}
			elseif (isset($post['log_painel_composicao']) && $post['log_painel_composicao']){
				$sql->esqUnir('painel_composicao', 'painel_composicao', 'painel_composicao_responsavel = usuarios.usuario_id');
				$sql->adOnde('painel_composicao_id='.(int)$post['log_painel_composicao']);
				}
			elseif (isset($post['log_tr']) && $post['log_tr']){
				$sql->esqUnir('tr', 'tr', 'tr_responsavel = usuarios.usuario_id');
				$sql->adOnde('tr_id='.(int)$post['log_tr']);
				}
			elseif (isset($post['log_me']) && $post['log_me']){
				$sql->esqUnir('me', 'me', 'me_usuario = usuarios.usuario_id');
				$sql->adOnde('me_id='.(int)$post['log_me']);
				}
			elseif (isset($post['log_acao_item']) && $post['log_acao_item']){
				$sql->esqUnir('plano_acao_item', 'plano_acao_item', 'plano_acao_item_responsavel = usuarios.usuario_id');
				$sql->adOnde('plano_acao_item_id='.(int)$post['log_acao_item']);
				}	
			elseif (isset($post['log_beneficio']) && $post['log_beneficio']){
				$sql->esqUnir('beneficio', 'beneficio', 'beneficio_usuario = usuarios.usuario_id');
				$sql->adOnde('beneficio_id='.(int)$post['log_beneficio']);
				}	
			elseif (isset($post['log_painel_slideshow']) && $post['log_painel_slideshow']){
				$sql->esqUnir('painel_slideshow', 'painel_slideshow', 'painel_slideshow_responsavel = usuarios.usuario_id');
				$sql->adOnde('painel_slideshow_id='.(int)$post['log_painel_slideshow']);
				}	
			elseif (isset($post['log_projeto_viabilidade']) && $post['log_projeto_viabilidade']){
				$sql->esqUnir('projeto_viabilidade', 'projeto_viabilidade', 'projeto_viabilidade_responsavel = usuarios.usuario_id');
				$sql->adOnde('projeto_viabilidade_id='.(int)$post['log_projeto_viabilidade']);
				}	
			elseif (isset($post['log_projeto_abertura']) && $post['log_projeto_abertura']){
				$sql->esqUnir('projeto_abertura', 'projeto_abertura', 'projeto_abertura_responsavel = usuarios.usuario_id');
				$sql->adOnde('projeto_abertura_id='.(int)$post['log_projeto_abertura']);
				}
			elseif (isset($post['log_plano_gestao']) && $post['log_plano_gestao']){
				$sql->esqUnir('plano_gestao', 'plano_gestao', 'pg_usuario = usuarios.usuario_id');
				$sql->adOnde('pg_id='.(int)$post['log_plano_gestao']);
				}
			elseif (isset($post['log_ssti']) && $post['log_ssti']){
				$sql->esqUnir('ssti', 'ssti', 'ssti_responsavel = usuarios.usuario_id');
				$sql->adOnde('ssti_id='.(int)$post['log_ssti']);
				}
			elseif (isset($post['log_laudo']) && $post['log_laudo']){
				$sql->esqUnir('laudo', 'laudo', 'laudo_responsavel = usuarios.usuario_id');
				$sql->adOnde('laudo_id='.(int)$post['log_laudo']);
				}		
			elseif (isset($post['log_trelo']) && $post['log_trelo']){
				$sql->esqUnir('trelo', 'trelo', 'trelo_responsavel = usuarios.usuario_id');
				$sql->adOnde('trelo_id='.(int)$post['log_trelo']);
				}
			elseif (isset($post['log_trelo_cartao']) && $post['log_trelo_cartao']){
				$sql->esqUnir('trelo_cartao', 'trelo_cartao', 'trelo_cartao_responsavel = usuarios.usuario_id');
				$sql->adOnde('trelo_cartao_id='.(int)$post['log_trelo_cartao']);
				}	
			elseif (isset($post['log_pdcl']) && $post['log_pdcl']){
				$sql->esqUnir('pdcl', 'pdcl', 'pdcl_responsavel = usuarios.usuario_id');
				$sql->adOnde('pdcl_id='.(int)$post['log_pdcl']);
				}	
			elseif (isset($post['log_pdcl_item']) && $post['log_pdcl_item']){
				$sql->esqUnir('pdcl_item', 'pdcl_item', 'pdcl_item_responsavel = usuarios.usuario_id');
				$sql->adOnde('pdcl_item_id='.(int)$post['log_pdcl_item']);
				}		
			else	{
				//esta é para forçar ir em branco se esqueci algum objeto
				$sql->adOnde('usuario_id = 0');
				}

			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');

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
		elseif (isset($post['log_id']) && $post['log_id']) $tipo='atualizado';
		else $tipo='incluido';

		$modulo='';
		if (isset($post['log_tarefa']) && $post['log_tarefa']) $modulo= 'd'.$config['genero_tarefa'].' '.$config['tarefa'];
		elseif (isset($post['log_projeto']) && $post['log_projeto']) $modulo= 'd'.$config['genero_projeto'].' '.$config['projeto'];
		elseif (isset($post['log_perspectiva']) && $post['log_perspectiva']) $modulo= 'd'.$config['genero_perspectiva'].' '.$config['perspectiva'];
		elseif (isset($post['log_tema']) && $post['log_tema']) $modulo= 'd'.$config['genero_tema'].' '.$config['tema'];
		elseif (isset($post['log_objetivo']) && $post['log_objetivo']) $modulo= 'd'.$config['genero_objetivo'].' '.$config['objetivo'];
		elseif (isset($post['log_fator']) && $post['log_fator']) $modulo= 'd'.$config['genero_fator'].' '.$config['fator'];
		elseif (isset($post['log_estrategia']) && $post['log_estrategia']) $modulo= 'd'.$config['genero_iniciativa'].' '.$config['iniciativa'];
		elseif (isset($post['log_meta']) && $post['log_meta']) $modulo= 'd'.$config['genero_meta'].' '.$config['meta'];
		elseif (isset($post['log_pratica']) && $post['log_pratica']) $modulo= 'd'.$config['genero_pratica'].' '.$config['pratica'];
		elseif (isset($post['log_indicador']) && $post['log_indicador']) $modulo= 'do indicador';
		elseif (isset($post['log_acao']) && $post['log_acao']) $modulo= 'd'.$config['genero_acao'].' '.$config['acao'];
		elseif (isset($post['log_canvas']) && $post['log_canvas']) $modulo= 'd'.$config['genero_canvas'].' '.$config['canvas'];
		elseif (isset($post['log_risco']) && $post['log_risco']) $modulo= 'd'.$config['genero_risco'].' '.$config['risco'];
		elseif (isset($post['log_risco_resposta']) && $post['log_risco_resposta']) $modulo= 'd'.$config['genero_risco_resposta'].' '.$config['risco_resposta'];
		elseif (isset($post['log_calendario']) && $post['log_calendario']) $modulo= 'da agenda';
		elseif (isset($post['log_monitoramento']) && $post['log_monitoramento']) $modulo= 'do monitoramento';
		elseif (isset($post['log_ata']) && $post['log_ata']) $modulo= 'da ata de reunião';
		elseif (isset($post['log_mswot']) && $post['log_mswot']) $modulo= 'da matriz SWOT';
		elseif (isset($post['log_swot']) && $post['log_swot']) $modulo= 'do campo de matriz SWOT';
		elseif (isset($post['log_operativo']) && $post['log_operativo']) $modulo= 'do plano operativo';
		elseif (isset($post['log_instrumento']) && $post['log_instrumento']) $modulo= 'do instrumento jurídico';
		elseif (isset($post['log_recurso']) && $post['log_recurso']) $modulo= 'do recurso';
		elseif (isset($post['log_problema']) && $post['log_problema']) $modulo= 'd'.$config['genero_problema'].' '.$config['problema'];
		elseif (isset($post['log_demanda']) && $post['log_demanda']) $modulo= 'da demanda';
		elseif (isset($post['log_programa']) && $post['log_programa']) $modulo= 'd'.$config['genero_programa'].' '.$config['programa'];
		elseif (isset($post['log_licao']) && $post['log_licao']) $modulo= 'd'.$config['genero_licao'].' '.$config['licao'];
		elseif (isset($post['log_evento']) && $post['log_evento']) $modulo= 'do evento';
		elseif (isset($post['log_link']) && $post['log_link']) $modulo= 'do link';
		elseif (isset($post['log_avaliacao']) && $post['log_avaliacao']) $modulo= 'da avaliação';
		elseif (isset($post['log_tgn']) && $post['log_tgn']) $modulo= 'd'.$config['genero_tgn'].' '.$config['tgn'];
		elseif (isset($post['log_brainstorm']) && $post['log_brainstorm']) $modulo= 'do brainstorm';
		elseif (isset($post['log_gut']) && $post['log_gut']) $modulo= 'da matriz GUT';
		elseif (isset($post['log_causa_efeito']) && $post['log_causa_efeito']) $modulo= 'do diagrama de causa-efeito';
		elseif (isset($post['log_arquivo']) && $post['log_arquivo']) $modulo= 'do arquivo';
		elseif (isset($post['log_forum']) && $post['log_forum']) $modulo= 'do fórum';
		elseif (isset($post['log_checklist']) && $post['log_checklist']) $modulo= 'do checklist';
		elseif (isset($post['log_agenda']) && $post['log_agenda']) $modulo= 'do compromisso';
		elseif (isset($post['log_agrupamento']) && $post['log_agrupamento']) $modulo= 'do agrupamento';
		elseif (isset($post['log_patrocinador']) && $post['log_patrocinador']) $modulo= 'do patrocinador';
		elseif (isset($post['log_template']) && $post['log_template']) $modulo= 'do modelo';
		elseif (isset($post['log_painel']) && $post['log_painel']) $modulo= 'do painel de indicador';
		elseif (isset($post['log_painel_odometro']) && $post['log_painel_odometro']) $modulo= 'do odômetro de indicador';
		elseif (isset($post['log_painel_composicao']) && $post['log_painel_composicao']) $modulo= 'da composição de painéis';
		elseif (isset($post['log_tr']) && $post['log_tr']) $modulo= 'd'.$config['genero_tr'].' '.$config['tr'];
		elseif (isset($post['log_me']) && $post['log_me']) $modulo= 'd'.$config['genero_me'].' '.$config['me'];
		elseif (isset($post['log_acao_item']) && $post['log_acao_item']) $modulo= 'do item d'.$config['genero_acao'].' '.$config['acao'];
		elseif (isset($post['log_beneficio']) && $post['log_beneficio']) $modulo= 'd'.$config['genero_beneficio'].' '.$config['beneficio'].' d'.$config['genero_programa'].' '.$config['programa'];
		elseif (isset($post['log_painel_slideshow']) && $post['log_painel_slideshow']) $modulo= 'do slideshow de composições';
		elseif (isset($post['log_projeto_viabilidade']) && $post['log_projeto_viabilidade']) $modulo= 'do estudo de viabilidade';
		elseif (isset($post['log_projeto_abertura']) && $post['log_projeto_abertura']) $modulo= 'do termo de abertura';
		elseif (isset($post['log_plano_gestao']) && $post['log_plano_gestao']) $modulo= 'do planejamento estratégico';
		elseif (isset($post['log_ssti']) && $post['log_ssti']) $modulo= 'd'.$config['genero_ssti'].' '.$config['ssti'];
		elseif (isset($post['log_laudo']) && $post['log_laudo']) $modulo= 'd'.$config['genero_laudo'].' '.$config['laudo'];
		elseif (isset($post['log_trelo']) && $post['log_trelo']) $modulo= 'd'.$config['genero_trelo'].' '.$config['trelo'];
		elseif (isset($post['log_trelo_cartao']) && $post['log_trelo_cartao']) $modulo= 'd'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'];
		elseif (isset($post['log_pdcl']) && $post['log_pdcl']) $modulo= 'd'.$config['genero_pdcl'].' '.$config['pdcl'];
		elseif (isset($post['log_pdcl_item']) && $post['log_pdcl_item']) $modulo= 'd'.$config['genero_pdcl_item'].' '.$config['pdcl_item'];
		
		else $modulo='';
	
		foreach($usuarios as $usuario){
			if (!isset($usado[$usuario['usuario_id']]) && !isset($usado[$usuario['contato_email']])){

				if ($usuario['usuario_id']) $usado[$usuario['usuario_id']]=1;
				if ($usuario['contato_email']) $usado[$usuario['contato_email']]=1;
				$email = new Mail;
				$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)) $email->ResponderPara($Aplic->usuario_email);
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)) $email->ResponderPara($Aplic->usuario_email2);
                   
				if ($tipo == 'excluido') {
					$email->Assunto('Excluído registro de ocorrência '.$modulo, $localidade_tipo_caract);
					$titulo='Excluído registro de ocorrência '.$modulo;
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado registro de ocorrência '.$modulo, $localidade_tipo_caract);
					$titulo='Atualizado registro de ocorrência '.$modulo;
					}
				else {
					$email->Assunto('Inserido registro de ocorrência '.$modulo, $localidade_tipo_caract);
					$titulo='Inserido registro de ocorrência '.$modulo;
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado registro de ocorrência '.$modulo.': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído registro de ocorrência '.$modulo.': '.$nome.'<br>';
				else $corpo = 'Inserido registro de ocorrência '.$modulo.': '.$nome.'<br>';

				if ($tipo!='excluido') $corpo_interno = '<br><a href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'\');"><b>Clique para acessar</b></a>';
	
				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do registro de ocorrência:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do registro de ocorrência:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do registro de ocorrência:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

				if ($usuario['usuario_id']!=$Aplic->usuario_id && $usuario['usuario_id']) {
					if ($usuario['usuario_id']) msg_email_interno('', $titulo, $corpo.$corpo_interno,'',$usuario['usuario_id']);
					if ($email->EmailValido($usuario['contato_email']) && $config['email_ativo']) {
										

						$corpo_externo='';
						if ($Aplic->profissional && $usuario['usuario_id']){
							require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';

							$endereco_link=link_email_externo($usuario['usuario_id'], $endereco);

							$corpo_externo=($endereco_link ? '<br><a href="'.$endereco_link.'"><b>Clique para acessar</b></a>' : '');
							}

						$email->Corpo($corpo.$corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
						
						$email->Para($usuario['contato_email'], true);
						$email->Enviar();
						}
					}
				}
			}
		}

	}



?>