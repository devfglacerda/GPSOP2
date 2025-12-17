<?php
require_once $Aplic->getClasseSistema('Modelo');
require_once $Aplic->getClasseSistema('Template');

$modeloID=getParam($_REQUEST, 'modeloID', null);
if ($modeloID) $modelo_id=reset($modeloID);
else $modelo_id=getParam($_REQUEST, 'modelo_id', null);

$modelo_tipo_id=getParam($_REQUEST, 'modelo_tipo_id', null);
$modelo_dados_id=getParam($_REQUEST, 'modelo_dados_id', null);
$anterior=getParam($_REQUEST, 'anterior', 0);
$posterior=getParam($_REQUEST, 'posterior', 0);
$modelo_usuario_id=getParam($_REQUEST, 'modelo_usuario_id', null);
$novo=getParam($_REQUEST, 'novo', 0);
$retornar=getParam($_REQUEST, 'retornar', 'modelo_pesquisar');

$sql = new BDConsulta;

if ($Aplic->profissional){
	$sql->adTabela('assinatura');
	$sql->adCampo('assinatura_id, assinatura_data, assinatura_aprova');
	$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('assinatura_modelo='.(int)$modelo_id);
	$assinar = $sql->linha();
	$sql->limpar();
	
	//tem assinatura que aprova
	$sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	$sql->adOnde('assinatura_aprova=1');
	$sql->adOnde('assinatura_modelo='.(int)$modelo_id);
	$tem_aprovacao = $sql->resultado();
	$sql->limpar();
	}



$sql->adTabela('modelos');
$sql->esqUnir('modelos_tipo','modelos_tipo','modelos_tipo.modelo_tipo_id=modelos.modelo_tipo');
$sql->adCampo('modelo_aprovado, class_sigilosa, modelo_assinatura, modelo_chave_publica, modelo_id, modelo_tipo, modelo_criador_original, modelo_data, modelo_versao_aprovada, modelo_protocolo, modelo_autoridade_assinou, modelo_autoridade_aprovou, modelo_assunto, organizacao, modelo_tipo_html');
$sql->adOnde('modelo_id='.(int)$modelo_id);
$linha=$sql->Linha();

