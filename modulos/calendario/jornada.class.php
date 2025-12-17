 <?php 
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\calendario\jornada.class.php		

Classe Cjornada para manipulação do expendiente dos usuários e OMs.																																						
																																												
********************************************************************************************/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $Aplic, $usuario_id;
require_once ($Aplic->getClasseBiblioteca('PEAR/Date'));
require_once ($Aplic->getClasseSistema('aplic'));
require_once $Aplic->getClasseSistema('libmail');
require_once $Aplic->getClasseSistema('data');

include_once BASE_DIR.'/modulos/tarefas/funcoes.php';

class Cjornada {
	public $cia_id;
	public $usuario_id;
	public $projeto_id;
	public $tarefa_id;
	public $recurso_id;
	public $jornada_id;
	public $data;
	public $minutoiCal;
	
	public function __construct( $cia_id=0, $usuario_id=0, $projeto_id=0, $recurso_id=0, $tarefa_id=0, $jornada_id=0){
		global $Aplic;
		if($usuario_id) {
			$q = new BDConsulta;
			$q->adTabela('usuarios', 'us');
			$q->adUnir('contatos', 'co', 'co.contato_id = us.usuario_contato');
			$q->adCampo('contato_cia');
			$q->adOnde('usuario_id = '.(int)$usuario_id);
			$resultado=$q->Resultado();
			$q->limpar();	
			if ($resultado) $this->cia_id=$resultado;
			}
		elseif($tarefa_id) {
			$q = new BDConsulta;
			$q->adTabela('tarefas');
			$q->adCampo('tarefa_cia');
			$q->adOnde('tarefa_id = '.(int)$tarefa_id);
			$resultado=$q->Resultado();
			$q->limpar();	
			if ($resultado) $this->cia_id=$resultado;
			else $this->cia_id=$Aplic->usuario_cia;
			}		
		elseif($projeto_id) {
			$q = new BDConsulta;
			$q->adTabela('projetos');
			$q->adCampo('projeto_cia');
			$q->adOnde('projeto_id = '.(int)$projeto_id);
			$resultado=$q->Resultado();
			$q->limpar();	
			if ($resultado) $this->cia_id=$resultado;
			else $this->cia_id=$Aplic->usuario_cia;
			}	
		elseif($recurso_id) {
			$q = new BDConsulta;
			$q->adTabela('recursos');
			$q->adCampo('recurso_cia');
			$q->adOnde('recurso_cia = '.(int)$recurso_id);
			$resultado=$q->Resultado();
			$q->limpar();	
			if ($resultado) $this->cia_id=$resultado;
			else $this->cia_id=$Aplic->usuario_cia;
			}	
		elseif ($cia_id) $this->cia_id=$cia_id;
		
		if (!$this->cia_id)	$this->cia_id=$Aplic->usuario_cia;
		$this->usuario_id=$usuario_id;
		$this->projeto_id=$projeto_id;
		$this->tarefa_id=$tarefa_id;
		$this->recurso_id=$recurso_id;
		$this->jornada_id=$jornada_id;
		}
	
	public function horas_dia( $inicio){
		global $config;
		$horas=horas_periodo($inicio.' 00:00:00', $inicio.' 23:59:59', $this->cia_id, $this->usuario_id, $this->projeto_id, $this->recurso_id, $this->tarefa_id);
		return $horas;
		}

	public function setData( $data){
		$this->data=$data;
		$this->minical = new CCalendarioMes($data);
		$this->minical->setEstilo('minititulo', 'minical');
		$this->minical->mostrarSetas = false;
		$this->minical->mostrarSemana = false;
		$this->minical->clicarMes = false;
		$this->minical->setExpediente('sim');
		}


	public function adicionarMes( $qnt){
		$this->data->adMeses($qnt);
		}

	public function calendarioMesAtual( $sobrecarga=false){
		$primeira_data = new CData($this->data);
		$primeira_data->setDay(1);
		$primeira_data->setTime(0, 0, 0);
		$ultima_data = new CData($this->data);
		$ultima_data->setDay($this->data->getDaysInMonth());
		$ultima_data->setTime(23, 59, 59);
		$links = array();
		if ($sobrecarga) $this->getSobrecargaLinks($links);
		else getExpedienteLinks($primeira_data, $ultima_data, $links, $this->cia_id, $this->usuario_id, $this->projeto_id, $this->recurso_id, $this->tarefa_id, $this->jornada_id);
		$this->minical->setEventos($links);
		$this->minical->setData($this->data);
		return $this->minical->mostrar();
		}
		
