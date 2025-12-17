<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
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

$idunico=getParam($_REQUEST, 'idunico', '');
if (!$idunico) $idunico=uniqid('',true);

$sql = new BDConsulta;

if ($excluir){
	$sql->setExcluir('modelos');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();

	$sql->setExcluir('modelos_dados');
	$sql->adOnde('modelo_dados_modelo='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();

	$sql->adTabela('modelos_anexos');
	$sql->adCampo('caminho');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$resultados=$sql->Lista();
	$sql->limpar();
	foreach ($resultados as $anexo){
		$caminho=str_replace('/', '\\', $anexo['caminho']);
		if (file_exists($base_dir.'\\'.$config['pasta_anexos'].'_modelos\\'.$caminho))	@unlink($base_dir.'\\'.$config['pasta_anexos'].'_modelos\\'.$caminho);
		}
	$sql->setExcluir('modelos_anexos');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();

	$sql->setExcluir('anexos');
	$sql->adOnde('modelo='.(int)$modelo_id);
	$sql->exec();
	$sql->limpar();

	$Aplic->redirecionar('m=email&a='.$retornar);
	exit();
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

if ($salvar && getParam($_REQUEST, 'assunto', '')){
	if (!$modelo_id){
		$sql->adTabela('modelos');
		$sql->adInserir('modelo_tipo', $modelo_tipo_id);
		$sql->adInserir('modelo_criador_original',  $Aplic->usuario_id);
		$sql->adInserir('modelo_criador_nome',  $Aplic->usuario_nome);
		$sql->adInserir('modelo_criador_funcao',  $Aplic->usuario_funcao);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela modelos!');
		$modelo_id=$bd->Insert_ID('modelos','modelo_id');
		$sql->Limpar();
		//mudar os anexos que estão sem id do modelo
		$sql->adTabela('modelos_anexos');
		$sql->adCampo('modelo_anexo_id, caminho');
		$sql->adOnde('idunico = \''.$idunico.'\'');
		$anexos=$sql->lista();
		$sql->Limpar();
		foreach($anexos as $anexo){
			$segunda_parte=str_replace($idunico, '', substr($anexo['caminho'],8));
			$novo_caminho=substr($anexo['caminho'],0,8).'M'.$modelo_id.$segunda_parte;
			if (file_exists($base_dir.'/'.$config['pasta_anexos'].'_modelos'.'/'.$anexo['caminho']))	rename($base_dir.'/'.$config['pasta_anexos'].'_modelos'.'/'.$anexo['caminho'], $base_dir.'/'.$config['pasta_anexos'].'_modelos'.'/'.$novo_caminho);
			$sql->adTabela('modelos_anexos');
			$sql->adAtualizar('caminho',  $novo_caminho);
			$sql->adAtualizar('modelo_id',  $modelo_id);
			$sql->adOnde('modelo_anexo_id='.(int)$anexo['modelo_anexo_id']);
			$sql->exec();
			$sql->limpar();
			}
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

	

	ver2('Documento salvo');
	$salvar=0;
	$novo=0;
	}
elseif ($salvar && !getParam($_REQUEST, 'assunto', '')) ver2('O assunto do documento não foi enviado!');

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


	if ($editar) $modelo->edicao=true;
	else $modelo->edicao=false;
	$criador=$Aplic->usuario_id;
	}
elseif ($modelo_id && !$salvar){
	$sql->adTabela('modelos');
	$sql->esqUnir('modelos_tipo','modelos_tipo','modelos_tipo.modelo_tipo_id=modelos.modelo_tipo');
	$sql->adCampo('class_sigilosa, modelo_assinatura, modelo_chave_publica, modelo_id, modelo_tipo, modelo_criador_original, modelo_data, modelo_versao_aprovada, modelo_protocolo, modelo_autoridade_assinou, modelo_autoridade_aprovou, modelo_assunto, organizacao, modelo_tipo_html');
	$sql->adOnde('modelo_id='.(int)$modelo_id);
	$linha=$sql->Linha();

	$sql->Limpar();
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
	$sql->Limpar();
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

	if ($editar && !$linha['modelo_versao_aprovada']) $modelo->edicao=true;
	else $modelo->edicao=false;
	}
$qnt_antes=0;
$qnt_depois=0;



if ($modelo_dados_id && $modelo_id){
	$sql->adTabela('modelos_dados');
	$sql->adCampo('count(modelo_dados_id)');
	$sql->adOnde('modelo_dados_id <'.(int)$modelo_dados_id);
	$sql->adOnde('modelo_dados_modelo ='.(int)$modelo_id);
	$qnt_antes=$sql->Resultado();
	$sql->Limpar();
	$sql->adTabela('modelos_dados');
	$sql->adCampo('count(modelo_dados_id)');
	$sql->adOnde('modelo_dados_id >'.(int)$modelo_dados_id);
	$sql->adOnde('modelo_dados_modelo ='.(int)$modelo_id);
	$qnt_depois=$sql->Resultado();
	$sql->Limpar();
	}


echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="a" id="a" value="'.$a.'">';
echo '<input type=hidden name="m" id="email" value="email">';
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
echo '<input type=hidden name="idunico" id="idunico"  value="'.$idunico.'">';
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

if (!$modelo->edicao &&!$dialogo){
	echo '<table rules="ALL" border="1" align="center" cellspacing=0 cellpadding=0 style="width:750px;">';
	echo '<tr><td colspan=2 style="background-color: #484040">';
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
					$lista= dica('Ler '.ucfirst($config['mensagem']), 'Clique para ler '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem']).'<a href="javascript: void(0);" onclick="env.a.value=\''.$Aplic->usuario_prefs['modelo_msg'].'\';	env.msg_id.value='.$lista_referencia_pai[$i]['referencia_msg_pai'].'; env.submit();">Msg. '.$lista_referencia_pai[$i]['referencia_msg_pai'].($lista_referencia_pai[$i]['referencia']? ' - '.$lista_referencia_pai[$i]['referencia'] : '').' - '.nome_funcao($lista_referencia_pai[$i]['nome_de'], '', $lista_referencia_pai[$i]['funcao_de'], '', $lista_referencia_pai[$i]['de_id']).' - '.retorna_data($lista_referencia_pai[$i]['data_envio'], false).'</a>'.dicaF();
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
					$lista= dica('Ler Documento', 'Clique para ler este documento').'<a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_editar&modelo_id='.$lista_referencia_pai[$i]['referencia_doc_pai'].($lista_referencia_pai[$i]['modelo_autoridade_aprovou'] > 0 ? '&dialogo=1\'' : '\', \'_self\'').')">Doc. '.$lista_referencia_pai[$i]['referencia_doc_pai'].($lista_referencia_pai[$i]['modelo_assunto']? ' - '.$lista_referencia_pai[$i]['modelo_assunto'] : '').' - '.$nome.' - '.$data.'</a>'.dicaF();
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
					$lista= dica('Ler '.ucfirst($config['mensagem']), 'Clique para ler '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem']).'<a href="javascript: void(0);" onclick="env.a.value=\''.$Aplic->usuario_prefs['modelo_msg'].'\';	env.msg_id.value='.$lista_referencia_filho[$i]['referencia_msg_filho'].'; env.submit();">Msg. '.$lista_referencia_filho[$i]['referencia_msg_filho'].($lista_referencia_filho[$i]['referencia']? ' - '.$lista_referencia_filho[$i]['referencia'] : '').' - '.nome_funcao($lista_referencia_filho[$i]['nome_de'], '', $lista_referencia_filho[$i]['funcao_de'], '', $lista_referencia_filho[$i]['de_id']).' - '.retorna_data($lista_referencia_filho[$i]['data_envio'], false).'</a>'.dicaF();
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
					$lista= dica('Ler Documento', 'Clique para ler este documento').'<a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_editar&modelo_id='.$lista_referencia_filho[$i]['referencia_doc_filho'].($lista_referencia_filho[$i]['modelo_autoridade_aprovou'] > 0 ? '&dialogo=1\'' : '\', \'_self\'').')">Doc. '.$lista_referencia_filho[$i]['referencia_doc_filho'].($lista_referencia_filho[$i]['modelo_assunto']? ' - '.$lista_referencia_filho[$i]['modelo_assunto'] : '').' - '.$nome.' - '.$data.'</a>'.dicaF();
					}
				$km->Add("root_referenciados","root_refa_".$lista_referencia_filho[$i]['referencia_msg_filho'].'_'.$lista_referencia_filho[$i]['referencia_doc_filho'], $lista);
				}
			}

	
	//anotar
	$km->Add("root","acao_anotar",dica('Anotar', 'Anotar neste documento.').'Anotar'.dicaF(), "javascript: void(0);' onclick='env.tipo.value=4; env.a.value=\"modelo_envia_anot\";	env.retornar.value=\"modelo_editar\"; env.submit();");

	


	
	//retornar
	if ($retornar) $km->Add("root","root_retornar",dica('Retornar','Ao se pressionar este botão irá retornar a tela anterior.').'Retornar'.dicaF(), "javascript: void(0);' onclick='env.a.value=\"".$retornar."\"; env.submit();");
	echo $km->Render();
	echo '</td></tr>';
	echo '</table>';
	}


echo '<br>';
$modelo_usuario_id=getParam($_REQUEST, 'modelo_usuario_id', 0);
$modelo_id=getParam($_REQUEST, 'modelo_id', 0);
$tipos_status=array('' => 'indefinido') + getSisValor('status');
$primeiro=0;
$sql = new BDConsulta; 
$sql->adTabela('modelos');
$sql->esqUnir('modelo_usuario','modelo_usuario','modelo_usuario.modelo_id = modelos.modelo_id');
$sql->adCampo('modelos.modelo_id');
if ($modelo_usuario_id) {
	$sql->adOnde('modelo_usuario.modelo_usuario_id = '.$modelo_usuario_id);
	$sql->adOnde('modelos.class_sigilosa <= '.$Aplic->usuario_acesso_email);
	}
else {
	$sql->adOnde('modelo_criador_original = '.$Aplic->usuario_id);
	}
$permitido = $sql->Resultado();
$sql->limpar();

		
$sql->adTabela('preferencia_cor');
$sql->adCampo('cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_anotacao, cor_resposta, cor_encamihamentos');
$sql->adOnde('usuario_id ='.$Aplic->usuario_id);
$cor=$sql->Linha();
$sql->limpar();
if (!isset($cor['cor_msg'])) {
	$sql->adTabela('preferencia_cor');
	$sql->adCampo('cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_anotacao, cor_resposta, cor_encamihamentos');
	$sql->adOnde('usuario_id = 0 OR usuario_id IS NULL');
	$cor=$sql->Linha();
	$sql->limpar();
 	}
 	
$sql->adTabela('modelos');
$sql->adCampo('modelo_data_protocolo, modelo_protocolo, modelo_protocolista, modelo_criador_original, modelo_data, modelo_autoridade_assinou, modelo_data_assinado, modelo_autoridade_aprovou, modelo_data_aprovado');
$sql->adOnde('modelo_id = '.$modelo_id);
$modelo = $sql->Linha();
$sql->limpar();



echo '
<table align="center" cellspacing=0 width="790" cellpadding=0><tbody><tr><td
 style="text-align: left;" valign="undefined"><img
 style="width: 230px; height: 57px;" alt=""
 src="http://www.dae.ce.gov.br/images/logo.jpg"></td><td
 style="text-align: right;" valign="undefined"><img
 style="width: 230px; height: 65px;" alt=""
 src="http://www.blogdowilrismar.com/imagens/logo_governo_estado.png"></td></tr></tbody></table>
<table align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_encamihamentos'].'" cellspacing=0 width="790" cellpadding=0>';
echo '<tr><td colspan="5" align="center" style="font-size:12pt;"><b>Histórico do processo</b></td></tr>';
echo '<tr><td align=center><table align="center" class="tbl1" cellspacing=0 width="100%" cellpadding=0>';
echo '<tr align=center><td><b>'.ucfirst($config['usuario']).'</b></td><td><b>Ação</b></td><td><b>Data</b></td></tr>';
$sql->adTabela('modelos_dados');
$sql->adCampo('modelos_dados_criador, modelo_dados_data');
$sql->adOnde('modelo_dados_modelo = '.$modelo_id);
//EUZ adicionada linha para Ordem
$sql->adOrdem('modelo_dados_data');
//EUD

$dados = $sql->Lista();
$sql->limpar();
$qnt=0;
foreach($dados as $dado) echo '<tr align=center><td>'.nome_funcao('','','','',$modelo['modelo_criador_original']).'</td><td>'.(!$qnt++ ? 'Criou' : 'Editou').'</td><td>'.retorna_data($dado['modelo_dados_data']).'</td></tr>';
if ($modelo['modelo_autoridade_assinou']) echo '<tr align=center><td>'.nome_funcao('','','','',$modelo['modelo_autoridade_assinou']).'</td><td>Assinou</td><td>'.retorna_data($modelo['modelo_data_assinado']).'</td></tr>';
elseif ($modelo['modelo_autoridade_aprovou']) echo '<tr align=center><td>'.nome_funcao('','','','',$modelo['modelo_autoridade_aprovou']).'</td><td>Aprovou</td><td>'.retorna_data($modelo['modelo_data_aprovado']).'</td></tr>';
if ($modelo['modelo_protocolista']) echo '<tr align=center><td>'.nome_funcao('','','','',$modelo['modelo_protocolista']).'</td><td>Protocolou</td><td>'.retorna_data($modelo['modelo_data_protocolo']).'</td></tr>';
if ($modelo['modelo_protocolo']) echo '<tr align=center><td><b>Protocolo :&nbsp;</b>'.$modelo['modelo_protocolo'].'</td><td colspan=2>&nbsp;</td></tr>';
echo '</table></td></tr>';
echo '<tr><td>&nbsp;</td></tr>';
echo '</table>';
//echo sombra_baixo('', 790);
$sql->adTabela('modelo_anotacao');
$sql->adUnir('usuarios','usuarios','modelo_anotacao.usuario_id = usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('modelo_anotacao_usuarios, modelo_anotacao.datahora, modelo_anotacao.usuario_id, modelo_anotacao.nome_de, modelo_anotacao.funcao_de, modelo_anotacao.texto, modelo_anotacao.tipo, contato_funcao, modelo_anotacao_id');
$sql->adOnde('modelo_id = '.$modelo_id);
//EUZ retirado o DESC
$sql->adOrdem('modelo_anotacao_id');
//EUD

$sql_resultadosb = $sql->Lista();
$sql->limpar();
$outros_despachos=array();
foreach ($sql_resultadosb as $rs_anot){ 
	if ($rs_anot['tipo'] == 1 ) { 
		//despacho
		$vetor_destinatarios=array();
		$saida = '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
		$saida.= '<table align="center" cellspacing=0 width="790" cellpadding=0>';
		$saida.= '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_despacho'].'" ><a href="javascript:void(0);" onclick="javascript: mostrar_esconder(\'linha1_\', '.$rs_anot['modelo_anotacao_id'].');">Despacho de '.nome_funcao($rs_anot['nome_de'], $rs_anot['nome_usuario'], $rs_anot['funcao_de'], $rs_anot['contato_funcao']).' em '.retorna_data($rs_anot['datahora']).'</a></td></tr>';
		$saida.= '<tr id="linha1_'.$rs_anot['modelo_anotacao_id'].'" style="display:none"><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].'">'.$rs_anot['texto'].'</td></tr>';
		$saida.= '<tr id="2linha1_'.$rs_anot['modelo_anotacao_id'].'" style="display:none"><td style="font-size:8pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_despacho'].'"><table cellspacing=0 cellpadding=0><tr><td><b>Para</b>:</td><td>';
		$sql->adTabela('modelo_usuario');
		$sql->adUnir('usuarios','usuarios','modelo_usuario.para_id = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
		$sql->adCampo('modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de, modelo_usuario.para_id, modelo_usuario.nome_para, modelo_usuario.funcao_para, modelo_usuario.copia_oculta, contato_funcao');
		$sql->adOnde('modelo_id = '.$modelo_id);
		$sql->adOnde('de_id = '.$rs_anot['usuario_id']);
		$sql->adOnde('modelo_usuario.datahora=\''.$rs_anot['datahora'].'\'');
		//EUZ postgres
		//$sql->adGrupo('para_id');
    $sql->adGrupo('para_id, contatos.contato_posto, contatos.contato_nomeguerra, modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de, modelo_usuario.para_id, modelo_usuario.nome_para, modelo_usuario.funcao_para, modelo_usuario.copia_oculta, contato_funcao');
		//EUD

		$destinatarios_despacho = $sql->Lista();
		$sql->limpar();
	  $quant=0; 
	  $primeira_linha=0; 
		if (!count($destinatarios_despacho)){
	  	$sql->adTabela('modelo_usuario');
			$sql->adUnir('usuarios','usuarios','modelo_usuario.para_id = usuarios.usuario_id');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
			$sql->adCampo('modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de, modelo_usuario.para_id, modelo_usuario.nome_para, modelo_usuario.funcao_para, modelo_usuario.copia_oculta, contato_funcao');
			$sql->adOnde('modelo_id = '.$modelo_id);
			$sql->adOnde('de_id = '.$rs_anot['usuario_id']);
			
			$sql->adOnde('modelo_usuario.datahora BETWEEN adiciona_data(\''.$rs_anot['datahora'].'\', -60, \'SECOND\') AND adiciona_data(\''.$rs_anot['datahora'].'\', 60, \'SECOND\')');
      $sql->adGrupo('para_id');
			
			$sql->adOnde('modelo_usuario.datahora BETWEEN adiciona_data(\''.$rs_anot['datahora'].'\', -60, \'SECOND\') AND adiciona_data(\''.$rs_anot['datahora'].'\', 60, \'SECOND\')');
			$sql->adGrupo('para_id');
			$destinatarios_despacho = $sql->Lista();
			$sql->limpar();
	  	}
	  	
	  if (isset($destinatarios_despacho[0]['para_id'])&& $destinatarios_despacho[0]['para_id']) $vetor_destinatarios[]=$destinatarios_despacho[0]['para_id'];
		if (isset($destinatarios_despacho[0]) && $destinatarios_despacho[0]) $saida.= formata_despacho($destinatarios_despacho[0]);
		$qnt_destinatario=count($destinatarios_despacho);
		if ($qnt_destinatario > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_destinatario; $i < $i_cmp; $i++) {
					$lista.= formata_despacho($destinatarios_despacho[$i]).'<br>';
					$vetor_destinatarios[]=$destinatarios_despacho[$i]['para_id'];
					}		
				$saida.= dica('Outros Destinatários', 'Clique para visualizar os demais destinatários.').' <a href="javascript: void(0);" onclick="mostrar_esconder(\'despacho_\', '.$rs_anot['modelo_anotacao_id'].');">(+'.($qnt_destinatario - 1).')</a>'.dicaF(). '<span style="display: none" id="despacho_'.$rs_anot['modelo_anotacao_id'].'"><br>'.$lista.'</span>';
				}
		$saida.= '</td></tr></table></td></tr></table>';
		$saida.= '</td></tr></table>'; 
		if (in_array($Aplic->usuario_id, $vetor_destinatarios) || $rs_anot['usuario_id']==$Aplic->usuario_id) echo $saida;
		else $outros_despachos[]=$saida;
		} 
	else if ($rs_anot['tipo'] == 2 ){ 
		echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
		echo '<table align="center" cellspacing=0 width="790" cellpadding=0>';
	  echo '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_resposta'].'" ><a href="javascript:void(0);" onclick="javascript: mostrar_esconder(\'linha1_\', '.$rs_anot['modelo_anotacao_id'].');">Resposta de '.nome_funcao($rs_anot['nome_de'], $rs_anot['nome_usuario'], $rs_anot['funcao_de'], $rs_anot['contato_funcao'])." em ".retorna_data($rs_anot['datahora']).'</a></td></tr>';
	  echo '<tr id="linha1_'.$rs_anot['modelo_anotacao_id'].'" style="display:none"><td style="font-size:10pt; padding-left: 5px; padding-right: 5px;  background-color: #'.$cor['cor_fundo'].'">'.$rs_anot['texto'].'</td></tr></table>';
		echo '</td></tr></table>'; 
		} 
	else if ($rs_anot['tipo'] == 4 ){
		$pode_ver=0;
		if (!$rs_anot['modelo_anotacao_usuarios'] || $rs_anot['usuario_id']==$Aplic->usuario_id) $pode_ver=1;
		else {
			$sql->adTabela('modelo_anotacao_usuarios');
			$sql->adOnde('usuario_id');
			$sql->adOnde('modelo_anotacao_id = '.$rs_anot['modelo_anotacao_id']);
			$sql->adOnde('usuario_id='.$Aplic->usuario_id);
			$pode_ver= $sql->Resultado();
			$sql->limpar();
			}
		if ($pode_ver){
			echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
		  echo '<table align="center" cellspacing=0 width="790" cellpadding=0>';
		  echo '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_anotacao'].'" ><a href="javascript:void(0);" onclick="javascript: mostrar_esconder(\'linha1_\', '.$rs_anot['modelo_anotacao_id'].');">Nota de '.nome_funcao($rs_anot['nome_de'], $rs_anot['nome_usuario'], $rs_anot['funcao_de'], $rs_anot['contato_funcao']).' em '.retorna_data($rs_anot['datahora']).'</a></td></tr>';
		  echo '<tr id="linha1_'.$rs_anot['modelo_anotacao_id'].'" style="display:none"><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].'">'.$rs_anot['texto'].'</td></tr></table>';     
		  echo '</td></tr></table>'; 
			}
	  } 
	}    
