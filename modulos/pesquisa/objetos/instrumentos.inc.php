<?php 
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class instrumentos extends pesquisa {
	public $tabela = 'instrumento';
	public $tabela_apelido = 'instrumento';
	public $tabela_modulo = 'instrumento';
	public $tabela_chave = 'instrumento.instrumento_id';
	public $tabela_link = 'index.php?m=instrumento&a=instrumento_ver&instrumento_id=';
	public $tabela_titulo = 'instrumentos';
	public $tabela_ordem_por = 'instrumento_nome';
	public $buscar_campos = array('instrumento_nome', 'instrumento_numero', 'instrumento_edital_nr', 'instrumento_processo', 'instrumento_objeto', 'instrumento_justificativa', 'instrumento_entidade', 'instrumento_entidade_cnpj', 'instrumento_cliente_obs', 'instrumento_supervisor_obs', 'instrumento_autoridade_obs');
	public $mostrar_campos = array('instrumento_nome', 'instrumento_numero', 'instrumento_edital_nr', 'instrumento_processo', 'instrumento_objeto', 'instrumento_justificativa', 'instrumento_entidade', 'instrumento_entidade_cnpj', 'instrumento_cliente_obs', 'instrumento_supervisor_obs', 'instrumento_autoridade_obs');
	public $tabela_agruparPor = 'instrumento.instrumento_id';
	public $funcao='instrumento';
	}
?>