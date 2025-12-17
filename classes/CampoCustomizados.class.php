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

class CampoCustomizado {
	public $campo_customizado_id;
	public $campo_customizado_ordem;
	public $campo_customizado_nome;
	public $campo_customizado_descricao;
	public $campo_customizado_formula;
	public $campo_customizado_tipo_html;
	public $campo_customizado_publicado;
	public $campo_customizado_tipo_dado;
	public $campo_customizado_tags_extras;

	public $campo_customizado_descendente;
	public $campo_customizado_por_chave;

	public $objeto_id = null;
	public $campo_customizado_valor_id = 0;
	public $campo_customizado_valor_caractere;
	public $campo_customizado_valor_inteiro;
	public $estilo;

	public function __construct(
		$campo_customizado_id=null,
		$campo_customizado_nome=null,
		$campo_customizado_ordem=null,
		$campo_customizado_descricao=null,
		$campo_customizado_formula=null,
		$campo_customizado_tags_extras=null,
		$campo_customizado_publicado=null,
		$campo_customizado_descendente=null,
		$campo_customizado_por_chave=null,
		$campo_customizado_id_pai=null
		) {
		$this->campo_customizado_id = $campo_customizado_id;
		$this->campo_customizado_nome = $campo_customizado_nome;
		$this->campo_customizado_ordem = $campo_customizado_ordem;
		$this->campo_customizado_descricao = $campo_customizado_descricao;
		$this->campo_customizado_formula = $campo_customizado_formula;
		$this->campo_customizado_tags_extras = $campo_customizado_tags_extras;
		$this->campo_customizado_publicado = $campo_customizado_publicado;

		$this->campo_customizado_descendente = $campo_customizado_descendente;
		$this->campo_customizado_por_chave = $campo_customizado_por_chave;

		$this->campo_customizado_id_pai = $campo_customizado_id_pai;
		}

	public function load($objeto_id) {
		global $bd;
		$sql = new BDConsulta;
		$sql->adTabela('campo_customizado_valor');
		$sql->adOnde('campo_customizado_valor_campo = '.(int)$this->campo_customizado_id);
		$sql->adOnde('campo_customizado_valor_objeto = '.(int)$objeto_id);
		$linha = $sql->linha();
		$sql->limpar();
		$campo_customizado_valor_id = $linha['campo_customizado_valor_id'];
		$campo_customizado_valor_caractere = $linha['campo_customizado_valor_caractere'];
		$campo_customizado_valor_inteiro = $linha['campo_customizado_valor_inteiro'];
		if ($campo_customizado_valor_id != null) {
			$this->campo_customizado_valor_id = $campo_customizado_valor_id;
			$this->campo_customizado_valor_caractere = $campo_customizado_valor_caractere;
			$this->campo_customizado_valor_inteiro = $campo_customizado_valor_inteiro;
			}
		}

	public function armazenar($objeto_id) {
		global $bd;
		if ($objeto_id == null) return 'Erro: Não foi possível armazenar o campo ('.$this->campo_nome.'), id associado não foi suprido.';
		else {
			$ins_valorInteiro = $this->campo_customizado_valor_inteiro == null ? '0' : $this->campo_customizado_valor_inteiro;
			$ins_valorCaractere = $this->campo_customizado_valor_caractere == null ? '' : stripslashes($this->campo_customizado_valor_caractere);
			$sql = new BDConsulta;


			//processar valores
			if ($this->campo_customizado_tipo_html=='valor'){
				$ins_valorCaractere=float_americano($ins_valorCaractere);
				}
			else if ($this->campo_customizado_tipo_html=='formula'){
				$ins_valorCaractere=null;
				}
            else if ($this->campo_customizado_tipo_html=='data'){
                if($ins_valorCaractere && strlen($ins_valorCaractere) == 10){
                    $d = substr($ins_valorCaractere, 0, 2);
                    $m = substr($ins_valorCaractere, 3, 2);
                    $y = substr($ins_valorCaractere, 6);
                    $ins_valorCaractere = $y.'-'.$m.'-'.$d;
                    }
                else{
                    $ins_valorCaractere = '';
                    }
                }

			if ($this->campo_customizado_valor_id > 0) {
				$sql->adTabela('campo_customizado_valor');
				$sql->adAtualizar('campo_customizado_valor_caractere', $ins_valorCaractere);
				$sql->adAtualizar('campo_customizado_valor_inteiro', $ins_valorInteiro);
				$sql->adOnde('campo_customizado_valor_id = '.$this->campo_customizado_valor_id);
				}
			else {
				$sql->adTabela('campo_customizado_valor');
				$sql->adCampo('MAX(campo_customizado_valor_id)');
				$max_id = $sql->Resultado();
				$sql->limpar();

				$novo_campo_customizado_valor_id = $max_id ? $max_id + 1 : 1;

				$sql->adTabela('campo_customizado_valor');
				$sql->adInserir('campo_customizado_valor_id', $novo_campo_customizado_valor_id);
				$sql->adInserir('campo_customizado_valor_campo', $this->campo_customizado_id);
				$sql->adInserir('campo_customizado_valor_objeto', $objeto_id);
				$sql->adInserir('campo_customizado_valor_caractere', $ins_valorCaractere);
				$sql->adInserir('campo_customizado_valor_inteiro', $ins_valorInteiro);
				}
			$rs = $sql->exec();
			$sql->limpar();
			if (!$rs) return $bd->ErrorMsg().' | SQL: ';
			}
		}

