<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

require_once (BASE_DIR.'/modulos/projetos/termo_abertura.class.php');

$Aplic->carregarCKEditorJS();

$projeto_abertura_id = intval(getParam($_REQUEST, 'projeto_abertura_id', 0));

$obj = new CTermoAbertura();
$obj->load($projeto_abertura_id);
$sql = new BDConsulta();



if (!permiteAcessarTermoAbertura($obj->projeto_abertura_acesso, $projeto_abertura_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');
$podeEditar=permiteEditarTermoAbertura($obj->projeto_abertura_acesso, $projeto_abertura_id);

$botoesTitulo = new CBlocoTitulo('Criação d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'anexo_projeto.png', $m, $m.'.'.$a);

$botoesTitulo->mostrar();


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="aprovar" value="1" />';
echo '<input type="hidden" id="projeto_abertura_id" name="projeto_abertura_id" value="'.$projeto_abertura_id.'" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_termo_abertura" />';


echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=1 width="100%" class="std">';

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->projeto_abertura_cor.'" colspan="2"><font color="'.melhorCor($obj->projeto_abertura_cor).'"><b>'.$obj->projeto_abertura_nome.'<b></font></td></tr>';


echo '<tr><td align="right" width=100>'.dica('Justificativa', 'Justificativa para a criação d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Justificativa:'.dicaF().'</td><td align=left><textarea data-gpweb-cmp="ckeditor" name="projeto_abertura_aprovacao" id="projeto_abertura_aprovacao"class="textarea"></textarea></td></tr>';


echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('criar', 'Criar', 'Criar '.$config['genero_projeto'].' '.$config['projeto'].'.','','salvar_termo_abertura();').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a criação d'.$config['genero_projeto'].' '.$config['projeto'].'.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();
?>

<script type="text/javascript">
function	salvar_termo_abertura(){
	var conteudo = CKEDITOR.instances['projeto_abertura_aprovacao'].getData().replace(/<[^>]*>/gi, '');
	if (!conteudo.length){
		alert('Escreva uma justificativa');
		env.projeto_abertura_aprovacao.focus();
		return;
		}
	if (confirm("Tem certeza que deseja criar <?php echo $config['genero_projeto'].' '.$config['projeto']?>?")) env.submit();
	}
</script>