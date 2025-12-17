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

/********************************************************************************************

gpweb\classes\BDConsulta.class.php

Classe BDConsulta processa as consultas SQL realizdas na base de dados

********************************************************************************************/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

require_once BASE_DIR.'/lib/adodb/adodb.inc.php';
define('QUERY_STYLE_ASSOC', ADODB_FETCH_ASSOC);
define('QUERY_STYLE_NUM', ADODB_FETCH_NUM);
define('QUERY_STYLE_BOTH', ADODB_FETCH_BOTH);

class BDConsulta {
	public $comando_sql;
	public $lista_tabelas;
	public $onde;
	public $ordem_por;
	public $agrupar_por;
	public $tendo;
	public $limite;
	public $compensar;
	public $unir;
	public $tipo;
	public $atualizar_lista;
	public $valor_lista;
	public $criar_tabela;
	public $criar_definicao;
	public $_prefixo_tabela;
	public $_consulta_id = null;
	public $_estilo_antigo = null;
	public $_bd = null;
	public $chave_estrangeira = false;

	public function __construct($prefixo = null, $query_bd = null) {
		global $bd;

		if (isset($prefixo)) $this->_prefixo_tabela = $prefixo;
		else $this->_prefixo_tabela = config('prefixoBd', '');
		$this->_bd = isset($query_bd) ? $query_bd : $bd;
		$this->limpar();
		}

	public function limpar() {
		global $ADODB_FETCH_MODE;
		if (isset($this->_estilo_antigo)) {
			$ADODB_FETCH_MODE = $this->_estilo_antigo;
			$this->_estilo_antigo = null;
			}
		$this->tipo = 'selecionar';
		$this->query = null;
		$this->tabela_lista = null;
		$this->onde = null;
		$this->ordem = null;
		$this->agrupar_por = null;
		$this->limite = null;
		$this->offset = -1;
		$this->unir = null;
		$this->lista_valores = null;
		$this->atualizar_lista = null;
		$this->criar_tabela = null;
		$this->criar_definicao = null;
		if ($this->_consulta_id) $this->_consulta_id->Close();
		$this->_consulta_id = null;
		}

	public function limparConsulta() {
		if ($this->_consulta_id) $this->_consulta_id->Close();
		$this->_consulta_id = null;
		}

	public function comando_sql( $execucao=false) {
		if ($execucao) return $this->prepare();
		else return $this->prepareSelecionar();
		}

	public function sem_chave_estrangeira() {
		$this->chave_estrangeira=true;
		}

	public function adMapa( $varnome, $nome, $id) {
		if (!isset($this->$varnome)) $this->$varnome = array();
		if (isset($id)) $this->{$varnome}[$id] = $nome;
		else $this->{$varnome}[] = $nome;
		}

	public function adTabela( $nome, $id = null) {
		$this->adMapa('tabela_lista', $nome, ($id ? $id : $nome));
		}

	public function adClausula( $clausula, $valor, $checar_vetor = true) {
		if (!isset($this->$clausula)) $this->$clausula = array();
		if ($checar_vetor && is_array($valor)) {
			foreach ($valor as $v) array_push($this->$clausula, $v);
			}
		else array_push($this->$clausula, $valor);
		}

	public function adCampo( $comando_sql) {
		$this->adClausula('query', $comando_sql);
		}

	public function adInserir( $campo, $valor = null, $set = false, $func = false) {
		if (is_array($campo) && $valor == null) {
			foreach ($campo as $f => $v) $this->adMapa('lista_valores', $f, $v);
			}
		elseif ($set) {
			if (is_array($campo))	$campos = $campo;
			else $campos = explode(',', $campo);
			if (is_array($valor))	$valores = $valor;
			else $valores = explode(',', $valor);
			for ($i = 0, $i_cmp = count($campos); $i < $i_cmp; $i++) $this->adMapa('lista_valores', $this->quote($valores[$i]), $campos[$i]);
			}
		elseif (!$func) $this->adMapa('lista_valores', $this->quote($valor), $campo);
		else $this->adMapa('lista_valores', $valor, $campo);
		$this->tipo = 'inserir';
		}

	public function adInserirSelecionado( $tabela) {
		$this->criar_tabela = $tabela;
		$this->tipo = 'inserir_selecionado';
		}

	public function adSubstituir( $campo, $valor, $set = false, $func = false) {
		$this->adInserir($campo, $valor, $set, $func);
		$this->tipo = 'substituir';
		}

