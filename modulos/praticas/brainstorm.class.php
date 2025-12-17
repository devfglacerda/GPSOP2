<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
class CBrainstorm extends CAplicObjeto {
  public $brainstorm_id=null;
  public $brainstorm_cia=null;
  public $brainstorm_dept=null;
  public $brainstorm_responsavel=null;
  public $brainstorm_nome=null;
  public $brainstorm_descricao=null;
  public $brainstorm_objeto=null;
  public $brainstorm_data=null;
  public $brainstorm_cor=null;
  public $brainstorm_acesso=null;
	public $brainstorm_ativo=null;
	public $brainstorm_principal_indicador = null;
	public $brainstorm_moeda = null;
	public $brainstorm_aprovado = null;
	
	
	public function __construct() {
		parent::__construct('brainstorm', 'brainstorm_id');
		}

	public function excluir( $oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('brainstorm_id', null)==$this->brainstorm_id) $Aplic->setEstado('brainstorm_id', null);
		parent::excluir();
		return null;
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic, $_REQUEST;
		$sql = new BDConsulta();
		if ($_REQUEST['brainstorm_id']) {
			$ret = $sql->atualizarObjeto('brainstorm', $this, 'brainstorm_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('brainstorm', $this, 'brainstorm_id');
			$sql->limpar();
			}
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('brainstorm', $this->brainstorm_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->brainstorm_id);

		$brainstorm_usuarios=getParam($_REQUEST, 'brainstorm_usuarios', null);
		$brainstorm_usuarios=explode(',', $brainstorm_usuarios);
		$sql->setExcluir('brainstorm_usuarios');
		$sql->adOnde('brainstorm_id = '.$this->brainstorm_id);
		$sql->exec();
		$sql->limpar();
		foreach($brainstorm_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('brainstorm_usuarios');
				$sql->adInserir('brainstorm_id', $this->brainstorm_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$brainstorm_depts=getParam($_REQUEST, 'brainstorm_depts', null);
		$brainstorm_depts=explode(',', $brainstorm_depts);
		$sql->setExcluir('brainstorm_depts');
		$sql->adOnde('brainstorm_id = '.$this->brainstorm_id);
		$sql->exec();
		$sql->limpar();
		foreach($brainstorm_depts as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('brainstorm_depts');
				$sql->adInserir('brainstorm_id', $this->brainstorm_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('brainstorm_cia');
			$sql->adOnde('brainstorm_cia_brainstorm='.(int)$this->brainstorm_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'brainstorm_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('brainstorm_cia');
						$sql->adInserir('brainstorm_cia_brainstorm', $this->brainstorm_id);
						$sql->adInserir('brainstorm_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}
		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('brainstorm_gestao');
			$sql->adAtualizar('brainstorm_gestao_brainstorm', (int)$this->brainstorm_id);
			$sql->adAtualizar('brainstorm_gestao_uuid', null);
			$sql->adOnde('brainstorm_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_brainstorm', (int)$this->brainstorm_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_brainstorm', (int)$this->brainstorm_id);
			$sql->adAtualizar('priorizacao_uuid', null);
			$sql->adOnde('priorizacao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();		
			}
		
		//verificar aprovacao
		if ($Aplic->profissional) {
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_brainstorm='.(int)$this->brainstorm_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_brainstorm='.(int)$this->brainstorm_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_brainstorm='.(int)$this->brainstorm_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('brainstorm');
			$sql->adAtualizar('brainstorm_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('brainstorm_id='.(int)$this->brainstorm_id);
			$sql->exec();
			$sql->limpar();
			}

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	public function check() {
		return null;
		}



	public function notificar( $post=array()){
		global $Aplic, $config, $localidade_tipo_caract;
		require_once ($Aplic->getClasseSistema('libmail'));
		$sql = new BDConsulta;

		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if (isset($post['brainstorm_usuarios']) && $post['brainstorm_usuarios'] && isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['brainstorm_usuarios'].')');
			$usuarios1 = $sql->Lista();
			$sql->limpar();
			}
		if (isset($post['email_outro']) && $post['email_outro']){
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
			$sql->esqUnir('brainstorm', 'brainstorm', 'brainstorm.brainstorm_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('brainstorm_id='.$this->brainstorm_id);
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

		if (isset($post['excluir']) && $post['excluir'])$tipo='excluido';
		elseif (isset($post['brainstorm_id']) && $post['brainstorm_id']) $tipo='atualizado';
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
					$email->Assunto('Excluído brainstorm', $localidade_tipo_caract);
					$titulo='Excluído brainstorm';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado brainstorm', $localidade_tipo_caract);
					$titulo='Atualizado brainstorm';
					}
				else {
					$email->Assunto('Inserido brainstorm', $localidade_tipo_caract);
					$titulo='Inserido brainstorm';
					}

				if ($tipo=='atualizado') $corpo = 'Atualizado brainstorm: '.$this->brainstorm_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído brainstorm: '.$this->brainstorm_nome.'<br>';
				else $corpo = 'Inserido brainstorm: '.$this->brainstorm_nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão do brainstorm:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição do brainstorm:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do brainstorm:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=brainstorm_ver&brainstorm_id='.$this->brainstorm_id.'\');"><b>Clique para acessar o brainstorm</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=brainstorm_ver&brainstorm_id='.$this->brainstorm_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o brainstorm</b></a>';
						}
					}

				$email->Corpo($corpo_externo, (isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : $localidade_tipo_caract));
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