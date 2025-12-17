<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class foruns extends pesquisa {
	public $tabela = 'foruns';
	public $tabela_modulo = 'foruns';
	public $tabela_chave = 'forum_id';
	public $tabela_link = 'index.php?m=foruns&a=ver&forum_id=';
	public $tabela_titulo = 'Fóruns';
	public $tabela_ordem_por = 'forum_nome';
	public $buscar_campos = array('forum_nome', 'forum_descricao');
	public $mostrar_campos = array('forum_nome', 'forum_descricao');
	public $funcao='forum';
	}
?>