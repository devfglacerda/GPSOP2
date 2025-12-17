<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

require_once ($Aplic->getClasseSistema('libmail'));
//require_once ($Aplic->getClasseSistema('aplic'));
//require_once ($Aplic->getClasseSistema('data'));

class CArquivo extends CAplicObjeto {
	public $arquivo_id = null;
	public $arquivo_cia = null;
	public $arquivo_dept = null;
	public $arquivo_dono = null;
	public $arquivo_usuario_upload = null;
	public $arquivo_pasta = null;
	public $chave_publica = null;
	public $arquivo_projeto = null;
	public $arquivo_tarefa = null;
	public $arquivo_pratica = null;
	public $arquivo_acao = null;
	public $arquivo_indicador = null;
	public $arquivo_usuario = null;
	public $arquivo_objetivo = null;
	public $arquivo_perspectiva = null;
	public $arquivo_tema = null;
	public $arquivo_fator = null;
	public $arquivo_estrategia = null;
	public $arquivo_meta = null;
	public $arquivo_demanda = null;
	public $arquivo_instrumento = null;
	public $arquivo_calendario = null;
	public $arquivo_ata = null;
	public $arquivo_canvas = null;
	public $arquivo_versao_id = null;
	public $arquivo_categoria = null;
	public $arquivo_nome = null;
	public $arquivo_nome_real = null;
	public $arquivo_local = null;
	public $arquivo_descricao = null;
	public $arquivo_acesso = null;
	public $assinatura = null;
	public $arquivo_data = null;
	public $arquivo_tipo = null;
	public $arquivo_versao = null;
	public $arquivo_saida = null;
	public $arquivo_motivo_saida = null;
	public $arquivo_icone = null;
	public $arquivo_tamanho = null;
	public $arquivo_cor = null;
	public $arquivo_ativo = null;
	public $arquivo_principal_indicador = null;
	public $arquivo_moeda = null;
	public $arquivo_aprovado = null;
	

	public function __construct() {
		global $Aplic;
		parent::__construct('arquivo', 'arquivo_id');
		}

	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$msg = $this->check();
		if ($msg) return 'CArquivo::checagem para armazenar falhou '.$msg;
		$sql = new BDConsulta();
		if ($this->arquivo_id) {
			$ret = $sql->atualizarObjeto('arquivo', $this, 'arquivo_id');
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('arquivo', $this, 'arquivo_id');
			$sql->limpar();
			}


