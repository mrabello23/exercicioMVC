<?php
	require "../config.php";
	include "../incs/cabecalho.php";
	include "../incs/menu_superior.php";

	$dashboard = array();

	$dashboard[] = array(
		"qtd" 	=> 10,
		"nome" 	=> "status 1",
		"id" 	=> 1
	);

	$dashboard[] = array(
		"qtd" 	=> 20,
		"nome" 	=> "status 2",
		"id" 	=> 2
	);

	$dashboard[] = array(
		"qtd" 	=> 30,
		"nome" 	=> "status 3",
		"id" 	=> 3
	);

	$dashboard[] = array(
		"qtd" 	=> 40,
		"nome" 	=> "status 4",
		"id" 	=> 4
	);
?>

<div class="row">
	<div class="col-md-12">
		<h2>Projeto Entrevista</h2><hr />
		<p> <b><?=$_SESSION['nome'];?></b>, seja bem vindo. </p>
	</div>

	<div class="col-md-12 dashboard">
		<!-- RETORNO MSG -->
		<?php if ( isset($_SESSION["msg"]) && isset($_SESSION["tipo_msg"]) ): ?>
			<div class="row">
				<div class="alert alert-<?=$_SESSION["tipo_msg"];?> col-md-12" role="alert">
					<strong>Atenção: </strong>
					<?php
						echo $_SESSION["msg"];
						unset($_SESSION["msg"]);
						unset($_SESSION["tipo_msg"]);
					?>
				</div>
			</div>
		<?php endif; ?>

		<?php foreach ($dashboard as $key => $value): ?>
			<div class="col-lg-3">
				<div class="panel panel-info">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-12 text-right">
								<div class="huge"><?=$value["qtd"];?></div>
								<div class="txt-dashboard"><?=$value["nome"];?></div>
							</div>
						</div>
					</div>
					<a href="#" class="btn-dashboard" rel="<?=$value["id"];?>">
						<div class="panel-footer">
							<span class="pull-left">Ver Detalhes</span>
							<span class="pull-right"><span aria-hidden="true">&rarr;</span></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div> <!-- /.col-lg-3 -->
		<?php endforeach; ?>
	</div> <!-- /.col-md-12 -->
</div> <!-- /.row -->

<?php include "../incs/rodape.php"; ?>