	public function setValorInt($v) {
		$this->campo_customizado_valor_inteiro = $v;
		}

	public function intValor() {
		return $this->campo_customizado_valor_inteiro;
		}

	public function setValor($v) {
		$this->campo_customizado_valor_caractere = $v;
		}

	public function valor() {
		return $this->campo_customizado_valor_caractere;
		}

	public function valorCaractere() {
		return $this->campo_customizado_valor_caractere;
		}

	public function setValorId($v) {
		$this->campo_customizado_valor_id = $v;
		}

	public function valorId() {
		return $this->campo_customizado_valor_id;
		}

	public function campoNome() {
		return $this->campo_customizado_nome;
		}

	public function campoDescricao() {
		return $this->campo_customizado_descricao;
		}

	public function campoFormula() {
		return $this->campo_customizado_formula;
		}

	public function campoId() {
		return $this->campo_customizado_id;
		}

	public function campoTipoHtml() {
		return $this->campo_customizado_tipo_html;
		}

	public function campoTagExtra() {
		return $this->campo_customizado_tags_extras;
		}

	public function campoOrdem() {
		return $this->campo_customizado_ordem;
		}

	public function campoPublicado() {
		return $this->campo_customizado_publicado;
		}

	public function campoPorChave() {
		return $this->campo_customizado_por_chave;
		}

	public function campoDescendente() {
		return $this->campo_customizado_descendente;
		}

	}

class CampoCustomizadoCaixaMarcar extends CampoCustomizado {

	public function __construct(
		$campo_customizado_id=null,
		$campo_customizado_nome=null,
		$campo_customizado_ordem=null,
		$campo_customizado_descricao=null,
		$campo_customizado_formula=null,
		$campo_customizado_tags_extras=null,
		$campo_customizado_publicado=null,
		$campo_customizado_descendente=null,
		$campo_customizado_por_chave=null
		) {
		parent::__construct(
		$campo_customizado_id,
		$campo_customizado_nome,
		$campo_customizado_ordem,
		$campo_customizado_descricao,
		$campo_customizado_formula,
		$campo_customizado_tags_extras,
		$campo_customizado_publicado,
		$campo_customizado_descendente,
		$campo_customizado_por_chave
		);
		$this->campo_customizado_tipo_html = 'checkbox';
		}

	public function getHTML($modo) {
		$html='';
		switch ($modo) {
			case 'editar':
				$bool_tag = ($this->intValor() ? 'checked="checked"': '');
				if ($this->campo_customizado_descricao) $html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td><input type="checkbox" name="'.$this->campo_customizado_nome.'" value="1" '.$bool_tag.$this->campo_customizado_tags_extras.'/></td></tr>';
				break;
			case 'ver':
				if ($this->campo_customizado_descricao) $html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%">'.($this->intValor() ? 'Sim': 'Não').'</td></tr>';
				break;
			}
		return $html;
		}

	public function setValor($v) {
		$this->campo_customizado_valor_inteiro = $v;
		}
	}

class CampoCustomizadoTexto extends CampoCustomizado {

	public function __construct(
		$campo_customizado_id=null,
		$campo_customizado_nome=null,
		$campo_customizado_ordem=null,
		$campo_customizado_descricao=null,
		$campo_customizado_formula=null,
		$campo_customizado_tags_extras=null,
		$campo_customizado_publicado=null,
		$campo_customizado_descendente=null,
		$campo_customizado_por_chave=null
		) {
		parent::__construct(
		$campo_customizado_id,
		$campo_customizado_nome,
		$campo_customizado_ordem,
		$campo_customizado_descricao,
		$campo_customizado_formula,
		$campo_customizado_tags_extras,
		$campo_customizado_publicado,
		$campo_customizado_descendente,
		$campo_customizado_por_chave
		);
		$this->campo_customizado_tipo_html = 'textinput';
		}

	public function getHTML($modo) {
		$html ='';
		switch ($modo) {
			case 'editar':
				$html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td><input type="text" class="texto" name="'.$this->campo_customizado_nome.'" value="'.$this->valorCaractere().'" '.($this->campo_customizado_tags_extras ? $this->campo_customizado_tags_extras : 'style="width:100%"').' /></td></tr>';
				break;
			case 'ver':
				if ($this->valorCaractere()) $html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%">'.$this->valorCaractere();
				break;
			}
		return $html;
		}
	}

class CampoCustomizadoAreaTexto extends CampoCustomizado {

