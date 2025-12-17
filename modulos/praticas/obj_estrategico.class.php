<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


class CObjetivo extends CAplicObjeto {

	public $objetivo_id = null;
  public $objetivo_cia = null;
  public $objetivo_dept = null;
  public $objetivo_nome = null;
  public $objetivo_data = null;
  public $objetivo_usuario = null;
  public $objetivo_ordem = null;
  public $objetivo_acesso = null;
  public $objetivo_perspectiva = null;
  public $objetivo_tema = null;
  public $objetivo_indicador = null;
  public $objetivo_cor = null;
  public $objetivo_oque = null;
  public $objetivo_descricao = null;
  public $objetivo_onde = null;
  public $objetivo_quando = null;
  public $objetivo_como = null;
  public $objetivo_porque = null;
  public $objetivo_quanto = null;
  public $objetivo_quem = null;
  public $objetivo_controle = null;
  public $objetivo_melhorias = null;
  public $objetivo_metodo_aprendizado = null;
  public $objetivo_desde_quando = null;
  public $objetivo_ativo = null;
  public $objetivo_tipo = null;
	public $objetivo_percentagem = null;
	public $objetivo_tipo_pontuacao = null;
	public $objetivo_ponto_alvo = null;
	public $objetivo_aprovado = null;
	public $objetivo_moeda = null;
	
	public function __construct() {
		parent::__construct('objetivo', 'objetivo_id');
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->objetivo_id) {
			$ret = $sql->atualizarObjeto('objetivo', $this, 'objetivo_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('objetivo', $this, 'objetivo_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));

		$campos_customizados = new CampoCustomizados('objetivos', $this->objetivo_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->objetivo_id);

