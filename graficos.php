<?php require 'header.php'?>
<div class="container mt-5">
    <h1 class="text-center">Gerenciamento de Consumo de Alimentos</h1>

    <!-- Formulário para adicionar alimentos -->
    <form method="post" action="" id="form-alimentos" class="row g-3">
        <div class="col-md-6">
            <label for="nome" class="form-label">Nome do Alimento:</label>
            <input type="text" class="form-control" name="nome" required>
        </div>
        <div class="col-md-6">
            <label for="setor" class="form-label">Setor:</label>
            <select class="form-control" name="setor" required>
                <option value="Suporte">Suporte</option>
                <option value="RH">RH</option>
                <option value="Logistica">Logística</option>
                <option value="Limpeza">Limpeza</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="quantidade" class="form-label">Quantidade Consumida:</label>
            <input type="number" class="form-control" name="quantidade" required>
        </div>
        <div class="col-md-6">
            <label for="preco_unitario" class="form-label">Preço Unitário:</label>
            <input type="text" class="form-control" name="preco_unitario" required>
        </div>
        <div class="col-md-12 text-end">
            <button type="submit" name="adicionar" class="btn btn-laranja">Adicionar</button>
        </div>
    </form>

    <!-- Filtro por setor -->
    <div class="mt-4">
        <label for="setor_filtro" class="form-label">Filtrar por Setor:</label>
        <select class="form-control" id="setor_filtro">
            <option value="Todos">Todos</option>
            <option value="Suporte">Suporte</option>
            <option value="RH">RH</option>
            <option value="Logistica">Logística</option>
            <option value="Limpeza">Limpeza</option>
        </select>
    </div>

    <!-- Botões para alternar entre consumo semanal e mensal -->
    <div class="mt-4 text-center">
        <button class="btn btn-primary" id="btn-semanal">Consumo Semanal</button>
        <button class="btn btn-secondary" id="btn-mensal">Consumo Mensal</button>
        <button class="btn btn-success" id="btn-gerar-relatorio">Gerar Relatório PDF</button>
        <a href="index.php"  class="btn btn-primary" >Retornar para alimentos</a>

    </div>

    <!-- Gráfico de Consumo -->
    <div class="mt-4">
        <canvas id="grafico-consumo"></canvas>
    </div>
</div>

<script>
// Arrays para armazenar os dados
let alimentos = [];
let setores = [];

// Função para adicionar alimento
document.getElementById('form-alimentos').addEventListener('submit', function (event) {
    event.preventDefault();

    let nome = event.target.nome.value;
    let setor = event.target.setor.value;
    let quantidade = parseInt(event.target.quantidade.value);
    let preco_unitario = parseFloat(event.target.preco_unitario.value);

    let total_gasto_mensal = quantidade * preco_unitario * 4; // Aproximando o mês com 4 semanas
    let total_gasto_semanal = quantidade * preco_unitario;

    alimentos.push({
        nome: nome,
        setor: setor,
        quantidade: quantidade,
        gasto_semanal: total_gasto_semanal,
        gasto_mensal: total_gasto_mensal
    });

    setores.push(setor);

    atualizarGrafico('mensal');
});

// Função para atualizar o gráfico
function atualizarGrafico(periodo) {
    let ctx = document.getElementById('grafico-consumo').getContext('2d');
    
    let dadosFiltrados = alimentos.filter(alimento => {
        let filtroSetor = document.getElementById('setor_filtro').value;
        return filtroSetor === 'Todos' || alimento.setor === filtroSetor;
    });

    let labels = dadosFiltrados.map(alimento => alimento.nome);
    let data = dadosFiltrados.map(alimento => periodo === 'mensal' ? alimento.gasto_mensal : alimento.gasto_semanal);

    if (window.myChart) {
        window.myChart.destroy();
    }

    window.myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Gasto em ' + (periodo === 'mensal' ? 'Mensal' : 'Semanal'),
                data: data,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

//  botões de alternância
document.getElementById('btn-semanal').addEventListener('click', function () {
    atualizarGrafico('semanal');
});

document.getElementById('btn-mensal').addEventListener('click', function () {
    atualizarGrafico('mensal');
});

// Listener para o filtro por setor
document.getElementById('setor_filtro').addEventListener('change', function () {
    atualizarGrafico('mensal');
});
</script>

<script>
document.getElementById('btn-gerar-relatorio').addEventListener('click', async function () {
    const { jsPDF } = window.jspdf;

    const doc = new jsPDF();

    // Função para adicionar uma imagem ao PDF
    function adicionarImagemAoPDF(canvas, titulo) {
        return new Promise((resolve) => {
            canvas.toBlob(function (blob) {
                const img = new Image();
                img.src = URL.createObjectURL(blob);
                img.onload = function () {
                    doc.addImage(img, 'PNG', 10, 10, 180, 100); // Ajuste as coordenadas e tamanho conforme necessário
                    resolve();
                };
            });
        });
    }

    // Captura do gráfico de consumo
    const canvasGrafico = document.getElementById('grafico-consumo');
    const graficoCanvas = await html2canvas(canvasGrafico);
    await adicionarImagemAoPDF(graficoCanvas, 'Gráfico de Consumo');

    // Adiciona uma nova página para o gráfico mensal
    doc.addPage();
    const filtroSetor = document.getElementById('setor_filtro').value;
    const periodo = filtroSetor === 'Todos' ? 'Mensal' : filtroSetor;
    doc.text(`Relatório de Consumo ${periodo}`, 10, 10);

    // Adiciona ao PDF
    const canvasGraficoAtual = document.getElementById('grafico-consumo');
    const graficoAtualCanvas = await html2canvas(canvasGraficoAtual);
    await adicionarImagemAoPDF(graficoAtualCanvas, 'Gráfico de Consumo');

    // Salva o PDF
    doc.save(`Relatorio_Consumo_${periodo}.pdf`);
});
</script>



<!-- Bootstrap JS  -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