	public function adAtualizar( $campo, $valor = null, $set = false) {
		if (is_array($campo) && $valor == null) {
			foreach ($campo as $f => $v) $this->adMapa('atualizar_lista', $f, $v);
			}
		elseif ($set) {
			if (is_array($campo)) $campos = $campo;
			else $campos = explode(',', $campo);
			if (is_array($valor))	$valores = $valor;
			else $valores = explode(',', $valor);
			for ($i = 0, $i_cmp = count($campos); $i < $i_cmp; $i++) $this->adMapa('atualizar_lista', $valores[$i], $campos[$i]);
			}
		else $this->adMapa('atualizar_lista', $valor, $campo);
		$this->tipo = 'atualizar';
		}


	public function criaDefinicao( $def) {
		$this->criar_definicao = $def;
		}


	public function executarScript( $def = null) {
		$this->tipo = 'executarScript';
		if ($def)	$this->criar_definicao = $def;
		}

	public function criarTabela( $tabela, $def = null) {
		$this->tipo = 'criarPermanente';
		$this->criar_tabela = $tabela;
		if ($def)	$this->criar_definicao = $def;
		}


	public function excluirTabela( $tabela) {
		$this->tipo = 'excluirTabela';
		$this->criar_tabela = $tabela;
		}


	public function excluirCampo( $nome) {
		if (!is_array($this->criar_definicao)) $this->criar_definicao = array();
		$this->criar_definicao[] = array('acao' => 'DROP', 'tipo' => '', 'spec' => $nome);
		}



	public function setExcluir( $tabela) {
		$this->tipo = 'excluir';
		$this->adMapa('tabela_lista', $tabela, null);
		}

	public function adOnde( $comando_sql) {
		if (isset($comando_sql)) $this->adClausula('onde', $comando_sql);
		}

	public function adUnir( $tabela, $apelido, $unir, $tipo = 'left') {
		$var = array('tabela' => $tabela, 'apelido' => $apelido, 'condicao' => $unir, 'tipo' => $tipo);
		$this->adClausula('unir', $var, false);
		}

	public function esqUnir( $tabela, $apelido, $unir) {
		$this->adUnir($tabela, $apelido, $unir, 'left');
		}

	public function dirUnir( $tabela, $apelido, $unir) {
		$this->adUnir($tabela, $apelido, $unir, 'right');
		}

	public function internoUnir( $tabela, $apelido, $unir) {
		$this->adUnir($tabela, $apelido, $unir, 'inner');
		}

	public function adOrdem( $ordem) {
		if (isset($ordem)) $this->adClausula('ordem', $ordem);
		}

	public function adGrupo( $grupo) {
		$this->adClausula('agrupar_por', $grupo);
		}

	public function setLimite( $limite, $inicio = -1) {
		$this->limite = $limite;
		$this->offset = $inicio;
		}

	public function prepare( $limpar = false) {
		global $config;
		switch ($this->tipo) {
			case 'selecionar':
				$q = $this->prepareSelecionar();
				break;
			case 'atualizar':
				$q = $this->prepareAtualizar();
				if ($config['registrar_mudancas']){
					$tabela=array_shift($this->tabela_lista);					
					if ($tabela!='registro' && $tabela!='sessoes') inserir_historico($q, $this->tipo);
					}
				break;
			case 'inserir':
				$q = $this->prepareInserir();
				if ($config['registrar_mudancas']){
					$tabela=array_shift($this->tabela_lista);					
					if ($tabela!='registro' && $tabela!='sessoes') inserir_historico($q, $this->tipo);
					}
				break;
			case 'inserir_selecionado':
				$s = $this->prepareSelecionar();
				$q = 'INSERT INTO '.$this->_prefixo_tabela.$this->criar_tabela;
				$q .= ' '.$s;
				if ($config['registrar_mudancas']){
					$tabela=array_shift($this->tabela_lista);					
					if ($tabela!='registro' && $tabela!='sessoes') inserir_historico($q, $this->tipo);
					}
				break;
			case 'substituir':
				$q = $this->prepareSubstituir();
				break;
			case 'excluir':
				$q = $this->prepareExcluir();
				if ($config['registrar_mudancas']){
					$tabela=array_shift($this->tabela_lista);					
					if ($tabela!='registro' && $tabela!='sessoes') inserir_historico($q, $this->tipo);
					}
				break;
			case 'criar':
				$s = $this->prepareSelecionar();
				$q = 'CREATE TEMPORARY table '.$this->_prefixo_tabela.$this->criar_tabela;
				if (!empty($this->criar_definicao))	$q .= ' '.$this->criar_definicao;
				$q .= ' '.$s;
				break;
			case 'alterar':
				$q = $this->prepareAlterar();
				if ($config['registrar_mudancas']){
					$tabela=array_shift($this->tabela_lista);					
					if ($tabela!='registro' && $tabela!='sessoes') inserir_historico($q, $this->tipo);
					}
				break;
			case 'criarPermanente':
				$q = 'CREATE table '.$this->_prefixo_tabela.$this->criar_tabela;
				if (!empty($this->criar_definicao)) $q .= ' '.$this->criar_definicao;
				break;
			case 'excluirTabela':
				$q = 'DROP table IF EXISTS '.$this->_prefixo_tabela.$this->criar_tabela;
				break;

			case 'executarScript':
				$q=$this->criar_definicao;
				if ($config['registrar_mudancas'] && $this->tabela_lista){
					$tabela=array_shift($this->tabela_lista);					
					if ($tabela!='registro' && $tabela!='sessoes') inserir_historico($q, $this->tipo);
					}
				break;
			}
		if ($limpar) $this->limpar();
		return $q;
		dprint(__file__, __line__, 2, $q);
		}