//if (count($sql_resultadosb)) echo sombra_baixo('', 790); 
if (count($outros_despachos))	{
	echo '<table align="center"><tr><td>'.dica('Outros Despachos','Clique neste link para visualizar os outros despachos efetados n'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'<a href="javascript:void(0);" onclick="javascript:mostrar_esconder(\'outros_despacho\', \'\');" style="padding-left: 5px; font-size:10pt; font-weight:Bold;">Outros despachos ('.count($outros_despachos).')</a>'.dicaF().'</td></tr></table>';
	echo '<span style="display: none" id="outros_despacho">';
	foreach($outros_despachos as $outro) echo $outro;
	echo '<br></span>';
	}
$sql->adTabela('modelo_usuario');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=de_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('modelo_usuario_id, data_retorno, data_limite, resposta_despacho, modelo_usuario.tipo, modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de, modelo_usuario.para_id, modelo_usuario.nome_para, modelo_usuario.funcao_para, modelo_usuario.copia_oculta, modelo_usuario.status, modelo_usuario.datahora_leitura, modelo_usuario.cm, modelo_usuario.meio, usuarios.usuario_id, contato_funcao, datahora');
$sql->adOnde('modelo_id = '.$modelo_id);
//EUZ adicionada linha para Ordem
$sql->adOrdem('datahora');
//EUD

$sql_resultadosf = $sql->Lista();
$sql->limpar();
$tipo=array('0'=>'envio', '1'=>'despacho', '2'=>'resposta', '3'=>'encaminhamento', '4'=>'nota');
$objeto_data = new CData();
$agora=$objeto_data->format('%Y-%m-%d %H:%M:%S');
if ($sql_resultadosf && count($sql_resultadosf)){
	echo '<table align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_encamihamentos'].'" cellspacing=0 width="790" cellpadding=0>';
	echo '<tr><td colspan="5" align="center" style="font-size:12pt;"><b>Tramitação interna do processo - DIARQ</b></td></tr>';
	echo '<tr><td><table align="center" class="tbl1" cellspacing=0 width="100%" cellpadding=0>';
	echo '<tr><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Tipo</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>De</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Para</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Data de Envio</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Data de Leitura</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Status</b></td></tr>'; 	

	foreach ($sql_resultadosf as $rs_enc){ 
	  if (($rs_enc['copia_oculta'] !=1) || ($rs_enc['de_id']==$Aplic->usuario_id || $rs_enc['para_id']==$Aplic->usuario_id )) {
	    if ($rs_enc['tipo']==1 && !$rs_enc['data_limite']) $cor_campo='FFFFFF';
	    elseif ($rs_enc['tipo']==1 && (($rs_enc['data_retorno']> $rs_enc['data_limite']) || ($rs_enc['data_limite']< $agora && !$rs_enc['data_retorno']))) $cor_campo='FFCCCC';
	    elseif ($rs_enc['tipo']==1 && ($rs_enc['data_retorno']<= $rs_enc['data_limite'])) $cor_campo='CCFFCC';
	    else $cor_campo='FFFFFF';
	    echo '<tr>';
	    echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px; background-color:#'.$cor_campo.'">'.$tipo[$rs_enc['tipo']].($rs_enc['resposta_despacho'] ? '<a href="javascript: void(0);" onclick="mostrar_esconder(\'despacho_\', '.$rs_enc['modelo_usuario_id'].');">'.imagem('icones/msg10000.gif','Resposta ao Despacho','Clique neste ícone '.imagem('icones/msg1000.gif').' para visualizar a resposta ao despacho.').'</a>' :'').'</td>';
	    echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.nome_funcao($rs_enc['nome_de'], '', $rs_enc['funcao_de'], '').'</td>';
	    echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.formata_destinatario($rs_enc).'</td>';
	    echo "<td nowrap='nowrap' style='font-size:7pt; padding-left: 2px; padding-right: 2px;'>".retorna_data($rs_enc['datahora']).'</td>';
	    echo "<td nowrap='nowrap' style='font-size:7pt; padding-left: 2px; padding-right: 2px;'>";
			if (!$rs_enc['datahora_leitura'])	echo 'Não Lida';
			else echo retorna_data($rs_enc['datahora_leitura']).($rs_enc['cm'] == 1 ? '(CM:'.nome_usuario($rs_enc['cm']).' por '.$rs_enc['meio'].')' : '');
			echo '</td>';
			echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.$tipos_status[$rs_enc['status']].'</td>';
			echo '</tr>';
			if ($rs_enc['resposta_despacho']) echo '<tr id="despacho_'.$rs_enc['modelo_usuario_id'].'" style="display:none;"><td colspan=20>'.$rs_enc['resposta_despacho'].'</td></tr>';
			}
		}
	echo '</table></td></tr><tr><td>&nbsp;</td></tr></table>';
	//echo sombra_baixo('', 790); 	
	}
	



	

