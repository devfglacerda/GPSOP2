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

global $Aplic;


require_once BASE_DIR.'/modulos/tarefas/tarefas.class.php';
require_once BASE_DIR.'/classes/libmail.class.php';



if ($Aplic->profissional){
	require_once BASE_DIR.'/modulos/projetos/funcoes_pro.php';
	require_once BASE_DIR.'/classes/2dmath.class.php';
	}

$projStatus = getSisValor('StatusProjeto');
$prioridade_nome = getSisValor('PrioridadeProjeto');
$prioridade_cor = getSisValor('CorPrioridadeProjeto');
$prioridade = array();
foreach ($prioridade_nome as $chave => $val) $prioridade[$chave]['nome'] = $val;
foreach ($prioridade_cor as $chave => $val) $prioridade[$chave]['cor'] = $val;
$projeto_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

class FiltrosProjetoBuilder {
	private $cia_id = null;
	private $envolvimento = null;
	private $usuario_id;
	private $supervisor = null;
	private $autoridade = null;
	private $cliente = null;
	private $ordenarPor = 'projeto_nome';
	private $ordemDir = 'ASC';
	private $usar_periodo = null;
	private $reg_data_inicio = null;
	private $reg_data_fim = null;
	private $projeto_tipo = null;
	private $projeto_setor = null;
	private $projeto_segmento = null;
	private $projeto_intervencao = null;
	private $projeto_tipo_intervencao = null;
	private $estado_sigla = null;
	private $municipio_id = null;
	private $pesquisar_texto = null;
	private $mostrarProjRespPertenceDept = null;
	private $recebido = null;
	private $dept_id = null;
	private $favorito_id = null;
	private $lista_cias = null;
	private $projetostatus = null;
	private $ponto_inicio = null;
	private $projeto_expandido = null;
	private $nao_apenas_superiores = null;
	private $exibir = array();
	private $portfolio = null;
	private $template = null;
	private $portfolio_pai = null;
	private $limite = true;
	private $data_inicio = null;
	private $data_termino = null;
	private $filtro_area = null;
	private $filtro_criterio = null;
	private $filtro_opcao = null;
	private $filtro_prioridade = null;
	private $filtro_perspectiva = null;
	private $filtro_tema = null;
	private $filtro_objetivo = null;
	private $filtro_fator = null;
	private $filtro_estrategia = null;
	private $filtro_meta = null;
	private $filtro_me = null;
	private $filtro_canvas = null;
	private $filtro_extra = null;
	private $projeto_id = null;
	private $tarefa_id = null;
	private $pg_perspectiva_id = null;
	private $tema_id = null;
	private $objetivo_id = null;
	private $fator_id = null;
	private $pg_estrategia_id = null;
	private $pg_meta_id = null;
	private $pratica_id = null;
	private $pratica_indicador_id = null;
	private $plano_acao_id = null;
	private $canvas_id = null;
	private $risco_id = null;
	private $risco_resposta_id = null;
	private $calendario_id = null;
	private $monitoramento_id = null;
	private $ata_id = null;
	private $mswot_id = null;
	private $swot_id = null;
	private $operativo_id = null;
	private $instrumento_id = null;
	private $recurso_id = null;
	private $problema_id = null;
	private $demanda_id = null;
	private $programa_id = null;
	private $licao_id = null;
	private $evento_id = null;
	private $link_id = null;
	private $avaliacao_id = null;
	private $tgn_id = null;
	private $brainstorm_id = null;
	private $gut_id = null;
	private $causa_efeito_id = null;
	private $arquivo_id = null;
	private $forum_id = null;
	private $checklist_id = null;
	private $agenda_id = null;
	private $agrupamento_id = null;
	private $patrocinador_id = null;
	private $template_id = null;
	private $painel_id = null;
	private $painel_odometro_id = null;
	private $painel_composicao_id = null;
	private $tr_id = null;
	private $me_id = null;
	private $plano_acao_item_id = null;
	private $beneficio_id = null;
	private $painel_slideshow_id = null;
	private $projeto_viabilidade_id = null;
	private $projeto_abertura_id = null;
	private $pg_id = null;
	private $ssti_id = null;
	private $laudo_id = null;
	private $trelo_id = null;
	private $trelo_cartao_id = null;
	private $pdcl_id = null;
	private $pdcl_item_id = null;







	/**
	 * @return null
	 */
	public function getUsar_periodo() {
		return $this->usar_periodo;
		}

	/**
	 * @param int $usar_periodo
	 *
	 * @return usar_periodo
	 */
	public function setUsar_periodo($usar_periodo) {
		$this->usar_periodo = $usar_periodo;
		return $this;
		}	



	/**
	 * @return null
	 */
	public function getReg_data_inicio() {
		return $this->reg_data_inicio;
		}

	/**
	 * @param int $reg_data_inicio
	 *
	 * @return reg_data_inicio
	 */
	public function setReg_data_inicio($reg_data_inicio) {
		$this->reg_data_inicio = $reg_data_inicio;
		return $this;
		}
		
		
		
	/**
	 * @return null
	 */
	public function getReg_data_fim() {
		return $this->reg_data_fim;
		}

	/**
	 * @param int $reg_data_fim
	 *
	 * @return reg_data_fim
	 */
	public function setReg_data_fim($reg_data_fim) {
		$this->reg_data_fim = $reg_data_fim;
		return $this;
		}	




	/**
	 * @return null
	 */
	public function getEnvolvimento() {
		return $this->envolvimento;
		}

	/**
	 * @param int $envolvimento
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setEnvolvimento($envolvimento) {
		$this->envolvimento = (int) $envolvimento;
		return $this;
		}	

	/**
	 * @return int
	 */
	public function getUsuarioId() {
		return $this->usuario_id;
		}

	/**
	 * @param int $usuario_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setUsuarioId($usuario_id) {
		$this->usuario_id = (int) $usuario_id;

		return $this;
		}

	/**
	 * @return int
	 */
	public function getSupervisor() {
		return $this->supervisor;
		}

	/**
	 * @param int $supervisor
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setSupervisor($supervisor) {
		$this->supervisor = (int) $supervisor;

		return $this;
		}

	/**
	 * @return int
	 */
	public function getAutoridade() {
		return $this->autoridade;
		}

	/**
	 * @param int $autoridade
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setAutoridade($autoridade) {
		$this->autoridade = (int) $autoridade;

		return $this;
		}

	/**
	 * @return int
	 */
	public function getCliente() {
		return $this->cliente;
		}

	/**
	 * @param int $cliente
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setCliente($cliente) {
		$this->cliente = (int) $cliente;

		return $this;
		}

	/**
	 * @return int
	 */
	public function getCiaId() {
		return $this->cia_id;
		}

	/**
	 * @param int $cia_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setCiaId($cia_id) {
		$this->cia_id = (int) $cia_id;

		return $this;
		}

	/**
	 * @return string
	 */
	public function getOrdenarPor() {
		return $this->ordenarPor;
		}

	/**
	 * @param string $ordenarPor
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setOrdenarPor($ordenarPor) {
		$this->ordenarPor = $ordenarPor;

		return $this;
		}

	/**
	 * @return string
	 */
	public function getOrdemDir() {
		return $this->ordemDir;
		}

	/**
	 * @param string $ordemDir
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setOrdemDir($ordemDir) {
		$this->ordemDir = $ordemDir;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getProjetoTipo() {
		return $this->projeto_tipo;
		}

	/**
	 * @param null $projeto_tipo
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setProjetoTipo($projeto_tipo) {
		$this->projeto_tipo = $projeto_tipo;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getProjetoSetor() {
		return $this->projeto_setor;
		}

	/**
	 * @param null $projeto_setor
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setProjetoSetor($projeto_setor) {
		$this->projeto_setor = $projeto_setor;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getProjetoSegmento() {
		return $this->projeto_segmento;
		}

	/**
	 * @param null $projeto_segmento
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setProjetoSegmento($projeto_segmento) {
		$this->projeto_segmento = $projeto_segmento;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getProjetoIntervencao() {
		return $this->projeto_intervencao;
		}

	/**
	 * @param null $projeto_intervencao
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setProjetoIntervencao($projeto_intervencao) {
		$this->projeto_intervencao = $projeto_intervencao;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getProjetoTipoIntervencao() {
		return $this->projeto_tipo_intervencao;
		}

	/**
	 * @param null $projeto_tipo_intervencao
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setProjetoTipoIntervencao($projeto_tipo_intervencao) {
		$this->projeto_tipo_intervencao = $projeto_tipo_intervencao;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getEstadoSigla() {
		return $this->estado_sigla;
		}

	/**
	 * @param null $estado_sigla
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setEstadoSigla($estado_sigla) {
		$this->estado_sigla = $estado_sigla;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getMunicipioId() {
		return $this->municipio_id;
		}

	/**
	 * @param null $municipio_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setMunicipioId($municipio_id) {
		$this->municipio_id = $municipio_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPesquisarTexto() {
		return $this->pesquisar_texto;
		}

	/**
	 * @param null $pesquisar_texto
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPesquisarTexto($pesquisar_texto) {
		$this->pesquisar_texto = $pesquisar_texto;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getMostrarProjRespPertenceDept() {
		return $this->mostrarProjRespPertenceDept;
		}

	/**
	 * @param null $mostrarProjRespPertenceDept
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setMostrarProjRespPertenceDept($mostrarProjRespPertenceDept) {
		$this->mostrarProjRespPertenceDept = $mostrarProjRespPertenceDept;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getRecebido() {
		return $this->recebido;
		}

	/**
	 * @param null $recebido
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setRecebido($recebido) {
		$this->recebido = $recebido;

		return $this;
		}

	/**
	 * @return int
	 */
	public function getDeptId() {
		return $this->dept_id;
		}

	/**
	 * @param int $dept_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setDeptId($dept_id) {
		$this->dept_id = (int) $dept_id;

		return $this;
		}

	/**
	 * @return int
	 */
	public function getFavoritoId() {
		return $this->favorito_id;
		}

	/**
	 * @param int $favorito_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setFavoritoId($favorito_id) {
		$this->favorito_id = (int) $favorito_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getListaCias() {
		return $this->lista_cias;
		}

	/**
	 * @param null $lista_cias
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setListaCias($lista_cias) {
		$this->lista_cias = $lista_cias;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getProjetoStatus() {
		return $this->projetostatus;
		}

	/**
	 * @param null $projetostatus
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setProjetoStatus($projetostatus) {
		$this->projetostatus = $projetostatus;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPontoInicio() {
		return $this->ponto_inicio;
		}

	/**
	 * @param null $ponto_inicio
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPontoInicio($ponto_inicio) {
		$this->ponto_inicio = $ponto_inicio;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getProjetoExpandido() {
		return $this->projeto_expandido;
		}

	/**
	 * Especifica o projeto pai sendo expandido
	 *
	 * @param int $projeto_expandido Id do projeto que foi expandido para vizualização dos filhos ou 0 para nenhum
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setProjetoExpandido($projeto_expandido) {
		$this->projeto_expandido = (int) $projeto_expandido;
		return $this;
		}

	/**
	 * @return null
	 */
	public function getNaoApenasSuperiores() {
		return $this->nao_apenas_superiores;
		}

	/**
	 * @param null $nao_apenas_superiores
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setNaoApenasSuperiores($nao_apenas_superiores) {
		$this->nao_apenas_superiores = $nao_apenas_superiores;

		return $this;
		}

	/**
	 * @return array
	 */
	public function getExibir() {
		return $this->exibir;
		}

	/**
	 * @param array $exibir
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setExibir($exibir) {
		$this->exibir = $exibir;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPortfolio() {
		return $this->portfolio;
	}

	/**
	 * @param null $portfolio
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPortfolio($portfolio) {
		$this->portfolio = $portfolio;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getTemplate() {
		return $this->template;
		}

	/**
	 * @param null $template
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setTemplate($template) {
		$this->template = $template;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPortfolioPai() {
		return $this->portfolio_pai;
	}

	/**
	 * @param null $portfolio_pai
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPortfolioPai($portfolio_pai) {
		$this->portfolio_pai = $portfolio_pai;

		return $this;
		}

	/**
	 * @return boolean
	 */
	public function getLimite() {
		return $this->limite;
		}

	/**
	 * @param boolean $limite
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setLimite($limite) {
		$this->limite = $limite;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getDataInicio() {
		return $this->data_inicio;
		}

	/**
	 * @param null $data_inicio
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setDataInicio($data_inicio) {
		$this->data_inicio = $data_inicio;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getDataTermino() {
		return $this->data_termino;
		}

	/**
	 * @param null $data_termino
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setDataTermino($data_termino) {
		$this->data_termino = $data_termino;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getFiltroArea() {
		return $this->filtro_area;
		}

	/**
	 * @param null $filtro_area
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setFiltroArea($filtro_area) {
		$this->filtro_area = $filtro_area;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getFiltroCriterio() {
		return $this->filtro_criterio;
		}

	/**
	 * @param null $filtro_criterio
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setFiltroCriterio($filtro_criterio) {
		$this->filtro_criterio = $filtro_criterio;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getFiltroOpcao() {
		return $this->filtro_opcao;
		}

	/**
	 * @param null $filtro_opcao
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setFiltroOpcao($filtro_opcao) {
		$this->filtro_opcao = $filtro_opcao;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getFiltroPrioridade() {
		return $this->filtro_prioridade;
		}

	/**
	 * @param null $filtro_prioridade
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setFiltroPrioridade($filtro_prioridade) {
		$this->filtro_prioridade = $filtro_prioridade;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getFiltroPerspectiva() {
		return $this->filtro_perspectiva;
		}

	/**
	 * @param null $filtro_perspectiva
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setFiltroPerspectiva($filtro_perspectiva) {
		$this->filtro_perspectiva = $filtro_perspectiva;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getFiltroTema() {
		return $this->filtro_tema;
		}

	/**
	 * @param null $filtro_tema
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setFiltroTema($filtro_tema) {
		$this->filtro_tema = $filtro_tema;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getFiltroObjetivo() {
		return $this->filtro_objetivo;
		}

	/**
	 * @param null $filtro_objetivo
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setFiltroObjetivo($filtro_objetivo) {
		$this->filtro_objetivo = $filtro_objetivo;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getFiltroFator() {
		return $this->filtro_fator;
		}

	/**
	 * @param null $filtro_fator
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setFiltroFator($filtro_fator) {
		$this->filtro_fator = $filtro_fator;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getFiltroEstrategia() {
		return $this->filtro_estrategia;
		}

	/**
	 * @param null $filtro_estrategia
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setFiltroEstrategia($filtro_estrategia) {
		$this->filtro_estrategia = $filtro_estrategia;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getFiltroMeta() {
		return $this->filtro_meta;
		}

	/**
	 * @param null $filtro_meta
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setFiltroMeta($filtro_meta) {
		$this->filtro_meta = $filtro_meta;

		return $this;
		}


/**
	 * @return null
	 */
	public function getFiltroMe() {
		return $this->filtro_me;
	}

/**
	 * @param null $filtro_me
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setFiltroMe($filtro_me) {
		$this->filtro_me = $filtro_me;

		return $this;
		}



	/**
	 * @return null
	 */
	public function getFiltroCanvas() {
		return $this->filtro_canvas;
		}

	/**
	 * @param null $filtro_canvas
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setFiltroCanvas($filtro_canvas) {
		$this->filtro_canvas = $filtro_canvas;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getFiltroExtra() {
		return $this->filtro_extra;
		}

	/**
	 * @param null $filtro_extra
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setFiltroExtra($filtro_extra) {
		$this->filtro_extra = $filtro_extra;

		return $this;
		}





/**
	 * @return null
	 */
	public function getProjetoId() {
		return $this->projeto_id;
		}

	/**
	 * @param null $projeto_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setProjetoId($projeto_id) {
		$this->projeto_id = $projeto_id;

		return $this;
		}
	
	/**
	 * @return null
	 */
	public function getTarefaId() {
		return $this->tarefa_id;
	}

	/**
	 * @param null $tarefa_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setTarefaId($tarefa_id) {
		$this->tarefa_id = $tarefa_id;

		return $this;
		}



	/**
	 * @return null
	 */
	public function getPgPerspectivaId() {
		return $this->pg_perspectiva_id;
		}

	/**
	 * @param null $pg_perspectiva_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPgPerspectivaId($pg_perspectiva_id) {
		$this->pg_perspectiva_id = $pg_perspectiva_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getTemaId() {
		return $this->tema_id;
	}

	/**
	 * @param null $tema_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setTemaId($tema_id) {
		$this->tema_id = $tema_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPgObjetivoEstrategicoId() {
		return $this->objetivo_id;
	}

	/**
	 * @param null $objetivo_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPgObjetivoEstrategicoId($objetivo_id) {
		$this->objetivo_id = $objetivo_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPgFatorCriticoId() {
		return $this->fator_id;
		}

	/**
	 * @param null $fator_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPgFatorCriticoId($fator_id) {
		$this->fator_id = $fator_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPgEstrategiaId() {
		return $this->pg_estrategia_id;
		}

	/**
	 * @param null $pg_estrategia_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPgEstrategiaId($pg_estrategia_id) {
		$this->pg_estrategia_id = $pg_estrategia_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPgMetaId() {
		return $this->pg_meta_id;
		}

	/**
	 * @param null $pg_meta_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPgMetaId($pg_meta_id) {
		$this->pg_meta_id = $pg_meta_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPraticaId() {
		return $this->pratica_id;
		}

	/**
	 * @param null $pratica_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPraticaId($pratica_id) {
		$this->pratica_id = $pratica_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPraticaIndicadorId() {
		return $this->pratica_indicador_id;
		}

	/**
	 * @param null $pratica_indicador_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPraticaIndicadorId($pratica_indicador_id) {
		$this->pratica_indicador_id = $pratica_indicador_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPlanoAcaoId() {
		return $this->plano_acao_id;
		}

	/**
	 * @param null $plano_acao_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPlanoAcaoId($plano_acao_id) {
		$this->plano_acao_id = $plano_acao_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getCanvasId() {
		return $this->canvas_id;
		}

	/**
	 * @param null $canvas_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setCanvasId($canvas_id) {
		$this->canvas_id = $canvas_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getRiscoId() {
		return $this->risco_id;
		}

	/**
	 * @param null $risco_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setRiscoId($risco_id) {
		$this->risco_id = $risco_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getRiscoRespostaId() {
		return $this->risco_resposta_id;
		}

	/**
	 * @param null $risco_resposta_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setRiscoRespostaId($risco_resposta_id) {
		$this->risco_resposta_id = $risco_resposta_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getCalendarioId() {
		return $this->calendario_id;
		}

	/**
	 * @param null $calendario_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setCalendarioId($calendario_id) {
		$this->calendario_id = $calendario_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getMonitoramentoId() {
		return $this->monitoramento_id;
		}

	/**
	 * @param null $monitoramento_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setMonitoramentoId($monitoramento_id) {
		$this->monitoramento_id = $monitoramento_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getAtaId() {
		return $this->ata_id;
		}

	/**
	 * @param null $ata_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setAtaId($ata_id) {
		$this->ata_id = $ata_id;

		return $this;
		}
	
	public function getMswotId() {
		return $this->mswot_id;
		}

	/**
	 * @param null $swot_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setMswotId($mswot_id) {
		$this->mswot_id = $mswot_id;

		return $this;
		}

	/**
	 * @return null
	 */
	 
	 
	public function getSwotId() {
		return $this->swot_id;
		}

	/**
	 * @param null $swot_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setSwotId($swot_id) {
		$this->swot_id = $swot_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getOperativoId() {
		return $this->operativo_id;
		}

	/**
	 * @param null $operativo_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setOperativoId($operativo_id) {
		$this->operativo_id = $operativo_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getInstrumentoId() {
		return $this->instrumento_id;
		}

	/**
	 * @param null $instrumento_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setInstrumentoId($instrumento_id) {
		$this->instrumento_id = $instrumento_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getRecursoId() {
		return $this->recurso_id;
		}

	/**
	 * @param null $recurso_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setRecursoId($recurso_id) {
		$this->recurso_id = $recurso_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getProblemaId() {
		return $this->problema_id;
		}

	/**
	 * @param null $problema_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setProblemaId($problema_id) {
		$this->problema_id = $problema_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getDemandaId() {
		return $this->demanda_id;
		}

	/**
	 * @param null $demanda_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setDemandaId($demanda_id) {
		$this->demanda_id = $demanda_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getProgramaId() {
		return $this->programa_id;
		}

	/**
	 * @param null $programa_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setProgramaId($programa_id) {
		$this->programa_id = $programa_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getLicaoId() {
		return $this->licao_id;
		}

	/**
	 * @param null $licao_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setLicaoId($licao_id) {
		$this->licao_id = $licao_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getEventoId() {
		return $this->evento_id;
		}

	/**
	 * @param null $evento_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setEventoId($evento_id) {
		$this->evento_id = $evento_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getLinkId() {
		return $this->link_id;
		}

	/**
	 * @param null $link_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setLinkId($link_id) {
		$this->link_id = $link_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getAvaliacaoId() {
		return $this->avaliacao_id;
		}

	/**
	 * @param null $avaliacao_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setAvaliacaoId($avaliacao_id) {
		$this->avaliacao_id = $avaliacao_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getTgnId() {
		return $this->tgn_id;
		}

	/**
	 * @param null $tgn_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setTgnId($tgn_id) {
		$this->tgn_id = $tgn_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getBrainstormId() {
		return $this->brainstorm_id;
		}

	/**
	 * @param null $brainstorm_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setBrainstormId($brainstorm_id) {
		$this->brainstorm_id = $brainstorm_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getGutId() {
		return $this->gut_id;
		}

	/**
	 * @param null $gut_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setGutId($gut_id) {
		$this->gut_id = $gut_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getCausaEfeitoId() {
		return $this->causa_efeito_id;
		}

	/**
	 * @param null $causa_efeito_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setCausaEfeitoId($causa_efeito_id) {
		$this->causa_efeito_id = $causa_efeito_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getArquivoId() {
		return $this->arquivo_id;
		}

	/**
	 * @param null $arquivo_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setArquivoId($arquivo_id) {
		$this->arquivo_id = $arquivo_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getForumId() {
		return $this->forum_id;
		}

	/**
	 * @param null $forum_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setForumId($forum_id) {
		$this->forum_id = $forum_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getChecklistId() {
		return $this->checklist_id;
		}

	/**
	 * @param null $checklist_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setChecklistId($checklist_id) {
		$this->checklist_id = $checklist_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getAgendaId() {
		return $this->agenda_id;
		}

	/**
	 * @param null $agenda_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setAgendaId($agenda_id) {
		$this->agenda_id = $agenda_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getAgrupamentoId() {
		return $this->agrupamento_id;
		}

	/**
	 * @param null $agrupamento_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setAgrupamentoId($agrupamento_id) {
		$this->agrupamento_id = $agrupamento_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPatrocinadorId() {
		return $this->patrocinador_id;
		}

	/**
	 * @param null $patrocinador_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPatrocinadorId($patrocinador_id) {
		$this->patrocinador_id = $patrocinador_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getTemplateId() {
		return $this->template_id;
		}

	/**
	 * @param null $template_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setTemplateId($template_id) {
		$this->template_id = $template_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPainelId() {
		return $this->painel_id;
		}

	/**
	 * @param null $painel_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPainelId($painel_id) {
		$this->painel_id = $painel_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPainelOdometroId() {
		return $this->painel_odometro_id;
		}

	/**
	 * @param null $painel_odometro_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPainelOdometroId($painel_odometro_id) {
		$this->painel_odometro_id = $painel_odometro_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getPainelComposicaoId() {
		return $this->painel_composicao_id;
		}

	/**
	 * @param null $painel_composicao_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPainelComposicaoId($painel_composicao_id) {
		$this->painel_composicao_id = $painel_composicao_id;

		return $this;
		}

	/**
	 * @return null
	 */
	public function getTrId() {
		return $this->tr_id;
		}



	/**
	 * @param null $tr_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setTrId($tr_id) {
		$this->tr_id = $tr_id;

		return $this;
		}




	/**
	 * @return null
	 */
	public function getMeId() {
		return $this->me_id;
		}


	/**
	 * @param null $me_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setMeId($me_id) {
		$this->me_id = $me_id;

		return $this;
		}




	/**
	 * @return null
	 */
	public function getPlano_acao_itemId() {
		return $this->plano_acao_item_id;
		}


	/**
	 * @param null $plano_acao_item_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPlano_acao_itemId($plano_acao_item_id) {
		$this->plano_acao_item_id = $plano_acao_item_id;

		return $this;
		}



	/**
	 * @return null
	 */
	public function getBeneficioId() {
		return $this->beneficio_id;
		}


	/**
	 * @param null $beneficio_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setBeneficioId($beneficio_id) {
		$this->beneficio_id = $beneficio_id;

		return $this;
		}
	
	
	
	/**
	 * @return null
	 */
	public function getPainel_slideshowId() {
		return $this->painel_slideshow_id;
		}


	/**
	 * @param null $painel_slideshow_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPainel_slideshowId($painel_slideshow_id) {
		$this->painel_slideshow_id = $painel_slideshow_id;

		return $this;
		}
	
	
	/**
	 * @return null
	 */
	public function getProjeto_viabilidadeId() {
		return $this->projeto_viabilidade_id;
		}