	public function prepareSelecionar() {
		switch (strtolower(trim(config('tipoBd')))){
			case 'oci8':
			case 'oracle':
			case 'postgres':	
				$q = 'SELECT ';	
				if (isset($this->query)) {
					if (is_array($this->query)) {
						$dentroSelecao = false;
						$q .= implode(',', $this->query);
						}
					else $q .= $this->query;
					}
				else $q .= '*';
				$q .= ' FROM ';
				if (isset($this->tabela_lista)) {
					if (is_array($this->tabela_lista)) {
						$dentroTabela = false;
						foreach ($this->tabela_lista as $tabela_id => $tabela) {
							if ($dentroTabela) $q .= ',';
							else $dentroTabela = true;
							$q .= $this->_prefixo_tabela.$tabela;
							if (!is_numeric($tabela_id)) $q .= ' '.$tabela_id;
							}
						}
					else $q .= $this->_prefixo_tabela.$this->tabela_lista;
					}
				else return false;
				$q .= $this->fazer_uniao($this->unir);
				$q .= $this->fazer_clausula_onde($this->onde);
				$q .= $this->fazer_clausula_agrupar($this->agrupar_por);
				$q .= $this->fazer_clausula_tendo($this->tendo);
				$q .= $this->fazer_clausula_ordem($this->ordem);
				$q .= $this->fazer_clausula_limite($this->limite, $this->offset);
				return $q;
				break;
			
			default:
			//mySQL
				$q = 'SELECT ';	
				if (isset($this->query)) {
					if (is_array($this->query)) {
						$dentroSelecao = false;
						$q .= implode(',', $this->query);
						}
					else $q .= $this->query;
					}
				else $q .= '*';
				$q .= ' FROM ';
				if (isset($this->tabela_lista)) {
					if (is_array($this->tabela_lista)) {
						$dentroTabela = false;
						$q .= '(';
						foreach ($this->tabela_lista as $tabela_id => $tabela) {
							if ($dentroTabela)	$q .= ',';
							else $dentroTabela = true;
							$q .= $this->_prefixo_tabela.$tabela;
							if (!is_numeric($tabela_id)) $q .= ' AS '.$tabela_id;
							}
						$q .= ')';
						}
					else $q .= $this->_prefixo_tabela.$this->tabela_lista;
					}
				else return false;
				$q .= $this->fazer_uniao($this->unir);
				$q .= $this->fazer_clausula_onde($this->onde);
				$q .= $this->fazer_clausula_agrupar($this->agrupar_por);
				$q .= $this->fazer_clausula_tendo($this->tendo);
				$q .= $this->fazer_clausula_ordem($this->ordem);
				$q .= $this->fazer_clausula_limite($this->limite, $this->offset);
				return $q;
			}
		}

