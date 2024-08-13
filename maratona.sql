create table funcionarios(
	id_funcionario int auto_increment primary key,
    nome varchar(100) not null,
    alimentacao varchar(50) not null,
    telefone varchar(15) not null,
    departamento varchar(100) not null
);
create table fornecedores(
		id_fornecedor int auto_increment primary key,
        nome varchar(100) not null,
        endereco varchar(150) not null,
        telefone varchar(15) not null,
        email varchar(150) not null unique
);
create table alimentos(
	id_alimentos int auto_increment primary key,
    nome varchar(100) not null,
    categoria varchar(100) not null,
    descricao text not null,
    data_validade date not null,
	quantidade int not null,
    unidade_media varchar(10) not null,
    fornecedor_id int,
    preco_unitario decimal(10,2) not null,
    data_entrada date not null,
    status_alimento varchar(100) not null,
    foreign key(fornecedor_id) references fornecedores(id_fornecedor)
);

INSERT INTO funcionarios (nome, alimentacao, departamento, telefone)
VALUES ("Ana Beatriz", "Vegetariana", "Suporte","9898765231");


INSERT INTO alimentos (nome, categoria, descricao, data_validade, quantidade, unidade_media, preco_unitario, data_entrada, status_alimento) VALUES
("toscana seara", "Carnes", "Linguiça de churrasco SEARA", "10/08/2024", "30", "kg","20", "04/08/2024", "Perfeito");


INSERT INTO fornecedores (nome, endereco, telefone, email) VALUES ("Fribal", "Rua castelao, 18 ,Calhau", "9898781223", "Fribal@cliente.com")