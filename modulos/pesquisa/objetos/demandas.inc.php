<?php 
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class demandas extends pesquisa {
	public $tabela = 'demandas';
	public $tabela_apelido = 'demandas';
	public $tabela_modulo = 'projetos';
	public $tabela_chave = 'demandas.demanda_id';
	public $tabela_link = 'index.php?m=projetos&a=demanda_ver&demanda_id=';
	public $tabela_titulo = 'Demandas';
	public $tabela_ordem_por = 'demanda_nome';
	public $buscar_campos = array('demanda_nome', 'demanda_identificacao', 'demanda_justificativa', 'demanda_resultados', 'demanda_alinhamento', 'demanda_fonte_recurso', 'demanda_observacao', 'demanda_prazo', 'demanda_custos', 'demanda_codigo', 'demanda_cliente_obs', 'demanda_supervisor_obs', 'demanda_autoridade_obs', 'demanda_descricao', 'demanda_objetivos', 'demanda_como', 'demanda_localizacao', 'demanda_beneficiario', 'demanda_objetivo', 'demanda_objetivo_especifico', 'demanda_escopo', 'demanda_nao_escopo', 'demanda_premissas', 'demanda_restricoes', 'demanda_orcamento', 'demanda_beneficio', 'demanda_produto', 'demanda_requisito');
	public $mostrar_campos = array('demanda_nome', 'demanda_identificacao', 'demanda_justificativa', 'demanda_resultados', 'demanda_alinhamento', 'demanda_fonte_recurso', 'demanda_observacao', 'demanda_prazo', 'demanda_custos', 'demanda_codigo', 'demanda_cliente_obs', 'demanda_supervisor_obs', 'demanda_autoridade_obs', 'demanda_descricao', 'demanda_objetivos', 'demanda_como', 'demanda_localizacao', 'demanda_beneficiario', 'demanda_objetivo', 'demanda_objetivo_especifico', 'demanda_escopo', 'demanda_nao_escopo', 'demanda_premissas', 'demanda_restricoes', 'demanda_orcamento', 'demanda_beneficio', 'demanda_produto', 'demanda_requisito');
	public $tabela_agruparPor = 'demandas.demanda_id';
	public $funcao='demanda';
	}
?>