		$arquivo_usuarios=getParam($_REQUEST, 'arquivo_usuarios', null);
		$arquivo_usuarios=explode(',', $arquivo_usuarios);
		$sql->setExcluir('arquivo_usuario');
		$sql->adOnde('arquivo_usuario_arquivo = '.$this->arquivo_id);
		$sql->exec();
		$sql->limpar();
		foreach($arquivo_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('arquivo_usuario');
				$sql->adInserir('arquivo_usuario_arquivo', $this->arquivo_id);
				$sql->adInserir('arquivo_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'arquivo_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('arquivo_dept');
		$sql->adOnde('arquivo_dept_arquivo = '.$this->arquivo_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('arquivo_dept');
				$sql->adInserir('arquivo_dept_arquivo', $this->arquivo_id);
				$sql->adInserir('arquivo_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		
			$sql->setExcluir('arquivo_cia');
			$sql->adOnde('arquivo_cia_arquivo='.(int)$this->arquivo_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'arquivo_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('arquivo_cia');
						$sql->adInserir('arquivo_cia_arquivo', $this->arquivo_id);
						$sql->adInserir('arquivo_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			

		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('arquivo_gestao');
			$sql->adAtualizar('arquivo_gestao_arquivo', (int)$this->arquivo_id);
			$sql->adAtualizar('arquivo_gestao_uuid', null);
			$sql->adOnde('arquivo_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_arquivo', (int)$this->arquivo_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_arquivo', (int)$this->arquivo_id);
			$sql->adAtualizar('priorizacao_uuid', null);
			$sql->adOnde('priorizacao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();		
			}
		
		//verificar aprovacao
		
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_arquivo='.(int)$this->arquivo_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_arquivo='.(int)$this->arquivo_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_arquivo='.(int)$this->arquivo_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('arquivo');
			$sql->adAtualizar('arquivo_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('arquivo_id='.(int)$this->arquivo_id);
			$sql->exec();
			$sql->limpar();
			

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('arquivo', $this->arquivo_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->arquivo_id);
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	public function check() {
		$this->arquivo_versao_id = intval($this->arquivo_versao_id);
		return null;
		}
	public function retirada( $usuarioId, $arquivoId, $coRazao) {
		$q = new BDConsulta;
		$q->adTabela('arquivo');
		$q->adAtualizar('arquivo_saida', $usuarioId);
		$q->adAtualizar('arquivo_motivo_saida', $coRazao);
		$q->adOnde('arquivo_id = '.(int)$arquivoId);
		$q->exec();
		$q->limpar();
		return true;
		}
	public function excluir( $oid = NULL) {
		global $Aplic;
		if (!$this->podeExcluir($msg)) return $msg;
		$this->_mensagem = 'excluido';
	
		if ($Aplic->getEstado('arquivo_id', null)==$this->arquivo_id) $Aplic->setEstado('arquivo_id', null);
		parent::excluir();
		return null;
		}
	public function excluirArquivo() {
		//ERRO corrigir depois
		global $config;
		$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
		}

	public function versaoArquivo(){


		}


	public function moverArquivo( $nomereal, $projetoAntigo='', $praticaAntiga='', $indicadorAntigo='', $usuarioAntigo='', $objetivoAntigo='', $estrategiaAntigo='', $acaoAntigo='', $fatorAntigo='', $metaAntigo='', $perspectivaAntigo='', $temaAntigo='', $demandaAntiga='', $calendarioAntigo='', $ataAntiga='', $instrumentoAntigo='', $canvasAntigo='') {
		global $Aplic, $config;
		$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
		//ERRO corrigir depois

		if ($this->arquivo_projeto) {$pasta='projetos'; $chave=$this->arquivo_projeto;}
		elseif ($this->arquivo_pratica) {$pasta='praticas'; $chave=$this->arquivo_pratica;}
		elseif ($this->arquivo_demanda) {$pasta='demandas'; $chave=$this->arquivo_demanda;}
		elseif ($this->arquivo_instrumento) {$pasta='instrumentos'; $chave=$this->arquivo_instrumento;}
		elseif ($this->arquivo_indicador) {$pasta='indicadores'; $chave=$this->arquivo_indicador;}
		elseif ($this->arquivo_perspectiva) {$pasta='perspectivas'; $chave=$this->arquivo_perspectiva;}
		elseif ($this->arquivo_canvas) {$pasta='canvas'; $chave=$this->arquivo_canvas;}
		elseif ($this->arquivo_tema) {$pasta='temas'; $chave=$this->arquivo_tema;}
		elseif ($this->arquivo_objetivo) {$pasta='objetivos'; $chave=$this->arquivo_objetivo;}
		elseif ($this->arquivo_estrategia) {$pasta='estrategias'; $chave=$this->arquivo_estrategia;}
		elseif ($this->arquivo_fator) {$pasta='fatores'; $chave=$this->arquivo_fator;}
		elseif ($this->arquivo_meta) {$pasta='metas'; $chave=$this->arquivo_meta;}
		elseif ($this->arquivo_calendario) {$pasta='calendarios'; $chave=$this->arquivo_calendario;}
		elseif ($this->arquivo_ata) {$pasta='atas'; $chave=$this->arquivo_ata;}
		elseif ($this->arquivo_usuario) {$pasta='usuarios'; $chave=$this->arquivo_usuario;}
		elseif ($this->arquivo_acao) {$pasta='planos_acao'; $chave=$this->arquivo_acao;}
		else {$pasta='generico'; $chave=$Aplic->usuario_cia;}

		if ($projetoAntigo) {$pasta_antiga='projetos'; $chave_antiga=$projetoAntigo;}
		elseif ($praticaAntiga) {$pasta_antiga='praticas'; $chave_antiga=$praticaAntiga;}
		elseif ($demandaAntiga) {$pasta_antiga='demandas'; $chave_antiga=$demandaAntiga;}
		elseif ($instrumentoAntigo) {$pasta_antiga='instrumentos'; $chave_antiga=$instrumentoAntigo;}
		elseif ($indicadorAntigo) {$pasta_antiga='indicadores'; $chave_antiga=$indicadorAntigo;}
		elseif ($usuarioAntigo) {$pasta_antiga='usuarios'; $chave_antiga=$usuarioAntigo;}
		elseif ($perspectivaAntigo) {$pasta_antiga='perspectivas'; $chave_antiga=$perspectivaAntigo;}
		elseif ($canvasAntigo) {$pasta_antiga='canvas'; $chave_antiga=$canvasAntigo;}
		elseif ($temaAntigo) {$pasta_antiga='temas'; $chave_antiga=$temaAntigo;}
		elseif ($objetivoAntigo) {$pasta_antiga='objetivos'; $chave_antiga=$objetivoAntigo;}
		elseif ($estrategiaAntigo) {$pasta_antiga='estrategias'; $chave_antiga=$estrategiaAntigo;}
		elseif ($fatorAntigo) {$pasta_antiga='fatores'; $chave_antiga=$fatorAntigo;}
		elseif ($metaAntigo) {$pasta_antiga='metas'; $chave_antiga=$metaAntigo;}
		elseif ($acaoAntigo) {$pasta_antiga='planos_acao'; $chave_antiga=$acaoAntigo;}
		elseif ($calendarioAntigo) {$pasta_antiga='calendarios'; $chave_antiga=$calendarioAntigo;}
		elseif ($ataAntiga) {$pasta_antiga='atas'; $chave_antiga=$ataAntiga;}
		else {$pasta_antiga='generico'; $chave_antiga=$Aplic->usuario_cia;}

		if (!is_dir($base_dir)){
			$res = mkdir($base_dir, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões na raiz de '.$base_dir, UI_MSG_ALERTA);
				return false;
				}
			}

		if (!is_dir($base_dir.'/arquivos')){
			$res = mkdir($base_dir.'/arquivos', 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\.', UI_MSG_ALERTA);
				return false;
				}
			}

	 	if (!is_dir($base_dir.'/arquivos/'.$pasta)){
			$res = mkdir($base_dir.'/arquivos/'.$pasta, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\\'.$pasta, UI_MSG_ALERTA);
				return false;
				}
			}


		if (!is_dir($base_dir.'/arquivos/'.$pasta.'/'.$chave)){
			$res = mkdir($base_dir.'/arquivos/'.$pasta.'/'.$chave, 0777);
			if (!$res) {
				$Aplic->setMsg('A pasta para arquivos enviados não foi configurada para receber arquivos - mude as permissões no diretório/arquivo.', UI_MSG_ALERTA);
				return false;
				}
			}
		$res = rename($base_dir.'/arquivos/'.$pasta_antiga.'/'.$chave_antiga.'/'.$nomereal, $base_dir.'/arquivos/'.$pasta.'/'.$chave.'/'.$nomereal);
		if (!$res) return false;
		return true;
		}

	public function duplicarArquivo( $id_antigo, $nomereal) {
		global $Aplic, $config;
		//ERRO corrigir depois
		$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
		if ($this->arquivo_projeto) $pasta='projetos';
		elseif ($this->arquivo_pratica) $pasta='praticas';
		elseif ($this->arquivo_demanda) $pasta='demandas';
		elseif ($this->arquivo_instrumento) $pasta='instrumentos';
		elseif ($this->arquivo_indicador) $pasta='indicadores';
		elseif ($this->arquivo_perspectiva) $pasta='perspectivas';
		elseif ($this->arquivo_canvas) $pasta='canvas';
		elseif ($this->arquivo_tema) $pasta='temas';
		elseif ($this->arquivo_objetivo) $pasta='objetivos';
		elseif ($this->arquivo_estrategia) $pasta='estrategias';
		elseif ($this->arquivo_fator) $pasta='fatores';
		elseif ($this->arquivo_meta) $pasta='metas';
		elseif ($this->arquivo_calendario) $pasta='calendarios';
		elseif ($this->arquivo_ata) $pasta='atas';
		elseif ($this->arquivo_usuario) $pasta='usuarios';
		elseif ($this->arquivo_acao) $pasta='planos_acao';

		if (!$id_antigo){
			if ($this->arquivo_projeto) $id_antigo=$this->arquivo_projeto;
			elseif ($this->arquivo_pratica) $id_antigo=$this->arquivo_pratica;
			elseif ($this->arquivo_demanda) $id_antigo=$this->arquivo_demanda;
			elseif ($this->arquivo_instrumento) $id_antigo=$this->arquivo_instrumento;
			elseif ($this->arquivo_indicador) $id_antigo=$this->arquivo_indicador;
			elseif ($this->arquivo_perspectiva) $id_antigo=$this->arquivo_perspectiva;
			elseif ($this->arquivo_canvas) $id_antigo=$this->arquivo_canvas;
			elseif ($this->arquivo_tema) $id_antigo=$this->arquivo_tema;
			elseif ($this->arquivo_objetivo) $id_antigo=$this->arquivo_objetivo;
			elseif ($this->arquivo_estrategia) $id_antigo=$this->arquivo_estrategia;
			elseif ($this->arquivo_fator) $id_antigo=$this->arquivo_fator;
			elseif ($this->arquivo_meta) $id_antigo=$this->arquivo_meta;
			elseif ($this->arquivo_calendario) $id_antigo=$this->arquivo_calendario;
			elseif ($this->arquivo_ata) $id_antigo=$this->arquivo_ata;
			elseif ($this->arquivo_usuario) $id_antigo=$this->arquivo_usuario;
			elseif ($this->arquivo_acao) $id_antigo=$this->arquivo_acao;
			}
		if (!$nomereal) $nomereal=$this->arquivo_nome_real;


		$dest_nomereal = uniqid(rand());

		$res = copy($base_dir.'/arquivos/'.$pasta.'/'.$id_antigo.'/'.$nomereal, $base_dir.'/arquivos/'.$pasta.'/'.$id_antigo.'/'.$dest_nomereal);

		if (!$res) return false;
		return $dest_nomereal;
		}

	public function moverTemp( $upload) {
		global $Aplic, $config;
		$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

		$dia=date('d');
		$mes=date('m');
		$ano=date('Y');

		if (!is_dir($base_dir)){
			$res = mkdir($base_dir, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões na raiz de '.$base_dir, UI_MSG_ALERTA);
				return false;
				}
			}

		if (!is_dir($base_dir.'/arquivos')){
			$res = mkdir($base_dir.'/arquivos', 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir, UI_MSG_ALERTA);
				return false;
				}
			}

	 	if (!is_dir($base_dir.'/arquivos/'.$ano)){
			$res = mkdir($base_dir.'/arquivos/'.$ano, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta do ano para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos', UI_MSG_ALERTA);
				return false;
				}
			}

		if (!is_dir($base_dir.'/arquivos/'.$ano.'/'.$mes)){
			$res = mkdir($base_dir.'/arquivos/'.$ano.'/'.$mes, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta do mês para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos\\'.$ano, UI_MSG_ALERTA);
				return false;
				}
			}

		if (!is_dir($base_dir.'/arquivos/'.$ano.'/'.$mes.'/'.$dia)){
			$res = mkdir($base_dir.'/arquivos/'.$ano.'/'.$mes.'/'.$dia, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta do dia para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos\\'.$ano.'\\'.$mes, UI_MSG_ALERTA);
				return false;
				}
			}

		$this->arquivo_local=$ano.'/'.$mes.'/'.$dia.'/';

		$this->_filepath = $base_dir.'/arquivos/'.$ano.'/'.$mes.'/'.$dia.'/'.$this->arquivo_nome_real;
		$res = move_uploaded_file($upload['tmp_name'], $this->_filepath);
		if (!$res) return false;
		return true;
		}


	public function notificar($post = array())
{
    global $Aplic, $config, $localidade_tipo_caract;

    require_once($Aplic->getClasseSistema('libmail'));

    $sql = new BDConsulta;

    $sql->adTabela('arquivo');
    $sql->adCampo('arquivo_nome, arquivo_projeto');
    $sql->adOnde('arquivo_id =' . $this->arquivo_id);
    $info_arquivo = $sql->Resultado();

    $nome = $info_arquivo['arquivo_nome'];
    $arquivo_projeto_id = $info_arquivo['arquivo_projeto'];
    $sql->limpar();

    $nome_do_projeto = ''; // Inicializa o nome do projeto como vazio

    if ($arquivo_projeto_id) { // Verifica se há um ID de projeto vinculado ao arquivo
        $sql->adTabela('projetos');
        $sql->adCampo('projeto_nome');
        $sql->adOnde('projeto_id =' . $arquivo_projeto_id);
        $projeto = $sql->Resultado();
        $nome_do_projeto = $projeto['projeto_nome'];
        $sql->limpar();
    }

    $usuarios = array();
    $usuarios1 = array();
    $usuarios2 = array();
    $usuarios3 = array();
    $usuarios4 = array();

    if (isset($post['arquivo_usuarios']) && $post['arquivo_usuarios'] && isset($post['email_designados']) && $post['email_designados']) {
        $sql->adTabela('usuarios');
        $sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
        $sql->adCampo('DISTINCT usuarios.usuario_id, ' . ($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra') . ' AS nome_usuario, contato_email');
        $sql->adOnde('usuario_id IN (' . $post['arquivo_usuarios'] . ')');
        $usuarios1 = $sql->Lista();
        $sql->limpar();
    }
    if (isset($post['email_outro']) && $post['email_outro']) {
        $sql->adTabela('usuarios');
        $sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
        $sql->adCampo('DISTINCT usuarios.usuario_id, ' . ($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra') . ' AS nome_usuario, contato_email');
        $sql->adOnde('contato_id IN (' . $post['email_outro'] . ')');
        $usuarios2 = $sql->Lista();
        $sql->limpar();
    }

    if (isset($post['email_responsavel']) && $post['email_responsavel']) {
        $sql->adTabela('usuarios');
        $sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
        $sql->esqUnir('arquivo', 'arquivo', 'arquivo.arquivo_dono = usuarios.usuario_id');
        $sql->adCampo('DISTINCT usuarios.usuario_id, ' . ($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra') . ' AS nome_usuario, contato_email');
        $sql->adOnde('arquivo_id=' . $this->arquivo_id);
        $usuarios3 = $sql->Lista();
        $sql->limpar();
    }

    if (isset($post['email_extras']) && $post['email_extras']) {
        $extras = explode(',', $post['email_extras']);
        foreach ($extras as $chave => $valor) {
            $usuarios4[] = array('usuario_id' => 0, 'nome_usuario' => '', 'contato_email' => $valor);
        }
    }

    $usuarios = array_merge((array)$usuarios1, (array)$usuarios2);
    $usuarios = array_merge((array)$usuarios, (array)$usuarios3);
    $usuarios = array_merge((array)$usuarios, (array)$usuarios4);

    $usado_usuario = array();
    $usado_email = array();

    if (isset($post['del']) && $post['del']) $tipo = 'excluido';
    elseif (isset($post['arquivo_id']) && $post['arquivo_id']) $tipo = 'atualizado';
    else $tipo = 'incluido';

    foreach ($usuarios as $usuario) {
        if (!isset($usado[$usuario['usuario_id']]) && !isset($usado[$usuario['contato_email']])) {

            if ($usuario['usuario_id']) $usado[$usuario['usuario_id']] = 1;
            if ($usuario['contato_email']) $usado[$usuario['contato_email']] = 1;
            $email = new Mail;
            $email->De($config['email'], $Aplic->usuario_nome);

            if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)) {
                $email->ResponderPara($Aplic->usuario_email);
            } elseif ($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)) {
                $email->ResponderPara($Aplic->usuario_email2);
            }

            if ($tipo == 'excluido') $titulo = 'Arquivo excluído';
            elseif ($tipo == 'atualizado') $titulo = 'Arquivo atualizado';
            else $titulo = 'Arquivo inserido';

            $email->Assunto($titulo, $localidade_tipo_caract);

            if ($tipo == 'atualizado') $corpo = 'Atualizado o arquivo: ' . $nome . '<br>';
            elseif ($tipo == 'excluido') $corpo = 'Excluído o arquivo: ' . $nome . '<br>';
            else $corpo = 'Inserido o arquivo: ' . $nome . '<br>';

            if ($nome_do_projeto) { // Verifica se há um nome de projeto
                $corpo .= '<br><b>Projeto:</b> ' . $nome_do_projeto . '<br>'; // Adiciona o nome do projeto ao corpo do email
            }

            if ($tipo == 'excluido') $corpo .= '<br><br><b>Responsável pela exclusão do arquivo:</b> ' . $Aplic->usuario_posto . ' ' . $Aplic->usuario_nomeguerra;
            elseif ($tipo == 'atualizado') $corpo .= '<br><br><b>Responsável pela edição do arquivo:</b> ' . $Aplic->usuario_posto . ' ' . $Aplic->usuario_nomeguerra;
            else $corpo .= '<br><br><b>Criador do arquivo:</b> ' . $Aplic->usuario_posto . ' ' . $Aplic->usuario_nomeguerra;

			
			
			
            $corpo_interno = $corpo;
            $corpo_externo = $corpo;

            if ($tipo != 'excluido') {
                // Link interno para todos os usuários
                $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=ver&arquivo_id=' . $this->arquivo_id . '\');"><b>Clique para acessar o arquivo</b></a>';
                // Link externo para todos os usuários
                $corpo_externo .= '<br><a href="https://gpsop.sop.ce.gov.br/index.php?m=arquivos&a=ver&arquivo_id=' . $this->arquivo_id . '"><b>Clique para acessar o arquivo</b></a>';
            }

            $email->Corpo($corpo_externo, (isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : $localidade_tipo_caract));
            if ($usuario['usuario_id'] != $Aplic->usuario_id && $usuario['usuario_id']) {
                if ($usuario['usuario_id']) msg_email_interno('', $titulo, $corpo_interno, '', $usuario['usuario_id']);
                if ($email->EmailValido($usuario['contato_email']) && $config['email_ativo']) {
                    $email->Para($usuario['contato_email'], true);
                    $email->Enviar();
                }
            }
        }
    }
}



	public function getResponsavel() {
		$responsavel = '';
		if (!$this->arquivo_dono)	return $responsavel;
		$this->_consulta->limpar();
		$this->_consulta->adTabela('usuarios', 'a');
		$this->_consulta->esqUnir('contatos', 'b', 'b.contato_id = a.usuario_contato');
		$this->_consulta->adCampo('contato_posto, contato_nomeguerra');
		$this->_consulta->adOnde('a.usuario_id = '.(int)$this->arquivo_dono);
		if ($qid = &$this->_consulta->exec()) $responsavel = $qid->fields['contato_posto'].' '.$qid->fields['contato_nomeguerra'];
		$this->_consulta->limpar();
		return $responsavel;
		}
	public function getTarefaNome() {
		$tarefaNome = '';
		if (!$this->arquivo_tarefa)	return $tarefaNome;
		$this->_consulta->limpar();
		$this->_consulta->adTabela('tarefas');
		$this->_consulta->adCampo('tarefa_nome');
		$this->_consulta->adOnde('tarefa_id = '.(int)$this->arquivo_tarefa);
		if ($qid = &$this->_consulta->exec()) {
			if ($qid->fields['tarefa_nome']) $tarefaNome = $qid->fields['tarefa_nome'];
			else $tarefaNome = $qid->fields[0];
			}
		$this->_consulta->limpar();
		return $tarefaNome;
		}

	public function getAcaoNome() {
		$acaoNome = '';
		if (!$this->arquivo_acao)	return $acaoNome;
		$this->_consulta->limpar();
		$this->_consulta->adTabela('plano_acao');
		$this->_consulta->adCampo('plano_acao_nome');
		$this->_consulta->adOnde('plano_acao_id = '.(int)$this->arquivo_acao);
		if ($qid = &$this->_consulta->exec()) {
			if ($qid->fields['plano_acao_nome']) $acaoNome = $qid->fields['plano_acao_nome'];
			else $acaoNome = $qid->fields[0];
			}
		$this->_consulta->limpar();
		return $acaoNome;
		}



	public function podeAcessar() {
		global $Aplic;
		$valor=permiteAcessarArquivo($this->arquivo_acesso, $this->arquivo_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarArquivo($this->arquivo_acesso, $this->arquivo_id);
		return $valor;
		}


	}

class CPastaArquivo extends CAplicObjeto {
	public $arquivo_pasta_id = null;
  public $arquivo_pasta_superior = null;
  public $arquivo_pasta_cia = null;
  public $arquivo_pasta_dept = null;
  public $arquivo_pasta_dono = null;
  public $arquivo_pasta_projeto = null;
  public $arquivo_pasta_tarefa = null;
  public $arquivo_pasta_acesso = null;
  public $arquivo_pasta_pratica = null;
  public $arquivo_pasta_demanda = null;
  public $arquivo_pasta_instrumento = null;
  public $arquivo_pasta_acao = null;
  public $arquivo_pasta_indicador = null;
  public $arquivo_pasta_usuario = null;
  public $arquivo_pasta_perspectiva = null;
  public $arquivo_pasta_tema = null;
  public $arquivo_pasta_objetivo = null;
  public $arquivo_pasta_fator = null;
  public $arquivo_pasta_estrategia = null;
  public $arquivo_pasta_meta = null;
  public $arquivo_pasta_calendario = null;
  public $arquivo_pasta_ata = null;
  public $arquivo_pasta_canvas = null;
  public $arquivo_pasta_nome = null;
  public $arquivo_pasta_descricao = null;
  public $arquivo_pasta_cor = null;
  public $arquivo_pasta_ativo = null;


	public function __construct() {
		parent::__construct('arquivo_pasta', 'arquivo_pasta_id');
		}

	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$msg = $this->check();
		if ($msg) return 'CArquivo::checagem para armazenar falhou '.$msg;
		$sql = new BDConsulta();
		if ($this->arquivo_pasta_id) {
			$ret = $sql->atualizarObjeto('arquivo_pasta', $this, 'arquivo_pasta_id');
		
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('arquivo_pasta', $this, 'arquivo_pasta_id');
			$sql->limpar();
			}


		$arquivo_pasta_usuarios=getParam($_REQUEST, 'arquivo_pasta_usuarios', null);
		$arquivo_pasta_usuarios=explode(',', $arquivo_pasta_usuarios);
		$sql->setExcluir('arquivo_pasta_usuario');
		$sql->adOnde('arquivo_pasta_usuario_pasta = '.$this->arquivo_pasta_id);
		$sql->exec();
		$sql->limpar();
		foreach($arquivo_pasta_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('arquivo_pasta_usuario');
				$sql->adInserir('arquivo_pasta_usuario_pasta', $this->arquivo_pasta_id);
				$sql->adInserir('arquivo_pasta_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'arquivo_pasta_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('arquivo_pasta_dept');
		$sql->adOnde('arquivo_pasta_dept_pasta = '.$this->arquivo_pasta_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('arquivo_pasta_dept');
				$sql->adInserir('arquivo_pasta_dept_pasta', $this->arquivo_pasta_id);
				$sql->adInserir('arquivo_pasta_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		
			$sql->setExcluir('arquivo_pasta_cia');
			$sql->adOnde('arquivo_pasta_cia_pasta='.(int)$this->arquivo_pasta_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'arquivo_pasta_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('arquivo_pasta_cia');
						$sql->adInserir('arquivo_pasta_cia_pasta', $this->arquivo_pasta_id);
						$sql->adInserir('arquivo_pasta_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			


		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('arquivo_pasta_gestao');
			$sql->adAtualizar('arquivo_pasta_gestao_pasta', (int)$this->arquivo_pasta_id);
			$sql->adAtualizar('arquivo_pasta_gestao_uuid', null);
			$sql->adOnde('arquivo_pasta_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
		}	

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	public function check() {
		return null;
		}
	public function excluir( $oid = null) {
		$k = $this->_chave_tabela;
		if ($oid) $this->$k = intval($oid);
		if (!$this->podeExcluir($msg, ($oid ? $oid : $this->arquivo_pasta_id)))	return $msg;
		$this->$k = $this->$k ? $this->$k : intval(($oid ? $oid : $this->arquivo_pasta_id));
		$q = new BDConsulta();
		$q->setExcluir($this->_tbl);
		$q->adOnde($this->_chave_tabela.' = '.$this->$k);
		if (!$q->exec()) {
			$q->limpar();
			return db_error();
			}
		else {
			$q->limpar();
			return null;
			}
		}
	public function podeExcluir(&$msg='', $oid = null, $unioes = null) {
		global $Aplic;
		$q = new BDConsulta();
		$q->adTabela('arquivo_pasta');
		$q->adCampo('COUNT(DISTINCT arquivo_pasta_id) AS num_de_subpastas');
		$q->adOnde('arquivo_pasta_superior='.(int)$oid);
		$res1 = $q->Resultado();
		$q->limpar();
		
		$q->adTabela('arquivo');
		$q->adCampo('COUNT(DISTINCT arquivo_id) AS num_of_files');
		$q->adOnde('arquivo_pasta='.(int)$oid);
		$res2 = $q->Resultado();
		$q->limpar();
		if (($res1 > 0) || ($res2 > 0)) {
			$msg = 'Não é possível excluir a pasta, pois a mesma tem arquivos e/ou subpastas';
			return false;
			}
		return true;
		}
	public function getNomePastaSuperior() {
		$q = new BDConsulta();
		$q->adTabela('arquivo_pasta');
		$q->adCampo('arquivo_pasta_nome');
		$q->adOnde('arquivo_pasta_id='.$this->arquivo_pasta_superior);
		return $q->Resultado();
		}
	public function contarPastas() {
		$q = new BDConsulta();
		$q->adTabela($this->_tbl);
		$q->adCampo('COUNT('.$this->_chave_tabela. ' )');
		$resultado = $q->Resultado();
		return $resultado;
		}


	public function podeAcessar() {
		global $Aplic;
		$valor=permiteAcessarPasta($this->arquivo_pasta_acesso, $this->arquivo_pasta_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarPasta($this->arquivo_pasta_acesso, $this->arquivo_pasta_id);
		return $valor;
		}
	}


function ultimo_arquivo($arquivo_versoes, $arquivo_nome, $arquivo_projeto) {
	$ultimo = null;
	if (isset($arquivo_versoes)) foreach ($arquivo_versoes as $arquivo_versao){
			if (($arquivo_versao['arquivo_nome'] == $arquivo_nome && $arquivo_versao['arquivo_projeto'] == $arquivo_projeto) && ($ultimo == null || $ultimo['arquivo_versao'] < $arquivo_versao['arquivo_versao']))	$ultimo = $arquivo_versao;
			}
	return $ultimo;
	}

function getIcone($arquivo_tipo) {
	global $config, $estilo_ui;
	$resultado = '';
	$mime = str_replace('/', '-', $arquivo_tipo);
	$icone = 'gnome-mime-'.$mime;
	if (is_file(BASE_DIR.'/estilo/rondon/imagens/icones/'.$icone.'.png')) $resultado = 'icones/'.$icone.'.png';
	else {
		$mime = explode('/', $arquivo_tipo);
		switch ($mime[0]) {
			case 'audio':
				$resultado = 'icones/gnome-mime-audio-x-wav.png';
				break;
			case 'image':
				$resultado = 'icones/gnome-mime-image.png';
				break;
			case 'text':
				$resultado = 'icones/gnome-mime-text-x-txt.png';
				break;
			case 'video':
				$resultado = 'icones/gnome-mime-video.png';
				break;
			}
		if ($mime[0] == 'aplicacao') {
			switch ($mime[1]) {
				case 'vnd.ms-excel':
					$resultado = 'icones/gnome-mime-application-x-applix-spreadsheet.png';
					break;
				case 'vnd.ms-powerpoint':
					$resultado = 'icones/gnome-mime-video-quicktime.png';
					break;
				case 'octet-stream':
					$resultado = 'icones/fonte_c.png';
					break;
				default:
					$resultado = 'icones/gnome-mime-application-msword.png';
				}
			}
		}
	if ($resultado == '') $resultado = 'icones/desconhecido.png';

	return $resultado;
	}

?>