<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $config;
$nome_meses=array('01'=>'Janeiro', '02'=>'Fevereiro', '03'=>'Março', '04'=>'Abril', '05'=>'Maio', '06'=>'Junho', '07'=>'Julho', '08'=>'Agosto', '09'=>'Setembro', '10'=>'Outubro', '11'=>'Novembro', '12'=>'Dezembro');

require_once ($Aplic->getClasseBiblioteca('PEAR/Date'));
require_once ($Aplic->getClasseSistema('aplic'));
require_once $Aplic->getClasseSistema('libmail');
require_once $Aplic->getClasseSistema('data');
require_once ($Aplic->getClasseSistema('evento_recorrencia'));
require_once BASE_DIR.'/modulos/calendario/jornada_links.php';

class CCalendarioMes {
	public $este_mes;
	public $mesAnterior;
	public $mesProximo;
	public $anoAnterior;
	public $anoProximo;
	public $estiloTitulo;
	public $estiloPrincipal;
	public $chamarVolta;
	public $mostrarCabecalho;
	public $mostrarSetas;
	public $mostrarDias;
	public $mostrarSemana;
	public $clicarMes;
	public $mostrarEventos;
	public $diaFuncao;
	public $semanaFuncao;
	public $eventoFuncao;
	public $mostrarDiasIluminados;
	public $expediente;
	public $alocacao;

	public function __construct( $data = null) {
		$this->setData($data);
		$this->classes = array();
		$this->chamar_volta = '';
		$this->mostrarTitulo = true;
		$this->mostrarSetas = true;
		$this->mostrarDias = true;
		$this->mostrarSemana = true;
		$this->mostrarEventos = true;
		$this->mostrarDiasIluminados = true;
		$this->expediente = false;
		$this->estiloTitulo = '';
		$this->estiloPrincipal = '';
		$this->diaFuncao = '';
		$this->semanaFuncao = '';
		$this->eventos = array();
		$this->diasIluminados = array();
		}


	public function setData( $data = null) {
		global $Aplic;
		$this->esteMes = new CData($data);
		$d = $this->esteMes->getDay();
		$m = $this->esteMes->getMonth();
		$y = $this->esteMes->getYear();
		$this->anoAnterior = new CData($data);
		$this->anoAnterior->setYear($this->anoAnterior->getYear() - 1);
		$this->anoProximo = new CData($data);
		$this->anoProximo->setYear($this->anoProximo->getYear() + 1);
		setlocale(LC_TIME, $Aplic->usuario_linguagem);
		$data = Data_Calc::beginOfPrevMonth($d, $m, $y, '%Y%m%d');
		setlocale(LC_ALL, $Aplic->usuario_linguagem);
		$this->mesAnterior = new CData($data);
		setlocale(LC_TIME, $Aplic->usuario_linguagem);
		$data = Data_Calc::beginOfNextMonth($d, $m, $y, '%Y%m%d');
		setlocale(LC_ALL, $Aplic->usuario_linguagem);
		$this->mesProximo = new CData($data);
		}

	public function setEstilo( $titulo, $principal) {
		$this->estiloTitulo = $titulo;
		$this->estiloPrincipal = $principal;
		}

	public function setLinkFuncoes( $dia = '', $semana = '', $eventoFuncao='') {
		$this->diaFuncao = $dia;
		$this->semanaFuncao = $semana;
		$this->eventoFuncao = $eventoFuncao;
		}

public function setExpediente( $sim = '') {
		$this->expediente = $sim;
		}

public function setAlocacao( $sim = '') {
		$this->alocacao = $sim;
		}

	public function setCallback( $function) {
		$this->chamar_volta = $function;
		}

	public function setEventos( $e) {
		$this->eventos = $e;
		}

	public function setDiasIluminados( $hd) {
		$this->diasIluminados = $hd;
		}

	public function mostrar() {
		$s = '';
		if ($this->mostrarTitulo) $s .= $this->_desenharTitulo();
		$s .= '<table border=0 cellspacing=1 cellpadding=2 width="100%" class="'.$this->estiloPrincipal.'">';
		if ($this->mostrarDias) $s .= $this->_desenharDias();
		$s .= $this->_desenharPrincipal();
		$s .= '</table>';
		return $s;
		}


