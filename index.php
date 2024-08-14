

<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['adicionar'])) {
        // Adicionar um novo alimento
        $stmt = $pdo->prepare("INSERT INTO alimentos (nome, categoria, descricao, data_validade, quantidade, unidade_media, preco_unitario, data_entrada, status_alimento, fornecedor_id) VALUES (:nome, :categoria, :descricao, :data_validade, :quantidade, :unidade_media, :preco_unitario, :data_entrada, :status_alimento, :fornecedor_id)");
        $stmt->execute([
            ':nome' => $_POST['nome'],
            ':categoria' => $_POST['categoria'],
            ':descricao' => $_POST['descricao'],
            ':data_validade' => $_POST['data_validade'],
            ':quantidade' => $_POST['quantidade'],
            ':unidade_media' => $_POST['unidade_media'],
            ':preco_unitario' => $_POST['preco_unitario'],
            ':data_entrada' => $_POST['data_entrada'],
            ':status_alimento' => $_POST['status_alimento'],
            ':fornecedor_id' => $_POST['fornecedor_id']
        ]);
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['editar'])) {
        // Editar um alimento existente
        $stmt = $pdo->prepare("UPDATE alimentos SET nome = :nome, categoria = :categoria, descricao = :descricao, data_validade = :data_validade, quantidade = :quantidade, unidade_media = :unidade_media, preco_unitario = :preco_unitario, data_entrada = :data_entrada, status_alimento = :status_alimento, fornecedor_id = :fornecedor_id WHERE id_alimentos = :id_alimentos");
        $stmt->execute([
            ':nome' => $_POST['nome'],
            ':categoria' => $_POST['categoria'],
            ':descricao' => $_POST['descricao'],
            ':data_validade' => $_POST['data_validade'],
            ':quantidade' => $_POST['quantidade'],
            ':unidade_media' => $_POST['unidade_media'],
            ':preco_unitario' => $_POST['preco_unitario'],
            ':data_entrada' => $_POST['data_entrada'],
            ':status_alimento' => $_POST['status_alimento'],
            ':fornecedor_id' => $_POST['fornecedor_id'],
            ':id_alimentos' => $_POST['id_alimentos']
        ]);
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['excluir'])) {
        // Excluir alimentos selecionados
        if (!empty($_POST['ids'])) {
            $ids = implode(',', array_map('intval', $_POST['ids']));
            $stmt = $pdo->query("DELETE FROM alimentos WHERE id_alimentos IN ($ids)");
        }
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }
}
?>
<?php require 'header.php' ?>

