<?php

require_once $Aplic->getClasseSistema('Modelo');
require_once $Aplic->getClasseSistema('Template');

$modeloID=getParam($_REQUEST, 'modeloID', null);
if ($modeloID) $modelo_id=reset($modeloID);
else $modelo_id=getParam($_REQUEST, 'modelo_id', null);

$modelo_tipo_id=getParam($_REQUEST, 'modelo_tipo_id', null);
$modelo_dados_id=getParam($_REQUEST, 'modelo_dados_id', null);
$salvar=getParam($_REQUEST, 'salvar', 0);
$editar=getParam($_REQUEST, 'editar', 0);
$excluir=getParam($_REQUEST, 'excluir', 0);
$aprovar=getParam($_REQUEST, 'aprovar', 0);
$assinar=getParam($_REQUEST, 'assinar', 0);
$anterior=getParam($_REQUEST, 'anterior', 0);
$posterior=getParam($_REQUEST, 'posterior', 0);

$uuid=getParam($_REQUEST, 'uuid', null);

$campo=getParam($_REQUEST, 'campo', 0);
$retornar=getParam($_REQUEST, 'retornar', 'modelo_pesquisar');
$novo=getParam($_REQUEST, 'novo', 0);
$cancelar=getParam($_REQUEST, 'cancelar', 0);
$lista_doc_referencia=getParam($_REQUEST, 'lista_doc_referencia', array());
$lista_msg_referencia=getParam($_REQUEST, 'lista_msg_referencia', array());

$coletivo=($Aplic->usuario_lista_grupo && $Aplic->usuario_lista_grupo!=$Aplic->usuario_id);
$modelo_usuario_id=getParam($_REQUEST, 'modelo_usuario_id', null);


$sql = new BDConsulta;

if (!$modelo_id){
	$sql->adTabela('modelos_tipo');
	$sql->adCampo('modelo_tipo_campos, modelo_tipo_html');
	$sql->adOnde('modelo_tipo_id='.(int)$modelo_tipo_id);
	$linha=$sql->linha();
	$sql->limpar();

	$campos = unserialize($linha['modelo_tipo_campos']);

	$modelo= new Modelo;
	$modelo->set_modelo_tipo($modelo_tipo_id);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
	$tpl = new Template($linha['modelo_tipo_html'],'',$config['militar']);
	$modelo->set_modelo($tpl);


	$modelo->set_modelo_id($modelo_id);


	if ($editar) $modelo->edicao=true;
	else $modelo->edicao=false;
	$criador=$Aplic->usuario_id;
	}