	public function _desenharTitulo() {
		global $Aplic, $m, $a, $localidade_tipo_caract, $estilo_interface;
		$nome_meses=array('01'=>'Janeiro', '02'=>'Fevereiro', '03'=>'Março', '04'=>'Abril', '05'=>'Maio', '06'=>'Junho', '07'=>'Julho', '08'=>'Agosto', '09'=>'Setembro', '10'=>'Outubro', '11'=>'Novembro', '12'=>'Dezembro');
		$base_dir = 'm='.$m.($a ? '&a='.$a : '').(isset($_REQUEST['dialogo']) ? '&dialogo=1' : '');
		$s = '<table border=0 cellspacing=0 cellpadding="3" width="100%" class="'.$this->estiloTitulo.'">';
		$s .= '<tr>';
		if ($this->mostrarSetas) {
			$href = $base_dir.'&data='.$this->mesAnterior->format('%Y%m%d').($this->chamar_volta ? '&chamar_volta='.$this->chamar_volta : '').((count($this->diasIluminados) > 0) ? '&uts='.chave($this->diasIluminados) : '');
			$s .= '<td align="left"><a href="javascript:void(0);" onclick="url_passar(0, \''.$href.'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif'), 'Mês Anterior', 'Clique neste ícone '.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif')).' para exibir o mês anterior.').'</a></td>';
			}
		$s .= '<td width="99%" align="center">';
		if ($this->clicarMes) {
			setlocale(LC_TIME, $Aplic->usuario_linguagem);
			$s .= dica($nome_meses[$this->esteMes->format('%m')].' de '.$this->esteMes->format('%Y'), 'Clique para exibir este mês.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&data='.$this->esteMes->format('%Y%m%d').'\');">';
			}
		setlocale(LC_TIME, $Aplic->usuario_linguagem);
		$s .= '<b>'.$nome_meses[$this->esteMes->format('%m')].' '.$this->esteMes->format('%Y') .'</b>'. (($this->clicarMes) ? dicaF().'</a>' : '');
		setlocale(LC_ALL, $Aplic->usuario_linguagem);
		$s .= '</td>';
		if ($this->mostrarSetas) {
			$href = ($base_dir.'&data='.$this->mesProximo->format('%Y%m%d').(($this->chamar_volta) ? ('&chamar_volta='.$this->chamar_volta) : '').((count($this->diasIluminados) > 0) ? ('&uts='.chave($this->diasIluminados)) : ''));
			$s .= '<td align="right"><a href="javascript:void(0);" onclick="url_passar(0, \''.$href.'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif'), 'Próximo Mês', 'Clique neste ícone '.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif')).' para exibir o próximo mês.').'</a></td>';
			}
		$s .= '</tr></table>';
		return $s;
		}


	public function _desenharDias() {
		global $Aplic, $localidade_tipo_caract, $config;
		setlocale(LC_TIME, $Aplic->usuario_linguagem);
		$semana = Data_Calc::getCalendarioSemana(null, null, null, '%a', (defined(localidade_PRIMEIRO_DIA) ? localidade_PRIMEIRO_DIA : 1));
		setlocale(LC_ALL, $Aplic->usuario_linguagem);
		$s = ($this->mostrarSemana ? '<td style="background-color:#f2f1f1;">&nbsp;</td>' : '');
		foreach ($semana as $dia) $s .= '<td width="14%" align="center" style="background-color:#f2f1f1;">'. dia_semana_curto($dia) .'</td>';
		return '<tr>'.$s.'</tr>';
		}

	public function _desenharPrincipal() {
		global $Aplic, $diasUteis,$config, $nome_meses, $cia_id, $usuario_id, $projeto_id, $recurso_id, $tarefa_id, $podeAdicionar;
		if (!$cia_id)$cia_id=$Aplic->getEstado('cia_id', $Aplic->usuario_cia);
		if (!isset($diasUteis)) $diasUteis=explode (',', $config['cal_dias_uteis']);
		$hoje = new CData();
		$hoje = $hoje->format('%Y%m%d%w');
		$data = $this->esteMes;
		$este_dia = intval($data->getDay());
		$este_mes = intval($data->getMonth());
		$este_ano = intval($data->getYear());
		setlocale(LC_TIME, $Aplic->usuario_linguagem);
		$cal = Data_Calc::getCalendarioMes($este_mes, $este_ano, '%Y%m%d%w', (defined(localidade_PRIMEIRO_DIA) ? localidade_PRIMEIRO_DIA : 1));
		setlocale(LC_ALL, $Aplic->usuario_linguagem);
		$html = '';

		$sql = new BDConsulta;

		$primeiraData=new CData($este_ano.'-'.$data->getMonth().'-01');
		$ultimaData=new CData($data->beginOfNextMonth());
		
		$expediente=array();
		getExpedienteHoras($primeiraData, $ultimaData, $expediente, $cia_id, $usuario_id, $projeto_id, $recurso_id, $tarefa_id);


		foreach ($cal as $semana) {
			$html .= '<tr>';
			$data = new CData(substr($semana[0],0,8));
			$titulo=$data->format('%U').'ª Semana - '.$nome_meses[$data->format('%m')]. ' de '.$data->format('%Y');
			if ($this->mostrarSemana) $html .= '<td class="semana" style="vertical-align:middle;" align="center">'.($this->diaFuncao ? dica($titulo, 'Clique neste ícone '.imagem('icones/ver.semana.gif').' para exibir esta semana.')."<a href=\"javascript:$this->semanaFuncao('".substr($semana[0],0,8)."')\">" : '').'<img src="'.acharImagem('ver.semana.gif').'" width="16" height="15" border=0 />'.($this->diaFuncao ? '</a>'.dicaF() : '').'</td>';
			foreach ($semana as $dia) {

				$este_dia = new CData($dia);
				$y = intval(substr($dia, 0, 4));
				$m = intval(substr($dia, 4, 2));
				$d = intval(substr($dia, 6, 2));
				$diadasemana = intval(substr($dia, 8, 1));
				$cdia = intval(substr($dia, 0, 8));
				$texto='';

				$horas=(isset($expediente[substr($dia, 0, 8)])? $expediente[substr($dia, 0, 8)] : 8);

				if (array_key_exists($cdia, $this->eventos) && $this->estiloPrincipal == 'minical') {
					$nr_tarefas = 0;
					$nr_eventos = 0;
					$nr_acoes = 0;
					$nr_atas = 0;
					$nr_atas_acoes = 0;
					$nr_problemas = 0;
					$nr_expedientes=0;
					$nr_sobrecarga=0;
					$nr_alocacao=0;
					$horas=0;
					$sobrecarga=0;


					foreach ($this->eventos[$cdia] as $registro) {
						if (isset($registro['tarefa']) && $registro['tarefa']) ++$nr_tarefas;
						elseif (isset($registro['acao']) && $registro['acao']) ++$nr_acoes;
						elseif (isset($registro['ata']) && $registro['ata']) ++$nr_atas;
						elseif (isset($registro['ata_acao']) && $registro['ata_acao']) ++$nr_atas_acoes;
						elseif (isset($registro['problema']) && $registro['problema']) ++$nr_problemas;
						elseif (isset($registro['alocacao']) && $registro['alocacao']) ++$nr_alocacao;
						elseif (isset($registro['expediente']) && $registro['expediente']) {
							++$nr_expedientes;
							$horas+=$registro['horas'];
							}
						elseif (isset($registro['sobrecarga']) && $registro['sobrecarga']) {
							++$nr_sobrecarga;
							$sobrecarga+=$registro['percentagem'];
							}
						else ++$nr_eventos;
						$texto.=$registro['texto_mini'];
						}
					if ($dia == $hoje) $classe = 'hoje';


					$qnt_multiplo=0;
					//checar se multiplo
					if ($nr_eventos) $qnt_multiplo++;
					if ($nr_tarefas) $qnt_multiplo++;
					if ($nr_acoes) $qnt_multiplo++;
					if ($nr_atas) $qnt_multiplo++;
					if ($nr_atas_acoes) $qnt_multiplo++;
					if ($nr_problemas) $qnt_multiplo++;

					if ($qnt_multiplo > 1) {
						$classe = 'multiplo';
						$titulo='Múltiplos Objetos';
						}
					elseif ($nr_acoes) {
						$classe = 'acao';
						$titulo='Planos de Ação';
						}
					elseif ($nr_atas) {
						$classe = 'ata';
						$titulo='Atas de Reunião';
						}	
					elseif ($nr_atas_acoes) {
						$classe = 'ata_acao';
						$titulo='Ações de Atas de Reunião';
						}		
					elseif ($nr_problemas) {
						$classe = 'problema';
						$titulo=ucfirst($config['problemas']);
						}		
					elseif ($nr_tarefas) {
						$classe = 'tarefa';
						$titulo=ucfirst($config['tarefas']);
						}
					elseif ($nr_eventos) {
						$classe = 'evento';
						$titulo='Eventos';
						}

					elseif ($nr_expedientes) {
						$integral=($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
						if ($integral==$registro['horas']) $classe = 'expediente_normal';
						elseif ((($integral/2)<=$registro['horas']) && ($registro['horas']<=($integral*0.75))) $classe = 'expediente_meio';
						elseif ($registro['horas']< 0.1) $classe = 'expediente_sem';
						else $classe = 'expediente_outros';
						}
					elseif ($nr_sobrecarga) {
						if ($registro['percentagem'] > 0 && $registro['percentagem'] < 25) $classe = 'sobrecarga_25';
						elseif ($registro['percentagem'] >= 25 && $registro['percentagem'] < 50) $classe = 'sobrecarga_50';
						elseif ($registro['percentagem'] >= 50 && $registro['percentagem'] < 75) $classe = 'sobrecarga_75';
						elseif ($registro['percentagem'] >= 75 && $registro['percentagem'] < 95) $classe = 'sobrecarga_95';
						elseif ($registro['percentagem'] >= 95 && $registro['percentagem'] <= 100) $classe = 'sobrecarga_100';
						elseif ($registro['percentagem'] > 100) $classe = 'sobrecarga_acima100';
						else $classe = '';
						}
					elseif ($nr_alocacao) {
						if ($registro['percentagem'] > 0 && $registro['percentagem'] < 25) $classe = 'alocacao_25';
						elseif ($registro['percentagem'] >= 25 && $registro['percentagem'] < 50) $classe = 'alocacao_50';
						elseif ($registro['percentagem'] >= 50 && $registro['percentagem'] < 75) $classe = 'alocacao_75';
						elseif ($registro['percentagem'] >= 75 && $registro['percentagem'] < 95) $classe = 'alocacao_95';
						elseif ($registro['percentagem'] >= 95 && $registro['percentagem'] <= 100) $classe = 'alocacao_100';
						elseif ($registro['percentagem'] > 100) $classe = 'alocacao_acima100';
						else $classe = '';
						}
					}
				elseif ($m != $este_mes) $classe = 'vazio';
				elseif ($dia == $hoje)	$classe = 'hoje';
				
				elseif ($horas==4) $classe='meio_expediente';
				elseif ($horas==0) $classe='fim_semana';
				elseif ($horas==8) $classe='dia';
				else $classe='expediente_alternativo';
				
				
				$dia = substr($dia, 0, 8);
				$html .= '<td class="'.$classe.'"'.(($this->mostrarDiasIluminados && isset($this->diasIluminados[$dia])) ? ' style="border: 1px solid '.$this->diasIluminados[$dia].'"' : '').($podeAdicionar ? ' ondblclick="'.$this->eventoFuncao.'(\''.$dia.'\',\''.$este_dia->format('%d/%m/%Y').'\')' : '').'">';
				if ($m == $este_mes) {
					if ($this->expediente) $html .= ($texto ? dica('Expediente no dia '.$d.' de '.strtolower($nome_meses[$this->esteMes->format('%m')]).' de '.$this->esteMes->format('%Y'), '<table cellspacing=0 cellpadding=0>'.$texto.'</table>').$d.dicaF() : $d);
					elseif ($this->alocacao) $html .= ($texto ? dica('Alocação do recurso no dia '.$d.' de '.strtolower($nome_meses[$this->esteMes->format('%m')]).' de '.$this->esteMes->format('%Y'), '<table cellspacing=0 cellpadding=0>'.$texto.'</table>').$d.dicaF() : $d);
					elseif ($this->diaFuncao) $html .= "<a href=\"javascript:$this->diaFuncao('$dia','".$este_dia->format('%d/%m/%Y')."')\" class=\"$classe\">".($texto ? dica($titulo.' no dia '.$d.' de '.strtolower($nome_meses[$this->esteMes->format('%m')]).' de '.$this->esteMes->format('%Y'), '<table cellspacing=0 cellpadding=0>'.$texto.'</table>').$d.dicaF() : $d).'</a>';
					else $html .= $d;
					if ($this->mostrarEventos) $html .= $this->_desenharEventos(substr($dia, 0, 8));
					}

				$html .= '</td>';
				}
			$html .= '</tr>';
			}
		return $html;
		}



	public function _desenharSemana( $dataObj) {
		$href = "javascript:$this->semanaFuncao(".$dataObj->getTempostamp().',\''.$dataObj->toString().'\')';
		return '<td class="semana">'.($this->diaFuncao ? '<a href="'.$href.'">' : '').dica('Semana', 'Clique neste ícone '.imagem('icones/ver.semana.gif').' para exibir esta semana.').'<img src="'.acharImagem('ver.semana.gif').'" width="16" height="15" border=0 />'.dicaF().'</a>'.($this->diaFuncao ? '</a>' : '').'</td>';
		}

	public function _desenharEventos( $dia) {
		if (!isset($this->eventos[$dia]) || $this->estiloPrincipal == 'minical') return '';
		$eventos = $this->eventos[$dia];
		$s = '<br><table cellpadding=0 cellspacing=0 align="left">';
		foreach ($eventos as $e) $s .= $e['texto'];
		$s.='</table>';
		return $s;
		}



	}