	public function getSobrecargaLinks( &$links) {
		global $a, $Aplic, $config, $usuario_id;
		$primeira_data = new CData($this->data);
		$primeira_data->setDay(1);
		$primeira_data->setTime(0, 0, 0);
		$ultima_data = new CData($this->data);
		$ultima_data->setDay($this->data->getDaysInMonth());
		$ultima_data->setTime(23, 59, 59);
		$d="%Y-%m-%d";
		$horas_diasuteis=array();
		$horas_tarefas_diasuteis=array();
		$horas_trabalhadas=array();
		$q = new BDConsulta;
		$q->adTabela('tarefas', 't1');
		$q->adUnir('tarefa_designados', 'ut', 't1.tarefa_id = ut.tarefa_id');
		$q->adCampo('tarefa_inicio, tarefa_fim, tarefa_duracao ,perc_designado');
		$q->adOnde('ut.usuario_id = '.(int)$usuario_id);
		$q->adOnde('tarefa_duracao > 0');
		$q->adOnde('date(tarefa_inicio) <= \''.$ultima_data->format('%Y-%m-%d').'\' AND date(tarefa_fim)>= \''.$primeira_data->format('%Y-%m-%d').'\'');
		$tarefas=$q->Lista();
		$q->limpar();
		
		//adicionar eventos de calendário
		$q->adTabela('eventos');
		$q->esqUnir('evento_participante', 'evento_participante', 'evento_participante_evento = eventos.evento_id');
		$q->adCampo('evento_inicio AS tarefa_inicio, evento_fim AS tarefa_fim, evento_participante_duracao AS tarefa_duracao, evento_participante_percentual AS perc_designado');
		$q->adOnde('evento_participante_usuario = '.(int)$usuario_id);
		//$q->adOnde('evento_participante_duracao > 0');
		$q->adOnde('date(evento_inicio) <= \''.$ultima_data->format('%Y-%m-%d').'\'');
		$q->adOnde('date(evento_fim)>= \''.$primeira_data->format('%Y-%m-%d').'\'');
		$eventos=$q->Lista();
		$q->limpar();
		if (count($eventos)) $tarefas=array_merge($tarefas, $eventos);


		foreach ($tarefas as $tarefa) {
			$data_inicial=new CData($tarefa['tarefa_inicio']);
			$data_final=new CData($tarefa['tarefa_fim']);
			$data=$data_inicial;
			$percentual=$tarefa['perc_designado']/100;
			
			for ($i = 0, $i_cmp = $data_inicial->dataDiferenca($data_final); $i <= $i_cmp; $i++) {
				if (!isset($horas_diasuteis[$data->format('%Y%m%d')])) $horas_diasuteis[$data->format('%Y%m%d')]= round($this->horas_dia($data->format('%Y-%m-%d')), 2);
				$data = $data->getNextDay();
				}
				
			$diferenca_data=$data_inicial->dataDiferenca($data_final);
			if ($diferenca_data==0) {
				//termino da tarefa no mesmo dia do inicio da tarefa
				$horas=horas_periodo($tarefa['tarefa_inicio'], $tarefa['tarefa_fim'], $this->cia_id, $this->usuario_id);
				if (isset($horas_trabalhadas[$data_inicial->format('%Y%m%d')]))$horas_trabalhadas[$data_inicial->format('%Y%m%d')]+=$horas*$percentual;
				else $horas_trabalhadas[$data_inicial->format('%Y%m%d')]=$horas*$percentual;
				}
			elseif($diferenca_data==1){
				//termino da tarefa no dia seguinte do inicio da tarefa
				
				//primeiro dia
				$horas=horas_periodo($tarefa['tarefa_inicio'], $data_inicial->format("%Y-%m-%d 23:59:59"), $this->cia_id, $this->usuario_id);
				if (isset($horas_trabalhadas[$data_inicial->format('%Y%m%d')]))$horas_trabalhadas[$data_inicial->format('%Y%m%d')]+=$horas*$percentual;
				else $horas_trabalhadas[$data_inicial->format('%Y%m%d')]=$horas*$percentual;

				//ultimo dia
				$horas=horas_periodo($data_final->format("%Y-%m-%d 00:00:00"), $tarefa['tarefa_fim'], $this->cia_id, $this->usuario_id);
				if (isset($horas_trabalhadas[$data_final->format('%Y%m%d')]))$horas_trabalhadas[$data_final->format('%Y%m%d')]+=$horas*$percentual;
				else $horas_trabalhadas[$data_final->format('%Y%m%d')]=$horas*$percentual;
				}
			else{
				//primeiro dia
				$horas=horas_periodo($tarefa['tarefa_inicio'], $data_inicial->format("%Y-%m-%d 23:59:59"), $this->cia_id, $this->usuario_id);
				if (isset($horas_trabalhadas[$data_inicial->format('%Y%m%d')]))$horas_trabalhadas[$data_inicial->format('%Y%m%d')]+=$horas*$percentual;
				else $horas_trabalhadas[$data_inicial->format('%Y%m%d')]=$horas*$percentual;

				$data=$data_inicial;
				for ($i = 0, $i_cmp = $data_inicial->dataDiferenca($data_final); $i < $i_cmp; $i++) {
					if ($i){
						$horas=horas_periodo($data->format("%Y-%m-%d 00:00:00"), $data->format("%Y-%m-%d 23:59:59"), $this->cia_id, $this->usuario_id);
						if (isset($horas_trabalhadas[$data->format('%Y%m%d')]))$horas_trabalhadas[$data->format('%Y%m%d')]+=$horas*$percentual;
						else $horas_trabalhadas[$data->format('%Y%m%d')]=$horas*$percentual;
						}
					$data = $data->getNextDay();
					}

				//ultimo dia
				$horas=horas_periodo($data_final->format("%Y-%m-%d 00:00:00"), $tarefa['tarefa_fim'], $this->cia_id, $this->usuario_id);
				if (isset($horas_trabalhadas[$data_final->format('%Y%m%d')]))$horas_trabalhadas[$data_final->format('%Y%m%d')]+=$horas*$percentual;
				else $horas_trabalhadas[$data_final->format('%Y%m%d')]=$horas*$percentual;
				}
			}
			
			$data=$primeira_data;
			for ($i = 0, $i_cmp = $primeira_data->dataDiferenca($ultima_data); $i <= $i_cmp; $i++) {
				$indice=$data->format('%Y%m%d');
				if (!isset($horas_diasuteis[$indice]) || (isset($horas_diasuteis[$indice]) && !$horas_diasuteis[$indice])) $percentual= 0;
				else  $percentual=(int)(100*$horas_trabalhadas[$indice]/$horas_diasuteis[$indice]);
				if ($percentual){
					$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
					$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Percentual Designado</b></td><td>'.$percentual.'%</td></tr>';
					$dentro .= '</table>';
					$temp =  array('sobrecarga' => true, 'texto_mini' => '<tr><td>'.$dentro.'</td></tr>', 'percentagem' => $percentual);
					$links[$indice][] = $temp;
					}
				$data = $data->getNextDay();
				}
			}
			
		}
		
		
