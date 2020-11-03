<?php

# Rota para saber versão do Laravel

use App\Http\Controllers\JobController;

Route::get('laraversion', 	function() {return "<p>Laravel version: ".app()::VERSION."</p>";}  )->name('laraversion');

// Route::get('/', function () { return view('welcome'); });
Route::get('/', 'Auth\LoginController@showLoginForm');

Auth::routes();

# Termos de Uso
Route::get('/terms', 'TermController@termosDeUso')->name('termos.de.uso');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('efetua.login');

# form criar usuário vendedor
Route::get('/register/admin', 'Auth\RegisterVendedorController@formVendedor')->name('registrar.vendedor');
# form criar usuário freelancer
Route::get('/register/freela', 'Auth\RegisterFreelaController@formFreela')->name('registrar.freela');


# ativar usuário -> vai com a url que foi solicitado
Route::get('/verify-user/{code}', 'Auth\RegisterController@activateUser')->name('activate.user');

# app-raiz. home
Route::get('/app', 'HomeController@index')->name('home');
Route::get('/app/home/teste', 'HomeTestController@index')->name('home-teste');

# rota get dashboard - projetos em andamento
Route::get('/app/item/{tipo}', 'HomeTestController@itemHome')->name('home.item');

#test
Route::get('/app-test', 'HomeTestController@index')->name('home-test'); 

# Rotas do Resource Planos
Route::resource('app/planos', 'PlanoController');

# Plano do Usuário
Route::get('user/{id}/plano', 'UserController@plano');

# Resource do User
Route::resource('app/users', 'UserController');
Route::get('app/membros/lista/{?tipo}', 'UserController@lista_por_tipos')->name('membros.lista.tipo');
Route::get('app/membros/equipe', 'UserController@equipe')->name('membros.equipe');
Route::get('app/membros/freelas', 'UserController@freelas')->name('membros.freelas');
Route::get('app/membros/coordenadores', 'UserController@coordenadores')->name('membros.coordenadores');
Route::get('app/membros/avaliadores', 'UserController@avaliadores')->name('membros.avaliadores');
Route::get('app/membros/admins', 'UserController@admins')->name('membros.admins');
Route::get('app/membros/ver/{id}', 'UserController@show')->name('visualizar.membro');

# Reenviar ativação de conta
Route::post('app/user/atribuir/plano', 'PlanoController@atribuirPlano')->name('user.atribuir.plano');

# Reenviar ativação de conta
Route::post('app/user/resend-activation', 'UserController@reenviarAtivacaoDeConta')->name('envia.ativacao.user');

# Galeria
Route::delete('app/user/galeria/excluir/{imagem_id}', 'UserController@ExcuirImagem')->name('delete.imagem.galeria');

# coordernador para publicador 
Route::get('app/users/lista/coordenadores', 'UserController@listaCoordenador')->name('coordernadores.lista');
Route::get('app/users/cria/coordenador', 'UserController@createCoordenador')->name('users.create.coordenador');

# Nova Senha e primeiro acesso
Route::get('app/user/nova/senha/{id?}', 'UserController@novaSenha')->name('user.nova.senha');
Route::put('app/user/gravar/senha', 'UserController@gravarSenha')->name('user.gravar.senha');
Route::post('app/user/encerrar/conta/{id}', 'UserController@encerrarConta')->name('user.encerrar.conta');
Route::post('app/user/transferir/conta/{id}', 'UserController@transferirConta')->name('user.transferir.deletar');


# Mudar o status do usuário
Route::get('app/user/muda/status/{id?}', 'UserController@mudarStatus')->name('user.mudar.status');
# Exclui usuário
Route::delete('app/user/deleta/{id?}', 'UserController@destroy')->name('user.deletar');

# 

# Resource de Configurações
Route::resource('app/configuracoes', 'ConfiguracaoController');
# Factory Test de Configurações
Route::get('factory/configuracoes', 'ConfiguracaoController@factory');

#UserConfig Add-Slot
Route::post('app/user/add/slot/', 'UserConfiguracoesController@aumentarSlotJobLivre')->name('user.add.job.slot');
#UserConfig Add-Slot-Candidatura
Route::post('app/user/add/slot/candidatura', 'UserConfiguracoesController@aumentarSlotJobCandidatura')->name('user.add.job.slot.candidatura');