	/**
	 * @param null $projeto_viabilidade_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setProjeto_viabilidadeId($projeto_viabilidade_id) {
		$this->projeto_viabilidade_id = $projeto_viabilidade_id;

		return $this;
		}
	
	
	/**
	 * @return null
	 */
	public function getProjeto_aberturaId() {
		return $this->projeto_abertura_id;
		}


	/**
	 * @param null $projeto_abertura_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setProjeto_aberturaId($projeto_abertura_id) {
		$this->projeto_abertura_id = $projeto_abertura_id;

		return $this;
		}
	
	
	/**
	 * @return null
	 */
	public function getPgId() {
		return $this->pg_id;
		}


	/**
	 * @param null $pg_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPgId($pg_id) {
		$this->pg_id = $pg_id;
		return $this;
		}
	
	
	/**
	 * @return null
	 */
	public function getSstiId() {
		return $this->ssti_id;
		}
	/**
	 * @param null $ssti_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setSstiId($ssti_id) {
		$this->ssti_id = $ssti_id;
		return $this;
		}
		
		
	/**
	 * @return null
	 */
	public function getLaudoId() {
		return $this->laudo_id;
		}
	/**
	 * @param null $laudo_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setLaudoId($laudo_id) {
		$this->laudo_id = $laudo_id;
		return $this;
		}
		
		
	/**
	 * @return null
	 */
	public function getTreloId() {
		return $this->trelo_id;
		}
	/**
	 * @param null $trelo_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setTreloId($trelo_id) {
		$this->trelo_id = $trelo_id;
		return $this;
		}
		
		
	/**
	 * @return null
	 */
	public function getTrelo_cartaoId() {
		return $this->trelo_cartao_id;
		}
	/**
	 * @param null $trelo_cartao_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setTrelo_cartaoId($trelo_cartao_id) {
		$this->trelo_cartao_id = $trelo_cartao_id;
		return $this;
		}
		
		
		
	/**
	 * @return null
	 */
	public function getPdclId() {
		return $this->pdcl_id;
		}
	/**
	 * @param null $pdcl_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPdclId($pdcl_id) {
		$this->pdcl_id = $pdcl_id;
		return $this;
		}
		
		
	/**
	 * @return null
	 */
	public function getPdcl_itemId() {
		return $this->pdcl_item_id;
		}
	/**
	 * @param null $pdcl_item_id
	 *
	 * @return FiltrosProjetoBuilder
	 */
	public function setPdcl_itemId($pdcl_item_id) {
		$this->pdcl_item_id = $pdcl_item_id;
		return $this;
		}	
					
		
		
		
		
		
		


}

class CProjeto extends CAplicObjeto {
  public $projeto_id = null;
  public $projeto_cia = null;
  public $projeto_dept = null;
  public $projeto_responsavel = null;
  public $projeto_criador = null;
  public $projeto_supervisor = null;
  public $projeto_autoridade = null;
  public $projeto_cliente = null;
  public $projeto_atualizador = null;
  public $projeto_superior = null;
  public $projeto_superior_original = null;
  public $projeto_perspectiva = null;
  public $projeto_tema = null;
  public $projeto_objetivo_estrategico = null;
  public $projeto_estrategia = null;
  public $projeto_indicador = null;
  public $projeto_meta = null;
  public $projeto_fator = null;
  public $projeto_pratica = null;
  public $projeto_acao = null;
  public $projeto_canvas = null;
  public $projeto_nome = null;
  public $projeto_nome_curto = null;
  public $projeto_codigo = null;
  public $projeto_sequencial = null;
  public $projeto_url = null;
  public $projeto_url_externa = null;
  public $projeto_data_inicio = null;
  public $projeto_data_fim = null;
  public $projeto_fim_atualizado = null;
  public $projeto_status = null;
  public $projeto_fase = null;
  public $projeto_percentagem = null;
  public $projeto_custo = null;
  public $projeto_gasto = null;
  public $projeto_cor = null;
  public $projeto_descricao = null;
  public $projeto_objetivos = null;
  public $projeto_observacao = null;
  public $projeto_como = null;
  public $projeto_localizacao = null;
  public $projeto_meta_custo = null;
  public $projeto_custo_atual = null;
  public $projeto_privativo = null;
  public $projeto_prioridade = null;
  public $projeto_tipo = null;
  public $projeto_data_chave = null;
  public $projeto_data_chave_pos = null;
  public $projeto_tarefa_chave = null;
  public $projeto_especial = null;
  public $projeto_criado = null;
  public $projeto_atualizado = null;
  public $projeto_data_fim_ajustada = null;
  public $projeto_status_comentario = null;
  public $projeto_subprioridade = null;
  public $projeto_data_fim_ajustada_usuario = null;
  public $projeto_acesso = null;
  public $projeto_endereco1 = null;
  public $projeto_endereco2 = null;
  public $projeto_cidade = null;
  public $projeto_estado = null;
  public $projeto_cep = null;
  public $projeto_pais = null;
  public $projeto_latitude = null;
  public $projeto_longitude = null;
  public $projeto_setor = null;
  public $projeto_segmento = null;
  public $projeto_intervencao = null;
  public $projeto_tipo_intervencao = null;
  public $projeto_ano = null;
  public $projeto_portfolio = null;
  public $projeto_plano_operativo = null;
  public $projeto_comunidade = null;
  public $projeto_social = null;
  public $projeto_social_acao = null;
	public $projeto_principal_indicador = null;
	public $projeto_justificativa = null;
	public $projeto_objetivo = null;
	public $projeto_objetivo_especifico = null;
	public $projeto_escopo = null;
	public $projeto_nao_escopo = null;
	public $projeto_premissas = null;
	public $projeto_restricoes = null;
	public $projeto_orcamento = null;
	public $projeto_beneficiario = null;
	public $projeto_beneficio = null;
	public $projeto_produto = null;
	public $projeto_requisito = null;
	public $projeto_fonte = null;
	public $projeto_regiao = null;
	public $projeto_alerta = null;
	public $projeto_trava_data = null;
	public $projeto_fisico_registro = null;
	public $projeto_aprova_registro = null;
	public $portfolio_externo=null;
	public $projeto_aprovado = null;
	public $projeto_moeda = null;
	public $projeto_ativo = null;
	public $baseline_id = null;
	public $projeto_encerramento = null;

	public function __construct($baseline_id=null, $portfolio_externo=null, $data_para_baseline=null, $baseline_projeto=null) {
		if ($baseline_id) {
			$this->baseline_id=$baseline_id;
			parent::__construct('baseline_projetos', 'projeto_id','baseline_id');
			}
		
		elseif ($data_para_baseline && $data_para_baseline!=date('Y-m-d')) {
			$sql = new BDConsulta;
			$sql->adTabela('baseline');
			$sql->adCampo('baseline_id');
			$sql->adOnde('baseline_projeto_id = '.(int)$baseline_projeto);
			$sql->adOrdem('ABS(DATEDIFF(baseline_data, \''.$data_para_baseline.'\'))');
			$sql->setLimite(0, 1);
			$baseline_id=$sql->resultado();
			$sql->limpar();
			if ($baseline_id) {
				$this->baseline_id=$baseline_id;
				parent::__construct('baseline_projetos', 'projeto_id','baseline_id');
				} 
			else {
				parent::__construct('projetos', 'projeto_id');
				}
			}
		else {
			parent::__construct('projetos', 'projeto_id');
			}
			
			
			
		if ($portfolio_externo){
			$this->projeto_portfolio=1;
			$this->portfolio_externo=$portfolio_externo;
			}
		}

	public function load( $id = null, $tira = true, $id2 = null) {
		$resultado = parent::load($id, $tira, $id2);
		return $resultado;
		}


	public function getTarefasCriticas( $projeto_id = null, $limite = 1) {
		$projeto_id = !empty($projeto_id) ? $projeto_id : $this->projeto_id;
		$sql = new BDConsulta;
		$sql->adTabela('tarefas');
		$sql->adOnde('tarefa_projeto = '.(int)$projeto_id.' AND tarefa_fim IS NOT NULL');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		$sql->adOrdem('tarefa_fim DESC');
		$sql->setLimite($limite);
		return $sql->Lista();
		}

