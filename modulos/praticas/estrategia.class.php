<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CEstrategia extends CAplicObjeto {

	public $pg_estrategia_id = null;
  public $pg_estrategia_cia = null;
  public $pg_estrategia_dept = null;
  public $pg_estrategia_principal_indicador = null;
  public $pg_estrategia_nome = null;
  public $pg_estrategia_fator = null;
  public $pg_estrategia_data = null;
  public $pg_estrategia_usuario = null;
  public $pg_estrategia_ordem = null;
  public $pg_estrategia_acesso = null;
  public $pg_estrategia_cor = null;
  public $pg_estrategia_oque = null;
  public $pg_estrategia_descricao = null;
  public $pg_estrategia_onde = null;
  public $pg_estrategia_quando = null;
  public $pg_estrategia_como = null;
  public $pg_estrategia_porque = null;
  public $pg_estrategia_quanto = null;
  public $pg_estrategia_quem = null;
  public $pg_estrategia_controle = null;
  public $pg_estrategia_melhorias = null;
  public $pg_estrategia_metodo_aprendizado = null;
  public $pg_estrategia_desde_quando = null;
  public $pg_estrategia_ativo = null;
  public $pg_estrategia_tipo = null;
	public $pg_estrategia_ano = null;
	public $pg_estrategia_codigo = null;
	public $pg_estrategia_inicio = null;
	public $pg_estrategia_fim = null;
	public $pg_estrategia_tipo_pontuacao = null;
	public $pg_estrategia_percentagem = null;
	public $pg_estrategia_ponto_alvo = null;
	public $pg_estrategia_aprovado = null;
	public $pg_estrategia_moeda = null;

	public function __construct() {
		parent::__construct('estrategias', 'pg_estrategia_id');
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->pg_estrategia_id) {
			$ret = $sql->atualizarObjeto('estrategias', $this, 'pg_estrategia_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('estrategias', $this, 'pg_estrategia_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));

		$campos_customizados = new CampoCustomizados('estrategias', $this->pg_estrategia_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->pg_estrategia_id);


		$estrategias_usuarios=getParam($_REQUEST, 'estrategias_usuarios', null);
		$estrategias_usuarios=explode(',', $estrategias_usuarios);
		$sql->setExcluir('estrategias_usuarios');
		$sql->adOnde('pg_estrategia_id = '.$this->pg_estrategia_id);
		$sql->exec();
		$sql->limpar();
		foreach($estrategias_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('estrategias_usuarios');
				$sql->adInserir('pg_estrategia_id', $this->pg_estrategia_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'pg_estrategia_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('estrategias_depts');
		$sql->adOnde('pg_estrategia_id = '.$this->pg_estrategia_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('estrategias_depts');
				$sql->adInserir('pg_estrategia_id', $this->pg_estrategia_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('estrategia_cia');
			$sql->adOnde('estrategia_cia_estrategia='.(int)$this->pg_estrategia_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'estrategia_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('estrategia_cia');
						$sql->adInserir('estrategia_cia_estrategia', $this->pg_estrategia_id);
						$sql->adInserir('estrategia_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		$uuid=getParam($_REQUEST, 'uuid', null);

		if ($uuid){
			$sql->adTabela('estrategia_gestao');
			$sql->adAtualizar('estrategia_gestao_estrategia', (int)$this->pg_estrategia_id);
			$sql->adAtualizar('estrategia_gestao_uuid', null);
			$sql->adOnde('estrategia_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}

		if ($uuid){

			$sql->adTabela('estrategia_media');
			$sql->adAtualizar('estrategia_media_estrategia', (int)$this->pg_estrategia_id);
			$sql->adAtualizar('estrategia_media_uuid', null);
			$sql->adOnde('estrategia_media_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('plano_acao_observador');
			$sql->adAtualizar('plano_acao_observador_estrategia', (int)$this->pg_estrategia_id);
			$sql->adAtualizar('plano_acao_observador_uuid', null);
			$sql->adOnde('plano_acao_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('projeto_observador');
			$sql->adAtualizar('projeto_observador_estrategia', (int)$this->pg_estrategia_id);
			$sql->adAtualizar('projeto_observador_uuid', null);
			$sql->adOnde('projeto_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_estrategia', (int)$this->pg_estrategia_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_estrategia', (int)$this->pg_estrategia_id);
			$sql->adAtualizar('priorizacao_uuid', null);
			$sql->adOnde('priorizacao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();		
			}

		if ($Aplic->profissional) {
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_estrategia='.(int)$this->pg_estrategia_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_estrategia='.(int)$this->pg_estrategia_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_estrategia='.(int)$this->pg_estrategia_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('estrategias');
			$sql->adAtualizar('pg_estrategia_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('pg_estrategia_id='.(int)$this->pg_estrategia_id);
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
		$valor=permiteAcessarEstrategia($this->pg_estrategia_acesso, $this->pg_estrategia_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarEstrategia($this->pg_estrategia_acesso, $this->pg_estrategia_id);
		return $valor;
		}

	public function calculo_percentagem(){
		$tipo=$this->pg_estrategia_tipo_pontuacao;

		$sql = new BDConsulta;
		$porcentagem=null;
		if (!$tipo) $porcentagem=$this->pg_estrategia_percentagem;
		elseif($tipo=='media_ponderada'){
			$sql->adTabela('estrategia_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=estrategia_media_projeto');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=estrategia_media_acao');
			$sql->adCampo('
			projeto_percentagem,
			plano_acao_percentagem,
			estrategia_media_projeto,
			estrategia_media_acao,
			estrategia_media_peso
			');

			$sql->adOnde('estrategia_media_estrategia ='.(int)$this->pg_estrategia_id);
			$sql->adOnde('estrategia_media_tipo =\'media_ponderada\'');
			$lista = $sql->lista();
			$sql->limpar();
			$numerador=0;
			$denominador=0;

			foreach($lista as $linha){
				if ($linha['estrategia_media_projeto']) $numerador+=($linha['projeto_percentagem']*$linha['estrategia_media_peso']);
				elseif ($linha['estrategia_media_acao']) $numerador+=($linha['plano_acao_percentagem']*$linha['estrategia_media_peso']);
				$denominador+=$linha['estrategia_media_peso'];
				}
			$porcentagem=($denominador ? $numerador/$denominador : 0);
			}
		elseif($tipo=='pontos_completos'){

			$sql->adTabela('estrategia_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=estrategia_media_projeto');
			$sql->adCampo('SUM(estrategia_media_ponto)');
			$sql->adOnde('estrategia_media_estrategia ='.(int)$this->pg_estrategia_id);
			$sql->adOnde('estrategia_media_tipo =\'pontos_completos\'');
			$sql->adOnde('projeto_percentagem = 100');
			$sql->adOnde('estrategia_media_projeto > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('estrategia_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=estrategia_media_acao');
			$sql->adCampo('SUM(estrategia_media_ponto)');
			$sql->adOnde('estrategia_media_estrategia ='.(int)$this->pg_estrategia_id);
			$sql->adOnde('estrategia_media_tipo =\'pontos_completos\'');
			$sql->adOnde('plano_acao_percentagem = 100');
			$sql->adOnde('estrategia_media_acao > 0');
			$pontos5 = $sql->Resultado();
			$sql->limpar();


			$porcentagem=($this->pg_estrategia_ponto_alvo ? (($pontos4+$pontos5)/$this->pg_estrategia_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='pontos_parcial'){
			$sql->adTabela('estrategia_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=estrategia_media_projeto');
			$sql->adCampo('SUM(estrategia_media_ponto*(projeto_percentagem/100))');
			$sql->adOnde('estrategia_media_estrategia ='.(int)$this->pg_estrategia_id);
			$sql->adOnde('estrategia_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('estrategia_media_projeto > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('estrategia_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=estrategia_media_acao');
			$sql->adCampo('SUM(estrategia_media_ponto*(plano_acao_percentagem/100))');
			$sql->adOnde('estrategia_media_estrategia ='.(int)$this->pg_estrategia_id);
			$sql->adOnde('estrategia_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('estrategia_media_acao > 0');
			$pontos5 = $sql->Resultado();
			$sql->limpar();

			$porcentagem=($this->pg_estrategia_ponto_alvo ? (($pontos4+$pontos5)/$this->pg_estrategia_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='indicador'){
			if ($this->pg_estrategia_principal_indicador) {
				include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
				$obj_indicador = new Indicador($this->pg_estrategia_principal_indicador);
				$porcentagem=$obj_indicador->Pontuacao();
				}
			else $porcentagem=0;
			}

		else $porcentagem=0; //caso nao previsto

		if ($porcentagem > 100) $porcentagem=100;
		if ($porcentagem!=$this->pg_estrategia_percentagem){
			$sql->adTabela('estrategias');
			$sql->adAtualizar('pg_estrategia_percentagem', $porcentagem);
			$sql->adOnde('pg_estrategia_id ='.(int)$this->pg_estrategia_id);
			$sql->exec();
			$sql->limpar();
			
			$this->disparo_observador('fisico');
			}
		return $porcentagem;
		}


	public function disparo_observador( $acao='fisico'){
		//Quem faz uso desta estratégia em cálculos de percntagem

		$sql = new BDConsulta;

		$sql->adTabela('estrategia_observador');
		$sql->adCampo('estrategia_observador.*');
		$sql->adOnde('estrategia_observador_estrategia ='.(int)$this->pg_estrategia_id);
		if ($acao) $sql->adOnde('estrategia_observador_acao =\''.$acao.'\'');
		$lista = $sql->lista();
		$sql->limpar();

		$qnt_objetivo=0;
		$qnt_me=0;
		$qnt_fator=0;

		foreach($lista as $linha){
			if ($linha['estrategia_observador_perspectiva']){
				if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
				$obj= new CPerspectiva();
				$obj->load($linha['estrategia_observador_perspectiva']);
				if (method_exists($obj, $linha['estrategia_observador_metodo'])){
					$obj->{$linha['estrategia_observador_metodo']}();
					}
				}
			elseif ($linha['estrategia_observador_tema']){
				if (!($qnt_tema++)) require_once BASE_DIR.'/modulos/praticas/tema.class.php';
				$obj= new CTema();
				$obj->load($linha['estrategia_observador_tema']);
				if (method_exists($obj, $linha['estrategia_observador_metodo'])){
					$obj->{$linha['estrategia_observador_metodo']}();
					}
				}
			elseif ($linha['estrategia_observador_objetivo']){
				if (!($qnt_objetivo++)) require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
				$obj= new CObjetivo();
				$obj->load($linha['estrategia_observador_objetivo']);
				if (method_exists($obj, $linha['estrategia_observador_metodo'])){
					$obj->{$linha['estrategia_observador_metodo']}();
					}
				}
			elseif ($linha['estrategia_observador_me']){
				if (!($qnt_me++)) require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
				$obj= new CMe();
				$obj->load($linha['estrategia_observador_me']);
				if (method_exists($obj, $linha['estrategia_observador_metodo'])){
					$obj->{$linha['estrategia_observador_metodo']}();
					}
				}
			elseif ($linha['estrategia_observador_fator']){
				if (!($qnt_fator++)) require_once BASE_DIR.'/modulos/praticas/fator.class.php';
				$obj= new CFator();
				$obj->load($linha['estrategia_observador_fator']);
				if (method_exists($obj, $linha['estrategia_observador_metodo'])){
					$obj->{$linha['estrategia_observador_metodo']}();
					}
				}
			}
		}


	public function notificar( $post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('estrategias');
		$sql->adCampo('pg_estrategia_nome');
		$sql->adOnde('pg_estrategia_id ='.$this->pg_estrategia_id);
		$nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['estrategias_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['estrategias_usuarios'].')');
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
			$sql->esqUnir('estrategias', 'estrategias', 'estrategias.pg_estrategia_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('pg_estrategia_id='.$this->pg_estrategia_id);
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
		elseif (isset($post['pg_estrategia_id']) && $post['pg_estrategia_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo=ucfirst($config['iniciativa']).' excluíd'.$config['genero_iniciativa'];
				elseif ($tipo=='atualizado') $titulo=ucfirst($config['iniciativa']).' atualizad'.$config['genero_iniciativa'];
				else $titulo=ucfirst($config['iniciativa']).' inserid'.$config['genero_iniciativa'];

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizad'.$config['genero_iniciativa'].' '.$config['genero_iniciativa'].' '.$config['iniciativa'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluíd'.$config['genero_iniciativa'].' '.$config['genero_iniciativa'].' '.$config['iniciativa'].': '.$nome.'<br>';
				else $corpo = 'Inserid'.$config['genero_iniciativa'].' '.$config['genero_iniciativa'].' '.$config['iniciativa'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão d'.$config['genero_iniciativa'].' '.$config['iniciativa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição d'.$config['genero_iniciativa'].' '.$config['iniciativa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador d'.$config['genero_iniciativa'].' '.$config['iniciativa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=estrategia_ver&pg_estrategia_id='.$this->pg_estrategia_id.'\');"><b>Clique para acessar '.$config['genero_iniciativa'].' '.$config['iniciativa'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=estrategia_ver&pg_estrategia_id='.$this->pg_estrategia_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_iniciativa'].' '.$config['iniciativa'].'</b></a>';
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



	public function fisico_previsto($data=null){
		if ($this->pg_estrategia_tipo_pontuacao=='media_ponderada'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			
			$sql = new BDConsulta;
			$sql->adTabela('estrategia_media');
			$sql->adCampo('estrategia_media_projeto, estrategia_media_acao, estrategia_media_peso');
			$sql->adOnde('estrategia_media_estrategia ='.(int)$this->pg_estrategia_id);
			$sql->adOnde('estrategia_media_tipo =\''.$this->pg_estrategia_tipo_pontuacao.'\'');
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['estrategia_media_projeto']){
					$obj = new CProjeto();
					$obj->load($linha['estrategia_media_projeto']);
					$numerador+=$obj->fisico_previsto($data)*$linha['estrategia_media_peso'];
					$denominador+=$linha['estrategia_media_peso'];
					}
				elseif ($linha['estrategia_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['estrategia_media_acao']);
					$numerador+=$obj->fisico_previsto($data)*$linha['estrategia_media_peso'];
					$denominador+=$linha['estrategia_media_peso'];
					}
				}
	
			return ($denominador ? $numerador/$denominador : 0);
			}
		elseif ($this->pg_estrategia_tipo_pontuacao=='pontos_parcial' || $this->pg_estrategia_tipo_pontuacao=='pontos_completos'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			
			$sql = new BDConsulta;
			$sql->adTabela('estrategia_media');
			$sql->adCampo('estrategia_media_projeto, estrategia_media_acao, estrategia_media_ponto');
			$sql->adOnde('estrategia_media_estrategia ='.(int)$this->pg_estrategia_id);
			$sql->adOnde('estrategia_media_tipo =\''.$this->pg_estrategia_tipo_pontuacao.'\'');
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['estrategia_media_projeto']){
					$obj = new CProjeto();
					$obj->load($linha['estrategia_media_projeto']);
					$numerador+=$obj->fisico_previsto($data)*$linha['estrategia_media_ponto'];
					$denominador+=$linha['estrategia_media_ponto'];
					}
				elseif ($linha['estrategia_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['estrategia_media_acao']);
					$numerador+=$obj->fisico_previsto($data)*$linha['estrategia_media_ponto'];
					$denominador+=$linha['estrategia_media_ponto'];
					}
				}
			return ($denominador ? $numerador/$denominador : 0);
			}
		else return $this->pg_estrategia_percentagem;
		}



	public function fisico_executado($data=null){
		if ($this->pg_estrategia_tipo_pontuacao=='media_ponderada'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			
			$sql = new BDConsulta;
			$sql->adTabela('estrategia_media');
			$sql->adCampo('estrategia_media_projeto, estrategia_media_acao, estrategia_media_peso');
			$sql->adOnde('estrategia_media_estrategia ='.(int)$this->pg_estrategia_id);
			$sql->adOnde('estrategia_media_tipo =\''.$this->pg_estrategia_tipo_pontuacao.'\'');
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['estrategia_media_projeto']){
					$obj = new CProjeto(null, null, $data, $linha['estrategia_media_projeto']);
					$obj->load($linha['estrategia_media_projeto']);
					$numerador+=$obj->projeto_percentagem*$linha['estrategia_media_peso'];
					$denominador+=$linha['estrategia_media_peso'];
					}
				elseif ($linha['estrategia_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['estrategia_media_acao']);
					$numerador+=$obj->plano_acao_percentagem*$linha['estrategia_media_peso'];
					$denominador+=$linha['estrategia_media_peso'];
					}
				}
	
			return ($denominador ? $numerador/$denominador : 0);
			}
		elseif ($this->pg_estrategia_tipo_pontuacao=='pontos_parcial' || $this->pg_estrategia_tipo_pontuacao=='pontos_completos'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			
			$sql = new BDConsulta;
			$sql->adTabela('estrategia_media');
			$sql->adCampo('estrategia_media_projeto, estrategia_media_acao, estrategia_media_ponto');
			$sql->adOnde('estrategia_media_estrategia ='.(int)$this->pg_estrategia_id);
			$sql->adOnde('estrategia_media_tipo =\''.$this->pg_estrategia_tipo_pontuacao.'\'');
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['estrategia_media_projeto']){
					$obj = new CProjeto(null, null, $data, $linha['estrategia_media_projeto']);
					$obj->load($linha['estrategia_media_projeto']);
					$numerador+=$obj->projeto_percentagem*$linha['estrategia_media_ponto'];
					$denominador+=$linha['estrategia_media_ponto'];
					}
				elseif ($linha['estrategia_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['estrategia_media_acao']);
					$numerador+=$obj->plano_acao_percentagem*$linha['estrategia_media_ponto'];
					$denominador+=$linha['estrategia_media_ponto'];
					}
				}
			return ($denominador ? $numerador/$denominador : 0);
			}
		else return $this->pg_estrategia_percentagem;
		}







	public function fisico_velocidade($data=null){
		$fisico_previsto=$this->fisico_previsto($data);
		$fisico_executado=$this->fisico_executado($data);
		return ($fisico_previsto ? $fisico_executado/$fisico_previsto : 0);
		}


	}


?>