# Resource do Plano
Route::resource('app/planos', 'PlanoController');



# Resource do Cliente
Route::resource('app/clientes', 'ClienteController');
Route::post('app/projeto/add/faturamento', 'ClienteFaturamentoController@store')->name('cliente.faturamento.add');
Route::get('app/cliente/pega/faturamentos/', 'ClienteFaturamentoController@show')->name('cliente.faturamento.show');
Route::post('app/cliente/atualizar/faturamentos/', 'ClienteFaturamentoController@update')->name('update.cliente.faturamento');
Route::delete('app/cliente/deleta/faturamentos/', 'ClienteFaturamentoController@destroy')->name('deletar.cliente.faturamento');



Route::post('app/cliente/vincular/faturamento', 'ClienteFaturamentoController@vincularProjeto')->name('cliente.faturamento.vincular');
# Pega Projetos de um cliente e retorna em json
Route::get('app/clientes/projetos/json', 'ClienteController@listarProjetos')->name('cliente.projetos.json');
# Factory Test de Cliente
Route::get('factory/cliente', 'ClienteController@factory');

# Resource de Habilidades
Route::resource('app/habilidades', 'HabilidadeController');

# Resource do Projeto
Route::resource('app/projetos', 'ProjetoController');
Route::get('app/andamento/projetos', 'ProjetoController@emAndamento')->name('projetos.andamento');
Route::get('app/concluidos/projetos', 'ProjetoController@concluidos')->name('projetos.concluidos');
Route::post('app/projeto/{id}/concluir', 'ProjetoController@concluir')->name('projeto.concluir');
Route::post('app/projeto/{id}/reabrir', 'ProjetoController@reabrir')->name('projeto.reabrir');

Route::get('app/projetos/{id}/add/imagens',  'ProjetoController@addImg')->name('imagens.add');
// todo: Verificar essas duas rotas se não são redundantes
// Route::get('app/projetos/{id?}/add/arquivos', 'ProjetoController@addArquivo')->name('projeto.add.arquivos');
Route::get('app/projetos/{id?}/add/arquivo',  'ProjetoController@addArquivo')->name('projeto.add.arquivo');
Route::get('app/projetos/{id?}/vincular/imagem/arquivo',  'ProjetoController@vincularImgArquivo')->name('projeto.vincular.img.arquivo');
Route::post('app/projetos/{id}/gravar/arquivos', 'ProjetoController@gravarArquivo')->name('projeto.gravar.arquivo');


# Imagens de um projeto específico
Route::get('app/projeto/imagens', 'ProjetoController@listarImagens')->name('projeto.imagens');
Route::get('app/projeto/imagens/r00', 'ProjetoController@listarImagensComR00')->name('projeto.imagens.r00');

# Mídias de um projeto específico
Route::get('app/projeto/arquivos', 'ProjetoController@listarArquivos')->name('projeto.arquivos');
Route::get('app/projeto/desvincular/arquivos/{arquivo?}/{projeto?}', 'ProjetoController@desvincularArquivos')->name('projeto.desvincular.arquivos');
# Factory Test de Projeto
Route::get('factory/projeto', 'ProjetoController@factory');


# Resource do Tipo de Imagem
Route::resource('app/tiposimagens', 'ImagemTipoController');

# Factory Test de Tipo de Imagem
Route::get('factory/tiposimagens', 'ImagemTipoController@factory');
Route::post('app/tipoimagens/transferir/imagens/{id}', 'ImagemTipoController@transferirImagens')->name('tipoimagems.transfer.deletar');


# Resource de Imagem
Route::resource('app/imagens', 'ImagemController');

