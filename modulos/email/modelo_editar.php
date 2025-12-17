<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

global $dialogo, $tab,$vetor_modelo, $msg_id;

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();
$Aplic->carregarComboMultiSelecaoJS();

require_once $Aplic->getClasseSistema('Modelo');
require_once $Aplic->getClasseSistema('Template');

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);


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

$campo=getParam($_REQUEST, 'campo', 0);
$retornar=getParam($_REQUEST, 'retornar', 'modelo_pesquisar');
$novo=getParam($_REQUEST, 'novo', 0);
$cancelar=getParam($_REQUEST, 'cancelar', 0);
$lista_doc_referencia=getParam($_REQUEST, 'lista_doc_referencia', array());
$lista_msg_referencia=getParam($_REQUEST, 'lista_msg_referencia', array());

if (isset($vetor_modelo[$tab]) && $vetor_modelo[$tab]) $modelo_id=$vetor_modelo[$tab];
$coletivo=($Aplic->usuario_lista_grupo && $Aplic->usuario_lista_grupo!=$Aplic->usuario_id);
$modelo_usuario_id=getParam($_REQUEST, 'modelo_usuario_id', null);

//caso seja um novo documento os anexos usarão a chave criada

$uuid=getParam($_REQUEST, 'uuid', null);

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="a" id="a" value="modelo_editar">';
echo '<input type=hidden name="m" id="email" value="email">';
echo '<input type="hidden" name="fazerSQL" value="modelo_fazer_sql" />';
echo '<input type=hidden name="anexo" id="anexo"  value="">';
echo '<input type=hidden name="sem_cabecalho" id="sem_cabecalho" value="">';
echo '<input type=hidden name="excluir" id="excluir"  value="">';
echo '<input type=hidden name="salvar" id="salvar"  value="">';
echo '<input type=hidden name="aprovar" id="aprovar"  value="">';
echo '<input type=hidden name="assinar" id="assinar"  value="">';
echo '<input type=hidden name="anterior" id="anterior"  value="">';
echo '<input type=hidden name="posterior" id="posterior"  value="">';
echo '<input type=hidden name="editar" id="editar"  value="'.$editar.'">';
echo '<input type=hidden name="modelo_id" id="modelo_id"  value="'.$modelo_id.'">';
echo '<input type=hidden name="modelo_tipo_id" id="modelo_tipo_id"  value="'.$modelo_tipo_id.'">';
echo '<input type=hidden name="modelo_usuario_id" id="modelo_usuario_id"  value="'.$modelo_usuario_id.'">';
$uuid=($modelo_id ? null : uuid());
echo '<input type=hidden name="uuid" id="uuid" value="'.$uuid.'" />';
echo '<input type=hidden name="msg_id" id="msg_id"  value="'.(isset($msg_id) ? $msg_id : '').'">';
echo '<input type=hidden name="dialogo" id="dialogo"  value="'.$dialogo.'">';
echo '<input type=hidden name="tab" id="tab"  value="'.(isset($tab) ? $tab : '').'">';
echo '<input type=hidden name="modelo_dados_id" id="modelo_dados_id" value="'.(isset($dados['modelo_dados_id']) ? $dados['modelo_dados_id'] : '').'">';
echo '<input type=hidden name="campo_atual" id="campo_atual"  value="">';
echo '<input type=hidden name="novo" id="novo"  value="'.$novo.'">';
echo '<input type=hidden name="retornar" id="retornar" value="'.$retornar.'">';
echo '<input type=hidden name="cancelar" id="cancelar" value="">';
echo '<input type=hidden name="tipo" id="tipo" value="">';
echo '<input type=hidden name="destino" id="destino" value="">';
echo '<input type=hidden name="status" id="status" value="">';
echo '<input type=hidden name="pasta" id="pasta" value="">';
echo '<input type=hidden name="mover" id="mover" value="">';
echo '<input type=hidden name="arquivar" id="arquivar" value="">';


$sql = new BDConsulta;

//criar um novo documento
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


	$modelo->edicao=true;
	$criador=$Aplic->usuario_id;
	}

