<?php 
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class recursos extends pesquisa {
	public $tabela = 'recursos';
	public $tabela_modulo = 'recursos';
	public $tabela_chave = 'recurso_id';
	public $tabela_link = 'index.php?m=recursos&a=ver&recurso_id=';
	public $tabela_titulo = 'Recursos';
	public $tabela_ordem_por = 'recurso_nome';
	public $buscar_campos = array('recurso_nome', 'recurso_nota', 'recurso_chave');
	public $mostrar_campos = array('recurso_nome', 'recurso_nota', 'recurso_chave');
	public $funcao='recurso';
	public function crecursos() {
		return new recursos();
		}
	}
?>