	public function __construct(
		$campo_customizado_id=null,
		$campo_customizado_nome=null,
		$campo_customizado_ordem=null,
		$campo_customizado_descricao=null,
		$campo_customizado_formula=null,
		$campo_customizado_tags_extras=null,
		$campo_customizado_publicado=null,
		$campo_customizado_descendente=null,
		$campo_customizado_por_chave=null
		) {
		parent::__construct(
		$campo_customizado_id,
		$campo_customizado_nome,
		$campo_customizado_ordem,
		$campo_customizado_descricao,
		$campo_customizado_formula,
		$campo_customizado_tags_extras,
		$campo_customizado_publicado,
		$campo_customizado_descendente,
		$campo_customizado_por_chave
		);
		$this->campo_customizado_tipo_html = 'textarea';
		}

	public function getHTML($modo) {
		$html ='';
		switch ($modo) {
			case 'editar':
				$html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td><textarea data-gpweb-cmp="ckeditor" name="'.$this->campo_customizado_nome.'" '.($this->campo_customizado_tags_extras ? $this->campo_customizado_tags_extras : 'style="width:100%" rows="4" class="texto"').'>'.$this->valorCaractere().'</textarea></td></tr>';
				break;
			case 'ver':
				if ($this->valorCaractere()) $html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%">'.$this->valorCaractere().'</td></tr>';
				break;
			}
		return $html;
		}

	}


class CampoCustomizadoFormula extends CampoCustomizado {

	public function __construct(
		$campo_customizado_id=null,
		$campo_customizado_nome=null,
		$campo_customizado_ordem=null,
		$campo_customizado_descricao=null,
		$campo_customizado_formula=null,
		$campo_customizado_tags_extras=null,
		$campo_customizado_publicado=null,
		$campo_customizado_descendente=null,
		$campo_customizado_por_chave=null,
		$campo_customizado_customizado_id_pai=null
		) {
		parent::__construct(
		$campo_customizado_id,
		$campo_customizado_nome,
		$campo_customizado_ordem,
		$campo_customizado_descricao,
		$campo_customizado_formula,
		$campo_customizado_tags_extras,
		$campo_customizado_publicado,
		$campo_customizado_descendente,
		$campo_customizado_por_chave,
		$campo_customizado_customizado_id_pai
		);
		$this->campo_customizado_tipo_html = 'formula';
		
		}

	public function getHTML($modo) {
		global $config;
		$html ='';
		switch ($modo) {
			case 'editar':

				break;
			case 'ver':
				$html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%">'.number_format( $this->calcularValor(), 2, ',', '.');
				break;
			}
		return $html;
		}

    public function calcularValor(){
        $sql = new BDConsulta;
        $sql->adTabela('campo_customizado_valor');
        $sql->esqUnir('campo_customizado', 'campo_customizado', 'campo_customizado_valor_campo=campo_customizado_id');
        $sql->adCampo('campo_customizado_id, campo_customizado_valor_caractere');
        $sql->adOnde('campo_customizado_valor_objeto = '.(int)$this->campo_customizado_id_pai);
        $sql->adOnde('campo_customizado_tipo_html = \'valor\'');
        $variaveis=$sql->listaVetorChave('campo_customizado_id','campo_customizado_valor_caractere');
        $sql->limpar();

        $formula=$this->campo_customizado_formula;
        foreach($variaveis as $campoid => $campo_customizado_valor_caractere){
            $chave='I'.$campoid;
            $formula=str_replace($chave , $campo_customizado_valor_caractere, $formula);
        }

        return $this->calcular_string($formula);
    }

	public function calcular_string($texto){
    $texto = trim($texto);
    $texto=previnirXSS($texto);
    if (!$texto)return 0;

    for($i = 0; $i < strlen($texto); $i++){
    	if (isset($texto[$i]) && $texto[$i]=='I' && isset($texto[$i+1]) && is_int($texto[$i+1]) && isset($texto[$i+2]) &&is_int($texto[$i+2])) {
    		$texto[$i]='0';
    		$texto[$i+1]='.';
    		$texto[$i+2]='0';
    		}
    	}
    $valor=0;
   	// $computar = @create_function("", "return (".$texto.");");
  	// $valor=( function_exists($computar) ? @$computar() :  0);
    
    
    
    
    try {
    	$valor = eval("return (".$texto.");");
      if($valor === false) throw new \RuntimeException('Formula inválida');
  		}
  	catch (Exception $e){
    
    	}

    return 0 + $valor;
		}


	}

class CampoCustomizadoData extends CampoCustomizado {

    public function __construct(
    	$campo_customizado_id=null,
			$campo_customizado_nome=null,
			$campo_customizado_ordem=null,
			$campo_customizado_descricao=null,
			$campo_customizado_formula=null,
			$campo_customizado_tags_extras=null,
			$campo_customizado_publicado=null,
			$campo_customizado_descendente=null,
			$campo_customizado_por_chave=null
			) {
        parent::__construct(
        $campo_customizado_id,
				$campo_customizado_nome,
				$campo_customizado_ordem,
				$campo_customizado_descricao,
				$campo_customizado_formula,
				$campo_customizado_tags_extras,
				$campo_customizado_publicado,
				$campo_customizado_descendente,
				$campo_customizado_por_chave);
        $this->campo_customizado_tipo_html = 'data';
        }

