<?php 
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');


class objetivos extends pesquisa {
	public $tabela = 'objetivo';
	public $tabela_apelido = 'objetivo';
	public $tabela_modulo = 'praticas';
	public $tabela_chave = 'objetivo.objetivo_id';
	public $tabela_link = 'index.php?m=praticas&a=obj_estrategico_ver&objetivo_id=';
	public $tabela_titulo ='objetivos';
	public $tabela_ordem_por = 'objetivo_nome';
	public $buscar_campos = array('objetivo_nome', 'objetivo_oque','objetivo_descricao','objetivo_onde','objetivo_quando','objetivo_como','objetivo_porque','objetivo_quanto','objetivo_quem','objetivo_controle','objetivo_melhorias','objetivo_metodo_aprendizado','objetivo_desde_quando');
	public $mostrar_campos = array('objetivo_nome', 'objetivo_oque','objetivo_descricao','objetivo_onde','objetivo_quando','objetivo_como','objetivo_porque','objetivo_quanto','objetivo_quem','objetivo_controle','objetivo_melhorias','objetivo_metodo_aprendizado','objetivo_desde_quando');
	public $funcao='objetivo';
	}
?>