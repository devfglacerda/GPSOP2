<?php 
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class contatos extends pesquisa {
	public $tabela = 'contatos';
	public $tabela_modulo = 'contatos';
	public $tabela_chave = 'contato_id';
	public $tabela_link = 'index.php?m=contatos&a=ver&contato_id=';
	public $tabela_titulo = 'Contatos';
	public $tabela_ordem_por = 'contato_nomeguerra,contato_posto';
	public $buscar_campos = array('contato_posto', 'contato_nomeguerra', 'contato_nomecompleto', 'contato_arma', 'contato_cia', 'contato_tipo', 'contato_email', 'contato_email2', 'contato_endereco1', 'contato_endereco2', 'contato_cidade', 'contato_estado', 'contato_cep', 'contato_pais', 'contato_notas');
	public $mostrar_campos = array('contato_posto', 'contato_nomeguerra', 'contato_nomecompleto', 'contato_arma', 'contato_cia', 'contato_tipo', 'contato_email', 'contato_email2', 'contato_endereco1', 'contato_endereco2', 'contato_cidade', 'contato_estado', 'contato_cep', 'contato_pais', 'contato_notas');
  public $funcao='contato';
	}
?>