    public function getHTML($modo) {
        global $config,$Aplic;
        $html ='';
        switch ($modo) {
            case 'editar':
                $data = $this->valorCaractere();
                if($data){
                    $data = new CData($data);
                    $data = $data->format('%d/%m/%Y');
                    }
                $Aplic->carregarCalendarioJS();
                $html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td><input data-gpweb-cmp="calendario" type="text" class="texto" id="'.$this->campo_customizado_nome.'" name="'.$this->campo_customizado_nome.'" value="'.$data.'" '.($this->campo_customizado_tags_extras ? $this->campo_customizado_tags_extras : 'size="10"').'/></td></tr>';
                break;
            case 'ver':
                if ($this->valorCaractere()) $html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%">'.retorna_data($this->valorCaractere(), false);
                break;
            }
        return $html;
        }

    }

class CampoCustomizadoValor extends CampoCustomizado {

	public function __construct(
		$campo_customizado_id=null,
		$campo_customizado_nome=null,
		$campo_customizado_ordem=null,
		$campo_customizado_descricao=null,
		$campo_customizado_formula=null,
		$campo_customizado_tags_extras=null,
		$campo_customizado_publicado=null,
		$campo_customizado_descendente=null,
		$campo_customizado_por_chave=null
		) {
		parent::__construct(
		$campo_customizado_id,
		$campo_customizado_nome,
		$campo_customizado_ordem,
		$campo_customizado_descricao,
		$campo_customizado_formula,
		$campo_customizado_tags_extras,
		$campo_customizado_publicado,
		$campo_customizado_descendente,
		$campo_customizado_por_chave
		);
		$this->campo_customizado_tipo_html = 'valor';
		}

	public function getHTML($modo) {
		global $config;
		$html ='';
		switch ($modo) {
			case 'editar':
				$html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="'.$this->campo_customizado_nome.'" value="'.str_replace('.', ',',$this->valorCaractere()).'" '.($this->campo_customizado_tags_extras ? $this->campo_customizado_tags_extras : 'style="text-align: right;" size="25"').' /></td></tr>';
				break;
			case 'ver':
				if ($this->valorCaractere()) $html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%">'.number_format($this->valorCaractere(), 2, ',', '.');
				break;
			}
		return $html;
		}

	}

class CampoCustomizadoLegenda extends CampoCustomizado {

	public function __construct(
		$campo_customizado_id=null,
		$campo_customizado_nome=null,
		$campo_customizado_ordem=null,
		$campo_customizado_descricao=null,
		$campo_customizado_formula=null,
		$campo_customizado_tags_extras=null,
		$campo_customizado_publicado=null,
		$campo_customizado_descendente=null,
		$campo_customizado_por_chave=null
		) {
		parent::__construct(
		$campo_customizado_id,
		$campo_customizado_nome,
		$campo_customizado_ordem,
		$campo_customizado_descricao,
		$campo_customizado_formula,
		$campo_customizado_tags_extras,
		$campo_customizado_publicado,
		$campo_customizado_descendente,
		$campo_customizado_por_chave
		);
		$this->campo_customizado_tipo_html = 'label';
		}

	public function getHTML($modo) {
		if ($this->campo_customizado_descricao) return '<tr><td align="right" style="white-space: nowrap"><span '.$this->campo_customizado_tags_extras.'>'.$this->campo_customizado_descricao.'</span></td></tr>';
		else return '';
		}

	}

class CampoCustomizadoSeparador extends CampoCustomizado {

	public function __construct(
		$campo_customizado_id=null,
		$campo_customizado_nome=null,
		$campo_customizado_ordem=null,
		$campo_customizado_descricao=null,
		$campo_customizado_formula=null,
		$campo_customizado_tags_extras=null,
		$campo_customizado_publicado=null,
		$campo_customizado_descendente=null,
		$campo_customizado_por_chave=null
		) {
		parent::__construct(
		$campo_customizado_id,
		$campo_customizado_nome,
		$campo_customizado_ordem,
		$campo_customizado_descricao,
		$campo_customizado_formula,
		$campo_customizado_tags_extras,
		$campo_customizado_publicado,
		$campo_customizado_descendente,
		$campo_customizado_por_chave
		);
		$this->campo_customizado_tipo_html = 'separator';
		}

	public function getHTML($modo) {
		return '<tr><td colspan="2"><hr '.$this->campo_customizado_tags_extras.' /></td></tr>';
		}

	}

class CampoCustomizadoSelecionar extends CampoCustomizado {
	public $opcoes;