	public function prepareAtualizar() {
		switch (strtolower(trim(config('tipoBd')))) {
			case 'oci8':
			case 'oracle':
			case 'postgres':
				$q = 'UPDATE ';
				if (isset($this->tabela_lista)) {
					if (is_array($this->tabela_lista)) {
						reset($this->tabela_lista);
						//Evandro olhar
						//list($chave, $tabela) = each($this->tabela_lista);
						foreach($this->tabela_lista as $chave1 => $tabela1) {
							$chave=$chave1; 
							$tabela=$tabela1;
							}
						}
					else $tabela = $this->tabela_lista;
					}
				else return false;
				$q .= $this->_prefixo_tabela.$tabela;
				$q .= ' SET ';
				$sets = '';
				foreach ($this->atualizar_lista as $campo => $valor) {
					if ($sets) $sets .= ', ';
					if( $valor === NULL ) $sets .= $campo.' = NULL';
          else $sets .= $campo.' = '.$this->quote($valor);
					}
				$q .= $sets;
				$q .= $this->fazer_clausula_onde($this->onde);
				return $q;
				break;
			
			default:
			//mySQL
				$q = 'UPDATE ';
				if (isset($this->tabela_lista)) {
					if (is_array($this->tabela_lista)) {
						reset($this->tabela_lista);
						//Evandro olhar
						//list($chave, $tabela) = each($this->tabela_lista);
						foreach($this->tabela_lista as $chave1 => $tabela1) {
							$chave=$chave1; 
							$tabela=$tabela1;
							}
						}
					else $tabela = $this->tabela_lista;
					}
				else return false;
				$q .= $this->quote_bd($this->_prefixo_tabela.$tabela);
				$q .= $this->fazer_uniao($this->unir);
				$q .= ' SET ';
				$sets = '';
				foreach ($this->atualizar_lista as $campo => $valor) {
					if ($sets) $sets .= ', ';
					$sets .= $this->quote_bd($campo).' = '.$this->quote($valor);
					}
				$q .= $sets;
				$q .= $this->fazer_clausula_onde($this->onde);
				return $q;
			}
		}

	public function prepareInserir() {
		switch (strtolower(trim(config('tipoBd')))) {
			case 'oci8':
			case 'oracle':
			case 'postgres':
			    //POSTGRESQL

				$q = 'INSERT INTO ';
				if (isset($this->tabela_lista)) {
					if (is_array($this->tabela_lista)) {
						reset($this->tabela_lista);
						//Evandro olhar
						//list($chave, $tabela) = each($this->tabela_lista);
						foreach($this->tabela_lista as $chave1 => $tabela1) {
							$chave=$chave1; 
							$tabela=$tabela1;
							}
						}
					else $tabela = $this->tabela_lista;
					}
				else return false;
				$q .= $this->_prefixo_tabela.$tabela;
				$campolista = '';
				$valorlista = '';
				foreach ($this->lista_valores as $campo => $valor) {
					if ($campolista) $campolista .= ', ';
					if ($valorlista) $valorlista .= ', ';
          $campolista .= trim($campo);
					$valorlista .= $valor;
					}
				$q .= ' ('.$campolista.') VALUES ('.$valorlista.')';
				return $q;
				break;
				
			default:
			//mySQL
				$q = 'INSERT INTO ';
				if (isset($this->tabela_lista)) {
					if (is_array($this->tabela_lista)) {
						reset($this->tabela_lista);
						//Evandro olhar
						//list($chave, $tabela) = each($this->tabela_lista);
						foreach($this->tabela_lista as $chave1 => $tabela1) {
							$chave=$chave1; 
							$tabela=$tabela1;
							}
						}
					else $tabela = $this->tabela_lista;
					}
				else return false;
				$q .= $this->quote_bd($this->_prefixo_tabela.$tabela);
				$campolista = '';
				$valorlista = '';
				foreach ($this->lista_valores as $campo => $valor) {
					if ($campolista) $campolista .= ', ';
					if ($valorlista) $valorlista .= ', ';
					$campolista .= $this->quote_bd(trim($campo));
					$valorlista .= $valor;
					}
				$q .= ' ('.$campolista.') VALUES ('.$valorlista.')';
				return $q;
			}
		}

	public function prepareInserirSelecionado() {
		$q = 'INSERT INTO ';
		if (isset($this->tabela_lista)) {
			if (is_array($this->tabela_lista)) {
				reset($this->tabela_lista);
				//Evandro olhar
				//list($chave, $tabela) = each($this->tabela_lista);
				foreach($this->tabela_lista as $chave1 => $tabela1) {
					$chave=$chave1; 
					$tabela=$tabela1;
					}
				}
			else $tabela = $this->tabela_lista;
			}
		else return false;
		$q .= $this->quote_bd($this->_prefixo_tabela.$tabela);
		$campolista = '';
		$valorlista = '';
		foreach ($this->lista_valores as $campo => $valor) {
			if ($campolista) $campolista .= ',';
			if ($valorlista) $valorlista .= ',';
			$campolista .= $this->quote_bd(trim($campo));
			$valorlista .= $valor;
			}
		$q .= '('.$campolista.') values ('.$valorlista.')';
		return $q;
		}

