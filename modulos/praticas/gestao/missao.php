<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
if ($editarPG) {
  $Aplic->carregarCKEditorJS();
	}
global $config, $exibir;
echo '<script type="text/javascript" src="'.BASE_URL.'/js/jscolor.js"></script>';
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
$direcao=getParam($_REQUEST, 'cmd', '');
$plano_gestao_arquivo_plano_gestao=getParam($_REQUEST, 'plano_gestao_arquivo_plano_gestao', '0');
$ordem=getParam($_REQUEST, 'ordem', '0');

echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="plano_gestao_arquivo_id" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';

if($direcao) {
		$novo_ui_ordem = $ordem;
		$q = new BDConsulta;
		$q->adTabela('plano_gestao_arquivo');
		$q->adOnde('plano_gestao_arquivo_id !='.(int)$plano_gestao_arquivo_id);
		$q->adOnde('plano_gestao_arquivo_plano_gestao ='.(int)$pg_id);
		$q->adOnde('plano_gestao_arquivo_campo = \'Missao\'');
		$q->adOrdem('plano_gestao_arquivo_ordem');
		$arquivos = $q->Lista();
		$q->limpar();
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			}
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			}
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			}
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($arquivos) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($arquivos) + 1)) {
			$q = new BDConsulta;
			$q->adTabela('plano_gestao_arquivo');
			$q->adAtualizar('plano_gestao_arquivo_ordem', $novo_ui_ordem);
			$q->adOnde('plano_gestao_arquivo_id ='.(int)$plano_gestao_arquivo_id);
			$q->exec();
			$q->limpar();
			$idx = 1;
			foreach ($arquivos as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$q->adTabela('plano_gestao_arquivo');
					$q->adAtualizar('plano_gestao_arquivo_ordem', $idx);
					$q->adOnde('plano_gestao_arquivo_id ='.(int)$acao['plano_gestao_arquivo_id']);
					$q->exec();
					$q->limpar();
					$idx++;
					}
				else {
					$q->adTabela('plano_gestao_arquivo');
					$q->adAtualizar('plano_gestao_arquivo_ordem', $idx + 1);
					$q->adOnde('plano_gestao_arquivo_id ='.(int)$acao['plano_gestao_arquivo_id']);
					$q->exec();
					$q->limpar();
					$idx = $idx + 2;
					}
				}
			}
		}