/********************************************************************************************

Classe CEvento para manipulação dos eventos.

********************************************************************************************/
class CEvento extends CAplicObjeto {
	public $evento_id = null;
	public $evento_cia = null;
	public $evento_dept = null;
	public $evento_titulo = null;
	public $evento_inicio = null;
	public $evento_fim = null;
	public $evento_duracao = null;
	public $evento_descricao = null;
	public $evento_oque = null;
	public $evento_onde = null;
	public $evento_quando = null;
	public $evento_como = null;
	public $evento_porque = null;
	public $evento_quanto = null;
	public $evento_quem = null;
	public $evento_nr_recorrencias = null;
	public $evento_recorrencias = null;
	public $evento_lembrar = null;
	public $evento_icone = null;
	public $evento_dono = null;
	public $evento_projeto = null;
	public $evento_tarefa = null;
	public $evento_tipo = null;
	public $evento_notificar = null;
	public $evento_diautil = null;
	public $evento_acesso = null;
	public $evento_cor = null;
	public $evento_pratica = null;
	public $evento_acao = null;
	public $evento_indicador = null;
	public $evento_perspectiva = null;
	public $evento_tema = null;
	public $evento_objetivo = null;
	public $evento_estrategia = null;
	public $evento_calendario = null;
	public $evento_fator = null;
	public $evento_meta = null;
	public $evento_canvas = null;
	public $evento_recorrencia_pai = null;
	public $evento_principal_indicador = null;
	public $evento_ativo = null;
	
	public function __construct() {
		parent::__construct('eventos', 'evento_id');
		}

	public function check() {
		$this->evento_tipo = intval($this->evento_tipo);
		$this->evento_diautil = intval($this->evento_diautil);
		return null;
		}

	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$this->arrumarTodos();
		$msg = $this->check();
		if ($msg)	return get_class($this).'::checagem para armazenar falhou - '.$msg;