Route::get('app/imagem/add/arquivos/{id?}', 'ImagemController@addArquivo')->name('imagem.add.arquivo');
Route::get('app/imagem/{img_id}/add/job/', 'ImagemController@addJob')->name('imagem.add.job');
Route::get('app/imagem/{img_id}/add/finalizador/{finalizador_id?}', 'ImagemController@addFinalizador')->name('imagem.add.finalizador');
Route::post('app/imagens/vincular/arquivos/', 'ImagemController@vincularArquivos')->name('imagem.vincular.arquivos');
Route::get('app/imagens/desvincular/arquivos/{arquivo?}/{imagens?}', 'ImagemController@desvincularArquivos')->name('imagem.desvincular.arquivos');
Route::get('app/imagem/progresso/{id?}', 'ImagemController@progresso')->name('progresso.imagem');
Route::post('app/imagem/{id}/concluir/', 'ImagemController@concluir')->name('imagem.concluir');
Route::post('app/imagem/{id}/reabrir/', 'ImagemController@reabrir')->name('imagem.reabrir');

Route::get('app/projeto/{pro_id}/imagem/{img_id}/add/job/', 'JobController@create')->name('job.create.projeto.imagem');



# Factory Test de Imagem
Route::get('factory/imagens', 'ImagemController@factory');

#Rotas Midias
Route::get('app/arquivos/add', 'MidiaController@create')->name('add.arquivo');
Route::get('app/arquivos/gravar', 'MidiaController@store')->name('midia.store');

#Rotas Tasks
Route::resource('app/tasks', 'TaskController');
# Executar Task
Route::get('executar/task/{job_id}/{task_id}', 'TaskController@executarTask')->name('executar.task');
# Desfazer Task
Route::get('desfazer/task/{job_id}/{task_id}', 'TaskController@desfazerTask')->name('desfazer.task');


#Rotas Tasks Revisoes Jobs
Route::resource('app/tasksrevisoesjob', 'JobRevisoesTasksController');
# Executar Task
Route::get('executar/taskrevisaojob/{job_id}/{task_id}', 'JobRevisoesTasksController@executarTask')->name('executar.task.revioes.job');
# Desfazer Task
Route::get('desfazer/taskrevisaojob/{job_id}/{task_id}', 'JobRevisoesTasksController@desfazerTask')->name('desfazer.task.revioes.job');



#Rotas de Tipos de Job
Route::resource('app/tipojobs', 'TipoJobController');
Route::get('app/tipojob/detalhes', 'TipoJobController@dados')->name('tipojobs.dados');
Route::get('app/tipojobs/{id?}/add/arquivos', 'TipoJobController@addArquivos')->name('tipojob.add.arquivos');
Route::get('app/tipojobs/{id?}/add/arquivo',  'TipoJobController@addArquivo')->name('tipojob.add.arquivo');
Route::post('app/tipojobs/vincular/arquivos/', 'TipoJobController@vincularArquivos')->name('tipojobs.vincular.arquivos');
Route::get('app/tipojobs/desvincular/arquivos/{arquivo?}/{id?}', 'TipoJobController@desvincularArquivos')->name('tipojobs.desvincular.arquivos');
Route::post('app/tipojobs/{id}/gravar/arquivos', 'TipoJobController@gravarArquivo')->name('tipojobs.gravar.arquivo');
Route::post('app/tipojobs/transferir/jobs/{id}', 'TipoJobController@transferirJobs')->name('tipojobs.transfer.deletar');


#rotas de deliverys format
Route::resource('app/deliveryformat', 'DeliveryFormatController');
Route::get('app/tipodelivery/detalhes', 'DeliveryFormatController@dados')->name('tipodelivery.dados');




# Resource de Job
Route::resource('app/jobs', 'JobController');
Route::post('app/jobs/add-file', 'JobController@addArquivo')->name('add.arquivo.job');
Route::get('app/jobs-abertos', 'JobController@abertos')->name('jobs.abertos');
Route::get('app/jobs-em-execucao', 'JobController@emExecucao')->name('jobs.execucao');
Route::get('app/jobs-recusados', 'JobController@recusados')->name('jobs.recusados');
Route::get('app/andamento/jobs', 'JobController@emAndamento')->name('jobs.andamento');
Route::get('app/todos/jobs', 'JobController@todos')->name('jobs.todos');
Route::get('app/concluidos/jobs', 'JobController@concluidos')->name('jobs.concluidos');
Route::get('app/parados/jobs', 'JobController@parados')->name('jobs.parados');
Route::get('app/jobs-aguardando-pagamento', 'JobController@aguardandoPagamento')->name('jobs.aguardando.pagamento');
Route::get('app/jobs-em-candidatura', 'JobController@emCandidatura')->name('jobs.em.candidatura');
#rotas para Jobs Recusados
// Route::resource('app/jobs-recusados', 'JobRecusadoController');
Route::post('app/concluir-jobs-lista', 'JobController@jobsConcluirLista')->name('jobs.concluir.lista');



