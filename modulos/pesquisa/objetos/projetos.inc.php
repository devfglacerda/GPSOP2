<?php 
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
	
class projetos extends pesquisa {
	public $tabela = 'projetos';
	public $tabela_apelido = 'projetos';
	public $tabela_modulo = 'projetos';
	public $tabela_chave = 'projeto_id';
	public $tabela_link = 'index.php?m=projetos&a=ver&projeto_id=';
	public $tabela_titulo = 'projetos';
	public $tabela_ordem_por = 'projeto_nome';
	public $buscar_campos = array('projeto_nome', 'projeto_descricao', 'projeto_url', 'projeto_url_externa', 'projeto_codigo', 'projeto_objetivos', 'projeto_observacao', 'projeto_como', 'projeto_localizacao', 'projeto_beneficiario', 'projeto_justificativa', 'projeto_objetivo', 'projeto_objetivo_especifico', 'projeto_escopo', 'projeto_nao_escopo', 'projeto_premissas', 'projeto_restricoes', 'projeto_orcamento', 'projeto_beneficio', 'projeto_produto', 'projeto_requisito');
	public $mostrar_campos = array('projeto_nome', 'projeto_descricao', 'projeto_url', 'projeto_url_externa', 'projeto_codigo', 'projeto_objetivos', 'projeto_observacao', 'projeto_como', 'projeto_localizacao', 'projeto_beneficiario', 'projeto_justificativa', 'projeto_objetivo', 'projeto_objetivo_especifico', 'projeto_escopo', 'projeto_nao_escopo', 'projeto_premissas', 'projeto_restricoes', 'projeto_orcamento', 'projeto_beneficio', 'projeto_produto', 'projeto_requisito');
	public $funcao='projeto';

	}

?>