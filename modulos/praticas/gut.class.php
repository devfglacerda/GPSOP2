<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
class CGut extends CAplicObjeto {
  public $gut_id=null;
  public $gut_cia=null;
  public $gut_dept=null;
  public $gut_responsavel=null;
  public $gut_nome=null;
  public $gut_descricao=null;
  public $gut_data=null;
  public $gut_cor=null;
  public $gut_acesso=null;
	public $gut_ativo=null;
	public $gut_principal_indicador = null;
	public $gut_moeda = null;
	public $gut_aprovado = null;
	
	public function __construct() {
		parent::__construct('gut', 'gut_id');
		}

	public function excluir( $oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('gut_id', null)==$this->gut_id) $Aplic->setEstado('gut_id', null);
		parent::excluir();
		return null;
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic, $_REQUEST;
		$sql = new BDConsulta();
		if ($_REQUEST['gut_id']) {
			$ret = $sql->atualizarObjeto('gut', $this, 'gut_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('gut', $this, 'gut_id');
			$sql->limpar();
			}
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('gut', $this->gut_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->gut_id);

		$gut_usuarios=getParam($_REQUEST, 'gut_usuarios', null);
		$gut_usuarios=explode(',', $gut_usuarios);
		$sql->setExcluir('gut_usuarios');
		$sql->adOnde('gut_id = '.$this->gut_id);
		$sql->exec();
		$sql->limpar();
		foreach($gut_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('gut_usuarios');
				$sql->adInserir('gut_id', $this->gut_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$gut_depts=getParam($_REQUEST, 'gut_depts', null);
		$gut_depts=explode(',', $gut_depts);
		$sql->setExcluir('gut_depts');
		$sql->adOnde('gut_id = '.$this->gut_id);
		$sql->exec();
		$sql->limpar();
		foreach($gut_depts as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('gut_depts');
				$sql->adInserir('gut_id', $this->gut_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}


		if ($Aplic->profissional){
			$sql->setExcluir('gut_cia');
			$sql->adOnde('gut_cia_gut='.(int)$this->gut_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'gut_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('gut_cia');
						$sql->adInserir('gut_cia_gut', $this->gut_id);
						$sql->adInserir('gut_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('gut_gestao');
			$sql->adAtualizar('gut_gestao_gut', (int)$this->gut_id);
			$sql->adAtualizar('gut_gestao_uuid', null);
			$sql->adOnde('gut_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('gut_linha');
			$sql->adAtualizar('gut_id', (int)$this->gut_id);
			$sql->adAtualizar('uuid', null);
			$sql->adOnde('uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_gut', (int)$this->gut_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_gut', (int)$this->gut_id);
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
			$sql->adOnde('assinatura_gut='.(int)$this->gut_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_gut='.(int)$this->gut_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_gut='.(int)$this->gut_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('gut');
			$sql->adAtualizar('gut_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('gut_id='.(int)$this->gut_id);
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

		if (isset($post['gut_usuarios']) && $post['gut_usuarios'] && isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['gut_usuarios'].')');
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
			$sql->esqUnir('gut', 'gut', 'gut.gut_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('gut_id='.$this->gut_id);
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
		elseif (isset($post['gut_id']) && $post['gut_id']) $tipo='atualizado';
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
					$email->Assunto('Excluída matriz GUT', $localidade_tipo_caract);
					$titulo='Excluída matriz GUT';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizada matriz GUT', $localidade_tipo_caract);
					$titulo='Atualizada matriz GUT';
					}
				else {
					$email->Assunto('Inserida matriz GUT', $localidade_tipo_caract);
					$titulo='Inserida matriz GUT';
					}

				if ($tipo=='atualizado') $corpo = 'Atualizada matriz GUT: '.$this->gut_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluído matriz GUT: '.$this->gut_nome.'<br>';
				else $corpo = 'Inserido matriz GUT: '.$this->gut_nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão da matriz GUT:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição da matriz GUT:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador da matriz GUT:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=gut_ver&gut_id='.$this->gut_id.'\');"><b>Clique para acessar o gut</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=gut_ver&gut_id='.$this->gut_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar a matriz GUT</b></a>';
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