	public function prepareSubstituir() {
		switch (strtolower(trim(config('tipoBd')))) {
			case 'oci8':
			case 'oracle':
			case 'postgres':	
				$q = 'REPLACE INTO ';
				if (isset($this->tabela_lista)) {
					if (is_array($this->tabela_lista)) {
						reset($this->tabela_lista);
						//Evandro olhar
						//list($chave, $tabela) = each($this->tabela_lista);
						foreach($this->tabela_lista as $chave1 => $tabela1) {
							$chave=$chave1; 
							$tabela=$tabela1;
							}
						}
					else $tabela = $this->tabela_lista;
					}
				else return false;
				$q .= $this->_prefixo_tabela.$tabela;
				$campolista = '';
				$valorlista = '';
				foreach ($this->lista_valores as $campo => $valor) {
					if ($campolista) $campolista .= ',';
					if ($valorlista) $valorlista .= ',';
					$campolista .= trim($campo);
					$valorlista .= $valor;
					}
				$q .= '('.$campolista.') values ('.$valorlista.')';
				return $q;
				break;
			
			default:
			//mySQL
				$q = 'REPLACE INTO ';
				if (isset($this->tabela_lista)) {
					if (is_array($this->tabela_lista)) {
						reset($this->tabela_lista);
						//Evandro olhar
						//list($chave, $tabela) = each($this->tabela_lista);
						foreach($this->tabela_lista as $chave1 => $tabela1) {
							$chave=$chave1; 
							$tabela=$tabela1;
							}
						}
					else $tabela = $this->tabela_lista;
					}
				else return false;
				$q .= $this->quote_bd($this->_prefixo_tabela.$tabela);
				$campolista = '';
				$valorlista = '';
				foreach ($this->lista_valores as $campo => $valor) {
					if ($campolista)	$campolista .= ',';
					if ($valorlista) $valorlista .= ',';
					$campolista .= $this->quote_bd(trim($campo));
					$valorlista .= $valor;
					}
				$q .= '('.$campolista.') values ('.$valorlista.')';
				return $q;
			}
		}

	public function prepareExcluir() {
		switch (strtolower(trim(config('tipoBd')))) {
			
			case 'oci8':
			
			case 'oracle':
			
			case 'postgres':
				$q = 'DELETE FROM ';
				if (isset($this->tabela_lista)) {
					//Evandro olhar
					//if (is_array($this->tabela_lista)) list($chave, $tabela) = each($this->tabela_lista);			
					if (is_array($this->tabela_lista)) foreach($this->tabela_lista as $chave1 => $tabela1) {
						$chave=$chave1; 
						$tabela=$tabela1;
						}
					else $tabela = $this->tabela_lista;
					}
				else return false;
				$q .= $this->_prefixo_tabela.$tabela;
				$q .= $this->fazer_clausula_onde($this->onde);
				return $q;
				break;
			
			default:
			//mySQL
				$q = 'DELETE FROM ';
				if (isset($this->tabela_lista)) {
					//Evandro olhar
					//if (is_array($this->tabela_lista)) list($chave, $tabela) = each($this->tabela_lista);
					if (is_array($this->tabela_lista)) foreach($this->tabela_lista as $chave1 => $tabela1) {
						$chave=$chave1; 
						$tabela=$tabela1;
						}
					else $tabela = $this->tabela_lista;
					}
				else return false;
				$q .= $this->quote_bd($this->_prefixo_tabela.$tabela);
				$q .= $this->fazer_clausula_onde($this->onde);
				return $q;
			}
		}

	public function prepareAlterar() {
		$q = 'ALTER table '.$this->quote_bd($this->_prefixo_tabela.$this->criar_tabela).' ';
		if (isset($this->criar_definicao)) {
			if (is_array($this->criar_definicao)) {
				$primeiro = true;
				foreach ($this->criar_definicao as $def) {
					if ($primeiro)	$primeiro = false;
					else $q .= ', ';
					$q .= $def['acao'].' '.$def['tipo'].' '.$def['spec'];
					}
				}
			else $q .= 'ADD '.$this->criar_definicao;
			}
		return $q;
		}