if ($modelo_id){
	$sql->adTabela('modelos');
	$sql->esqUnir('modelos_tipo','modelos_tipo','modelos_tipo.modelo_tipo_id=modelos.modelo_tipo');
	$sql->adCampo('class_sigilosa, modelo_assinatura, modelo_chave_publica, modelo_id, modelo_tipo, modelo_criador_original, modelo_data, modelo_versao_aprovada, modelo_protocolo, modelo_autoridade_assinou, modelo_autoridade_aprovou, modelo_assunto, organizacao, modelo_tipo_html');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$linha=$sql->Linha();

	$sql->limpar();
	$sql->adTabela('modelos_dados');
	$sql->esqUnir('usuarios', 'usuarios', 'usuario_id = modelos_dados_criador');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('contato_funcao, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adCampo('modelo_dados_id, modelos_dados_campos, modelos_dados_criador, modelo_dados_data');
	$sql->adOnde('modelo_dados_modelo='.(int)$modelo_id);
	
	
	$sql->adOrdem('modelo_dados_id DESC');
	$dados=$sql->Linha();
	$sql->limpar();
	$modelo_dados_id=$dados['modelo_dados_id'];
	$criador=$dados['modelos_dados_criador'];

  //desserializa o documento gravado
  if( config('tipoBd') == 'postgres') $campos = unserialize(stripslashes($dados['modelos_dados_campos']));
  else $campos = unserialize($dados['modelos_dados_campos']);

	$modelo= new Modelo;
	$modelo->set_modelo_tipo($modelo_tipo_id);
	$modelo->set_modelo_id($modelo_id);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
	$tpl = new Template($linha['modelo_tipo_html'],'',$config['militar']);
	$modelo->set_modelo($tpl);

	$modelo->edicao=true;
	}
$qnt_antes=0;
$qnt_depois=0;



if ($modelo_dados_id && $modelo_id){
	$sql->adTabela('modelos_dados');
	$sql->adCampo('count(modelo_dados_id)');
	$sql->adOnde('modelo_dados_id <'.(int)$modelo_dados_id);
	$sql->adOnde('modelo_dados_modelo ='.(int)$modelo_id);
	$qnt_antes=$sql->Resultado();
	$sql->limpar();
	$sql->adTabela('modelos_dados');
	$sql->adCampo('count(modelo_dados_id)');
	$sql->adOnde('modelo_dados_id >'.(int)$modelo_dados_id);
	$sql->adOnde('modelo_dados_modelo ='.(int)$modelo_id);
	$qnt_depois=$sql->Resultado();
	$sql->limpar();
	}




$assinado='';
if (function_exists('openssl_sign') && isset($linha['modelo_assinatura']) && $linha['modelo_assinatura']){
	$sql->adTabela('chaves_publicas');
	$sql->adCampo('chave_publica_chave, chave_publica_usuario');
	$sql->adOnde('chave_publica_id="'.$linha['modelo_chave_publica'].'"');
	$chave_publica=$sql->Linha();
	$sql->limpar();

	$sql->adTabela('modelos_dados');
	$sql->adCampo('modelo_dados_id, modelos_dados_campos, modelos_dados_criador, modelo_dados_data');
	$sql->adOnde('modelo_dados_modelo='.(int)$linha['modelo_versao_aprovada']);
	$dados_aprovado=$sql->Linha();
	$sql->limpar();

	$identificador=$dados_aprovado['modelo_dados_id'].md5($dados_aprovado['modelos_dados_campos']).$dados_aprovado['modelos_dados_criador'].$dados_aprovado['modelo_dados_data'];
	$ok = openssl_verify($identificador, base64_decode($linha['modelo_assinatura']), $chave_publica['chave_publica_chave'], OPENSSL_ALGO_SHA1);

	if (!$ok) $assinado='&nbsp;'.dica(nome_funcao('','','','',$chave_publica['chave_publica_usuario']),'A assinatura digital do documento não confere! Documento possívelmente adulterado.').'<img src="'.acharImagem('icones/assinatura_erro.gif').'" style="vertical-align:top" width="15" height="13" />'.dicaF();
	else $assinado='&nbsp;'.dica(nome_funcao('','','','',$chave_publica['chave_publica_usuario']),'A assinatura digital do documento confere .').'<img src="'.acharImagem('icones/assinatura.gif').'" style="vertical-align:top" width="15" height="13" />'.dicaF();
	}



$sql->adTabela('modelo_usuario');
$sql->esqUnir('modelo_anotacao','modelo_anotacao','modelo_anotacao.modelo_anotacao_id=modelo_usuario.modelo_anotacao_id');
$sql->adCampo('modelo_usuario.tipo, de_id, para_id, status, pasta_id, data_limite, data_retorno, resposta_despacho, concatenar_tres( modelo_anotacao.nome_de, \' - \', modelo_anotacao.funcao_de) AS nome_despachante, texto');
$sql->adOnde('modelo_usuario.modelo_usuario_id='.(int)$modelo_usuario_id);
$enviado = $sql->Linha();
$sql->limpar();

$podeEditar=false;









if (!$dialogo){
	
	
	$botoesTitulo = new CBlocoTitulo(($modelo_id? 'Editar' : 'Criar').' Documento', 'documento.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	
	echo estiloTopoCaixa();	
	
	echo '<table rules="ALL" border=1 align="center" cellspacing=0 cellpadding=0 style="width:100%;">';
	echo '<tr><td colspan=2 style="background-color: #212121">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";

	$km->Add("root","root_salvar",dica('Salvar', 'Salvar o documento.').'Salvar'.dicaF(), "javascript: void(0);' onclick='salvar_doc();");
	$km->Add("root","root_referenciar",dica('Referenciar Documento', 'Abre uma janela para procurar um documento ao qual este documento fará referência.').'Referenciar Documento'.dicaF(), "javascript: void(0);' onclick='popDocumentos_referencia2();");
	
	$km->Add("root","root_referenciar",dica('Referenciar '.ucfirst($config['mensagem']), 'Abre uma janela para procurar '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' que este documento fará referência.').'Referenciar '.ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='popMensagem();");
	if ($criador==$Aplic->usuario_id  && !$novo) $km->Add("root","root_excluir",dica('Excluir', 'Excluir este documento.').'Excluir'.dicaF(), "javascript: void(0);' onclick='if(confirm(\"Tem certeza que deseja excluir este documento?\")){env.sem_cabecalho.value=0; env.excluir.value=1; env.submit();}");
	$km->Add("root","root_cancelar",dica('Cancelar', 'Cancelar a '.($modelo_id ? 'edição': 'criação').' deste documento.').'Cancelar'.dicaF(), "javascript: void(0);' onclick='if(confirm(\"Tem certeza que deseja cancelar?\")){url_passar(0, \"".$Aplic->getPosicao()."\");}");
	echo $km->Render();
	echo '</td></tr>';
	echo '</table>';
	}




echo '<table width="100%" align="center" cellspacing=0 cellpadding=0 class="std">';


//preencher as referencias

$vetor_msg_referencia=array();
$vetor_doc_referencia=array();
$sql->adTabela('referencia');
$sql->esqUnir('msg', 'msg', 'msg.msg_id=referencia.referencia_msg_pai');
$sql->esqUnir('modelos', 'modelos', 'modelos.modelo_id=referencia.referencia_doc_pai');
$sql->adCampo('referencia.*, msg.de_id, modelos.*, msg.referencia, msg.data_envio, nome_de, funcao_de');
$sql->adOnde('referencia_doc_filho = '.(int)$modelo_id);
$lista_referencia_pai = $sql->Lista();
$sql->limpar();
if ($lista_referencia_pai && count($lista_referencia_pai)) {
	$qnt_lista_referencia_pai=count($lista_referencia_pai);
	for ($i = 0, $i_cmp = $qnt_lista_referencia_pai; $i < $i_cmp; $i++) {
		if ($lista_referencia_pai[$i]['referencia_msg_pai']) {
			$lista= 'Msg. '.$lista_referencia_pai[$i]['referencia_msg_pai'].($lista_referencia_pai[$i]['referencia']? ' - '.$lista_referencia_pai[$i]['referencia'] : '').' - '.nome_funcao($lista_referencia_pai[$i]['nome_de'], '', $lista_referencia_pai[$i]['funcao_de'], '', $lista_referencia_pai[$i]['de_id']).' - '.retorna_data($lista_referencia_pai[$i]['data_envio'], false);
			$vetor_msg_referencia[$lista_referencia_pai[$i]['referencia_msg_pai']]=$lista;
			}
		else {
			if ($lista_referencia_pai[$i]['modelo_autoridade_assinou']) {
				$nome=nome_funcao($lista_referencia_pai[$i]['modelo_assinatura_nome'], '', $lista_referencia_pai[$i]['modelo_assinatura_funcao'], '', $lista_referencia_pai[$i]['modelo_autoridade_assinou']);
				$data=retorna_data($lista_referencia_pai[$i]['modelo_data_assinado'], false);
				}
			elseif ($lista_referencia_pai[$i]['modelo_autoridade_aprovou']) {
				$nome=nome_funcao($lista_referencia_pai[$i]['modelo_aprovou_nome'], '', $lista_referencia_pai[$i]['modelo_aprovou_funcao'], '', $lista_referencia_pai[$i]['modelo_autoridade_aprovou']);
				$data=retorna_data($lista_referencia_pai[$i]['modelo_data_aprovado'], false);
				}
			else {
				$nome=nome_funcao($lista_referencia_pai[$i]['modelo_criador_nome'], '', $lista_referencia_pai[$i]['modelo_criador_funcao'], '', $lista_referencia_pai[$i]['modelo_criador_original']);
				$data=retorna_data($lista_referencia_pai[$i]['modelo_data'], false);
				}
			$lista= 'Doc. '.$lista_referencia_pai[$i]['referencia_doc_pai'].($lista_referencia_pai[$i]['modelo_assunto']? ' - '.$lista_referencia_pai[$i]['modelo_assunto'] : '').' - '.$nome.' - '.$data;
			$vetor_doc_referencia[$lista_referencia_pai[$i]['referencia_doc_pai']]=$lista;
			}
		}
	}
echo '<tr id="mensagens_referencia" style="display:'.(count($vetor_msg_referencia)? '' : 'none').'"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['mensagens']).' Referenciad'.$config['genero_mensagem'].'s','Lista de '.$config['mensagens'].' a'.($config['genero_mensagem']=='o' ? 'o' : '').'s quais este documento faz referência.').'Mensagens referenciadas:'.dicaF().'</td><td align="left">'.selecionaVetor($vetor_msg_referencia, 'lista_msg_referencia[]', ' multiple size=3 class="texto" style="width:400px;" ondblClick="javascript:remover_msg(); return false;"','','','lista_msg_referencia').'</td></tr>';
echo '<tr id="documentos_referencia" style="display:'.(count($vetor_doc_referencia)? '' : 'none').'"><td align="right" style="white-space: nowrap">'.dica('Documentos Referenciados','Lista de documentos aos quais este documento faz referência.').'Documentos referenciados:'.dicaF().'</td><td align="left">'.selecionaVetor($vetor_doc_referencia, 'lista_doc_referencia[]', ' multiple size=3 class="texto" style="width:400px;" ondblClick="javascript:remover_referencia(); return false;"','','','lista_doc_referencia').'</td></tr>';



$class_sigilosa=getSisValor('class_sigilosa', '','CAST(sisvalor_valor_id AS '. ( $config['tipoBd']==	'mysql' ? 'UNSIGNED' : '' ). ' INTEGER) <= '.(int)$Aplic->usuario_acesso_email, 'sisvalor_valor_id ASC');
echo '<tr><td align=right width=30 style="white-space: nowrap">'.dica('Assunto','Assunto a que este documento se refere.').'Assunto:'.dicaF().'</td><td><input type="text" class="texto" name="assunto" value="'.(isset($linha['modelo_assunto']) ? $linha['modelo_assunto'] : '').'" style="width:400px;" /></td></tr>';
echo '<tr><td align="right" width=30 style="white-space: nowrap">'.dica('Sigilo', 'Escolha a classsificação sigilosa deste documento.<br>Somente '.$config['genero_usuario'].'s '.$config['usuarios'].' com perfil acesso compatível poderão visualiza-lo').'Sigilo: '.dicaF().'</td><td>'.selecionaVetor($class_sigilosa, 'class_sigilosa','class="texto" size=1 style="width:400px"',(isset($linha['class_sigilosa']) ? $linha['class_sigilosa'] : '')).'</td></tr>';

$modelo_projeto=getParam($_REQUEST, 'modelo_projeto', null);
$modelo_tarefa=getParam($_REQUEST, 'modelo_tarefa', null);
$modelo_perspectiva=getParam($_REQUEST, 'modelo_perspectiva', null);
$modelo_tema=getParam($_REQUEST, 'modelo_tema', null);
$modelo_objetivo=getParam($_REQUEST, 'modelo_objetivo', null);
$modelo_fator=getParam($_REQUEST, 'modelo_fator', null);
$modelo_estrategia=getParam($_REQUEST, 'modelo_estrategia', null);
$modelo_meta=getParam($_REQUEST, 'modelo_meta', null);
$modelo_pratica=getParam($_REQUEST, 'modelo_pratica', null);
$modelo_acao=getParam($_REQUEST, 'modelo_acao', null);
$modelo_canvas=getParam($_REQUEST, 'modelo_canvas', null);
$modelo_risco=getParam($_REQUEST, 'modelo_risco', null);
$modelo_risco_resposta=getParam($_REQUEST, 'modelo_risco_resposta', null);
$modelo_indicador=getParam($_REQUEST, 'modelo_indicador', null);
$modelo_calendario=getParam($_REQUEST, 'modelo_calendario', null);
$modelo_monitoramento=getParam($_REQUEST, 'modelo_monitoramento', null);
$modelo_ata=getParam($_REQUEST, 'modelo_ata', null);
$modelo_mswot=getParam($_REQUEST, 'modelo_mswot', null);
$modelo_swot=getParam($_REQUEST, 'modelo_swot', null);
$modelo_operativo=getParam($_REQUEST, 'modelo_operativo', null);
$modelo_instrumento=getParam($_REQUEST, 'modelo_instrumento', null);
$modelo_recurso=getParam($_REQUEST, 'modelo_recurso', null);
$modelo_problema=getParam($_REQUEST, 'modelo_problema', null);
$modelo_demanda=getParam($_REQUEST, 'modelo_demanda', null);
$modelo_programa=getParam($_REQUEST, 'modelo_programa', null);
$modelo_licao=getParam($_REQUEST, 'modelo_licao', null);
$modelo_evento=getParam($_REQUEST, 'modelo_evento', null);
$modelo_link=getParam($_REQUEST, 'modelo_link', null);
$modelo_avaliacao=getParam($_REQUEST, 'modelo_avaliacao', null);
$modelo_tgn=getParam($_REQUEST, 'modelo_tgn', null);
$modelo_brainstorm=getParam($_REQUEST, 'modelo_brainstorm', null);
$modelo_gut=getParam($_REQUEST, 'modelo_gut', null);
$modelo_causa_efeito=getParam($_REQUEST, 'modelo_causa_efeito', null);
$modelo_arquivo=getParam($_REQUEST, 'modelo_arquivo', null);
$modelo_forum=getParam($_REQUEST, 'modelo_forum', null);
$modelo_checklist=getParam($_REQUEST, 'modelo_checklist', null);
$modelo_agenda=getParam($_REQUEST, 'modelo_agenda', null);
$modelo_agrupamento=getParam($_REQUEST, 'modelo_agrupamento', null);
$modelo_patrocinador=getParam($_REQUEST, 'modelo_patrocinador', null);
$modelo_template=getParam($_REQUEST, 'modelo_template', null);
$modelo_painel=getParam($_REQUEST, 'modelo_painel', null);
$modelo_painel_odometro=getParam($_REQUEST, 'modelo_painel_odometro', null);
$modelo_painel_composicao=getParam($_REQUEST, 'modelo_painel_composicao', null);
$modelo_tr=getParam($_REQUEST, 'modelo_tr', null);
$modelo_me=getParam($_REQUEST, 'modelo_me', null);
$modelo_acao_item=getParam($_REQUEST, 'modelo_acao_item', null);
$modelo_beneficio=getParam($_REQUEST, 'modelo_beneficio', null);
$modelo_painel_slideshow=getParam($_REQUEST, 'modelo_painel_slideshow', null);
$modelo_projeto_viabilidade=getParam($_REQUEST, 'modelo_projeto_viabilidade', null);
$modelo_projeto_abertura=getParam($_REQUEST, 'modelo_projeto_abertura', null);
$modelo_plano_gestao=getParam($_REQUEST, 'modelo_plano_gestao', null);
$modelo_ssti=getParam($_REQUEST, 'modelo_ssti', null);
$modelo_laudo=getParam($_REQUEST, 'modelo_laudo', null);
$modelo_trelo=getParam($_REQUEST, 'modelo_trelo', null);
$modelo_trelo_cartao=getParam($_REQUEST, 'modelo_trelo_cartao', null);
$modelo_pdcl=getParam($_REQUEST, 'modelo_pdcl', null);
$modelo_pdcl_item=getParam($_REQUEST, 'modelo_pdcl_item', null);




$tipos=array(''=>'');
// Coloquei a linha de baixo 

//if ($Aplic->checarModulo('projetos', 'editar', null, 'projetos_lista')) $tipos['projeto']=ucfirst($config['projeto']); 
if ($Aplic->checarModulo('projetos', 'editar', null, 'projetos_lista')) $tipos['projeto']=' Projeto';

// Coloquei a linha de baixo 
//if ($Aplic->checarModulo('tarefas', 'editar', null, null)) $tipos['tarefa']= ucfirst($config['tarefa']); 

if ($Aplic->checarModulo('tarefas', 'editar', null, null)) $tipos['tarefa']=' Tarefa';
if ($Aplic->checarModulo('praticas', 'editar', null, 'perspectiva')) $tipos['perspectiva']=ucfirst($config['perspectiva']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'tema')) $tipos['tema']=ucfirst($config['tema']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'objetivo')) $tipos['objetivo']=ucfirst($config['objetivo']); 
if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'editar', null, 'fator')) $tipos['fator']=ucfirst($config['fator']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'iniciativa')) $tipos['estrategia']=ucfirst($config['iniciativa']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'meta')) $tipos['meta']=ucfirst($config['meta']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'plano_acao')) $tipos['acao']=ucfirst($config['acao']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'pratica')) $tipos['pratica']=ucfirst($config['pratica']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'indicador')) $tipos['indicador']='Indicador'; 
if ($Aplic->checarModulo('agenda', 'editar', null, null)) $tipos['calendario']='Agenda';
if ($Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'editar', null, null)) $tipos['instrumento']=ucfirst($config['instrumento']);
if ($Aplic->checarModulo('recursos', 'editar', null, null)) $tipos['recurso']=ucfirst($config['recurso']);
if ($Aplic->checarModulo('projetos', 'editar', null, 'demanda')) $tipos['demanda']='Demanda';
if ($Aplic->checarModulo('projetos', 'editar', null, 'licao')) $tipos['licao']=ucfirst($config['licao']);
if ($Aplic->checarModulo('eventos', 'editar', null, null)) $tipos['evento']='Evento';
if ($Aplic->checarModulo('links', 'editar', null, null)) $tipos['link']='Link';
if ($Aplic->checarModulo('praticas', 'editar', null, 'avaliacao_indicador')) $tipos['avaliacao']='Avaliação';
if ($Aplic->checarModulo('praticas', 'editar', null, 'brainstorm')) $tipos['brainstorm']='Brainstorm';
if ($Aplic->checarModulo('praticas', 'editar', null, 'gut')) $tipos['gut']='Matriz GUT';
if ($Aplic->checarModulo('praticas', 'editar', null, 'causa_efeito')) $tipos['causa_efeito']='Diagrama de causa-efeito';
if ($Aplic->checarModulo('arquivos', 'editar', null,  null)) $tipos['arquivo']='Arquivo';
if ($Aplic->checarModulo('foruns', 'editar', null, null)) $tipos['forum']='Fórum';
if ($Aplic->checarModulo('praticas', 'editar', null, 'checklist')) $tipos['checklist']='Checklist';
if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'editar', null, null)) $tipos['patrocinador']=ucfirst($config['patrocinador']);
if ($Aplic->checarModulo('praticas', 'editar', null, 'plano_acao_item')) $tipos['acao_item']='Item de '.ucfirst($config['acao']);
if ($Aplic->checarModulo('projetos', 'editar', null, 'viabilidade')) $tipos['projeto_viabilidade']='Estudo de viabilidade';
if ($Aplic->checarModulo('projetos', 'editar', null, 'abertura')) $tipos['projeto_abertura']='Termo de abertura';
if ($Aplic->checarModulo('praticas', 'editar', null, 'planejamento')) $tipos['plano_gestao']='Planejamento estratégico';
if ($Aplic->profissional) {
	$tipos['agenda']='Compromisso';
	if ($Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'editar', null, null)) $tipos['operativo']='Plano operativo';
	if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'editar', null, null)) $tipos['ata']='Ata de reunião';	
	if ($Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'editar', null, null)) {
		$tipos['mswot']='Matriz SWOT';
		$tipos['swot']='Campo SWOT';
		}
	if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'editar', null, null)) $tipos['problema']=ucfirst($config['problema']);
	if ($Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'editar', null, null)) $tipos['agrupamento']='Agrupamento';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'canvas')) $tipos['canvas']=ucfirst($config['canvas']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'risco')) $tipos['risco']=ucfirst($config['risco']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'resposta_risco')) $tipos['risco_resposta']=ucfirst($config['risco_resposta']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'monitoramento')) $tipos['monitoramento']='Monitoramento';
	if ($Aplic->checarModulo('projetos', 'editar', null, 'programa')) $tipos['programa']=ucfirst($config['programa']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'tgn')) $tipos['tgn']=ucfirst($config['tgn']);
	if ($Aplic->checarModulo('projetos', 'editar', null, 'modelo')) $tipos['template']='Modelo';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'painel_indicador')) $tipos['painel']='Painel de indicador';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'odometro_indicador')) $tipos['painel_odometro']='Odômetro de indicador';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'composicao_painel')) $tipos['painel_composicao']='Composição de painéis';
	if ($Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'editar', null, null)) $tipos['tr']=ucfirst($config['tr']);
	if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'editar', null, 'me')) $tipos['me']=ucfirst($config['me']);
	if ($Aplic->checarModulo('projetos', 'editar', null, 'beneficio')) $tipos['beneficio']=ucfirst($config['beneficio']).' de '.$config['programa'];
	if ($Aplic->checarModulo('projetos', 'editar', null, 'slideshow_painel')) $tipos['painel_slideshow']='Slideshow de composições';
	if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'editar', null, 'ssti')) $tipos['ssti']=ucfirst($config['ssti']);
	if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'editar', null, 'laudo')) $tipos['laudo']=ucfirst($config['laudo']);
	if ($Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'editar', null, null)) {
		$tipos['trelo']=ucfirst($config['trelo']);
		$tipos['trelo_cartao']=ucfirst($config['trelo_cartao']);
		}
	if ($Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'editar', null, null)) {
		$tipos['pdcl']=ucfirst($config['pdcl']);
		$tipos['pdcl_item']=ucfirst($config['pdcl_item']);
		}
	}	
