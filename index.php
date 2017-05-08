<?php
require_once "config.php";
include "incs/cabecalho.php";
?>

<div class="login">
	<h3><u>Teste MVC</u> <br /> Marcel Oliveira</h3><hr/>
	<?php if ( isset($_SESSION['msg_tipo']) && isset($_SESSION['msg']) ): ?>
		<div class="alert alert-<?=($_SESSION['msg_tipo'] == "Erro" ? "danger" : "success");?> alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong><?=$_SESSION['msg_tipo'];?>:</strong> <?=$_SESSION['msg'];?>
		</div>
		<?php unset($_SESSION['msg_tipo']); unset($_SESSION['msg']); ?>
	<?php endif; ?>

	<form class="login-inner" action="entrar.php" method="POST">
		<input type="email" class="form-control email" id="email-input" placeholder="Digite o Email" name="login" />
		<input type="password" class="form-control password" id="password-input" placeholder="Digite a Senha" name="senha" />
		<input class="btn btn-block btn-lg btn-primary submit" type="submit" value="Entrar" />
	</form>

	<a href="#" class="btn btn-sm btn-default forgot">Esqueceu a senha?</a>
</div>

<?php
	session_destroy();
	include "incs/rodape.php";
?>