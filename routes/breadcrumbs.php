<?php

use App\Models\Projeto;
use App\Models\Role;
use App\Models\Cliente;
use App\Models\ImagemTipo;
use App\Models\Job;
use App\Models\Task;
use App\Models\TipoJob;
use App\Models\Imagem;
use App\Models\GrupoImagem;
use App\Models\CentroDeCusto;
use App\Models\CategoriaDeCusto;
use App\Models\Habilidade;
use App\User;


// --- Home ---------------------------------------
Breadcrumbs::for('home', function ($trail) {
    $trail->push( __('messages.Home'), route('home'));
});


// --- Membros ---------------------------------------
// Home > Todos os membros
Breadcrumbs::for('todos os membros', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Todos os membros'), route('users.index'));
});

// Home > Todos os membros > Novo membro
Breadcrumbs::for('novo membro', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Novo membro'), route('users.create'));
});


// Home > Todos os Membros > [membro] > Editar
// Breadcrumbs::for('editar membro', function ($trail, Usuario $usuario) {
//     $trail->parent('todos os membros', $usuario);
//     $trail->push( __('messages.Editar membro'), route('users.edit', $usuario->id));
// });

// Home > Todos os membros > [detalhes do perfil]
Breadcrumbs::for('perfil', function ($trail, User $user) {
    $trail->parent('home');
    $trail->push($user->name, route('users.show', encrypt($user->id)));
});


// --- Políticas ---------------------------------------
// Home > Todas as politicas
Breadcrumbs::for('todas as politicas', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Políticas de acesso'), route('politicas.index'));
});

// Home > Todas as politicas > [detalhes da politica]
// Breadcrumbs::for('detalhe-politica', function ($trail Role $role) {
//     $trail->parent('todas as politicas');
//     $trail->push($role->name, route('politicas.show', $role->id));
// });


// --- Clientes ---------------------------------------
// Home > Todos os clientes
Breadcrumbs::for('todos os clientes', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Todos os clientes'), route('clientes.index'));
});

// Home > Todos os clientes > Novo cliente
Breadcrumbs::for('novo cliente', function ($trail) {
    $trail->parent('todos os clientes');
    $trail->push( __('messages.Novo cliente'), route('clientes.create'));
});

// Home > Todos os clientes > [cliente] > Editar
Breadcrumbs::for('editar cliente', function ($trail, Cliente $cliente) {
    $trail->parent('todos os clientes', $cliente);
    $trail->push( __('messages.Editar'), route('clientes.edit', encrypt($cliente->id)));
});

// Home > Todos os clientes > [detalhes do cliente]
Breadcrumbs::for('detalhe-cliente', function ($trail, Cliente $cliente) {
    $trail->parent('todos os clientes');
    $trail->push($cliente->nome_fantasia, route('clientes.show', encrypt($cliente->id)));
});

// --- Projetos ---------------------------------------
// Home > Todos os projetos
Breadcrumbs::for('todos os projetos', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Todos os projetos'), route('projetos.index'));
});

// Home > Todos os projetos > Projeto em andamento
Breadcrumbs::for('projeto andamento', function ($trail) {
    $trail->parent('todos os projetos');
    $trail->push( __('messages.Projetos em andamento'), route('projetos.andamento'));
});

// Home > Todos os projetos > Projetos concluídos
Breadcrumbs::for('projetos concluidos', function ($trail) {
    $trail->parent('todos os projetos');
    $trail->push( __('messages.Projetos concluídos'), route('projetos.concluidos'));
});

// Home > Todos os projetos > Novo projeto
Breadcrumbs::for('novo projeto', function ($trail) {
    $trail->parent('todos os projetos');
    $trail->push( __('messages.Novo projeto'), route('projetos.create'));
});

// Home > Todos os projetos > [detalhes do projeto]
Breadcrumbs::for('detalhe-projeto', function ($trail, Projeto $projeto) {
    $trail->parent('todos os projetos');
    $trail->push($projeto->nome, route('projetos.show', encrypt($projeto->id)));
});