asort($tipos);


if ($modelo_tarefa) $tipo='tarefa';
elseif ($modelo_projeto) $tipo='projeto';
elseif ($modelo_perspectiva) $tipo='perspectiva';
elseif ($modelo_tema) $tipo='tema';
elseif ($modelo_objetivo) $tipo='objetivo';
elseif ($modelo_fator) $tipo='fator';
elseif ($modelo_estrategia) $tipo='estrategia';
elseif ($modelo_meta) $tipo='meta';
elseif ($modelo_pratica) $tipo='pratica';
elseif ($modelo_acao) $tipo='acao';
elseif ($modelo_canvas) $tipo='canvas';
elseif ($modelo_risco) $tipo='risco';
elseif ($modelo_risco_resposta) $tipo='risco_resposta';
elseif ($modelo_indicador) $tipo='modelo_indicador';
elseif ($modelo_calendario) $tipo='calendario';
elseif ($modelo_monitoramento) $tipo='monitoramento';
elseif ($modelo_ata) $tipo='ata';
elseif ($modelo_mswot) $tipo='mswot';
elseif ($modelo_swot) $tipo='swot';
elseif ($modelo_operativo) $tipo='operativo';
elseif ($modelo_instrumento) $tipo='instrumento';
elseif ($modelo_recurso) $tipo='recurso';
elseif ($modelo_problema) $tipo='problema';
elseif ($modelo_demanda) $tipo='demanda';
elseif ($modelo_programa) $tipo='programa';
elseif ($modelo_licao) $tipo='licao';
elseif ($modelo_evento) $tipo='evento';
elseif ($modelo_link) $tipo='link';
elseif ($modelo_avaliacao) $tipo='avaliacao';
elseif ($modelo_tgn) $tipo='tgn';
elseif ($modelo_brainstorm) $tipo='brainstorm';
elseif ($modelo_gut) $tipo='gut';
elseif ($modelo_causa_efeito) $tipo='causa_efeito';
elseif ($modelo_arquivo) $tipo='arquivo';
elseif ($modelo_forum) $tipo='forum';
elseif ($modelo_checklist) $tipo='checklist';
elseif ($modelo_agenda) $tipo='agenda';
elseif ($modelo_agrupamento) $tipo='agrupamento';
elseif ($modelo_patrocinador) $tipo='patrocinador';
elseif ($modelo_template) $tipo='template';
elseif ($modelo_painel) $tipo='painel';
elseif ($modelo_painel_odometro) $tipo='painel_odometro';
elseif ($modelo_painel_composicao) $tipo='painel_composicao';
elseif ($modelo_tr) $tipo='tr';
elseif ($modelo_me) $tipo='me';
elseif ($modelo_acao_item) $tipo='acao_item';
elseif ($modelo_beneficio) $tipo='beneficio';
elseif ($modelo_painel_slideshow) $tipo='painel_slideshow';
elseif ($modelo_projeto_viabilidade) $tipo='projeto_viabilidade';
elseif ($modelo_projeto_abertura) $tipo='projeto_abertura';
elseif ($modelo_plano_gestao) $tipo='plano_gestao';
elseif ($modelo_ssti) $tipo='ssti';
elseif ($modelo_laudo) $tipo='laudo';
elseif ($modelo_trelo) $tipo='trelo';
elseif ($modelo_trelo_cartao) $tipo='trelo_cartao';
elseif ($modelo_pdcl) $tipo='pdcl';
elseif ($modelo_pdcl_item) $tipo='pdcl_item';
else $tipo='';



//echo '<table align="left" cellspacing=0 cellpadding=0 style="width:100%;">';


