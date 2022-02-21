/* Sai do contexto do Html e entra no contexto PHP */
<?php


// Seleção com unidade e situação Preenchidos

/* Construir o comando Select para ser executado no banco MYSQL */

if ($unidade <> 0 and $situacao <> 0 and $aluno == 0 and $curso == 0 and $turma == ''){
	$check_sql = "SELECT * FROM aluno WHERE id_unidade = $unidade AND situacao =$situacao";
}


// Seleção com uinidade, situação e $aluno Preenchidos
if ($unidade <> 0 and $situacao <> 0 and $aluno <> 0 and $curso == 0 and $turma == ''){
	$check_sql = "SELECT * FROM aluno WHERE id_unidade = $unidade AND situacao = $situacao AND id = $aluno";
}


// Seleção com unidade, situação, aluno e curso Preenchidos
if ($unidade <> 0 and $situacao <> 0 and $aluno <> 0 and $curso <> 0 and $turma == ''){
	$check_sql = "SELECT * FROM aluno WHERE id_unidade = $unidade AND situacao = $situacao AND id = $aluno AND id_curso = $curso ";
}


// Seleção com unidade, situação, curso e turma Preenchidos
if ($unidade <> 0 and $situacao <> 0 and $aluno == 0 and $curso <> 0 and $turma <> ''){
	$check_sql = "SELECT * FROM aluno WHERE id_unidade = $unidade AND situacao = $situacao AND id = $aluno AND id_curso = $curso AND turma = $turma";
}


// Seleção com unidade, situação e curso Preenchidos
if ($unidade <> 0 and $situacao <> 0 and $aluno == 0 and $curso <> 0 and $turma == ''){
	$check_sql = "SELECT * FROM aluno WHERE id_unidade = $unidade AND situacao = $situacao AND id_curso = $curso ";
}


/* Executo o select MYSQL criado */
sc_lookup(rs, $check_sql);

$length = count($rs);

//echo $length;
//die;	

$l = 0;
//$loc_data_processamento = date('d/m/Y');

/* Testa se o Select trouxe registros */
if ($length > 0)     // Row found
{
	
	while($l < $length)
	{
		
		$loc_id				    = {rs[$l][0]};
		$loc_id_entidade 		= {rs[$l][2]};
		$loc_id_unidade 		= {rs[$l][3]};
		$loc_tipo_aluno 		= {rs[$l][7]};
		$loc_nome 				= {rs[$l][8]};
		$loc_id_resp_fin 		= {rs[$l][55]};
		$loc_per_desconto 		= {rs[$l][61]};
		$loc_valor_desconto_a	= {rs[$l][62]};
		$loc_dia_vencimento 	= {rs[$l][66]};
		$loc_id_curso 			= {rs[$l][76]};
		$loc_turma 			    = {rs[$l][77]};
		$loc_turno 			    = {rs[$l][78]};
		$valor_acescimo         = {rs[$l][126]};

		$l += 1;
		
		if (loc_dia_vencimento > 0){
			$vencimento1 = $ano . '-' . $mes . '-' . $loc_dia_vencimento . 
		' 00:00:00';
		}else {
			$vencimento1 = $ano . '-' . $mes . '-' . $dia . 
		' 00:00:00';
		}	
		//$data_mysql = '2009-03-12 03:54:21';
		$timestamp = strtotime($vencimento1); // Gera o timestamp de $data_mysql
		//echo date('d/m/Y', $timestamp); // Resultado: 12/03/2009

		/* busca Responsavel financeiro */
		
		$check_sql = "SELECT *"
  		. " FROM responsaveis	"
 	  	. " WHERE id = '" . $loc_id_resp_fin} . "'";
		
		sc_lookup(rs1, $check_sql);
		
  		
		if (count($rs1) > 0)
		{
			$loc_cpf 				= {rs1[0][11]};
			$loc_cep 				= {rs1[0][13]};
			$loc_tp_log 			= {rs1[0][14]};
			$loc_logradouro 		= {rs1[0][15]};
			$loc_numero 			= {rs1[0][16]};
			$loc_complemento 		= {rs1[0][17]};
			$loc_bairro 			= {rs1[0][18]};
			$loc_cidade 			= {rs1[0][19]};
			$loc_uf 				= {rs1[0][20]};
			$loc_turma   			= {rs1[0][43]};
			$loc_turno 	     		= {rs1[0][44]};
		}
		
	
		
		
		// busca curso	
		
		// Check for record
		
		$check_sql = 'SELECT *'
			. ' FROM cursos' 
			. " WHERE id = '" . $loc_id_curso} . "'";
		sc_lookup(rs2, $check_sql);

		if (count($rs2) > 0)
		{
			$loc_mens_manha 			= {rs2[0][11]};
			$loc_mens_manha_integral 	= {rs2[0][14]};
			$loc_desc_esprcial_valor 	= {rs2[0][17]};
			$loc_desc_esp 				= {rs2[0][20]};
			
		} else {
			$loc_mens_manha 			= 0;
			$loc_mens_manha_integral 	= 0;
			$loc_desc_esprcial_valor 	= 0;
			$loc_desc_esp 				= 0;
		}
		// fim Busca curso	
		
		
		// Seta Valor da mensalidade
		
		
		if ($loc_valor_desconto_a > 0){
			$loc_valor_bruto = $loc_mens_manha + $valor_acescimo;
			$loc_valor_desconto = $loc_valor_desconto_a;

			$loc_valor_total = $loc_mens_manha - $loc_valor_desconto_a;

		}else {
			$loc_valor_bruto = $loc_mens_manha + $valor_acescimo;
			$loc_valor_desconto = ($loc_mens_manha * $loc_per_desconto/100);
			$loc_valor_total = $loc_mens_manha - ($loc_mens_manha * $loc_per_desconto/100);
		}		
		
		//Insere Mensalidade no Banco
		
		for($i=1; $i<={qtd}; $i++)
			
		{
			if ($i > 1)
				{
				 $xx = $i - 1;
				$var_venc_novo =  sc_date($vencimento1, "aaaa-mm-dd", "+ ", 0, $xx, 0);	
				}
			else
				{

				$var_venc_novo =  $vencimento1;		
				}

		//str_to_date(data, '%d.%m.%Y')	
		
		$insert_fields = array(   
			 'id_entidade' => "'$loc_id_entidade'",
			 'id_unidade' => "'$loc_id_unidade'",
			 'id_aluno' => "'$loc_id'",
			 'id_curso' => "'$loc_id_curso'",
			 'dta_processamento' => "'$data'",
			 'valor_base' => "'$loc_valor_bruto'",
			 'dta_vencimento' => "'$var_venc_novo'",
			 'descontos' => "'$loc_valor_desconto'",
			 'valor_total' => "'$loc_valor_total'",
			 'id_tipo_titulo' => "'tipo'",);
			
		// Insert record
			
		$insert_sql = 'INSERT INTO mensalidades ' . ' ('   . implode(', ', array_keys($insert_fields))   . ')'
    	. ' VALUES ('    . implode(', ', array_values($insert_fields)) . ')';

		sc_exec_sql($insert_sql);
		
				
		//Fim Insere mensalidade no Banco
			
		}
								
	}
				
		
}

/* Função para apresentação de Caixa de Mensagem */
sc_redir(blank_mensagem, 'Mensalidades geradas com sucesso!!!', _self);

/* Sai do contexto PHP e Volta ao contexto do Html  */
?>