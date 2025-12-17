<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class cias extends pesquisa {
	public $tabela = 'cias';
	public $tabela_modulo = 'cias';
	public $tabela_chave = 'cia_id';
	public $tabela_link = 'index.php?m=cias&a=ver&cia_id=';
	public $tabela_titulo='organizacoes';
	public $tabela_ordem_por = 'cia_nome';
	public $buscar_campos = array('cia_nome', 'cia_endereco1', 'cia_endereco2', 'cia_cidade', 'cia_estado', 'cia_cep', 'cia_url', 'cia_descricao', 'cia_email');
	public $mostrar_campos = array('cia_nome', 'cia_endereco1', 'cia_endereco2', 'cia_cidade', 'cia_estado', 'cia_cep', 'cia_url', 'cia_descricao', 'cia_email');
  public $funcao='organizacao';
	}
?>