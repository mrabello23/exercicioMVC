<?php 
	require "../../config.php";
	include "../../incs/cabecalho.php";
	include "../../incs/menu_superior.php";

	/** Paginação */
	$pagina = (isset($_GET['pg']) ? (int)$_GET['pg'] : 1);
	$url = "http://".$_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];

	$user = new UsuarioController();
	$dados = $user->listar('', $url, $pagina);
	// echo "<pre>"; print_r($dados); echo "</pre>";
?>

<div class="row">
	<div class="col-md-12">
		<h2>Lista de Usuários</h2><hr />

		<?php if ( isset($_SESSION['msg_tipo']) && isset($_SESSION['msg']) ): ?>
			<div class="alert alert-<?=($_SESSION['msg_tipo'] == "Erro" ? "danger" : "success");?> alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<strong><?=$_SESSION['msg_tipo'];?>:</strong> <?=$_SESSION['msg'];?>
			</div>
			<?php unset($_SESSION['msg_tipo']); unset($_SESSION['msg']); ?>
		<?php endif; ?>

		<div class="row">
			<div class="col-md-12">
				<a href="adicionar.php" data-toggle="tooltip" data-placement="top" class="btn btn-primary">
					<span class="glyphicon glyphicon-check" aria-hidden="true"></span>
					Adicionar usuário
				</a>
			</div>
		</div>

		<table class="table table-striped">
			<thead>
				<tr>
					<th class="w300">Nome</th>
					<th>CPF</th>
					<th>Login</th>
					<th>Ativo?</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php if (count($dados["dados"]) > 0): ?>
					<?php foreach ($dados["dados"] as $key => $value): ?>
						<tr <?=($value["ativo"] != 1 ? "class='erro'" : "");?> >
							<td><?=utf8_encode($value["nome"]);?></td>
							<td><?=$value["cpf"];?></td>
							<td><?=$value["email"];?></td>
							<td><?=($value["ativo"] == 1 ? "SIM" : "NÃO");?></td>
							<td>
								<div class="pull-right">
									<a href="<?=$base_url."view/usuarios/adicionar.php?cd=".$value["id"];?>" title="Editar" data-toggle="tooltip" data-placement="top" class="btn btn-info btn-xs">
										<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
									</a>

									<a href="#" title="Excluir" rel="<?=$value["id"];?>" data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs excluir">
										<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
									</a>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>

		<?php echo $dados["paginacao"]; ?>
	</div> <!-- /.col-md-12 -->
</div> <!-- /.row -->


<!-- MODAL CONFIRMAÇÃO DE EXCLUSÃO -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_confirmacao">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<form action="<?=$base_url."acoes/acoes.php";?>" method="POST" accept-charset="utf-8">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Confirmação de exclusão</h4>
				</div>
				<div class="modal-body">
						<p>
							<b>Atenção: </b><br />
							Deseja excluir o registro selecionado?
						</p>
						<input type="hidden" name="codigo" value="" id="codigo" />
						<input type="hidden" name="class" value="<?=base64_encode("UsuarioController");?>" />
						<input type="hidden" name="method" value="<?=base64_encode("excluir");?>" />
						<input type="hidden" name="path" value="<?=base64_encode("usuarios");?>" />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					<button type="submit" class="btn btn-primary">Confirmar</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php include "../../incs/rodape.php"; ?>