// Home > Todos os projetos > [projeto] > Editar
Breadcrumbs::for('editar projeto', function ($trail, Projeto $projeto) {
    $trail->parent('detalhe-projeto', $projeto);
    $trail->push( __('messages.Editar'), route('projetos.edit', encrypt($projeto->id)));
});

// Home > Todos os projetos > [projeto] > Adicionar arquivos ao projeto
Breadcrumbs::for('adicionar arquivo', function ($trail, Projeto $projeto) {
    $trail->parent('detalhe-projeto', $projeto);
    $trail->push( __('messages.Adicionar arquivo'), route('projeto.add.arquivo', encrypt($projeto->id)));
});

// Home > Todos os projetos > [projeto] > Adicionar imagens ao projeto
Breadcrumbs::for('adicionar imagem', function ($trail, Projeto $projeto) {
    $trail->parent('detalhe-projeto', $projeto);
    $trail->push( __('messages.Adicionar imagem'), route('imagens.add', encrypt($projeto->id)));
});

// Home > Todos os projetos > [projeto] > Criar job
Breadcrumbs::for('criar job', function ($trail, Projeto $projeto) {
    $trail->parent('detalhe-projeto', $projeto);
    $trail->push( __('messages.Criar job'), route('jobs.create', encrypt($projeto->id)));
});

// Home > Todos os projetos > [projeto] > Adicionar arquivos e imagens
Breadcrumbs::for('adicionar arquivo imagem', function ($trail, Projeto $projeto) {
    $trail->parent('detalhe-projeto', $projeto);
    $trail->push( __('messages.Vincular arquivos e imagens'), route('projeto.vincular.img.arquivo', encrypt($projeto->id)));
});


// --- Tipos de Imagens ---------------------------------------
// Home > Todos os tipos de imagens
Breadcrumbs::for('todos tipos imagens', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Todos tipos de imagens'), route('tiposimagens.index'));
});

// Home > Novo tipo imagem
Breadcrumbs::for('novo tipo imagem', function ($trail) {
    $trail->parent('todos tipos imagens');
    $trail->push( __('messages.Novo tipo de imagem'), route('tiposimagens.create'));
});

// Home > Todos os tipos de imagens > [detalhes do tipoimagem]
Breadcrumbs::for('detalhe-tipoimagem', function ($trail, ImagemTipo $tipo_imagem) {
    $trail->parent('todos tipos imagens');
    $trail->push($tipo_imagem->nome, route('tiposimagens.show', encrypt($tipo_imagem->id)));
});

// Home > Todos os tipos de imagens > [tipoimagem] > Editar
Breadcrumbs::for('editar tipoimagem', function ($trail, ImagemTipo $tipo_imagem) {
    $trail->parent('detalhe-tipoimagem', $tipo_imagem);
    $trail->push( __('messages.Editar'), route('tiposimagens.edit', encrypt($tipo_imagem->id)));
});


// --- Imagens ---------------------------------------
// Home > [projeto] > [detalhes da imagem]
Breadcrumbs::for('detalhe-imagem', function ($trail, Imagem $imagem) {
    $trail->parent('todos os projetos');
    $trail->push($imagem->projeto->nome, route('projetos.show', encrypt($imagem->projeto->id)));
    $trail->push($imagem->nome, route('imagens.show', encrypt($imagem->id)));
});

// Home > [projeto] > [imagem] > Editar
Breadcrumbs::for('editar imagem', function ($trail, Imagem $imagem) {
    $trail->parent('detalhe-imagem', $imagem);
    $trail->push( __('messages.Editar'), route('imagens.edit', encrypt($imagem->id)));
});

// --- Jobs ---------------------------------------
// Home > Jobs em andamento
Breadcrumbs::for('job andamento', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Jobs em andamento'), route('jobs.andamento'));
});

Breadcrumbs::for('job execucao', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Jobs em execução'), route('jobs.execucao'));
});

// Home > Relatórios > Dashboard
Breadcrumbs::for('relatorios.dashboard', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Dashboard'), route('relatorio.dashboard'));
});
// Home > Jobs concluídos
Breadcrumbs::for('jobs consolidados', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Jobs Consolidados'), route('relatorio.consolidado.usuarios'));
});