if ($excluir){
	$sql->setExcluir('modelos');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();

	$sql->setExcluir('modelos_dados');
	$sql->adOnde('modelo_dados_modelo='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();

	$sql->adTabela('modelo_anexo');
	$sql->adCampo('modelo_anexo_local, modelo_anexo_nome_real');
	$sql->adOnde('modelo_anexo_modelo='.(int)$modelo_id);
	$arquivo=$sql->Lista();
	$sql->limpar();
	foreach ($resultados as $anexo){
		$caminho=str_replace('/', '\\', $anexo['modelo_anexo_local']);
		if (file_exists($base_dir.'/arquivos/'.$arquivo['modelo_anexo_local'].$arquivo['modelo_anexo_nome_real']))	@unlink($base_dir.'\\arquivos\\'.$arquivo['modelo_anexo_local'].$arquivo['modelo_anexo_nome_real']);
		}
	$sql->setExcluir('modelo_anexo');
	$sql->adOnde('modelo_anexo_modelo='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();

	$sql->setExcluir('anexo');
	$sql->adOnde('anexo_modelo='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();

	$Aplic->redirecionar('m=email&a='.$retornar);
	exit();
	}

//leitura do modelo
if ($modelo_id && !$modelo_tipo_id){
	//Se foi enviado um modelo de documento
	$sql->adTabela('modelo_usuario');
	$sql->adCampo('datahora_leitura, de_id, aviso_leitura');
	$sql->adOnde('modelo_id ='.(int)$modelo_id);
	$sql->adOnde('para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$Aplic->usuario_id));
	$sql_resultadosa = $sql->Lista();
	$sql->limpar();
	foreach ($sql_resultadosa as $rs_leitura){
		if (!$rs_leitura['datahora_leitura']) {
			$data = date('Y-m-d H:i:s');
			$sql->adTabela('modelo_usuario');
			$sql->adAtualizar('datahora_leitura', $data);
			$sql->adAtualizar('status', 1);
			$sql->adOnde('para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$Aplic->usuario_id));
			$sql->adOnde('modelo_id='.(int)$modelo_id);
			$sql->adOnde('datahora_leitura IS NULL');
			$sql->exec();
			$sql->limpar();
			if ($rs_leitura['aviso_leitura']==1 && $Aplic->usuario_id==$usuario_id) aviso_leitura_modelo($rs_leitura['de_id'], $msg_id, $data);
			}
		}
	//Para abranger também os modelos anexados em msg_id
	$sql->adTabela('modelo_leitura');
	$sql->adInserir('datahora_leitura', date('Y-m-d H:i:s'));
	$sql->adInserir('usuario_id', $Aplic->usuario_id);
	$sql->adInserir('modelo_id', $modelo_id);
	$sql->adInserir('download', 0);
	$sql->exec();
	$sql->limpar();
	}

if ($modelo_id && !$modelo_tipo_id){
	$sql->adTabela('modelos');
	$sql->adCampo('modelo_tipo');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$modelo_tipo_id=$sql->Resultado();
	$sql->limpar();
	}

if (!$modelo_tipo_id){
	$Aplic->setMsg('Houve um erro ao carregar o tipo de documento', UI_MSG_ERRO);
	$Aplic->redirecionar('m=email&a='.$retornar);
	exit();
	}

if ($aprovar){
	$sql->adTabela('modelos');
	$sql->adAtualizar('modelo_versao_aprovada',  $modelo_dados_id);
	$sql->adAtualizar('modelo_autoridade_aprovou',  $Aplic->usuario_id);
	$sql->adAtualizar('modelo_aprovou_nome',  $Aplic->usuario_nome);
	$sql->adAtualizar('modelo_aprovou_funcao',  $Aplic->usuario_funcao);
	$sql->adAtualizar('modelo_data_aprovado',  date('Y-m-d H:i:s'));
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();
	ver2('Documento aprovado.');
	}

if ($assinar){
	$sql->adTabela('modelos');
	$sql->adCampo('modelo_versao_aprovada');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$aprovado=$sql->Resultado();
	$sql->limpar();
	$sql->adTabela('modelos_dados');
	$sql->adCampo('modelo_dados_id, modelos_dados_campos, modelos_dados_criador, modelo_dados_data');
	$sql->adOnde('modelo_dados_modelo='.(int)$aprovado);
	$dados_aprovado=$sql->Linha();
	$sql->limpar();
	$assinatura='';
	if (function_exists('openssl_sign') && $Aplic->chave_privada)	{
		$identificador=$dados_aprovado['modelo_dados_id'].md5($dados_aprovado['modelos_dados_campos']).$dados_aprovado['modelos_dados_criador'].$dados_aprovado['modelo_dados_data'];
		openssl_sign($identificador, $assinatura, $Aplic->chave_privada);
		}
	$sql->adTabela('modelos');
	$sql->adAtualizar('modelo_autoridade_assinou',  $Aplic->usuario_id);
	$sql->adAtualizar('modelo_assinatura_nome',  $Aplic->usuario_nome);
	$sql->adAtualizar('modelo_assinatura_funcao',  $Aplic->usuario_funcao);
	$sql->adAtualizar('modelo_data_assinado',  date('Y-m-d H:i:s'));
	$sql->adAtualizar('modelo_assinatura', base64_encode($assinatura));
	$sql->adAtualizar('modelo_chave_publica', $Aplic->chave_publica_id);
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();
	echo '<script>alert("Documento assinado.")</script>';
	}

if ($salvar){
	
	$novo_modelo=0;
	
	if (!$modelo_id){
		$sql->adTabela('modelos');
		$sql->adInserir('modelo_tipo', $modelo_tipo_id);
		$sql->adInserir('modelo_criador_original',  $Aplic->usuario_id);
		$sql->adInserir('modelo_criador_nome',  $Aplic->usuario_nome);
		$sql->adInserir('modelo_criador_funcao',  $Aplic->usuario_funcao);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela modelos!');
		$modelo_id=$bd->Insert_ID('modelos','modelo_id');
		$sql->limpar();
		$novo_modelo=1;
		}

	$sql->adTabela('modelos');
	$sql->esqUnir('modelos_tipo','modelos_tipo','modelos_tipo.modelo_tipo_id=modelos.modelo_tipo');
	$sql->adCampo('modelo_tipo, modelo_data, organizacao, modelo_tipo_html');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$linha=$sql->Linha();

	$sql->adTabela('modelos');
	$sql->adAtualizar('modelo_assunto',  getParam($_REQUEST, 'assunto', ''));
	$sql->adAtualizar('class_sigilosa',  getParam($_REQUEST, 'class_sigilosa', 0));
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();

	if (!$linha['modelo_data']){
		$sql->adTabela('modelos');
		$sql->adAtualizar('modelo_data',  date('Y-m-d H:i:s'));
		$sql->adOnde('modelo_id='.(int)$modelo_id);
		$sql->exec();
		$sql->limpar();
		}

	$sql->adTabela('modelos_tipo');
	$sql->adCampo('modelo_tipo_campos');
	$sql->adOnde('modelo_tipo_id='.(int)$linha['modelo_tipo']);
	$campos = unserialize($sql->Resultado());

	$sql->limpar();
	$modelo= new Modelo;
	$modelo->set_modelo_tipo($linha['modelo_tipo']);
	$modelo->set_modelo_id($modelo_id);

	foreach((array)$campos['campo'] as $posicao => $campo) {
		
		if ($campo['tipo']=='remetente'){
			$resultado=array();
			$resultado[0]=getParam($_REQUEST, 'remetente_'.$posicao, '');
			$resultado[1]=getParam($_REQUEST, 'remetente_funcao_'.$posicao, '');
			$modelo->set_campo($campo['tipo'], $resultado, $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
			}

		elseif ($campo['tipo']=='protocolo_secao'){
			$resultado=array();
			$resultado[0]=getParam($_REQUEST, 'dept_protocolo', '');
			$resultado[1]=getParam($_REQUEST, 'dept_qnt_nr', '');
			$modelo->set_campo($campo['tipo'], $resultado, $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
			}

		elseif ($campo['tipo']=='impedimento'){
			$resultado=array();
			$resultado[0]=getParam($_REQUEST, 'impedimento_'.$posicao, '');
			$resultado[1]=getParam($_REQUEST, 'posto_'.$posicao, '');
			$resultado[2]=getParam($_REQUEST, 'nomeguerra_'.$posicao, '');
			$resultado[3]=getParam($_REQUEST, 'funcao_'.$posicao, '');
			$resultado[7]=getParam($_REQUEST, 'assinante_'.$posicao, '');
			$resultado[9]=getParam($_REQUEST, 'ordem_postonome_'.$posicao, '');
			if ($resultado[0]){
				$resultado[4]=getParam($_REQUEST, 'postor_'.$posicao, '');
				$resultado[5]=getParam($_REQUEST, 'nomeguerrar_'.$posicao, '');
				$resultado[6]=getParam($_REQUEST, 'funcaor_'.$posicao, '');
				$resultado[8]=getParam($_REQUEST, 'assinanter_'.$posicao, '');
				$resultado[10]=getParam($_REQUEST, 'ordem_postonomer_'.$posicao, '');
				}
			$modelo->set_campo($campo['tipo'], $resultado, $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
			}
			
		elseif ($campo['tipo']=='assinatura'){
			$resultado=array();
			$resultado[0]=getParam($_REQUEST, 'posto_'.$posicao, '');
			$resultado[1]=getParam($_REQUEST, 'nomeguerra_'.$posicao, '');
			$resultado[2]=getParam($_REQUEST, 'funcao_'.$posicao, '');
			$resultado[3]=getParam($_REQUEST, 'assinante_'.$posicao, '');
			$resultado[4]=getParam($_REQUEST, 'ordem_postonome_'.$posicao, '');
			$modelo->set_campo($campo['tipo'], $resultado, $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
			}
			
		elseif ($campo['tipo']=='destinatarios'){
			$resultado=array();
			$resultado[0]=getParam($_REQUEST, 'campo_'.$posicao, '');
			$lista_destinatarios=getParam($_REQUEST, 'lista_destinatarios_'.$posicao, '');
			$funcao_destinatarios=getParam($_REQUEST, 'funcao_destinatarios_'.$posicao, '');
			$lista_destinatarios=explode('#', $lista_destinatarios);
			$funcao_destinatarios=explode('#', $funcao_destinatarios);
			for ($i=0; $i < count($lista_destinatarios); $i++){
				if ($lista_destinatarios[$i]) $resultado[$i+1]=array($lista_destinatarios[$i], $funcao_destinatarios[$i]);
				}
			$modelo->set_campo($campo['tipo'], $resultado, $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
			}

		elseif ($campo['tipo']=='anexo'){
			$anexos=getParam($_REQUEST, 'anexo_'.$posicao, '');
			$nomes_fantasia=getParam($_REQUEST, 'nome_fantasia_'.$posicao, '');
			$resultado=array();
			foreach ((array)$anexos as $chave => $modelo_anexo){
				if (isset($nomes_fantasia[$chave])) $resultado[$modelo_anexo]=$nomes_fantasia[$chave];
				}
			$modelo->set_campo($campo['tipo'], $resultado, $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
			}
		else $modelo->set_campo($campo['tipo'], getParam($_REQUEST, 'campo_'.$posicao, null), $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
		
		}
	$tpl = new Template($linha['modelo_tipo_html'],'',$config['militar']);
	$modelo->set_modelo($tpl);
	$modelo->edicao=false;
	$editar=0;
	$vars = get_object_vars($modelo);
	$sql->adTabela('modelos_dados');
	$sql->adInserir('modelo_dados_modelo', $modelo_id);
  if( config('tipoBd') == 'postgres') $sql->adInserir('modelos_dados_campos', addslashes(serialize($vars)));
  else $sql->adInserir('modelos_dados_campos', serialize($vars));

	$sql->adInserir('modelos_dados_criador', $Aplic->usuario_id);
	$sql->adInserir('nome_usuario', ($Aplic->usuario_posto ? $Aplic->usuario_posto.' ' : '').$Aplic->usuario_nomeguerra);
	$sql->adInserir('funcao_usuario', $Aplic->usuario_funcao);
	$sql->adInserir('modelo_dados_data',  date('Y-m-d H:i:s'));
	$sql->exec();
	$sql->limpar();
	$modelo_dados_id=$bd->Insert_ID('modelos_dados','modelo_dados_id');
	//grava o documento

	//referencias
	//excluir antigas referencias
	$sql->setExcluir('referencia');
	$sql->adOnde('referencia_doc_filho = '.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();
	foreach((array)$lista_doc_referencia as $chave => $doc_id_pai){
		$sql->adTabela('referencia');
	  $sql->adInserir('referencia_doc_pai', $doc_id_pai);
		$sql->adInserir('referencia_doc_filho', $modelo_id);
		$sql->adInserir('referencia_responsavel', $Aplic->usuario_id);
		$sql->adInserir('referencia_data', date('Y-m-d H:i:s'));
		$sql->adInserir('referencia_nome_de', $Aplic->usuario_nome);
		$sql->adInserir('referencia_funcao_de', $Aplic->usuario_funcao);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela referencia!');
		$sql->limpar();
		}
	foreach((array)$lista_msg_referencia as $chave => $msg_id_pai){
		$sql->adTabela('referencia');
	  $sql->adInserir('referencia_msg_pai', $msg_id_pai);
		$sql->adInserir('referencia_doc_filho', $modelo_id);
		$sql->adInserir('referencia_responsavel', $Aplic->usuario_id);
		$sql->adInserir('referencia_data', date('Y-m-d H:i:s'));
		$sql->adInserir('referencia_nome_de', $Aplic->usuario_nome);
		$sql->adInserir('referencia_funcao_de', $Aplic->usuario_funcao);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela referencia!');
		$sql->limpar();
		}

	if ($uuid){
			$sql->adTabela('modelo_gestao');
			$sql->adAtualizar('modelo_gestao_modelo', (int)$modelo_id);
			$sql->adAtualizar('modelo_gestao_uuid', null);
			$sql->adOnde('modelo_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			
			$sql->adTabela('modelo_anexo');
			$sql->adAtualizar('modelo_anexo_uuid',  null);
			$sql->adAtualizar('modelo_anexo_modelo', (int)$modelo_id);
			$sql->adOnde('modelo_anexo_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}
	$salvar=0;
	$novo=0;
	
	//verificar aprovacao
	if ($Aplic->profissional) {
		$sql->adTabela('assinatura');
		$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
		$sql->adCampo('count(assinatura_id)');
		$sql->adOnde('assinatura_modelo='.(int)$modelo_id);
		$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
		$sql->adOnde('assinatura_aprova=1');
		$sql->adOnde('assinatura_atesta_opcao > 0');
		$nao_aprovado1 = $sql->resultado();
		$sql->limpar();
		
		
		$sql->adTabela('assinatura');
		$sql->adCampo('count(assinatura_id)');
		$sql->adOnde('assinatura_modelo='.(int)$modelo_id);
		$sql->adOnde('assinatura_aprova=1');
		$sql->adOnde('assinatura_atesta IS NULL');
		$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
		$nao_aprovado2 = $sql->resultado();
		$sql->limpar();
		
		//assinatura que tem despacho mas nem assinou
		$sql->adTabela('assinatura');
		$sql->adCampo('count(assinatura_id)');
		$sql->adOnde('assinatura_modelo='.(int)$modelo_id);
		$sql->adOnde('assinatura_aprova=1');
		$sql->adOnde('assinatura_atesta IS NOT NULL');
		$sql->adOnde('assinatura_atesta_opcao IS NULL');
		$nao_aprovado3 = $sql->resultado();
		$sql->limpar();
		
		$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
		
		$sql->adTabela('modelos');
		$sql->adAtualizar('modelo_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('modelo_id='.(int)$modelo_id);
		$sql->exec();
		$sql->limpar();
		}
	
	
	
	if ($novo_modelo){
		$sql->adTabela('modelo_gestao');
		$sql->adCampo('modelo_gestao.*');
		$sql->adOnde('modelo_gestao_modelo='.(int)$modelo_id);
		$sql->adOrdem('modelo_gestao_ordem ASC');
		$linha=$sql->linha();
		$sql->limpar();
		
		$sql->adTabela('modelo_gestao');
		$sql->adCampo('count(modelo_gestao_id)');
		$sql->adOnde('modelo_gestao_modelo='.(int)$modelo_id);
		$qnt=$sql->Resultado();
		$sql->limpar();
		
		if ($linha['modelo_gestao_tarefa'] && $qnt==1) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['modelo_gestao_tarefa'];
		elseif ($linha['modelo_gestao_projeto'] && $qnt==1) $endereco='m=projetos&a=ver&projeto_id='.$linha['modelo_gestao_projeto'];
		elseif ($linha['modelo_gestao_perspectiva'] && $qnt==1) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['modelo_gestao_perspectiva'];
		elseif ($linha['modelo_gestao_tema'] && $qnt==1) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['modelo_gestao_tema'];
		elseif ($linha['modelo_gestao_objetivo'] && $qnt==1) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['modelo_gestao_objetivo'];
		elseif ($linha['modelo_gestao_fator'] && $qnt==1) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['modelo_gestao_fator'];
		elseif ($linha['modelo_gestao_estrategia'] && $qnt==1) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['modelo_gestao_estrategia'];
		elseif ($linha['modelo_gestao_meta'] && $qnt==1) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['modelo_gestao_meta'];
		elseif ($linha['modelo_gestao_pratica'] && $qnt==1) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['modelo_gestao_pratica'];
		elseif ($linha['modelo_gestao_indicador'] && $qnt==1) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['modelo_gestao_indicador'];
		elseif ($linha['modelo_gestao_acao'] && $qnt==1) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['modelo_gestao_acao'];
		elseif ($linha['modelo_gestao_canvas'] && $qnt==1) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['modelo_gestao_canvas'];
		elseif ($linha['modelo_gestao_risco'] && $qnt==1) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['modelo_gestao_risco'];
		elseif ($linha['modelo_gestao_risco_resposta'] && $qnt==1) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['modelo_gestao_risco_resposta'];
		elseif ($linha['modelo_gestao_calendario'] && $qnt==1) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['modelo_gestao_calendario'];
		elseif ($linha['modelo_gestao_monitoramento'] && $qnt==1) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['modelo_gestao_monitoramento'];
		elseif ($linha['modelo_gestao_ata'] && $qnt==1) $endereco='m=atas&a=ata_ver&ata_id='.$linha['modelo_gestao_ata'];
		elseif ($linha['modelo_gestao_mswot'] && $qnt==1) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['modelo_gestao_mswot'];
		elseif ($linha['modelo_gestao_swot'] && $qnt==1) $endereco='m=swot&a=swot_ver&swot_id='.$linha['modelo_gestao_swot'];
		elseif ($linha['modelo_gestao_operativo'] && $qnt==1) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['modelo_gestao_operativo'];
		elseif ($linha['modelo_gestao_instrumento'] && $qnt==1) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['modelo_gestao_instrumento'];
		elseif ($linha['modelo_gestao_recurso'] && $qnt==1) $endereco='m=recursos&a=ver&recurso_id='.$linha['modelo_gestao_recurso'];
		elseif ($linha['modelo_gestao_problema'] && $qnt==1) $endereco='m=problema&a=problema_ver&problema_id='.$linha['modelo_gestao_problema'];
		elseif ($linha['modelo_gestao_demanda'] && $qnt==1) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['modelo_gestao_demanda'];
		elseif ($linha['modelo_gestao_programa'] && $qnt==1) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['modelo_gestao_programa'];
		elseif ($linha['modelo_gestao_licao'] && $qnt==1) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['modelo_gestao_licao'];
		elseif ($linha['modelo_gestao_evento'] && $qnt==1) $endereco='m=calendario&a=ver&evento_id='.$linha['modelo_gestao_evento'];
		elseif ($linha['modelo_gestao_link'] && $qnt==1) $endereco='m=links&a=ver&link_id='.$linha['modelo_gestao_link'];
		elseif ($linha['modelo_gestao_avaliacao'] && $qnt==1) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['modelo_gestao_avaliacao'];
		elseif ($linha['modelo_gestao_tgn'] && $qnt==1) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['modelo_gestao_tgn'];
		elseif ($linha['modelo_gestao_brainstorm'] && $qnt==1) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['modelo_gestao_brainstorm'];
		elseif ($linha['modelo_gestao_gut'] && $qnt==1) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['modelo_gestao_gut'];
		elseif ($linha['modelo_gestao_causa_efeito'] && $qnt==1) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['modelo_gestao_causa_efeito'];
		elseif ($linha['modelo_gestao_arquivo'] && $qnt==1) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['modelo_gestao_arquivo'];
		elseif ($linha['modelo_gestao_forum'] && $qnt==1) $endereco='m=foruns&a=ver&forum_id='.$linha['modelo_gestao_forum'];
		elseif ($linha['modelo_gestao_checklist'] && $qnt==1) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['modelo_gestao_checklist'];
		elseif ($linha['modelo_gestao_agenda'] && $qnt==1) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['modelo_gestao_agenda'];
		elseif ($linha['modelo_gestao_agrupamento'] && $qnt==1) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['modelo_gestao_agrupamento'];
		elseif ($linha['modelo_gestao_patrocinador'] && $qnt==1) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['modelo_gestao_patrocinador'];
		elseif ($linha['modelo_gestao_template'] && $qnt==1) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['modelo_gestao_template'];
		elseif ($linha['modelo_gestao_painel'] && $qnt==1) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['modelo_gestao_painel'];
		elseif ($linha['modelo_gestao_painel_odometro'] && $qnt==1) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['modelo_gestao_painel_odometro'];
		elseif ($linha['modelo_gestao_painel_composicao'] && $qnt==1) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['modelo_gestao_painel_composicao'];
		elseif ($linha['modelo_gestao_tr'] && $qnt==1) $endereco='m=tr&a=tr_ver&tr_id='.$linha['modelo_gestao_tr'];
		elseif ($linha['modelo_gestao_me'] && $qnt==1) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['modelo_gestao_me'];
		elseif ($linha['modelo_gestao_acao_item'] && $qnt==1) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['modelo_gestao_acao_item'];
		elseif ($linha['modelo_gestao_beneficio'] && $qnt==1) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['modelo_gestao_beneficio'];
		elseif ($linha['modelo_gestao_painel_slideshow'] && $qnt==1) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['modelo_gestao_painel_slideshow'];
		elseif ($linha['modelo_gestao_projeto_viabilidade'] && $qnt==1) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['modelo_gestao_projeto_viabilidade'];
		elseif ($linha['modelo_gestao_projeto_abertura'] && $qnt==1) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['modelo_gestao_projeto_abertura'];
		elseif ($linha['modelo_gestao_plano_gestao'] && $qnt==1) $endereco='m=praticas&a=menu&u=gestao&pg_id='.$linha['modelo_gestao_plano_gestao'];
		elseif ($linha['modelo_gestao_ssti'] && $qnt==1 && !$modelo_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['modelo_gestao_ssti'];
		elseif ($linha['modelo_gestao_laudo'] && $qnt==1 && !$modelo_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['modelo_gestao_laudo'];
		elseif ($linha['modelo_gestao_trelo'] && $qnt==1 && !$modelo_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['modelo_gestao_trelo'];
		elseif ($linha['modelo_gestao_trelo_cartao'] && $qnt==1 && !$modelo_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['modelo_gestao_trelo_cartao'];
		elseif ($linha['modelo_gestao_pdcl'] && $qnt==1 && !$modelo_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['modelo_gestao_pdcl'];
		elseif ($linha['modelo_gestao_pdcl_item'] && $qnt==1 && !$modelo_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['modelo_gestao_pdcl_item'];
		else $endereco='';
		if ($endereco) $Aplic->redirecionar($endereco);
		}	 
	}
$Aplic->redirecionar('m=email&a=modelo_ver&modelo_id='.(int)$modelo_id);
?>