	public function &exec( $estilo = ADODB_FETCH_BOTH, $depurar = false, $gravar=false, $motrar_msg = true) {
		global $ADODB_FETCH_MODE, $performace_tempo_BD, $performace_consultas_BD;
		if (!isset($this->_estilo_antigo)) $this->_estilo_antigo = $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = $estilo;
		$this->limparConsulta();
		if ($q = $this->prepare()) {
			if ($depurar) {
				$qid = $this->_bd->Execute('EXPLAIN '.$q);
				if ($qid) {
					$res = array();
					while ($linha = $this->carregarLinha()) $res[] = $linha;
					dprint(__file__, __line__, 0, 'Verificação SQL: '.var_export($res, true));
					$qid->Close();
					}
				}
			if ($gravar) {
				$qid = $this->_bd->Execute('EXPLAIN '.$q);
				if ($qid) {
					$res = array();
					while ($linha = $this->carregarLinha()) $res[] = $linha;
					ver5(var_export($res, true));
					$qid->Close();
					}
				}		
			if ($this->chave_estrangeira) $this->_bd->Execute('SET FOREIGN_KEY_CHECKS=0;');
			$this->_consulta_id = $this->_bd->Execute($q);
			if (!$this->_consulta_id && $motrar_msg) {
				$erro = $this->_bd->ErrorMsg();
				dprint(__file__, __line__, 0, "SQL falhou($q)".' <BR>Erro era: <span style="color:red">'.$erro.'</span>');
				return $this->_consulta_id;
				}
			return $this->_consulta_id;
			}
		else {
			return $this->_consulta_id;
			}
		}

	public function carregarLinha() {
		if (!$this->_consulta_id) return false;
		return $this->_consulta_id->FetchRow();
		}

	public function Lista( $maxLinhas = null) {
		global $Aplic;

		if (!$this->exec(ADODB_FETCH_ASSOC)) {
			$Aplic->setMsg($this->_bd->ErrorMsg(), UI_MSG_ERRO);
			$this->limpar();
			return false;
			}
		$lista = array();
		$cnt = 0;
		while ($hash = $this->carregarLinha()) {
			$lista[] = $hash;
			if ($maxLinhas && $maxLinhas == $cnt++) break;
			}
		$this->limpar();
		return $lista;
		}

	public function ListaChave( $indice = null) {
		if (!$this->exec(ADODB_FETCH_ASSOC)) exit($this->_bd->ErrorMsg());
		$listaLinha = array();
		$chaves = null;
		while ($hash = $this->carregarLinha()) {
			if ($indice) {
				$listaLinha[$hash[$indice]] = $hash;
				$chave = 0;
				foreach ($hash as $campo) {
					$listaLinha[$hash[$indice]][$chave] = $campo;
					$chave++;
					}
				}
			else {
				if (!$chaves) $chaves = array_keys($hash);
				$listaLinha[$hash[$chaves[0]]] = $hash[$chaves[1]];
				}
			}
		$this->limpar();
		return $listaLinha;
		}


	public function ListaChaveSimples( $indice = null) {
		if (!$this->exec(ADODB_FETCH_ASSOC)) exit($this->_bd->ErrorMsg());
		$listaLinha = array();
		$chaves = null;
		while ($hash = $this->carregarLinha()) {
			if ($indice) {
				$listaLinha[$hash[$indice]] = $hash;
				$chave = 0;
				}
			else {
				$listaLinha[] = $hash;
				}
			}
		$this->limpar();
		return $listaLinha;
		}


	public function listaVetorChave( $chave, $valor='') {
		if (!$this->exec(ADODB_FETCH_ASSOC)) exit($this->_bd->ErrorMsg());
		$listaLinha = array();
		$chaves = null;
		while ($hash = $this->carregarLinha()) {
			if ($valor) $listaLinha[$hash[$chave]] = $hash[$valor];
			else $listaLinha[$hash[$chave]] = $hash;
			}
		$this->limpar();
		return $listaLinha;
		}

	public function Linha() {
		if (!$this->exec(ADODB_FETCH_ASSOC)) exit($this->_bd->ErrorMsg());
		$hash = $this->carregarLinha();
		$this->limpar();
		return $hash;
		}

	public function carregarListaVetor( $indice = 0) {
		if (!$this->exec(ADODB_FETCH_NUM)) exit($this->_bd->ErrorMsg());
		$listaLinha = array();
		$chaves = null;
		while ($hash = $this->carregarLinha()) $listaLinha[$hash[$indice]] = $hash;
		$this->limpar();
		return $listaLinha;
		}

