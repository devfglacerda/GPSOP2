<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CPatrocinador extends CAplicObjeto {

	public $patrocinador_id = null;
  public $patrocinador_nome = null;
  public $patrocinador_cia = null;
  public $patrocinador_dept = null;
  public $patrocinador_responsavel = null;
  public $patrocinador_descricao = null;
  public $patrocinador_endereco1 = null;
  public $patrocinador_endereco2 = null;
  public $patrocinador_cidade = null;
  public $patrocinador_estado = null;
  public $patrocinador_cep = null;
  public $patrocinador_pais = null;
  public $patrocinador_cpf = null;
  public $patrocinador_cnpj = null;
  public $patrocinador_email = null;
  public $patrocinador_url = null;
  public $patrocinador_tel = null;
  public $patrocinador_tel2 = null;
  public $patrocinador_cel = null;
  public $patrocinador_cor = null;
  public $patrocinador_ativo = null;
  public $patrocinador_acesso = null;
  public $patrocinador_tipo = null;
	public $patrocinador_aprovado = null;
	public $patrocinador_moeda = null;

	public function __construct() {
		parent::__construct('patrocinadores', 'patrocinador_id');
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->patrocinador_id) {
			$ret = $sql->atualizarObjeto('patrocinadores', $this, 'patrocinador_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('patrocinadores', $this, 'patrocinador_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('patrocinadores', $this->patrocinador_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->patrocinador_id);


		$patrocinadores_usuarios=getParam($_REQUEST, 'patrocinador_usuarios', null);
		$patrocinadores_usuarios=explode(',', $patrocinadores_usuarios);
		$sql->setExcluir('patrocinadores_usuarios');
		$sql->adOnde('patrocinador_id = '.$this->patrocinador_id);
		$sql->exec();
		$sql->limpar();
		foreach($patrocinadores_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('patrocinadores_usuarios');
				$sql->adInserir('patrocinador_id', $this->patrocinador_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'patrocinador_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('patrocinadores_depts');
		$sql->adOnde('patrocinador_id = '.$this->patrocinador_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('patrocinadores_depts');
				$sql->adInserir('patrocinador_id', $this->patrocinador_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$instrumentos_selecionados=getParam($_REQUEST, 'patrocinador_instrumentos', array());
		$instrumentos_selecionados=explode(',', $instrumentos_selecionados);
		$sql->setExcluir('patrocinadores_instrumentos');
		$sql->adOnde('patrocinador_id = '.$this->patrocinador_id);
		$sql->exec();
		$sql->limpar();
		foreach($instrumentos_selecionados as $chave => $instrumento_id){
			if($instrumento_id){
				$sql->adTabela('patrocinadores_instrumentos');
				$sql->adInserir('patrocinador_id', $this->patrocinador_id);
				$sql->adInserir('instrumento_id', $instrumento_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('patrocinador_cia');
			$sql->adOnde('patrocinador_cia_patrocinador='.(int)$this->patrocinador_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'patrocinador_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('patrocinador_cia');
						$sql->adInserir('patrocinador_cia_patrocinador', $this->patrocinador_id);
						$sql->adInserir('patrocinador_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('patrocinador_gestao');
			$sql->adAtualizar('patrocinador_gestao_patrocinador', (int)$this->patrocinador_id);
			$sql->adAtualizar('patrocinador_gestao_uuid', null);
			$sql->adOnde('patrocinador_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_patrocinador', (int)$this->patrocinador_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_patrocinador', (int)$this->patrocinador_id);
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
			$sql->adOnde('assinatura_patrocinador='.(int)$this->patrocinador_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_patrocinador='.(int)$this->patrocinador_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_patrocinador='.(int)$this->patrocinador_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('patrocinadores');
			$sql->adAtualizar('patrocinador_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('patrocinador_id='.(int)$this->patrocinador_id);
			$sql->exec();
			$sql->limpar();
			}

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	public function check() {
		return null;
		}


	public function podeAcessar() {
		$valor=permiteAcessarPatrocinador($this->patrocinador_acesso, $this->patrocinador_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarPatrocinador($this->patrocinador_acesso, $this->patrocinador_id);
		return $valor;
		}


	public function notificar( $post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('patrocinadores');
		$sql->adCampo('patrocinador_nome');
		$sql->adOnde('patrocinador_id ='.$this->patrocinador_id);
		$patrocinador_nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['patrocinadores_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['patrocinadores_usuarios'].')');
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
			$sql->esqUnir('patrocinadores', 'patrocinadores', 'patrocinadores.patrocinador_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('patrocinador_id='.$this->patrocinador_id);
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
		elseif (isset($post['patrocinador_id']) && $post['patrocinador_id']) $tipo='atualizado';
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
					$email->Assunto('Excluí'.$config['genero_patrocinador'].' '.$config['patrocinador'], $localidade_tipo_caract);
					$titulo='Excluíd'.$config['genero_patrocinador'].' '.$config['patrocinador'].'';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizad'.$config['genero_patrocinador'].' '.$config['patrocinador'].'', $localidade_tipo_caract);
					$titulo='Atualizad'.$config['genero_patrocinador'].' '.$config['patrocinador'].'';
					}
				else {
					$email->Assunto('Inserid'.$config['genero_patrocinador'].' '.$config['patrocinador'].'', $localidade_tipo_caract);
					$titulo='Inserid'.$config['genero_patrocinador'].' '.$config['patrocinador'].'';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizad'.$config['genero_patrocinador'].' '.$config['patrocinador'].': '.$patrocinador_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluíd'.$config['genero_patrocinador'].' '.$config['patrocinador'].': '.$patrocinador_nome.'<br>';
				else $corpo = 'Inserid'.$config['genero_patrocinador'].' '.$config['patrocinador'].': '.$patrocinador_nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão d'.$config['genero_patrocinador'].' '.$config['patrocinador'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição d'.$config['genero_patrocinador'].' '.$config['patrocinador'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador d'.$config['genero_patrocinador'].' '.$config['patrocinador'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;
				
				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=patrocinador_ver&patrocinador_id='.$this->patrocinador_id.'\');"><b>Clique para acessar '.$config['genero_patrocinador'].' '.$config['patrocinador'].'</b></a>';
					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=patrocinador_ver&patrocinador_id='.$this->patrocinador_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_patrocinador'].' '.$config['patrocinador'].'</b></a>';
						}
					}

				$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
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


//funções gerais do módulo



?>