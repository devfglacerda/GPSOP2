


<?php


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
$Aplic->carregarCalendarioJS();

global $mostrarCaixachecarEditar, $tarefas, $prioridades, $projeto_id, $dialogo ;
global $m, $a, $data, $mostrar_marcada, $mostra_projeto_completo, $mostraProjetosEspera, $mostrar_tarefa_dinamica, $mostrar_tarefa_baixa, $mostrar_sem_data, $usuario_id, $dept_id, $tarefa_tipo;
global $tarefa_ordenar_item1, $tarefa_ordenar_tipo1, $tarefa_ordenar_ordem1;
global $tarefa_ordenar_item2, $tarefa_ordenar_tipo2, $tarefa_ordenar_ordem2;
global $Aplic, $cal_sdf, $projeto_id, $designados;

global $Aplic, $cal_sdf, $ver_todos_projetos;
$mostrarNomeProjeto=nome_projeto($projeto_id);
$qnt=0;
$Aplic->carregarCalendarioJS();
$usuario_id = getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
$grupo=getParam($_REQUEST, 'grupo', 'designado');
$fazer_relatorio = getParam($_REQUEST, 'fazer_relatorio', 0);
$usar_periodo = getParam($_REQUEST, 'usar_periodo', 0);
$log_pdf = 1;
$dias = getParam($_REQUEST, 'dias', 30);
$data_inicio= getParam($_REQUEST, 'reg_data_inicio', '');
$data_fim= getParam($_REQUEST, 'reg_data_fim', '');
$fazer_relatorio = getParam($_REQUEST, 'fazer_relatorio', 0);
$periodo_valor = getParam($_REQUEST, 'pvalor', 1);



$tipo = getSisValor('TipoTarefa');
$status = getSisValor('StatusTarefa');

echo '<form name="frm_botoes" method="post">';
echo '<input type="hidden" name="m" value="depts" />';
echo '<input type="hidden" name="a" value="ver" />';
echo '<input type="hidden" name="tab" value="2" />';
echo '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />';


$Aplic->carregarCKEditorJS();

$botoesTitulo = new CBlocoTitulo('Resumo tarefas', 'arquivo.png', $m, "$m.$a");
$botoesTitulo->mostrar();



$sql = new BDConsulta; 
if (isset($_REQUEST['dept_id'])) $dept_id=getParam($_REQUEST, 'dept_id', 0);
if ($projeto_id) $sql->adOnde('t.tarefa_projeto = '.(int)$projeto_id);





$sql = new BDConsulta;
if (count($tarefas)<1){
$sql = new BDConsulta;
$sql->adTabela('tarefa_depts');
$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id = tarefa_depts.tarefa_id');
$sql->esqUnir('projetos', 'pr', 'tarefas.tarefa_projeto = pr.projeto_id');
$sql->esqUnir('tarefa_designados', 'ut', 'ut.tarefa_id = tarefas.tarefa_id');
		
$sql->adOnde('projeto_ativo = 1');
$sql->adOnde('projeto_template = 0');
$sql->adOnde('tarefa_percentagem < 100');
$sql->adOnde('tarefa_dinamica = 0');
if ($projeto_id) $sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
$sql->adCampo('projeto_nome, usuario_id, tarefa_projeto, tarefa_nome, tarefa_criador, tarefa_dept, tarefa_percentagem, tarefa_inicio, tarefa_fim, tarefa_status, tarefa_prioridade');
$sql->adOnde('departamento_id = '.(int)$dept_id);

	
$lista = $sql->Lista();

$sql->limpar();



	}



				
			

echo estiloTopoCaixa();
echo '<table width="100%" cellpadding=1 cellspacing=1 class="std">';


	

echo '<tr>


<th>Projeto</th>
<th>Tarefa</th>
<th>Designado</th>

<th>Porcetagem</th><th>Data Inicial</th><th>Data Final</th><th>Status</th></tr>';
foreach($lista as $linha) 

echo '<tr>
<td>'.link_projeto($linha['tarefa_projeto']).'</td>
<td>'.$linha['tarefa_nome'].'</td>
<td>'.link_usuario($linha['usuario_id']).'</td>

<td align="center">'.number_format($linha['tarefa_percentagem']).'%'.'</td>
<td align="center">'.retorna_data($linha['tarefa_inicio']).'</td>
<td align="center">'.retorna_data($linha['tarefa_fim']).'</td>
<td align="center">'.($linha['tarefa_status'] && isset($status[$linha['tarefa_status']]) ? $status[$linha['tarefa_status']] : '&nbsp;').'</td>
 


 

 </tr>';




echo '</table>';


?>

<script type="text/javascript">

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	

function setData( frm_nome, f_data ) {
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
	
function popUsuario(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, window.setUsuario, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, 'Usuário','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=usuario_id;
	document.getElementById('nome_usuario').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}
	
  var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "reg_data_inicio",
  	date :  <?php echo $data_inicio->format("%Y%m%d")?>,
  	selection: <?php echo $data_inicio->format("%Y%m%d")?>,
    onSelect: function(cal1) { 
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("reg_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal1.hide(); 
  	}
  });
  
	var cal2 = Calendario.setup({
		trigger : "f_btn2",
    inputField : "reg_data_fim",
		date : <?php echo $data_fim->format("%Y%m%d")?>,
		selection : <?php echo $data_fim->format("%Y%m%d")?>,
    onSelect : function(cal2) { 
    var date = cal2.selection.get();
    if (date){
      date = Calendario.intToDate(date);
      document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("reg_data_fim").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal2.hide(); 
  	}
  });
 
</script>