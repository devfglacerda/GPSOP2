<?php
global $config, $traducao;

$traducao=array_merge($traducao, array(
'tarefas_atrasadas_titulo'=>''.$config['tarefas'].' atrasadas',
'tarefas_atrasadas_descricao'=>'Ver lista d'.$config['genero_tarefa'].'s '.$config['tarefas'].' atrasadas',
'tarefas_atrasadas_dica'=>'Lista d'.$config['genero_tarefa'].'s '.$config['tarefas'].' que ocorrem em periodo definido.'
));
?>