# Rota para criar novo job do projeto
Route::get('app/projetos/{proj_id}/add/job', 'JobController@create')->name('jobs.create');
Route::get('app/job/progresso/{id?}', 'JobController@progresso')->name('progresso.job');
// Route::post('app/job/{id}/concluir', 'JobController@concluir')->name('job.concluir');
// Route::post('app/job/{id}/reabrir', 'JobController@reabrir')->name('job.reabrir');

# rota para progresso revisao job
Route::get('app/job/revisao/progresso/{id?}', 'JobController@progressoRevisao')->name('progresso.revisao.job');


# rota para mudar o status do job
Route::post('app/job/{id}/{novostatus}/mudar-status', 'JobController@mudarStatus')->name('job.mudarStatus');
Route::post('app/job/mudar-status-varios', 'JobController@mudarStatusVarios')->name('job.mudarStatus.varios');

# prorrogar data de entrega do job
Route::post('app/job/{id}//prorrogar-data-entrega', 'JobController@prorrogarDataEntrega')->name('job.prorrogarDataEntrega');

# prorrogar data de propostas do job
Route::post('app/job/{id}//prorrogar-data-proposta', 'JobController@prorrogarDataProposta')->name('job.prorrogar.data.proposta');


# Rota para criar novo job individual
Route::get('app/jobs/novo/avulso',  'JobController@createAvulso')->name('job.avulso.create');
Route::post('app/jobs/novo/avulso/gravar', 'JobController@storeAvulso')->name('job.avulso.store');
Route::get('app/pega/job/{job_id}', 'JobController@freelaPegaJob')->name('freela.pega.job');

# Rota para salvar imagem 
Route::post('app/jobs/upload/avaliacao/{id}', 'JobController@uploadAvaliacao')->name('jobs.upload.avaliacao');

# Rota para salvar HR
Route::post('app/jobs/upload/hr/{id}', 'JobController@uploadHR')->name('jobs.upload.hr');
# Rota para solcitar HR
Route::post('app/jobs/solicitar/hr/{id}', 'JobController@solicitarHR')->name('job.solicitar.hr');

# Rota para revisao do arquivo de avaliação
Route::get('app/avaliacao/{avaliacao_id}/nova/revisao{tira_arquivos}', 'AvaliacaoRevisaoController@create')->name('nova.revisao.avalicao');
# Rota para store da revisão da avaliação
Route::post('app/avalicao/salvar/revisao', 'AvaliacaoRevisaoController@store')->name('revisao.avaliacao.store');
# Rota para editar a Revisão  da avaliação
Route::get('app/avaliacao/editar/revisao/{revisao_id}', 'AvaliacaoRevisaoController@edit')->name('editar.revisao.avaliacao');
# Rota para atualizar a Revisão  da avaliação
Route::post('app/avaliacao/atualizar/revisao/{revisao_id}', 'AvaliacaoRevisaoController@update')->name('update.revisao.avaliacao');
# Rota para visualizar Revisão  da avaliação 
Route::get('app/avaliacao/visualizar/revisao/{revisao_id}{tira_arquivo}', 'AvaliacaoRevisaoController@show')->name('visualizar.revisao.avaliacao');
# Rota para excluir Revisão da avaliação 
Route::delete('app/avaliacao/excluir/revisao/{revisao_id}', 'AvaliacaoRevisaoController@destroy')->name('excluir.revisao.avaliacao');
# Rota para deletar PIN da revisão da avaliacao
Route::get('app/avaliacao/revisao/deleta/marcador/{marcador_id}', 'AvaliacaoRevisaoController@destroyMarcador')->name('deletar.pin.revisao.avaliacao');
# Rota para deletar imagem da revisão da avaliacao
Route::get('app/avaliacao/revisao/deleta/midia/{midia_id}/pin/{marcador_id}', 'AvaliacaoRevisaoController@destroyMarcadorMidia')->name('deletar.midia.pin.revisao.avaliacao');



