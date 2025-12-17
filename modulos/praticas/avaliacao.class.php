<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CAvaliacao extends CAplicObjeto {

	public $avaliacao_id = null;
  public $avaliacao_cia = null;
  public $avaliacao_dept = null;
  public $avaliacao_responsavel = null;
  public $avaliacao_nome = null;
  public $avaliacao_data = null;
  public $avaliacao_descricao = null;
  public $avaliacao_inicio = null;
  public $avaliacao_fim = null;
  public $avaliacao_status = null;
  public $avaliacao_acesso = null;
  public $avaliacao_cor = null;
	public $avaliacao_ativa = null;

	public function __construct() {
		parent::__construct('avaliacao', 'avaliacao_id');
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->avaliacao_id) {
			$ret = $sql->atualizarObjeto('avaliacao', $this, 'avaliacao_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('avaliacao', $this, 'avaliacao_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('avaliacao', $this->avaliacao_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->avaliacao_id);


		$avaliacao_usuarios=getParam($_REQUEST, 'avaliacao_usuarios', null);
		$avaliacao_usuarios=explode(',', $avaliacao_usuarios);
		$sql->setExcluir('avaliacao_usuarios');
		$sql->adOnde('avaliacao_id = '.$this->avaliacao_id);
		$sql->exec();
		$sql->limpar();
		foreach($avaliacao_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('avaliacao_usuarios');
				$sql->adInserir('avaliacao_id', $this->avaliacao_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'avaliacao_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('avaliacao_dept');
		$sql->adOnde('avaliacao_dept_avaliacao = '.$this->avaliacao_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('avaliacao_dept');
				$sql->adInserir('avaliacao_dept_avaliacao', $this->avaliacao_id);
				$sql->adInserir('avaliacao_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('avaliacao_cia');
			$sql->adOnde('avaliacao_cia_avaliacao='.(int)$this->avaliacao_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'avaliacao_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('avaliacao_cia');
						$sql->adInserir('avaliacao_cia_avaliacao', $this->avaliacao_id);
						$sql->adInserir('avaliacao_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}

			$uuid=getParam($_REQUEST, 'uuid', null);
			if ($uuid){
				$sql->adTabela('avaliacao_gestao');
				$sql->adAtualizar('avaliacao_gestao_avaliacao', (int)$this->avaliacao_id);
				$sql->adAtualizar('avaliacao_gestao_uuid', null);
				$sql->adOnde('avaliacao_gestao_uuid=\''.$uuid.'\'');
				$sql->exec();
				$sql->limpar();
				}
			}

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	public function check() {
		return null;
		}


	public function podeAcessar() {
		$valor=permiteAcessarAvaliacao($this->avaliacao_acesso, $this->avaliacao_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarAvaliaca($this->avaliacao_acesso, $this->avaliacao_id);
		return $valor;
		}




	public function notificar( $post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('avaliacao');
		$sql->adCampo('avaliacao_nome');
		$sql->adOnde('avaliacao_id ='.$this->avaliacao_id);
		$meta_nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['avaliacao_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['avaliacao_usuarios'].')');
			$usuarios1 = $sql->Lista();
			$sql->limpar();
			}
		if ($post['email_outro']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_outro'].')');
			$usuarios2=$sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_responsavel'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->esqUnir('avaliacao', 'avaliacao', 'avaliacao.avaliacao_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('avaliacao_id='.$this->avaliacao_id);
			$usuarios3=$sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_extras']) && $post['email_extras']){
			$extras=explode(',',$post['email_extras']);
			foreach($extras as $chave => $valor) $usuarios4[]=array('usuario_id' => 0, 'nome_usuario' =>'', 'contato_email'=> $valor);
			}



		$usuarios = array_merge((array)$usuarios1, (array)$usuarios2);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios3);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios4);


		$usado_usuario=array();
		$usado_email=array();

		if (isset($post['del']) && $post['del'])$tipo='excluido';
		elseif (isset($post['avaliacao_id']) && $post['avaliacao_id']) $tipo='atualizado';
		else $tipo='incluido';

		foreach($usuarios as $usuario){
			if (!isset($usado[$usuario['usuario_id']]) && !isset($usado[$usuario['contato_email']])){

				if ($usuario['usuario_id']) $usado[$usuario['usuario_id']]=1;
				if ($usuario['contato_email']) $usado[$usuario['contato_email']]=1;
				$email = new Mail;
                $email->De($config['email'], $Aplic->usuario_nome);

                if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                    $email->ResponderPara($Aplic->usuario_email);
                    }
                else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                    $email->ResponderPara($Aplic->usuario_email2);
                    }

				if ($tipo == 'excluido') {
					$email->Assunto('Excluído a avaliação', $localidade_tipo_caract);
					$titulo='Excluída avaliação';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado a avaliação', $localidade_tipo_caract);
					$titulo='Atualizada avaliação';
					}
				else {
					$email->Assunto('Inserido a avaliação', $localidade_tipo_caract);
					$titulo='Inserido a avaliação';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado a avaliação: '.$meta_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído a avaliação: '.$meta_nome.'<br>';
				else $corpo = 'Inserido a avaliação: '.$meta_nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão da avaliação:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição da avaliação:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador da avaliação:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;
				
				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=meta_ver&avaliacao_id='.$this->avaliacao_id.'\');"><b>Clique para acessar a avaliação</b></a>';
					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=meta_ver&avaliacao_id='.$this->avaliacao_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar a avaliação</b></a>';
						}
					}

				$email->Corpo($corpo_interno, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
				if ($usuario['usuario_id']!=$Aplic->usuario_id && $usuario['usuario_id']) {
					if ($usuario['usuario_id']) msg_email_interno('', $titulo, $corpo_interno,'',$usuario['usuario_id']);
					if ($email->EmailValido($usuario['contato_email']) && $config['email_ativo']) {
						$email->Para($usuario['contato_email'], true);
						$email->Enviar();
						}
					}
				}
			}
		}

	}


?>