	public function armazenar( $atualizarNulos = false) {
		global $Aplic;

		$this->arrumarTodos();
		$msg = $this->check();
		if ($msg)	return get_class($this).'::checagem para armazenar falhou - '.$msg;

		$sql = new BDConsulta;
		if ($this->projeto_id && $Aplic->profissional) {
			$sql->adTabela('projeto_portfolio');
			$sql->adCampo('count(projeto_portfolio_filho)');
			$sql->adOnde('projeto_portfolio_pai ='.$this->projeto_id);
		  $ja_existe = $sql->Resultado();
		  $sql->limpar();
			$this->projeto_portfolio=($ja_existe ? 1 : 0);
			$ret = $sql->atualizarObjeto('projetos', $this, 'projeto_id', true, array('baseline_id'));
			$sql->limpar();
			}
		elseif ($this->projeto_id && !$Aplic->profissional){
			$ret = $sql->atualizarObjeto('projetos', $this, 'projeto_id', true, array('baseline_id'));
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('projetos', $this, 'projeto_id');
			$sql->limpar();
			}


		$sql->setExcluir('projeto_depts');
		$sql->adOnde('projeto_id='.(int)$this->projeto_id);
		$sql->exec();
		$sql->limpar();
		$depts=getParam($_REQUEST, 'projeto_depts', '');
		$depts=explode(',', $depts);
		if (count($depts)) {
			foreach ($depts as $secao) {
				if ($secao){
					$sql->adTabela('projeto_depts');
					$sql->adInserir('projeto_id', $this->projeto_id);
					$sql->adInserir('departamento_id', $secao);
					$sql->exec();
					$sql->limpar();
					}
				}
			}

		$sql->setExcluir('projeto_cia');
		$sql->adOnde('projeto_cia_projeto='.(int)$this->projeto_id);
		$sql->exec();
		$sql->limpar();
		$cias=getParam($_REQUEST, 'projeto_cias', '');
		$cias=explode(',', $cias);
		if (count($cias)) {
			foreach ($cias as $cia_id) {
				if ($cia_id){
					$sql->adTabela('projeto_cia');
					$sql->adInserir('projeto_cia_projeto', $this->projeto_id);
					$sql->adInserir('projeto_cia_cia', $cia_id);
					$sql->exec();
					$sql->limpar();
					}
				}
			}

		$sql->setExcluir('municipio_lista');
		$sql->adOnde('municipio_lista_projeto='.(int)$this->projeto_id);
		$sql->adOnde('municipio_lista_tarefa IS NULL');
		$sql->exec();
		$sql->limpar();

		$municipios=getParam($_REQUEST, 'projeto_municipios', '');
		$municipios=explode(',', $municipios);

		if (count($municipios)) {
			foreach ($municipios as $municipio_id) {
				if ($municipio_id){
					$sql->adTabela('municipio_lista');
					$sql->adInserir('municipio_lista_projeto', $this->projeto_id);
					$sql->adInserir('municipio_lista_municipio', $municipio_id);
					$sql->exec();
					$sql->limpar();
					}
				}
			}

		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('projeto_contatos');
			$sql->adAtualizar('projeto_id', (int)$this->projeto_id);
			$sql->adAtualizar('uuid', null);
			$sql->adOnde('uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			$sql->adTabela('projeto_integrantes');
			$sql->adAtualizar('projeto_id', (int)$this->projeto_id);
			$sql->adAtualizar('uuid', null);
			$sql->adOnde('uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			if ($Aplic->profissional){
				$sql->adTabela('projeto_stakeholder');
				$sql->adAtualizar('projeto_stakeholder_projeto', (int)$this->projeto_id);
				$sql->adAtualizar('projeto_stakeholder_uuid', null);
				$sql->adOnde('projeto_stakeholder_uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();

				$sql->adTabela('projeto_portfolio');
				$sql->adAtualizar('projeto_portfolio_pai', (int)$this->projeto_id);
				$sql->adAtualizar('uuid', null);
				$sql->adOnde('uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();

				$sql->adTabela('projeto_gestao');
				$sql->adAtualizar('projeto_gestao_projeto', (int)$this->projeto_id);
				$sql->adAtualizar('projeto_gestao_uuid', null);
				$sql->adOnde('projeto_gestao_uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();

				$sql->adTabela('priorizacao');
				$sql->adAtualizar('priorizacao_projeto', (int)$this->projeto_id);
				$sql->adAtualizar('priorizacao_uuid', null);
				$sql->adOnde('priorizacao_uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();
				
				$sql->adTabela('assinatura');
				$sql->adAtualizar('assinatura_projeto', (int)$this->projeto_id);
				$sql->adAtualizar('assinatura_uuid', null);
				$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();
	
				$sql->adTabela('priorizacao');
				$sql->adAtualizar('priorizacao_projeto', (int)$this->projeto_id);
				$sql->adAtualizar('priorizacao_uuid', null);
				$sql->adOnde('priorizacao_uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();
				}
			}


		//verificar aprovacao
		if ($Aplic->profissional) {
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_projeto='.(int)$this->projeto_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_projeto='.(int)$this->projeto_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_projeto='.(int)$this->projeto_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('projetos');
			$sql->adAtualizar('projeto_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('projeto_id='.(int)$this->projeto_id);
			$sql->exec();
			$sql->limpar();
			}
			

		$sql->adTabela('tarefas');
		$sql->adAtualizar('tarefa_acesso',$this->projeto_acesso);
		$sql->adOnde('tarefa_projeto = '.(int)$this->projeto_id.' AND tarefa_acesso < '.(int)$this->projeto_acesso);
		$sql->exec();
		$sql->limpar();

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('projetos', $this->projeto_id, 'projeto_id');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->projeto_id);

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	public function excluir( $oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('projeto_id', null)==$this->projeto_id) $Aplic->setEstado('projeto_id', null);
    parent::excluir();
		return null;
		}


	public function podeAcessar() {
		$valor=permiteAcessar($this->projeto_acesso, $this->projeto_id, 0);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditar($this->projeto_acesso, $this->projeto_id, 0);
		return $valor;
		}


	public function disparo_observador($acao='fisico', $primeiro = true){
		//Quem faz uso deste tema em cálculos de percentagem
		$sql = new BDConsulta;
		$sql->adTabela('projeto_observador');
		$sql->adCampo('projeto_observador.*');
		$sql->adOnde('projeto_observador_projeto ='.(int)$this->projeto_id);
		if ($acao) $sql->adOnde('projeto_observador_acao =\''.$acao.'\'');
		$lista = $sql->lista();
		$sql->limpar();

		$qnt_programa=0;
		$qnt_plano_gestao=0;
		$qnt_perspectiva=0;
		$qnt_tema=0;
		$qnt_objetivo=0;
		$qnt_me=0;
		$qnt_fator=0;
		$qnt_estrategia=0;
		$qnt_meta=0;
		$qnt_acao=0;
		$qnt_risco=0;
		$qnt_risco_resposta=0;
		$qnt_pdcl_item=0;
		
		foreach($lista as $linha){

			if ($linha['projeto_observador_portfolio']){
				$obj= new CProjeto();
				$obj->load($linha['projeto_observador_portfolio']);
				if (method_exists($obj, $linha['projeto_observador_metodo'])){
					$obj->{$linha['projeto_observador_metodo']}();
					}
				}
				
			elseif ($linha['projeto_observador_risco']){
				if (!($qnt_risco++)) require_once BASE_DIR.'/modulos/praticas/risco_pro.class.php';
				$obj= new CRisco();
				$obj->load($linha['projeto_observador_risco']);
				if (method_exists($obj, $linha['projeto_observador_metodo'])){
					$obj->{$linha['projeto_observador_metodo']}();
					}
				}	
			
			elseif ($linha['projeto_observador_risco_resposta']){
				if (!($qnt_risco_resposta++)) require_once BASE_DIR.'/modulos/praticas/risco_resposta_pro.class.php';
				$obj= new CRiscoResposta();
				$obj->load($linha['projeto_observador_risco_resposta']);
				if (method_exists($obj, $linha['projeto_observador_metodo'])){
					$obj->{$linha['projeto_observador_metodo']}();
					}
				}		
				
			elseif ($linha['projeto_observador_pdcl_item']){
				if (!($qnt_pdcl_item++)) require_once BASE_DIR.'/modulos/pdcl/pdcl_item.class.php';
				$obj= new CPDCLItem();
				$obj->load($linha['projeto_observador_pdcl_item']);
				if (method_exists($obj, $linha['projeto_observador_metodo'])){
					$obj->{$linha['projeto_observador_metodo']}();
					}
				}			
				
			elseif ($linha['projeto_observador_programa']){
				if (!($qnt_programa++)) require_once BASE_DIR.'/modulos/projetos/programa_pro.class.php';
				$obj= new CPrograma();
				$obj->load($linha['projeto_observador_programa']);
				if (method_exists($obj, $linha['projeto_observador_metodo'])){
					$obj->{$linha['projeto_observador_metodo']}();
					}
				}
			elseif ($linha['projeto_observador_plano_gestao']){
				if (!($qnt_plano_gestao++)) require_once BASE_DIR.'/modulos/praticas/gestao/gestao.class.php';
				$obj= new CGestao();
				$obj->load($linha['projeto_observador_plano_gestao']);
				if (method_exists($obj, $linha['projeto_observador_metodo'])){
					$obj->{$linha['projeto_observador_metodo']}();
					}
				}		
			elseif ($linha['projeto_observador_perspectiva']){
				if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
				$obj= new CPerspectiva();
				$obj->load($linha['projeto_observador_perspectiva']);
				if (method_exists($obj, $linha['projeto_observador_metodo'])){
					$obj->{$linha['projeto_observador_metodo']}();
					}
				}
			elseif ($linha['projeto_observador_tema']){
				if (!($qnt_tema++)) require_once BASE_DIR.'/modulos/praticas/tema.class.php';
				$obj= new CTema();
				$obj->load($linha['projeto_observador_tema']);
				if (method_exists($obj, $linha['projeto_observador_metodo'])){
					$obj->{$linha['projeto_observador_metodo']}();
					}
				}
			elseif ($linha['projeto_observador_objetivo']){
				if (!($qnt_objetivo++)) require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
				$obj= new CObjetivo();
				$obj->load($linha['projeto_observador_objetivo']);
				if (method_exists($obj, $linha['projeto_observador_metodo'])){
					$obj->{$linha['projeto_observador_metodo']}();
					}
				}
			elseif ($linha['projeto_observador_me']){
				if (!($qnt_me++)) require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
				$obj= new CMe();
				$obj->load($linha['projeto_observador_me']);
				if (method_exists($obj, $linha['projeto_observador_metodo'])){
					$obj->{$linha['projeto_observador_metodo']}();
					}
				}

			elseif ($linha['projeto_observador_fator']){
				if (!($qnt_fator++)) require_once BASE_DIR.'/modulos/praticas/fator.class.php';
				$obj= new CFator();
				$obj->load($linha['projeto_observador_fator']);
				if (method_exists($obj, $linha['projeto_observador_metodo'])){
					$obj->{$linha['projeto_observador_metodo']}();
					}
				}
			elseif ($linha['projeto_observador_estrategia']){
				if (!($qnt_estrategia++)) require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
				$obj= new CEstrategia();
				$obj->load($linha['projeto_observador_estrategia']);
				if (method_exists($obj, $linha['projeto_observador_metodo'])){
					$obj->{$linha['projeto_observador_metodo']}();
					}
				}
			elseif ($linha['projeto_observador_meta']){
				if (!($qnt_meta++)) require_once BASE_DIR.'/modulos/praticas/meta.class.php';
				$obj= new CMeta();
				$obj->load($linha['projeto_observador_meta']);
				if (method_exists($obj, $linha['projeto_observador_metodo'])){
					$obj->{$linha['projeto_observador_metodo']}();
					}
				}
			elseif ($linha['projeto_observador_acao']){
				if (!($qnt_acao++)) require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
				$obj= new CPlanoAcao();
				$obj->load($linha['projeto_observador_acao']);
				if (method_exists($obj, $linha['projeto_observador_metodo'])){
					$obj->{$linha['projeto_observador_metodo']}();
					}
				}
			}
		
		
		
		$this->observador_portfolio($primeiro);	
		}
		
		
	protected function observador_portfolio($primeiro){
    static $ids_processados = array();
    if ($primeiro) $ids_processados=array();//limpa
    if(array_key_exists($this->projeto_id, $ids_processados)){
	    return;
	    }
    //que portfólios usam este projeto
    $lista_portfolio=array();
		projeto_portfolios($this->projeto_id, $lista_portfolio);
		//processar cada portfolio superior
		
    foreach($lista_portfolio as $portfolio_atual){
    	$ids_processados[$portfolio_atual]=$portfolio_atual;
    	portfolio_porcentagem($portfolio_atual);
    	$portfolio = new CProjeto($portfolio_atual);
    	$portfolio->projeto_id=$portfolio_atual;
      $portfolio->disparo_observador('fisico', false);
    	}     
    } 	
		
		
		
		
		
		
		
		

	public function check() {
		$this->projeto_acesso = intval($this->projeto_acesso);
		$this->projeto_ativo = intval($this->projeto_ativo);
		$this->projeto_privativo = intval($this->projeto_privativo);
		$this->projeto_meta_custo = $this->projeto_meta_custo ? $this->projeto_meta_custo : 0.00;
		$this->projeto_custo_atual = $this->projeto_custo_atual ? $this->projeto_custo_atual : 0.00;
		if (empty($this->projeto_data_fim)) $this->projeto_data_fim = null;
		return null;
		}

	public function getCodigo( $completo=true){
		if (!$this->projeto_sequencial) $this->setSequencial();
		if ($this->projeto_setor && $this->projeto_sequencial){
			if ($this->projeto_sequencial<10) $sequencial='000'.$this->projeto_sequencial;
			elseif ($this->projeto_sequencial<100) $sequencial='00'.$this->projeto_sequencial;
			elseif ($this->projeto_sequencial<1000) $sequencial='0'.$this->projeto_sequencial;
			else $sequencial=$this->projeto_sequencial;
			return $this->projeto_setor.($completo && $this->projeto_segmento ? '.' : '').substr($this->projeto_segmento, 2).($completo && $this->projeto_intervencao ? '.' : '').substr($this->projeto_intervencao, 4).($completo && $this->projeto_tipo_intervencao ? '.' : '').substr($this->projeto_tipo_intervencao, 6).($completo ? '.' : '').$sequencial.($completo  && $this->projeto_ano? '/' : '').$this->projeto_ano;
			}
		else return $this->projeto_codigo;
		}


	public function setSequencial(){
		if (!$this->projeto_sequencial){
			$sql = new BDConsulta;
			$sql->adTabela('projetos');
			$sql->adCampo('max(projeto_sequencial)');
			$sql->adOnde('projeto_cia='.(int)$this->projeto_cia);
			if ($this->projeto_ano) $sql->adOnde('projeto_ano=\''.$this->projeto_ano.'\'');
			$maior_sequencial= (int)$sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projetos');
			$sql->adAtualizar('projeto_sequencial', ($maior_sequencial+1));
			$sql->adOnde('projeto_id ='.(int)$this->projeto_id);
			$retorno=$sql->exec();
			$sql->limpar();
			$this->projeto_sequencial=($maior_sequencial+1);
			return $retorno;
			}
		}

	public function getSetor(){
		if ($this->projeto_setor){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Setor"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_setor.'"');
			$projeto_setor= $sql->Resultado();
			$sql->limpar();
			return $projeto_setor;
			}
		else return '';
		}

	public function getSegmento(){
		if ($this->projeto_segmento){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Segmento"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_segmento.'"');
			$projeto_segmento= $sql->Resultado();
			$sql->limpar();
			return $projeto_segmento;
			}
		else return '';
		}

	public function getIntervencao(){
		if ($this->projeto_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Intervencao"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_intervencao.'"');
			$projeto_intervencao= $sql->Resultado();
			$sql->limpar();
			return $projeto_intervencao;
			}
		else return '';
		}

	public function getTipoIntervencao(){
		if ($this->projeto_tipo_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_tipo_intervencao.'"');
			$projeto_tipo_intervencao= $sql->Resultado();
			$sql->limpar();
			return $projeto_tipo_intervencao;
			}
		else return '';
		}

	public function custo_estimado( $baseline_id=false, $indicador_id=false){
		global $Aplic, $config;

		$filtro_extra=($indicador_id && $Aplic->profissional ? filtro_indicador_projeto($indicador_id) : '');

		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefa_custos','tarefa_custos');
		$sql->adCampo('SUM((tarefa_custos_quantidade*tarefa_custos_custo*tarefa_custos_cotacao)*((100+tarefa_custos_bdi)/100)) AS total');
		if ($lista) $sql->adOnde('tarefa_custos_tarefa IN ('.$lista.')');
		else $sql->adOnde('tarefa_custos_tarefa IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$this->projeto_id.')');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('tarefa_custos.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		if ($filtro_extra) {
			$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas', 'tarefas.tarefa_id=tarefa_custos_tarefa');
			$sql->adOnde($filtro_extra);
			$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
			}
		if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('tarefa_custos_aprovado = 1');
		$total1=$sql->Resultado();
		$sql->limpar();
		
		$total2=0;
			if ($Aplic->profissional){
			$sql->adTabela('projeto_custo');
			$sql->adCampo('SUM((projeto_custo_quantidade*projeto_custo_custo*projeto_custo_cotacao)*((100+projeto_custo_bdi)/100)) AS total');
			if ($lista) $sql->adOnde('projeto_custo_projeto IN ('.$lista.')');
			else $sql->adOnde('projeto_custo_projeto='.(int)$this->projeto_id);
			if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('projeto_custo_aprovado = 1');
			$total2=$sql->Resultado();
			$sql->limpar();
			}
		
		return ($total1+$total2);
		}


	public function recurso_gasto( $baseline_id=false, $indicador_id=false, $financeiro=null){
		return recurso_gasto($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, null, $indicador_id, $financeiro, $this->portfolio_externo);
		}

	public function recurso_valor_agregado( $baseline_id=false, $indicador_id=false){
		return recurso_valor_agregado($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), false, $indicador_id, $this->portfolio_externo);
		}

	public function recurso_EPT( $baseline_id=false, $indicador_id=false){
		return recurso_EPT($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, $indicador_id, $this->portfolio_externo);
		}

	public function ata_acao( $baseline_id=false, $indicador_id=false){
		return ata_acao($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, $indicador_id, $this->portfolio_externo);
		}



	public function recurso_gasto_periodo( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0){
		global $Aplic, $config;

		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}

		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_ponto','recurso_ponto');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas','recurso_ponto_tarefa=tarefa_id');
		$sql->adCampo('SUM(recurso_ponto_duracao*recurso_ponto_valor_hora*recurso_ponto_quantidade*(recurso_ponto_percentual/100)) AS total');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		if ($config['aprova_recurso']) $sql->adOnde('recurso_ponto_aprovado = 1');
		$total1=$sql->Resultado();
		$sql->limpar();

		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_ponto_gasto','recurso_ponto_gasto');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_ponto','recurso_ponto','recurso_ponto_gasto_ponto=recurso_ponto_id');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas','recurso_ponto_tarefa=tarefa_id');
		$sql->adCampo('SUM(recurso_ponto_gasto_quantidade*recurso_ponto_gasto_gasto) AS total');
		$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id))	{
			$sql->adOnde('recurso_ponto_gasto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$sql->adOnde('recurso_ponto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			}
		if ($config['aprova_recurso']) $sql->adOnde('recurso_ponto_aprovado = 1');
		$total2=$sql->Resultado();
		$sql->limpar();

		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_ponto','recurso_ponto');
		$sql->esqUnir('eventos','eventos','evento_id=recurso_ponto_evento');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas','evento_tarefa=tarefa_id');
		$sql->adCampo('SUM(recurso_ponto_duracao*recurso_ponto_valor_hora*recurso_ponto_quantidade*(recurso_ponto_percentual/100)) AS total');
		$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('recurso_ponto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		if ($config['aprova_recurso']) $sql->adOnde('recurso_ponto_aprovado = 1');
		$total3=$sql->Resultado();
		$sql->limpar();

		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_ponto_gasto','recurso_ponto_gasto');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_ponto','recurso_ponto','recurso_ponto_gasto_ponto=recurso_ponto_id');
		$sql->esqUnir('eventos','eventos','evento_id=recurso_ponto_evento');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas','evento_tarefa=tarefa_id');
		$sql->adCampo('SUM(recurso_ponto_gasto_quantidade*recurso_ponto_gasto_gasto) AS total');
		$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id))	{
			$sql->adOnde('recurso_ponto_gasto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$sql->adOnde('recurso_ponto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			}
		if ($config['aprova_recurso']) $sql->adOnde('recurso_ponto_aprovado = 1');
		$total4=$sql->Resultado();
		$sql->limpar();

		$total=$total1+$total2+$total3+$total4;

		if ($ate_data_atual) {
			//No meio da execução
			$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
			$sql->adCampo('tarefa_id, tarefa_inicio, tarefa_cia, tarefa_duracao, tarefa_projeto');
			$sql->adOnde('tarefa_projetoex_id IS NULL');
			if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
			else $sql->adOnde('tarefa_id IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$this->projeto_id.')');
			$sql->adOnde('tarefa_inicio<= \''.$data_final.'\'');
			$sql->adOnde('tarefa_fim > \''.$data_final.'\'');
			if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$em_execucao = $sql->Lista();
			$sql->limpar();
			foreach($em_execucao as $linha) {

				if ($data_inicial){
					$inicio=(strtotime($data_inicial) > strtotime($linha['tarefa_inicio']) ? $data_inicial : $linha['tarefa_inicio']);
					}
				else $inicio=$linha['tarefa_inicio'];

				$horas=horas_periodo($inicio, $data_final, $linha['tarefa_cia'], null, $linha['tarefa_projeto']);
				$porcentagem=($linha['tarefa_duracao'] > 0 ? $horas/$linha['tarefa_duracao'] : 1);

				$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_ponto','recurso_ponto');
				$sql->adCampo('SUM(recurso_ponto_duracao*recurso_ponto_valor_hora*recurso_ponto_quantidade*(recurso_ponto_percentual/100)) AS total');
				$sql->adOnde('recurso_ponto_tarefa='.$linha['tarefa_id']);
				if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
				if ($config['aprova_recurso']) $sql->adOnde('recurso_ponto_aprovado = 1');
				$total1=$sql->Resultado();
				$sql->limpar();

				$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_ponto_gasto','recurso_ponto_gasto');
				$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_ponto','recurso_ponto','recurso_ponto_gasto_ponto=recurso_ponto_id');
				$sql->adCampo('SUM(recurso_ponto_gasto_quantidade*recurso_ponto_gasto_gasto) AS total');
				$sql->adOnde('recurso_ponto_tarefa='.$linha['tarefa_id']);
				if (($this->baseline_id ? $this->baseline_id : $baseline_id))	{
					$sql->adOnde('recurso_ponto_gasto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
					$sql->adOnde('recurso_ponto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
					}
				if ($config['aprova_recurso']) $sql->adOnde('recurso_ponto_aprovado = 1');
				$total2=$sql->Resultado();
				$sql->limpar();

				$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_ponto','recurso_ponto');
				$sql->esqUnir('eventos','eventos','evento_id=recurso_ponto_evento');
				$sql->adCampo('SUM(recurso_ponto_duracao*recurso_ponto_valor_hora*recurso_ponto_quantidade*(recurso_ponto_percentual/100)) AS total');
				$sql->adOnde('evento_tarefa='.$linha['tarefa_id']);
				if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('recurso_ponto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
				if ($config['aprova_recurso']) $sql->adOnde('recurso_ponto_aprovado = 1');
				$total3=$sql->Resultado();
				$sql->limpar();

				$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_ponto_gasto','recurso_ponto_gasto');
				$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_ponto','recurso_ponto','recurso_ponto_gasto_ponto=recurso_ponto_id');
				$sql->esqUnir('eventos','eventos','evento_id=recurso_ponto_evento');
				$sql->adCampo('SUM(recurso_ponto_gasto_quantidade*recurso_ponto_gasto_gasto) AS total');
				$sql->adOnde('evento_tarefa='.$linha['tarefa_id']);
				if (($this->baseline_id ? $this->baseline_id : $baseline_id))	{
					$sql->adOnde('recurso_ponto_gasto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
					$sql->adOnde('recurso_ponto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
					}
				if ($config['aprova_recurso']) $sql->adOnde('recurso_ponto_aprovado = 1');
				$total4=$sql->Resultado();
				$sql->limpar();

				$total+=($porcentagem*($total1+$total2+$total3+$total4));
				}
			}

		return $total;

		}

	public function pagamento( $baseline_id=0, $tipo=null, $no_ano=true, $inicio='', $fim=''){
		return pagamento($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, $tipo, $no_ano, $inicio, $fim, $this->portfolio_externo);
		}

	public function recurso_previsto( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=false, $indicador_id=false){
		return recurso_previsto($this->projeto_id, $data_final, $data_inicial, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), false, $indicador_id, $this->portfolio_externo);
		}

	public function mao_obra_previsto( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0, $indicador_id=false){
		return mao_obra_previsto($this->projeto_id, $data_final, $data_inicial, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), false, $indicador_id, $this->portfolio_externo);
		}
	
	public function projeto_custo( $baseline_id=false, $indicador_id=false){
		return projeto_custo($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), $indicador_id, $this->portfolio_externo);
		}

	public function custo_previsto( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=false, $indicador_id=false){
		return custo_previsto($this->projeto_id, $data_final, $data_inicial, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), false, $indicador_id, $this->portfolio_externo);
		}

	public function custo_valor_agregado( $baseline_id=false, $indicador_id=false){
		return custo_valor_agregado($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, $indicador_id, $this->portfolio_externo);
		}

	public function custo_EPT( $baseline_id=false, $indicador_id=false){
		return custo_EPT($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, $indicador_id, $this->portfolio_externo);
		}

	public function financeiro_velocidade( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=false, $indicador_id=false){
		return financeiro_velocidade($this->projeto_id, $data_final, $data_inicial, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, $indicador_id, $this->portfolio_externo);
		}


	public function custo_gasto( $baseline_id=false){
		return custo_gasto($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
		}


	public function fisico_previsto( $data='', $ate_data_atual=true, $baseline_id=false, $indicador_id=false){
		return fisico_previsto($this->projeto_id, $data, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : ($this->baseline_id ? $this->baseline_id : $baseline_id)), null, $indicador_id, $this->portfolio_externo);
		}


	public function fisico_velocidade( $data='', $ate_data_atual=true, $baseline_id=false, $indicador_id=false){
		return fisico_velocidade($this->projeto_id, $data, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, $indicador_id, $this->portfolio_externo);
		}



	public function podeExcluir( &$msg='', $oid = null, $unioes = null) {
		return permiteEditar($this->projeto_acesso, $this->projeto_id);
		}



	public function importarTarefas( $de_projeto_id, $data='') {
		global $bd;
		$traducao=array();
		$campos=array('tarefa_superior','tarefa_cia','tarefa_dono','tarefa_criador','tarefa_comunidade','tarefa_social','tarefa_acao');
		$sql = new BDConsulta;
		$sql->adTabela('projetos');
		$sql->adCampo('projeto_data_inicio');
		$sql->adOnde('projeto_id ='.(int)$de_projeto_id);
		$projeto_inicio_importado = $sql->resultado();
		$sql->limpar();
		$sql->adTabela('tarefas');
		$sql->adCampo('MIN(tarefa_inicio)');
		$sql->adOnde('tarefa_projeto ='.(int)$de_projeto_id);
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		$tarefa_inicio_importado = $sql->resultado();
		$sql->limpar();
		$originalData = new CData(($tarefa_inicio_importado ? $tarefa_inicio_importado : $projeto_inicio_importado));
		$sql->adTabela('tarefas');
		$sql->adCampo('MIN(tarefa_inicio)');
		$sql->adOnde('tarefa_projeto ='.(int)$this->projeto_id);
		$inicio_minimo = $sql->resultado();
		$sql->limpar();
		$destData = new CData(($data ? $data : ($inicio_minimo ? $inicio_minimo : $this->projeto_data_inicio)));
		$diferencaTempo = $originalData->dataDiferenca($destData);
		if ($originalData->compare($originalData, $destData) > 0) $diferencaTempo = -1 * $diferencaTempo;
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefas.*');
		$sql->adOnde('tarefa_projeto ='.(int)$de_projeto_id);
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		$tarefas = $sql->lista();
		$sql->limpar();
		$tarefa_inicio=new CData();
		$tarefa_fim=new CData();
		if (count($tarefas)){
	 		foreach ($tarefas as $linha){
	 			$sql->adTabela('tarefas');
	 			$sql->adInserir('tarefa_projeto', $this->projeto_id);

	 			$tarefa_inicio->setData($linha['tarefa_inicio']);
				$tarefa_inicio->adDias($diferencaTempo);
        $sql->adInserir('tarefa_inicio_manual', $tarefa_inicio->format('%Y-%m-%d %H:%M:%S'));
				$sql->adInserir('tarefa_inicio', $tarefa_inicio->format('%Y-%m-%d %H:%M:%S'));
				if ($linha['tarefa_fim']) {
					$tarefa_fim->setData($linha['tarefa_fim']);
					$tarefa_fim->adDias($diferencaTempo);
					$sql->adInserir('tarefa_fim_manual', $tarefa_fim->format('%Y-%m-%d %H:%M:%S'));
          $sql->adInserir('tarefa_fim', $tarefa_fim->format('%Y-%m-%d %H:%M:%S'));
					}
				foreach ($linha as $chave => $valor) {
					if (in_array($chave, $campos) && $valor > 0) $sql->adInserir($chave, $valor);
					elseif ($chave!='tarefa_id' && $chave!='tarefa_projeto' && $chave!='tarefa_inicio' && $chave !='tarefa_fim' && $chave !='tarefa_percentagem') $sql->adInserir($chave, $valor);
					}
		   	$sql->exec();
		   	$nova_tarefa_id=$bd->Insert_ID('tarefas','tarefa_id');
		   	$traducao[$linha['tarefa_id']]=$nova_tarefa_id;
				$sql->limpar();
				}
			}
			//acertar a tarefa superior
	  $sql->adTabela('tarefas');
	  $sql->adCampo('tarefa_superior, tarefa_id');
	  $sql->adOnde('tarefa_projeto ='.(int)$this->projeto_id);
	  $sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
	  $tarefas = $sql->lista();
	  $sql->limpar();
	  foreach($tarefas as $linha){
	  	if (isset($traducao[$linha['tarefa_superior']])){
	  		$sql->adTabela('tarefas');
	  		$sql->adAtualizar('tarefa_superior', (int)$traducao[$linha['tarefa_superior']]);
	  		$sql->adOnde('tarefa_id='.(int)$linha['tarefa_id']);
	  		$sql->exec();
	  		$sql->limpar();
	  		}
	  	}
		$this->duplicar_linha_tabela($traducao, 'tarefa_depts', 'tarefa_id');
		$this->duplicar_linha_tabela($traducao, 'tarefa_dependencias', 'dependencias_tarefa_id', 'dependencias_req_tarefa_id');
		$this->duplicar_linha_tabela($traducao, 'tarefa_designados', 'tarefa_id');
		$this->duplicar_linha_tabela($traducao, 'tarefa_custos', 'tarefa_custos_tarefa','','tarefa_custos_id');
		$this->duplicar_linha_tabela($traducao, 'municipio_lista', 'municipio_lista_tarefa','','municipio_lista_id');
		atualizar_percentagem((int)$this->projeto_id);
		}

	public function duplicar_linha_tabela( $traducao, $tabela='', $nome_chave='', $nome_chave2='', $ignorar=''){
		$sql = new BDConsulta;
		$lista_antiga=array();
		foreach($traducao as $chave=> $valor) $lista_antiga[]=$chave;

		$lista_string=implode(',',$lista_antiga);
		$lista=array();
		if ($lista_string){
			$sql->adTabela($tabela);
	    $sql->adCampo('*');
	    $sql->adOnde($nome_chave.' IN ('.implode(',',$lista_antiga).')');
	    $lista = $sql->lista();
	    $sql->limpar();
	  	}
		foreach($lista as $linha){
			$sql->adTabela($tabela);
			foreach($linha as $campo => $valor){
				if ($campo==$nome_chave || $campo==$nome_chave2) $sql->adInserir($campo, $traducao[$valor]);
				elseif ($campo!=$ignorar) $sql->adInserir($campo, $valor);
				}
			$sql->exec();
			$sql->limpar();
			}
		}

	public function getEmpregosObra( $baseline_id=false, $indicador_id=false){
		global $Aplic;
		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas');
		$sql->adCampo('sum(tarefa_emprego_obra)');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_projeto= '.(int)$this->projeto_id);
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));

		if ($indicador_id && $Aplic->profissional){
			$filtro_extra=filtro_indicador_projeto($indicador_id);
			if ($filtro_extra) $sql->adOnde($filtro_extra);
			}
		$quantidade=$sql->Resultado();
		$sql->limpar();
		return $quantidade;
		}

	public function getEmpregosDiretos( $baseline_id=false, $indicador_id=false){
		global $Aplic;
		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas');
		$sql->adCampo('sum(tarefa_emprego_direto)');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_projeto= '.(int)$this->projeto_id);
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));

		if ($indicador_id && $Aplic->profissional){
			$filtro_extra=filtro_indicador_projeto($indicador_id);
			if ($filtro_extra) $sql->adOnde($filtro_extra);
			}

		$quantidade=$sql->Resultado();
		$sql->limpar();
		return $quantidade;
		}

	public function getEmpregosIndiretos( $baseline_id=false, $indicador_id=false){
		global $Aplic;
		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas');
		$sql->adCampo('sum(tarefa_emprego_indireto)');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_projeto= '.(int)$this->projeto_id);
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));

		if ($indicador_id && $Aplic->profissional){
			$filtro_extra=filtro_indicador_projeto($indicador_id);
			if ($filtro_extra) $sql->adOnde($filtro_extra);
			}

		$quantidade=$sql->Resultado();
		$sql->limpar();
		return $quantidade;
		}


	public function getTotalRecursosFinanceiros( $baseline_id=false, $indicador_id=false) {
		global $Aplic;
		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}
		$sql = new BDConsulta();
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_tarefa','recurso_tarefa');
		$sql->esqUnir('recursos','recursos','recurso_tarefa_recurso=recursos.recurso_id');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas','tarefas.tarefa_id=recurso_tarefa_tarefa');
		$sql->adCampo('SUM(recurso_tarefa_quantidade)');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)){
			$sql->adOnde('recurso_tarefa.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$sql->adOnde('tarefas.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			}
		if ($lista) $sql->adOnde('recurso_tarefa_tarefa IN ('.$lista.')');
		if ($indicador_id && $Aplic->profissional){
			$filtro_extra=filtro_indicador_projeto($indicador_id);
			if ($filtro_extra) $sql->adOnde($filtro_extra);
			}
		elseif (!$lista) $sql->adOnde('tarefa_projeto= '.(int)$this->projeto_id);
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		$sql->adOnde('recursos.recurso_tipo=5');
		$sql->adOnde('recurso_tarefa_aprovado=1');
		$total=$sql->Resultado();
		$sql->limpar();

		if ($Aplic->modulo_ativo('financeiro')){
			$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'financeiro_rel_nc', 'financeiro_rel_nc');
			if ($lista) {
				$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas','tarefa_projeto=financeiro_rel_nc_projeto');
				$sql->adOnde('tarefa_id IN ('.$lista.')');
				}
			else $sql->adOnde('financeiro_rel_nc_projeto = '.(int)$this->projeto_id);
			if (($this->baseline_id ? $this->baseline_id : $baseline_id)){
				$sql->adOnde('financeiro_rel_nc.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
				if ($lista) $sql->adOnde('tarefas.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
				}
			$sql->adCampo('SUM(financeiro_rel_nc_valor)');
			$total+=$sql->Resultado();
			$sql->limpar();
			}
		return $total;
		}

	public function gasto_efetuado( $baseline_id=false, $indicador_id=false, $financeiro=null){
		global $Aplic, $config;
		$lista='';
		$vetor=($this->projeto_id ? array($this->projeto_id => $this->projeto_id) : array());
		if ($Aplic->profissional) portfolio_projetos($this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
		$lista=implode(',',$vetor);
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefa_gastos', 'tarefa_gastos');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas', 'tarefas', 'tarefas.tarefa_id=tarefa_gastos.tarefa_gastos_tarefa');

		if ($financeiro=='empenhado') $sql->adCampo('SUM((tarefa_gastos_empenhado*tarefa_gastos_custo*tarefa_gastos_cotacao)*((100+tarefa_gastos_bdi)/100)) AS total');
		elseif ($financeiro=='liquidado') $sql->adCampo('SUM((tarefa_gastos_liquidado*tarefa_gastos_custo*tarefa_gastos_cotacao)*((100+tarefa_gastos_bdi)/100)) AS total');
		elseif ($financeiro=='pago') $sql->adCampo('SUM((tarefa_gastos_pago*tarefa_gastos_custo*tarefa_gastos_cotacao)*((100+tarefa_gastos_bdi)/100)) AS total');
		else $sql->adCampo('SUM((tarefa_gastos_quantidade*tarefa_gastos_custo*tarefa_gastos_cotacao)*((100+tarefa_gastos_bdi)/100)) AS total');

		if ($lista) $sql->adOnde('tarefa_projeto IN ('.$lista.')');
		else $sql->adOnde('tarefa_gastos_tarefa IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$this->projeto_id.')');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) {
			$sql->adOnde('tarefa_gastos.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$sql->adOnde('tarefas.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			}

		if ($indicador_id && $Aplic->profissional){
			$filtro_extra=filtro_indicador_projeto($indicador_id);
			if ($filtro_extra) {
				$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas', 'tarefas', 'tarefas.tarefa_id=tarefa_gastos.tarefa_gastos_tarefa');
				$sql->adOnde($filtro_extra);
				$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
				}
			}
		if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('tarefa_gastos_aprovado = 1');
		$total=$sql->Resultado();
		$sql->limpar();
		return $total;
		}

	public function gasto_efetuado_periodo( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0, $financeiro=null){
		global $Aplic, $config;
		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}

		if (!$data_final) $data_final=date('Y-m-d H:i:s');

		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefa_gastos', 'tarefa_gastos');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas','tarefa_gastos_tarefa=tarefa_id');
		if ($financeiro=='empenhado') $sql->adCampo('SUM((tarefa_gastos_empenhado*tarefa_gastos_custo*tarefa_gastos_cotacao)*((100+tarefa_gastos_bdi)/100)) AS total');
		elseif ($financeiro=='liquidado') $sql->adCampo('SUM((tarefa_gastos_liquidado*tarefa_gastos_custo*tarefa_gastos_cotacao)*((100+tarefa_gastos_bdi)/100)) AS total');
		elseif ($financeiro=='pago') $sql->adCampo('SUM((tarefa_gastos_pago*tarefa_gastos_custo*tarefa_gastos_cotacao)*((100+tarefa_gastos_bdi)/100)) AS total');
		else $sql->adCampo('SUM((tarefa_gastos_quantidade*tarefa_gastos_custo*tarefa_gastos_cotacao)*((100+tarefa_gastos_bdi)/100)) AS total');
		$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$this->projeto_id.')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('tarefa_gastos.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('tarefa_gastos_aprovado = 1');
		$total=$sql->Resultado();
		$sql->limpar();

		if ($ate_data_atual) {
			//No meio da execução
			$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
			$sql->adCampo('tarefa_id, tarefa_inicio, tarefa_cia, tarefa_duracao, tarefa_projeto');
			$sql->adOnde('tarefa_projetoex_id IS NULL');
			if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
			else $sql->adOnde('tarefa_id IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$this->projeto_id.')');
			$sql->adOnde('tarefa_inicio<= \''.$data_final.'\'');
			$sql->adOnde('tarefa_fim > \''.$data_final.'\'');
			if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$em_execucao = $sql->Lista();
			$sql->limpar();
			foreach($em_execucao as $linha) {

				if ($data_inicial){
					$inicio=(strtotime($data_inicial) > strtotime($linha['tarefa_inicio']) ? $data_inicial : $linha['tarefa_inicio']);
					}
				else $inicio=$linha['tarefa_inicio'];

				$horas=horas_periodo($inicio, $data_final, $linha['tarefa_cia'], null, $linha['tarefa_projeto']);
				$porcentagem=($linha['tarefa_duracao'] > 0 ? $horas/$linha['tarefa_duracao'] : 1);
				$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefa_gastos','tarefa_gastos');
				if ($financeiro=='empenhado') $sql->adCampo('SUM((tarefa_gastos_empenhado*tarefa_gastos_custo*tarefa_gastos_cotacao)*((100+tarefa_gastos_bdi)/100)) AS total');
				elseif ($financeiro=='liquidado') $sql->adCampo('SUM((tarefa_gastos_liquidado*tarefa_gastos_custo*tarefa_gastos_cotacao)*((100+tarefa_gastos_bdi)/100)) AS total');
				elseif ($financeiro=='pago') $sql->adCampo('SUM((tarefa_gastos_pago*tarefa_gastos_custo*tarefa_gastos_cotacao)*((100+tarefa_gastos_bdi)/100)) AS total');
				else $sql->adCampo('SUM((tarefa_gastos_quantidade*tarefa_gastos_custo*tarefa_gastos_cotacao)*((100+tarefa_gastos_bdi)/100)) AS total');
				$sql->adOnde('tarefa_gastos_tarefa='.$linha['tarefa_id']);
				if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
				if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('tarefa_gastos_aprovado = 1');
				$gasto_itens=$sql->Resultado();
				$sql->limpar();
				$total+=($porcentagem*$gasto_itens);
				}
			}

		return $total;
		}

	public function gasto_registro( $baseline_id=false, $indicador_id=false){
		global $Aplic, $config;

		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'log','log');
		$sql->esqUnir('custo','custo', 'custo_log=log.log_id');
		$sql->adCampo('SUM((custo_quantidade*custo_custo*custo_cotacao)*((100+custo_bdi)/100)) AS total');
		
		$sql->adOnde('custo_gasto=1');
		if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('log_aprovado = 1');
		
		if ($lista) $sql->adOnde('log_tarefa IN ('.$lista.')');
		else $sql->adOnde('log_tarefa IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id))	$sql->adOnde('log.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));


		if ($indicador_id && $Aplic->profissional){
			$filtro_extra=filtro_indicador_projeto($indicador_id);
			if ($filtro_extra) {
				$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas', 'tarefas', 'tarefas.tarefa_id=log_tarefa');
				$sql->adOnde($filtro_extra);
				$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
				}
			}


		$total=$sql->Resultado();
		$sql->limpar();
		return $total;
		}

	public function gasto_registro_periodo( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0){
		global $Aplic, $config;
		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'log','log');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas','log_tarefa=tarefa_id');
		$sql->esqUnir('custo','custo', 'custo_log=log.log_id');
		$sql->adCampo('SUM((custo_quantidade*custo_custo*custo_cotacao)*((100+custo_bdi)/100)) AS total');
		
		$sql->adOnde('custo_gasto=1');
		if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('log_aprovado = 1');
		
		$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id))	$sql->adOnde('log.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$total=$sql->Resultado();
		$sql->limpar();

		if ($ate_data_atual) {
			//No meio da execução
			$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
			$sql->adCampo('tarefa_id, tarefa_inicio, tarefa_cia, tarefa_duracao, tarefa_projeto');
			$sql->adOnde('tarefa_projetoex_id IS NULL');
			if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
			else $sql->adOnde('tarefa_id IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$this->projeto_id.')');
			$sql->adOnde('tarefa_inicio<= \''.$data_final.'\'');
			$sql->adOnde('tarefa_fim > \''.$data_final.'\'');
			if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$em_execucao = $sql->Lista();
			$sql->limpar();
			foreach($em_execucao as $linha) {

				if ($data_inicial){
					$inicio=(strtotime($data_inicial) > strtotime($linha['tarefa_inicio']) ? $data_inicial : $linha['tarefa_inicio']);
					}
				else $inicio=$linha['tarefa_inicio'];

				$horas=horas_periodo($inicio, $data_final, $linha['tarefa_cia'], null, $linha['tarefa_projeto']);
				$porcentagem=($linha['tarefa_duracao'] > 0 ? $horas/$linha['tarefa_duracao'] : 1);
				$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'log','log');
				
				$sql->esqUnir('custo','custo', 'custo_log=log.log_id');
				$sql->adCampo('SUM((custo_quantidade*custo_custo*custo_cotacao)*((100+custo_bdi)/100)) AS total');
				$sql->adOnde('custo_gasto=1');
				if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('log_aprovado = 1');
				
				$sql->adOnde('log_tarefa='.$linha['tarefa_id']);
				if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
				$gasto_itens=$sql->Resultado();
				$sql->limpar();
				$total+=($porcentagem*$gasto_itens);
				}
			}
		return $total;
		}



	public function homem_hora( $baseline_id=0, $indicador_id=false){
		return homem_hora($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, $indicador_id, $this->portfolio_externo);
		}


	public function gasto( $baseline_id=0, $indicador_id=false, $financeiro=null){
		$mao_obra_gasto=$this->mao_obra_gasto(($this->baseline_id ? $this->baseline_id : $baseline_id), $indicador_id, $financeiro);
		$gasto_registro=$this->gasto_registro(($this->baseline_id ? $this->baseline_id : $baseline_id), $indicador_id);
		$planilha_gasto=$this->gasto_efetuado(($this->baseline_id ? $this->baseline_id : $baseline_id), $indicador_id, $financeiro);
		$recurso_gasto=$this->recurso_gasto(($this->baseline_id ? $this->baseline_id : $baseline_id), $indicador_id, $financeiro);
		return $mao_obra_gasto+$gasto_registro+$planilha_gasto+$recurso_gasto;
		}

	public function custo( $baseline_id=0, $indicador_id=false, $financeiro=null){
		$mao_obra_custo=$this->mao_obra_previsto('', '', false, ($this->baseline_id ? $this->baseline_id : $baseline_id), $indicador_id);
		$planilha_custo=$this->custo_previsto('','', false, ($this->baseline_id ? $this->baseline_id : $baseline_id), $indicador_id);
		$recurso_custo=$this->recurso_previsto('', '', false, ($this->baseline_id ? $this->baseline_id : $baseline_id), $indicador_id);
		return $mao_obra_custo+$planilha_custo+$recurso_custo;
		}


	public function mao_obra_gasto( $baseline_id=0, $indicador_id=false, $financeiro=null){
		return mao_obra_gasto($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), null,$indicador_id, $financeiro, $this->portfolio_externo);
		}

	public function mao_obra_valor_agregado( $baseline_id=0, $indicador_id=false){
		return mao_obra_valor_agregado($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), false, false,$this->portfolio_externo);
		}

	public function mao_obra_EPT( $baseline_id=0, $indicador_id=false){
		return mao_obra_EPT($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, $indicador_id, $this->portfolio_externo);
		}

	public function mao_obra_gasto_periodo( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0, $financeiro=null){
		global $Aplic, $config;

		$sql = new BDConsulta;

		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}

		$total1=0;
		$total2=0;
		$total3=0;
		$total4=0;

		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'folha_ponto','folha_ponto');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas','folha_ponto_tarefa=tarefa_id');

		if ($financeiro=='empenhado') $sql->adCampo('SUM(folha_ponto_empenhado*folha_ponto_valor_hora) AS total');
		elseif ($financeiro=='liquidado') $sql->adCampo('SUM(folha_ponto_liquidado*folha_ponto_valor_hora) AS total');
		elseif ($financeiro=='pago') $sql->adCampo('SUM(folha_ponto_pago*folha_ponto_valor_hora) AS total');
		else $sql->adCampo('SUM(folha_ponto_duracao*folha_ponto_valor_hora) AS total');

		$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('folha_ponto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		if ($config['aprova_mo']) $sql->adOnde('folha_ponto_aprovado = 1');
		$total1=$sql->Resultado();
		$sql->limpar();

		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'folha_ponto_gasto','folha_ponto_gasto');
		$sql->esqUnir('folha_ponto','folha_ponto','folha_ponto_gasto_folha=folha_ponto_id');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas','folha_ponto_tarefa=tarefa_id');

		if ($financeiro=='empenhado') $sql->adCampo('SUM(folha_ponto_gasto_empenhado*folha_ponto_gasto_gasto) AS total');
		elseif ($financeiro=='liquidado') $sql->adCampo('SUM(folha_ponto_gasto_liquidado*folha_ponto_gasto_gasto) AS total');
		elseif ($financeiro=='pago') $sql->adCampo('SUM(folha_ponto_gasto_pago*folha_ponto_gasto_gasto) AS total');
		else $sql->adCampo('SUM(folha_ponto_gasto_quantidade*folha_ponto_gasto_gasto) AS total');

		$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('folha_ponto_gasto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		if ($config['aprova_mo']) $sql->adOnde('folha_ponto_aprovado = 1');
		$total2=$sql->Resultado();
		$sql->limpar();

		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'folha_ponto','folha_ponto');
		$sql->esqUnir('eventos','eventos','evento_id=folha_ponto_evento');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas','evento_tarefa=tarefa_id');

		if ($financeiro=='empenhado') $sql->adCampo('SUM(folha_ponto_empenhado*folha_ponto_valor_hora) AS total');
		elseif ($financeiro=='liquidado') $sql->adCampo('SUM(folha_ponto_liquidado*folha_ponto_valor_hora) AS total');
		elseif ($financeiro=='pago') $sql->adCampo('SUM(folha_ponto_pago*folha_ponto_valor_hora) AS total');
		else $sql->adCampo('SUM(folha_ponto_duracao*folha_ponto_valor_hora) AS total');

		$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('folha_ponto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		if ($config['aprova_mo']) $sql->adOnde('folha_ponto_aprovado = 1');
		$total3=$sql->Resultado();
		$sql->limpar();

		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'folha_ponto_gasto','folha_ponto_gasto');
		$sql->esqUnir('folha_ponto','folha_ponto','folha_ponto_gasto_folha=folha_ponto_id');
		$sql->esqUnir('eventos','eventos','evento_id=folha_ponto_evento');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas','evento_tarefa=tarefa_id');

		if ($financeiro=='empenhado') $sql->adCampo('SUM(folha_ponto_gasto_empenhado*folha_ponto_gasto_gasto) AS total');
		elseif ($financeiro=='liquidado') $sql->adCampo('SUM(folha_ponto_gasto_liquidado*folha_ponto_gasto_gasto) AS total');
		elseif ($financeiro=='pago') $sql->adCampo('SUM(folha_ponto_gasto_pago*folha_ponto_gasto_gasto) AS total');
		else $sql->adCampo('SUM(folha_ponto_gasto_quantidade*folha_ponto_gasto_gasto) AS total');

		$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('folha_ponto_gasto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		if ($config['aprova_mo']) $sql->adOnde('folha_ponto_aprovado = 1');
		$total4=$sql->Resultado();
		$sql->limpar();

		$total=$total1+$total2+$total3+$total4;

		if ($ate_data_atual) {
			//No meio da execução
			$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
			$sql->adCampo('tarefa_id, tarefa_inicio, tarefa_cia, tarefa_duracao, tarefa_projeto');
			$sql->adOnde('tarefa_projetoex_id IS NULL');
			if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
			else $sql->adOnde('tarefa_id IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$this->projeto_id.')');
			$sql->adOnde('tarefa_inicio<= \''.$data_final.'\'');
			$sql->adOnde('tarefa_fim > \''.$data_final.'\'');
			if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$em_execucao = $sql->Lista();
			$sql->limpar();
			foreach($em_execucao as $linha) {

				if ($data_inicial){
					$inicio=(strtotime($data_inicial) > strtotime($linha['tarefa_inicio']) ? $data_inicial : $linha['tarefa_inicio']);
					}
				else $inicio=$linha['tarefa_inicio'];

				$horas=horas_periodo($inicio, $data_final, $linha['tarefa_cia'], null, $linha['tarefa_projeto']);
				$porcentagem=($linha['tarefa_duracao'] > 0 ? $horas/$linha['tarefa_duracao'] : 1);

				$total1=0;
				$total2=0;
				$total3=0;
				$total4=0;

				$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'folha_ponto','folha_ponto');

				if ($financeiro=='empenhado') $sql->adCampo('SUM(folha_ponto_empenhado*folha_ponto_valor_hora) AS total');
				elseif ($financeiro=='liquidado') $sql->adCampo('SUM(folha_ponto_liquidado*folha_ponto_valor_hora) AS total');
				elseif ($financeiro=='pago') $sql->adCampo('SUM(folha_ponto_pago*folha_ponto_valor_hora) AS total');
				else $sql->adCampo('SUM(folha_ponto_duracao*folha_ponto_valor_hora) AS total');

				$sql->adOnde('folha_ponto_tarefa='.$linha['tarefa_id']);
				if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('folha_ponto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
				if ($config['aprova_mo']) $sql->adOnde('folha_ponto_aprovado = 1');
				$total1=$sql->Resultado();
				$sql->limpar();

				$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'folha_ponto_gasto','folha_ponto_gasto');
				$sql->esqUnir('folha_ponto','folha_ponto','folha_ponto_gasto_folha=folha_ponto_id');

				if ($financeiro=='empenhado') $sql->adCampo('SUM(folha_ponto_gasto_empenhado*folha_ponto_gasto_gasto) AS total');
				elseif ($financeiro=='liquidado') $sql->adCampo('SUM(folha_ponto_gasto_liquidado*folha_ponto_gasto_gasto) AS total');
				elseif ($financeiro=='pago') $sql->adCampo('SUM(folha_ponto_gasto_pago*folha_ponto_gasto_gasto) AS total');
				else $sql->adCampo('SUM(folha_ponto_gasto_quantidade*folha_ponto_gasto_gasto) AS total');

				$sql->adOnde('folha_ponto_tarefa='.$linha['tarefa_id']);
				if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('folha_ponto_gasto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
				if ($config['aprova_mo']) $sql->adOnde('folha_ponto_aprovado = 1');
				$total2=$sql->Resultado();
				$sql->limpar();

				$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'folha_ponto','folha_ponto');
				$sql->esqUnir('eventos','eventos','evento_id=folha_ponto_evento');

				if ($financeiro=='empenhado') $sql->adCampo('SUM(folha_ponto_empenhado*folha_ponto_valor_hora) AS total');
				elseif ($financeiro=='liquidado') $sql->adCampo('SUM(folha_ponto_liquidado*folha_ponto_valor_hora) AS total');
				elseif ($financeiro=='pago') $sql->adCampo('SUM(folha_ponto_pago*folha_ponto_valor_hora) AS total');
				else $sql->adCampo('SUM(folha_ponto_duracao*folha_ponto_valor_hora) AS total');

				$sql->adOnde('evento_tarefa='.$linha['tarefa_id']);
				if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('folha_ponto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
				if ($config['aprova_mo']) $sql->adOnde('folha_ponto_aprovado = 1');
				$total3=$sql->Resultado();
				$sql->limpar();

				$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'folha_ponto_gasto','folha_ponto_gasto');
				$sql->esqUnir('folha_ponto','folha_ponto','folha_ponto_gasto_folha=folha_ponto_id');
				$sql->esqUnir('eventos','eventos','evento_id=folha_ponto_evento');

				if ($financeiro=='empenhado') $sql->adCampo('SUM(folha_ponto_gasto_empenhado*folha_ponto_gasto_gasto) AS total');
				elseif ($financeiro=='liquidado') $sql->adCampo('SUM(folha_ponto_gasto_liquidado*folha_ponto_gasto_gasto) AS total');
				elseif ($financeiro=='pago') $sql->adCampo('SUM(folha_ponto_gasto_pago*folha_ponto_gasto_gasto) AS total');
				else $sql->adCampo('SUM(folha_ponto_gasto_quantidade*folha_ponto_gasto_gasto) AS total');

				$sql->adOnde('evento_tarefa='.$linha['tarefa_id']);
				if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('folha_ponto_gasto.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
				if ($config['aprova_mo']) $sql->adOnde('folha_ponto_aprovado = 1');
				$total4=$sql->Resultado();
				$sql->limpar();


				$total+=($porcentagem*($total1+$total2+$total3+$total4));
				}
			}

		return $total;
		}

	public function notificarResponsavel($nao_eh_novo, $tipo='gerente', $email_texto='') {
		global $Aplic, $config, $localidade_tipo_caract;

		if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['projeto']).' Excluid'.$config['genero_projeto'].': '.$this->projeto_nome;
		elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['projeto']).' Atualizad'.$config['genero_projeto'].': '.$this->projeto_nome;
		else $titulo=ucfirst($config['projeto']).' Criad'.$config['genero_projeto'].': '.$this->projeto_nome;

		$sql = new BDConsulta;
		if ($tipo=='gerente'){
			$sql->adTabela('projetos', 'p');
			$sql->esqUnir('usuarios', 'o', 'o.usuario_id = p.projeto_responsavel');
			$sql->esqUnir('contatos', 'oc', 'oc.contato_id = o.usuario_contato');
			$sql->adCampo('p.projeto_id, oc.contato_email, o.usuario_id');
			$sql->adOnde('p.projeto_id = '.(int)$this->projeto_id);
			$sql->adOnde('o.usuario_id != '.(int)$Aplic->usuario_id);
			$usuario = $sql->Linha();
			$sql->limpar();
			}
		elseif ($tipo=='supervisor'){
			$sql->adTabela('projetos', 'p');
			$sql->esqUnir('usuarios', 'o', 'o.usuario_id = p.projeto_supervisor');
			$sql->esqUnir('contatos', 'oc', 'oc.contato_id = o.usuario_contato');
			$sql->adCampo('p.projeto_id, oc.contato_email, o.usuario_id');
			$sql->adOnde('p.projeto_id = '.(int)$this->projeto_id);
			$sql->adOnde('o.usuario_id != '.(int)$Aplic->usuario_id);
			$usuario = $sql->Linha();
			$sql->limpar();
			}
		elseif ($tipo=='autoridade'){
			$sql->adTabela('projetos', 'p');
			$sql->esqUnir('usuarios', 'o', 'o.usuario_id = p.projeto_autoridade');
			$sql->esqUnir('contatos', 'oc', 'oc.contato_id = o.usuario_contato');
			$sql->adCampo('p.projeto_id, oc.contato_email, o.usuario_id');
			$sql->adOnde('p.projeto_id = '.(int)$this->projeto_id);
			$sql->adOnde('o.usuario_id != '.(int)$Aplic->usuario_id);
			$usuario = $sql->Linha();
			$sql->limpar();
			}
		elseif ($tipo=='cliente'){
			$sql->adTabela('projetos', 'p');
			$sql->esqUnir('usuarios', 'o', 'o.usuario_id = p.projeto_cliente');
			$sql->esqUnir('contatos', 'oc', 'oc.contato_id = o.usuario_contato');
			$sql->adCampo('p.projeto_id, oc.contato_email, o.usuario_id');
			$sql->adOnde('p.projeto_id = '.(int)$this->projeto_id);
			$sql->adOnde('o.usuario_id != '.(int)$Aplic->usuario_id);
			$usuario = $sql->Linha();
			$sql->limpar();
			}

		$corpo='';
		$corpo_email='';
		$corpo_interno='';
		$corpo_externo='';
		if ($usuario['usuario_id']) {
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_projeto']).' '.ucfirst($config['projeto']).' '.$this->projeto_nome.' foi atualizad'.$config['genero_projeto'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_projeto']).' '.ucfirst($config['projeto']).' '.$this->projeto_nome.' foi criad'.$config['genero_projeto'].'.</b><br>';
			$corpo .= '<br><br>(Você está recebendo este e-mail por ser '.$config[$tipo].' d'.$config['genero_projeto'].' '.$config['projeto'].')<br><br>';
			
			
			$corpo .='<table border="1"><tr><td>'.link_projeto($this->projeto_id,'','','',true).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Responsável pela exclusão:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_projeto'].' '.$config['projeto'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_projeto'].' '.$config['projeto'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra.'<br><br>   ';
			
			$corpo .='<br><br><a href="'.$config['dominio_site'].'/index.php?m=projetos&a=ver&projeto_id='.$this->projeto_id.'">Clique para acessar o Projeto</a><br>';	
			

			if ($email_texto) $corpo .= '<br><br>'.$email_texto;

			$corpo_interno=$corpo;
			$corpo_externo=$corpo;
			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=ver&projeto_id='.$this->projeto_id.'\');"><b>Clique para acessar '.$config['genero_projeto'].' '.$config['projeto'].'</b></a>';
			}

		if ($usuario['usuario_id']) msg_email_interno('', $titulo, $corpo_interno, '', $usuario['usuario_id']);

		if ($config['email_ativo']) {
			if ($Aplic->profissional){
				//texto diferente para gerente e supervisor
				require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
				$email = new Mail;
                $email->De($config['email'], $Aplic->usuario_nome);

                if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                    $email->ResponderPara($Aplic->usuario_email);
                    }
                else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                    $email->ResponderPara($Aplic->usuario_email2);
                    }

				if ($email->EmailValido($usuario['contato_email'])) {
					$email->Assunto($titulo, $localidade_tipo_caract);
					$endereco=link_email_externo($usuario['usuario_id'], 'm=projetos&a=ver&projeto_id='.$this->projeto_id);
					$link='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_projeto'].' '.$config['projeto'].'</b></a>';
					$email->Corpo($corpo_externo.$link, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
					$email->Para($usuario['contato_email'], true);
					$email->Enviar();
					}
				}
			else{
				$email = new Mail;
                $email->De($config['email'], $Aplic->usuario_nome);

                if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                    $email->ResponderPara($Aplic->usuario_email);
                    }
                else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                    $email->ResponderPara($Aplic->usuario_email2);
                    }

				$email->Assunto($titulo, $localidade_tipo_caract);
				//texto igual para gerente e supervisor
				$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
				if ($email->EmailValido($usuario['contato_email'])) {
					$email->Para($usuario['contato_email'], true);
					$email->Enviar();
					}
				}
			}
		}





	public function notificar( $nao_eh_novo, $tipo='designados', $email_texto='', $contatos=''){
		global $Aplic, $config, $localidade_tipo_caract;
		$email = new Mail;
        $email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$sql = new BDConsulta;
		$usuarios = array();
		if ($tipo=='designados'){
			$sql->adTabela('projeto_integrantes','pi');
			$sql->esqUnir('contatos', 'c', 'c.contato_id = pi.contato_id');
			$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = c.contato_id');
			$sql->adCampo('pi.contato_id, usuario_id, contato_email');
			$sql->adOnde('usuarios.usuario_id != '.(int)$Aplic->usuario_id);
			$sql->adOnde('pi.projeto_id = '.(int)$this->projeto_id);
			$usuarios = $sql->Lista();
			$sql->limpar();
			}
		elseif ($tipo=='contatos'){
			$sql->adTabela('projeto_contatos', 'pc');
			$sql->esqUnir('contatos', 'c', 'c.contato_id = pc.contato_id');
			$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = c.contato_id');
			$sql->adCampo('c.contato_id, usuario_id, contato_email');
			$sql->adOnde('pc.projeto_id = '.(int)$this->projeto_id);
			$usuarios = $sql->Lista();
			$sql->limpar();
			}
		elseif ($Aplic->profissional && $tipo=='stakeholders'){
			$sql->adTabela('projeto_stakeholder');
			$sql->esqUnir('contatos', 'c', 'c.contato_id = projeto_stakeholder_contato');
			$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = c.contato_id');
			$sql->adCampo('c.contato_id, usuario_id, contato_email');
			$sql->adOnde('projeto_stakeholder_projeto = '.(int)$this->projeto_id);
			$usuarios = $sql->Lista();
			$sql->limpar();
			}
		elseif ($tipo=='outros'){
			$sql->adTabela('contatos', 'c');
			$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = c.contato_id');
			$sql->adCampo('c.contato_id, usuario_id, contato_email');
			$sql->adOnde('c.contato_id IN ('.$contatos.')');
			$usuarios = $sql->Lista();
			$sql->limpar();
			}
		elseif ($tipo=='extras' && $contatos){
			$extras=explode(',',$contatos);
			foreach($extras as $chave => $valor) $usuarios[]=array('usuario_id' => 0, 'nome_usuario' =>'', 'contato_email'=> $valor);
			}

		$corpo_email='';

		if (count($usuarios)) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['projeto']).' Excluid'.$config['genero_projeto'].': '.$this->projeto_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['projeto']).' Atualizad'.$config['genero_projeto'].': '.$this->projeto_nome;
			else $titulo=ucfirst($config['projeto']).' Criad'.$config['genero_projeto'].': '.$this->projeto_nome;

			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_projeto']).' '.ucfirst($config['projeto']).' '.$this->projeto_nome.' foi atualizad'.$config['genero_projeto'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_projeto']).' '.ucfirst($config['projeto']).' '.$this->projeto_nome.' foi criad'.$config['genero_projeto'].'.</b><br>';

			if ($tipo=='designados') $corpo .= '<br><br>(Você está recebendo este e-mail por ser um dos designados d'.$config['genero_projeto'].' '.$config['projeto'].')<br><br>';
			elseif ($tipo=='contatos') $corpo .= '<br><br>(Você está recebendo este e-mail por ser um dos contatos d'.$config['genero_projeto'].' '.$config['projeto'].')<br><br>';
			elseif ($tipo=='stakeholders') $corpo .= '<br><br>(Você está recebendo este e-mail por ser um dos stakeholders d'.$config['genero_projeto'].' '.$config['projeto'].')<br><br>';

			if ($email_texto) $corpo .= '<br><br>'.$email_texto;

			$corpo .='<table border="1"><tr><td>'.link_projeto($this->projeto_id,'','','',true).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Responsável pela exclusão:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_projeto'].' '.$config['projeto'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_projeto'].' '.$config['projeto'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


			$corpo_externo=$corpo;
			$corpo_interno=$corpo;
			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=ver&projeto_id='.$this->projeto_id.'\');"><b>Clique para acessar '.$config['genero_projeto'].' '.$config['projeto'].'</b></a>';

			$validos=0;
			$email->Corpo($corpo_email, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
			foreach ($usuarios as $linha) {
				$corpo_interno=$corpo;
				if ($linha['usuario_id']) msg_email_interno ('', $titulo, $corpo_interno,'',$linha['usuario_id']);

				if ($email->EmailValido($linha['contato_email']) && $config['email_ativo']) {
					if ($Aplic->profissional){
						//texto diferente para gerente e supervisor
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$email = new Mail;
            $email->De($config['email'], $Aplic->usuario_nome);

            if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                $email->ResponderPara($Aplic->usuario_email);
                }
            else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                $email->ResponderPara($Aplic->usuario_email2);
                }

						if ($email->EmailValido($linha['contato_email'])) {
							$endereco=link_email_externo($linha['usuario_id'], 'm=projetos&a=ver&projeto_id='.$this->projeto_id);
							if ($endereco) $corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_projeto'].' '.$config['projeto'].'</b></a>';
							$email->Assunto($titulo, $localidade_tipo_caract);
							$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
							$email->Para($linha['contato_email'], true);
							$email->Enviar();
							}
						}
					else {
						$validos++;
						$email->Para($linha['contato_email'], true);
						}
					}
				}
			if ($validos) $email->Enviar();
			}
		}

	public function quantidade_adquirida_periodo( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0){
		global $Aplic;
		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}

		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
		$sql->adCampo('SUM(tarefa_adquirido) AS total');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id))	$sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$total=$sql->Resultado();
		$sql->limpar();

		if ($ate_data_atual) {
			//No meio da execução
			$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
			$sql->adCampo('tarefa_id, tarefa_inicio, tarefa_cia, tarefa_duracao, tarefa_projeto, tarefa_adquirido');
			$sql->adOnde('tarefa_projetoex_id IS NULL');
			if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
			else $sql->adOnde('tarefa_id IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$this->projeto_id.')');
			$sql->adOnde('tarefa_inicio<= \''.$data_final.'\'');
			$sql->adOnde('tarefa_fim > \''.$data_final.'\'');
			if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$em_execucao = $sql->Lista();
			$sql->limpar();
			foreach($em_execucao as $linha) {

				if ($data_inicial){
					$inicio=(strtotime($data_inicial) > strtotime($linha['tarefa_inicio']) ? $data_inicial : $linha['tarefa_inicio']);
					}
				else $inicio=$linha['tarefa_inicio'];

				$horas=horas_periodo($inicio, $data_final, $linha['tarefa_cia'], null, $linha['tarefa_projeto']);
				$porcentagem=($linha['tarefa_duracao'] > 0 ? $horas/$linha['tarefa_duracao'] : 1);

				$total+=($porcentagem*$linha['tarefa_adquirido']);
				}
			}
		return $total;
		}

	public function quantidade_prevista_periodo( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0){
		global $Aplic;
		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}

		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
		$sql->adCampo('SUM(tarefa_previsto) AS total');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id))	$sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$total=$sql->Resultado();
		$sql->limpar();

		if ($ate_data_atual) {
			//No meio da execução
			$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
			$sql->adCampo('tarefa_id, tarefa_inicio, tarefa_cia, tarefa_duracao, tarefa_projeto, tarefa_previsto');
			$sql->adOnde('tarefa_projetoex_id IS NULL');
			if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
			else $sql->adOnde('tarefa_id IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$this->projeto_id.')');
			$sql->adOnde('tarefa_inicio<= \''.$data_final.'\'');
			$sql->adOnde('tarefa_fim > \''.$data_final.'\'');
			if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$em_execucao = $sql->Lista();
			$sql->limpar();
			foreach($em_execucao as $linha) {

				if ($data_inicial){
					$inicio=(strtotime($data_inicial) > strtotime($linha['tarefa_inicio']) ? $data_inicial : $linha['tarefa_inicio']);
					}
				else $inicio=$linha['tarefa_inicio'];

				$horas=horas_periodo($inicio, $data_final, $linha['tarefa_cia'], null, $linha['tarefa_projeto']);
				$porcentagem=($linha['tarefa_duracao'] > 0 ? $horas/$linha['tarefa_duracao'] : 1);

				$total+=($porcentagem*$linha['tarefa_previsto']);
				}
			}
		return $total;
		}

	public function quantidade_realizada_periodo( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0){
		global $Aplic;
		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}

		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
		$sql->adCampo('SUM(tarefa_realizado) AS total');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id))	$sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$total=$sql->Resultado();
		$sql->limpar();

		if ($ate_data_atual) {
			//No meio da execução
			$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
			$sql->adCampo('tarefa_id, tarefa_inicio, tarefa_cia, tarefa_duracao, tarefa_projeto, tarefa_realizado');
			$sql->adOnde('tarefa_projetoex_id IS NULL');
			if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
			else $sql->adOnde('tarefa_id IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$this->projeto_id.')');
			$sql->adOnde('tarefa_inicio<= \''.$data_final.'\'');
			$sql->adOnde('tarefa_fim > \''.$data_final.'\'');
			if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$em_execucao = $sql->Lista();
			$sql->limpar();
			foreach($em_execucao as $linha) {

				if ($data_inicial){
					$inicio=(strtotime($data_inicial) > strtotime($linha['tarefa_inicio']) ? $data_inicial : $linha['tarefa_inicio']);
					}
				else $inicio=$linha['tarefa_inicio'];

				$horas=horas_periodo($inicio, $data_final, $linha['tarefa_cia'], null, $linha['tarefa_projeto']);
				$porcentagem=($linha['tarefa_duracao'] > 0 ? $horas/$linha['tarefa_duracao'] : 1);

				$total+=($porcentagem*$linha['tarefa_realizado']);
				}
			}
		return $total;
		}

	public function empregos_execucao_periodo( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0){
		global $Aplic;
		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}

		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
		$sql->adCampo('SUM(tarefa_emprego_obra) AS total');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id))	$sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$total=$sql->Resultado();
		$sql->limpar();

		if ($ate_data_atual) {
			//No meio da execução
			$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
			$sql->adCampo('tarefa_id, tarefa_inicio, tarefa_cia, tarefa_duracao, tarefa_projeto, tarefa_emprego_obra');
			$sql->adOnde('tarefa_projetoex_id IS NULL');
			if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
			else $sql->adOnde('tarefa_id IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$this->projeto_id.')');
			$sql->adOnde('tarefa_inicio<= \''.$data_final.'\'');
			$sql->adOnde('tarefa_fim > \''.$data_final.'\'');
			if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$em_execucao = $sql->Lista();
			$sql->limpar();
			foreach($em_execucao as $linha) {

				if ($data_inicial){
					$inicio=(strtotime($data_inicial) > strtotime($linha['tarefa_inicio']) ? $data_inicial : $linha['tarefa_inicio']);
					}
				else $inicio=$linha['tarefa_inicio'];

				$horas=horas_periodo($inicio, $data_final, $linha['tarefa_cia'], null, $linha['tarefa_projeto']);
				$porcentagem=($linha['tarefa_duracao'] > 0 ? $horas/$linha['tarefa_duracao'] : 1);

				$total+=($porcentagem*$linha['tarefa_emprego_obra']);
				}
			}
		return $total;
		}

	public function empregos_direto_periodo( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0){
		global $Aplic;
		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}

		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
		$sql->adCampo('SUM(tarefa_emprego_direto) AS total');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id))	$sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$total=$sql->Resultado();
		$sql->limpar();

		if ($ate_data_atual) {
			//No meio da execução
			$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
			$sql->adCampo('tarefa_id, tarefa_inicio, tarefa_cia, tarefa_duracao, tarefa_projeto, tarefa_emprego_direto');
			$sql->adOnde('tarefa_projetoex_id IS NULL');
			if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
			else $sql->adOnde('tarefa_id IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$this->projeto_id.')');
			$sql->adOnde('tarefa_inicio<= \''.$data_final.'\'');
			$sql->adOnde('tarefa_fim > \''.$data_final.'\'');
			if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$em_execucao = $sql->Lista();
			$sql->limpar();
			foreach($em_execucao as $linha) {

				if ($data_inicial){
					$inicio=(strtotime($data_inicial) > strtotime($linha['tarefa_inicio']) ? $data_inicial : $linha['tarefa_inicio']);
					}
				else $inicio=$linha['tarefa_inicio'];

				$horas=horas_periodo($inicio, $data_final, $linha['tarefa_cia'], null, $linha['tarefa_projeto']);
				$porcentagem=($linha['tarefa_duracao'] > 0 ? $horas/$linha['tarefa_duracao'] : 1);

				$total+=($porcentagem*$linha['tarefa_emprego_direto']);
				}
			}
		return $total;
		}

	public function empregos_indireto_periodo( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0){
		global $Aplic;
		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}

		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
		$sql->adCampo('SUM(tarefa_emprego_indireto) AS total');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id))	$sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$total=$sql->Resultado();
		$sql->limpar();

		if ($ate_data_atual) {
			//No meio da execução
			$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
			$sql->adCampo('tarefa_id, tarefa_inicio, tarefa_cia, tarefa_duracao, tarefa_projeto, tarefa_emprego_indireto');
			$sql->adOnde('tarefa_projetoex_id IS NULL');
			if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
			else $sql->adOnde('tarefa_id IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$this->projeto_id.')');
			$sql->adOnde('tarefa_inicio<= \''.$data_final.'\'');
			$sql->adOnde('tarefa_fim > \''.$data_final.'\'');
			if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$em_execucao = $sql->Lista();
			$sql->limpar();
			foreach($em_execucao as $linha) {

				if ($data_inicial){
					$inicio=(strtotime($data_inicial) > strtotime($linha['tarefa_inicio']) ? $data_inicial : $linha['tarefa_inicio']);
					}
				else $inicio=$linha['tarefa_inicio'];

				$horas=horas_periodo($inicio, $data_final, $linha['tarefa_cia'], null, $linha['tarefa_projeto']);
				$porcentagem=($linha['tarefa_duracao'] > 0 ? $horas/$linha['tarefa_duracao'] : 1);

				$total+=($porcentagem*$linha['tarefa_emprego_indireto']);
				}
			}
		return $total;
		}
	
	public function getPriorizacaoFisico($baseline_id=0, $pratica_indicador_id=null){
		return priorizacaoFisico($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), $pratica_indicador_id, $this->portfolio_externo);
		}

	public function getPriorizacao($baseline_id=0, $pratica_indicador_id=null){
		return priorizacao($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), $pratica_indicador_id, $this->portfolio_externo);
		}

	public function getQuantidadeAdquirida($baseline_id=0, $pratica_indicador_id=null){
		return quantidadeAdquirida($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, $pratica_indicador_id, $this->portfolio_externo);
		}

	public function getQuantidadePrevista($baseline_id=0, $pratica_indicador_id=null){
		return quantidadePrevista($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, $pratica_indicador_id, $this->portfolio_externo);
		}

	public function getQuantidadeRealizada($baseline_id=0, $pratica_indicador_id=null){
		return quantidadeRealizada($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, $pratica_indicador_id, $this->portfolio_externo);
		}

	public function getRealizadaPrevista($baseline_id=0, $pratica_indicador_id=null){
		return realizadaPrevista($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, $pratica_indicador_id, $this->portfolio_externo);
		}

	public function getAdquiridaPrevista($baseline_id=0, $pratica_indicador_id=null){
		return adquiridaPrevista($this->projeto_id, ($this->baseline_id ? $this->baseline_id : $baseline_id), null, $pratica_indicador_id, $this->portfolio_externo);
		}



	public function dias_trabalho_periodo( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0){
		global $Aplic, $config;
		$lista='';
		if ($this->projeto_portfolio && $Aplic->profissional){
			$vetor=array();
			portfolio_tarefas((int)$this->projeto_id, $vetor, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->portfolio_externo);
			$lista=implode(',',$vetor);
			if (!$lista) return 0;
			}

		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
		$sql->adCampo('SUM(tarefa_duracao) AS total');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
		else $sql->adOnde('tarefa_id IN (select tarefa_id from '.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas AS tarefas WHERE tarefa_projeto='.$this->projeto_id.(($this->baseline_id ? $this->baseline_id : $baseline_id) ? ' AND tarefas.baseline_id='.($this->baseline_id ? $this->baseline_id : $baseline_id) : '').')');
		if ($ate_data_atual) {
			$sql->adOnde('tarefa_fim<= \''.$data_final.'\'');
			if ($data_inicial) $sql->adOnde('tarefa_inicio>= \''.$data_inicial.'\'');
			}
		if (($this->baseline_id ? $this->baseline_id : $baseline_id))	$sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$total=(float)$sql->Resultado();
		$total /= $config['horas_trab_diario'];
		$sql->limpar();

		if ($ate_data_atual) {
			//No meio da execução
			$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas');
			$sql->adCampo('tarefa_id, tarefa_inicio, tarefa_cia, tarefa_duracao, tarefa_projeto');
			$sql->adOnde('tarefa_projetoex_id IS NULL');
			if ($lista) $sql->adOnde('tarefa_id IN ('.$lista.')');
			else $sql->adOnde('tarefa_id IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$this->projeto_id.')');
			$sql->adOnde('tarefa_inicio<= \''.$data_final.'\'');
			$sql->adOnde('tarefa_fim > \''.$data_final.'\'');
			if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$em_execucao = $sql->Lista();
			$sql->limpar();
			foreach($em_execucao as $linha) {

				if ($data_inicial){
					$inicio=(strtotime($data_inicial) > strtotime($linha['tarefa_inicio']) ? $data_inicial : $linha['tarefa_inicio']);
					}
				else $inicio=$linha['tarefa_inicio'];

				$horas=horas_periodo($inicio, $data_final, $linha['tarefa_cia'], null, $linha['tarefa_projeto']);
				$total += $horas/$config['horas_trab_diario'];
				}
			}
		return $total;
		}
	}


