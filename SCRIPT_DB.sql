create database testeMVC;
use testeMVC;

create table usuario(
	id int(11) primary key auto_increment,
    nome varchar(255) not null,
    email varchar(255) not null,
    senha varchar(255) not null,
    ativo tinyint(1) default 1, 
    cpf varchar(255) not null
)engine=INNODB;

-- USUARIO: admin@admin.com.br
-- SENHA: admin
insert into usuario(nome,email,senha,ativo,cpf) values("admin","admin@admin.com.br","YWRtaW4=","123.456.789-00",1);