class CJornadaPadrao extends CAplicObjeto {
	
	public $jornada_id = null;
  public $jornada_nome = null;
  public $jornada_1_inicio = null;
  public $jornada_1_almoco_inicio = null;
  public $jornada_1_almoco_fim = null;
  public $jornada_1_fim = null;
  public $jornada_2_inicio = null;
  public $jornada_2_almoco_inicio = null;
  public $jornada_2_almoco_fim = null;
  public $jornada_2_fim = null;
  public $jornada_3_inicio = null;
  public $jornada_3_almoco_inicio = null;
  public $jornada_3_almoco_fim = null;
  public $jornada_3_fim = null;
  public $jornada_4_inicio = null;
  public $jornada_4_almoco_inicio = null;
  public $jornada_4_almoco_fim = null;
  public $jornada_4_fim = null;
  public $jornada_5_inicio = null;
  public $jornada_5_almoco_inicio = null;
  public $jornada_5_almoco_fim = null;
  public $jornada_5_fim = null;
  public $jornada_6_inicio = null;
  public $jornada_6_almoco_inicio = null;
  public $jornada_6_almoco_fim = null;
  public $jornada_6_fim = null;
  public $jornada_7_inicio = null;
  public $jornada_7_almoco_inicio = null;
  public $jornada_7_almoco_fim = null;
  public $jornada_7_fim = null;
  public $jornada_1_duracao = null;
  public $jornada_2_duracao = null;
  public $jornada_3_duracao = null;
  public $jornada_4_duracao = null;
  public $jornada_5_duracao = null;
  public $jornada_6_duracao = null;
  public $jornada_7_duracao = null;

	public function __construct() {
		parent::__construct('jornada', 'jornada_id');
		}

	
	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->jornada_id) {
			$ret = $sql->atualizarObjeto('jornada', $this, 'jornada_id', false);
			$sql->limpar();
			} 
		else {
			$ret = $sql->inserirObjeto('jornada', $this, 'jornada_id');
			$sql->limpar();
			}
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	public function check() {
		return null;
		}

	}		
?>