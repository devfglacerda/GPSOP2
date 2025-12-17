<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
class CCausa_efeito extends CAplicObjeto {
  public $causa_efeito_id=null;
  public $causa_efeito_cia=null;
  public $causa_efeito_dept=null;
  public $causa_efeito_responsavel=null;
  public $causa_efeito_nome=null;
  public $causa_efeito_descricao=null;
  public $causa_efeito_objeto=null;
  public $causa_efeito_data=null;
  public $causa_efeito_cor=null;
  public $causa_efeito_acesso=null;
	public $causa_efeito_ativo=null;
	public $causa_efeito_principal_indicador = null;
	public $causa_efeito_moeda = null;
	public $causa_efeito_aprovado = null;
	
	public function __construct() {
		parent::__construct('causa_efeito', 'causa_efeito_id');
		}

	public function excluir( $oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('causa_efeito_id', null)==$this->causa_efeito_id) $Aplic->setEstado('causa_efeito_id', null);
		parent::excluir();
		return null;
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic, $_REQUEST;
		$sql = new BDConsulta();
		if ($_REQUEST['causa_efeito_id']) {
			$ret = $sql->atualizarObjeto('causa_efeito', $this, 'causa_efeito_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('causa_efeito', $this, 'causa_efeito_id');
			$sql->limpar();
			}
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('causa_efeito', $this->causa_efeito_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->causa_efeito_id);

		$causa_efeito_usuarios=getParam($_REQUEST, 'causa_efeito_usuarios', null);
		$causa_efeito_usuarios=explode(',', $causa_efeito_usuarios);
		$sql->setExcluir('causa_efeito_usuarios');
		$sql->adOnde('causa_efeito_id = '.$this->causa_efeito_id);
		$sql->exec();
		$sql->limpar();
		foreach($causa_efeito_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('causa_efeito_usuarios');
				$sql->adInserir('causa_efeito_id', $this->causa_efeito_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$causa_efeito_depts=getParam($_REQUEST, 'causa_efeito_depts', null);
		$causa_efeito_depts=explode(',', $causa_efeito_depts);
		$sql->setExcluir('causa_efeito_depts');
		$sql->adOnde('causa_efeito_id = '.$this->causa_efeito_id);
		$sql->exec();
		$sql->limpar();
		foreach($causa_efeito_depts as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('causa_efeito_depts');
				$sql->adInserir('causa_efeito_id', $this->causa_efeito_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('causa_efeito_cia');
			$sql->adOnde('causa_efeito_cia_causa_efeito='.(int)$this->causa_efeito_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'causa_efeito_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('causa_efeito_cia');
						$sql->adInserir('causa_efeito_cia_causa_efeito', $this->causa_efeito_id);
						$sql->adInserir('causa_efeito_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('causa_efeito_gestao');
			$sql->adAtualizar('causa_efeito_gestao_causa_efeito', (int)$this->causa_efeito_id);
			$sql->adAtualizar('causa_efeito_gestao_uuid', null);
			$sql->adOnde('causa_efeito_gestao_uuid=\''.getParam($_REQUEST, 'uuid', null).'\'');
			$sql->exec();
			$sql->limpar();
			
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_causa_efeito', (int)$this->causa_efeito_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_causa_efeito', (int)$this->causa_efeito_id);
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
			$sql->adOnde('assinatura_causa_efeito='.(int)$this->causa_efeito_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_causa_efeito='.(int)$this->causa_efeito_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_causa_efeito='.(int)$this->causa_efeito_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('causa_efeito');
			$sql->adAtualizar('causa_efeito_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('causa_efeito_id='.(int)$this->causa_efeito_id);
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

		if (isset($post['causa_efeito_usuarios']) && $post['causa_efeito_usuarios'] && isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['causa_efeito_usuarios'].')');
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
			$sql->esqUnir('causa_efeito', 'causa_efeito', 'causa_efeito.causa_efeito_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('causa_efeito_id='.$this->causa_efeito_id);
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
		elseif (isset($post['causa_efeito_id']) && $post['causa_efeito_id']) $tipo='atualizado';
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
					$email->Assunto('Excluída causa-efeito', $localidade_tipo_caract);
					$titulo='Excluída causa-efeito';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizada causa-efeito', $localidade_tipo_caract);
					$titulo='Atualizada causa-efeito';
					}
				else {
					$email->Assunto('Inserida causa-efeito', $localidade_tipo_caract);
					$titulo='Inserida causa-efeito';
					}

				if ($tipo=='atualizado') $corpo = 'Atualizada causa-efeito: '.$this->causa_efeito_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído causa_efeito: '.$this->causa_efeito_nome.'<br>';
				else $corpo = 'Inserido causa_efeito<: '.$this->causa_efeito_nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão da causa-efeito:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição da causa-efeito:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador da causa-efeito:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=causa_efeito_ver&causa_efeito_id='.$this->causa_efeito_id.'\');"><b>Clique para acessar o causa_efeito</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=causa_efeito_ver&causa_efeito_id='.$this->causa_efeito_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar a causa-efeito</b></a>';
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