	public function carregarColuna() {
		if (!$this->exec(ADODB_FETCH_NUM)) die($this->_bd->ErrorMsg());
		$resultado = array();
		while ($linha = $this->carregarLinha()) $resultado[] = $linha[0];
		$this->limpar();
		return $resultado;
		}

	public function carregarObjeto( &$objeto, $unirTudo = false, $tira = true) {
		if (!$this->exec(ADODB_FETCH_NUM)) die($this->_bd->ErrorMsg());
		if ($objeto != null) {
			$hash = $this->Linha();
			$this->limpar();
			if (!$hash) return false;
			$this->unirLinhaAoObjeto($hash, $objeto, null, $tira, $unirTudo);
			return true;
			}
		else {
			if ($objeto = $this->_consulta_id->FetchNextObject(false)) {
				$this->limpar();
				return true;
				}
			else {
				$objeto = null;
				return false;
				}
			}
		}

	public function unirLinhaAoObjeto( $hash, &$obj, $prefixo = null, $checarAspas = true, $unirTudo = false) {
		is_array($hash) or die('unirLinhaAoObjeto : hash esperado');
		is_object($obj) or die('unirLinhaAoObjeto : objeto esperado');
		if ($unirTudo) {
			foreach ($hash as $k => $v)	$obj->$k = decodificarHTML($hash[$k]);
			}
		else {
			if ($prefixo) {
				$plen = strlen($prefixo);
				foreach ($hash as $k => $v){
					$k1 = substr($k,$plen);
					if(property_exists($obj,$k1)) $obj->$k1 = decodificarHTML($hash[$k]);
					}
				}
			else {
				foreach ($hash as $k => $v){
					if(property_exists($obj,$k)) $obj->$k = decodificarHTML($hash[$k]);
					}
				}
			}
		}
		
	public function Resultado() {
		global $Aplic;
		$resultado = false;
		if (!$this->exec(ADODB_FETCH_NUM)) {
			$Aplic->setMsg($this->_bd->ErrorMsg(), UI_MSG_ERRO);
			}
		elseif ($data = $this->carregarLinha()) {
			$resultado = $data[0];
			}
		$this->limpar();
		return $resultado;
		}

	public function fazer_clausula_onde( $clausula_onde) {
		$resultado = '';
		if (!isset($clausula_onde)) return $resultado;
		if (is_array($clausula_onde)) {
			if (count($clausula_onde)) {
				$iniciado = false;
				$resultado = ' WHERE ('.implode(') AND (', $clausula_onde).')';
				}
			}
		elseif (strlen($clausula_onde) > 0) $resultado = ' WHERE '.$clausula_onde;
		return $resultado;
		}

	public function fazer_clausula_ordem( $clausula_ordem) {
		$resultado = '';
		if (!isset($clausula_ordem)) return $resultado;
		if (is_array($clausula_ordem)) {
			$iniciado = false;
			$resultado = ' ORDER BY '.implode(',', $clausula_ordem);
			}
		elseif (strlen($clausula_ordem) > 0) $resultado = ' ORDER BY '.$clausula_ordem;
		return $resultado;
		}

	public function fazer_clausula_agrupar( $clausula_agrupar) {
		$resultado = '';
		if (!isset($clausula_agrupar)) return $resultado;
		if (is_array($clausula_agrupar)) {
			$iniciado = false;
			$resultado = ' GROUP BY '.implode(',', $clausula_agrupar);
			}
		elseif (strlen($clausula_agrupar) > 0) {
			$resultado = ' GROUP BY '.$clausula_agrupar;
			}
		return $resultado;
		}

	public function fazer_uniao( $clausula_unir) {
		$resultado = '';
		if (!isset($clausula_unir)) return $resultado;
		if (is_array($clausula_unir)) {
			foreach ($clausula_unir as $unir) {
				$resultado .= ' '.strtoupper($unir['tipo']).' JOIN '.$this->quote_bd($this->_prefixo_tabela.$unir['tabela']);
				if ($unir['apelido']) $resultado .= ' AS '.$unir['apelido'];
				else $resultado .= ' AS '.$unir['tabela'];
				if (is_array($unir['condicao'])) $resultado .= ' USING ('.implode(',', $unir['condicao']).')';
				else $resultado .= ' ON '.$unir['condicao'];
				}
			}
		else $resultado .= ' LEFT JOIN '.$this->quote_bd($this->_prefixo_tabela.$clausula_unir);
		return $resultado;
		}