	public function __construct(
		$campo_customizado_id=null,
		$campo_customizado_nome=null,
		$campo_customizado_ordem=null,
		$campo_customizado_descricao=null,
		$campo_customizado_formula=null,
		$campo_customizado_tags_extras=null,
		$campo_customizado_publicado=null,
		$campo_customizado_descendente=null,
		$campo_customizado_por_chave=null
		) {
		parent::__construct(
		$campo_customizado_id,
		$campo_customizado_nome,
		$campo_customizado_ordem,
		$campo_customizado_descricao,
		$campo_customizado_formula,
		$campo_customizado_tags_extras,
		$campo_customizado_publicado,
		$campo_customizado_descendente,
		$campo_customizado_por_chave
		);
		$this->campo_customizado_tipo_html = 'selecionar';
		$this->opcoes = new ListaOpcoesCustomizadas($campo_customizado_id, $campo_customizado_por_chave, $campo_customizado_descendente);
		$this->opcoes->load();
		}

	public function getHTML($modo) {
		$html='';
		switch ($modo) {
			case 'editar':
				$html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td>'.$this->opcoes->getHTML($this->campo_customizado_nome, $this->valorCaractere()).'</td></tr>';
				break;
			case 'ver':
				if ($this->valorCaractere()) $html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%">'.$this->opcoes->itemNoIndice($this->valorCaractere()).'</td></tr>';
				break;
			}
		return $html;
		}

	}

class CampoCustomizadoLinkWeb extends CampoCustomizado {

	public function __construct(
		$campo_customizado_id=null,
		$campo_customizado_nome=null,
		$campo_customizado_ordem=null,
		$campo_customizado_descricao=null,
		$campo_customizado_formula=null,
		$campo_customizado_tags_extras=null,
		$campo_customizado_publicado=null,
		$campo_customizado_descendente=null,
		$campo_customizado_por_chave=null
		) {
		parent::__construct(
		$campo_customizado_id,
		$campo_customizado_nome,
		$campo_customizado_ordem,
		$campo_customizado_descricao,
		$campo_customizado_formula,
		$campo_customizado_tags_extras,
		$campo_customizado_publicado,
		$campo_customizado_descendente,
		$campo_customizado_por_chave
		);
		$this->campo_customizado_tipo_html = 'href';
		}

	public function getHTML($modo) {
		$html='';
		switch ($modo) {
			case 'editar':
				$html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td><input type="text" class="texto" name="'.$this->campo_customizado_nome.'" value="'.$this->valorCaractere().'" ' .($this->campo_customizado_tags_extras ? $this->campo_customizado_tags_extras : 'style="width:100%"'). ' /></td></tr>';
				break;
			case 'ver':
				if(strpos($this->campoTagExtra(),'{'.$this->campoNome().'}') && $this->valorCaractere()) {
					$html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%"><a href="'.str_replace('{'.$this->campoNome().'}', $this->valorCaractere(), $this->campoTagExtra()).'" target="_blank">'.$this->valorCaractere().'</a></td></tr>';
					}
				else if ($this->valorCaractere()) $html = '<tr><td align="right" style="white-space: nowrap">'.dica($this->campo_customizado_descricao, 'Campo customizado').$this->campo_customizado_descricao.': </td><td '.($this->estilo ? $this->estilo : 'class="realce"').' width="100%"><a href="'.$this->valorCaractere().'">'.$this->valorCaractere().'</a></td></tr>';
				break;
			}
		return $html;
		}

	}

class CampoCustomizados {
	public $m;
	public $a;
	public $modo;
	public $obj_id;
	public $ordem;
	public $publicado;
	public $campos;