function projeto_por_area_pro($filtro){
	$area = json_decode(get_magic_quotes_gpc() ? stripslashes($filtro) : $filtro);
	$bCirc = null;
	if($area->type == 'circle'){
		$bCirc = new Circle2(new Vector2($area->center->x, $area->center->y), $area->radius);
		}
	else if($area->type == 'rectangle'){
		$bCirc = new Circle2();
		$poly = new Polygon2();
		$poly->addPoint($area->sw->x, $area->sw->y);
		$poly->addPoint($area->sw->x, $area->ne->y);
		$poly->addPoint($area->ne->x, $area->ne->y);
		$poly->addPoint($area->ne->x, $area->sw->y);
		$bCirc->fromPolygon($poly);
		}
	else if($area->type == 'polygon'){
		$bCirc = new Circle2();
		$poly = new Polygon2();
		foreach($area->points as $point){
			$poly->addPoint($point->x, $point->y);
			}
		$bCirc->fromPolygon($poly);
		}

	$sql = new BDConsulta();
	$sql->adTabela('projeto_area');
	$sql->adCampo('projeto_area_id as area_id, projeto_area_projeto as projeto_id');
	$sql->exec();
	$areas = $sql->Lista();
	$sql->limpar();

	$projetos = array();
	$bc = new Circle2();
	foreach($areas as $area){
		if(!isset($projetos[$area['projeto_id']])){
			$sql->adTabela('projeto_ponto');
			$sql->adCampo('projeto_ponto_latitude as lt, projeto_ponto_longitude as lg');
			$sql->adOnde('projeto_area_id = '.(int) $area['area_id']);
			$sql->exec();
			$pontos = $sql->Lista();
			$sql->limpar();
			if($pontos){
				$poly = new Polygon2();
				foreach($pontos as $ponto){
					$poly->addPoint((float)$ponto['lt'], (float)$ponto['lg']);
					}
				$bc->fromPolygon($poly);

				if($bCirc->testCircle($bc)) $projetos[$area['projeto_id']] = (int)$area['projeto_id'];
				}
			}
		}

	return $projetos;
	}