# Desvincular arquivo do Job
Route::get('app/job/{job}/desvincular/arquivo/{arquivo}', 'JobController@desvincularArquivo')->name('job.desvincular.arquivo');


# Resource de Role - CRUD Permissions
Route::resource('app/politicas', 'RoleController');
Route::post('app/politicas/atribuir', 'RoleController@atribuirPolitica')->name('atribuir.politica');
Route::post('app/politicas/atribuir/permissao', 'RoleController@atribuirPermissao')->name('atribuir.permissao');
Route::get('app/politicas/usuario/permissao', 'RoleController@usuarioPermissao')->name('usuario.permissao');
Route::post('app/politicas/atribuir/permissao/usuario{id}', 'RoleController@usuarioPermissaoUpdate')->name('atribuir.permissao.usuario');
Route::post('app/politicas/nova/permissao', 'RoleController@novaPermissao')->name('politicas.nova.permissao');
Route::get('app/permissoes/politicas', 'RoleController@permissaoPorPollitica')->name('politicas.por.permissao');

# Rota para adicionar Revisão em Imagem 
Route::post('app/imagens/{id}/adiciona/revisao/{rev_num}', 'ImagemController@adicionaRevisao')->name('adiciona.revisao.imagem');
Route::get('app/imagem/{imagem_id}/nova/revisao', 'RevisaoController@create')->name('nova.revisao.imagem');
Route::post('app/imagem/salvar/revisao', 'RevisaoController@store')->name('revisao.imagem.store');


# Rota para visualizar Revisão em Imagem 
Route::get('app/imagem/visualizar/revisao/{revisao_id}', 'RevisaoController@show')->name('visualizar.revisao.imagem');
# Rota para excluir Revisão em Imagem 
Route::delete('app/imagem/{imagem_id}/excluir/revisao/{revisao_id}', 'RevisaoController@destroy')->name('excluir.revisao.imagem');
# Rota para editar a Revisão
Route::get('app/imagem/editar/revisao/{revisao_id}', 'RevisaoController@edit')->name('editar.revisao.imagem');
# Rota para atualizar a Revisão
Route::post('app/imagem/atualizar/revisao/{revisao_id}', 'RevisaoController@update')->name('update.revisao.imagem');

# Rota para deletar PIN
Route::get('app/revisao/deleta/marcador/{marcador_id}', 'RevisaoController@destroyMarcador')->name('deletar.pin.revisao');
# Rota para deletar imagem do pin vinda do banco
Route::get('app/revisao/deleta/midia/{midia_id}/pin/{marcador_id}', 'RevisaoController@destroyMarcadorMidia')->name('deletar.midia.pin.revisao');





// Rotas da conta Paypal 
Route::post('app/conta-paypal/user/nova', 'UserContaController@storePaypal')->name('add.conta.paypal');
Route::get('app/conta-paypal/user/editar', 'UserContaController@edit')->name('editar.conta.paypal');

// Rotas da conta bancaria 
Route::post('app/conta/user/nova', 'UserContaController@store')->name('nova.conta.user');
Route::get('app/conta/user/visualizar', 'UserContaController@index')->name('visualizar.conta.user');
# Rotas para Conta do Usuario
Route::resource('app/minha-conta', 'UserContaController');

# Rotas para confirmação de pagamento freelancer
Route::post('app/user/confirma/pagamento/freelancer', 'UserFinanceiroController@confirmaPagamentoFreelancer')->name('confirmar.pagamento.freelancer');

Route::get('app/conta/user/visualizar', 'UserContaController@index')->name('visualizar.conta.user');

Route::get('app/conta/user/visualizar/movimentacao', 'UserContaController@movimentacao')->name('visualizar-conta-movimentacao');



# Rotas para o centro de custo
Route::resource('app/centro-custo', 'CentroDeCustoController');

