<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li>
					<a href="<?=$base_url;?>view/index.php" title="Voltar para Home" data-toggle="tooltip" data-placement="bottom">
						<span class="glyphicon glyphicon-home" aria-hidden="true"></span>
						Home
					</a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
						Usuários <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="<?=$base_url;?>view/usuarios/index.php">Listar</a></li>
						<li><a href="<?=$base_url;?>view/usuarios/adicionar.php">Cadastrar</a></li>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="<?=$base_url;?>view/sair.php">
						<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
						Sair
					</a>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container -->
</nav>