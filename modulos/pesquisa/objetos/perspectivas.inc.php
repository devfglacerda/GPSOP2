<?php 
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');


class perspectivas extends pesquisa {
	public $tabela = 'perspectivas';
	public $tabela_apelido = 'perspectivas';
	public $tabela_modulo = 'praticas';
	public $tabela_chave = 'perspectivas.pg_perspectiva_id';
	public $tabela_link = 'index.php?m=praticas&a=perspectiva_ver&pg_perspectiva_id=';
	public $tabela_titulo ='perspectivas';
	public $tabela_ordem_por = 'pg_perspectiva_nome';
	public $buscar_campos = array('pg_perspectiva_nome', 'pg_perspectiva_oque','pg_perspectiva_descricao','pg_perspectiva_onde','pg_perspectiva_quando','pg_perspectiva_como','pg_perspectiva_porque','pg_perspectiva_quanto','pg_perspectiva_quem','pg_perspectiva_controle','pg_perspectiva_melhorias','pg_perspectiva_metodo_aprendizado','pg_perspectiva_desde_quando');
	public $mostrar_campos = array('pg_perspectiva_nome', 'pg_perspectiva_oque','pg_perspectiva_descricao','pg_perspectiva_onde','pg_perspectiva_quando','pg_perspectiva_como','pg_perspectiva_porque','pg_perspectiva_quanto','pg_perspectiva_quem','pg_perspectiva_controle','pg_perspectiva_melhorias','pg_perspectiva_metodo_aprendizado','pg_perspectiva_desde_quando');
	public $funcao='perspectiva';
	}
?>