# Rotas para a categoria de custo 
Route::resource('app/categoria-custo', 'CategoriaDeCustoController');

# Rotas para grupo de imagens
Route::resource('app/grupo-imagem', 'GrupoImagemController');


//rotas de notificação
//limpar todas as notificações do usuário
Route::get('user/mark-read-all-notification/{tipo}', 'UserController@markReadAllNotification')->name('user.mark.read.all.notification');

//limpar  a notificaçção clicada
Route::get('user/mark-read-notification/{not_id}', 'UserController@markReadNotification')->name('user.mark.read.notification');

//todas as notificações	
Route::get('user/notifications', 'UserController@getAllNotifications')->name('notifications.index');

//todas as notificações	
Route::get('user/mark-push-notification', 'UserController@markPushNotification')->name('notifications.push');


Route::resource('app/comments', 'CommentController');

// DEBUG
// Route::get('/mailable', function () {
//     $notifiable = App\User::find(1);
 
//     return new App\Notifications\AlertAction($notifiable);
// });

# Temporária para setar marcadores dos usuarios
# Route::get('users/add/marcadores', 'UserController@addMarcadorAll');


#Rotas Tutoriais
Route::get('app/tutoriais/add', 'TutorialController@create')->name('create.tutorial');
Route::post('app/tutoriais/store', 'TutorialController@store')->name('add.tutoria');
Route::get('app/tutoriais/editar/{tutorial_id}', 'TutorialController@edit')->name('edit.tutorial');
Route::put('app/tutoriais/atualizar/{tutorial_id}', 'TutorialController@update')->name('update.tutorial');
Route::delete('app/tutoriais/excluir/{revisao_id}', 'TutorialController@destroy')->name('delete.tutorial');
Route::get('app/tutoriais/visualizar', 'TutorialController@index')->name('index.tutorial');

#Rotas Resources Files

Route::get('app/resources/files/add', 'ResourceFileController@create')->name('create.resources.files');
Route::post('app/resources/files/store', 'ResourceFileController@store')->name('add.resources.files');
Route::get('app/resources/files/editar/{tutorial_id}', 'ResourceFileController@edit')->name('edit.resources.files');
Route::get('app/resources/files/visualizar', 'ResourceFileController@index')->name('index.resources.files');
Route::put('app/resources/files/atualizar/{tutorial_id}', 'ResourceFileController@update')->name('update.resources.files');
Route::delete('app/resources/files/excluir/{revisao_id}', 'ResourceFileController@destroy')->name('delete.resources.files');


Route::post('app/candidaturas/{id}/{novostatus}/mudar-status', 'JobCandidaturaController@mudarStatus')->name('candidatura.mudarStatus');


# Relatórios
Route::get('app/reports/users',     'RelatorioController@consolidadoPorUsuario')->name('relatorio.consolidado.usuarios');
Route::get('app/reports/dashboard', 'RelatorioController@dashboard')->name('relatorio.dashboard');

# Rotas de pagamento

# Rotas de pagamento - Gera View Pagamento Publicador Job
Route::get('app/user/confirma/pagamento/{id}', 'PagamentoController@geraViewPagamentoPayPal')->name('job.publicador.view.pagamento');

# Rotas de pagamento - Mudar status job pos pagamento
Route::post('app/user/muda-status/pagamento/{id}', 'PagamentoController@setaStatusJobPagamento')->name('job.mudar.pagamento');

# Rotas de pagamento - Mudar status job pos pagamento de proposta aceita
Route::get('app/user/muda-status/pagamento/job/proposta/{id}', 'PagamentoController@setaStatusJobPagamentoPropostaPos')->name('job.mudar.pagamento.job.proposta');

# Rotas para confirmação de pagamento
Route::post('app/user/confirma/pagamento', 'PagamentoController@confirmarPagamentoPublicadorJob')->name('confirmar.pagamento');


# Rotas de avaliação 
Route::post('app/avaliacoes/store', 'AvaliacaoController@store')->name('add.avaliacao');
Route::get('app/avaliacoes/visualizar', 'AvaliacaoController@index')->name('index.avaliacao');
