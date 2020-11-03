<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        // Soluciona problema com tamanhos de caracteres dos campos unicos das tabelas
        \Schema::defaultStringLength(191);
        
        // Mapa de Relacionamento Polimorfico
        Relation::morphMap([
            'usuario'   => 'App\User',
            'job'       => 'App\Models\Job',
            'imagem'    => 'App\Models\Imagem',
            'projeto'   => 'App\Models\Projeto',
        ]);

        // forceLocale
        // \App::setLocale('pt-br');
        
        // Adminlte menu
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {

            //Painel Jobs
            $event->menu->add([
                'text'        => __('menu.PAINEL DE JOBS'),
                'icon'        => 'terminal',
                'url'         => 'app',
                'can'         => 'menu-painel-job',
            ]);

            //Painel Market
            $event->menu->add([
                'text'        => __('menu.MARKETPLACE'),
                'icon'        => 'shopping-cart',
                'url'         => 'app',
                'can'         => 'menu-market-job',
            ]);

            // Fullfreela
            $event->menu->add([
                'text'        => __('menu.FULLFREELA'),
                'icon'        => 'hashtag',
                'can'  => 'menu-fullfreela',   
                'submenu' => [
                    [
                        'text'    => __('menu.Membros'),
                        'icon'    => 'users',
                        'can'     => 'menu-membro', 
                        'submenu' => [
                            [
                                'text'        => __('menu.Novo Membro'),
                                'url'         => 'app/users/create',
                                'icon'        => 'user-plus',
                            ],
                            [
                                'text'        => __('menu.Todos os Membros'),
                                'url'         => 'app/users',
                                'icon'        => 'users',
                            ],
                            [
                                'text'        => __('menu.Habilidades'),
                                'url'         => 'app/habilidades',
                                'icon'        => 'users',
                            ],
                        ],
                    ],
                    [
                        'text' => __('menu.Coordenadores'),
                        'url'   => route('coordernadores.lista'),
                        'icon' => 'users',
                        'can'  => 'menu-coordenador'
                    ],
                    [
                        'text' => __('menu.Políticas de Acesso'),
                        'url'  => route('politicas.index'),
                        'icon' => 'key',
                        'can'  => 'gerencia-politicas',
                    ],
                    [
                        'text'    => __('menu.Relatórios'),
                        'icon'    => 'list',
                        'can'     => 'menu-relatorios-fullfreela', 
                        'submenu' => [
                            [
                                'text' => __('menu.Dashboard'),
                                'url'  =>  route('relatorio.dashboard'),
                                'icon' => 'list',
                                'can'  => 'relatorio-dashboard-fullfreela'
                            ],
                            [
                                'text' => __('menu.Consolidado Jobs'),
                                'url'  =>  route('relatorio.consolidado.usuarios'),
                                'icon' => 'sliders',
                                'can'  => 'menu-consolidado-job'
                            ]
                        ],
                    ]
                ],
            ]);

            
            // Planos
            $event->menu->add([
                'text'    => __('menu.PLANOS'),
                'icon'    => 'shield',
                'can'  => 'menu-plano',   
                'submenu' => [
                    
                    [
                        'text' => __('menu.Novo Plano'),
                        'url'  => route('planos.create'),
                        'icon' => 'product-hunt', 
                        'can'  => 'cria-plano',                      
                        
                    ],
                    [
                        'text' => __('menu.Todos os Planos'),
                        'url'  => route('planos.index'),
                        'icon' => 'industry',    
                    ],
                ],
            ]);

            // Clientes
            $event->menu->add([
                'text'    => __('menu.CLIENTES'),
                'icon'    => 'user-md',
                'can'  => 'menu-cliente',   
                'submenu' => [
                    
                    [
                        'text' => __('menu.Novo Cliente'),
                        'url'  => route('clientes.create'),
                        'icon' => 'product-hunt', 
                        'can'  => 'cria-cliente',                      
                        
                    ],
                    [
                        'text' => __('menu.Todos os Clientes'),
                        'url'  => route('clientes.index'),
                        'icon' => 'industry',    

                       
                    ],
                ],
            ]);

            // Projetos
            $event->menu->add([
                'text'        => __('menu.PROJETOS'),
                'icon'        => 'archive',
                'can'       => 'menu-projeto', 
                'submenu' => [
                    [
                        'text' => __('menu.Novo Projeto'),
                        'url'  => route('projetos.create'),
                        'icon' => 'archive',
                        'can'  => 'cria-projeto',
                    ],
                    [
                        'text' => __('menu.Em Andamento'),
                        'url'  =>  route('projetos.andamento'),
                        'icon' => 'sliders',
                        'can'  => 'lista-projeto'
                    ],
                    [
                        'text' => __('menu.Todos os Projetos'),
                        'url'  => route('projetos.index'),
                        'icon' => 'bars',
                        'can'  => 'lista-projeto',
                    ],
                ],
            ]);

            // Imagem
            $event->menu->add([
                'text'    => __('menu.IMAGENS'),
                'icon'    => 'building-o',
                'can'  => 'menu-imagem', 
                'submenu' => [
                    [
                        'text'        => __('menu.Tipos de Imagens'),
                        'icon'        => 'users',
                        'submenu' => [
                            [
                                'text'   => __('menu.Novo Tipo'),
                                'url'    => route('tiposimagens.create'),
                                'icon'   => 'tag',
                            ],
                            [
                                'text'   => __('menu.Todos os Tipos'),
                                'url'    => route('tiposimagens.index'),
                                'icon'   => 'tag',
                                'can'     => 'lista-imagem',
                            ]
                        ],
                    ],
                    [
                        'text'        => __('menu.Grupos de Imagens'),
                        'icon'        => 'users',
                        'can'  => 'menu-grupo-imagem', 
                        'submenu' => [
                            [
                                'text'   => __('menu.Novo Grupo'),
                                'url'    => route('grupo-imagem.create'),
                                'icon'   => 'tag',
                            ],
                            [
                                'text'   => __('menu.Todos os Grupos'),
                                'url'    => route('grupo-imagem.index'),
                                'icon'   => 'tag',
                            ]
                        ]                        
                    ]
                ]
            ]);

            // Jobs
            $event->menu->add([
                'text'        => __('menu.JOBS'),
                'icon'        => 'tags',
                'can'         => 'menu-job',
                'submenu' => [
                    [
                        'text'      => __('menu.Tipos de Jobs'),
                        'icon'      => 'tag',
                        'can'       => 'menu-tipo-job',
                        'submenu' => [
                            [
                                'text'   => __('menu.Novo'),
                                'url'    => route('tipojobs.create'),
                                'icon'   => 'tag',
                                'can'    => 'cria-tipo-job',                                
                            ],
                            [
                                'text'   => __('menu.Todos os Tipos'),
                                'url'    => route('tipojobs.index'),
                                'icon'   => 'tags',
                                'can'    => 'lista-tipo-job',
                                
                            ]
                        ]
                    ],
                    [
                        'text'      => __('menu.Formato de Entrega'),
                        'icon'      => 'tag',
                        'can'       => 'menu-formato-entrega',
                        'submenu' => [
                            [
                                'text'   => __('menu.Novo'),
                                'url'    => route('deliveryformat.create'),
                                'icon'   => 'tag',
                                'can'    => 'cria-formato-entrega',                                
                            ],
                            [
                                'text'   => __('menu.Todos os Formatos de Entrega'),
                                'url'    => route('deliveryformat.index'),
                                'icon'   => 'tags',
                                'can'    => 'lista-formato-entrega',
                                
                            ]
                        ]
                    ],



                    [
                        'text' => __('menu.Publicar Job'),
                        'url'  =>  route('job.avulso.create'),
                        'icon' => 'sliders',
                        'can'  => 'cria-job'
                    ],
                    [
                        'text' => __('menu.Jobs Abertos'),
                        'url'  =>  route('jobs.abertos'),
                        'icon' => 'sliders',
                        'can'  => 'menu-abertos-job'
                    ],
                    [
                        'text' => __('menu.Aguardando Pagamento'),
                        'url'  =>  route('jobs.aguardando.pagamento'),
                        'icon' => 'sliders',
                        'can'  => 'menu-abertos-job'
                    ],
                    [
                        'text' => __('menu.Em Candidatura'),
                        'url'  =>  route('jobs.em.candidatura'),
                        'icon' => 'sliders',
                        'can'  => 'menu-candidaturas-job'
                    ],
                    [
                        'text' => __('menu.Jobs Em Execução'),
                        'url'  =>  route('jobs.execucao'),
                        'icon' => 'sliders',
                        'can'  => 'lista-job'
                    ],
                    [
                        'text' => __('menu.Jobs Recusados'),
                        'url'  =>  route('jobs.recusados'),
                        'icon' => 'sliders',
                        'can'  => 'lista-job'
                    ],
                    [
                        'text' => __('menu.Jobs Concluídos'),
                        'url'  =>  route('jobs.concluidos'),
                        'icon' => 'sliders',
                        'can'  => 'lista-job'
                    ],

                    [
                        'text' => __('menu.Todos os Jobs'),
                        'url'  =>  route('jobs.todos'),
                        'icon' => 'sliders',
                        'can'  => 'lista-job'
                    ],

                    [
                        'text' => __('menu.Consolidado Jobs'),
                        'url'  =>  route('relatorio.consolidado.usuarios'),
                        'icon' => 'sliders',
                        'can'  => 'menu-consolidado-job'
                    ]

                ]
            ]);

            // Tasks
            $event->menu->add([
                'text'      => __('menu.Tasks'),
                'icon'      => 'tasks',
                'can'       => 'menu-task', 
                'submenu'   => [
                    [
                        'text' => __('menu.Nova Task'),
                        'url'  => route('tasks.create'),
                        'icon' => 'tag',
                        'can'  => 'cria-task',
                        
                    ],
                    [
                        'text' => __('menu.Todos as Tasks'),
                        'url'  => route('tasks.index'),
                        'icon' => 'tags',      
                        'can'  => 'lista-task',                  
                    ],
                ]                
            ]);

            // Financeiro
            $event->menu->add([
                'text'        => __('menu.FINANCEIRO'),
                'icon'        => 'bank',
                'can'  => 'menu-financeiro',
                'submenu' => [
                    [
                        'text'        => __('menu.Centros de Custo'),
                        'url'         => route('centro-custo.index'),
                        'icon'        => 'sitemap',
                        'can'         => 'gerencia-financeiro'
                    ],
                    [
                        'text'        => __('menu.Categorias de Custo'),
                        'url'         => route('categoria-custo.index'),
                        'icon'        => 'sitemap',
                        'can'         => 'gerencia-financeiro'
                    ],
                    [
                        'text'        => __('menu.Fluxo Geral'),
                        'url'         => 'app/financeiro',
                        'icon'        => 'bar-chart',
                        'can'         => 'gerencia-financeiro'
                    ],
                    [
                        'text'        => __('menu.Pagamentos'),
                        'url'         => route('visualizar-conta-movimentacao'),
                        'icon'        => 'money',
                        'can'         => 'faz-pagamento'
                    ],
                    [
                        'text'        => __('menu.Recebimentos'),
                        'url'         =>  route('visualizar-conta-movimentacao'),
                        'icon'        => 'usd',
                        'can'         => 'recebe-pagamento'
                    ],
                ],
            ]);

            // Resources
            $event->menu->add([
                'text'    => __('menu.RESOURCES'),
                'icon'    => 'cloud-download',
                'can'       => 'menu-resource', 
                'submenu' => [
                    [
                        'text' => __('menu.Tutoriais'),
                        'url'  => route('index.tutorial'),
                        'icon' => 'graduation-cap',
                    ],
                    [
                        'text' => __('menu.Arquivos Studio'),
                        'url'  => route('index.resources.files'),
                        'icon' => 'file',
                    ],
                    // [
                    //     'text' => __('menu.Certificação'),
                    //     'url'  => 'app/resources/certificação',
                    //     'icon' => 'certificate',
                    // ],
                ],
            ]);
            
            // Contador de Notificações
            $notify_count = \Auth::user()->unreadNotifications()->count();
            
            // Meu Perfil
            $event->menu->add([
                'text'    => __('menu.MEU PERFIL'),
                'icon'    => 'id-badge',

                'submenu' => [
                    [
                        'text' => __('menu.Perfil Completo'),
                        // 'url'  => 'app/users/perfil',
                        'url'  => route('users.show', encrypt(\Auth::user()->id)),
                        'icon' => 'address-card',
                    ],
                    [
                        'text'        => __('menu.Minha Conta'),
                        'url'         => 'app/minha-conta',
                        'icon'        => 'paypal',
                        // 'canany'      => ['faz-pagamento','recebe-pagamento']
                        'can'         => 'recebe-pagamento'
                    ],
                    [
                        'text' => __('menu.Notificações'),
                        'url'  => route('notifications.index'),
                        'icon' => $notify_count > 0 ? 'bell' : 'bell-o',
                        'label' => $notify_count > 0 ? $notify_count : '',
                        'label-color' => $notify_count > 0 ? 'danger' : '',
                    ],
                    [
                        'text' => __('menu.Alterar Senha'),
                        // 'url'  => route('password.request'), 
                        'url'  => route('user.nova.senha'), 
                        'icon' => 'key',
                    ],
                    // [
                    //     'text' __(=> 'Minhas Habilidades'),
                    //     'url'  => 'app/users/habilidades',
                    //     'icon' => 'flask',
                    // ],
                    // [
                    //     'text' __(=> 'Pontuação'),
                    //     'url'  => 'app/users/pontuacao',
                    //     'icon' => 'star',
                    // ],
                ],
            ]);

            $event->menu->add([
                'text' => __('menu.SUPORTE'),
                'url'  => 'mailto:support@fullfreela.com',
                'icon' => 'info',
            ]);


            // Sair
            $event->menu->add([
                    'text' => __('menu.Sair'),
                    'url'  => '#',
                    'icon' => 'power-off',
            ]);
        });

        // BLADE DIRECTIVES
        Blade::directive('convert_money', function ($money) {
            return '<?php echo number_format($money, 2, ",", "."); ?>';
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