	public function fazer_clausula_tendo( $clausula_tendo) {
		$resultado = '';
		if (!isset($clausula_tendo)) return $resultado;
		if (is_array($clausula_tendo)) {
			if (count($clausula_tendo)) {
				$iniciado = false;
				$resultado = ' HAVING '.implode(' AND ', $clausula_tendo);
				}
			}
		elseif (strlen($clausula_tendo) > 0)	$resultado = ' HAVING '.$clausula_tendo;
		return $resultado;
		}

	public function fazer_clausula_limite( $limite, $compensar) {
		$resultado = '';
		if (!isset($limite)) return $resultado;
		if (is_array($limite) && (count($limite) == 2)) $resultado = ' LIMIT '.implode(' OFFSET ', $limite);
		elseif (isset($limite) && ($compensar <= 0)) $resultado = ' LIMIT '.intval($limite);
		elseif (isset($limite) && ($compensar > 0)) $resultado = ' LIMIT '.intval($compensar).' OFFSET '.intval($limite);
		
		return $resultado;
		}

	public function quoteParecido( $formato, $s){
		$s = str_replace(array('%','_'), array('\%','\_'), addslashes($s));
		return '\''. sprintf($formato, $s). '\'';
		}

	public function quote( $texto) {
		if (is_int($texto)) return $texto;
		else return $this->_bd->qstr($texto);
		}

	public function quote_bd( $texto) {
		return $this->_bd->nameQuote.$texto.$this->_bd->nameQuote;
		}

	public function inserirVetor( $tabela, &$hash) {
		$this->adTabela($tabela);
		foreach ($hash as $k => $v) {
			if (is_array($v) or is_object($v) or $v == null) continue;
			$campos[] = $k;
			$valores[$k] = $v;
			}
		foreach ($campos as $campo) $this->adInserir($campo, $valores[$campo]);
		if (!$this->exec()) return false;
		$id = db_insert_id();      //EUZ inserir
		return true;
		}

	public function atualizarVetor( $tabela, &$hash, $chaveNome) {
		$this->adTabela($tabela);
		foreach ($hash as $k => $v) {
			if (is_array($v) or is_object($v) or $k[0] == '_') 	continue;
			if ($k == $chaveNome) {
				$this->adOnde($chaveNome.' = \''.db_escape($v).'\'');
				continue;
				}
			$campos[] = $k;
			if ($v == '') $valores[$k] = 'NULL';
			else $valores[$k] = $v;
			}
		if (count($valores)) {
			foreach ($campos as $campo) $this->adAtualizar($campo, $valores[$campo]);
			$ret = $this->exec();
			}
		return $ret;
		}

	public function inserirObjeto( $tabela, &$objeto, $chaveNome = null, $verboso = false) {
		global $bd;
		$this->adTabela($tabela);
		$campos=array();
		foreach (get_object_vars($objeto) as $k => $v) {
			if (is_array($v) or is_object($v) or $v == null) continue;
			if ($k == $chaveNome ) continue;
			if ($k[0] == '_') continue;
			$campos[] = $k;
			$valores[$k] = $v;
			}
		foreach ($campos as $campo) $this->adInserir($campo, previnirXSS($valores[$campo]));
		if (!$this->exec())	{
			die('Falhou a inserção de dados em '.$tabela.': '.$bd->ErrorMsg());
			return false;
			}
		$id = db_insert_id($tabela, $chaveNome);
		if ($chaveNome && $id) $objeto->$chaveNome = $id;
		return true;
		}

	public function atualizarObjeto( $tabela, &$objeto, $chaveNome, $atualizarNulos = true, $ignorar=null) {
		global $bd;
		$tem_ignorar=is_array($ignorar); 
		$this->adTabela($tabela);
		foreach (get_object_vars($objeto) as $k => $v) {
			if (is_array($v) or is_object($v) or $k[0] == '_') continue;
			if ($k == $chaveNome) {
				$this->adOnde($chaveNome.' = \''.db_escape($v).'\'');
				continue;
				}
			$campos[] = $k;
			$valores[$k] = $v;
			}
		if (count($valores)) {
			foreach ($campos as $campo) {
				if (!$tem_ignorar) $this->adAtualizar($campo, previnirXSS($valores[$campo]));
				elseif (!in_array($campo, $ignorar)) $this->adAtualizar($campo, previnirXSS($valores[$campo]));
				}
			$this->exec();
			}
		return true;
		}

	public function duplicar() {
		if (version_compare(phpversion(), '5') >= 0) $novoObj = clone($this);
		else $novoObj = $this;
		return $novoObj;
		}

	}
?>