function projetos_quantidade(FiltrosProjetoBuilder $filtros) {
	global $Aplic, $config;
	$envolvimento = $filtros->getEnvolvimento();
	$usuario_id = $filtros->getUsuarioId();
	$supervisor = $filtros->getSupervisor();
	$autoridade = $filtros->getAutoridade();
	$cliente = $filtros->getCliente();
	$cia_id = $filtros->getCiaId();
	$lista_cias = $filtros->getListaCias();
	$usar_periodo =  $filtros->getUsar_periodo();
	$reg_data_inicio =  $filtros->getReg_data_inicio();
	$reg_data_fim =  $filtros->getReg_data_fim();
	$ordenarPor = $filtros->getOrdenarPor();
	$ordemDir = $filtros->getOrdemDir();
	$projeto_tipo = $filtros->getProjetoTipo();
	$projeto_setor = $filtros->getProjetoSetor();
	$projeto_segmento = $filtros->getProjetoSegmento();
	$projeto_intervencao = $filtros->getProjetoIntervencao();
	$projeto_tipo_intervencao = $filtros->getProjetoTipoIntervencao();
	$estado_sigla = $filtros->getEstadoSigla();
	$municipio_id = $filtros->getMunicipioId();
	$pesquisar_texto = $filtros->getPesquisarTexto();
	$mostrarProjRespPertenceDept = $filtros->getMostrarProjRespPertenceDept();
	$recebido = $filtros->getRecebido();
	$dept_id = $filtros->getDeptId();
	$favorito_id = $filtros->getFavoritoId();
	$projetostatus = $filtros->getProjetoStatus();
	$ponto_inicio = $filtros->getPontoInicio();
	$projeto_expandido = $filtros->getProjetoExpandido();
	$nao_apenas_superiores = $filtros->getNaoApenasSuperiores();
	$exibir = $filtros->getExibir();
	$portfolio = $filtros->getPortfolio();
	$template = $filtros->getTemplate();
	$portfolio_pai = $filtros->getPortfolioPai();
	$limite = $filtros->getLimite();
	$data_inicio = $filtros->getDataInicio();
	$data_termino = $filtros->getDataTermino();
	$filtro_area = $filtros->getFiltroArea();
	$filtro_criterio = $filtros->getFiltroCriterio();
	$filtro_opcao = $filtros->getFiltroOpcao();
	$filtro_prioridade = $filtros->getFiltroPrioridade();
	$filtro_perspectiva = $filtros->getFiltroPerspectiva();
	$filtro_tema = $filtros->getFiltroTema();
	$filtro_objetivo = $filtros->getFiltroObjetivo();
	$filtro_fator = $filtros->getFiltroFator();
	$filtro_estrategia = $filtros->getFiltroEstrategia();
	$filtro_meta = $filtros->getFiltroMeta();
	$filtro_me = $filtros->getFiltroMe();
	$filtro_canvas = $filtros->getFiltroCanvas();
	$filtro_extra = $filtros->getFiltroExtra();

	$projeto_id = $filtros->getProjetoId();
	$tarefa_id = $filtros->getTarefaId();
	$pg_perspectiva_id = $filtros->getPgPerspectivaId();
	$tema_id = $filtros->getTemaId();
	$objetivo_id = $filtros->getPgObjetivoEstrategicoId();
	$fator_id = $filtros->getPgFatorCriticoId();
	$pg_estrategia_id = $filtros->getPgEstrategiaId();
	$pg_meta_id = $filtros->getPgMetaId();
	$pratica_id = $filtros->getPraticaId();
	$pratica_indicador_id = $filtros->getPraticaIndicadorId();
	$plano_acao_id = $filtros->getPlanoAcaoId();
	$canvas_id = $filtros->getCanvasId();
	$risco_id = $filtros->getRiscoId();
	$risco_resposta_id = $filtros->getRiscoRespostaId();
	$calendario_id = $filtros->getCalendarioId();
	$monitoramento_id = $filtros->getMonitoramentoId();
	$ata_id = $filtros->getAtaId();
	$mswot_id = $filtros->getMswotId();
	$swot_id = $filtros->getSwotId();
	$operativo_id = $filtros->getOperativoId();
	$instrumento_id = $filtros->getInstrumentoId();
	$recurso_id = $filtros->getRecursoId();
	$problema_id = $filtros->getProblemaId();
	$demanda_id = $filtros->getDemandaId();
	$programa_id = $filtros->getProgramaId();
	$licao_id = $filtros->getLicaoId();
	$evento_id = $filtros->getEventoId();
	$link_id = $filtros->getLinkId();
	$avaliacao_id = $filtros->getAvaliacaoId();
	$tgn_id = $filtros->getTgnId();
	$brainstorm_id = $filtros->getBrainstormId();
	$gut_id = $filtros->getGutId();
	$causa_efeito_id = $filtros->getCausaEfeitoId();
	$arquivo_id = $filtros->getArquivoId();
	$forum_id = $filtros->getForumId();
	$checklist_id = $filtros->getChecklistId();
	$agenda_id = $filtros->getAgendaId();
	$agrupamento_id = $filtros->getAgrupamentoId();
	$patrocinador_id = $filtros->getPatrocinadorId();
	$template_id = $filtros->getTemplateId();
	$painel_id = $filtros->getPainelId();
	$painel_odometro_id = $filtros->getPainelOdometroId();
	$painel_composicao_id = $filtros->getPainelComposicaoId();
	$tr_id = $filtros->getTrId();
	$me_id = $filtros->getMeId();
	$plano_acao_item_id = $filtros->getPlano_acao_itemId();
	$beneficio_id = $filtros->getBeneficioId();
	$painel_slideshow_id = $filtros->getPainel_slideshowId();
	$projeto_viabilidade_id = $filtros->getProjeto_viabilidadeId();
	$projeto_abertura_id = $filtros->getProjeto_aberturaId();
	$pg_id = $filtros->getPgId();
	$ssti_id = $filtros->getSstiId();		
	$laudo_id = $filtros->getLaudoId();		
	$trelo_id = $filtros->getTreloId();		
	$trelo_cartao_id = $filtros->getTrelo_cartaoId();		
	$pdcl_id = $filtros->getPdclId();		
	$pdcl_item_id = $filtros->getPdcl_itemId();

	$adicionarProjComTarefasDesignadas = $Aplic->getEstado('adicionarProjComTarefas') ? $Aplic->getEstado('adicionarProjComTarefas') : 0;
	$projetos_by_area = null;
	if($Aplic->profissional && $filtro_area){
		$projetos_by_area = implode(',',projeto_por_area_pro($filtro_area));
		if(!$projetos_by_area) return 0;
		}
		
	if (!$lista_cias) $lista_cias=$cia_id;	
		
		
		
		
		
	$sql = new BDConsulta;
  $filtro_extra_lista = false;
  if($Aplic->profissional && $filtro_extra){
    $first = true;
    foreach($filtro_extra as $filtro){
      if(isset($filtro['campo_customizado_valor_atual']) && $filtro['campo_customizado_valor_atual']){
        $sql->adTabela('campo_customizado_valor');

        if($filtro['campo_customizado_tipo_html'] == 'select' || $filtro['campo_customizado_tipo_html'] == 'selecionar'){
          $sql->adOnde('campo_customizado_valor_campo = '.$filtro['campo_customizado_id'].' AND campo_customizado_valor_caractere = \''.$filtro['campo_customizado_valor_atual'].'\'');
          }
        else if($filtro['campo_customizado_tipo_html'] == 'checkbox'){
          $valor = (int)$filtro['campo_customizado_valor_atual'];
          if($valor != 0){
            if($valor == 1) $valor = 1;
            else $valor = 0;
            $sql->adOnde('campo_customizado_valor_campo = '.$filtro['campo_customizado_id'].' AND campo_customizado_valor_inteiro = '.$valor);
            }
          }
        else{
          $sql->adOnde('campo_customizado_valor_campo = '.$filtro['campo_customizado_id'].' AND campo_customizado_valor_caractere = \''.$filtro['campo_customizado_valor_atual'].'\'');
          }

        $lista = $sql->listaVetorChave('campo_customizado_valor_objeto','campo_customizado_valor_objeto');
        $sql->limpar();

        if(!$first){
          $filtro_extra_lista = array_intersect($filtro_extra_lista, $lista);
          }
        else{
          $filtro_extra_lista = $lista;
          }

        $first = false;
        }
      }
    if(!$first){
      $filtro_extra_lista = implode(',', $filtro_extra_lista);
      }
    }
	$horas_trabalhadas = ($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
	$sql->adTabela('projetos', 'pr');
	$sql->esqUnir('projeto_cia', 'projeto_cia', 'pr.projeto_id = projeto_cia_projeto');
	$sql->adOnde('pr.projeto_plano_operativo=0 OR pr.projeto_plano_operativo IS NULL');


	if ($usar_periodo){
		$sql->esqUnir('tarefas','tarefas3', 'tarefas3.tarefa_projeto=pr.projeto_id');
		$sql->adOnde('tarefas3.tarefa_inicio <= \''.$reg_data_fim.' 23:59:59\'');
		$sql->adOnde('tarefas3.tarefa_fim >= \''.$reg_data_inicio.' 00:00:00\'');
		}
	$sql->esqUnir('projeto_gestao','projeto_gestao','projeto_gestao_projeto = pr.projeto_id');
	if ($tarefa_id) $sql->adOnde('projeto_gestao_tarefa IN ('.$tarefa_id.')');
	elseif ($projeto_id){
		$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=projeto_gestao_tarefa');
		$sql->adOnde('projeto_gestao_semelhante IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
		}
	elseif ($pg_perspectiva_id) $sql->adOnde('projeto_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('projeto_gestao_tema IN ('.$tema_id.')');
	elseif ($objetivo_id) $sql->adOnde('projeto_gestao_objetivo IN ('.$objetivo_id.')');
	elseif ($fator_id) $sql->adOnde('projeto_gestao_fator IN ('.$fator_id.')');
	elseif ($pg_estrategia_id) $sql->adOnde('projeto_gestao_estrategia IN ('.$pg_estrategia_id.')');
	elseif ($pg_meta_id) $sql->adOnde('projeto_gestao_meta IN ('.$pg_meta_id.')');
	elseif ($pratica_id) $sql->adOnde('projeto_gestao_pratica IN ('.$pratica_id.')');
	elseif ($pratica_indicador_id) $sql->adOnde('projeto_gestao_indicador IN ('.$pratica_indicador_id.')');
	elseif ($plano_acao_id) $sql->adOnde('projeto_gestao_acao IN ('.$plano_acao_id.')');
	elseif ($canvas_id) $sql->adOnde('projeto_gestao_canvas IN ('.$canvas_id.')');
	elseif ($risco_id) $sql->adOnde('projeto_gestao_risco IN ('.$risco_id.')');
	elseif ($risco_resposta_id) $sql->adOnde('projeto_gestao_risco_resposta IN ('.$risco_resposta_id.')');
	elseif ($calendario_id) $sql->adOnde('projeto_gestao_calendario IN ('.$calendario_id.')');
	elseif ($monitoramento_id) $sql->adOnde('projeto_gestao_monitoramento IN ('.$monitoramento_id.')');
	elseif ($ata_id) $sql->adOnde('projeto_gestao_ata IN ('.$ata_id.')');
	elseif ($mswot_id) $sql->adOnde('projeto_gestao_mswot IN ('.$mswot_id.')');
	elseif ($swot_id) $sql->adOnde('projeto_gestao_swot IN ('.$swot_id.')');
	elseif ($operativo_id) $sql->adOnde('projeto_gestao_operativo IN ('.$operativo_id.')');
	elseif ($instrumento_id) $sql->adOnde('projeto_gestao_instrumento IN ('.$instrumento_id.')');
	elseif ($recurso_id) $sql->adOnde('projeto_gestao_recurso IN ('.$recurso_id.')');
	elseif ($problema_id) $sql->adOnde('projeto_gestao_problema IN ('.$problema_id.')');
	elseif ($demanda_id) $sql->adOnde('projeto_gestao_demanda IN ('.$demanda_id.')');
	elseif ($programa_id) $sql->adOnde('projeto_gestao_programa IN ('.$programa_id.')');
	elseif ($licao_id) $sql->adOnde('projeto_gestao_licao IN ('.$licao_id.')');
	elseif ($evento_id) $sql->adOnde('projeto_gestao_evento IN ('.$evento_id.')');
	elseif ($link_id) $sql->adOnde('projeto_gestao_link IN ('.$link_id.')');
	elseif ($avaliacao_id) $sql->adOnde('projeto_gestao_avaliacao IN ('.$avaliacao_id.')');
	elseif ($tgn_id) $sql->adOnde('projeto_gestao_tgn IN ('.$tgn_id.')');
	elseif ($brainstorm_id) $sql->adOnde('projeto_gestao_brainstorm IN ('.$brainstorm_id.')');
	elseif ($gut_id) $sql->adOnde('projeto_gestao_gut IN ('.$gut_id.')');
	elseif ($causa_efeito_id) $sql->adOnde('projeto_gestao_causa_efeito IN ('.$causa_efeito_id.')');
	elseif ($arquivo_id) $sql->adOnde('projeto_gestao_arquivo IN ('.$arquivo_id.')');
	elseif ($forum_id) $sql->adOnde('projeto_gestao_forum IN ('.$forum_id.')');
	elseif ($checklist_id) $sql->adOnde('projeto_gestao_checklist IN ('.$checklist_id.')');
	elseif ($agenda_id) $sql->adOnde('projeto_gestao_agenda IN ('.$agenda_id.')');
	elseif ($agrupamento_id) $sql->adOnde('projeto_gestao_agrupamento IN ('.$agrupamento_id.')');
	elseif ($patrocinador_id) $sql->adOnde('projeto_gestao_patrocinador IN ('.$patrocinador_id.')');
	elseif ($template_id) $sql->adOnde('projeto_gestao_template IN ('.$template_id.')');
	elseif ($painel_id) $sql->adOnde('projeto_gestao_painel IN ('.$painel_id.')');
	elseif ($painel_odometro_id) $sql->adOnde('projeto_gestao_painel_odometro IN ('.$painel_odometro_id.')');
	elseif ($painel_composicao_id) $sql->adOnde('projeto_gestao_painel_composicao IN ('.$painel_composicao_id.')');
	elseif ($tr_id) $sql->adOnde('projeto_gestao_tr IN ('.$tr_id.')');
	elseif ($me_id) $sql->adOnde('projeto_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('projeto_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('projeto_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('projeto_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('projeto_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('projeto_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('projeto_gestao_plano_gestao IN ('.$pg_id.')');
	elseif ($ssti_id) $sql->adOnde('projeto_gestao_ssti IN ('.$ssti_id.')');
	elseif ($laudo_id) $sql->adOnde('projeto_gestao_laudo IN ('.$laudo_id.')');
	elseif ($trelo_id) $sql->adOnde('projeto_gestao_trelo IN ('.$trelo_id.')');
	elseif ($trelo_cartao_id) $sql->adOnde('projeto_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
	elseif ($pdcl_id) $sql->adOnde('projeto_gestao_pdcl IN ('.$pdcl_id.')');
	elseif ($pdcl_item_id) $sql->adOnde('projeto_gestao_pdcl_item IN ('.$pdcl_item_id.')');				
				
				
	if ($data_inicio && $data_termino) $sql->adOnde('(projeto_data_inicio >=\''.$data_inicio.' 00:00:00\' AND projeto_data_inicio <= \''.$data_termino.' 23:59:59\') OR (projeto_data_fim >=\''.$data_inicio.' 00:00:00\' AND projeto_data_fim <= \''.$data_termino.' 23:59:59\')');
	if ($projeto_setor && !$projeto_expandido) $sql->adOnde('pr.projeto_setor = '.(int)$projeto_setor);
	if ($projeto_segmento && !$projeto_expandido) $sql->adOnde('pr.projeto_segmento = '.(int)$projeto_segmento);
	if ($projeto_intervencao && !$projeto_expandido) $sql->adOnde('pr.projeto_intervencao = '.(int)$projeto_intervencao);
	if ($projeto_tipo_intervencao && !$projeto_expandido) $sql->adOnde('pr.projeto_tipo_intervencao = '.(int)$projeto_tipo_intervencao);
	if($projetos_by_area) $sql->adOnde('pr.projeto_id IN ('.$projetos_by_area.')');
	if ($supervisor && !$projeto_expandido) $sql->adOnde('pr.projeto_supervisor IN ('.$supervisor.')');
	if ($autoridade && !$projeto_expandido) $sql->adOnde('pr.projeto_autoridade IN ('.$autoridade.')');
	if ($cliente && !$projeto_expandido) $sql->adOnde('pr.projeto_cliente IN ('.$cliente.')');
	if ($estado_sigla) $sql->adOnde('pr.projeto_estado=\''.$estado_sigla.'\'');
	if ($municipio_id) $sql->adOnde('pr.projeto_cidade IN ('.$municipio_id.')');
	$sql->adOnde('pr.projeto_template'.($template ? '=1' : '=0 OR pr.projeto_template IS NULL'));

	if ($filtro_criterio && $Aplic->profissional){
		$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_nos_marcadores.pratica=projeto_gestao.projeto_gestao_pratica');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		}
	
	if ($filtro_criterio || $filtro_perspectiva || $filtro_canvas || $filtro_tema || $filtro_objetivo || $filtro_fator || $filtro_estrategia || $filtro_meta || $filtro_me)	{
		$filtragem=array();

		if (is_array($filtro_perspectiva)) $filtro_perspectiva=implode(',', $filtro_perspectiva);
		if (is_array($filtro_criterio)) $filtro_criterio=implode(',', $filtro_criterio);
		if (is_array($filtro_objetivo)) $filtro_objetivo=implode(',', $filtro_objetivo);
		if (is_array($filtro_tema)) $filtro_tema=implode(',', $filtro_tema);
		if (is_array($filtro_canvas)) $filtro_canvas=implode(',', $filtro_canvas);
		if (is_array($filtro_estrategia)) $filtro_estrategia=implode(',', $filtro_estrategia);
		if (is_array($filtro_fator)) $filtro_fator=implode(',', $filtro_fator);
		if (is_array($filtro_meta)) $filtro_meta=implode(',', $filtro_meta);
		if (is_array($filtro_me)) $filtro_me=implode(',', $filtro_me);


		if ($filtro_criterio) $filtragem[]='pratica_item_criterio IN ('.$filtro_criterio.')';
		if ($filtro_perspectiva) $filtragem[]='projeto_gestao_perspectiva IN ('.$filtro_perspectiva.')';
		if ($filtro_canvas) $filtragem[]='projeto_gestao_canvas IN ('.$filtro_canvas.')';
		if ($filtro_tema) $filtragem[]='projeto_gestao_tema IN ('.$filtro_tema.')';
		if ($filtro_objetivo) $filtragem[]='projeto_gestao_objetivo IN ('.$filtro_objetivo.')';
		if ($filtro_fator) $filtragem[]='projeto_gestao_fator IN ('.$filtro_fator.')';
		if ($filtro_estrategia) $filtragem[]='projeto_gestao_estrategia IN ('.$filtro_estrategia.')';
		if ($filtro_meta) $filtragem[]='projeto_gestao_meta IN ('.$filtro_meta.')';
		if ($filtro_me) $filtragem[]='projeto_gestao_me IN ('.$filtro_me.')';
		if (count($filtragem)) $sql->adOnde(implode(' OR ', $filtragem));
		}

	if (!$portfolio && !$portfolio_pai) $sql->adOnde('pr.projeto_portfolio IS NULL OR pr.projeto_portfolio=0');
	elseif($portfolio && !$portfolio_pai && $Aplic->profissional)  $sql->adOnde('pr.projeto_portfolio=1');
	if ($portfolio_pai && $Aplic->profissional){
		$sql->esqUnir('projeto_portfolio', 'projeto_portfolio', 'projeto_portfolio_filho = pr.projeto_id');
		$sql->adOnde('projeto_portfolio_pai = '.(int)$portfolio_pai);
		}
	if ($favorito_id && !$projeto_expandido){
		$sql->esqUnir('favorito_lista', 'favorito_lista', 'pr.projeto_id=favorito_lista_campo');
		$sql->esqUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
		$sql->adOnde('favorito.favorito_id IN ('.$favorito_id.')');
		}
	if($dept_id && !$projeto_expandido) $sql->esqUnir('projeto_depts', 'projeto_depts', 'projeto_depts.projeto_id = pr.projeto_id');

	if ($filtro_opcao=='equipe_tarefa'){
		$sql->esqUnir('tarefas', 't', 't.tarefa_projeto = pr.projeto_id');
		$sql->adOnde('t.tarefa_projetoex_id IS NULL');
		$sql->esqUnir('tarefa_designados', 'tu', 't.tarefa_id = tu.tarefa_id');
		$sql->adOnde('tu.usuario_id = '.(int)$Aplic->usuario_id.' OR tarefa_dono='.(int)$Aplic->usuario_id);
		}
	elseif ($filtro_opcao=='projeto_tarefa'){
		$sql->esqUnir('tarefas', 't', 't.tarefa_projeto = pr.projeto_id');
		$sql->adOnde('t.tarefa_projetoex_id IS NULL');
		$sql->esqUnir('tarefa_designados', 'tu', 't.tarefa_id = tu.tarefa_id');
		$sql->esqUnir('projeto_integrantes', 'pi', 'pi.projeto_id = t.tarefa_projeto');
		$sql->esqUnir('usuarios', 'usu3', 'usu3.usuario_contato = pi.contato_id');
		$sql->adOnde('tu.usuario_id = '.(int)$Aplic->usuario_id.' OR tarefa_dono='.(int)$Aplic->usuario_id.' OR usu3.usuario_id='.(int)$Aplic->usuario_id.' OR projeto_responsavel='.(int)$Aplic->usuario_id);
		}
	elseif ($filtro_opcao=='equipe_projeto'){
		$sql->esqUnir('projeto_integrantes', 'pi', 'pi.projeto_id = pr.projeto_id');
		$sql->esqUnir('usuarios', 'usu3', 'usu3.usuario_contato = pi.contato_id');
		$sql->adOnde('usu3.usuario_id='.(int)$Aplic->usuario_id.' OR projeto_responsavel='.(int)$Aplic->usuario_id);
		}
	elseif ($filtro_opcao=='gerente'){
		$sql->adOnde('projeto_responsavel='.(int)$Aplic->usuario_id);
		}
	elseif ($filtro_opcao=='tarefa'){
		$sql->esqUnir('tarefas', 't', 't.tarefa_projeto = pr.projeto_id');
		$sql->adOnde('t.tarefa_projetoex_id IS NULL');
		$sql->adOnde('tarefa_dono='.(int)$Aplic->usuario_id);
		}

	if ($adicionarProjComTarefasDesignadas && !$filtro_opcao) {
		$sql->esqUnir('tarefas', 't', 't.tarefa_projeto = pr.projeto_id');
		$sql->adOnde('t.tarefa_projetoex_id IS NULL');
		$sql->esqUnir('tarefa_designados', 'tu', 't.tarefa_id = tu.tarefa_id');
		}
	if ($projeto_expandido) $sql->adOnde('pr.projeto_superior='.$projeto_expandido. ' OR pr.projeto_id='.$projeto_expandido);
	elseif (!$nao_apenas_superiores) $sql->adOnde('pr.projeto_superior IS NULL OR pr.projeto_superior=0 OR pr.projeto_superior=pr.projeto_id');
	if ($recebido && !$projeto_expandido && $lista_cias && !$filtro_opcao) {
		$sql->esqUnir('projeto_observado', 'projeto_observado', 'projeto_observado.projeto_id = pr.projeto_id');
		$sql->adOnde('projeto_observado.cia_para IN ('.$lista_cias.')');
		$sql->adOnde('projeto_observado.aprovado = 1');
		$sql->adOnde('projeto_observado.cia_de NOT IN ('.$lista_cias.')');
		}
	if ($projetostatus && !$projeto_expandido){
		if ($Aplic->profissional){
			$projetostatus=explode(',',$projetostatus);
			if (in_array(-1,$projetostatus) && !in_array(-2,$projetostatus)) $sql->adOnde('projeto_ativo=1');
			if (in_array(-2,$projetostatus) && !in_array(-1,$projetostatus)) $sql->adOnde('projeto_ativo=0 OR projeto_ativo IS NULL');
			$qnt_status=0;
			$status_fim='';
			foreach($projetostatus as $status) if ($status > 0) $status_fim.=($qnt_status++ ? ',' : '').$status;
			if ($status_fim) $sql->adOnde('projeto_status IN ('.$status_fim.')');
			}
		else {
			if ($projetostatus == -1) $sql->adOnde('projeto_ativo=1');
			elseif ($projetostatus == -2) $sql->adOnde('projeto_ativo=0 OR projeto_ativo IS NULL');
			elseif ($projetostatus > 0) $sql->adOnde('projeto_status IN ('.$projetostatus.')');
			}
		}
	if($dept_id && !$projeto_expandido) $sql->adOnde('projeto_depts.departamento_id IN ('.$dept_id.') OR pr.projeto_dept IN ('.$dept_id.')');
	
	if (!$envolvimento && $cia_id && !$mostrarProjRespPertenceDept && !$recebido && !$lista_cias && !$projeto_expandido && !$favorito_id  && !$filtro_opcao)	$sql->adOnde('pr.projeto_cia = '.(int)$cia_id.' OR projeto_cia_cia='.(int)$cia_id.($template ? ' OR pr.projeto_cia IS NULL' : ''));
	elseif (!$envolvimento && !$mostrarProjRespPertenceDept && !$recebido && $lista_cias && !$projeto_expandido && !$favorito_id) $sql->adOnde('pr.projeto_cia IN ('.$lista_cias.') OR projeto_cia_cia IN ('.$lista_cias.')');

	elseif ($cia_id && !$mostrarProjRespPertenceDept && !$recebido && !$lista_cias && !$projeto_expandido && !$favorito_id  && !$filtro_opcao)	$sql->adOnde('pr.projeto_cia = '.(int)$cia_id);
	elseif (!$mostrarProjRespPertenceDept && !$recebido && $lista_cias && !$projeto_expandido && !$favorito_id) $sql->adOnde('pr.projeto_cia IN ('.$lista_cias.')');
	
	
	
	if ($projeto_tipo > -1 && !$projeto_expandido)	$sql->adOnde('pr.projeto_tipo IN ('.$projeto_tipo.')');
	if ($usuario_id && $adicionarProjComTarefasDesignadas && !$projeto_expandido && !$filtro_opcao) $sql->adOnde('(tu.usuario_id IN ('.$usuario_id.') OR pr.projeto_responsavel IN ('.$usuario_id.'))');
	elseif ($usuario_id && !$projeto_expandido && !$filtro_opcao) $sql->adOnde('pr.projeto_responsavel IN ('.$usuario_id.')');
	if (trim($pesquisar_texto) && !$projeto_expandido) $sql->adOnde('pr.projeto_nome LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_descricao LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_objetivos LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_como LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_codigo LIKE \'%'.$pesquisar_texto.'%\'');
	//if ($mostrarProjRespPertenceDept && !empty($responsavel_ids) && !$projeto_expandido) $sql->adOnde('pr.projeto_responsavel IN ('.implode(',', $responsavel_ids).')');

	if ($filtro_prioridade){
		$sql->esqUnir('priorizacao', 'priorizacao', 'pr.projeto_id=priorizacao_projeto');
		$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade.')');
		}

  if($filtro_extra_lista !== false){
    if(!$filtro_extra_lista) return array();
    $sql->adOnde('pr.projeto_id IN ('.$filtro_extra_lista.')');
    }
	$sql->adCampo('count(DISTINCT pr.projeto_id)');
	
	$qnt= $sql->Resultado();
	$sql->limpar();
	return $qnt;
	}

function projetos_inicio_data(FiltrosProjetoBuilder $filtros) {
	global $Aplic, $config;
		$envolvimento = $filtros->getEnvolvimento();
    $usuario_id = $filtros->getUsuarioId();
    $supervisor = $filtros->getSupervisor();
    $autoridade = $filtros->getAutoridade();
    $cliente = $filtros->getCliente();
    $cia_id = $filtros->getCiaId();
    $lista_cias = $filtros->getListaCias();
    $usar_periodo =  $filtros->getUsar_periodo();
		$reg_data_inicio =  $filtros->getReg_data_inicio();
		$reg_data_fim =  $filtros->getReg_data_fim();
    $ordenarPor = $filtros->getOrdenarPor();
    $ordemDir = $filtros->getOrdemDir();
    $projeto_tipo = $filtros->getProjetoTipo();
    $projeto_setor = $filtros->getProjetoSetor();
    $projeto_segmento = $filtros->getProjetoSegmento();
    $projeto_intervencao = $filtros->getProjetoIntervencao();
    $projeto_tipo_intervencao = $filtros->getProjetoTipoIntervencao();
    $estado_sigla = $filtros->getEstadoSigla();
    $municipio_id = $filtros->getMunicipioId();
    $pesquisar_texto = $filtros->getPesquisarTexto();
    $mostrarProjRespPertenceDept = $filtros->getMostrarProjRespPertenceDept();
    $recebido = $filtros->getRecebido();
    $dept_id = $filtros->getDeptId();
    $favorito_id = $filtros->getFavoritoId();
    $projetostatus = $filtros->getProjetoStatus();
    $ponto_inicio = $filtros->getPontoInicio();
    $projeto_expandido = $filtros->getProjetoExpandido();
    $nao_apenas_superiores = $filtros->getNaoApenasSuperiores();
    $exibir = $filtros->getExibir();
    $portfolio = $filtros->getPortfolio();
    $template = $filtros->getTemplate();
    $portfolio_pai = $filtros->getPortfolioPai();
    $limite = $filtros->getLimite();
    $data_inicio = $filtros->getDataInicio();
    $data_termino = $filtros->getDataTermino();
    $filtro_area = $filtros->getFiltroArea();
    $filtro_criterio = $filtros->getFiltroCriterio();
    $filtro_opcao = $filtros->getFiltroOpcao();
    $filtro_prioridade = $filtros->getFiltroPrioridade();
    $filtro_perspectiva = $filtros->getFiltroPerspectiva();
    $filtro_tema = $filtros->getFiltroTema();
    $filtro_objetivo = $filtros->getFiltroObjetivo();
    $filtro_fator = $filtros->getFiltroFator();
    $filtro_estrategia = $filtros->getFiltroEstrategia();
    $filtro_meta = $filtros->getFiltroMeta();
    $filtro_me = $filtros->getFiltroMe();
    $filtro_canvas = $filtros->getFiltroCanvas();
    $filtro_extra = $filtros->getFiltroExtra();
    
    
    $projeto_id = $filtros->getProjetoId();
		$tarefa_id = $filtros->getTarefaId();
    $pg_perspectiva_id = $filtros->getPgPerspectivaId();
    $tema_id = $filtros->getTemaId();
    $objetivo_id = $filtros->getPgObjetivoEstrategicoId();
    $fator_id = $filtros->getPgFatorCriticoId();
    $pg_estrategia_id = $filtros->getPgEstrategiaId();
    $pg_meta_id = $filtros->getPgMetaId();
    $pratica_id = $filtros->getPraticaId();
    $pratica_indicador_id = $filtros->getPraticaIndicadorId();
    $plano_acao_id = $filtros->getPlanoAcaoId();
    $canvas_id = $filtros->getCanvasId();
    $risco_id = $filtros->getRiscoId();
    $risco_resposta_id = $filtros->getRiscoRespostaId();
    $calendario_id = $filtros->getCalendarioId();
    $monitoramento_id = $filtros->getMonitoramentoId();
    $ata_id = $filtros->getAtaId();
    $mswot_id = $filtros->getMswotId();
    $swot_id = $filtros->getSwotId();
    $operativo_id = $filtros->getOperativoId();
    $instrumento_id = $filtros->getInstrumentoId();
    $recurso_id = $filtros->getRecursoId();
    $problema_id = $filtros->getProblemaId();
    $demanda_id = $filtros->getDemandaId();
    $programa_id = $filtros->getProgramaId();
    $licao_id = $filtros->getLicaoId();
    $evento_id = $filtros->getEventoId();
    $link_id = $filtros->getLinkId();
    $avaliacao_id = $filtros->getAvaliacaoId();
    $tgn_id = $filtros->getTgnId();
    $brainstorm_id = $filtros->getBrainstormId();
    $gut_id = $filtros->getGutId();
    $causa_efeito_id = $filtros->getCausaEfeitoId();
    $arquivo_id = $filtros->getArquivoId();
    $forum_id = $filtros->getForumId();
    $checklist_id = $filtros->getChecklistId();
    $agenda_id = $filtros->getAgendaId();
    $agrupamento_id = $filtros->getAgrupamentoId();
    $patrocinador_id = $filtros->getPatrocinadorId();
    $template_id = $filtros->getTemplateId();
    $painel_id = $filtros->getPainelId();
    $painel_odometro_id = $filtros->getPainelOdometroId();
    $painel_composicao_id = $filtros->getPainelComposicaoId();
    $tr_id = $filtros->getTrId();
		$me_id = $filtros->getMeId();
		$plano_acao_item_id = $filtros->getPlano_acao_itemId();
		$beneficio_id = $filtros->getBeneficioId();
		$painel_slideshow_id = $filtros->getPainel_slideshowId();
		$projeto_viabilidade_id = $filtros->getProjeto_viabilidadeId();
		$projeto_abertura_id = $filtros->getProjeto_aberturaId();
		$pg_id = $filtros->getPgId();
		$ssti_id = $filtros->getSstiId();		
		$laudo_id = $filtros->getLaudoId();		
		$trelo_id = $filtros->getTreloId();		
		$trelo_cartao_id = $filtros->getTrelo_cartaoId();		
		$pdcl_id = $filtros->getPdclId();		
		$pdcl_item_id = $filtros->getPdcl_itemId();
		
	$adicionarProjComTarefasDesignadas = $Aplic->getEstado('adicionarProjComTarefas') ? $Aplic->getEstado('adicionarProjComTarefas') : 0;
	$projetos_by_area = null;
	if($Aplic->profissional && $filtro_area){
		$projetos_by_area = implode(',',projeto_por_area_pro($filtro_area));
		if(!$projetos_by_area) return array();
		}
		
	if (!$lista_cias) $lista_cias=$cia_id;		
		
	$sql = new BDConsulta;
  $filtro_extra_lista = false;
  if($Aplic->profissional && $filtro_extra){
    $first = true;
    foreach($filtro_extra as $filtro){
      if(isset($filtro['campo_customizado_valor_atual']) && $filtro['campo_customizado_valor_atual']){
        $sql->adTabela('campo_customizado_valor');
        $sql->adCampo('campo_customizado_valor_objeto');

        if($filtro['campo_customizado_tipo_html'] == 'select' || $filtro['campo_customizado_tipo_html'] == 'selecionar'){
          $sql->adOnde('campo_customizado_valor_campo = '.$filtro['campo_customizado_id'].' AND campo_customizado_valor_caractere = \''.$filtro['campo_customizado_valor_atual'].'\'');
          }
        else if($filtro['campo_customizado_tipo_html'] == 'checkbox'){
          $valor = (int)$filtro['campo_customizado_valor_atual'];
          if($valor != 0){
            if($valor == 1) $valor = 1;
            else $valor = 0;
            $sql->adOnde('campo_customizado_valor_campo = '.$filtro['campo_customizado_id'].' AND campo_customizado_valor_inteiro = '.$valor);
            }
          }
        else{
          $sql->adOnde('campo_customizado_valor_campo = '.$filtro['campo_customizado_id'].' AND campo_customizado_valor_caractere = \''.$filtro['campo_customizado_valor_atual'].'\'');
          }

        $lista = $sql->listaVetorChave('campo_customizado_valor_objeto','campo_customizado_valor_objeto');
        $sql->limpar();

        if(!$first){
          $filtro_extra_lista = array_intersect($filtro_extra_lista, $lista);
          }
        else{
          $filtro_extra_lista = $lista;
          }

        $first = false;
        }
      }
    if(!$first){
      $filtro_extra_lista = implode(',', $filtro_extra_lista);
      }
    }
	$horas_trabalhadas = ($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
	
	$sql->adTabela('projetos', 'pr');
	$sql->esqUnir('projeto_cia', 'projeto_cia', 'pr.projeto_id = projeto_cia_projeto');
	$sql->adOnde('pr.projeto_plano_operativo=0 OR pr.projeto_plano_operativo IS NULL');
	
	$sql->esqUnir('municipios', 'municipios', 'pr.projeto_cidade = municipios.municipio_id');
	

	if ($usar_periodo){
		$sql->esqUnir('tarefas','tarefas3', 'tarefas3.tarefa_projeto=pr.projeto_id');
		$sql->adOnde('tarefas3.tarefa_inicio <= \''.$reg_data_fim.' 23:59:59\'');
		$sql->adOnde('tarefas3.tarefa_fim >= \''.$reg_data_inicio.' 00:00:00\'');
		}
	
	$sql->esqUnir('projeto_gestao','projeto_gestao','projeto_gestao_projeto = pr.projeto_id');
	if ($tarefa_id) $sql->adOnde('projeto_gestao_tarefa IN ('.$tarefa_id.')');
	elseif ($projeto_id){
		$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=projeto_gestao_tarefa');
		$sql->adOnde('projeto_gestao_semelhante IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
		}
	elseif ($pg_perspectiva_id) $sql->adOnde('projeto_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('projeto_gestao_tema IN ('.$tema_id.')');
	elseif ($objetivo_id) $sql->adOnde('projeto_gestao_objetivo IN ('.$objetivo_id.')');
	elseif ($fator_id) $sql->adOnde('projeto_gestao_fator IN ('.$fator_id.')');
	elseif ($pg_estrategia_id) $sql->adOnde('projeto_gestao_estrategia IN ('.$pg_estrategia_id.')');
	elseif ($pg_meta_id) $sql->adOnde('projeto_gestao_meta IN ('.$pg_meta_id.')');
	elseif ($pratica_id) $sql->adOnde('projeto_gestao_pratica IN ('.$pratica_id.')');
	elseif ($pratica_indicador_id) $sql->adOnde('projeto_gestao_indicador IN ('.$pratica_indicador_id.')');
	elseif ($plano_acao_id) $sql->adOnde('projeto_gestao_acao IN ('.$plano_acao_id.')');
	elseif ($canvas_id) $sql->adOnde('projeto_gestao_canvas IN ('.$canvas_id.')');
	elseif ($risco_id) $sql->adOnde('projeto_gestao_risco IN ('.$risco_id.')');
	elseif ($risco_resposta_id) $sql->adOnde('projeto_gestao_risco_resposta IN ('.$risco_resposta_id.')');
	elseif ($calendario_id) $sql->adOnde('projeto_gestao_calendario IN ('.$calendario_id.')');
	elseif ($monitoramento_id) $sql->adOnde('projeto_gestao_monitoramento IN ('.$monitoramento_id.')');
	elseif ($ata_id) $sql->adOnde('projeto_gestao_ata IN ('.$ata_id.')');
	elseif ($mswot_id) $sql->adOnde('projeto_gestao_mswot IN ('.$mswot_id.')');
	elseif ($swot_id) $sql->adOnde('projeto_gestao_swot IN ('.$swot_id.')');
	elseif ($operativo_id) $sql->adOnde('projeto_gestao_operativo IN ('.$operativo_id.')');
	elseif ($instrumento_id) $sql->adOnde('projeto_gestao_instrumento IN ('.$instrumento_id.')');
	elseif ($recurso_id) $sql->adOnde('projeto_gestao_recurso IN ('.$recurso_id.')');
	elseif ($problema_id) $sql->adOnde('projeto_gestao_problema IN ('.$problema_id.')');
	elseif ($demanda_id) $sql->adOnde('projeto_gestao_demanda IN ('.$demanda_id.')');
	elseif ($programa_id) $sql->adOnde('projeto_gestao_programa IN ('.$programa_id.')');
	elseif ($licao_id) $sql->adOnde('projeto_gestao_licao IN ('.$licao_id.')');
	elseif ($evento_id) $sql->adOnde('projeto_gestao_evento IN ('.$evento_id.')');
	elseif ($link_id) $sql->adOnde('projeto_gestao_link IN ('.$link_id.')');
	elseif ($avaliacao_id) $sql->adOnde('projeto_gestao_avaliacao IN ('.$avaliacao_id.')');
	elseif ($tgn_id) $sql->adOnde('projeto_gestao_tgn IN ('.$tgn_id.')');
	elseif ($brainstorm_id) $sql->adOnde('projeto_gestao_brainstorm IN ('.$brainstorm_id.')');
	elseif ($gut_id) $sql->adOnde('projeto_gestao_gut IN ('.$gut_id.')');
	elseif ($causa_efeito_id) $sql->adOnde('projeto_gestao_causa_efeito IN ('.$causa_efeito_id.')');
	elseif ($arquivo_id) $sql->adOnde('projeto_gestao_arquivo IN ('.$arquivo_id.')');
	elseif ($forum_id) $sql->adOnde('projeto_gestao_forum IN ('.$forum_id.')');
	elseif ($checklist_id) $sql->adOnde('projeto_gestao_checklist IN ('.$checklist_id.')');
	elseif ($agenda_id) $sql->adOnde('projeto_gestao_agenda IN ('.$agenda_id.')');
	elseif ($agrupamento_id) $sql->adOnde('projeto_gestao_agrupamento IN ('.$agrupamento_id.')');
	elseif ($patrocinador_id) $sql->adOnde('projeto_gestao_patrocinador IN ('.$patrocinador_id.')');
	elseif ($template_id) $sql->adOnde('projeto_gestao_template IN ('.$template_id.')');
	elseif ($painel_id) $sql->adOnde('projeto_gestao_painel IN ('.$painel_id.')');
	elseif ($painel_odometro_id) $sql->adOnde('projeto_gestao_painel_odometro IN ('.$painel_odometro_id.')');
	elseif ($painel_composicao_id) $sql->adOnde('projeto_gestao_painel_composicao IN ('.$painel_composicao_id.')');
	elseif ($tr_id) $sql->adOnde('projeto_gestao_tr IN ('.$tr_id.')');
	elseif ($me_id) $sql->adOnde('projeto_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('projeto_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('projeto_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('projeto_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('projeto_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('projeto_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('projeto_gestao_plano_gestao IN ('.$pg_id.')');
	elseif ($ssti_id) $sql->adOnde('projeto_gestao_ssti IN ('.$ssti_id.')');
	elseif ($laudo_id) $sql->adOnde('projeto_gestao_laudo IN ('.$laudo_id.')');
	elseif ($trelo_id) $sql->adOnde('projeto_gestao_trelo IN ('.$trelo_id.')');
	elseif ($trelo_cartao_id) $sql->adOnde('projeto_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
	elseif ($pdcl_id) $sql->adOnde('projeto_gestao_pdcl IN ('.$pdcl_id.')');
	elseif ($pdcl_item_id) $sql->adOnde('projeto_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
	
	if ($data_inicio && $data_termino) $sql->adOnde('(projeto_data_inicio >=\''.$data_inicio.' 00:00:00\' AND projeto_data_inicio <= \''.$data_termino.' 23:59:59\') OR (projeto_data_fim >=\''.$data_inicio.' 00:00:00\' AND projeto_data_fim <= \''.$data_termino.' 23:59:59\')');
	if ($projeto_setor && !$projeto_expandido) $sql->adOnde('pr.projeto_setor = '.(int)$projeto_setor);
	if ($projeto_segmento && !$projeto_expandido) $sql->adOnde('pr.projeto_segmento = '.(int)$projeto_segmento);
	if ($projeto_intervencao && !$projeto_expandido) $sql->adOnde('pr.projeto_intervencao = '.(int)$projeto_intervencao);
	if ($projeto_tipo_intervencao && !$projeto_expandido) $sql->adOnde('pr.projeto_tipo_intervencao = '.(int)$projeto_tipo_intervencao);
	if($projetos_by_area) $sql->adOnde('pr.projeto_id IN ('.$projetos_by_area.')');
	if ($supervisor && !$projeto_expandido) $sql->adOnde('pr.projeto_supervisor IN ('.$supervisor.')');
	if ($autoridade && !$projeto_expandido) $sql->adOnde('pr.projeto_autoridade IN ('.$autoridade.')');
	if ($cliente && !$projeto_expandido) $sql->adOnde('pr.projeto_cliente IN ('.$cliente.')');
	if ($estado_sigla) $sql->adOnde('pr.projeto_estado=\''.$estado_sigla.'\'');
	if ($municipio_id) $sql->adOnde('pr.projeto_cidade IN ('.$municipio_id.')');
	$sql->adOnde('pr.projeto_template'.($template ? '=1' : '=0 OR pr.projeto_template IS NULL'));

	if ($filtro_criterio && $Aplic->profissional){
		$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_nos_marcadores.pratica=projeto_gestao.projeto_gestao_pratica');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		}

	if ($filtro_criterio || $filtro_perspectiva || $filtro_canvas || $filtro_tema || $filtro_objetivo || $filtro_fator || $filtro_estrategia || $filtro_meta || $filtro_me)	{
		$filtragem=array();

		if (is_array($filtro_perspectiva)) $filtro_perspectiva=implode(',', $filtro_perspectiva);
		if (is_array($filtro_criterio)) $filtro_criterio=implode(',', $filtro_criterio);
		if (is_array($filtro_objetivo)) $filtro_objetivo=implode(',', $filtro_objetivo);
		if (is_array($filtro_tema)) $filtro_tema=implode(',', $filtro_tema);
		if (is_array($filtro_canvas)) $filtro_canvas=implode(',', $filtro_canvas);
		if (is_array($filtro_estrategia)) $filtro_estrategia=implode(',', $filtro_estrategia);
		if (is_array($filtro_fator)) $filtro_fator=implode(',', $filtro_fator);
		if (is_array($filtro_meta)) $filtro_meta=implode(',', $filtro_meta);
		if (is_array($filtro_me)) $filtro_me=implode(',', $filtro_me);

		if ($filtro_criterio) $filtragem[]='pratica_item_criterio IN ('.$filtro_criterio.')';
		if ($filtro_perspectiva) $filtragem[]='projeto_gestao_perspectiva IN ('.$filtro_perspectiva.')';
		if ($filtro_canvas) $filtragem[]='projeto_gestao_canvas IN ('.$filtro_canvas.')';
		if ($filtro_tema) $filtragem[]='projeto_gestao_tema IN ('.$filtro_tema.')';
		if ($filtro_objetivo) $filtragem[]='projeto_gestao_objetivo IN ('.$filtro_objetivo.')';
		if ($filtro_fator) $filtragem[]='projeto_gestao_fator IN ('.$filtro_fator.')';
		if ($filtro_estrategia) $filtragem[]='projeto_gestao_estrategia IN ('.$filtro_estrategia.')';
		if ($filtro_meta) $filtragem[]='projeto_gestao_meta IN ('.$filtro_meta.')';
		if ($filtro_me) $filtragem[]='projeto_gestao_me IN ('.$filtro_me.')';
		if (count($filtragem)) $sql->adOnde(implode(' OR ', $filtragem));
		}

	if (!$portfolio && !$portfolio_pai) $sql->adOnde('pr.projeto_portfolio IS NULL OR pr.projeto_portfolio=0');
	elseif($portfolio && !$portfolio_pai && $Aplic->profissional)  $sql->adOnde('pr.projeto_portfolio=1');
	if ($portfolio_pai && $Aplic->profissional){
		$sql->esqUnir('projeto_portfolio', 'projeto_portfolio', 'projeto_portfolio_filho = pr.projeto_id');
		$sql->adOnde('projeto_portfolio_pai = '.(int)$portfolio_pai);
		}
	if ($favorito_id && !$projeto_expandido){
		$sql->esqUnir('favorito_lista', 'favorito_lista', 'pr.projeto_id=favorito_lista_campo');
		$sql->esqUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
		$sql->adOnde('favorito.favorito_id IN ('.$favorito_id.')');
		}
	if($dept_id && !$projeto_expandido) $sql->esqUnir('projeto_depts', 'projeto_depts', 'projeto_depts.projeto_id = pr.projeto_id');

	if ($filtro_opcao=='equipe_tarefa'){
		$sql->esqUnir('tarefas', 't', 't.tarefa_projeto = pr.projeto_id');
		$sql->adOnde('t.tarefa_projetoex_id IS NULL');
		$sql->esqUnir('tarefa_designados', 'tu', 't.tarefa_id = tu.tarefa_id');
		$sql->adOnde('tu.usuario_id = '.(int)$Aplic->usuario_id.' OR tarefa_dono='.(int)$Aplic->usuario_id);
		}
	elseif ($filtro_opcao=='projeto_tarefa'){
		$sql->esqUnir('tarefas', 't', 't.tarefa_projeto = pr.projeto_id');
		$sql->adOnde('t.tarefa_projetoex_id IS NULL');
		$sql->esqUnir('tarefa_designados', 'tu', 't.tarefa_id = tu.tarefa_id');
		$sql->esqUnir('projeto_integrantes', 'pi', 'pi.projeto_id = t.tarefa_projeto');
		$sql->esqUnir('usuarios', 'usu3', 'usu3.usuario_contato = pi.contato_id');
		$sql->adOnde('tu.usuario_id = '.(int)$Aplic->usuario_id.' OR tarefa_dono='.(int)$Aplic->usuario_id.' OR usu3.usuario_id='.(int)$Aplic->usuario_id.' OR projeto_responsavel='.(int)$Aplic->usuario_id);
		}
	elseif ($filtro_opcao=='equipe_projeto'){
		$sql->esqUnir('projeto_integrantes', 'pi', 'pi.projeto_id = pr.projeto_id');
		$sql->esqUnir('usuarios', 'usu3', 'usu3.usuario_contato = pi.contato_id');
		$sql->adOnde('usu3.usuario_id='.(int)$Aplic->usuario_id.' OR projeto_responsavel='.(int)$Aplic->usuario_id);
		}
	elseif ($filtro_opcao=='gerente'){
		$sql->adOnde('projeto_responsavel='.(int)$Aplic->usuario_id);
		}
	elseif ($filtro_opcao=='tarefa'){
		$sql->esqUnir('tarefas', 't', 't.tarefa_projeto = pr.projeto_id');
		$sql->adOnde('t.tarefa_projetoex_id IS NULL');
		$sql->adOnde('tarefa_dono='.(int)$Aplic->usuario_id);
		}

	if ($adicionarProjComTarefasDesignadas && !$filtro_opcao) {
		$sql->esqUnir('tarefas', 't', 't.tarefa_projeto = pr.projeto_id');
		$sql->adOnde('t.tarefa_projetoex_id IS NULL');
		$sql->esqUnir('tarefa_designados', 'tu', 't.tarefa_id = tu.tarefa_id');
		}
	if ($projeto_expandido) $sql->adOnde('pr.projeto_superior='.$projeto_expandido. ' OR pr.projeto_id='.$projeto_expandido);
	elseif (!$nao_apenas_superiores) $sql->adOnde('pr.projeto_superior IS NULL OR pr.projeto_superior=0 OR pr.projeto_superior=pr.projeto_id');
	if ($recebido && !$projeto_expandido && $lista_cias && !$filtro_opcao) {
		$sql->esqUnir('projeto_observado', 'projeto_observado', 'projeto_observado.projeto_id = pr.projeto_id');
		$sql->adOnde('projeto_observado.cia_para IN ('.$lista_cias.')');
		$sql->adOnde('projeto_observado.aprovado = 1');
		$sql->adOnde('projeto_observado.cia_de NOT IN ('.$lista_cias.')');
		}
	if ($projetostatus && !$projeto_expandido){
		if ($Aplic->profissional){
			$projetostatus=explode(',',$projetostatus);
			if (in_array(-1,$projetostatus) && !in_array(-2,$projetostatus)) $sql->adOnde('projeto_ativo=1');
			if (in_array(-2,$projetostatus) && !in_array(-1,$projetostatus)) $sql->adOnde('projeto_ativo=0 OR projeto_ativo IS NULL');
			$qnt_status=0;
			$status_fim='';
			foreach($projetostatus as $status) if ($status > 0) $status_fim.=($qnt_status++ ? ',' : '').$status;
			if ($status_fim) $sql->adOnde('projeto_status IN ('.$status_fim.')');
			}
		else {
			if ($projetostatus == -1) $sql->adOnde('projeto_ativo=1');
			elseif ($projetostatus == -2) $sql->adOnde('projeto_ativo=0 OR projeto_ativo IS NULL');
			elseif ($projetostatus > 0) $sql->adOnde('projeto_status IN ('.$projetostatus.')');
			}
		}
	if($dept_id && !$projeto_expandido) $sql->adOnde('projeto_depts.departamento_id IN ('.$dept_id.') OR pr.projeto_dept IN ('.$dept_id.')');
	
	if (!$envolvimento && $cia_id && !$mostrarProjRespPertenceDept && !$recebido && !$lista_cias && !$projeto_expandido && !$favorito_id  && !$filtro_opcao)	$sql->adOnde('pr.projeto_cia = '.(int)$cia_id.' OR projeto_cia_cia='.(int)$cia_id.($template ? ' OR pr.projeto_cia IS NULL' : ''));
	elseif (!$envolvimento && !$mostrarProjRespPertenceDept && !$recebido && $lista_cias && !$projeto_expandido && !$favorito_id) $sql->adOnde('pr.projeto_cia IN ('.$lista_cias.') OR projeto_cia_cia IN ('.$lista_cias.')');
	
	elseif ($cia_id && !$mostrarProjRespPertenceDept && !$recebido && !$lista_cias && !$projeto_expandido && !$favorito_id  && !$filtro_opcao)	$sql->adOnde('pr.projeto_cia = '.(int)$cia_id);
	elseif (!$mostrarProjRespPertenceDept && !$recebido && $lista_cias && !$projeto_expandido && !$favorito_id) $sql->adOnde('pr.projeto_cia IN ('.$lista_cias.')');
	
	
	if ($projeto_tipo > -1 && !$projeto_expandido)	$sql->adOnde('pr.projeto_tipo IN ('.$projeto_tipo.')');
	if ($usuario_id && $adicionarProjComTarefasDesignadas && !$projeto_expandido && !$filtro_opcao) $sql->adOnde('(tu.usuario_id IN ('.$usuario_id.') OR pr.projeto_responsavel IN ('.$usuario_id.'))');
	elseif ($usuario_id && !$projeto_expandido && !$filtro_opcao) $sql->adOnde('pr.projeto_responsavel IN ('.$usuario_id.')');
	if (trim($pesquisar_texto) && !$projeto_expandido) $sql->adOnde('pr.projeto_nome LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_descricao LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_objetivos LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_como LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_codigo LIKE \'%'.$pesquisar_texto.'%\'');
	//if ($mostrarProjRespPertenceDept && !empty($responsavel_ids) && !$projeto_expandido) $sql->adOnde('pr.projeto_responsavel IN ('.implode(',', $responsavel_ids).')');
	$sql->adCampo('pr.*');
	$sql->adCampo('municipios.municipio_nome');
	if ($filtro_prioridade){
		$sql->esqUnir('priorizacao', 'priorizacao', 'pr.projeto_id=priorizacao_projeto');
		if ($config['metodo_priorizacao']) $sql->adCampo('(SELECT round(exp(sum(log(coalesce(priorizacao_valor,1))))) FROM priorizacao WHERE priorizacao_projeto = pr.projeto_id AND priorizacao_modelo IN ('.$filtro_prioridade.')) AS priorizacao');
		else $sql->adCampo('(SELECT SUM(priorizacao_valor) FROM priorizacao WHERE priorizacao_projeto = pr.projeto_id AND priorizacao_modelo IN ('.$filtro_prioridade.')) AS priorizacao');
		$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade.')');
		}


	if ($Aplic->profissional) $sql->adCampo('(SELECT count(assinatura_id) FROM assinatura WHERE assinatura_aprova=1 AND assinatura_projeto=pr.projeto_id) AS tem_aprovacao');	
	$sql->adCampo('projeto_aprovado, projeto_portfolio');

	if ($ordenarPor=='priorizacao' && !$filtro_prioridade) $ordenarPor='projeto_nome';

	if ($ordenarPor && $ordemDir) $sql->adOrdem($ordenarPor.' ' .$ordemDir);

	$sql->adGrupo('projeto_id');

	if ($limite) $sql->setLimite($ponto_inicio, $limite);

  if($filtro_extra_lista !== false){
    if(!$filtro_extra_lista) return array();
    $sql->adOnde('pr.projeto_id IN ('.$filtro_extra_lista.')');
    }
	   
	$projetos = $sql->Lista();
	$sql->limpar();

	foreach ($projetos as $chave => $projeto){
		//cada projeto pode ser na verdade um portfolio
		$vetor_projetos=array($projeto['projeto_id'] => $projeto['projeto_id']);
		if ($Aplic->profissional){
			portfolio_projetos($projeto['projeto_id'], $vetor_projetos);
			}
		$vetor_projetos=implode(',',$vetor_projetos);


		//este bloco de custo e gasto é novo
		if (isset($exibir['custo']) && $exibir['custo']){
			$sql->adTabela('tarefa_custos');
			$sql->esqUnir('tarefas','tarefas','tarefa_id=tarefa_custos_tarefa');
			$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
			$sql->adCampo('SUM(tarefa_custos_quantidade*tarefa_custos_custo)');
			$sql->adOnde('tarefa_projeto IN ('.$vetor_projetos.')');
			if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('tarefa_custos_aprovado = 1');
			$custo1=$sql->Resultado();
			$sql->limpar();
			
			$sql->adTabela('projeto_custo');
			$sql->adCampo('SUM(projeto_custo_quantidade*projeto_custo_custo)');
			$sql->adOnde('projeto_custo_projeto IN ('.$vetor_projetos.')');
			if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('projeto_custo_aprovado = 1');
			$custo2=$sql->Resultado();
			$sql->limpar();
			$projetos[$chave]['projeto_custo']=$custo1+$custo2;
			}
			
		if (isset($exibir['gasto']) && $exibir['gasto']){
			$sql->adTabela('tarefa_gastos');
			$sql->esqUnir('tarefas','tarefas','tarefa_id=tarefa_gastos_tarefa');
			$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
			$sql->adCampo('SUM(tarefa_gastos_quantidade*tarefa_gastos_custo)');
			$sql->adOnde('tarefa_projeto IN ('.$vetor_projetos.')');
			if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('tarefa_gastos_aprovado = 1');
			$projetos[$chave]['projeto_gasto']=$sql->Resultado();
			$sql->limpar();
			}


		if (isset($exibir['recursos']) && $exibir['recursos']){
			$sql->adTabela('recurso_tarefa');
			$sql->esqUnir('recursos','recursos','recurso_tarefa_recurso=recursos.recurso_id');
			$sql->esqUnir('tarefas','tarefas','tarefas.tarefa_id=recurso_tarefa_tarefa');
			$sql->adOnde('tarefas.tarefa_projetoex_id IS NULL');
			$sql->adCampo('SUM(recurso_tarefa_quantidade)');
			$sql->adOnde('tarefa_projeto IN ('.$vetor_projetos.')');
			$sql->adOnde('recursos.recurso_tipo=5');
			$projetos[$chave]['total_recursos']=$sql->Resultado();
			$sql->limpar();
			}

		if (isset($exibir['fisico']) && $exibir['fisico'] && $projeto['projeto_portfolio']){
			$projetos[$chave]['projeto_percentagem']=$porcentagem=portfolio_porcentagem($projeto['projeto_id']);
			$sql->limpar();
			}

		$sql->adTabela('tarefas');
		$sql->adCampo('COUNT(distinct tarefas.tarefa_id)');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		$sql->adOnde('tarefa_projeto IN ('.$vetor_projetos.')');
		$projetos[$chave]['total_tarefas']=$sql->Resultado();
		$sql->limpar();

		$sql->adTabela('tarefas');
		$sql->adCampo('COUNT(distinct tarefas.tarefa_id)');
		$sql->adOnde('tarefa_projeto IN ('.$vetor_projetos.')');
		$sql->adOnde('tarefa_dono='.$Aplic->usuario_id);
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		$projetos[$chave]['minhas_tarefas']=$sql->Resultado();
		$sql->limpar();

		//log de correções de projetos
        $sql->adTabela( 'log', 'l' );
        $sql->esqUnir( 'projetos', 'p', 'l.log_projeto = p.projeto_id' );
        $sql->adOnde( '(l.log_correcao != 0 AND l.log_correcao IS NOT NULL)' );
        $sql->adOnde('p.projeto_id IN ('.$vetor_projetos.')');
        $sql->adCampo( 'l.log_correcao AS corrigido_id' );
        $log_corrigidos = $sql->listaVetorChave( 'corrigido_id', 'corrigido_id' );
        $sql->limpar();

        //log de problemas dos projetos
        $sql->adTabela('log');
        $sql->esqUnir( 'projetos', 'p', 'log.log_projeto = p.projeto_id' );
        $sql->adCampo('COUNT(distinct log_id)');
        $sql->adOnde('p.projeto_id IN ('.$vetor_projetos.')');
        if( !empty( $log_corrigidos ) ) {
            $sql->adOnde( 'log.log_id NOT IN (' . implode( ',', $log_corrigidos ) . ')' );
        }
        $sql->adOnde('log_corrigir=1');
        $projetos[$chave]['log_corrigir']=(int)$sql->Resultado();
        $sql->limpar();

		//log de correções das tarefas
        $sql->adTabela( 'log', 'l' );
        $sql->esqUnir( 'tarefas', 't', 'l.log_tarefa = t.tarefa_id' );
        $sql->adOnde( '(l.log_correcao != 0 AND l.log_correcao IS NOT NULL)' );
        $sql->adOnde('tarefa_projeto IN ('.$vetor_projetos.')');
        $sql->adOnde('tarefa_projetoex_id IS NULL');
        $sql->adCampo( 'l.log_correcao AS corrigido_id' );
        $log_corrigidos = $sql->listaVetorChave( 'corrigido_id', 'corrigido_id' );
        $sql->limpar();

        //log de problemas das tarefas
		$sql->adTabela('log');
		$sql->esqUnir('tarefas','tarefas','log.log_tarefa=tarefas.tarefa_id');
		$sql->adCampo('COUNT(distinct log_id)');
		$sql->adOnde('tarefa_projeto IN ('.$vetor_projetos.')');
		if( !empty( $log_corrigidos ) ) {
            $sql->adOnde( 'log.log_id NOT IN (' . implode( ',', $log_corrigidos ) . ')' );
        }
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		$sql->adOnde('log_corrigir=1');
        $projetos[$chave]['log_corrigir'] += (int) $sql->Resultado();
		$sql->limpar();

		$sql->adTabela('tarefas');
		$sql->adCampo('MAX(tarefa_fim) AS data_fim');
		$sql->adOnde('tarefa_projeto IN ('.$vetor_projetos.')');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		$projetos[$chave]['projeto_fim_atualizado']=$sql->Resultado();
		$sql->limpar();

		$projeto_data_fim=$projetos[$chave]['projeto_fim_atualizado'];

		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_id');
		$sql->adOnde('tarefa_projeto IN ('.$vetor_projetos.')');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		$sql->adOnde('tarefa_fim=\''.$projetos[$chave]['projeto_fim_atualizado'].'\'');
		$projetos[$chave]['critica_tarefa']=$sql->Resultado();
		$sql->limpar();

		$sql->adTabela('tarefas');
		$sql->adCampo('MIN(tarefa_inicio) AS data_inicio');
		$sql->adOnde('tarefa_projeto IN ('.$vetor_projetos.')');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		$projetos[$chave]['projeto_inicio_atualizado']=$sql->Resultado();
		$sql->limpar();

		$projeto_data_inicio=$projetos[$chave]['projeto_inicio_atualizado'];

		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_id');
		$sql->adOnde('tarefa_projeto IN ('.$vetor_projetos.')');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		$sql->adOnde('tarefa_inicio=\''.$projetos[$chave]['projeto_inicio_atualizado'].'\'');
		$projetos[$chave]['critica_inicio_tarefa']=$sql->Resultado();
		$sql->limpar();

		$hoje=date('Y-m-d H:i:s');
		$projeto_percentagem=$projetos[$chave]['projeto_percentagem'];

		if ($projeto_percentagem==100) $status='#aaddaa';
		elseif ($projeto_data_inicio > $hoje) $status='#ffffff';
		elseif (($projeto_data_fim < $hoje) && $projeto_percentagem < 100) $status='#cc6666';
		elseif (($projeto_data_fim > $hoje) && ($projeto_data_inicio < $hoje) && $projeto_percentagem > 0) $status='#e6eedd';
		else $status='#ffeebb';

		$projetos[$chave]['projeto_situacao']=$status;
		}
	return $projetos;
	}


function listar_simples_projetos($usuario_id=false, $supervisor=false, $autoridade=false, $cliente=false, $cia_id=0, $ordenarPor='projeto_nome',$ordemDir='ASC', $projeto_tipo=0, $projeto_setor=0, $projeto_segmento=0, $projeto_intervencao=0, $projeto_tipo_intervencao=0, $estado_sigla='', $municipio_id='', $pesquisar_texto='', $mostrarProjRespPertenceDept=0, $recebido=false, $dept_id=0, $favorito_id=0, $lista_cias='', $projetostatus=0, $ponto_inicio=0, $projeto_expandido=0, $nao_apenas_superiores=false, $exibir=array(), $portfolio=false, $template=false, $portfolio_pai=false, $limite=true, $data_inicio=null, $data_termino=null, $filtro_area = '') {
	global $Aplic, $config;
	$adicionarProjComTarefasDesignadas = $Aplic->getEstado('adicionarProjComTarefas') ? $Aplic->getEstado('adicionarProjComTarefas') : 0;

	$projetos_by_area = null;
	if($Aplic->profissional && $filtro_area){
		$projetos_by_area = implode(',',projeto_por_area_pro($filtro_area));
		if(!$projetos_by_area) return array();
		}

	$sql = new BDConsulta;
	$horas_trabalhadas = ($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
	$sql->adTabela('projetos', 'pr');
	$sql->esqUnir('projeto_cia', 'projeto_cia', 'pr.projeto_id = projeto_cia_projeto');
	$sql->adOnde('pr.projeto_plano_operativo=0 OR pr.projeto_plano_operativo IS NULL');
	if ($data_inicio && $data_termino) $sql->adOnde('(projeto_data_inicio >=\''.$data_inicio.' 00:00:00\' AND projeto_data_inicio <= \''.$data_termino.' 23:59:59\') OR (projeto_data_fim >=\''.$data_inicio.' 00:00:00\' AND projeto_data_fim <= \''.$data_termino.' 23:59:59\')');
	if ($projeto_setor && !$projeto_expandido) $sql->adOnde('pr.projeto_setor = '.(int)$projeto_setor);
	if ($projeto_segmento && !$projeto_expandido) $sql->adOnde('pr.projeto_segmento = '.(int)$projeto_segmento);
	if ($projeto_intervencao && !$projeto_expandido) $sql->adOnde('pr.projeto_intervencao = '.(int)$projeto_intervencao);
	if ($projeto_tipo_intervencao && !$projeto_expandido) $sql->adOnde('pr.projeto_tipo_intervencao = '.(int)$projeto_tipo_intervencao);
	if($projetos_by_area) $sql->adOnde('pr.projeto_id IN ('.$projetos_by_area.')');
	if ($supervisor && !$projeto_expandido) $sql->adOnde('pr.projeto_supervisor IN ('.$supervisor.')');
	if ($autoridade && !$projeto_expandido) $sql->adOnde('pr.projeto_autoridade IN ('.$autoridade.')');
	if ($cliente && !$projeto_expandido) $sql->adOnde('pr.projeto_cliente IN ('.$cliente.')');
	if ($estado_sigla) $sql->adOnde('pr.projeto_estado=\''.$estado_sigla.'\'');
	if ($municipio_id) $sql->adOnde('pr.projeto_cidade IN ('.$municipio_id.')');
	$sql->adOnde('pr.projeto_template'.($template ? '=1' : '=0 OR pr.projeto_template IS NULL'));
	if (!$portfolio && !$portfolio_pai) $sql->adOnde('pr.projeto_portfolio IS NULL OR pr.projeto_portfolio=0');
	elseif($portfolio && !$portfolio_pai && $Aplic->profissional)  $sql->adOnde('pr.projeto_portfolio=1');
	if ($portfolio_pai && $Aplic->profissional){
		$sql->esqUnir('projeto_portfolio', 'projeto_portfolio', 'projeto_portfolio_filho = pr.projeto_id');
		$sql->adOnde('projeto_portfolio_pai = '.(int)$portfolio_pai);
		}
	if ($favorito_id && !$projeto_expandido){
		$sql->esqUnir('favorito_lista', 'favorito_lista', 'pr.projeto_id=favorito_lista_campo');
		$sql->esqUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
		$sql->adOnde('favorito.favorito_id IN ('.$favorito_id.')');
		}
	if($dept_id && !$projeto_expandido) $sql->esqUnir('projeto_depts', 'projeto_depts', 'projeto_depts.projeto_id = pr.projeto_id');
	if ($adicionarProjComTarefasDesignadas) {
		$sql->esqUnir('tarefas', 't', 't.tarefa_projeto = pr.projeto_id');
		$sql->adOnde('t.tarefa_projetoex_id IS NULL');
		$sql->esqUnir('tarefa_designados', 'tu', 't.tarefa_id = tu.tarefa_id');
		}
	if ($projeto_expandido) $sql->adOnde('pr.projeto_superior='.$projeto_expandido. ' OR pr.projeto_id='.$projeto_expandido);
	elseif (!$nao_apenas_superiores) $sql->adOnde('pr.projeto_superior IS NULL OR pr.projeto_superior=0 OR pr.projeto_superior=pr.projeto_id');
	if ($recebido && !$projeto_expandido && $lista_cias) {
		$sql->esqUnir('projeto_observado', 'projeto_observado', 'projeto_observado.projeto_id = pr.projeto_id');
		$sql->adOnde('projeto_observado.cia_para IN ('.$lista_cias.')');
		$sql->adOnde('projeto_observado.aprovado = 1');
		$sql->adOnde('projeto_observado.cia_de NOT IN ('.$lista_cias.')');
		}
	if ($projetostatus && !$projeto_expandido){

		if ($projetostatus == -1) $sql->adOnde('projeto_ativo = 1');
		elseif ($projetostatus == -2) $sql->adOnde('projeto_ativo=0 OR projeto_ativo IS NULL');
		elseif ($projetostatus > 0) $sql->adOnde('projeto_status IN ('.$projetostatus.')');


		}
	if($dept_id && !$projeto_expandido) $sql->adOnde('projeto_depts.departamento_id IN ('.$dept_id.') OR pr.projeto_dept IN ('.$dept_id.')');
	if ($cia_id && !$mostrarProjRespPertenceDept && !$recebido && !$lista_cias && !$projeto_expandido && !$favorito_id)	$sql->adOnde('pr.projeto_cia = '.(int)$cia_id.' OR projeto_cia_cia='.(int)$cia_id.($template ? ' OR pr.projeto_cia IS NULL' : ''));
	elseif (!$mostrarProjRespPertenceDept && !$recebido && $lista_cias && !$projeto_expandido && !$favorito_id) $sql->adOnde('pr.projeto_cia IN ('.$lista_cias.') OR projeto_cia_cia IN ('.$lista_cias.')');
	if ($projeto_tipo > -1 && !$projeto_expandido)	$sql->adOnde('pr.projeto_tipo IN ('.$projeto_tipo.')');
	if ($usuario_id && $adicionarProjComTarefasDesignadas && !$projeto_expandido && !$filtro_opcao) $sql->adOnde('(tu.usuario_id IN ('.$usuario_id.') OR pr.projeto_responsavel IN ('.$usuario_id.'))');
	elseif ($usuario_id && !$projeto_expandido) $sql->adOnde('pr.projeto_responsavel IN ('.$usuario_id.')');
	if (trim($pesquisar_texto) && !$projeto_expandido) $sql->adOnde('pr.projeto_nome LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_descricao LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_objetivos LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_como LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_codigo LIKE \'%'.$pesquisar_texto.'%\'');
	//if ($mostrarProjRespPertenceDept && !empty($responsavel_ids) && !$projeto_expandido) $sql->adOnde('pr.projeto_responsavel IN ('.implode(',', $responsavel_ids).')');
	$sql->adCampo('pr.projeto_id as projeto_id');

	if ($ordenarPor && $ordemDir) $sql->adOrdem($ordenarPor.' ' .$ordemDir);
	$projetos = $sql->ListaChaveSimples('projeto_id');
	$sql->limpar();

	return $projetos;
	}


function demandas_quantidade($a=null, $tab=0, $envolvimento=null, $cia_id=null, $lista_cias=null, $dept_id=null, $lista_depts=null, $usuario_id=null, $supervisor=null, $autoridade=null, $cliente=null, $demanda_setor=null, $demanda_segmento=null, $demanda_intervencao=null, $demanda_tipo_intervencao=null, $pesquisar_texto=null, $filtro_prioridade_demanda=null, $favorito_id=null,
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
	$pg_id=null,
	$ssti_id=null,
	$laudo_id=null,
	$trelo_id=null,
	$trelo_cartao_id=null,
	$pdcl_id=null,
	$pdcl_item_id=null){

	global $Aplic;
	$sql = new BDConsulta;
	$sql->adTabela('demandas');
	$sql->adCampo('count(DISTINCT demandas.demanda_id)');
	
	if ($filtro_prioridade_demanda){
		$sql->esqUnir('priorizacao', 'priorizacao', 'demandas.demanda_id=priorizacao_demanda');
		$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_demanda.')');
		}

	if ($favorito_id){
		$sql->internoUnir('favorito_lista', 'favorito_lista', 'demandas.demanda_id=favorito_lista_campo');
		$sql->internoUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
		$sql->adOnde('favorito.favorito_id='.(int)$favorito_id);
		}
	elseif ($dept_id && !$lista_depts) {
		$sql->esqUnir('demanda_depts','demanda_depts', 'demanda_depts.demanda_id=demandas.demanda_id');
		$sql->adOnde('demanda_dept='.(int)$dept_id.' OR demanda_depts.dept_id='.(int)$dept_id);
		}
	elseif ($lista_depts) {
		$sql->esqUnir('demanda_depts','demanda_depts', 'demanda_depts.demanda_id=demandas.demanda_id');
		$sql->adOnde('demanda_dept IN ('.$lista_depts.') OR demanda_depts.dept_id IN ('.$lista_depts.')');
		}	
	elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('demanda_cia', 'demanda_cia', 'demandas.demanda_id=demanda_cia_demanda');
		$sql->adOnde('demanda_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR demanda_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}
	elseif ($cia_id && !$lista_cias) $sql->adOnde('demanda_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('demanda_cia IN ('.$lista_cias.')');
	
	if ($demanda_setor) $sql->adOnde('demanda_setor IN ('.$demanda_setor.')');
	if ($demanda_segmento) $sql->adOnde('demanda_segmento IN ('.$demanda_segmento.')');
	if ($demanda_intervencao) $sql->adOnde('demanda_intervencao IN ('.$demanda_intervencao.')');
	if ($demanda_tipo_intervencao) $sql->adOnde('demanda_tipo_intervencao IN ('.$demanda_tipo_intervencao.')');
	if ($supervisor) $sql->adOnde('demanda_supervisor IN ('.$supervisor.')');
	if ($autoridade) $sql->adOnde('demanda_autoridade IN ('.$autoridade.')');
	if ($cliente) $sql->adOnde('demanda_cliente IN ('.$cliente.')');
	if($usuario_id) $sql->esqUnir('demanda_usuarios', 'demanda_usuarios', 'demanda_usuarios.demanda_id = demandas.demanda_id');
	if ($usuario_id) $sql->adOnde('(demanda_usuarios.usuario_id IN ('.$usuario_id.') OR demanda_usuario IN ('.$usuario_id.'))');
	if (trim($pesquisar_texto)) $sql->adOnde('demanda_nome LIKE \'%'.$pesquisar_texto.'%\' OR demanda_identificacao LIKE \'%'.$pesquisar_texto.'%\' OR demanda_justificativa LIKE \'%'.$pesquisar_texto.'%\' OR demanda_observacao LIKE \'%'.$pesquisar_texto.'%\' OR demanda_resultados LIKE \'%'.$pesquisar_texto.'%\' OR demanda_alinhamento LIKE \'%'.$pesquisar_texto.'%\'');


	$sql->esqUnir('demanda_gestao','demanda_gestao','demanda_gestao_demanda = demandas.demanda_id');
	if ($tarefa_id) $sql->adOnde('demanda_gestao_tarefa IN ('.$tarefa_id.')');
	elseif ($projeto_id){
		$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=demanda_gestao_tarefa');
		$sql->adOnde('demanda_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
		}
	elseif ($pg_perspectiva_id) $sql->adOnde('demanda_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('demanda_gestao_tema IN ('.$tema_id.')');
	elseif ($objetivo_id) $sql->adOnde('demanda_gestao_objetivo IN ('.$objetivo_id.')');
	elseif ($fator_id) $sql->adOnde('demanda_gestao_fator IN ('.$fator_id.')');
	elseif ($pg_estrategia_id) $sql->adOnde('demanda_gestao_estrategia IN ('.$pg_estrategia_id.')');
	elseif ($pg_meta_id) $sql->adOnde('demanda_gestao_meta IN ('.$pg_meta_id.')');
	elseif ($pratica_id) $sql->adOnde('demanda_gestao_pratica IN ('.$pratica_id.')');
	elseif ($pratica_indicador_id) $sql->adOnde('demanda_gestao_indicador IN ('.$pratica_indicador_id.')');
	elseif ($plano_acao_id) $sql->adOnde('demanda_gestao_acao IN ('.$plano_acao_id.')');
	elseif ($canvas_id) $sql->adOnde('demanda_gestao_canvas IN ('.$canvas_id.')');
	elseif ($risco_id) $sql->adOnde('demanda_gestao_risco IN ('.$risco_id.')');
	elseif ($risco_resposta_id) $sql->adOnde('demanda_gestao_risco_resposta IN ('.$risco_resposta_id.')');
	elseif ($calendario_id) $sql->adOnde('demanda_gestao_calendario IN ('.$calendario_id.')');
	elseif ($monitoramento_id) $sql->adOnde('demanda_gestao_monitoramento IN ('.$monitoramento_id.')');
	elseif ($ata_id) $sql->adOnde('demanda_gestao_ata IN ('.$ata_id.')');
	elseif ($mswot_id) $sql->adOnde('demanda_gestao_mswot IN ('.$mswot_id.')');
	elseif ($swot_id) $sql->adOnde('demanda_gestao_swot IN ('.$swot_id.')');
	elseif ($operativo_id) $sql->adOnde('demanda_gestao_operativo IN ('.$operativo_id.')');
	elseif ($instrumento_id) $sql->adOnde('demanda_gestao_instrumento IN ('.$instrumento_id.')');
	elseif ($recurso_id) $sql->adOnde('demanda_gestao_recurso IN ('.$recurso_id.')');
	elseif ($problema_id) $sql->adOnde('demanda_gestao_problema IN ('.$problema_id.')');
	elseif ($demanda_id) $sql->adOnde('demanda_gestao_semelhante IN ('.$demanda_id.')');
	elseif ($programa_id) $sql->adOnde('demanda_gestao_programa IN ('.$programa_id.')');
	elseif ($licao_id) $sql->adOnde('demanda_gestao_licao IN ('.$licao_id.')');
	elseif ($evento_id) $sql->adOnde('demanda_gestao_evento IN ('.$evento_id.')');
	elseif ($link_id) $sql->adOnde('demanda_gestao_link IN ('.$link_id.')');
	elseif ($avaliacao_id) $sql->adOnde('demanda_gestao_avaliacao IN ('.$avaliacao_id.')');
	elseif ($tgn_id) $sql->adOnde('demanda_gestao_tgn IN ('.$tgn_id.')');
	elseif ($brainstorm_id) $sql->adOnde('demanda_gestao_brainstorm IN ('.$brainstorm_id.')');
	elseif ($gut_id) $sql->adOnde('demanda_gestao_gut IN ('.$gut_id.')');
	elseif ($causa_efeito_id) $sql->adOnde('demanda_gestao_causa_efeito IN ('.$causa_efeito_id.')');
	elseif ($arquivo_id) $sql->adOnde('demanda_gestao_arquivo IN ('.$arquivo_id.')');
	elseif ($forum_id) $sql->adOnde('demanda_gestao_forum IN ('.$forum_id.')');
	elseif ($checklist_id) $sql->adOnde('demanda_gestao_checklist IN ('.$checklist_id.')');
	elseif ($agenda_id) $sql->adOnde('demanda_gestao_agenda IN ('.$agenda_id.')');
	elseif ($agrupamento_id) $sql->adOnde('demanda_gestao_agrupamento IN ('.$agrupamento_id.')');
	elseif ($patrocinador_id) $sql->adOnde('demanda_gestao_patrocinador IN ('.$patrocinador_id.')');
	elseif ($template_id) $sql->adOnde('demanda_gestao_template IN ('.$template_id.')');
	elseif ($painel_id) $sql->adOnde('demanda_gestao_painel IN ('.$painel_id.')');
	elseif ($painel_odometro_id) $sql->adOnde('demanda_gestao_painel_odometro IN ('.$painel_odometro_id.')');
	elseif ($painel_composicao_id) $sql->adOnde('demanda_gestao_painel_composicao IN ('.$painel_composicao_id.')');
	elseif ($tr_id) $sql->adOnde('demanda_gestao_tr IN ('.$tr_id.')');
	elseif ($me_id) $sql->adOnde('demanda_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('demanda_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('demanda_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('demanda_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('demanda_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('demanda_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('demanda_gestao_plano_gestao IN ('.$pg_id.')');
	elseif ($ssti_id) $sql->adOnde('demanda_gestao_ssti IN ('.$ssti_id.')');
	elseif ($laudo_id) $sql->adOnde('demanda_gestao_laudo IN ('.$laudo_id.')');
	elseif ($trelo_id) $sql->adOnde('demanda_gestao_trelo IN ('.$trelo_id.')');
	elseif ($trelo_cartao_id) $sql->adOnde('demanda_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
	elseif ($pdcl_id) $sql->adOnde('demanda_gestao_pdcl IN ('.$pdcl_id.')');
	elseif ($pdcl_item_id) $sql->adOnde('demanda_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
	
	if ($tab==0 && $a=='demanda_lista') $sql->adOnde('demanda_caracteristica_projeto IS NULL OR demanda_caracteristica_projeto=0');
	elseif ($tab==1 && $a=='demanda_lista') $sql->adOnde('demanda_caracteristica_projeto=1');
	elseif ($tab==2 && $a=='demanda_lista') $sql->adOnde('demanda_caracteristica_projeto=-1');
	if ($tab< 3 && $a=='demanda_lista') $sql->adOnde('demanda_projeto IS NULL OR demanda_projeto=0');
	elseif ($tab==3 && $a=='demanda_lista') $sql->adOnde('demanda_projeto IS NOT NULL AND demanda_projeto!=0');
	if ($tab<5 || $a!='demanda_lista') $sql->adOnde('demanda_ativa=1');
	else $sql->adOnde('demanda_ativa=0');

	$qnt=$sql->Resultado();
	$sql->limpar();
	return $qnt;
	}


function getProjetos() {
	global $Aplic;
	$st_projetos = array(0 => '');
	$sql = new BDConsulta();
	$sql->adTabela('projetos');
	$sql->adCampo('projeto_id, projeto_nome, projeto_superior');
	$sql->adOrdem('projeto_nome');
	$st_projetos = $sql->ListaChave('projeto_id');
	reset_projeto_superiors($st_projetos);
	return $st_projetos;
	}

function reset_projeto_superiors(&$projetos) {
	foreach ($projetos as $chave => $projeto) if ($projeto['projeto_id'] == $projeto['projeto_superior'])	$projetos[$chave][2] = '';
	}

function mostrar_st_projeto(&$a, $nivel = 0) {
	global $st_projetos_arr;
	$st_projetos_arr[] = array($a, $nivel);
	}


function achar_proj_subordinado(&$tarr, $superior, $nivel = 0) {
	$nivel = $nivel + 1;
	$n = count($tarr);
	for ($x = 0; $x < $n; $x++) {
		if ($tarr[$x]['projeto_superior'] == $superior && $tarr[$x]['projeto_superior'] != $tarr[$x]['projeto_id']) {
			mostrar_st_projeto($tarr[$x], $nivel);
			achar_proj_subordinado($tarr, $tarr[$x]['projeto_id'], $nivel);
			}
		}
	}

function getProjetosEstruturados($original_projeto_id = 0, $projeto_status = -1, $apenasAtivo = false) {
	global $Aplic, $st_projetos_arr;
	$st_projetos = array(0 => '');
	$sql = new BDConsulta();
	$sql->adTabela('projetos');
	$sql->esqUnir('cias', '', 'projetos.projeto_cia = cia_id');
	$sql->esqUnir('projeto_depts', 'pd', 'pd.projeto_id = projetos.projeto_id');
	$sql->esqUnir('depts', 'dep', 'pd.departamento_id = dep.dept_id');
	$sql->adCampo('projetos.projeto_id, projeto_nome, projeto_superior');
	if ($original_projeto_id) $sql->adOnde('projeto_superior_original = '.(int)$original_projeto_id);
	if ($projeto_status >= 0) $sql->adOnde('projeto_status = '.(int)$projeto_status);
	if ($apenasAtivo) $sql->adOnde('projeto_ativo=1');
	$sql->adOrdem('projeto_nome');
	$sql->adGrupo('projeto_id');
	$st_projetos = $sql->Lista();
	$tnums = count($st_projetos);
	for ($i = 0; $i < $tnums; $i++) {
		$st_projeto = $st_projetos[$i];
		if (($st_projeto['projeto_superior'] == $st_projeto['projeto_id'])) {
			mostrar_st_projeto($st_projeto);
			achar_proj_subordinado($st_projetos, $st_projeto['projeto_id']);
			}
		}
	}

function getIndiceProjeto($vetorLista, $projeto_id) {
	$resultado = false;
	foreach ($vetorLista as $chave => $data) if ($data['projeto_id'] == $projeto_id) return $chave;
	return $resultado;
	}

?>