<?php
	
	//Conectando ao MySQL
	$conexao = mysqli_connect('localhost', 'usuario','senha');
	mysqli_select_db($conexao, 'base_de_dados');
	mysqli_set_charset($conexao,"utf8");

	ini_set('default_charset', 'UTF-8');

	$csv_filename = 'CSV_'.date('d-m-Y H:i:s').'.csv'; //Nome do Arquivo CSV a gerar

	//Cabeçalhos:
	header('Content-Encoding: UTF-8');
	header('Content-Type: application/csv; charset=UTF-8');
	header("Content-Disposition: attachment; filename='".$csv_filename."'");

	//Criando o arquivo:
	$file = fopen('php://output', 'w');
	fputs( $file, "\xEF\xBB\xBF" ); //Usando BOM para indicar a codificação do conteudo do arquivo

	// Fazendo o select na tabela:
	$query = mysqli_query($conexao, "SELECT * FROM tabela;");

	$field = mysqli_field_count($conexao);

	// Pegando os títulos:
	$titulos = array();
	for($i = 0; $i < $field; $i++) {
		array_push($titulos, mysqli_fetch_field_direct($query, $i)->name);
	}
	fputcsv($file, $titulos, ";"); //Atribuindo os títulos ao arquivo CSV

	// Pegando todas as linhas:
	while($row = mysqli_fetch_array($query)) {
		$dados = array();
		for($i = 0; $i < $field; $i++) {
	        array_push($dados, $row[mysqli_fetch_field_direct($query, $i)->name]);
	    }
		fputcsv($file, $dados, ";"); //Atribuindo a linha montada acima ao arquivo CSV
	}
	fclose($file); // Fechando o arquivo
	mysqli_close($conexao); // Fechando a conexao com o MySQL

?>