function formata_despacho ($rs_anotf=array()){
	global $Aplic;
	$saida='';
	if ($rs_anotf['para_id'] == $Aplic->usuario_id ) $saida.= '<b>';
  if ($rs_anotf['copia_oculta'] ==1 && ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= '<i>';
  if ($rs_anotf['copia_oculta'] !=1 || ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= nome_funcao($rs_anotf['nome_para'], $rs_anotf['nome_usuario'], $rs_anotf['funcao_para'], $rs_anotf['contato_funcao'])."&nbsp;&nbsp;";
  if ($rs_anotf['copia_oculta'] ==1 && ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3 )) $saida.= '</i>'; 
  if ($rs_anotf['para_id'] == $Aplic->usuario_id ) $saida.= '</b>';
  return $saida;
	}

function formata_destinatario($rs_para=array()){
	global $Aplic,$tipos_status;
	$saida='';
	if (($rs_para['copia_oculta'] ==1) && ($rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= '<i>';
	$saida.=($rs_para['copia_oculta'] !=1|| $rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3 ? nome_funcao($rs_para['nome_para'], '', $rs_para['funcao_para'], '') : 'oculto');
	if (($rs_para['copia_oculta'] ==1) && ($rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id  || $Aplic->usuario_acesso_email > 3)) $saida.= '</i>';
	return $saida;	
	}
		
?>
<script language=Javascript>


function mostrar_esconder(campo, numero){
	if (document.getElementById(campo+numero).style.display == 'none'){
		document.getElementById(campo+numero).style.display = '';
		if (document.getElementById('2'+campo+numero)) document.getElementById('2'+campo+numero).style.display = '';
		}
	else {
		document.getElementById(campo+numero).style.display = 'none';
		if (document.getElementById('2'+campo+numero)) document.getElementById('2'+campo+numero).style.display = 'none';
		}
	}


</script>	

<body>
<b style="color: rgb(0, 0, 0); font-family: Arial; font-size: 11px; font-style: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: center; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: rgb(255, 255, 255);"><br>
</b>
<div style="text-align: center;"><b
 style="color: rgb(0, 0, 0); font-family: Arial; font-size: 11px; font-style: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: center; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: rgb(255, 255, 255);">___________________________________________________________________________________________________</b><br>
<b
 style="color: rgb(0, 0, 0); font-family: Arial; font-size: 11px; font-style: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: center; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: rgb(255, 255, 255);">Departamento
de Arquitetura e Engenharia&nbsp;</b><br>
<b
 style="color: rgb(0, 0, 0); font-family: Arial; font-size: 11px; font-style: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: center; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: rgb(255, 255, 255);">CNPJ
13.543.312/0001-93</b><br
 style="color: rgb(0, 0, 0); font-family: Arial; font-size: 11px; font-style: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: center; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: rgb(255, 255, 255);">
<span
 style="color: rgb(0, 0, 0); font-family: Arial; font-size: 11px; font-style: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: center; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; display: inline ! important; float: none; background-color: rgb(255, 255, 255);">Av.
Alberto Craveiro, 2775 / T&eacute;rreo - Castel&atilde;o,
Fortaleza/CE - CEP 60861-211</span><br>
<span
 style="color: rgb(0, 0, 0); font-family: Arial; font-size: 11px; font-style: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: center; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; display: inline ! important; float: none; background-color: rgb(255, 255, 255);">Fone:
085 3295-6217</span><br
 style="color: rgb(0, 0, 0); font-family: Arial; font-size: 11px; font-style: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: center; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; background-color: rgb(255, 255, 255);">
<span
 style="color: rgb(0, 0, 0); font-family: Arial; font-size: 11px; font-style: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: center; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; display: inline ! important; float: none; background-color: rgb(255, 255, 255);">Hor&aacute;rio
de funcionamento: 08h &agrave;s 12h - 13h &agrave;s 17h
(Segunda-Sexta)</span></div>
</body>
