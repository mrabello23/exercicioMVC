<?php
	require "../../config.php";
	include "../../incs/cabecalho.php";
	include "../../incs/menu_superior.php";

	$dadosUsuario = array(
		"dados" => array(
			0 => array(
				"id" => "",
				"nome" => "",
				"email" => "",
				"ativo" => 1,
				"cpf" => "",
				"senha" => ""
			)
		)
	);

	if (isset($_GET["cd"]) && !empty($_GET["cd"])) {
		$user = new UsuarioController();
		$dadosUsuario = $user->listar($_GET["cd"]);
	}
?>

<div class="row">
	<div class="col-md-12">
		<h2>Cadastrar Usuários</h2><hr />
		<div class="alert alert-danger alert-dismissible hide alert-form" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>Atenção!</strong> Preencha todos os campos do formulário.
		</div>

		<?php if ( isset($_SESSION['msg_tipo']) && isset($_SESSION['msg']) ): ?>
			<div class="alert alert-<?=($_SESSION['msg_tipo'] == "Erro" ? "danger" : "success");?> alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<strong><?=$_SESSION['msg_tipo'];?></strong> <?=$_SESSION['msg'];?>
			</div>
			<?php unset($_SESSION['msg_tipo']); unset($_SESSION['msg']); ?>
		<?php endif; ?>

		<p class="vermelho"><span class="tamanho20">*</span> Obrigatórios</p>
		<form action="<?=$base_url."acoes/acoes.php";?>" method="POST" accept-charset="utf-8" id="formCadUser">
			<div class="row">
				<div class="col-md-5 form-group">
					<label for="nome">Nome <span class="vermelho tamanho20">*</span>: </label>
					<input type="text" name="nome" class="form-control" value="<?=utf8_encode($dadosUsuario["dados"][0]["nome"]);?>"/>
				</div>
				<div class="col-md-5 form-group">
					<label for="email">E-mail / Login <span class="vermelho tamanho20">*</span>: </label>
					<input type="text" name="email" class="form-control" value="<?=$dadosUsuario["dados"][0]["email"];?>"/>
				</div>
			</div> <!-- /.row -->
			<div class="row">
				<div class="col-md-4 form-group">
					<label for="senha">Senha <span class="vermelho tamanho20">*</span>: </label>
					<input type="text" name="senha" class="form-control" value="<?=base64_decode($dadosUsuario["dados"][0]["senha"]);?>"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="cpf">CPF <span class="vermelho tamanho20">*</span>: </label>
					<input type="text" name="cpf" class="form-control cpf" id="cpf" value="<?=$dadosUsuario["dados"][0]["cpf"];?>" placeholder="Apenas números"/>
				</div>
				<div class="col-md-3 form-group">
					<label for="ativo">Ativo <span class="vermelho tamanho20">*</span>: </label>
					<select name="ativo" class="form-control">
						<option value="">Selecione</option>
						<option value="1" <?=($dadosUsuario["dados"][0]["ativo"] == 1 ? "selected" : "");?> >Sim</option>
						<option value="0" <?=($dadosUsuario["dados"][0]["ativo"] == 0 ? "selected" : "");?> >Não</option>
					</select>
				</div>
			</div> <!-- /.row -->

			<div class="row">
				<div class="col-md-12 form-group">
					<label for="">&nbsp;</label><br/>
					<input type="hidden" name="codigo" value="<?=$dadosUsuario["dados"][0]["id"];?>" />
					<input type="hidden" name="class" value="<?=base64_encode("UsuarioController");?>" />
					<input type="hidden" name="method" value="<?=base64_encode("salvar");?>" />
					<input type="hidden" name="path" value="<?=base64_encode("usuarios");?>" />

					<input type="submit" value="Cadastrar" class="btn btn-primary" id="cadastrar"/>
				</div>
			</div> <!-- /.row -->
			<br />
		</form>
	</div> <!-- /.col-md-12 -->
</div> <!-- /.row -->

<?php include "../../incs/rodape.php"; ?>