		$sql = new BDConsulta;
		if ($this->evento_id) {
			$ret = $sql->atualizarObjeto('eventos', $this, 'evento_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('eventos', $this, 'evento_id');
			$sql->limpar();
			}

		if ($Aplic->profissional && isset($_REQUEST['uuid']) && $_REQUEST['uuid']){
			$sql->adTabela('evento_gestao');
			$sql->adAtualizar('evento_gestao_evento', (int)$this->evento_id);
			$sql->adAtualizar('evento_gestao_uuid', null);
			$sql->adOnde('evento_gestao_uuid=\''.getParam($_REQUEST, 'uuid', null).'\'');
			$sql->exec();
			$sql->limpar();
			}


		$evento_usuarios=getParam($_REQUEST, 'evento_usuarios', null);
		$evento_usuarios=explode(',', $evento_usuarios);
		$sql->setExcluir('evento_usuario');
		$sql->adOnde('evento_usuario_evento = '.$this->evento_id);
		$sql->exec();
		$sql->limpar();
		foreach($evento_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('evento_usuario');
				$sql->adInserir('evento_usuario_evento', $this->evento_id);
				$sql->adInserir('evento_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'evento_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('evento_dept');
		$sql->adOnde('evento_dept_evento = '.$this->evento_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('evento_dept');
				$sql->adInserir('evento_dept_evento', $this->evento_id);
				$sql->adInserir('evento_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('evento_cia');
			$sql->adOnde('evento_cia_evento='.(int)$this->evento_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'evento_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('evento_cia');
						$sql->adInserir('evento_cia_evento', $this->evento_id);
						$sql->adInserir('evento_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}
			
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('evento', $this->evento_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->evento_id);	

		}

	public function getTarefaNome() {
		$tarefaNome = '';
		if (!$this->evento_tarefa)	return $tarefaNome;
		$this->_consulta->limpar();
		$this->_consulta->adTabela('tarefas');
		$this->_consulta->adCampo('tarefa_nome');
		$this->_consulta->adOnde('tarefa_id = '.(int)$this->evento_tarefa);
		$tarefaNome =$this->_consulta->Resultado();
		$this->_consulta->limpar();
		return $tarefaNome;
		}

	public function getAcaoNome() {
		$acaoNome = '';
		if (!$this->evento_acao)	return $acaoNome;
		$this->_consulta->limpar();
		$this->_consulta->adTabela('plano_acao');
		$this->_consulta->adCampo('plano_acao_nome');
		$this->_consulta->adOnde('plano_acao_id = '.(int)$this->evento_acao);
		$acaoNome =$this->_consulta->Resultado();
		$this->_consulta->limpar();
		return $acaoNome;
		}


	public function excluir( $oid = NULL) {
		global $Aplic,$config;
		$excluido = parent::excluir($this->evento_id);
		if (empty($excluido)) {
			$sql = new BDConsulta;
			
			$sql->setExcluir('evento_recorrencia');
			$sql->adOnde('recorrencia_id_origem = '.$this->evento_id);
			$sql->adOnde('recorrencia_modulo = \'calendario\'');
			$excluido = ((!$sql->exec()) ? 'Não foi possível eliminar da tabela evento_recorrencia.'.db_error():null);
			$sql->limpar();
			}
		if ($Aplic->getEstado('evento_id', null)==$this->evento_id) $Aplic->setEstado('evento_id', null);	
		return $excluido;
		}

	public static function getEventoParaPeriodo($data_inicio, $data_fim, $evento_filtro='', $usuario_id=null, $envolvimento=null, $cia_id=null, $dept_id=null,
	   $projeto_id=null,
	   $tarefa_id=null,
	   $pg_perspectiva_id=null,
	   $tema_id=null,
	   $objetivo_id=null,
	   $fator_id=null,
	   $pg_estrategia_id=null,
	   $pg_meta_id=null,
	   $pratica_id=null,
	   $pratica_indicador_id=null,
	   $plano_acao_id=null,
	   $canvas_id=null,
	   $risco_id=null,
	   $risco_resposta_id=null,
	   $calendario_id=null,
	   $monitoramento_id=null,
	   $ata_id=null,
	   $mswot_id=null,
	   $swot_id=null,
	   $operativo_id=null,
	   $instrumento_id=null,
	   $recurso_id=null,
	   $problema_id=null,
	   $demanda_id=null,
	   $programa_id=null,
	   $licao_id=null,
	   $evento_id=null,
	   $link_id=null,
	   $avaliacao_id=null,
	   $tgn_id=null,
	   $brainstorm_id=null,
	   $gut_id=null,
	   $causa_efeito_id=null,
	   $arquivo_id=null,
	   $forum_id=null,
	   $checklist_id=null,
	   $agenda_id=null,
	   $agrupamento_id=null,
	   $patrocinador_id=null,
	   $template_id=null,
	   $painel_id=null,
	   $painel_odometro_id=null,
	   $painel_composicao_id=null,
	   $tr_id=null,
	   $me_id=null,
	   $plano_acao_item_id=null,
	   $beneficio_id=null,
	   $painel_slideshow_id=null,
	   $projeto_viabilidade_id=null,
	   $projeto_abertura_id=null,
	   $pg_id=null
		) {
		global $Aplic;

		$db_inicio=($data_inicio ? $data_inicio->format('%Y-%m-%d %H:%M:%S') : null);
		$db_fim=($data_fim ? $data_fim->format('%Y-%m-%d %H:%M:%S') : null);

		$nada_selecionado=!(
			$projeto_id || 
			$tarefa_id || 
			$pg_perspectiva_id || 
			$tema_id || 
			$objetivo_id || 
			$fator_id || 
			$pg_estrategia_id || 
			$pg_meta_id || 
			$pratica_id || 
			$pratica_indicador_id || 
			$plano_acao_id || 
			$canvas_id || 
			$risco_id || 
			$risco_resposta_id || 
			$calendario_id || 
			$monitoramento_id || 
			$ata_id || 
			$mswot_id || 
			$swot_id || 
			$operativo_id || 
			$instrumento_id || 
			$recurso_id || 
			$problema_id || 
			$demanda_id || 
			$programa_id || 
			$licao_id || 
			$evento_id || 
			$link_id || 
			$avaliacao_id || 
			$tgn_id || 
			$brainstorm_id || 
			$gut_id || 
			$causa_efeito_id || 
			$arquivo_id || 
			$forum_id || 
			$checklist_id || 
			$agenda_id || 
			$agrupamento_id || 
			$patrocinador_id || 
			$template_id || 
			$painel_id || 
			$painel_odometro_id || 
			$painel_composicao_id || 
			$tr_id || 
			$me_id || 
			$plano_acao_item_id || 
			$beneficio_id || 
			$painel_slideshow_id || 
			$projeto_viabilidade_id || 
			$projeto_abertura_id || 
			$pg_id
			);


		$sql = new BDConsulta;
		$sql->adTabela('eventos', 'e');

		$sql->esqUnir('evento_gestao','evento_gestao','evento_gestao_evento = e.evento_id');
		if ($tarefa_id) $sql->adOnde('evento_gestao_tarefa IN ('.$tarefa_id.')');
		elseif ($projeto_id){
			$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=evento_gestao_tarefa');
			$sql->adOnde('evento_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
			}
		elseif ($pg_perspectiva_id) $sql->adOnde('evento_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
		elseif ($tema_id) $sql->adOnde('evento_gestao_tema IN ('.$tema_id.')');
		elseif ($objetivo_id) $sql->adOnde('evento_gestao_objetivo IN ('.$objetivo_id.')');
		elseif ($fator_id) $sql->adOnde('evento_gestao_fator IN ('.$fator_id.')');
		elseif ($pg_estrategia_id) $sql->adOnde('evento_gestao_estrategia IN ('.$pg_estrategia_id.')');
		elseif ($pg_meta_id) $sql->adOnde('evento_gestao_meta IN ('.$pg_meta_id.')');
		elseif ($pratica_id) $sql->adOnde('evento_gestao_pratica IN ('.$pratica_id.')');
		elseif ($pratica_indicador_id) $sql->adOnde('evento_gestao_indicador IN ('.$pratica_indicador_id.')');
		elseif ($plano_acao_id) $sql->adOnde('evento_gestao_acao IN ('.$plano_acao_id.')');
		elseif ($canvas_id) $sql->adOnde('evento_gestao_canvas IN ('.$canvas_id.')');
		elseif ($risco_id) $sql->adOnde('evento_gestao_risco IN ('.$risco_id.')');
		elseif ($risco_resposta_id) $sql->adOnde('evento_gestao_risco_resposta IN ('.$risco_resposta_id.')');
		elseif ($calendario_id) $sql->adOnde('evento_gestao_calendario IN ('.$calendario_id.')');
		elseif ($monitoramento_id) $sql->adOnde('evento_gestao_monitoramento IN ('.$monitoramento_id.')');
		elseif ($ata_id) $sql->adOnde('evento_gestao_ata IN ('.$ata_id.')');
		elseif ($mswot_id) $sql->adOnde('evento_gestao_mswot IN ('.$mswot_id.')');
		elseif ($swot_id) $sql->adOnde('evento_gestao_swot IN ('.$swot_id.')');
		elseif ($operativo_id) $sql->adOnde('evento_gestao_operativo IN ('.$operativo_id.')');
		elseif ($instrumento_id) $sql->adOnde('evento_gestao_instrumento IN ('.$instrumento_id.')');
		elseif ($recurso_id) $sql->adOnde('evento_gestao_recurso IN ('.$recurso_id.')');
		elseif ($problema_id) $sql->adOnde('evento_gestao_problema IN ('.$problema_id.')');
		elseif ($demanda_id) $sql->adOnde('evento_gestao_demanda IN ('.$demanda_id.')');
		elseif ($programa_id) $sql->adOnde('evento_gestao_programa IN ('.$programa_id.')');
		elseif ($licao_id) $sql->adOnde('evento_gestao_licao IN ('.$licao_id.')');
		
		elseif ($evento_id) $sql->adOnde('evento_gestao_semelhante IN ('.$evento_id.')');
		
		elseif ($link_id) $sql->adOnde('evento_gestao_link IN ('.$link_id.')');
		elseif ($avaliacao_id) $sql->adOnde('evento_gestao_avaliacao IN ('.$avaliacao_id.')');
		elseif ($tgn_id) $sql->adOnde('evento_gestao_tgn IN ('.$tgn_id.')');
		elseif ($brainstorm_id) $sql->adOnde('evento_gestao_brainstorm IN ('.$brainstorm_id.')');
		elseif ($gut_id) $sql->adOnde('evento_gestao_gut IN ('.$gut_id.')');
		elseif ($causa_efeito_id) $sql->adOnde('evento_gestao_causa_efeito IN ('.$causa_efeito_id.')');
		elseif ($arquivo_id) $sql->adOnde('evento_gestao_arquivo IN ('.$arquivo_id.')');
		elseif ($forum_id) $sql->adOnde('evento_gestao_forum IN ('.$forum_id.')');
		elseif ($checklist_id) $sql->adOnde('evento_gestao_checklist IN ('.$checklist_id.')');
		elseif ($agenda_id) $sql->adOnde('evento_gestao_agenda IN ('.$agenda_id.')');
		elseif ($agrupamento_id) $sql->adOnde('evento_gestao_agrupamento IN ('.$agrupamento_id.')');
		elseif ($patrocinador_id) $sql->adOnde('evento_gestao_patrocinador IN ('.$patrocinador_id.')');
		elseif ($template_id) $sql->adOnde('evento_gestao_template IN ('.$template_id.')');
		elseif ($painel_id) $sql->adOnde('evento_gestao_painel IN ('.$painel_id.')');
		elseif ($painel_odometro_id) $sql->adOnde('evento_gestao_painel_odometro IN ('.$painel_odometro_id.')');
		elseif ($painel_composicao_id) $sql->adOnde('evento_gestao_painel_composicao IN ('.$painel_composicao_id.')');
		elseif ($tr_id) $sql->adOnde('evento_gestao_tr IN ('.$tr_id.')');
		elseif ($me_id) $sql->adOnde('evento_gestao_me IN ('.$me_id.')');
		elseif ($plano_acao_item_id) $sql->adOnde('evento_gestao_acao_item IN ('.$plano_acao_item_id.')');
		elseif ($beneficio_id) $sql->adOnde('evento_gestao_beneficio IN ('.$beneficio_id.')');
		elseif ($painel_slideshow_id) $sql->adOnde('evento_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
		elseif ($projeto_viabilidade_id) $sql->adOnde('evento_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
		elseif ($projeto_abertura_id) $sql->adOnde('evento_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
		elseif ($pg_id) $sql->adOnde('evento_gestao_plano_gestao IN ('.$pg_id.')');

		

		if ($evento_filtro=='todos'){
			if ($dept_id) {
				$sql->esqUnir('evento_dept','evento_dept', 'evento_dept_evento=e.evento_id');
				$sql->adOnde('evento_dept_dept IN ('.$dept_id.') OR e.evento_dept IN ('.$dept_id.')');
				}
			elseif (!$envolvimento && $Aplic->profissional && $cia_id) {
				$sql->esqUnir('evento_cia', 'evento_cia', 'e.evento_id=evento_cia_evento');
				$sql->adOnde('evento_cia IN ('.$cia_id.') OR evento_cia_cia IN ('.$cia_id.')');
				}
			elseif ($cia_id) $sql->adOnde('evento_cia='.(int)$cia_id);
			}
		switch ($evento_filtro) {
			case 'meu':
				$sql->esqUnir('evento_participante', 'evento_participante', 'evento_participante_evento = e.evento_id');
				$sql->adOnde('evento_dono IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).') OR evento_participante_usuario IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).')');
				break;
			case 'dono':
				$sql->adOnde('evento_dono IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).')');
				break;
			case 'todos':
				if ($usuario_id){
					$sql->esqUnir('evento_participante', 'evento_participante', 'evento_participante_evento = e.evento_id');
					$sql->adOnde('(evento_dono IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).') OR (evento_participante_usuario IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).') AND (evento_participante_aceito=1 || evento_participante_aceito=0)))');
					}
				break;
			case 'todos_aceitos':
				$sql->esqUnir('evento_participante', 'evento_participante', 'evento_participante_evento = e.evento_id');
				$sql->adOnde('evento_participante_usuario IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).') AND evento_participante_aceito=1');
				break;
			case 'todos_pendentes':
				$sql->esqUnir('evento_participante', 'evento_participante', 'evento_participante_evento = e.evento_id');
				$sql->adOnde('evento_participante_usuario IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).') AND evento_participante_aceito=0');
				break;
			case 'todos_recusados':
				$sql->esqUnir('evento_participante', 'evento_participante', 'evento_participante_evento = e.evento_id');
				$sql->adOnde('evento_participante_usuario IN ('.($usuario_id ? $usuario_id : $Aplic->usuario_lista_grupo).') AND evento_participante_aceito=-1');
				break;
			}
		if ($db_inicio && $db_fim) $sql->adOnde('(evento_inicio <= \''.$db_fim.'\' AND evento_fim >= \''.$db_inicio. '\') OR evento_inicio BETWEEN \''.$db_inicio. '\' AND \''.$db_fim.'\'');
		elseif($db_inicio) $sql->adOnde('evento_inicio >= \''.$db_inicio.'\'');
		elseif($db_fim)  $sql->adOnde('evento_fim <= \''.$db_fim.'\'');
		
		$sql->adCampo('e.evento_id, e.evento_acesso, e.evento_titulo, e.evento_inicio, e.evento_fim, e.evento_descricao, e.evento_url, e.evento_nr_recorrencias, e.evento_recorrencias, e.evento_lembrar, e.evento_icone, e.evento_dono, e.evento_projeto, e.evento_tarefa, e.evento_tipo, e.evento_diautil, e.evento_notificar, e.evento_localizacao, e.evento_cor');
		$sql->adOrdem('e.evento_inicio, e.evento_fim ASC');
		$sql->adGrupo('evento_id');
		//ver3($sql);
		$listaEvento = $sql->Lista();
		$sql->limpar();
		return $listaEvento;
		}

	public function getDesignado( $tipo='') {
		global $config;
		$sql = new BDConsulta;
		$sql->adTabela('evento_participante');
		$sql->esqUnir('usuarios', 'usuarios','evento_participante_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'usuario_contato = contato_id');
		$sql->adCampo('usuarios.usuario_id');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome');
		$sql->adOnde('evento_participante_evento = '.(int)$this->evento_id);
		if ($tipo=='nao_recusou') $sql->adOnde('evento_participante_aceito != -1');
		elseif ($tipo=='recusou') $sql->adOnde('evento_participante_aceito = -1');
		elseif ($tipo=='aceitou') $sql->adOnde('evento_participante_aceito = 1');
		elseif ($tipo=='nao_decidiu') $sql->adOnde('evento_participante_aceito = 0');
		$sql->adOrdem('contato_posto_valor, contato_nomeguerra');
		$designado = $sql->listaVetorChave('usuario_id','nome');
		$sql->limpar();
		return $designado;
	}


	public function atualizarDesignados( $designado, $porcentagem) {
		global $Aplic;
		require_once BASE_DIR.'/modulos/tarefas/funcoes.php';
		$sql = new BDConsulta;
		$sql->setExcluir('evento_participante');
		$sql->adOnde('evento_participante_evento = '.(int)$this->evento_id);
		if (implode(',',$designado)) $sql->adOnde('evento_participante_usuario NOT IN('.implode(',',$designado).')');
		$sql->exec();
		$sql->limpar();
		if (is_array($designado) && count($designado)) {
			foreach ($designado as $chave => $usuario_id) {
				if ($usuario_id) {
					//checar se já foi inserido
					$sql->adTabela('evento_participante');
					$sql->adOnde('evento_participante_evento = '.(int)$this->evento_id);
					$sql->adOnde('evento_participante_usuario = '.(int)$usuario_id);
					$sql->adCampo('evento_participante_usuario');
					$ja_tem=$sql->Resultado();
					$sql->limpar();

					$sql->adTabela('usuarios');
					$sql->esqUnir('contatos','contatos','contato_id=usuario_contato');
					$sql->adOnde('usuario_id = '.$usuario_id);
					$sql->adCampo('contato_cia');
					$cia_id=$sql->Resultado();
					$sql->limpar();
					$duracao=horas_periodo($this->evento_inicio, $this->evento_fim, $cia_id, $usuario_id);

					if (!$ja_tem){
						$sql->adTabela('evento_participante');
						$sql->adInserir('evento_participante_evento', $this->evento_id);
						$sql->adInserir('evento_participante_usuario', $usuario_id);
						$sql->adInserir('evento_participante_duracao', $duracao);
						$sql->adInserir('evento_participante_percentual', (isset($porcentagem[$chave]) ? $porcentagem[$chave] : 100));
						if ($usuario_id==$this->evento_dono) {
							$sql->adInserir('evento_participante_aceito', 1);
							$sql->adInserir('evento_participante_data', date('Y-m-d H:i:s'));
							}
						$sql->exec();
						$sql->limpar();
						}
					else{
						}
					}
				}
			if ($msg = db_error()) $Aplic->setMsg($msg, UI_MSG_ERRO);
			}
		}

	public function atualizarDuracao( $designado) {
		global $Aplic;
		require_once BASE_DIR.'/modulos/tarefas/funcoes.php';
		$sql = new BDConsulta;
		if (is_array($designado) && count($designado)) {
			foreach ($designado as $usuario_id) {
				if ($usuario_id) {
					$sql->adTabela('usuarios');
					$sql->esqUnir('contatos','contatos','contato_id=usuario_contato');
					$sql->adOnde('usuario_id = '.$usuario_id);
					$sql->adCampo('contato_cia');
					$cia_id=$sql->Resultado();
					$sql->limpar();
					$duracao=horas_periodo($this->evento_inicio, $this->evento_fim, $cia_id, $usuario_id);
					$sql->adTabela('evento_participante');
					$sql->adAtualizar('evento_participante_duracao', $duracao);
					$sql->adOnde('evento_participante_evento='.(int)$this->evento_id);
					$sql->adOnde('evento_participante_usuario='.(int)$usuario_id);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		}

	public function notificar(
			$email_texto=null, 
			$nao_eh_novo=null, 
			$vetor_notificar=array(), 
			$lista_designados_antigo=array(), 
			$email_contatos_extra=null, 
			$email_extras=null, 
			$lista_convidados_antigo=array()){
		global $Aplic, $config, $localidade_tipo_caract;
		require_once ($Aplic->getClasseSistema('libmail'));
		$sql = new BDConsulta;

		$usuarios=array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();
		$usuarios5=array();
		$usuarios6=array();
		$usuarios7=array();

		if (isset($vetor_notificar['email_responsavel'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->esqUnir('eventos', 'eventos', 'evento_dono = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('evento_id='.(int)$this->evento_id);
			$usuarios1=$sql->Lista();
			$sql->limpar();
			}

		if (isset($vetor_notificar['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->esqUnir('evento_usuario', 'evento_usuario', 'evento_usuario_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('evento_usuario_evento='.(int)$this->evento_id);
			$usuarios2=$sql->Lista();
			$sql->limpar();
			}
	
		
		if (isset($vetor_notificar['email_designados_novos'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->esqUnir('evento_usuario', 'evento_usuario', 'evento_usuario_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('evento_usuario_evento='.(int)$this->evento_id);
			if (count($lista_designados_antigo)) $sql->adOnde('evento_usuario_usuario NOT IN ('.implode(',', $lista_designados_antigo).')');
			$usuarios3=$sql->Lista();
			$sql->limpar();
			}
	
		if (isset($vetor_notificar['email_contatos_extra']) && $email_contatos_extra){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$email_contatos_extra.')');
			$usuarios4=$sql->Lista();
			$sql->limpar();
			}

		if (isset($vetor_notificar['email_extras']) && $email_extras){
			$extras=explode(',',$email_extras);
			foreach($extras as $chave => $valor) $usuarios5[]=array('usuario_id' => 0, 'nome_usuario' =>'', 'contato_email'=> $valor);
			}
			
		if (isset($vetor_notificar['email_convidados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->esqUnir('evento_participante', 'evento_participante', 'evento_participante_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('evento_participante_evento='.(int)$this->evento_id);
			$usuarios6=$sql->Lista();
			$sql->limpar();
			}
			
		if (isset($vetor_notificar['email_convidados_novos'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->esqUnir('evento_participante', 'evento_participante', 'evento_participante_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('evento_participante_evento='.(int)$this->evento_id);
			if (count($lista_convidados_antigo)) $sql->adOnde('evento_participante_usuario NOT IN ('.implode(',', $lista_convidados_antigo).')');
			$usuarios7=$sql->Lista();
			$sql->limpar();
			}	

		$usuarios = array_merge((array)$usuarios1, (array)$usuarios2);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios3);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios4);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios5);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios6);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios7);




		$usado_usuario=array();
		$usado_email=array();


		if (isset($vetor_notificar['excluir']) && $vetor_notificar['excluir']) $tipo='excluido';
		elseif ($nao_eh_novo) $tipo='atualizado';
		else $tipo='incluido';
		
		foreach($usuarios as $usuario){

			if (!((isset($usado_usuario[$usuario['usuario_id']]) && $usado_usuario[$usuario['usuario_id']]) || (isset($usado_email[$usuario['contato_email']]) && $usado_email[$usuario['contato_email']]))){
				if ($usuario['usuario_id']) $usado_usuario[$usuario['usuario_id']]=1;
				if ($usuario['contato_email']) $usado_email[$usuario['contato_email']]=1;
				$email = new Mail;
        $email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

				if ($tipo == 'excluido') {
					$email->Assunto('Excluído evento', $localidade_tipo_caract);
					$titulo='Excluído evento';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado evento', $localidade_tipo_caract);
					$titulo='Atualizado evento';
					}
				else {
					$email->Assunto('Inserido evento', $localidade_tipo_caract);
					$titulo='Inserido evento';
					}

				if ($tipo=='atualizado') $corpo = 'Atualizado evento: '.$this->evento_titulo.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído evento: '.$this->evento_titulo.'<br>';
				else $corpo = 'Inserido evento: '.$this->evento_titulo.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do evento:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do evento:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do evento:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo .= '<br><b>Início:</b>'.retorna_data($this->evento_inicio);
				$corpo .= '<br><b>Término:</b>'.retorna_data($this->evento_inicio);
				
				if ($email_texto) $corpo .= '<br>'.$email_texto.'<br>';
				
				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><br><a href="javascript:void(0);" onclick="url_passar(0, \'m=calendario&a=ver&evento_id='.$this->evento_id.'\');"><b>Clique para acessar o evento</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=calendario&a=ver&evento_id='.$this->evento_id);
						if ($endereco) $corpo_externo.='<br><br><a href="'.$endereco.'"><b>Clique para acessar o evento</b></a>';
						}
					}

				$email->Corpo($corpo_externo, (isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : $localidade_tipo_caract));
				if ($usuario['usuario_id']!=$Aplic->usuario_id && $usuario['usuario_id']) {
					if ($usuario['usuario_id']) msg_email_interno('', $titulo, $corpo_interno,'',$usuario['usuario_id']);
					if ($email->EmailValido($usuario['contato_email']) && $config['email_ativo']) {
						$email->Para($usuario['contato_email'], true);
						$email->Enviar();
						}
					}
				}
			}
		}




















	public function checarConflito( $listaUsuarios = null) {
		global $Aplic, $config;
		if (!isset($listaUsuarios)) return false;
		$usuarios = explode(',', $listaUsuarios);
		if (!count($usuarios)) return false;
		$data_inicio = new CData($this->evento_inicio);
		$data_fim = new CData($this->evento_fim);
		$sql = new BDConsulta;
		$sql->adTabela('eventos', 'e');
		$sql->adUnir('evento_participante', 'evento_participante', 'evento_participante_evento = e.evento_id');
		$sql->adCampo('DISTINCT evento_participante_usuario');
		$sql->adOnde('evento_inicio <= \''.$data_fim->format('%Y-%m-%d %H:%M:%S').'\'');
		$sql->adOnde('evento_fim >= \''.$data_inicio->format('%Y-%m-%d %H:%M:%S').'\'');
		$sql->adOnde('evento_participante_usuario IN ('.implode(',', $usuarios).')');
		$sql->adOnde('e.evento_id !='.(int)$this->evento_id);
		$conflitos = $sql->carregarColuna();
		$sql->limpar();
		if (count($conflitos)) {
			$sql->adTabela('usuarios', 'u');
			$sql->esqUnir('contatos', 'con','usuario_contato = contato_id');
			$sql->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').'');
			$sql->adOnde('usuario_id IN ('.implode(',', $conflitos).')');
			return $sql->ListaChave();
			}
		else return false;
		}

	public function getEventosNaJanela( $data_inicio, $data_fim, $inicio_hora, $fim_hora, $usuarios = null) {
		global $Aplic;
		if (!isset($usuarios)) return false;
		if (!count($usuarios)) return false;
		$sql = new BDConsulta;
		$sql->adTabela('eventos', 'e');
		$sql->adUnir('evento_participante', 'evento_participante', 'evento_participante_evento = e.evento_id');
		$sql->adCampo('e.evento_dono, evento_participante_usuario AS usuario_id, e.evento_diautil, e.evento_id, e.evento_inicio, e.evento_fim');
		$sql->adOnde('evento_inicio >= \''.$data_inicio.'\'	AND evento_fim <= \''.$data_fim.'\'	AND extrair(\'HOUR_MINUTE\', e.evento_fim) >= \''.$inicio_hora.'\'	AND extrair(\'HOUR_MINUTE\', e.evento_inicio) <= \''.$fim_hora.'\' AND ( e.evento_dono in ('.implode(',', $usuarios).')	OR usuario_id in ('.implode(',',$usuarios).') )');
		$resultado = $sql->exec();
		if (!$resultado) return false;
		$listaEventos = array();
		while ($linha = $sql->carregarLinha()) $listaEventos[] = $linha;
		$sql->limpar();
		return $listaEventos;
		}

	public function podeAcessar(){
		if ($this->evento_pratica) $valor=permiteAcessarPratica($this->evento_acesso, $this->evento_pratica, $this->evento_acao);
		elseif ($this->evento_indicador) $valor=permiteAcessarIndicador($this->evento_acesso, $this->evento_indicador);
		elseif ($this->evento_tema) $valor=permiteAcessarTema(evento_acesso, $this->evento_tema);
		elseif ($this->evento_objetivo) $valor=permiteAcessarObjetivo($this->evento_acesso, $this->evento_objetivo);
		elseif ($this->evento_estrategia) $valor=permiteAcessarEstrategia($this->evento_acesso, $this->evento_estrategia);
		elseif ($this->evento_calendario) $valor=permiteAcessarCalendario($this->evento_calendario);
		else $valor=permiteAcessar($this->evento_acesso, $this->evento_projeto, $this->evento_tarefa);
		return $valor;
		}

	public function podeEditar() {
		if ($this->evento_pratica) $valor=permiteEditarPratica($this->evento_acesso, $this->evento_pratica, $this->evento_acao);
		elseif ($this->evento_indicador) $valor=permiteEditarIndicador($this->evento_acesso, $this->evento_indicador);
		elseif ($this->evento_tema) $valor=permiteEditarTema($this->evento_acesso, $this->evento_tema);
		elseif ($this->evento_objetivo) $valor=permiteEditarObjetivo($this->evento_acesso, $this->evento_objetivo);
		elseif ($this->evento_estrategia) $valor=permiteEditarEstrategia($this->evento_acesso, $this->evento_estrategia);
		elseif ($this->evento_calendario) $valor=permiteEditarCalendario($this->evento_calendario);
		else $valor=permiteEditar($this->evento_acesso, $this->evento_projeto, $this->evento_tarefa);
		return $valor;
		}



	public function adLembrete() {
		if (!$this->evento_inicio||($this->evento_lembrar < 1)) {
			return $this->limparLembrete();
			}
		$eq = new EventoFila;
		$args = null;
		$lembretes_antigos = $eq->procurar('calendario', 'evento', $this->evento_id);
		if (count($lembretes_antigos)) {
			foreach ($lembretes_antigos as $antigo_id => $data_antiga) $eq->remover($antigo_id);
			}
		$data = new CData($this->evento_inicio);
		$hoje = new CData(date('Y-m-d'));
		if ($data->compare($data, $hoje) < 0) $inicio_dia = time();
		else {
			$inicio_dia = $data->getData(DATE_FORMAT_UNIXTIME);
			}
		$eq->adicionar(array($this, 'lembrar'), $args, 'calendario', false, $this->evento_id, 'evento', ($inicio_dia-$this->evento_lembrar));
		}

	public function lembrar($modulo=null, $tipo=null, $id=null, $responsavel=null, $args=null) {
		global $localidade_tipo_caract, $Aplic, $config;
		$tipos = getSisValor('TipoEvento');
		$sql = new BDConsulta;
	  $sem_email_interno=0;
		
		if (!$this->load($id)) return - 1;
		$this->htmlDecodificar();
		$hoje = new CData();
		$sql->adTabela('eventos','e');
		$sql->esqUnir('evento_participante', 'evento_participante', 'evento_participante_evento = e.evento_id');
		$sql->esqUnir('usuarios', 'u', 'u.usuario_id = evento_participante_usuario');
		$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
		$sql->adCampo('c.contato_id, contato_posto, contato_nomeguerra, contato_email,u.usuario_id, cia_nome');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$sql->adOnde('e.evento_id = '.(int)$id);
		$contatos = $sql->ListaChaveSimples('contato_id');
		$sql->limpar();

		$responsavel_naoeh_designado = false;
		$sql->adTabela('usuarios', 'u');
		$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
		$sql->adCampo('c.contato_id, contato_posto, contato_nomeguerra, contato_email, usuario_id, cia_nome');
		$sql->adOnde('u.usuario_id = '.(int)$this->evento_dono);
		$responsavel=$sql->linha();
		$sql->limpar();

		if (!isset($contatos[$responsavel['contato_id']])) {
			$contatos[$responsavel['contato_id']]=$responsavel;
			}

		$agora = new CData();

		$projeto_nome = ($this->evento_projeto ? htmlspecialchars_decode(nome_projeto($this->evento_projeto)) : '');
		$tarefa_nome = ($this->evento_tarefa ? htmlspecialchars_decode(nome_tarefa($this->evento_tarefa)) : '');
		$indicador_nome = ($this->evento_indicador ? htmlspecialchars_decode(nome_indicador($this->evento_indicador)) : '');
		$objetivo_nome = ($this->evento_objetivo ? htmlspecialchars_decode(nome_objetivo($this->evento_objetivo)) : '');
		$tema_nome = ($this->evento_tema ? htmlspecialchars_decode(nome_tema($this->evento_tema)) : '');
		$estrategia_nome = ($this->evento_estrategia ? htmlspecialchars_decode(nome_estrategia($this->evento_estrategia)) : '');
		$calendario_nome = ($this->evento_calendario ? htmlspecialchars_decode(nome_calendario($this->evento_calendario)) : '');
		$pratica_nome = ($this->evento_pratica ? htmlspecialchars_decode(nome_pratica($this->evento_pratica)) : '');
		$acao_nome = ($this->evento_acao ? htmlspecialchars_decode(nome_acao($this->evento_acao)) : '');

		$data = new CData($this->evento_inicio);
		$assunto = '<b>Lembrete: </b>'.$this->evento_titulo.($projeto_nome || $pratica_nome || $indicador_nome || $calendario_nome || $estrategia_nome || $objetivo_nome || $tema_nome ? ' ('.$tema_nome.$projeto_nome.$pratica_nome.$estrategia_nome.$objetivo_nome.$indicador_nome.$calendario_nome.$acao_nome.($tarefa_nome  ? ' - '.$tarefa_nome :'').')': '');
		$corpo='<b>Evento:</b> '.$this->evento_titulo.($projeto_nome || $pratica_nome || $indicador_nome || $calendario_nome || $estrategia_nome || $objetivo_nome || $tema_nome ? ' ('.$projeto_nome.$pratica_nome.$indicador_nome.$estrategia_nome.$objetivo_nome.$calendario_nome.$acao_nome.($tarefa_nome ? ' - '.$tarefa_nome :'').')': '').'<br>';
		if ($this->evento_inicio) $corpo.='<b>Data de Início:</b> '.retorna_data($this->evento_inicio, true).'<br>';
		if ($this->evento_fim) $corpo.='<b>Data de Término:</b> '.retorna_data($this->evento_fim, true).'<br>';
		if ($this->evento_dono) $corpo.='<b>Responsável:</b> '.$responsavel['contato_posto'].' ' .$responsavel['contato_nomeguerra'].($responsavel['cia_nome'] ? ' - '.$responsavel['cia_nome'] : '').'<br>';
		if ($this->evento_tipo) $corpo.='<b>Tipo:</b> '.$tipos[$this->evento_tipo].'<br>';
		$designados='';
		foreach ($contatos as $contato) {
			$designados.= $contato['contato_posto'].' '.$contato['contato_nomeguerra'].($contato['cia_nome'] ? ' - '.$contato['cia_nome'] : '').($contato['contato_email'] ? ' <'.$contato['contato_email'].'>' : '').'<br>';
			}
		if ($designados) $corpo.='<b>Participante'.(count($contatos) > 1 ? 's':'').':</b><br>'.$designados;
		if ($this->evento_descricao) $corpo .= '<br><b>Descrição:</b><br>'.$this->evento_descricao.'<br>';
		
		$corpo_interno=$corpo;
		$corpo_externo=$corpo;
		
		$corpo_interno.='<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=calendario&a=ver&evento_id='.$this->evento_id.'&tab=0\');">Clique aqui para visualizar o evento</a><br><br>';
		
		
		$email = new Mail;
		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$interno_enviado=0;

		foreach ($contatos as $usuario_id => $contato) {
			$retorno_interno=msg_email_interno('', $assunto, $corpo_interno,'',$contato['usuario_id']);
			if (!$retorno_interno) $interno_enviado++;
			if ($email->EmailValido($contato['contato_email'])) {
				$email->Para($contato['contato_email'], true);
				}
			
			if ($Aplic->profissional){
				require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
				$endereco=link_email_externo($usuario_id, 'm=calendario&a=ver&evento_id='.$this->evento_id);
				if ($endereco) $corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o evento</b></a>';
				}
			$email->Assunto($assunto, $localidade_tipo_caract);
			$email->Corpo($corpo_externo, $localidade_tipo_caract);
			if ($config['email_ativo']) $retorno_externo=$email->Enviar();
			if ($interno_enviado || $retorno_externo) return true;
			
			}
		}

	public function limparLembrete() {
		$ev = new EventoFila;
		$evento_lista = $ev->procurar('calendario', 'evento', $this->evento_id);
		if (count($evento_lista)) {
			foreach ($evento_lista as $id => $data) $ev->remover($id);
			}
		}


	public function criar_recorrencias(){
		global $bd;
		$sql = new BDConsulta;
		$sql->adTabela('eventos');
		$sql->adCampo('eventos.*');
		$sql->adOnde('evento_id='.(int)$this->evento_id);
		$linha = $sql->linha();
		$sql->limpar();

		// 1 = hora, 2 = dia , 3 = semana, 4 = 15 dias, 5 = mes, 6 = quadrimestre, 7 = semestral, 8 =anual

		$data1=substr($linha['evento_inicio'], 0, 10);
		$data2=substr($linha['evento_fim'], 0, 10);
		$hora_inicio=substr($linha['evento_inicio'], 10, 8);
		$hora_fim=substr($linha['evento_fim'], 10, 8);

		$sql->adTabela('evento_participante');
		$sql->adCampo('evento_participante.*');
		$sql->adOnde('evento_participante_evento='.(int)$this->evento_id);
		$designados = $sql->lista();
		$sql->limpar();


		for ($i=0; $i < $linha['evento_nr_recorrencias'] ; $i++){

			if ($linha['evento_recorrencias']==2){
				$data1=strtotime('+1 day', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+1 day', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}

			if ($linha['evento_recorrencias']==3){
				$data1=strtotime('+1 week', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+1 week', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}

			if ($linha['evento_recorrencias']==4){
				$data1=strtotime('+15 day', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+15 day', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}

			if ($linha['evento_recorrencias']==5){
				$data1=strtotime('+1 month', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+1 month', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}

			if ($linha['evento_recorrencias']==9){
				$data1=strtotime('+2 month', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+2 month', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}


			if ($linha['evento_recorrencias']==10){
				$data1=strtotime('+3 month', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+3 month', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}


			if ($linha['evento_recorrencias']==6){
				$data1=strtotime('+4 month', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+4 month', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}

			if ($linha['evento_recorrencias']==7){
				$data1=strtotime('+6 month', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+6 month', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}

			if ($linha['evento_recorrencias']==8){
				$data1=strtotime('+1 year', strtotime($data1));
				$data1=date('Y-m-d', $data1);

				$data2=strtotime('+1 year', strtotime($data2));
				$data2=date('Y-m-d', $data2);
				}

			$sql->adTabela('eventos');

			foreach($linha as $chave => $valor) if ($chave !='evento_id' && $chave !='evento_recorrencia_pai' && $chave !='evento_inicio' && $chave !='evento_fim') $sql->adInserir($chave, $valor);
			$sql->adInserir('evento_inicio', $data1.' '.$hora_inicio);
			$sql->adInserir('evento_fim', $data2.' '.$hora_fim);
			$sql->adInserir('evento_recorrencia_pai', $linha['evento_id']);
			$sql->exec();
			$evento_id=$bd->Insert_ID('eventos','evento_id');
			$sql->limpar();
			//designados
			foreach($designados as $linha2){
				$sql->adTabela('evento_participante');
				$sql->adInserir('evento_participante_usuario', $linha2['evento_participante_usuario']);
				$sql->adInserir('evento_participante_aceito', $linha2['evento_participante_aceito']);
				$sql->adInserir('evento_participante_data', $linha2['evento_participante_data']);
				$sql->adInserir('evento_participante_duracao', $linha2['evento_participante_duracao']);
				$sql->adInserir('evento_participante_percentual', $linha2['evento_participante_percentual']);
				$sql->adInserir('evento_participante_evento', $evento_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		}


	}

$evento_filtro_lista = array('todos' => 'Todos os eventos', 'meu' => 'Eventos onde '.$config['genero_usuario'].' '.$config['usuario'].' está designad'.$config['genero_usuario'].' ou responsável', 'dono' => 'Eventos onde '.$config['genero_usuario'].' '.$config['usuario'].' é '.$config['genero_usuario'].' responsável', 'todos_aceitos' => 'Eventos aceitos pel'.$config['genero_usuario'].' '.$config['usuario'], 'todos_pendentes' => 'Eventos pendentes de aceitar pel'.$config['genero_usuario'].' '.$config['usuario'], 'todos_recusados' => 'Eventos recusados pel'.$config['genero_usuario'].' '.$config['usuario']);
?>