// Home > Todos os jobs
Breadcrumbs::for('todos os jobs', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Todos os jobs'), route('jobs.index'));
});


// Home > [projeto] > [detalhes do job]
Breadcrumbs::for('detalhe-job', function ($trail, Job $job) {
    //dd($job);
    if($job->avulso){
        $trail->parent('job execucao');
        $trail->push($job->nome);
        $trail->push($job->nome, route('jobs.show', encrypt($job->id)));
    }else{
        $trail->parent('todos os projetos');
        $trail->push($job->imagens->first()->projeto->nome, route('projetos.show', encrypt($job->imagens->first()->projeto->id)));
        $trail->push($job->nome, route('jobs.show', encrypt($job->id)));
    }

});


// Home > Todos os jobs > [job] > Editar
Breadcrumbs::for('editar job', function ($trail, Job $job) {
    $trail->parent('detalhe-job', $job);
    $trail->push( __('messages.Editar'), route('jobs.edit', encrypt($job->id)));
});


// --- Tasks ---------------------------------------
// Home > Todas as tasks
Breadcrumbs::for('todas as tasks', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Todas as tasks'), route('tasks.index'));
});

// Home > Todas as tasks > Nova task
Breadcrumbs::for('nova task', function ($trail) {
    $trail->parent('todas as tasks');
    $trail->push( __('messages.Nova task'), route('tasks.create'));
});

// Home > Todos as Tasks > [detalhes da task]
Breadcrumbs::for('detalhe-task', function ($trail, Task $task) {
    $trail->parent('todas as tasks');
    $trail->push($task->nome, route('tasks.show', encrypt($task->id)));
});

// Home > Todos as tasks > [task] > Editar
Breadcrumbs::for('editar task', function ($trail, Task $task) {
    $trail->parent('detalhe-task', $task);
    $trail->push( __('messages.Editar'), route('tasks.edit', encrypt($task->id)));
});


// --- Tipos de Job ---------------------------------------
// Home > Todos os tipos de jobs
Breadcrumbs::for('todos tipos jobs', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Todos os tipos de jobs'), route('tipojobs.index'));
});

// Home > Todos os tipos de jobs > Novo tipo de job
Breadcrumbs::for('novo tipo job', function ($trail) {
    $trail->parent('todos tipos jobs');
    $trail->push( __('messages.Novo tipo de job'), route('tipojobs.create'));
});

// Home > Todos os tipos de jobs > [detalhes do tipojob]
Breadcrumbs::for('detalhe-tipojob', function ($trail, TipoJob $tipojob) {
    $trail->parent('todos tipos jobs');
    $trail->push($tipojob->nome, route('tipojobs.show', encrypt($tipojob->id)));
});

// Home > Todos os tipos de jobs > [tipojob] > Editar
Breadcrumbs::for('editar tipojob', function ($trail, TipoJob $tipojob) {
    $trail->parent('detalhe-tipojob', $tipojob);
    $trail->push( __('messages.Editar'), route('tipojobs.edit', encrypt($tipojob->id)));
});

// Home > Todos os tipos de jobs > [tipojob] > Adicionar arquivo
Breadcrumbs::for('adicionar arquivo tipojob', function ($trail, TipoJob $tipojob) {
    $trail->parent('detalhe-tipojob', $tipojob);
    $trail->push( __('messages.Adicionar arquivos'), route('tipojob.add.arquivo', encrypt($tipojob->id)));
});


// --- Centro de Custos ---------------------------------------
// Home > Todos os centros de custo
Breadcrumbs::for('todos centros custos', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Todos os centros de custos'), route('centro-custo.index'));
});

// Home > Todos os centros custo > Novo centro custo
Breadcrumbs::for('novo centro custo', function ($trail) {
    $trail->parent('todos centros custos');
    $trail->push( __('messages.Novo centro de custo'), route('centro-custo.create'));
});

// Home > Todos os centros custo > [detalhes do centro custo]
Breadcrumbs::for('detalhe-centrocusto', function ($trail, CentroDeCusto $custo) {
    $trail->parent('todos centros custos');
    $trail->push($custo->nome, route('centro-custo.show', encrypt($custo->id)));
});