	public function __construct($m, $obj_id = null, $modo = 'editar', $publicado = 0) {
		$this->m = $m;
		$this->obj_id = $obj_id;
		$this->modo = $modo;
		$this->publicado = $publicado;
		$sql = new BDConsulta;
		$sql->adTabela('campo_customizado');
		$sql->adOnde('campo_customizado_modulo = \''.$this->m.'\' AND campo_customizado_pagina = \'editar\'');
		if ($publicado)	$sql->adOnde('campo_customizado_publicado = 1');
		$sql->adOrdem('campo_customizado_ordem ASC');
		$linhas = $sql->Lista();


		if ($linhas == null) {	}
		else {
			foreach ($linhas as $linha) {
				switch ($linha['campo_customizado_tipo_html']) {
					case 'checkbox':
						$this->campos[$linha['campo_customizado_nome']] = new CampoCustomizadoCaixaMarcar($linha['campo_customizado_id'], $linha['campo_customizado_nome'], $linha['campo_customizado_ordem'], stripslashes($linha['campo_customizado_descricao']), stripslashes($linha['campo_customizado_formula']), stripslashes($linha['campo_customizado_tags_extras']), $linha['campo_customizado_publicado'], $linha['campo_customizado_descendente'], $linha['campo_customizado_por_chave']);
						break;
					case 'href':
						$this->campos[$linha['campo_customizado_nome']] = new CampoCustomizadoLinkWeb($linha['campo_customizado_id'], $linha['campo_customizado_nome'], $linha['campo_customizado_ordem'], stripslashes($linha['campo_customizado_descricao']), stripslashes($linha['campo_customizado_formula']), stripslashes($linha['campo_customizado_tags_extras']), $linha['campo_customizado_publicado'], $linha['campo_customizado_descendente'], $linha['campo_customizado_por_chave']);
						break;
					case 'textarea':
						$this->campos[$linha['campo_customizado_nome']] = new CampoCustomizadoAreaTexto($linha['campo_customizado_id'], $linha['campo_customizado_nome'], $linha['campo_customizado_ordem'], stripslashes($linha['campo_customizado_descricao']), stripslashes($linha['campo_customizado_formula']), stripslashes($linha['campo_customizado_tags_extras']), $linha['campo_customizado_publicado'], $linha['campo_customizado_descendente'], $linha['campo_customizado_por_chave']);
						break;
					case 'selecionar':
						$this->campos[$linha['campo_customizado_nome']] = new CampoCustomizadoSelecionar($linha['campo_customizado_id'], $linha['campo_customizado_nome'], $linha['campo_customizado_ordem'], stripslashes($linha['campo_customizado_descricao']), stripslashes($linha['campo_customizado_formula']), stripslashes($linha['campo_customizado_tags_extras']), $linha['campo_customizado_publicado'], $linha['campo_customizado_descendente'], $linha['campo_customizado_por_chave']);
						break;
					case 'label':
						$this->campos[$linha['campo_customizado_nome']] = new CampoCustomizadoLegenda($linha['campo_customizado_id'], $linha['campo_customizado_nome'], $linha['campo_customizado_ordem'], stripslashes($linha['campo_customizado_descricao']), stripslashes($linha['campo_customizado_formula']), stripslashes($linha['campo_customizado_tags_extras']), $linha['campo_customizado_publicado'], $linha['campo_customizado_descendente'], $linha['campo_customizado_por_chave']);
						break;
					case 'separator':
						$this->campos[$linha['campo_customizado_nome']] = new CampoCustomizadoSeparador($linha['campo_customizado_id'], $linha['campo_customizado_nome'], $linha['campo_customizado_ordem'], stripslashes($linha['campo_customizado_descricao']), stripslashes($linha['campo_customizado_formula']), stripslashes($linha['campo_customizado_tags_extras']), $linha['campo_customizado_publicado'], $linha['campo_customizado_descendente'], $linha['campo_customizado_por_chave']);
						break;
          case 'data':
            $this->campos[$linha['campo_customizado_nome']] = new CampoCustomizadoData($linha['campo_customizado_id'], $linha['campo_customizado_nome'], $linha['campo_customizado_ordem'], stripslashes($linha['campo_customizado_descricao']), stripslashes($linha['campo_customizado_formula']), stripslashes($linha['campo_customizado_tags_extras']),  $linha['campo_customizado_publicado'], $linha['campo_customizado_descendente'], $linha['campo_customizado_por_chave']);
            break;
					case 'valor':
						$this->campos[$linha['campo_customizado_nome']] = new CampoCustomizadoValor($linha['campo_customizado_id'], $linha['campo_customizado_nome'], $linha['campo_customizado_ordem'], stripslashes($linha['campo_customizado_descricao']), stripslashes($linha['campo_customizado_formula']), stripslashes($linha['campo_customizado_tags_extras']), $linha['campo_customizado_publicado'], $linha['campo_customizado_descendente'], $linha['campo_customizado_por_chave']);
						break;
					case 'formula':
						$this->campos[$linha['campo_customizado_nome']] = new CampoCustomizadoFormula($linha['campo_customizado_id'], $linha['campo_customizado_nome'], $linha['campo_customizado_ordem'], stripslashes($linha['campo_customizado_descricao']), stripslashes($linha['campo_customizado_formula']), stripslashes($linha['campo_customizado_tags_extras']), $linha['campo_customizado_publicado'], $linha['campo_customizado_descendente'], $linha['campo_customizado_por_chave'], $this->obj_id);
						break;
					default:
						$this->campos[$linha['campo_customizado_nome']] = new CampoCustomizadoTexto($linha['campo_customizado_id'], $linha['campo_customizado_nome'], $linha['campo_customizado_ordem'], stripslashes($linha['campo_customizado_descricao']), stripslashes($linha['campo_customizado_formula']), stripslashes($linha['campo_customizado_tags_extras']), $linha['campo_customizado_ordem'], $linha['campo_customizado_publicado'], $linha['campo_customizado_descendente'], $linha['campo_customizado_por_chave']);
						break;
					}
				}
			if ($obj_id > 0) {
				foreach ($this->campos as $chave => $cCampo) $this->campos[$chave]->load($this->obj_id);
				}
			}
		}