		$objetivo_usuarios=getParam($_REQUEST, 'objetivo_usuarios', null);
		$objetivo_usuarios=explode(',', $objetivo_usuarios);
		$sql->setExcluir('objetivo_usuario');
		$sql->adOnde('objetivo_usuario_objetivo = '.$this->objetivo_id);
		$sql->exec();
		$sql->limpar();
		foreach($objetivo_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('objetivo_usuario');
				$sql->adInserir('objetivo_usuario_objetivo', $this->objetivo_id);
				$sql->adInserir('objetivo_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'objetivo_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('objetivo_dept');
		$sql->adOnde('objetivo_dept_objetivo = '.$this->objetivo_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('objetivo_dept');
				$sql->adInserir('objetivo_dept_objetivo', $this->objetivo_id);
				$sql->adInserir('objetivo_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}
			
		if ($Aplic->profissional){
			$sql->setExcluir('objetivo_cia');
			$sql->adOnde('objetivo_cia_objetivo='.(int)$this->objetivo_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'objetivo_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('objetivo_cia');
						$sql->adInserir('objetivo_cia_objetivo', $this->objetivo_id);
						$sql->adInserir('objetivo_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}
		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('objetivo_gestao');
			$sql->adAtualizar('objetivo_gestao_objetivo', (int)$this->objetivo_id);
			$sql->adAtualizar('objetivo_gestao_uuid', null);
			$sql->adOnde('objetivo_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}
		if ($uuid){
			$sql->adTabela('objetivo_media');
			$sql->adAtualizar('objetivo_media_objetivo', (int)$this->objetivo_id);
			$sql->adAtualizar('objetivo_media_uuid', null);
			$sql->adOnde('objetivo_media_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('plano_acao_observador');
			$sql->adAtualizar('plano_acao_observador_objetivo', (int)$this->objetivo_id);
			$sql->adAtualizar('plano_acao_observador_uuid', null);
			$sql->adOnde('plano_acao_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('projeto_observador');
			$sql->adAtualizar('projeto_observador_objetivo', (int)$this->objetivo_id);
			$sql->adAtualizar('projeto_observador_uuid', null);
			$sql->adOnde('projeto_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('fator_observador');
			$sql->adAtualizar('fator_observador_objetivo', (int)$this->objetivo_id);
			$sql->adAtualizar('fator_observador_uuid', null);
			$sql->adOnde('fator_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			
			
			
			
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_objetivo', (int)$this->objetivo_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_objetivo', (int)$this->objetivo_id);
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
			$sql->adOnde('assinatura_objetivo='.(int)$this->objetivo_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_objetivo='.(int)$this->objetivo_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_objetivo='.(int)$this->objetivo_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('objetivo');
			$sql->adAtualizar('objetivo_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('objetivo_id='.(int)$this->objetivo_id);
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
		$valor=permiteAcessarObjetivo($this->objetivo_acesso, $this->objetivo_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarObjetivo($this->objetivo_acesso, $this->objetivo_id);
		return $valor;
		}

	public function calculo_percentagem(){
		$tipo=$this->objetivo_tipo_pontuacao;

		$sql = new BDConsulta;
		$porcentagem=null;
		if (!$tipo) $porcentagem=$this->objetivo_percentagem;
		elseif($tipo=='media_ponderada'){
			$sql->adTabela('objetivo_media');
			$sql->esqUnir('me', 'me', 'me_id=objetivo_media_me');
			$sql->esqUnir('fator', 'fator', 'fator_id=objetivo_media_fator');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=objetivo_media_estrategia');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=objetivo_media_projeto');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=objetivo_media_acao');
			$sql->adCampo('
			fator_percentagem,
			me_percentagem,
			pg_estrategia_percentagem,
			projeto_percentagem,
			plano_acao_percentagem,
			objetivo_media_fator,
			objetivo_media_me,
			objetivo_media_estrategia,
			objetivo_media_projeto,
			objetivo_media_acao,
			objetivo_media_peso
			');

			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\'media_ponderada\'');
			$lista = $sql->lista();
			$sql->limpar();
			$numerador=0;
			$denominador=0;

			foreach($lista as $linha){
				if ($linha['objetivo_media_fator']) $numerador+=($linha['fator_percentagem']*$linha['objetivo_media_peso']);
				elseif ($linha['objetivo_media_me']) $numerador+=($linha['me_percentagem']*$linha['objetivo_media_peso']);
				elseif ($linha['objetivo_media_estrategia']) $numerador+=($linha['pg_estrategia_percentagem']*$linha['objetivo_media_peso']);
				elseif ($linha['objetivo_media_projeto']) $numerador+=($linha['projeto_percentagem']*$linha['objetivo_media_peso']);
				elseif ($linha['objetivo_media_acao']) $numerador+=($linha['plano_acao_percentagem']*$linha['objetivo_media_peso']);
				$denominador+=$linha['objetivo_media_peso'];
				}
			$porcentagem=($denominador ? $numerador/$denominador : 0);
			}
		elseif($tipo=='pontos_completos'){
			$sql->adTabela('objetivo_media');
			$sql->esqUnir('fator', 'fator', 'fator_id=objetivo_media_fator');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('fator_percentagem = 100');
			$sql->adOnde('objetivo_media_fator > 0');
			$pontos1 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('me', 'me', 'me_id=objetivo_media_me');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('me_percentagem = 100');
			$sql->adOnde('objetivo_media_me > 0');
			$pontos2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=objetivo_media_estrategia');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('pg_estrategia_percentagem = 100');
			$sql->adOnde('objetivo_media_estrategia > 0');
			$pontos3 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=objetivo_media_projeto');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('projeto_percentagem = 100');
			$sql->adOnde('objetivo_media_projeto > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=objetivo_media_acao');
			$sql->adCampo('SUM(objetivo_media_ponto)');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_completos\'');
			$sql->adOnde('plano_acao_percentagem = 100');
			$sql->adOnde('objetivo_media_acao > 0');
			$pontos5 = $sql->Resultado();
			$sql->limpar();


			$porcentagem=($this->objetivo_ponto_alvo != 0 ? (($pontos1+$pontos2+$pontos3+$pontos4+$pontos5)/$this->objetivo_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='pontos_parcial'){
		
			$sql->adTabela('objetivo_media');
			$sql->esqUnir('fator', 'fator', 'fator_id=objetivo_media_fator');
			$sql->adCampo('SUM(objetivo_media_ponto*(fator_percentagem/100))');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('objetivo_media_fator > 0');
			$pontos1 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('me', 'me', 'me_id=objetivo_media_me');
			$sql->adCampo('SUM(objetivo_media_ponto*(me_percentagem/100))');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('objetivo_media_me > 0');
			$pontos2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=objetivo_media_estrategia');
			$sql->adCampo('SUM(objetivo_media_ponto*(pg_estrategia_percentagem/100))');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('objetivo_media_estrategia > 0');
			$pontos3 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=objetivo_media_projeto');
			$sql->adCampo('SUM(objetivo_media_ponto*(projeto_percentagem/100))');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('objetivo_media_projeto > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivo_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=objetivo_media_acao');
			$sql->adCampo('SUM(objetivo_media_ponto*(plano_acao_percentagem/100))');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('objetivo_media_acao > 0');
			$pontos5 = $sql->Resultado();
			$sql->limpar();

			$porcentagem=($this->objetivo_ponto_alvo != 0 ? (($pontos1+$pontos2+$pontos3+$pontos4+$pontos5)/$this->objetivo_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='indicador'){
			if ($this->objetivo_principal_indicador) {
				include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
				$obj_indicador = new Indicador($this->objetivo_principal_indicador);
				$porcentagem=$obj_indicador->Pontuacao();
				}
			else $porcentagem=0;
			}

		else $porcentagem=0; //caso nao previsto

		if ($porcentagem > 100) $porcentagem=100;
		if ($porcentagem!=$this->objetivo_percentagem){
			$sql->adTabela('objetivo');
			$sql->adAtualizar('objetivo_percentagem', $porcentagem);
			$sql->adOnde('objetivo_id ='.(int)$this->objetivo_id);
			$sql->exec();
			$sql->limpar();
			$this->disparo_observador('fisico');
			}
		return $porcentagem;
		}

	public function disparo_observador( $acao='fisico'){
		//Quem faz uso deste objetivo em cálculos de percentagem
		$sql = new BDConsulta;

		$sql->adTabela('objetivo_observador');
		$sql->adCampo('objetivo_observador.*');
		$sql->adOnde('objetivo_observador_objetivo ='.(int)$this->objetivo_id);
		if ($acao) $sql->adOnde('objetivo_observador_acao =\''.$acao.'\'');
		$lista = $sql->lista();
		$sql->limpar();
		$qnt_perspectiva=0;
		$qnt_tema=0;
		foreach($lista as $linha){
			if ($linha['objetivo_observador_perspectiva']){
				if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
				$obj= new CPerspectiva();
				$obj->load($linha['objetivo_observador_perspectiva']);
				if (method_exists($obj, $linha['objetivo_observador_metodo'])){
					$obj->{$linha['objetivo_observador_metodo']}();
					}
				}
			elseif ($linha['objetivo_observador_tema']){
				if (!($qnt_tema++)) require_once BASE_DIR.'/modulos/praticas/tema.class.php';
				$obj= new CTema();
				$obj->load($linha['objetivo_observador_tema']);
				if (method_exists($obj, $linha['objetivo_observador_metodo'])){
					$obj->{$linha['objetivo_observador_metodo']}();
					}
				}
			}

		}


	public function notificar( $post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('objetivo');
		$sql->adCampo('objetivo_nome');
		$sql->adOnde('objetivo_id ='.$this->objetivo_id);
		$nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if ($post['objetivo_usuarios'] && isset($post['email_designados'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['objetivo_usuarios'].')');
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
			$sql->esqUnir('objetivo', 'objetivo', 'objetivo.objetivo_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('objetivo_id='.$this->objetivo_id);
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
		elseif (isset($post['objetivo_id']) && $post['objetivo_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo=ucfirst($config['objetivo']).' excluíd'.$config['genero_objetivo'];
				elseif ($tipo=='atualizado') $titulo=ucfirst($config['objetivo']).' atualizad'.$config['genero_objetivo'];
				else $titulo=ucfirst($config['objetivo']).' inserid'.$config['genero_objetivo'];

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizad'.$config['genero_objetivo'].' '.$config['genero_objetivo'].' '.$config['objetivo'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Excluíd'.$config['genero_objetivo'].' '.$config['genero_objetivo'].' '.$config['objetivo'].': '.$nome.'<br>';
				else $corpo = 'Inserid'.$config['genero_objetivo'].' '.$config['genero_objetivo'].' '.$config['objetivo'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Responsável pela exclusão d'.$config['genero_objetivo'].' '.$config['objetivo'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Responsável pela edição d'.$config['genero_objetivo'].' '.$config['objetivo'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador d'.$config['genero_objetivo'].' '.$config['objetivo'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=obj_estrategico_ver&objetivo_id='.$this->objetivo_id.'\');"><b>Clique para acessar '.$config['genero_objetivo'].' '.$config['objetivo'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=obj_estrategico_ver&objetivo_id='.$this->objetivo_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_objetivo'].' '.$config['objetivo'].'</b></a>';
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
		if ($this->objetivo_tipo_pontuacao=='media_ponderada'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
			require_once BASE_DIR.'/modulos/praticas/fator.class.php';
			require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
			$sql = new BDConsulta;
			$sql->adTabela('objetivo_media');
			$sql->adCampo('objetivo_media_projeto, objetivo_media_acao, objetivo_media_estrategia, objetivo_media_fator, objetivo_media_me, objetivo_media_peso');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\''.$this->objetivo_tipo_pontuacao.'\'');
			
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['objetivo_media_projeto']){
					$obj = new CProjeto();
					$obj->load($linha['objetivo_media_projeto']);
					$numerador+=$obj->fisico_previsto($data)*$linha['objetivo_media_peso'];
					$denominador+=$linha['objetivo_media_peso'];
					}
				elseif ($linha['objetivo_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['objetivo_media_acao']);
					$numerador+=$obj->fisico_previsto($data)*$linha['objetivo_media_peso'];
					$denominador+=$linha['objetivo_media_peso'];
					}
				elseif ($linha['objetivo_media_estrategia']){
					$obj = new CEstrategia();
					$obj->load($linha['objetivo_media_estrategia']);
					$numerador+=$obj->fisico_previsto($data)*$linha['objetivo_media_peso'];
					$denominador+=$linha['objetivo_media_peso'];
					}	
					
				elseif ($linha['objetivo_media_fator']){
					$obj = new CFator();
					$obj->load($linha['objetivo_media_fator']);
					$numerador+=$obj->fisico_previsto($data)*$linha['objetivo_media_peso'];
					$denominador+=$linha['objetivo_media_peso'];
					}			
				elseif ($linha['objetivo_media_me']){
					$obj = new CMe();
					$obj->load($linha['objetivo_media_me']);
					$numerador+=$obj->fisico_previsto($data)*$linha['objetivo_media_peso'];
					$denominador+=$linha['objetivo_media_peso'];
					}			
		
				}
			return ($denominador ? $numerador/$denominador : 0);
			}
		elseif ($this->objetivo_tipo_pontuacao=='pontos_parcial' || $this->objetivo_tipo_pontuacao=='pontos_completos'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
			require_once BASE_DIR.'/modulos/praticas/fator.class.php';
			require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
			$sql = new BDConsulta;
			$sql->adTabela('objetivo_media');
			$sql->adCampo('objetivo_media_projeto, objetivo_media_acao, objetivo_media_estrategia, objetivo_media_fator, objetivo_media_me, objetivo_media_ponto');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\''.$this->objetivo_tipo_pontuacao.'\'');
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['objetivo_media_projeto']){
					$obj = new CProjeto();
					$obj->load($linha['objetivo_media_projeto']);
					$numerador+=$obj->fisico_previsto($data)*$linha['objetivo_media_ponto'];
					$denominador+=$linha['objetivo_media_ponto'];
					}
				elseif ($linha['objetivo_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['objetivo_media_acao']);
					$numerador+=$obj->fisico_previsto($data)*$linha['objetivo_media_ponto'];
					$denominador+=$linha['objetivo_media_ponto'];
					}
				elseif ($linha['objetivo_media_estrategia']){
					$obj = new CEstrategia();
					$obj->load($linha['objetivo_media_estrategia']);
					$numerador+=$obj->fisico_previsto($data)*$linha['objetivo_media_ponto'];
					$denominador+=$linha['objetivo_media_ponto'];
					}	
				elseif ($linha['objetivo_media_fator']){
					$obj = new CFator();
					$obj->load($linha['objetivo_media_fator']);
					$numerador+=$obj->fisico_previsto($data)*$linha['objetivo_media_ponto'];
					$denominador+=$linha['objetivo_media_ponto'];
					}			
				elseif ($linha['objetivo_media_me']){
					$obj = new CMe();
					$obj->load($linha['objetivo_media_me']);
					$numerador+=$obj->fisico_previsto($data)*$linha['objetivo_media_ponto'];
					$denominador+=$linha['objetivo_media_ponto'];
					}				
					
						
				}
			return ($denominador ? $numerador/$denominador : 0);
			}	
		else return $this->objetivo_percentagem;	
		}




	public function fisico_executado($data=null){
		if ($this->objetivo_tipo_pontuacao=='media_ponderada'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
			require_once BASE_DIR.'/modulos/praticas/fator.class.php';
			require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
			$sql = new BDConsulta;
			$sql->adTabela('objetivo_media');
			$sql->adCampo('objetivo_media_projeto, objetivo_media_acao, objetivo_media_estrategia, objetivo_media_fator, objetivo_media_me, objetivo_media_peso');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\''.$this->objetivo_tipo_pontuacao.'\'');
			
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['objetivo_media_projeto']){
					$obj = new CProjeto(null, null, $data, $linha['objetivo_media_peso']);
					$obj->load($linha['objetivo_media_projeto']);

					$numerador+=$obj->projeto_percentagem*$linha['objetivo_media_peso'];
					$denominador+=$linha['objetivo_media_peso'];
					}
				elseif ($linha['objetivo_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['objetivo_media_acao']);
					$numerador+=$obj->plano_acao_percentagem*$linha['objetivo_media_peso'];
					$denominador+=$linha['objetivo_media_peso'];
					}
				elseif ($linha['objetivo_media_estrategia']){
					$obj = new CEstrategia();
					$obj->load($linha['objetivo_media_estrategia']);
					$numerador+=$obj->fisico_executado($data)*$linha['objetivo_media_peso'];
					$denominador+=$linha['objetivo_media_peso'];
					}	
					
				elseif ($linha['objetivo_media_fator']){
					$obj = new CFator();
					$obj->load($linha['objetivo_media_fator']);
					$numerador+=$obj->fisico_executado($data)*$linha['objetivo_media_peso'];
					$denominador+=$linha['objetivo_media_peso'];
					}			
				elseif ($linha['objetivo_media_me']){
					$obj = new CMe();
					$obj->load($linha['objetivo_media_me']);
					$numerador+=$obj->fisico_executado($data)*$linha['objetivo_media_peso'];
					$denominador+=$linha['objetivo_media_peso'];
					}			
		
				}
			return ($denominador ? $numerador/$denominador : 0);
			}
		elseif ($this->objetivo_tipo_pontuacao=='pontos_parcial' || $this->objetivo_tipo_pontuacao=='pontos_completos'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
			require_once BASE_DIR.'/modulos/praticas/fator.class.php';
			require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
			$sql = new BDConsulta;
			$sql->adTabela('objetivo_media');
			$sql->adCampo('objetivo_media_projeto, objetivo_media_acao, objetivo_media_estrategia, objetivo_media_fator, objetivo_media_me, objetivo_media_ponto');
			$sql->adOnde('objetivo_media_objetivo ='.(int)$this->objetivo_id);
			$sql->adOnde('objetivo_media_tipo =\''.$this->objetivo_tipo_pontuacao.'\'');
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['objetivo_media_projeto']){
					$obj = new CProjeto(null, null, $data, $linha['objetivo_media_projeto']);
					$obj->load($linha['objetivo_media_projeto']);
					$numerador+=$obj->projeto_percentagem*$linha['objetivo_media_ponto'];
					$denominador+=$linha['objetivo_media_ponto'];
					}
				elseif ($linha['objetivo_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['objetivo_media_acao']);
					$numerador+=$obj->plano_acao_percentagem*$linha['objetivo_media_ponto'];
					$denominador+=$linha['objetivo_media_ponto'];
					}
				elseif ($linha['objetivo_media_estrategia']){
					$obj = new CEstrategia();
					$obj->load($linha['objetivo_media_estrategia']);
					$numerador+=$obj->fisico_executado($data)*$linha['objetivo_media_ponto'];
					$denominador+=$linha['objetivo_media_ponto'];
					}	
				elseif ($linha['objetivo_media_fator']){
					$obj = new CFator();
					$obj->load($linha['objetivo_media_fator']);
					$numerador+=$obj->fisico_executado($data)*$linha['objetivo_media_ponto'];
					$denominador+=$linha['objetivo_media_ponto'];
					}			
				elseif ($linha['objetivo_media_me']){
					$obj = new CMe();
					$obj->load($linha['objetivo_media_me']);
					$numerador+=$obj->fisico_executado($data)*$linha['objetivo_media_ponto'];
					$denominador+=$linha['objetivo_media_ponto'];
					}				
					
						
				}
			return ($denominador ? $numerador/$denominador : 0);
			}	
		else return $this->objetivo_percentagem;	
		}





	public function fisico_velocidade($data=null){
		$fisico_previsto=$this->fisico_previsto($data);
		return ($fisico_previsto ? $this->objetivo_percentagem/$fisico_previsto : 0);
		}
	
	
	
	

	}

?>