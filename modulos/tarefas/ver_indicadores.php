<?php

global $tarefa_id, $obj;
$ordenar=getParam($_REQUEST, 'ordenar', 'pratica_indicador_id');
$ordem=getParam($_REQUEST, 'ordem', '0');
$projeto_id=$obj->tarefa_projeto;


$ata_ativo=$Aplic->modulo_ativo('atas');

$swot_ativo=$Aplic->modulo_ativo('swot');

$operativo_ativo=$Aplic->modulo_ativo('operativo');

$problema_ativo=$Aplic->modulo_ativo('problema');

$agrupamento_ativo=$Aplic->modulo_ativo('agrupamento');

$patrocinador_ativo=$Aplic->modulo_ativo('patrocinadores');

$tr_ativo=$Aplic->modulo_ativo('tr');


$sql = new BDConsulta;
$sql->adTabela('pratica_indicador');
$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_unidade, pratica_indicador_acumulacao, pratica_indicador_acesso, pratica_indicador_nome, pratica_indicador_requisito_descricao, pratica_indicador_cor, pratica_indicador_sentido, pratica_indicador_responsavel, pratica_indicador_composicao, pratica_indicador_formula, pratica_indicador_formula_simples, pratica_indicador_checklist, pratica_indicador_campo_projeto, pratica_indicador_campo_tarefa, pratica_indicador_campo_acao, pratica_indicador_agrupar');
$sql->adOnde('pratica_indicador_tarefa = '.$tarefa_id);
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$indicadores = $sql->lista();
$sql->limpar();

$detalhe_projeto=1;

include_once BASE_DIR.'/modulos/praticas/indicadores_ver_idx.php';

?>