<div class="container mt-5">
    <h1 class="titulo-pagina text-center">Gerenciamento de Alimentos</h1>

    <div class="usuario-adm">
        <!-- Formulário para adicionar um novo alimento -->
        <h2>Adicionar Alimento</h2>
        <form method="post" action="" class="row g-3">
            <div class="col-md-6">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" name="nome" required>
            </div>
            <div class="col-md-6">
                <label for="categoria" class="form-label">Categoria:</label>
                <input type="text" class="form-control" name="categoria" required>
            </div>
            <div class="col-md-12">
                <label for="descricao" class="form-label">Descrição:</label>
                <textarea name="descricao" class="form-control" rows="3" required></textarea>
            </div>
            <div class="col-md-6">
                <label for="data_validade" class="form-label">Data de Validade:</label>
                <input type="date" class="form-control" name="data_validade" required>
            </div>
            <div class="col-md-6">
                <label for="quantidade" class="form-label">Quantidade:</label>
                <input type="number" class="form-control" name="quantidade" required>
            </div>
            <div class="col-md-6">
                <label for="unidade_media" class="form-label">Unidade de Medida:</label>
                <input type="text" class="form-control" name="unidade_media" required>
            </div>
            <div class="col-md-6">
                <label for="preco_unitario" class="form-label">Preço Unitário:</label>
                <input type="text" class="form-control" name="preco_unitario" required>
            </div>
            <div class="col-md-6">
                <label for="data_entrada" class="form-label">Data de Entrada:</label>
                <input type="date" class="form-control" name="data_entrada" required>
            </div>
            <div class="col-md-6">
                <label for="status_alimento" class="form-label">Status:</label>
                <input type="text" class="form-control" name="status_alimento" required>
            </div>
            <div class="col-md-12">
                <label for="fornecedor_id" class="form-label">Fornecedor ID:</label>
                <input type="number" class="form-control" name="fornecedor_id" required>
            </div>
            <div class="col-md-12 text-end">
                <button type="submit" name="adicionar" class="btn btn-laranja">Adicionar</button>
            </div>
        </form>

        <!-- Tabela de alimentos com opções de edição e exclusão -->
        <h2 class="mt-5">Alimentos</h2>
        <form method="post" action="">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Selecionar</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Data de Validade</th>
                        <th scope="col">Quantidade</th>
                        <th scope="col">Unidade de Medida</th>
                        <th scope="col">Preço Unitário</th>
                        <th scope="col">Data de Entrada</th>
                        <th scope="col">Status</th>
                        <th scope="col">Fornecedor ID</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Listar todos os alimentos
                    $stmt = $pdo->query('SELECT * FROM alimentos');
                    while ($row = $stmt->fetch()) {
                        echo "<tr>
                            <td><input type='checkbox' name='ids[]' value='{$row['id_alimentos']}'></td>
                            <td>{$row['nome']}</td>
                            <td>{$row['categoria']}</td>
                            <td>{$row['descricao']}</td>
                            <td>{$row['data_validade']}</td>
                            <td>{$row['quantidade']}</td>
                            <td>{$row['unidade_media']}</td>
                            <td>{$row['preco_unitario']}</td>
                            <td>{$row['data_entrada']}</td>
                            <td>{$row['status_alimento']}</td>
                            <td>{$row['fornecedor_id']}</td>
                            <td>
    <!-- Formulário de edição -->
    <form method='post' action='' style='display:inline' class='text-center'>
        <div class='row justify-content-center'>
            <input type='hidden' name='id_alimentos' value='{$row['id_alimentos']}'>
            <div class='col-md-6 mb-2'>
                <input type='text' class='form-control' name='nome' value='{$row['nome']}' required>
            </div>
            <div class='col-md-6 mb-2'>
                <input type='text' class='form-control' name='categoria' value='{$row['categoria']}' required>
            </div>
            <div class='col-md-12 mb-2'>
                <textarea class='form-control' name='descricao' required>{$row['descricao']}</textarea>
            </div>
            <div class='col-md-6 mb-2'>
                <input type='date' class='form-control' name='data_validade' value='{$row['data_validade']}' required>
            </div>
            <div class='col-md-6 mb-2'>
                <input type='number' class='form-control' name='quantidade' value='{$row['quantidade']}' required>
            </div>
            <div class='col-md-6 mb-2'>
                <input type='text' class='form-control' name='unidade_media' value='{$row['unidade_media']}' required>
            </div>
            <div class='col-md-6 mb-2'>
                <input type='text' class='form-control' name='preco_unitario' value='{$row['preco_unitario']}' required>
            </div>
            <div class='col-md-6 mb-2'>
                <input type='date' class='form-control' name='data_entrada' value='{$row['data_entrada']}' required>
            </div>
            <div class='col-md-6 mb-2'>
                <input type='text' class='form-control' name='status_alimento' value='{$row['status_alimento']}' required>
            </div>
            <div class='col-md-12 mb-2'>
                <input type='number' class='form-control' name='fornecedor_id' value='{$row['fornecedor_id']}' required>
            </div>
            <div class='col-md-12'>
                <button type='submit' name='editar' class='btn btn-laranja'>Editar</button>
            </div>
        </div>
    </form>
</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
            <!-- Botão para excluir os alimentos selecionados -->
            <button type="submit" name="excluir" class="btn btn-danger">Excluir Selecionados</button>
            <a href="graficos.php"  class="btn btn-primary" >Ir para os gráficos</a>
        </form>
    </div>
</div>

</body>
</html>