// Home > Todos os centros custo > [centro custo] > Editar
Breadcrumbs::for('editar centrocusto', function ($trail, CentroDeCusto $custo) {
    $trail->parent('detalhe-centrocusto', $custo);
    $trail->push( __('messages.Editar'), route('centro-custo.edit', encrypt($custo->id)));
});


// --- Categoria de Custos ---------------------------------------
// Home > Todos as categorias de custo
Breadcrumbs::for('todas categorias custos', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Todas as categorias de custos'), route('categoria-custo.index'));
});

// Home > Todos os centros custo > Novo centro custo
Breadcrumbs::for('nova categoria custo', function ($trail) {
    $trail->parent('todas categorias custos');
    $trail->push( __('messages.Nova categoria de custo'), route('categoria-custo.create'));
});

// Home > Todos os centros custo > [detalhes do centro custo]
Breadcrumbs::for('detalhe-categoriacusto', function ($trail, CategoriaDeCusto $categoria) {
    $trail->parent('todas categorias custos');
    $trail->push($categoria->nome, route('categoria-custo.show', encrypt($categoria->id)));
});

// Home > Todos os centros custo > [centro custo] > Editar
Breadcrumbs::for('editar categoriacusto', function ($trail, CategoriaDeCusto $categoria) {
    $trail->parent('detalhe-categoriacusto', $categoria);
    $trail->push( __('messages.Editar'), route('categoria-custo.edit', encrypt($categoria->id)));
});


// --- Grupo de Imagem ---------------------------------------
// Home > Todos os grupos de imagens
Breadcrumbs::for('todos grupos imagens', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Todos os grupos de imagens'), route('grupo-imagem.index'));
});

// Home > Todos os grupos de imagens > Novo grupo imagem
Breadcrumbs::for('novo grupo imagem', function ($trail) {
    $trail->parent('todos grupos imagens');
    $trail->push( __('messages.Novo grupo de imagem'), route('grupo-imagem.create'));
});

// Home > Todos os grupos de imagens > [detalhes do grupo imagem]
Breadcrumbs::for('detalhe-grupoimagem', function ($trail, GrupoImagem $grupo_imagem) {
    $trail->parent('todos grupos imagens');
    $trail->push($grupo_imagem->nome, route('grupo-imagem.show', encrypt($grupo_imagem->id)));
});

// Home > Todos os grupos de imagens > [detalhes do grupo imagem] > Editar
Breadcrumbs::for('editar grupoimagem', function ($trail, GrupoImagem $grupo_imagem) {
    $trail->parent('detalhe-grupoimagem', $grupo_imagem);
    $trail->push( __('messages.Editar'), route('grupo-imagem.edit', encrypt($grupo_imagem->id)));
});

// --- Habilidades ---------------------------------------
// Home > Todas as habilidades
Breadcrumbs::for('todas as habilidades', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Todas as habilidades'), route('habilidades.index'));
});

// Home > Todas as habilidades > Nova habilidade
Breadcrumbs::for('nova habilidade', function ($trail) {
    $trail->parent('todas as habilidades');
    $trail->push( __('messages.Nova habilidade'), route('habilidades.create'));
});

// Home > Todas as habilidades > [detalhes da habilidade]
Breadcrumbs::for('detalhe-habilidade', function ($trail, Habilidade $hab) {
    $trail->parent('todas as habilidades');
    $trail->push($hab->nome, route('habilidades.show', encrypt($hab->id)));
});

// Home > Todos as habilidades > [habilidade] > Editar
Breadcrumbs::for('editar habilidade', function ($trail, Habilidade $hab) {
    $trail->parent('detalhe-habilidade', $hab);
    $trail->push( __('messages.Editar'), route('habilidades.edit', encrypt($hab->id)));
});


// --- Notificações ---------------------------------------
// Home > Todas as tasks
Breadcrumbs::for('todas as notificacoes', function ($trail) {
    $trail->parent('home');
    $trail->push( __('messages.Notificações'), route('notifications.index'));
});