$sql->limpar();
$sql->adTabela('modelos_dados');
$sql->esqUnir('usuarios', 'usuarios', 'usuario_id = modelos_dados_criador');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('contato_funcao, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('modelo_dados_id, modelos_dados_campos, modelos_dados_criador, modelo_dados_data');
$sql->adOnde('modelo_dados_modelo='.(int)$modelo_id);

if ($modelo_dados_id && $anterior) {
	$sql->adOnde('modelo_dados_id <'.$modelo_dados_id);
	$sql->adOrdem('modelo_dados_id DESC');
	}
elseif ($modelo_dados_id && $posterior) {
	$sql->adOnde('modelo_dados_id >'.(int)$modelo_dados_id);
	$sql->adOrdem('modelo_dados_id ASC');
	}
else $sql->adOrdem('modelo_dados_id DESC');

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

$modelo->edicao=false;

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








echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="a" id="a" value="modelo_ver">';
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




if (!$dialogo){
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes do Documento', 'documento.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	
	echo estiloTopoCaixa();	
	echo '<table rules="ALL" border=1 align="center" cellspacing=0 cellpadding=0 style="width:100%;">';
	echo '<tr><td colspan=2 style="background-color: #212121">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";

	

	//referencias
	$sql->adTabela('referencia');
	$sql->esqUnir('msg', 'msg', 'msg.msg_id=referencia.referencia_msg_pai');
	$sql->esqUnir('modelos', 'modelos', 'modelos.modelo_id=referencia.referencia_doc_pai');
	$sql->adCampo('referencia.*, msg.de_id, modelos.*, msg.referencia, msg.data_envio, nome_de, funcao_de');
	$sql->adOnde('referencia_doc_filho = '.(int)$modelo_id);
	$lista_referencia_pai = $sql->Lista();
	$sql->limpar();
	if ($lista_referencia_pai && count($lista_referencia_pai)) {
		$qnt_lista_referencia_pai=count($lista_referencia_pai);
		$km->Add("root","root_referencia",dica('Referencias','Lista de'.$config['genero_mensagem'].' '.($config['genero_mensagem']=='o' ? 'ao' : 'a').'s quais este documento faz referencia.').'Referencias'.dicaF());
			for ($i = 0, $i_cmp = $qnt_lista_referencia_pai; $i < $i_cmp; $i++) {
				if ($lista_referencia_pai[$i]['referencia_msg_pai']) {
					$lista= dica('Ler '.ucfirst($config['mensagem']), 'Clique para ler '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem']).'<a href="javascript: void(0);" onclick="env.a.value=\''.$Aplic->usuario_prefs['modelo_msg'].'\';	env.fazerSQL.value=\'\'; env.msg_id.value='.$lista_referencia_pai[$i]['referencia_msg_pai'].'; env.submit();">Msg. '.$lista_referencia_pai[$i]['referencia_msg_pai'].($lista_referencia_pai[$i]['referencia']? ' - '.$lista_referencia_pai[$i]['referencia'] : '').' - '.nome_funcao($lista_referencia_pai[$i]['nome_de'], '', $lista_referencia_pai[$i]['funcao_de'], '', $lista_referencia_pai[$i]['de_id']).' - '.retorna_data($lista_referencia_pai[$i]['data_envio'], false).'</a>'.dicaF();
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
					$lista= dica('Ler Documento', 'Clique para ler este documento').'<a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_ver&modelo_id='.$lista_referencia_pai[$i]['referencia_doc_pai'].($lista_referencia_pai[$i]['modelo_autoridade_aprovou'] > 0 ? '&dialogo=1\'' : '\', \'_self\'').')">Doc. '.$lista_referencia_pai[$i]['referencia_doc_pai'].($lista_referencia_pai[$i]['modelo_assunto']? ' - '.$lista_referencia_pai[$i]['modelo_assunto'] : '').' - '.$nome.' - '.$data.'</a>'.dicaF();
					}
				$km->Add("root_referencia","root_ref_".$lista_referencia_pai[$i]['referencia_msg_pai'].'_'.$lista_referencia_pai[$i]['referencia_doc_pai'], $lista);
				}
			}

	//referenciados
	$sql->adTabela('referencia');
	$sql->esqUnir('msg', 'msg', 'msg.msg_id=referencia.referencia_msg_filho');
	$sql->esqUnir('modelos', 'modelos', 'modelos.modelo_id=referencia.referencia_doc_filho');
	$sql->adCampo('referencia.*, msg.de_id, modelos.*, msg.referencia, msg.data_envio, nome_de, funcao_de');
	$sql->adOnde('referencia_doc_pai = '.(int)$modelo_id);
	$lista_referencia_filho = $sql->Lista();
	$sql->limpar();
	if ($lista_referencia_filho && count($lista_referencia_filho)) {
		$qnt_lista_referencia_pai=count($lista_referencia_filho);
		$km->Add("root","root_referenciados",dica('Referenciad'.$config['genero_mensagem'].'s','Lista de '.$config['mensagens'].' que fazem referencia a este documento.').'Referenciad'.$config['genero_mensagem'].'s'.dicaF());
			for ($i = 0, $i_cmp = $qnt_lista_referencia_pai; $i < $i_cmp; $i++) {
				if ($lista_referencia_filho[$i]['referencia_msg_filho']) {
					$lista= dica('Ler '.ucfirst($config['mensagem']), 'Clique para ler '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem']).'<a href="javascript: void(0);" onclick="env.a.value=\''.$Aplic->usuario_prefs['modelo_msg'].'\';	env.fazerSQL.value=\'\'; env.msg_id.value='.$lista_referencia_filho[$i]['referencia_msg_filho'].'; env.submit();">Msg. '.$lista_referencia_filho[$i]['referencia_msg_filho'].($lista_referencia_filho[$i]['referencia']? ' - '.$lista_referencia_filho[$i]['referencia'] : '').' - '.nome_funcao($lista_referencia_filho[$i]['nome_de'], '', $lista_referencia_filho[$i]['funcao_de'], '', $lista_referencia_filho[$i]['de_id']).' - '.retorna_data($lista_referencia_filho[$i]['data_envio'], false).'</a>'.dicaF();
					}
				else {
					if ($lista_referencia_filho[$i]['modelo_autoridade_assinou']) {
						$nome=nome_funcao($lista_referencia_filho[$i]['modelo_assinatura_nome'], '', $lista_referencia_filho[$i]['modelo_assinatura_funcao'], '', $lista_referencia_filho[$i]['modelo_autoridade_assinou']);
						$data=retorna_data($lista_referencia_filho[$i]['modelo_data_assinado'], false);
						}
					elseif ($lista_referencia_filho[$i]['modelo_autoridade_aprovou']) {
						$nome=nome_funcao($lista_referencia_filho[$i]['modelo_aprovou_nome'], '', $lista_referencia_filho[$i]['modelo_aprovou_funcao'], '', $lista_referencia_filho[$i]['modelo_autoridade_aprovou']);
						$data=retorna_data($lista_referencia_filho[$i]['modelo_data_aprovado'], false);
						}
					else {
						$nome=nome_funcao($lista_referencia_filho[$i]['modelo_criador_nome'], '', $lista_referencia_filho[$i]['modelo_criador_funcao'], '', $lista_referencia_filho[$i]['modelo_criador_original']);
						$data=retorna_data($lista_referencia_filho[$i]['modelo_data'], false);
						}
					$lista= dica('Ler Documento', 'Clique para ler este documento').'<a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_ver&modelo_id='.$lista_referencia_filho[$i]['referencia_doc_filho'].($lista_referencia_filho[$i]['modelo_autoridade_aprovou'] > 0 ? '&dialogo=1\'' : '\', \'_self\'').')">Doc. '.$lista_referencia_filho[$i]['referencia_doc_filho'].($lista_referencia_filho[$i]['modelo_assunto']? ' - '.$lista_referencia_filho[$i]['modelo_assunto'] : '').' - '.$nome.' - '.$data.'</a>'.dicaF();
					}
				$km->Add("root_referenciados","root_refa_".$lista_referencia_filho[$i]['referencia_msg_filho'].'_'.$lista_referencia_filho[$i]['referencia_doc_filho'], $lista);
				}
			}


	//informações
	if (!$novo) $km->Add("root","root_informacao",dica('Informações','Ao se pressionar este botão irá abrir uma janela onde poderá visualizar os despachos, anotações e encaminhamentos efetuados neste documento.').'Informações'.dicaF(), "javascript: void(0);' onclick='visualizar_extra();");


	$km->Add("root","acao",dica('Ação','Selecione qual ação deseja execuar neste documento.').'Ação');
	
	$bloquear=($linha['modelo_aprovado'] && $config['trava_aprovacao'] && $tem_aprovacao && !$Aplic->usuario_super_admin);
	if (isset($assinar['assinatura_id']) && $assinar['assinatura_id'] && !$bloquear) $km->Add("acao","acao_assinar", ($assinar['assinatura_data'] ? dica('Mudar Assinatura', 'Entrará na tela em que se pode mudar a assinatura.').'Mudar Assinatura'.dicaF() : dica('Assinar', 'Entrará na tela em que se pode assinar.').'Assinar'.dicaF()), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&u=assinatura&a=assinatura_assinar&modelo_id=".$modelo_id."\");"); 
	
	
	
	//editar
	$podeEditar=(!$linha['modelo_versao_aprovada'] && ($Aplic->checarModulo('email', 'acesso', $Aplic->usuario_id, 'edita_modelo') || ($linha['modelo_criador_original']==$Aplic->usuario_id)));

	if ($podeEditar && !$bloquear) $km->Add("acao","acao_editar",dica('Editar', 'Editar este documento.').'Editar'.dicaF(), "javascript: void(0);' onclick='env.a.value=\"modelo_editar\"; env.fazerSQL.value=\"\"; env.submit();");
	
	//responder despacho
	if (isset($enviado) && $enviado['data_limite'] && !$enviado['resposta_despacho']) $km->Add("acao","root_resposta_despacho",'<span id="responder_despacho" style="display:">'.dica('Inserir uma Resposta a um Despacho', 'Este doumento lhe foi enviado através de um despacho com solicitação de resposta de '.$enviado['nome_despachante'].' até '.retorna_data($enviado['data_limite'], false).'.<br>'.$enviado['texto'].'<br>Clique neste botão para abrir uma janela onde poderá escrever uma resposta a este despacho.').'Responder despacho'.dicaF().'</span>', "javascript: void(0);' onclick='resposta_despacho();");
	elseif (isset($enviado) && $enviado['tipo']==1 && !$enviado['resposta_despacho']) $km->Add("acao","root_resposta_despacho",'<span id="responder_despacho" style="display:">'.dica('Inserir uma Resposta a um Despacho', 'Este doumento lhe foi enviado através de um despacho sem prazo para resposta de '.$enviado['nome_despachante'].'.<br>'.$enviado['texto'].'<br>Clique neste botão para abrir uma janela onde poderá escrever uma resposta a este despacho.').'Responder despacho'.dicaF().'</span>', "javascript: void(0);' onclick='resposta_despacho();");

	//responder
	if (isset($enviado['de_id'])&& $enviado['de_id']!=$Aplic->usuario_id) $km->Add("acao","acao_responder",dica('Responder', 'Responder ao recebimento deste documento com o envio de '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' ao remetente.').'Responder'.dicaF(), "javascript: void(0);' onclick='env.tipo.value=2; env.fazerSQL.value=\"\"; env.a.value=\"modelo_envia_anot\";	env.retornar.value=\"modelo_ver\"; env.submit();");
	//encaminhar
	$km->Add("acao","acao_encaminhar",dica('Encaminhar', 'Encaminhe este documento.').'Encaminhar'.dicaF(), "javascript: void(0);' onclick='env.tipo.value=3;	env.destino.value=\"modelo_grava_encaminha\";	env.a.value=\"modelo_seleciona_usuarios\"; env.fazerSQL.value=\"\"; env.retornar.value=\"modelo_ver\"; env.submit();");

	//arquivar
	if ($enviado['status']!=4 && $modelo_usuario_id) $km->Add("acao","arquivada",dica('Arquivar','Clique nesta opção para mover este documento para a caixa dos arquivados.').'Arquivar'.dicaF(), "javascript: void(0);' onclick='env.status.value=4; env.a.value=\"modelo_grava_status\"; env.fazerSQL.value=\"\"; env.retornar.value=\"modelo_pesquisar\"; env.submit();");
	
	
		//despachar
	$km->Add("acao","acao_despachar",dica('Despachar', 'Despachar este documento.').'Despachar'.dicaF(), "javascript: void(0);' onclick='env.tipo.value=1; env.fazerSQL.value=\"\"; env.destino.value=\"modelo_envia_anot\";	env.a.value=\"modelo_seleciona_usuarios\";	env.retornar.value=\"modelo_ver\"; env.submit();");

	//anotar
	$km->Add("acao","acao_anotar",dica('Anotar', 'Anotar neste documento.').'Anotar'.dicaF(), "javascript: void(0);' onclick='env.tipo.value=4; env.a.value=\"modelo_envia_anot\"; env.fazerSQL.value=\"\";	env.retornar.value=\"modelo_ver\"; env.submit();");

	//aprovar
	if ($Aplic->checarModulo('email', 'acesso', $Aplic->usuario_id, 'aprova_modelo') && !$linha['modelo_versao_aprovada'] && !$bloquear) $km->Add("acao","acao_aprovar",dica('Aprovar', 'Aprovar este documento. Estando aprovado, o mesmo não poderá mais ser modificado e será enviado para o protocolo.').'Aprovar'.dicaF(), "javascript: void(0);' onclick='env.aprovar.value=1; env.submit();");
	//assinar
	if ($Aplic->checarModulo('email', 'acesso', $Aplic->usuario_id, 'assina_modelo') && !$linha['modelo_autoridade_assinou']  && !$bloquear && ($linha['modelo_autoridade_aprovou']==$Aplic->usuario_id) && function_exists('openssl_sign') && $Aplic->chave_privada) $km->Add("acao","acao_assinar",dica('Assinar', 'Assinar este documento.').'Assinar'.dicaF(), "javascript: void(0);' onclick='env.assinar.value=1; env.submit();");


	//imprimir
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Imprimir este documento.').'Imprimir'.dicaF(), "javascript: void(0);' onclick='imprimir();");

	//excluir
	if ($linha['modelo_criador_original']==$Aplic->usuario_id && !$linha['modelo_versao_aprovada'] && !$bloquear) $km->Add("acao","acao_excluir",dica('Excluir', 'Excluir este documento.').'Excluir'.dicaF(), "javascript: void(0);' onclick='if(confirm(\"Tem certeza que deseja excluir este documento?\")){env.excluir.value=1; env.submit();}");



	//mover
	if (isset($enviado['status'])) $km->Add("acao","mover_msg",dica('Mover Para','Selecione para aonde deseja mover este documento.').'Mover para'.dicaF());
	//entrada
	if (isset($enviado['status'])&& $enviado['status']>1) $km->Add("mover_msg","mover_msg_entrada",dica('Caixa de Entrada', 'Colocar este documento na caixa de entrada.').'Caixa de Entrada'.dicaF(), "javascript: void(0);' onclick='env.status.value=1; env.fazerSQL.value=\"\"; env.a.value=\"modelo_grava_status\"; env.retornar.value=\"modelo_pesquisar\"; env.submit();");
	//pender
	if (isset($enviado['status'])&& $enviado['status']!=3) $km->Add("mover_msg","mover_msg_pender",dica('Caixa de Pendentes', 'Colocar este documento na caixa dos pendentes.').'Caixa de Pendentes'.dicaF(), "javascript: void(0);' onclick='env.status.value=3; env.a.value=\"modelo_grava_status\"; env.fazerSQL.value=\"\"; env.retornar.value=\"modelo_pesquisar\"; env.submit();");
	//arquivar em uma pasta
	if (isset($enviado['status'])){
		$sql->adTabela('pasta');
		$sql->adCampo('pasta_id, nome');
		$sql->adOnde('usuario_id = '.$Aplic->usuario_id);
		$pastas=$sql->Lista();
		$sql->limpar();
		if (count($pastas)){
			$km->Add("mover_msg","mover_pasta",dica('Para Pasta','Selecione em qual pasta deseja arquivar este documento.').'Para Pasta'.dicaF());
			foreach ($pastas as $linha_pasta) $km->Add("mover_pasta","pasta_".$linha_pasta['pasta_id'],$linha_pasta['nome'], "javascript: void(0);' onclick='mover_pasta(".$linha_pasta['pasta_id'].");");
			}
		}


	
	//retornar
	if ($retornar) $km->Add("root","root_retornar",dica('Retornar','Ao se pressionar este botão irá retornar a tela anterior.').'Retornar'.dicaF(), "javascript: void(0);' onclick='env.fazerSQL.value=\"\"; env.a.value=\"".$retornar."\"; env.submit();");
	echo $km->Render();
	echo '</td></tr>';
	echo '</table>';
	}





echo '<table width="100%" align="center" cellspacing=1 cellpadding=0 class="std">';


if (isset($linha['modelo_id'])) echo '<tr><td align="right"  style="white-space: nowrap" width=10>'.dica('Número','Número deste documento.').'Número:'.dicaF().'</td><td  class="realce">'.$linha['modelo_id'].'</td></tr>';

echo '<tr><td align="right"  style="white-space: nowrap" width=10>'.dica('Assunto','Assunto a que este documento se refere.').'Assunto:'.dicaF().'</td><td  class="realce">'.$assinado.$linha['modelo_assunto'].'</td></tr>';


$sql->adTabela('modelo_gestao');
$sql->adCampo('modelo_gestao.*');
$sql->adOnde('modelo_gestao_modelo ='.(int)$modelo_id);	
$sql->adOrdem('modelo_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
$qnt_gestao=0;

if (count($lista)) {
	echo '<tr><td align="right" style="white-space: nowrap" valign="top">'.dica('Relacionado','Áreas as quais está relacionado.').'Relacionado:'.dicaF().'</td><td class="realce" width="100%">';
	foreach($lista as $gestao_data){
		if ($gestao_data['modelo_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['modelo_gestao_tarefa']);
		elseif ($gestao_data['modelo_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['modelo_gestao_projeto']);
		elseif ($gestao_data['modelo_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['modelo_gestao_pratica']);
		elseif ($gestao_data['modelo_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['modelo_gestao_acao']);
		elseif ($gestao_data['modelo_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['modelo_gestao_perspectiva']);
		elseif ($gestao_data['modelo_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['modelo_gestao_tema']);
		elseif ($gestao_data['modelo_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['modelo_gestao_objetivo']);
		elseif ($gestao_data['modelo_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['modelo_gestao_fator']);
		elseif ($gestao_data['modelo_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['modelo_gestao_estrategia']);
		elseif ($gestao_data['modelo_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['modelo_gestao_meta']);
		elseif ($gestao_data['modelo_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['modelo_gestao_canvas']);
		elseif ($gestao_data['modelo_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['modelo_gestao_risco']);
		elseif ($gestao_data['modelo_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['modelo_gestao_risco_resposta']);
		elseif ($gestao_data['modelo_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['modelo_gestao_indicador']);
		elseif ($gestao_data['modelo_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['modelo_gestao_calendario']);
		elseif ($gestao_data['modelo_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['modelo_gestao_monitoramento']);
		elseif ($gestao_data['modelo_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['modelo_gestao_ata']);
		elseif ($gestao_data['modelo_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['modelo_gestao_mswot']);
		elseif ($gestao_data['modelo_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['modelo_gestao_swot']);
		elseif ($gestao_data['modelo_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['modelo_gestao_operativo']);
		elseif ($gestao_data['modelo_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['modelo_gestao_instrumento']);
		elseif ($gestao_data['modelo_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['modelo_gestao_recurso']);
		elseif ($gestao_data['modelo_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['modelo_gestao_problema']);
		elseif ($gestao_data['modelo_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['modelo_gestao_demanda']);	
		elseif ($gestao_data['modelo_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['modelo_gestao_programa']);
		elseif ($gestao_data['modelo_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['modelo_gestao_licao']);
		elseif ($gestao_data['modelo_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['modelo_gestao_evento']);
		elseif ($gestao_data['modelo_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['modelo_gestao_link']);
		elseif ($gestao_data['modelo_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['modelo_gestao_avaliacao']);
		elseif ($gestao_data['modelo_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['modelo_gestao_tgn']);
		elseif ($gestao_data['modelo_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['modelo_gestao_brainstorm']);
		elseif ($gestao_data['modelo_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['modelo_gestao_gut']);
		elseif ($gestao_data['modelo_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['modelo_gestao_causa_efeito']);
		elseif ($gestao_data['modelo_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['modelo_gestao_arquivo']);
		elseif ($gestao_data['modelo_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['modelo_gestao_forum']);
		elseif ($gestao_data['modelo_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['modelo_gestao_checklist']);
		elseif ($gestao_data['modelo_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['modelo_gestao_agenda']);
		elseif ($gestao_data['modelo_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['modelo_gestao_agrupamento']);
		elseif ($gestao_data['modelo_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['modelo_gestao_patrocinador']);
		elseif ($gestao_data['modelo_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['modelo_gestao_template']);
		elseif ($gestao_data['modelo_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['modelo_gestao_painel']);
		elseif ($gestao_data['modelo_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['modelo_gestao_painel_odometro']);
		elseif ($gestao_data['modelo_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['modelo_gestao_painel_composicao']);		
		elseif ($gestao_data['modelo_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['modelo_gestao_tr']);	
		elseif ($gestao_data['modelo_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['modelo_gestao_me']);	
		elseif ($gestao_data['modelo_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['modelo_gestao_acao_item']);	
		elseif ($gestao_data['modelo_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['modelo_gestao_beneficio']);	
		elseif ($gestao_data['modelo_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['modelo_gestao_painel_slideshow']);	
		elseif ($gestao_data['modelo_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['modelo_gestao_projeto_viabilidade']);	
		elseif ($gestao_data['modelo_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['modelo_gestao_projeto_abertura']);	
		elseif ($gestao_data['modelo_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['modelo_gestao_plano_gestao']);	
		elseif ($gestao_data['modelo_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['modelo_gestao_ssti']);	
		elseif ($gestao_data['modelo_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['modelo_gestao_laudo']);	
		elseif ($gestao_data['modelo_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['modelo_gestao_trelo']);	
		elseif ($gestao_data['modelo_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['modelo_gestao_trelo_cartao']);	
		elseif ($gestao_data['modelo_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['modelo_gestao_pdcl']);	
		elseif ($gestao_data['modelo_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['modelo_gestao_pdcl_item']);	
		}
	echo '</td></tr>';
	}	


if ($Aplic->profissional && $tem_aprovacao) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Aprovado', 'Se o documento se encontra aprovado.').'Aprovado:'.dicaF().'</td><td  class="realce" width="100%">'.($linha['modelo_aprovado'] ? 'Sim' : '<span style="color:red; font-weight:bold">Não</span>').'</td></tr>';

if (isset($dados['modelo_dados_id'])) echo '<tr style="font-family:verdana, arial, helvetica, sans-serif;font-size:8pt;"><td width="100%" align="center" colspan=2>'.($qnt_antes ? '<a href="javascript: void(0);" onclick="javascript: env.anterior.value=1; env.fazerSQL.value=\'\'; env.submit();">'.imagem('icones/retroceder.gif', 'Retroceder', 'Clique para visualizar alterações anteriores').'</a>' : '').$dados['nome_usuario'].($dados['contato_funcao'] ? ' - '.$dados['contato_funcao'] : '').' - '.retorna_data($dados['modelo_dados_data']).($qnt_depois ? '<a href="javascript: void(0);" onclick="javascript:env.fazerSQL.value=\'\'; env.posterior.value=1; env.submit();">'.imagem('icones/avancar.gif', 'Avançar', 'Clique para visualizar alterações posteriores').'</a>' : '').'</td><td></td></tr>';
else echo '<tr><td width="100%" align="center" colspan=2>'.($qnt_antes ? '<a href="javascript: void(0);" onclick="javascript:env.fazerSQL.value=\'\'; env.anterior.value=1; env.submit();">'.imagem('icones/retroceder.gif', 'Retroceder', 'Clique para visualizar alterações anteriores').'</a>' : '').$Aplic->usuario_nome.($Aplic->usuario_funcao ? ' - '.$Aplic->usuario_funcao : '').($qnt_depois ? '<a href="javascript: void(0);" onclick="javascript:env.fazerSQL.value=\'\'; env.posterior.value=1; env.submit();">'.imagem('icones/avancar.gif', 'Avançar', 'Clique para visualizar alterações posteriores').'</a>' : '').'</td></tr>';


echo '<tr><td colspan=20><table border=1 align="center" cellspacing=0 cellpadding=0 style="background-color:#FFFFFF;"><tr><td>';

for ($i=1; $i <= $modelo->quantidade(); $i++){
	$campo='campo_'.$i;
	$tpl->$campo = $modelo->get_campo($i);
	}
echo $tpl->exibir($modelo->edicao);
echo '</td></tr></table>';

echo '</td></tr>';

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/email/modelo_ver_pro.php';		
echo '</table>';

echo '</form>';
?>
<script type="text/javascript">

function visualizar_extra(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 500, 500, 'm=email&a=modelo_exibe_extra&dialogo=1&modelo_usuario_id=<?php echo $modelo_usuario_id ?>&modelo_id=<?php echo $modelo_id ?>', null, window);
	else window.open('./index.php?m=email&a=modelo_exibe_extra&dialogo=1&modelo_usuario_id=<?php echo $modelo_usuario_id ?>&modelo_id=<?php echo $modelo_id ?>', '','height=600, width=810, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no, left=0, top=0');
	}	

function imprimir(){
	var sem_assinatura=1;
	if(confirm('Com assinatura digitalizada, se for o caso?')) sem_assinatura=0;
	url_passar(1, 'm=email&a=modelo_imprimir&dialogo=1&imprimir=1&modelo_id='+document.getElementById('modelo_id').value+'&sem_assinatura='+sem_assinatura);
	}		
</script>	