	public function adicionar(
		$uuid,
		$campo_customizado_nome,
		$campo_customizado_descricao,
		$campo_customizado_formula,
		$campo_customizado_tipo_html,
		$campo_customizado_tipo_dado,
		$campo_customizado_tags_extras,
		$campo_customizado_ordem,
		$campo_customizado_publicado,
		$campo_customizado_descendente,
		$campo_customizado_por_chave,
		&$erro_msg) {

		global $bd, $Aplic;

		$sql = new BDConsulta;
		$sql->adTabela('campo_customizado');
		$sql->adCampo('MAX(campo_customizado_id)');
		$max_id = $sql->Resultado();
		$sql->limpar();
		$next_id = ($max_id ? $max_id + 1 : 1);
		$campo_customizado_ordem = ($campo_customizado_ordem ? $campo_customizado_ordem : 1);
		$campo_customizado_publicado = ($campo_customizado_publicado ? 1 : 0);
		$campo_a = 'editar';

		$sql->adTabela('campo_customizado');
		$sql->adInserir('campo_customizado_id', $next_id);
		$sql->adInserir('campo_customizado_modulo', $this->m);
		$sql->adInserir('campo_customizado_pagina', $campo_a);
		$sql->adInserir('campo_customizado_tipo_html', $campo_customizado_tipo_html);
		$sql->adInserir('campo_customizado_tipo_dado', $campo_customizado_tipo_dado);
		$sql->adInserir('campo_customizado_ordem', $campo_customizado_ordem);
		$sql->adInserir('campo_customizado_nome', $campo_customizado_nome);
		$sql->adInserir('campo_customizado_descricao', $campo_customizado_descricao);
		$sql->adInserir('campo_customizado_formula', $campo_customizado_formula);
		$sql->adInserir('campo_customizado_tags_extras', $campo_customizado_tags_extras);
		$sql->adInserir('campo_customizado_ordem', $campo_customizado_ordem);
		$sql->adInserir('campo_customizado_publicado', $campo_customizado_publicado);
		$sql->adInserir('campo_customizado_descendente', $campo_customizado_descendente);
		$sql->adInserir('campo_customizado_por_chave', $campo_customizado_por_chave);
		if (!$sql->exec()) {
			$erro_msg = $bd->ErrorMsg();
			return 0;
			}

    $sql->limpar();

    if($Aplic->profissional &&
       (
           $campo_customizado_tipo_html === 'data'
           || $campo_customizado_tipo_html === 'selecionar'
           || $campo_customizado_tipo_html === 'textinput'
           || $campo_customizado_tipo_html === 'textarea'
           || $campo_customizado_tipo_html === 'checkbox'
           || $campo_customizado_tipo_html === 'valor'
           || $campo_customizado_tipo_html === 'formula'
           || $campo_customizado_tipo_html === 'href'
       )){
      //adiciona como opção para os formulários
      $sql->adTabela('campo_formulario');
      $sql->adInserir('campo_formulario_ativo', '0');
      $sql->adInserir('campo_formulario_campo', $campo_customizado_nome.'_ex');
      $sql->adInserir('campo_formulario_tipo', $this->m.'_ex');
      $sql->adInserir('campo_formulario_descricao', $campo_customizado_descricao);
      $sql->adInserir('campo_formulario_customizado', $next_id);
      $sql->exec();
      $sql->limpar();
      }

    if ($uuid){
    	$sql->adTabela('campo_customizado_lista');
      $sql->adAtualizar('campo_customizado_lista_campo', $next_id);
      $sql->adAtualizar('campo_customizado_lista_uuid', null);
      $sql->adOnde('campo_customizado_lista_uuid=\''.$uuid.'\'');
      $sql->exec();
      $sql->limpar();

    	}


    return $next_id;
		}

	public function atualizar(
		$campo_customizado_id,
		$campo_customizado_nome,
		$campo_customizado_descricao,
		$campo_customizado_formula,
		$campo_customizado_tipo_html,
		$campo_customizado_tipo_dado,
		$campo_customizado_tags_extras,
		$campo_customizado_ordem,
		$campo_customizado_publicado,
		$campo_customizado_descendente,
		$campo_customizado_por_chave,
		&$erro_msg) {

		global $bd, $Aplic;

		$sql = new BDConsulta;
		$sql->adTabela('campo_customizado');
		$sql->adAtualizar('campo_customizado_nome', $campo_customizado_nome);
		$sql->adAtualizar('campo_customizado_descricao', $campo_customizado_descricao);
		$sql->adAtualizar('campo_customizado_formula', $campo_customizado_formula);
		$sql->adAtualizar('campo_customizado_tipo_html', $campo_customizado_tipo_html);
		$sql->adAtualizar('campo_customizado_tipo_dado', $campo_customizado_tipo_dado);
		$sql->adAtualizar('campo_customizado_tags_extras', $campo_customizado_tags_extras);
		$sql->adAtualizar('campo_customizado_ordem', $campo_customizado_ordem);
		$sql->adAtualizar('campo_customizado_publicado', $campo_customizado_publicado);
		$sql->adAtualizar('campo_customizado_descendente', $campo_customizado_descendente);
		$sql->adAtualizar('campo_customizado_por_chave', $campo_customizado_por_chave);
		$sql->adOnde('campo_customizado_id = '.(int)$campo_customizado_id);
		if (!$sql->exec()) {
			$erro_msg = $bd->ErrorMsg();
			return 0;
			}

    $sql->limpar();

    if($Aplic->profissional && (
            $campo_customizado_tipo_html === 'data'
            || $campo_customizado_tipo_html === 'selecionar'
            || $campo_customizado_tipo_html === 'textinput'
            || $campo_customizado_tipo_html === 'textarea'
            || $campo_customizado_tipo_html === 'checkbox'
            || $campo_customizado_tipo_html === 'valor'
            || $campo_customizado_tipo_html === 'formula'
            || $campo_customizado_tipo_html === 'href'
        )){
      //adiciona como opção para os formulários
      $sql->adTabela('campo_formulario');
      $sql->adAtualizar('campo_formulario_campo', $campo_customizado_nome.'_ex');
      $sql->adAtualizar('campo_formulario_descricao', $campo_customizado_descricao);
      $sql->adOnde('campo_formulario_customizado='.(int)$campo_customizado_id);
      $sql->exec();
      $sql->limpar();

      return $campo_customizado_id;
      }
		}