if ($excluiranexo){

	$sql->adTabela('plano_gestao_arquivo');
	$sql->adCampo('plano_gestao_arquivo_endereco, plano_gestao_arquivo_local, plano_gestao_arquivo_nome_real');
	$sql->adOnde('plano_gestao_arquivo_id='.(int)$plano_gestao_arquivo_id);
	$caminho=$sql->linha();
	$sql->limpar();
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	if ($arquivo['plano_gestao_arquivo_local']) @unlink($base_dir.'/arquivos/'.$arquivo['plano_gestao_arquivo_local'].$arquivo['plano_gestao_arquivo_nome_real']);
	else @unlink($base_dir.'/arquivos/gestao/'.$arquivo['plano_gestao_arquivo_endereco']);

	$sql->setExcluir('plano_gestao_arquivo');
	$sql->adOnde('plano_gestao_arquivo_id='.(int)$plano_gestao_arquivo_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela plano_gestao_arquivo!'.$bd->stderr(true));
	$sql->limpar();
	}


if ($salvaranexo){
	grava_arquivo_pg($pg_id, 'arquivo', 'Missao');
	}


if ($salvar){
	//checar se existe
	$sql->adTabela('plano_gestao2');
	$sql->adCampo('count(pg_id)');
	$sql->adOnde('pg_id='.(int)$pg_id);
	$existe=$sql->Resultado();
	$sql->limpar();
	
	if ($existe) {
		$sql->adTabela('plano_gestao2');
		$sql->adAtualizar('pg_missao', getParam($_REQUEST, 'pg_missao', ''));
		$sql->adAtualizar('pg_missao_esc_superior', getParam($_REQUEST, 'pg_missao_esc_superior', ''));
		$sql->adAtualizar('pg_missao_cor', getParam($_REQUEST, 'pg_missao_cor', 'c9deae'));
		$sql->adOnde('pg_id ='.(int)$pg_id);
		$sql->exec();
		$sql->limpar();
		}
	else {
		$sql->adTabela('plano_gestao2');
		$sql->adInserir('pg_missao', getParam($_REQUEST, 'pg_missao', ''));
		$sql->adInserir('pg_missao_esc_superior', getParam($_REQUEST, 'pg_missao_esc_superior', ''));
		$sql->adInserir('pg_missao_cor', getParam($_REQUEST, 'pg_missao_cor', 'c9deae'));
		$sql->adInserir('pg_id', (int)$pg_id);
		$sql->exec();
		$sql->limpar();
		}		
	}


$sql->adTabela('plano_gestao2');
$sql->adCampo('pg_missao, pg_missao_esc_superior, pg_missao_cor');
$sql->adOnde('pg_id='.(int)$pg_id);
$pg=$sql->Linha();
$sql->limpar();

echo '<table width="100%" >';
echo '<tr><td colspan=2 align="left"><h1>Missão</h1></td></tr>';

if ($editarPG || $pg['pg_missao']) echo '<tr><td colspan=2 align="left"><b>Missão d'.$config['genero_organizacao'].' '.$config['organizacao'].'</b></td></tr>';
if ($editarPG) echo '<tr><td colspan=2 align="left"><table width="810"><tr><td style="width:800px; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="pg_missao" id="pg_missao" >'.$pg['pg_missao'].'</textarea></td></tr></table></td></tr>';
elseif($pg['pg_missao'])  echo '<tr><td colspan=2 align="left"><table width="100%"><tr><td class="realce" width="100%">'.$pg['pg_missao'].'</td></tr></table></td></tr>';


if ($editarPG || $pg['pg_missao_esc_superior']) echo '<tr><td colspan=2 align="left"><b>Missão definida pelo escalão superior d'.$config['genero_organizacao'].' '.$config['organizacao'].'</b></td></tr>';
if ($editarPG) echo '<tr><td colspan=2 align="left"><table width="810"><tr><td style="width:800px; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="pg_missao_esc_superior" id="pg_missao_esc_superior" >'.$pg['pg_missao_esc_superior'].'</textarea></td></tr></table></td></tr>';
elseif($pg['pg_missao_esc_superior'])  echo '<tr><td colspan=2 align="left"><table width="100%"><tr><td class="realce" width="100%">'.$pg['pg_missao_esc_superior'].'</td></tr></table></td></tr>';

if ($editarPG) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cor', 'Cor selecionada dentre as 16 milhões possíveis. Pode-se escrever diretamente o hexadecinal na cor ou utilizar a interface que se abre ao clicar na caixa de inserção do valor.').'Cor:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input class="jscolor" name="pg_missao_cor" value="'.($pg['pg_missao_cor'] ? $pg['pg_missao_cor'] : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="6" maxlength="6" style="width:57px;" /></td></tr>';

//arquivo anexo
$sql->adTabela('plano_gestao_arquivo');
$sql->adCampo('plano_gestao_arquivo_id, plano_gestao_arquivo_usuario, plano_gestao_arquivo_data, plano_gestao_arquivo_ordem, plano_gestao_arquivo_plano_gestao,plano_gestao_arquivo_nome, plano_gestao_arquivo_endereco');
$sql->adOnde('plano_gestao_arquivo_plano_gestao='.(int)$pg_id);
$sql->adOnde('plano_gestao_arquivo_campo=\'Missao\'');
$sql->adOrdem('plano_gestao_arquivo_ordem ASC');
$arquivos=$sql->Lista();
$sql->limpar();
if ($arquivos && count($arquivos))echo '<tr><td colspan=2><b>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</b></td></tr>';
foreach ($arquivos as $arquivo) {
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Remetente</b></td><td>'.nome_funcao('', '', '', '',$arquivo['plano_gestao_arquivo_usuario']).'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($arquivo['plano_gestao_arquivo_data']).'</td></tr>';
	$dentro .= '</table>';
	$dentro .= '<br>Clique neste link para visualizar o arquivo no Navegador Web.';
	echo '<tr><td colspan=2><table cellpadding=0 cellspacing=0><tr>';
	if ($editarPG) {
			echo '<td style="white-space: nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['plano_gestao_arquivo_ordem'].'; env.plano_gestao_arquivo_id.value='.(int)$arquivo['plano_gestao_arquivo_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['plano_gestao_arquivo_ordem'].'; env.plano_gestao_arquivo_id.value='.(int)$arquivo['plano_gestao_arquivo_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['plano_gestao_arquivo_ordem'].'; env.plano_gestao_arquivo_id.value='.(int)$arquivo['plano_gestao_arquivo_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['plano_gestao_arquivo_ordem'].'; env.plano_gestao_arquivo_id.value='.(int)$arquivo['plano_gestao_arquivo_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			}
	echo '<td><a href="javascript:void(0);" onclick="javascript:pg_download('.(int)$arquivo['plano_gestao_arquivo_id'].');">'.dica($arquivo['plano_gestao_arquivo_nome'],$dentro).$arquivo['plano_gestao_arquivo_nome'].'</a></td>';
	if ($editarPG) echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este arquivo?\')) {env.excluiranexo.value=1; env.plano_gestao_arquivo_id.value='.(int)$arquivo['plano_gestao_arquivo_id'].'; env.submit()}">'.imagem('icones/remover.png', 'Excluir Arquivo', 'Clique neste ícone para excluir o arquivo.').'</a></td>';
	echo '</tr></table></td></tr>';
	}
if ($editarPG) echo'<tr><td colspan=2><table><tr><td><b>Arquivo:</b></td><td><input type="file" class="arquivo" name="arquivo" size="30"></td><td width="720">'.($editarPG ? botao('salvar arquivo', 'Salvar Arquivo', 'Clique neste botão para enviar arquivo e salvar o mesmo no sistema.','','env.salvaranexo.value=1; env.submit()') : '&nbsp').'</td></tr></table></td></tr>';

//qual voltar

if ($exibir['premiacao'] && $exibir['perfil']) $retorno='premiacoes';
else if ($exibir['programa'] && $exibir['perfil']) $retorno='programasacoes';
else if ($exibir['pessoal'] && $exibir['perfil']) $retorno='quadropessoal';
else if ($exibir['clientes'] && $exibir['perfil']) $retorno='clientes';
else if ($exibir['processos'] && $exibir['perfil']) $retorno='processos_produtos_servicos';
else if ($exibir['fornecedores'] && $exibir['perfil']) $retorno='fornecedores_insumos';
else if ($exibir['estrutura'] && $exibir['perfil']) $retorno='estrutura_organizacional';
else $retorno='';

echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.($retorno ? botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\''.$retorno.'\');') : '').'</td><td width="40%">&nbsp;</td><td>'.($editarPG ? botao('salvar', 'Salvar', 'Salvar os dados acima.','','env.salvar.value=1; env.submit();') : '&nbsp').'</td><td width="40%">&nbsp;</td><td>'.botao('próximo', 'Próximo', 'Ir para a próxima tela.','','carregar(\'visaofuturo\');').'</td></tr></table></td></tr>';
echo '</table>';
echo '</td></tr></table>';
?>

<script type="text/javascript">
function setCor(cor) {
	var f = document.env;
	if (cor) f.pg_missao_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pg_missao_cor.value;
	}
</script>