echo '<tr><td align="right" style="white-space: nowrap" width=70>'.dica('Relacionado','A qual parte do sistema o documento está relacionado.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:400px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';


echo '<tr '.($modelo_projeto ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso seja específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_projeto" value="'.$modelo_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($modelo_projeto).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';

echo '<tr '.($modelo_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_tarefa" value="'.$modelo_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($modelo_tarefa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';

echo '<tr '.($modelo_projeto_abertura ? '' : 'style="display:none"').' id="projeto_abertura" ><td align="right" style="white-space: nowrap">'.dica('Termo de Abertura', 'Caso seja específico de um termo de abertura, neste campo deverá constar o nome do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_projeto_abertura" value="'.$modelo_projeto_abertura.'" /><input type="text" id="projeto_abertura_nome" name="projeto_abertura_nome" value="'.nome_termo_abertura($modelo_projeto_abertura).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAbertura();">'.imagem('icones/anexo_projeto_p.png','Selecionar Termo de Abertura','Clique neste ícone '.imagem('icones/anexo_projeto_p.png').' para selecionar um termo de abertura.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso seja específico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_perspectiva" value="'.$modelo_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($modelo_perspectiva).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso seja específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_tema" value="'.$modelo_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($modelo_tema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" style="white-space: nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso seja específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_objetivo" value="'.$modelo_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($modelo_objetivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso seja específico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_fator" value="'.$modelo_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($modelo_fator).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso seja específico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_estrategia" value="'.$modelo_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($modelo_estrategia).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['meta']), 'Caso seja específico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_meta" value="'.$modelo_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($modelo_meta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso seja específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_pratica" value="'.$modelo_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($modelo_pratica).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso seja específico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_acao" value="'.$modelo_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($modelo_acao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso seja específico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_canvas" value="'.$modelo_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($modelo_canvas).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso seja específico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_risco" value="'.$modelo_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($modelo_risco).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso seja específico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_risco_resposta" value="'.$modelo_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($modelo_risco_resposta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" style="white-space: nowrap">'.dica('Indicador', 'Caso seja específico de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_indicador" value="'.$modelo_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($modelo_indicador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" style="white-space: nowrap">'.dica('Agenda', 'Caso seja específico de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_calendario" value="'.$modelo_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($modelo_calendario).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/agenda_p.png','Selecionar Agenda','Clique neste ícone '.imagem('icones/agenda_p.png').' para selecionar uma agenda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" style="white-space: nowrap">'.dica('Monitoramento', 'Caso seja específico de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_monitoramento" value="'.$modelo_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($modelo_monitoramento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" style="white-space: nowrap">'.dica('Ata de Reunião', 'Caso seja específico de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_ata" value="'.(isset($modelo_ata) ? $modelo_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($modelo_ata) ? $modelo_ata : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('icones/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('icones/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_mswot ? '' : 'style="display:none"').' id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT', 'Caso seja específico de uma matriz SWOT neste campo deverá constar o nome da matriz SWOT').'Matriz SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_mswot" value="'.(isset($modelo_mswot) ? $modelo_mswot : '').'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot((isset($modelo_mswot) ? $modelo_mswot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste ícone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" style="white-space: nowrap">'.dica('Campo SWOT', 'Caso seja específico de um campo de matriz SWOT neste campo deverá constar o nome do campo de matriz SWOT').'Campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_swot" value="'.(isset($modelo_swot) ? $modelo_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($modelo_swot) ? $modelo_swot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('icones/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('icones/swot_p.png').' para selecionar um campo de matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso seja específico de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_operativo" value="'.$modelo_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($modelo_operativo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['instrumento']), 'Caso seja específico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo deverá constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_instrumento" value="'.$modelo_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($modelo_instrumento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['recurso']), 'Caso seja específico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_recurso" value="'.$modelo_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($modelo_recurso).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['problema']), 'Caso seja específico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_problema" value="'.$modelo_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($modelo_problema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" style="white-space: nowrap">'.dica('Demanda', 'Caso seja específico de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_demanda" value="'.$modelo_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($modelo_demanda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar Demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar uma demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']), 'Caso seja específico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_programa" value="'.$modelo_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($modelo_programa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['licao']), 'Caso seja específico de '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].', neste campo deverá constar o nome d'.$config['genero_licao'].' '.$config['licao'].'.').ucfirst($config['licao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_licao" value="'.$modelo_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($modelo_licao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar '.ucfirst($config['licao']),'Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" style="white-space: nowrap">'.dica('Evento', 'Caso seja específico de um evento, neste campo deverá constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_evento" value="'.$modelo_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($modelo_evento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_link ? '' : 'style="display:none"').' id="link" ><td align="right" style="white-space: nowrap">'.dica('link', 'Caso seja específico de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_link" value="'.$modelo_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($modelo_link).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" style="white-space: nowrap">'.dica('Avaliação', 'Caso seja específico de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_avaliacao" value="'.$modelo_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($modelo_avaliacao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tgn']), 'Caso seja específico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_tgn" value="'.$modelo_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($modelo_tgn).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" style="white-space: nowrap">'.dica('Brainstorm', 'Caso seja específico de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_brainstorm" value="'.$modelo_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($modelo_brainstorm).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" style="white-space: nowrap">'.dica('Matriz GUT', 'Caso seja específico de uma matriz GUT, neste campo deverá constar o nome da matriz GUT.').'Matriz GUT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_gut" value="'.$modelo_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($modelo_gut).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz GUT','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" style="white-space: nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso seja específico de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_causa_efeito" value="'.$modelo_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($modelo_causa_efeito).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Caso seja específico de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_arquivo" value="'.$modelo_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($modelo_arquivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" style="white-space: nowrap">'.dica('Fórum', 'Caso seja específico de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_forum" value="'.$modelo_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($modelo_forum).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" style="white-space: nowrap">'.dica('Checklist', 'Caso seja específico de um checklist, neste campo deverá constar o nome do checklist.').'checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_checklist" value="'.$modelo_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($modelo_checklist).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" style="white-space: nowrap">'.dica('Compromisso', 'Caso seja específico de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_agenda" value="'.$modelo_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($modelo_agenda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/compromisso_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" style="white-space: nowrap">'.dica('Agrupamento', 'Caso seja específico de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_agrupamento" value="'.$modelo_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($modelo_agrupamento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('icones/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('icones/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['patrocinador']), 'Caso seja específico de um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].', neste campo deverá constar o nome d'.$config['genero_patrocinador'].' '.$config['patrocinador'].'.').ucfirst($config['patrocinador']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_patrocinador" value="'.$modelo_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($modelo_patrocinador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif','Selecionar '.$config['patrocinador'],'Clique neste ícone '.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').' para selecionar um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_template ? '' : 'style="display:none"').' id="template" ><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Caso seja específico de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_template" value="'.$modelo_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($modelo_template).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" style="white-space: nowrap">'.dica('Painel de Indicador', 'Caso seja específico de um painel de indicador, neste campo deverá constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_painel" value="'.$modelo_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($modelo_painel).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" style="white-space: nowrap">'.dica('Odômetro de Indicador', 'Caso seja específico de um odômetro de indicador, neste campo deverá constar o nome do odômetro.').'Odômetro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_painel_odometro" value="'.$modelo_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($modelo_painel_odometro).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Odômetro','Clique neste ícone '.imagem('icones/odometro_p.png').' para selecionar um odômtro.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" style="white-space: nowrap">'.dica('Composição de Painéis', 'Caso seja específico de uma composição de painéis, neste campo deverá constar o nome da composição.').'Composição de Painéis:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_painel_composicao" value="'.$modelo_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($modelo_painel_composicao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/composicao_p.gif','Selecionar Composição de Painéis','Clique neste ícone '.imagem('icones/composicao_p.gif').' para selecionar uma composição de painéis.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tr']), 'Caso seja específico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo deverá constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_tr" value="'.$modelo_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($modelo_tr).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_me ? '' : 'style="display:none"').' id="me" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['me']), 'Caso seja específico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo deverá constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_me" value="'.$modelo_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($modelo_me).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_acao_item ? '' : 'style="display:none"').' id="acao_item" ><td align="right" style="white-space: nowrap">'.dica('Item de '.ucfirst($config['acao']), 'Caso seja específico de um item de '.$config['acao'].', neste campo deverá constar o nome do item de '.$config['acao'].'.').'Item de '.$config['acao'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_acao_item" value="'.$modelo_acao_item.'" /><input type="text" id="acao_item_nome" name="acao_item_nome" value="'.nome_acao_item($modelo_acao_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcaoItem();">'.imagem('icones/acao_item_p.png','Selecionar Item de '.ucfirst($config['acao']),'Clique neste ícone '.imagem('icones/acao_item_p.png').' para selecionar um item de '.$config['acao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_beneficio ? '' : 'style="display:none"').' id="beneficio" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['beneficio']).' de '.ucfirst($config['programa']), 'Caso seja específico de '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_beneficio'].' '.$config['beneficio'].' de '.$config['programa'].'.').ucfirst($config['beneficio']).' de '.$config['programa'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_beneficio" value="'.$modelo_beneficio.'" /><input type="text" id="beneficio_nome" name="beneficio_nome" value="'.nome_beneficio($modelo_beneficio).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBeneficio();">'.imagem('icones/beneficio_p.png','Selecionar '.ucfirst($config['beneficio']).' de '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/beneficio_p.png').' para selecionar '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_painel_slideshow ? '' : 'style="display:none"').' id="painel_slideshow" ><td align="right" style="white-space: nowrap">'.dica('Slideshow de Composições', 'Caso seja específico de um slideshow de composições, neste campo deverá constar o nome do slideshow de composições.').'Slideshow de composições:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_painel_slideshow" value="'.$modelo_painel_slideshow.'" /><input type="text" id="painel_slideshow_nome" name="painel_slideshow_nome" value="'.nome_painel_slideshow($modelo_painel_slideshow).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSlideshow();">'.imagem('icones/slideshow_p.gif','Selecionar Slideshow de Composições','Clique neste ícone '.imagem('icones/slideshow_p.gif').' para selecionar um slideshow de composições.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_projeto_viabilidade ? '' : 'style="display:none"').' id="projeto_viabilidade" ><td align="right" style="white-space: nowrap">'.dica('Estudo de Viabilidade', 'Caso seja específico de um estudo de viabilidade, neste campo deverá constar o nome do estudo de viabilidade.').'Estudo de viabilidade:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_projeto_viabilidade" value="'.$modelo_projeto_viabilidade.'" /><input type="text" id="projeto_viabilidade_nome" name="projeto_viabilidade_nome" value="'.nome_viabilidade($modelo_projeto_viabilidade).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popViabilidade();">'.imagem('icones/viabilidade_p.gif','Selecionar Estudo de Viabilidade','Clique neste ícone '.imagem('icones/viabilidade_p.gif').' para selecionar um estudo de viabilidade.').'</a></td></tr></table></td></tr>';

echo '<tr '.($modelo_plano_gestao ? '' : 'style="display:none"').' id="plano_gestao" ><td align="right" style="white-space: nowrap">'.dica('Planejamento Estratégico', 'Caso seja específico de um planejamento estratégico, neste campo deverá constar o nome do planejamento estratégico.').'Planejamento estratégico:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_plano_gestao" value="'.$modelo_plano_gestao.'" /><input type="text" id="plano_gestao_nome" name="plano_gestao_nome" value="'.nome_plano_gestao($modelo_plano_gestao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPlanejamento();">'.imagem('icones/planogestao_p.png','Selecionar Planejamento Estratégico','Clique neste ícone '.imagem('icones/planogestao_p.png').' para selecionar um planejamento estratégico.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_ssti ? '' : 'style="display:none"').' id="ssti" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['ssti']), 'Caso seja específico de '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].', neste campo deverá constar o nome d'.$config['genero_ssti'].' '.$config['ssti'].'.').ucfirst($config['ssti']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_ssti" value="'.$modelo_ssti.'" /><input type="text" id="ssti_nome" name="ssti_nome" value="'.nome_ssti($modelo_ssti).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSSTI();">'.imagem('icones/ssti_p.png','Selecionar '.ucfirst($config['ssti']),'Clique neste ícone '.imagem('icones/ssti_p.png').' para selecionar '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_laudo ? '' : 'style="display:none"').' id="laudo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['laudo']), 'Caso seja específico de '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].', neste campo deverá constar o nome d'.$config['genero_laudo'].' '.$config['laudo'].'.').ucfirst($config['laudo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_laudo" value="'.$modelo_laudo.'" /><input type="text" id="laudo_nome" name="laudo_nome" value="'.nome_laudo($modelo_laudo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLaudo();">'.imagem('icones/laudo_p.png','Selecionar '.ucfirst($config['laudo']),'Clique neste ícone '.imagem('icones/laudo_p.png').' para selecionar '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_trelo ? '' : 'style="display:none"').' id="trelo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo']), 'Caso seja específico de '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].', neste campo deverá constar o nome d'.$config['genero_trelo'].' '.$config['trelo'].'.').ucfirst($config['trelo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_trelo" value="'.$modelo_trelo.'" /><input type="text" id="trelo_nome" name="trelo_nome" value="'.nome_trelo($modelo_trelo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTrelo();">'.imagem('icones/trelo_p.png','Selecionar '.ucfirst($config['trelo']),'Clique neste ícone '.imagem('icones/trelo_p.png').' para selecionar '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_trelo_cartao ? '' : 'style="display:none"').' id="trelo_cartao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo_cartao']), 'Caso seja específico de '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].', neste campo deverá constar o nome d'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].'.').ucfirst($config['trelo_cartao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_trelo_cartao" value="'.$modelo_trelo_cartao.'" /><input type="text" id="trelo_cartao_nome" name="trelo_cartao_nome" value="'.nome_trelo_cartao($modelo_trelo_cartao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTreloCartao();">'.imagem('icones/trelo_cartao_p.png','Selecionar '.ucfirst($config['trelo_cartao']),'Clique neste ícone '.imagem('icones/trelo_cartao_p.png').' para selecionar '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_pdcl ? '' : 'style="display:none"').' id="pdcl" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl']), 'Caso seja específico de '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].', neste campo deverá constar o nome d'.$config['genero_pdcl'].' '.$config['pdcl'].'.').ucfirst($config['pdcl']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_pdcl" value="'.$modelo_pdcl.'" /><input type="text" id="pdcl_nome" name="pdcl_nome" value="'.nome_pdcl($modelo_pdcl).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCL();">'.imagem('icones/pdcl_p.png','Selecionar '.ucfirst($config['pdcl']),'Clique neste ícone '.imagem('icones/pdcl_p.png').' para selecionar '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($modelo_pdcl_item ? '' : 'style="display:none"').' id="pdcl_item" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl_item']), 'Caso seja específico de '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].', neste campo deverá constar o nome d'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].'.').ucfirst($config['pdcl_item']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="modelo_pdcl_item" value="'.$modelo_pdcl_item.'" /><input type="text" id="pdcl_item_nome" name="pdcl_item_nome" value="'.nome_pdcl_item($modelo_pdcl_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCLItem();">'.imagem('icones/pdcl_item_p.png','Selecionar '.ucfirst($config['pdcl_item']),'Clique neste ícone '.imagem('icones/pdcl_item_p.png').' para selecionar '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].'.').'</a></td></tr></table></td></tr>';




$sql->adTabela('modelo_gestao');
$sql->adCampo('modelo_gestao.*');
if (isset($uuid) && $uuid) $sql->adOnde('modelo_gestao_uuid = \''.$uuid.'\'');
else $sql->adOnde('modelo_gestao_modelo ='.(int)$modelo_id);	
$sql->adOrdem('modelo_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
echo '<tr><td></td><td><div id="combo_gestao">';
if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
foreach($lista as $gestao_data){
	echo '<tr align="center">';
	echo '<td style="white-space: nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['modelo_gestao_ordem'].', '.$gestao_data['modelo_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['modelo_gestao_ordem'].', '.$gestao_data['modelo_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['modelo_gestao_ordem'].', '.$gestao_data['modelo_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['modelo_gestao_ordem'].', '.$gestao_data['modelo_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	if ($gestao_data['modelo_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['modelo_gestao_tarefa']).'</td>';
	
	
	elseif ($gestao_data['modelo_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['modelo_gestao_projeto']).'</td>';
	
	elseif ($gestao_data['modelo_gestao_projeto_abertura']) echo '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['modelo_gestao_projeto_abertura']).'</td>';	
	elseif ($gestao_data['modelo_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['modelo_gestao_perspectiva']).'</td>';
	elseif ($gestao_data['modelo_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['modelo_gestao_tema']).'</td>';
	elseif ($gestao_data['modelo_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['modelo_gestao_objetivo']).'</td>';
	elseif ($gestao_data['modelo_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['modelo_gestao_fator']).'</td>';
	elseif ($gestao_data['modelo_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['modelo_gestao_estrategia']).'</td>';
	elseif ($gestao_data['modelo_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['modelo_gestao_meta']).'</td>';
	elseif ($gestao_data['modelo_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['modelo_gestao_pratica']).'</td>';
	elseif ($gestao_data['modelo_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['modelo_gestao_acao']).'</td>';
	elseif ($gestao_data['modelo_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['modelo_gestao_canvas']).'</td>';
	elseif ($gestao_data['modelo_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['modelo_gestao_risco']).'</td>';
	elseif ($gestao_data['modelo_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['modelo_gestao_risco_resposta']).'</td>';
	elseif ($gestao_data['modelo_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['modelo_gestao_indicador']).'</td>';
	elseif ($gestao_data['modelo_gestao_calendario']) echo '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['modelo_gestao_calendario']).'</td>';
	elseif ($gestao_data['modelo_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['modelo_gestao_monitoramento']).'</td>';
	elseif ($gestao_data['modelo_gestao_ata']) echo '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['modelo_gestao_ata']).'</td>';
	elseif ($gestao_data['modelo_gestao_mswot']) echo '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['modelo_gestao_mswot']).'</td>';
	elseif ($gestao_data['modelo_gestao_swot']) echo '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['modelo_gestao_swot']).'</td>';
	elseif ($gestao_data['modelo_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['modelo_gestao_operativo']).'</td>';
	elseif ($gestao_data['modelo_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['modelo_gestao_instrumento']).'</td>';
	elseif ($gestao_data['modelo_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['modelo_gestao_recurso']).'</td>';
	elseif ($gestao_data['modelo_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['modelo_gestao_problema']).'</td>';
	elseif ($gestao_data['modelo_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['modelo_gestao_demanda']).'</td>';
	elseif ($gestao_data['modelo_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['modelo_gestao_programa']).'</td>';
	elseif ($gestao_data['modelo_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['modelo_gestao_licao']).'</td>';
	elseif ($gestao_data['modelo_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['modelo_gestao_evento']).'</td>';
	elseif ($gestao_data['modelo_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['modelo_gestao_link']).'</td>';
	elseif ($gestao_data['modelo_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['modelo_gestao_avaliacao']).'</td>';
	elseif ($gestao_data['modelo_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['modelo_gestao_tgn']).'</td>';
	elseif ($gestao_data['modelo_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['modelo_gestao_brainstorm']).'</td>';
	elseif ($gestao_data['modelo_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['modelo_gestao_gut']).'</td>';
	elseif ($gestao_data['modelo_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['modelo_gestao_causa_efeito']).'</td>';
	elseif ($gestao_data['modelo_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['modelo_gestao_arquivo']).'</td>';
	elseif ($gestao_data['modelo_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['modelo_gestao_forum']).'</td>';
	elseif ($gestao_data['modelo_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['modelo_gestao_checklist']).'</td>';
	elseif ($gestao_data['modelo_gestao_agenda']) echo '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['modelo_gestao_agenda']).'</td>';
	elseif ($gestao_data['modelo_gestao_agrupamento']) echo '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['modelo_gestao_agrupamento']).'</td>';
	elseif ($gestao_data['modelo_gestao_patrocinador']) echo '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['modelo_gestao_patrocinador']).'</td>';
	elseif ($gestao_data['modelo_gestao_template']) echo '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['modelo_gestao_template']).'</td>';
	elseif ($gestao_data['modelo_gestao_painel']) echo '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['modelo_gestao_painel']).'</td>';
	elseif ($gestao_data['modelo_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['modelo_gestao_painel_odometro']).'</td>';
	elseif ($gestao_data['modelo_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['modelo_gestao_painel_composicao']).'</td>';		
	elseif ($gestao_data['modelo_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['modelo_gestao_tr']).'</td>';	
	elseif ($gestao_data['modelo_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['modelo_gestao_me']).'</td>';	
	elseif ($gestao_data['modelo_gestao_acao_item']) echo '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['modelo_gestao_acao_item']).'</td>';	
	elseif ($gestao_data['modelo_gestao_beneficio']) echo '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['modelo_gestao_beneficio']).'</td>';	
	elseif ($gestao_data['modelo_gestao_painel_slideshow']) echo '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['modelo_gestao_painel_slideshow']).'</td>';	
	elseif ($gestao_data['modelo_gestao_projeto_viabilidade']) echo '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['modelo_gestao_projeto_viabilidade']).'</td>';	
	
	elseif ($gestao_data['modelo_gestao_plano_gestao']) echo '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['modelo_gestao_plano_gestao']).'</td>';	
	elseif ($gestao_data['modelo_gestao_ssti']) echo '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['modelo_gestao_ssti']).'</td>';
	elseif ($gestao_data['modelo_gestao_laudo']) echo '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['modelo_gestao_laudo']).'</td>';
	elseif ($gestao_data['modelo_gestao_trelo']) echo '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['modelo_gestao_trelo']).'</td>';
	elseif ($gestao_data['modelo_gestao_trelo_cartao']) echo '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['modelo_gestao_trelo_cartao']).'</td>';
	elseif ($gestao_data['modelo_gestao_pdcl']) echo '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['modelo_gestao_pdcl']).'</td>';
	elseif ($gestao_data['modelo_gestao_pdcl_item']) echo '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['modelo_gestao_pdcl_item']).'</td>';
	
	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['modelo_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
	}
if (count($lista)) echo '</table>';
echo '</div></td></tr>';

	



if ($Aplic->profissional) include_once BASE_DIR.'/modulos/email/modelo_editar_pro.php';



echo '<tr><td colspan=20><table border=1 align="center" cellspacing=0 cellpadding=0 style="background-color:#FFFFFF;"><tr><td>';

for ($i=1; $i <= $modelo->quantidade(); $i++){
	$campo='campo_'.$i;
	$tpl->$campo = $modelo->get_campo($i);
	}
echo $tpl->exibir($modelo->edicao);
echo '</td></tr></table>';

echo '</td></tr>';












if($Aplic->profissional){
	//assinaturas
	echo '<tr><td style="height:1px;"></td></tr>';
	$sql->adTabela('assinatura_atesta');
	$sql->adCampo('assinatura_atesta_id, assinatura_atesta_nome');
	$sql->adOnde('assinatura_atesta_modelo=1');
	$sql->adOrdem('assinatura_atesta_ordem');
	$atesta_vetor = array(null=>'')+$sql->listaVetorChave('assinatura_atesta_id', 'assinatura_atesta_nome');
	$sql->limpar();
	$aprova_vetor= array(-1=>'Não', 1=>'Sim');
	echo '<input type="hidden" name="assinatura_id" id="assinatura_id" value="" />';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_assinaturas\').style.display) document.getElementById(\'apresentar_assinaturas\').style.display=\'\'; else document.getElementById(\'apresentar_assinaturas\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Assinam</b></a></td></tr>';
	echo '<tr id="apresentar_assinaturas" style="display:'.(!$dialogo ? 'none' : '').'"><td colspan=20><table cellspacing=0 cellpadding=0>';
	echo '<tr><td><table cellspacing=0 cellpadding=0>';
	echo '<tr><td><fieldset><legend class=texto style="color: black;">'.dica(ucfirst($config['usuario']),ucfirst($config['usuario']).' que irá assinar.').'&nbsp;<b>'.ucfirst($config['usuario']).'</b>&nbsp'.dicaF().'</legend><table cellspacing=0 cellpadding=0>';
	echo '<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Nome d'.$config['genero_usuario'].' '.$config['usuario'].' que irá assinar.').ucfirst($config['usuario']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="integrante_id" name="integrante_id" value="" /><input type="text" id="nome_assinatura" name="nome_assinatura" value="" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAssinatura2();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar um '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr><td align=right>'.dica('Função', 'Função d'.$config['genero_usuario'].' '.$config['usuario'].' que irá assinar.').'Função:'.dicaF().'</td><td><input type="text" id="assinatura_funcao" name="assinatura_funcao" value="" style="width:400px;" class="texto" /></td></tr>';
	echo '<tr><td align=right>'.dica('Tipo de Parecer', 'Tipo de parecer que '.$config['genero_usuario'].' '.$config['usuario'].' dará ao assinar.').'Tipo de parecer:'.dicaF().'</td><td style="width:400px;">'.selecionaVetor($atesta_vetor, 'assinatura_atesta', 'style="width:400px;" class="texto"').'</td></tr>';
	echo '<tr><td align=right>'.dica('Aprova', 'Informe se '.$config['genero_usuario'].' '.$config['usuario'].' necessita dar um parecer favorável para aprovação.').'Aprova:'.dicaF().'</td><td style="width:400px;">'.selecionaVetor($aprova_vetor, 'assinatura_aprova', 'style="width:400px;" class="texto"', -1).'</td></tr>';
	echo '</table></fieldset></td>';
	echo '<td id="adicionar_assinatura" style="display:"><a href="javascript: void(0);" onclick="incluir_assinatura();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir um '.$config['usuario'].'.').'</a></td>';
	echo '<td id="confirmar_assinatura" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'integrante_id\').value=0; document.getElementById(\'assinatura_funcao\').value=\'\';	document.getElementById(\'nome_assinatura\').value=\'\'; document.getElementById(\'adicionar_assinatura\').style.display=\'\';	document.getElementById(\'confirmar_assinatura\').style.display=\'none\';">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição.').'</a><a href="javascript: void(0);" onclick="incluir_assinatura();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição.').'</a></td>';
	echo '</tr>';
	echo '</table></td></tr>';
	if ($modelo_id) {
		$sql->adTabela('assinatura');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_id = assinatura_usuario');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->adOnde('assinatura_modelo = '.(int)$modelo_id);
		$sql->adCampo('assinatura_id, assinatura_funcao, assinatura_atesta, assinatura_aprova, assinatura_usuario, assinatura_ordem, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_contato');
		$sql->adOrdem('assinatura_ordem');
		$assinaturas=$sql->Lista();
		$sql->limpar();
		}
	else $assinaturas=null;
	echo '<tr><td colspan=20 align=left><div id="assinaturas">';
	if (is_array($assinaturas) && count($assinaturas)) {
		echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>'.dica(ucfirst($config['usuario']), 'Nome d'.$config['genero_usuario'].' '.$config['usuario'].' que assina.').ucfirst($config['usuario']).dicaF().'</th><th>'.dica('Função', 'Função d'.$config['genero_usuario'].' '.$config['usuario'].' que assina.').'Função'.dicaF().'</th><th>'.dica('Tipo de Parecer', 'Tipo de parecer d'.$config['genero_usuario'].' '.$config['usuario'].' que assina.').'Tipo de Parecer'.dicaF().'</th><th>'.dica('Aprova', 'Caso o parecer d'.$config['genero_usuario'].' '.$config['usuario'].' que assina é necessário para a aprovação.').'Aprova'.dicaF().'</th><th></th></tr>';
		foreach ($assinaturas as $assinatura) {
			echo '<tr align="center">';
			echo '<td style="white-space: nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_assinatura('.$assinatura['assinatura_ordem'].', '.$assinatura['assinatura_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_assinatura('.$assinatura['assinatura_ordem'].', '.$assinatura['assinatura_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_assinatura('.$assinatura['assinatura_ordem'].', '.$assinatura['assinatura_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_assinatura('.$assinatura['assinatura_ordem'].', '.$assinatura['assinatura_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			echo '<td align="left" style="white-space: nowrap">'.$assinatura['nome_contato'].'</td>';
			echo '<td align="left">'.$assinatura['assinatura_funcao'].'</td>';
			echo '<td align="left">'.(isset($atesta_vetor[$assinatura['assinatura_atesta']]) ? $atesta_vetor[$assinatura['assinatura_atesta']] : '&nbsp;').'</td>';
			echo '<td align="center">'.($assinatura['assinatura_aprova'] > 0 ? 'Sim' : 'Não').'</td>';
			echo '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_assinatura('.$assinatura['assinatura_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_usuario'].' '.$config['usuario'].'.').'</a>';
			echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_assinatura('.$assinatura['assinatura_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir '.$config['genero_usuario'].' '.$config['usuario'].'.').'</a></td>';
			echo '</tr>';
			}
		echo '</table>';
		}
	echo '</div></td></tr>';
	echo '</table></td></tr>';
	}








echo '</table>';
if (!$dialogo) echo estiloFundoCaixa();


echo '<input type=hidden name="modelo_cia" id="modelo_cia" value="'.$Aplic->usuario_cia.'">';

echo '</form>';

?>
<script type="text/javascript">

function popDept() {
  if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&cia_id=<?php echo $Aplic->usuario_cia ?>', window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&cia_id=<?php echo $Aplic->usuario_cia ?>','dept','left=0,top=0,height=600,width=400, scrollbars=yes, resizable');
	}


function setDept(cia, chave, val) {
	document.getElementById('dept_protocolo').value=chave;
	xajax_protocolo_dept_ajax(chave);
	}

function popDocumentos_referencia2() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 800, 500, 'm=email&a=modelo_pesquisar&dialogo=1&anexar_documento=1&campo=10', window.anexar_documento_referencia, window);
	else window.open('./index.php?m=email&a=modelo_pesquisar&dialogo=1&anexar_documento=1&campo=10', '','height=600, width=1010, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}
function popDocumentos_referencia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 800, 500, 'm=email&a=modelo_pesquisar&dialogo=1&referenciar_documento=1', window.anexar_documento_referencia, window);
	else window.open('./index.php?m=email&a=modelo_pesquisar&dialogo=1&referenciar_documento=1', '','height=600, width=1010, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}
function popMensagem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 800, 500, 'm=email&a=mensagem_pesquisar&dialogo=1&referenciar_mensagem=1', window.anexar_mensagem_referencia, window);
	else window.open('./index.php?m=email&a=mensagem_pesquisar&dialogo=1&referenciar_mensagem=1', '','height=600, width=1010, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}


function remover_referencia(){
	for(var i=0; i < document.getElementById('lista_doc_referencia').options.length; i++) {
		if (document.getElementById('lista_doc_referencia').options[i].selected && document.getElementById('lista_doc_referencia').options[i].value) {
			document.getElementById('lista_doc_referencia').options[i].value = "";
			document.getElementById('lista_doc_referencia').options[i].text = "";
			}
		}
	limpaVazios(document.getElementById('lista_doc_referencia'), document.getElementById('lista_doc_referencia').options.length);
	if (!document.getElementById('lista_doc_referencia').options.length) document.getElementById('documentos_referencia').style.display = 'none';
	}


function remover_msg(){
	for(var i=0; i < document.getElementById('lista_msg_referencia').options.length; i++) {
		if (document.getElementById('lista_msg_referencia').options[i].selected && document.getElementById('lista_msg_referencia').options[i].value) {
			document.getElementById('lista_msg_referencia').options[i].value = "";
			document.getElementById('lista_msg_referencia').options[i].text = "";
			}
		}
	limpaVazios(document.getElementById('lista_msg_referencia'), document.getElementById('lista_msg_referencia').options.length);
	if (!document.getElementById('lista_msg_referencia').options.length) document.getElementById('mensagens_referencia').style.display = 'none';
	}

// Limpa Vazios
function limpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		limpaVazios(box, box_len);
		}
	}

function anexar_mensagem_referencia(msg_id, texto){
	document.getElementById('mensagens_referencia').style.display = '';
	var aviso=0;
	for(var k=0; k < document.getElementById('lista_msg_referencia').options.length; k++){
		if (document.getElementById('lista_msg_referencia').options[k].value == msg_id) {
			aviso=1;
			break;
			}
		}
	if (aviso) alert("Est<?php echo ($config['genero_mensagem']=='a' ? 'a': 'e').' '.$config['mensagem']?> já havia sido referenciad<?php echo $config['genero_mensagem']?>");
	else {
		var item = new Option();
		item.value = msg_id;
		item.text = texto;
		document.getElementById('lista_msg_referencia').options[document.getElementById('lista_msg_referencia').options.length] = item;
		}
	}


function anexar_documento_referencia(modelo_id, texto){
	document.getElementById('documentos_referencia').style.display = '';
	var aviso=0;
	for(var k=0; k < document.getElementById('lista_doc_referencia').options.length; k++){
		if (document.getElementById('lista_doc_referencia').options[k].value == modelo_id) {
			aviso=1;
			break;
			}
		}
	if (aviso) alert('Este documento já havia sido referenciado');
	else {
		var item = new Option();
		item.value = modelo_id;
		item.text = texto;
		document.getElementById('lista_doc_referencia').options[document.getElementById('lista_doc_referencia').options.length] = item;
		}
	}





function salvar_doc(){
	if (env.assunto.value.length > 0) {
		var contatos_id_selecionados='';
		env.salvar.value=1;
		var total='';
		var vet = new Array();
    vet = document.getElementsByName('campos_destinatario');
    for(var i = 0; i < vet.length; i++){
	     	var obj = document.getElementsByName('campos_destinatario').item(i);
	     	campo=obj.value;
	     	contatos_id_selecionados='';
	     	var arr = new Array();
		    arr = document.getElementsByName('nome_dest_'+campo);
		    for(var i = 0; i < arr.length; i++){
		       var obj = document.getElementsByName('nome_dest_'+campo).item(i);
		       contatos_id_selecionados+=obj.value+'#';
		       }
		    funcoes_id_selecionados='';
		    arr = document.getElementsByName('funcao_'+campo);
		    for(var i = 0; i < arr.length; i++){
		       var obj = document.getElementsByName('funcao_'+campo).item(i);
		       funcoes_id_selecionados+=obj.value+'#';
		       }

			   document.getElementById('funcao_destinatarios_'+campo).value=funcoes_id_selecionados;
	       document.getElementById('lista_destinatarios_'+campo).value=contatos_id_selecionados;
	     	}
			//anexos
			var vet2 = new Array();
	    vet2 = document.getElementsByName('campos_anexos');
	    if (vet2.length){
		    for(var i = 0; i < vet.length; i++){
			     	var obj2 = document.getElementsByName('campos_anexos').item(i);
			     	campo=obj2.value;
						var arr2 = new Array();
			      var vetor_anexo= new Array();
					  arr2 = document.getElementsByName('anexo_'+campo);
					  for(var i = 0; i < arr2.length; i++){
				       var obj = document.getElementsByName('anexo_'+campo).item(i);
				       vetor_anexo[i]=obj.value;
				       }
			     	document.getElementById('campo_'+campo).value=vetor_anexo;


						var arr3 = new Array();
			      var vetor_anexo_nomes= '';
					  arr3 = document.getElementsByName('nome_fantasia_'+campo);
					  for(var i = 0; i < arr3.length; i++){
				       var obj = document.getElementsByName('nome_fantasia_'+campo).item(i);
				       vetor_anexo_nomes=vetor_anexo_nomes+( i>0 ? '#*' : '')+obj.value;
				       }
			     	document.getElementById('campo_modelos_nomes_'+campo).value=vetor_anexo_nomes;
						}
				}


			for (var i=0; i < document.getElementById('lista_doc_referencia').length ; i++) {
				document.getElementById('lista_doc_referencia').options[i].selected = true;
				}
			for (var i=0; i < document.getElementById('lista_msg_referencia').length ; i++) {
				document.getElementById('lista_msg_referencia').options[i].selected = true;
				}


			env.a.value='modelo_editar';
			env.submit();
			}
	else {
		alert('Necessita escrever o assunto de que se trata este documento.');
		env.assunto.focus();
		}
	}


function sumir(campo){
	document.getElementById(campo).style.display = 'none';
	}

function mover_pasta(pasta_id) {
	url_passar(0, "m=email&a=modelo_pesquisar&arquivar=1&mover=<?php echo $modelo_usuario_id ?>&pasta="+pasta_id);
	};




function resposta_despacho(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 500, 500, 'm=email&a=modelo_resposta_despacho&dialogo=1&modelo_id=<?php echo $modelo_id ?>&modelo_usuario_id=<?php echo $modelo_usuario_id ?>', null, window);
	else window.open('./index.php?m=email&a=modelo_resposta_despacho&dialogo=1&modelo_id=<?php echo $modelo_id ?>&modelo_usuario_id=<?php echo $modelo_usuario_id ?>', '','height=600, width=840, left=0, top=0, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}

function popDadosOrganizacao(campo, tipo_dado){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Remetente', 500, 500, 'm=publico&a=selecao_organizacao&dialogo=1&chamar_volta=setEndereco&tipo_dado='+tipo_dado+'&campo='+campo, window.setEndereco, window);
	else window.open('./index.php?m=publico&a=selecao_organizacao&dialogo=1&chamar_volta=setEndereco&tipo_dado='+tipo_dado+'&campo='+campo, 'Remetente','height=150,width=400,resizable,scrollbars=yes, left=0, top=0');
	}

function setEndereco(campo, tipo_dado, cia_nome, cia_endereco1, cia_endereco2, cia_cidade, cia_estado, cia_cep, cia_tel1, cia_tel2, cia_fax){
	if (tipo_dado=='endereco') document.getElementById('campo_'+campo).value=cia_endereco1+(cia_endereco2 ? "\n"+cia_endereco2 : "")+"\n"+cia_cidade+'-'+cia_estado+(cia_cep ? "\n"+cia_cep : "");
	else if (tipo_dado=='nome') document.getElementById('campo_'+campo).value=cia_nome;
	setGrupoId("campo"+campo+"_nome", cia_nome);
	setGrupoId("campo"+campo+"_cidade", cia_cidade);
	setGrupoId("campo"+campo+"_tel1", cia_tel1);
	setGrupoId("campo"+campo+"_fax", cia_fax);
	setGrupoId("campo"+campo+"_cep", cia_cep);
	setGrupoId("campo"+campo+"_end_completo", cia_endereco1+(cia_endereco2 ? "\n"+cia_endereco2 : "")+"\n"+cia_cidade+'-'+cia_estado+(cia_cep ? "\n"+cia_cep : ""));
	setGrupoId("campo"+campo+"_end", cia_endereco1+(cia_endereco2 ? cia_endereco2+"\n" : ""));
	}

	function setGrupoId(parcialid, valor){
		var elemento = document.getElementById(parcialid);
		if (elemento != null) document.getElementById(parcialid).value=valor;
		}



function popContatos(campo) {
	var contatos_id_selecionados='';
	var arr = new Array();
  arr = document.getElementsByName('nome_dest_'+campo);
  for(var i = 0; i < arr.length; i++){
     var obj = document.getElementsByName('nome_dest_'+campo).item(i);
     contatos_id_selecionados+=obj.value+',';
     }
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&campo='+campo+'&contatos_id_selecionados='+contatos_id_selecionados, window.setContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&campo='+campo+'&contatos_id_selecionados='+contatos_id_selecionados, 'contatos','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setContatos(retorno){
	var pedacos=retorno.split("#");
	var campo=pedacos[0];
	var resto=pedacos[1].split("*");
	var usuarios=resto[0].split(",");
	var nomes=resto[1].split(",");
	var funcoes=resto[2].split(",");
	document.getElementById('destinatarios_'+campo).innerHTML='';
	for(i=0;i<usuarios.length;i++) {
		if (usuarios[i]){
			var ni = document.getElementById('destinatarios_'+campo);
		  var novodiv = document.createElement('div');
		  var divIdNome = 'atual_'+campo+'_'+i;
		  novodiv.setAttribute('id',divIdNome);
		  novodiv.innerHTML = '<font size=1>&nbsp;'+nomes[i]+' - </font>'+'<input type="text" class="texto" name="funcao_'+campo+'" style="width:100px" value="'+funcoes[i]+'"><input type="hidden" name="nome_dest_'+campo+'" value="'+usuarios[i]+'"><a href="javascript: void(0);" onclick=\'var divIdNome="atual_'+campo+'_'+i+'"; env.campo_atual.value='+campo+'; removerElemento("'+divIdNome+'")\'><?php echo imagem("icones/excluir.gif")?></a>';
		  ni.appendChild(novodiv);
			}
		}
	}


function popAssinatura(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Assinatura', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&nome_completo=1&chamar_volta=setAssinatura&campo='+campo, window.setAssinatura, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&nome_completo=1&chamar_volta=setAssinatura&campo='+campo, 'Assinatura','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setAssinatura(usuario_id, posto, nome, funcao, campo){
	document.getElementById('funcao_'+campo).value=funcao;
	<?php echo ($config['militar'] < 10	? 'document.getElementById(\'nomeguerra_\'+campo).value=nome.toUpperCase();' : 'document.getElementById(\'nomeguerra_\'+campo).value=nome;')?>
	document.getElementById('posto_'+campo).value=posto;
	document.getElementById('assinante_'+campo).value=usuario_id;
	}


function popAssinaturaImpedido(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Assinatura', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&nome_completo=1&chamar_volta=setAssinaturaImpedido&campo='+campo, window.setAssinaturaImpedido, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&nome_completo=1&chamar_volta=setAssinaturaImpedido&campo='+campo, 'Assinatura','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setAssinaturaImpedido(usuario_id, posto, nome, funcao, campo){
	document.getElementById('funcaor_'+campo).value=funcao;
	<?php echo ($config['militar'] < 10	? 'document.getElementById(\'nomeguerrar_\'+campo).value=nome.toUpperCase();' : 'document.getElementById(\'nomeguerrar_\'+campo).value=nome;')?>
	document.getElementById('postor_'+campo).value=posto;
	document.getElementById('assinanter_'+campo).value=usuario_id;
	}

function popRemetente(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Remetente', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setRemetente&campo='+campo, window.setRemetente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setRemetente&campo='+campo, 'Remetente','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setRemetente(usuario_id, posto, nome, funcao, campo){
	document.getElementById('remetente_funcao_'+campo).value=funcao;
	document.getElementById('remetente_'+campo).value=usuario_id;
	}


function removerElemento(entrada){
	var campo_atual=document.getElementById('campo_atual').value;
	var d = document.getElementById('destinatarios_'+campo_atual);
 	var antigo = document.getElementById(entrada);
	d.removeChild(antigo);
	}

function setData(frm_nome, f_data) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + 'reg_' + f_data );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
      }
    else {
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			}
		}
	else campo_data_real.value = '';
	}

function popAnexar(modelo, posicao) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Inserir Anexo', 800, 500, 'm=email&a=modelo_inserir_anexo&dialogo=1&modelo_id='+modelo+'&posicao='+posicao+'&uuid='+document.getElementById('uuid').value, window.reescrever_anexos, window);
	else window.open('./index.php?m=email&a=modelo_inserir_anexo&dialogo=1&modelo_id='+modelo+'&posicao='+posicao+'&uuid='+document.getElementById('uuid').value, 'Inserir Anexo','left=0,top=0,height=280,width=800,scrollbars=yes, resizable=yes');
	}

function popExcluir(anexo, posicao){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Excluir Anexo', 800, 500, 'm=email&a=modelo_excluir_anexo&dialogo=1&modelo_anexo_id='+anexo+'&posicao='+posicao+'&uuid='+document.getElementById('uuid').value, window.reescrever_anexos, window);
	else abrirJanela('./index.php?m=email&a=modelo_excluir_anexo&dialogo=1&modelo_anexo_id='+anexo+'&posicao='+posicao+'&uuid='+document.getElementById('uuid').value, 'Excluir Anexo',285, 520);
	}

function popRenomear(anexo, posicao, qnt){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Renomear Anexo', 800, 500, 'm=email&a=modelo_renomear_anexo&dialogo=1&modelo_anexo_id='+anexo+'&posicao='+posicao+'&qnt='+qnt, window.reescrever_anexos, window);
	else abrirJanela('./index.php?m=email&a=modelo_renomear_anexo&dialogo=1&modelo_anexo_id='+anexo+'&posicao='+posicao+'&qnt='+qnt, 'Renomear Anexo',285, 520);
	}
function popDocumentos(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Documento', 800, 500, 'm=email&a=modelo_pesquisar&dialogo=1&anexar_documento=1&campo='+campo, window.anexar_documento, window);
	else window.open('./index.php?m=email&a=modelo_pesquisar&dialogo=1&anexar_documento=1&campo='+campo, '','height=600, width=1010, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no, left=0, top=0');
	}

function anexar_documento(modelo_id, texto, campo){
	var existe=0;
	//checar se já existe
	var arr = new Array();
    arr = document.getElementsByName('anexo_'+campo);
    for(var i = 0; i < arr.length; i++){
       var obj = document.getElementsByName('anexo_'+campo).item(i);
       if (obj.value==modelo_id) existe=1;
       }
	if (existe) alert('Este documento havia sido anexado!');
	else{
		var qnt=arr.length+1;
		var ni = document.getElementById('anexos_'+campo);
	  var novodiv = document.createElement('div');
	  var divIdNome = 'anexo_'+campo+'_'+qnt.value;
	  novodiv.setAttribute('id',divIdNome);

	  novodiv.innerHTML ='&nbsp;<input type="text" class="texto" name="nome_fantasia_'+campo+'[]" value="'+texto+'"><input type="hidden" name="anexo_'+campo+'[]" value="'+modelo_id+'"><a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_editar&modelo_id='+modelo_id+'&dialogo=1\')"><img style="vertical-align:middle" src="./estilo/rondon/imagens/icones/postagem.gif" alt="" border=0 /></a><a href="javascript: void(0);" onclick=\'var divIdNome="anexos_'+campo+'"; env.campo_atual.value='+campo+'; removerAnexo('+campo+', '+qnt.value+')\'><img style="vertical-align:middle" src="./estilo/rondon/imagens/icones/excluir.gif" alt="" border=0 /></a>';


	  ni.appendChild(novodiv);
		}
	}

function removerAnexo(campo, qnt){
	var d = document.getElementById('anexos_'+campo);
 	var antigo = document.getElementById('anexo_'+campo+'_'+qnt);
	d.removeChild(antigo);
	}

function reescrever_anexos(dados, posicao){
	document.getElementById('bloco_anexo_'+posicao).innerHTML=stripslashes(dados);
	}

function stripslashes(str) {
	str=str.replace(/\\'/g,'\'');
	str=str.replace(/\\"/g,'"');
	str=str.replace(/\&lt;/g,'');
	return str;
	}

function abrirJanela(janelaURL, janelaNome, janelaAltura, janelaLargura){
  var centroLargura = (window.screen.width - janelaLargura) / 2;
  var centroAltura = (window.screen.height - janelaAltura) / 2;
  newWindow = window.open(janelaURL, janelaNome, 'resizable=0,width='+janelaLargura+',height='+janelaAltura+',left='+centroLargura+',top=' + centroAltura);
  newWindow.focus();
  return newWindow.name;
	}


function mostrar(){
	limpar_tudo();
	esconder_tipo();
	if (document.getElementById('tipo_relacao').value){
		document.getElementById(document.getElementById('tipo_relacao').value).style.display='';
		}
	}

function esconder_tipo(){
	document.getElementById('projeto').style.display='none';
	document.getElementById('tarefa').style.display='none';
	document.getElementById('perspectiva').style.display='none';
	document.getElementById('tema').style.display='none';
	document.getElementById('objetivo').style.display='none';	
	document.getElementById('fator').style.display='none';	
	document.getElementById('estrategia').style.display='none';
	document.getElementById('meta').style.display='none';
	document.getElementById('pratica').style.display='none';
	document.getElementById('acao').style.display='none';
	document.getElementById('canvas').style.display='none';
	document.getElementById('risco').style.display='none';
	document.getElementById('risco_resposta').style.display='none';
	document.getElementById('indicador').style.display='none';
	document.getElementById('calendario').style.display='none';
	document.getElementById('monitoramento').style.display='none';
	document.getElementById('ata').style.display='none';
	document.getElementById('mswot').style.display='none';
	document.getElementById('swot').style.display='none';
	document.getElementById('operativo').style.display='none';
	document.getElementById('instrumento').style.display='none';
	document.getElementById('recurso').style.display='none';
	document.getElementById('problema').style.display='none';
	document.getElementById('demanda').style.display='none';
	document.getElementById('programa').style.display='none';
	document.getElementById('licao').style.display='none';
	document.getElementById('evento').style.display='none';
	document.getElementById('link').style.display='none';
	document.getElementById('avaliacao').style.display='none';
	document.getElementById('tgn').style.display='none';
	document.getElementById('brainstorm').style.display='none';
	document.getElementById('gut').style.display='none';
	document.getElementById('causa_efeito').style.display='none';
	document.getElementById('arquivo').style.display='none';
	document.getElementById('forum').style.display='none';
	document.getElementById('checklist').style.display='none';
	document.getElementById('agenda').style.display='none';
	document.getElementById('agrupamento').style.display='none';
	document.getElementById('patrocinador').style.display='none';
	document.getElementById('template').style.display='none';
	document.getElementById('painel').style.display='none';
	document.getElementById('painel_odometro').style.display='none';
	document.getElementById('painel_composicao').style.display='none';
	document.getElementById('tr').style.display='none';
	document.getElementById('me').style.display='none';
	document.getElementById('acao_item').style.display='none';
	document.getElementById('beneficio').style.display='none';
	document.getElementById('painel_slideshow').style.display='none';
	document.getElementById('projeto_viabilidade').style.display='none';
	document.getElementById('projeto_abertura').style.display='none';
	document.getElementById('plano_gestao').style.display='none';
	document.getElementById('ssti').style.display='none';
	document.getElementById('laudo').style.display='none';
	document.getElementById('trelo').style.display='none';
	document.getElementById('trelo_cartao').style.display='none';
	document.getElementById('pdcl').style.display='none';
	document.getElementById('pdcl_item').style.display='none';
	
	}
	
	


	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('modelo_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('modelo_cia').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.modelo_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		incluir_relacionado();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('modelo_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.modelo_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		incluir_relacionado();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('modelo_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('modelo_cia').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.modelo_template.value = chave;
		document.env.template_nome.value = valor;
		incluir_relacionado();
		}		


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('modelo_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=820,width=820,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.modelo_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	incluir_relacionado();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('modelo_cia').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.modelo_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	incluir_relacionado();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('modelo_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.modelo_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	incluir_relacionado();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('modelo_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.modelo_tema.value = chave;
	document.env.tema_nome.value = valor;
	incluir_relacionado();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('modelo_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.modelo_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('modelo_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.modelo_fator.value = chave;
	document.env.fator_nome.value = valor;
	incluir_relacionado();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('modelo_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.modelo_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	incluir_relacionado();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('modelo_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.modelo_meta.value = chave;
	document.env.meta_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('modelo_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.modelo_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	incluir_relacionado();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('modelo_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&edicao=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('modelo_cia').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.modelo_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	incluir_relacionado();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('modelo_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.modelo_acao.value = chave;
	document.env.acao_nome.value = valor;
	incluir_relacionado();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('modelo_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.modelo_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('modelo_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.modelo_risco.value = chave;
	document.env.risco_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('modelo_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('modelo_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.modelo_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('modelo_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('modelo_cia').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.modelo_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	incluir_relacionado();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('modelo_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('modelo_cia').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.modelo_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('modelo_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.modelo_ata.value = chave;
	document.env.ata_nome.value = valor;
	incluir_relacionado();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 1000, 700, 'm=swot&a=mswot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('modelo_cia').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.modelo_mswot.value = chave;
	document.env.mswot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Camçpo SWOT', 1000, 700, 'm=swot&a=swot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSWOT&tabela=swot&cia_id='+document.getElementById('modelo_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.modelo_swot.value = chave;
	document.env.swot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('modelo_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('modelo_cia').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.modelo_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('modelo_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('modelo_cia').value, 'Instrumento Jurídico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.modelo_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('modelo_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('modelo_cia').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.modelo_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('modelo_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.modelo_problema.value = chave;
	document.env.problema_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('modelo_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('modelo_cia').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.modelo_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('modelo_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.modelo_programa.value = chave;
	document.env.programa_nome.value = valor;
	incluir_relacionado();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('modelo_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.modelo_licao.value = chave;
	document.env.licao_nome.value = valor;
	incluir_relacionado();
	}

	
function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('modelo_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('modelo_cia').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.modelo_evento.value = chave;
	document.env.evento_nome.value = valor;
	incluir_relacionado();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('modelo_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('modelo_cia').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.modelo_link.value = chave;
	document.env.link_nome.value = valor;
	incluir_relacionado();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('modelo_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('modelo_cia').value, 'Avaliação','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.modelo_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	incluir_relacionado();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('modelo_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.modelo_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('modelo_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('modelo_cia').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.modelo_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	incluir_relacionado();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz GUT', 1000, 700, 'm=praticas&a=gut_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setGut&tabela=gut&cia_id='+document.getElementById('modelo_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('modelo_cia').value, 'Matriz GUT','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.modelo_gut.value = chave;
	document.env.gut_nome.value = valor;
	incluir_relacionado();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('modelo_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('modelo_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.modelo_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	incluir_relacionado();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('modelo_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('modelo_cia').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.modelo_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	incluir_relacionado();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 1000, 700, 'm=foruns&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('modelo_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('modelo_cia').value, 'Fórum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.modelo_forum.value = chave;
	document.env.forum_nome.value = valor;
	incluir_relacionado();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('modelo_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('modelo_cia').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.modelo_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	incluir_relacionado();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('modelo_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('modelo_cia').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.modelo_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	incluir_relacionado();
	}

function popPainel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('modelo_cia').value, window.setPainel, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('modelo_cia').value, 'Painel','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPainel(chave, valor){
	limpar_tudo();
	document.env.modelo_painel.value = chave;
	document.env.painel_nome.value = valor;
	incluir_relacionado();
	}		
	
function popOdometro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('modelo_cia').value, window.setOdometro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('modelo_cia').value, 'Odômetro','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setOdometro(chave, valor){
	limpar_tudo();
	document.env.modelo_painel_odometro.value = chave;
	document.env.painel_odometro_nome.value = valor;
	incluir_relacionado();
	}			
	
function popComposicaoPaineis() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('modelo_cia').value, window.setComposicaoPaineis, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('modelo_cia').value, 'Composição de Painéis','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setComposicaoPaineis(chave, valor){
	limpar_tudo();
	document.env.modelo_painel_composicao.value = chave;
	document.env.painel_composicao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTR() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('modelo_cia').value, window.setTR, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTR(chave, valor){
	limpar_tudo();
	document.env.modelo_tr.value = chave;
	document.env.tr_nome.value = valor;
	incluir_relacionado();
	}	
		
function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('modelo_cia').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	limpar_tudo();
	document.env.modelo_me.value = chave;
	document.env.me_nome.value = valor;
	incluir_relacionado();
	}		
		
function popAcaoItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('modelo_cia').value, window.setAcaoItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('modelo_cia').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAcaoItem(chave, valor){
	limpar_tudo();
	document.env.modelo_acao_item.value = chave;
	document.env.acao_item_nome.value = valor;
	incluir_relacionado();
	}		

function popBeneficio() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('modelo_cia').value, window.setBeneficio, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBeneficio(chave, valor){
	limpar_tudo();
	document.env.modelo_beneficio.value = chave;
	document.env.beneficio_nome.value = valor;
	incluir_relacionado();
	}	

function popSlideshow() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composições', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('modelo_cia').value, window.setSlideshow, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('modelo_cia').value, 'Slideshow de Composições','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}


function setSlideshow(chave, valor){
	limpar_tudo();
	document.env.modelo_painel_slideshow.value = chave;
	document.env.painel_slideshow_nome.value = valor;
	incluir_relacionado();
	}	

function popViabilidade() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=projetos&a=viabilidade_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('modelo_cia').value, window.setViabilidade, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('modelo_cia').value, 'Estudo de Viabilidade','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setViabilidade(chave, valor){
	limpar_tudo();
	document.env.modelo_projeto_viabilidade.value = chave;
	document.env.projeto_viabilidade_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAbertura() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=projetos&a=termo_abertura_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('modelo_cia').value, window.setAbertura, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('modelo_cia').value, 'Termo de Abertura','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAbertura(chave, valor){
	limpar_tudo();
	document.env.modelo_projeto_abertura.value = chave;
	document.env.projeto_abertura_nome.value = valor;
	incluir_relacionado();
	}		
	
function popPlanejamento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estratégico', 1000, 700, 'm=praticas&u=gestao&a=gestao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('modelo_cia').value, window.setPlanejamento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('modelo_cia').value, 'Planejamento Estratégico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPlanejamento(chave, valor){
	limpar_tudo();
	document.env.modelo_plano_gestao.value = chave;
	document.env.plano_gestao_nome.value = valor;
	incluir_relacionado();
	}		

	
function popSSTI() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["ssti"])?>', 1000, 700, 'm=ssti&a=ssti_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSSTI&tabela=ssti&cia_id='+document.getElementById('modelo_cia').value, window.setSSTI, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSSTI&tabela=ssti&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["ssti"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSSTI(chave, valor){
	limpar_tudo();
	document.env.modelo_ssti.value = chave;
	document.env.ssti_nome.value = valor;
	incluir_relacionado();
	}	
				
function popLaudo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["laudo"])?>', 1000, 700, 'm=ssti&a=laudo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLaudo&tabela=laudo&cia_id='+document.getElementById('modelo_cia').value, window.setLaudo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLaudo&tabela=laudo&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["laudo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLaudo(chave, valor){
	limpar_tudo();
	document.env.modelo_laudo.value = chave;
	document.env.laudo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popTrelo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo"])?>', 1000, 700, 'm=trelo&a=trelo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTrelo&tabela=trelo&cia_id='+document.getElementById('modelo_cia').value, window.setTrelo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTrelo&tabela=trelo&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["trelo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTrelo(chave, valor){
	limpar_tudo();
	document.env.modelo_trelo.value = chave;
	document.env.trelo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTreloCartao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo_cartao"])?>', 1000, 700, 'm=trelo&a=cartao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('modelo_cia').value, window.setTreloCartao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["trelo_cartao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTreloCartao(chave, valor){
	limpar_tudo();
	document.env.modelo_trelo_cartao.value = chave;
	document.env.trelo_cartao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPDCL() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl"])?>', 1000, 700, 'm=pdcl&a=pdcl_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('modelo_cia').value, window.setPDCL, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["pdcl"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCL(chave, valor){
	limpar_tudo();
	document.env.modelo_pdcl.value = chave;
	document.env.pdcl_nome.value = valor;
	incluir_relacionado();
	}				
	
function popPDCLItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl_item"])?>', 1000, 700, 'm=pdcl&a=pdcl_item_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCLItem&tabela=pdcl_item&cia_id='+document.getElementById('modelo_cia').value, window.setPDCLItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCLItem&tabela=pdcl_item&cia_id='+document.getElementById('modelo_cia').value, '<?php echo ucfirst($config["pdcl_item"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCLItem(chave, valor){
	limpar_tudo();
	document.env.modelo_pdcl_item.value = chave;
	document.env.pdcl_item_nome.value = valor;
	incluir_relacionado();
	}	
	

function limpar_tudo(){
	document.env.projeto_nome.value = '';
	document.env.modelo_projeto.value = null;
	document.env.modelo_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.modelo_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.modelo_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.modelo_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.modelo_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.modelo_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.modelo_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.modelo_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.modelo_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.modelo_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.modelo_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.modelo_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.modelo_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.modelo_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.modelo_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.modelo_ata.value = null;
	document.env.ata_nome.value = '';
	document.env.modelo_mswot.value = null;
	document.env.mswot_nome.value = '';
	document.env.modelo_swot.value = null;
	document.env.swot_nome.value = '';
	document.env.modelo_operativo.value = null;
	document.env.operativo_nome.value = '';
	document.env.modelo_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.modelo_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.modelo_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.modelo_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.modelo_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.modelo_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.modelo_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.modelo_link.value = null;
	document.env.link_nome.value = '';
	document.env.modelo_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.modelo_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.modelo_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.modelo_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.modelo_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.modelo_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.modelo_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.modelo_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.modelo_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.modelo_agrupamento.value = null;
	document.env.agrupamento_nome.value = '';
	document.env.modelo_patrocinador.value = null;
	document.env.patrocinador_nome.value = '';
	document.env.modelo_template.value = null;
	document.env.template_nome.value = '';
	document.env.modelo_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.modelo_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.modelo_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	document.env.modelo_tr.value = null;
	document.env.tr_nome.value = '';
	document.env.modelo_me.value = null;
	document.env.me_nome.value = '';
	document.env.modelo_acao_item.value = null;
	document.env.acao_item_nome.value = '';
	document.env.modelo_beneficio.value = null;
	document.env.beneficio_nome.value = '';
	document.env.modelo_painel_slideshow.value = null;
	document.env.painel_slideshow_nome.value = '';
	document.env.modelo_projeto_viabilidade.value = null;
	document.env.projeto_viabilidade_nome.value = '';
	document.env.modelo_projeto_abertura.value = null;
	document.env.projeto_abertura_nome.value = '';
	document.env.modelo_plano_gestao.value = null;
	document.env.plano_gestao_nome.value = '';
	document.env.modelo_ssti.value = null;
	document.env.ssti_nome.value = '';
	document.env.modelo_laudo.value = null;
	document.env.laudo_nome.value = '';
	document.env.modelo_trelo.value = null;
	document.env.trelo_nome.value = '';
	document.env.modelo_trelo_cartao.value = null;
	document.env.trelo_cartao_nome.value = '';
	document.env.modelo_pdcl.value = null;
	document.env.pdcl_nome.value = '';
	document.env.modelo_pdcl_item.value = null;
	document.env.pdcl_item_nome.value = '';			
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('modelo_id').value,
	document.getElementById('uuid').value,
	f.modelo_projeto.value,
	f.modelo_tarefa.value,
	f.modelo_perspectiva.value,
	f.modelo_tema.value,
	f.modelo_objetivo.value,
	f.modelo_fator.value,
	f.modelo_estrategia.value,
	f.modelo_meta.value,
	f.modelo_pratica.value,
	f.modelo_acao.value,
	f.modelo_canvas.value,
	f.modelo_risco.value,
	f.modelo_risco_resposta.value,
	f.modelo_indicador.value,
	f.modelo_calendario.value,
	f.modelo_monitoramento.value,
	f.modelo_ata.value,
	f.modelo_mswot.value,
	f.modelo_swot.value,
	f.modelo_operativo.value,
	f.modelo_instrumento.value,
	f.modelo_recurso.value,
	f.modelo_problema.value,
	f.modelo_demanda.value,
	f.modelo_programa.value,
	f.modelo_licao.value,
	f.modelo_evento.value,
	f.modelo_link.value,
	f.modelo_avaliacao.value,
	f.modelo_tgn.value,
	f.modelo_brainstorm.value,
	f.modelo_gut.value,
	f.modelo_causa_efeito.value,
	f.modelo_arquivo.value,
	f.modelo_forum.value,
	f.modelo_checklist.value,
	f.modelo_agenda.value,
	f.modelo_agrupamento.value,
	f.modelo_patrocinador.value,
	f.modelo_template.value,
	f.modelo_painel.value,
	f.modelo_painel_odometro.value,
	f.modelo_painel_composicao.value,
	f.modelo_tr.value,
	f.modelo_me.value,
	f.modelo_acao_item.value,
	f.modelo_beneficio.value,
	f.modelo_painel_slideshow.value,
	f.modelo_projeto_viabilidade.value,
	f.modelo_projeto_abertura.value,
	f.modelo_plano_gestao.value,
	f.modelo_ssti.value,
	f.modelo_laudo.value,
	f.modelo_trelo.value,
	f.modelo_trelo_cartao.value,
	f.modelo_pdcl.value,
	f.modelo_pdcl_item.value
	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(modelo_gestao_id){
	xajax_excluir_gestao(document.getElementById('modelo_id').value, document.getElementById('uuid').value, modelo_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, modelo_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, modelo_gestao_id, direcao, document.getElementById('modelo_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$modelo_id && (
	$modelo_tarefa || 
	$modelo_projeto || 
	$modelo_perspectiva || 
	$modelo_tema || 
	$modelo_objetivo || 
	$modelo_fator || 
	$modelo_estrategia || 
	$modelo_meta || 
	$modelo_pratica || 
	$modelo_acao || 
	$modelo_canvas || 
	$modelo_risco || 
	$modelo_risco_resposta || 
	$modelo_indicador || 
	$modelo_calendario || 
	$modelo_monitoramento || 
	$modelo_ata || 
	$modelo_mswot || 
	$modelo_swot || 
	$modelo_operativo || 
	$modelo_instrumento || 
	$modelo_recurso || 
	$modelo_problema || 
	$modelo_demanda || 
	$modelo_programa || 
	$modelo_licao || 
	$modelo_evento || 
	$modelo_link || 
	$modelo_avaliacao || 
	$modelo_tgn || 
	$modelo_brainstorm || 
	$modelo_gut || 
	$modelo_causa_efeito || 
	$modelo_arquivo || 
	$modelo_forum || 
	$modelo_checklist || 
	$modelo_agenda || 
	$modelo_agrupamento || 
	$modelo_patrocinador || 
	$modelo_template || 
	$modelo_painel || 
	$modelo_painel_odometro || 
	$modelo_painel_composicao || 
	$modelo_tr || 
	$modelo_me || 
	$modelo_acao_item || 
	$modelo_beneficio || 
	$modelo_painel_slideshow || 
	$modelo_projeto_viabilidade || 
	$modelo_projeto_abertura || 
	$modelo_plano_gestao|| 
	$modelo_ssti || 
	$modelo_laudo || 
	$modelo_trelo || 
	$modelo_trelo_cartao || 
	$modelo_pdcl || 
	$modelo_pdcl_item
	)) echo 'incluir_relacionado();';
	?>	

</script>