	public function campoComId($campo_customizado_id) {
		foreach ($this->campos as $k => $v) {
			if ($this->campos[$k]->campo_customizado_id == $campo_customizado_id) return $this->campos[$k];
			}
		}

	public function join(&$variaveis) {
		if (is_array($this->campos) && count($this->campos) > 0) {
			foreach ($this->campos as $k => $v) $this->campos[$k]->setValor(@$variaveis[$k]);
			}
		}

	public function armazenar($objeto_id) {
		if (is_array($this->campos) && count($this->campos) > 0) {
			$armazenar_erros = '';
			foreach ($this->campos as $k => $cf) {
				$resultado = $this->campos[$k]->armazenar($objeto_id);
				if ($resultado) $armazenar_erros .= 'Erro ao armazenar o campo customizado '.$k.':'.$resultado;
				}
			if ($armazenar_erros) echo $armazenar_erros;
			}
		}

	public function excluirCampo($campo_customizado_id) {
		global $bd;
		$sql = new BDConsulta;
		$sql->setExcluir('campo_customizado');
		$sql->adOnde('campo_customizado_id = '.(int)$campo_customizado_id);
		if (!$sql->exec()) {
			return $bd->ErrorMsg();
			}
    $sql->limpar();

    $sql->setExcluir('campo_formulario');
    $sql->adOnde('campo_formulario_customizado = '.(int)$campo_customizado_id);
		}

	public function count() {
		return (is_array($this->campos) ? count($this->campos) : 0);
		}

	public function getHTML() {
		if ($this->count() == 0) return '';
		else {
			$html = '';
			foreach ($this->campos as $cCampo) {
				if (!$this->publicado) $html .=  $cCampo->getHTML($this->modo);
				else $html .= $cCampo->getHTML($this->modo);
				}
			return $html;
			}
		}

	public function imprimirHTML() {
		$html = $this->getHTML();
		echo $html;
		}


	}

class ListaOpcoesCustomizadas {
	public $campo_customizado_id;
	public $opcoes;

	public $campo_customizado_por_chave;
	public $campo_customizado_descendente;



	public function __construct($campo_customizado_id, $campo_customizado_por_chave=null, $campo_customizado_descendente=null) {
		$this->campo_customizado_id = $campo_customizado_id;

		$this->campo_customizado_por_chave = $campo_customizado_por_chave;
		$this->campo_customizado_descendente = $campo_customizado_descendente;

		$this->opcoes = array();
		}

	public function load($oid = null, $tira = true) {
		global $bd;
		$sql = new BDConsulta;
		$sql->adTabela('campo_customizado_lista');
		$sql->adOnde('campo_customizado_lista_campo = '.(int)$this->campo_customizado_id);
		
		if ($this->campo_customizado_por_chave) $sql->adOrdem('campo_customizado_lista_opcao '.($this->campo_customizado_descendente ? 'DESC' : 'ASC'));
		else $sql->adOrdem('campo_customizado_lista_valor '.($this->campo_customizado_descendente ? 'DESC' : 'ASC'));
		$opcoes=$sql->lista();
		$sql->limpar();
		$this->opcoes = array();
		foreach($opcoes as $linha) $this->opcoes[$linha['campo_customizado_lista_opcao']] = $linha['campo_customizado_lista_valor'];
		}

	public function excluir() {
		$sql = new BDConsulta;
		$sql->setExcluir('campo_customizado_lista');
		$sql->adOnde('campo_customizado_lista_campo = '.$this->campo_customizado_id);
		$sql->exec();
		$sql->limpar();
		}

	public function setOpcoes($opcao_array) {
		$this->opcoes = $opcao_array;
		}

	public function getOpcoes() {
		return $this->opcoes;
		}

	public function itemNoIndice($i) {
		if (isset($this->opcoes[$i])) return $this->opcoes[$i];
		}

	public function getHTML($campo_nome, $selecionado) {
		$html = '<select class="texto" name="'.$campo_nome.'">';
		foreach ($this->opcoes as $i => $opt) {
			$html .= "\t".'<option value="'.$i.'"';
			if ($i == $selecionado) $html .= ' selected="selected" ';
			$html .= '>'.$opt.'</option>';
			}
		$html .= '</select>';
		return $html;
		}
	}
?>