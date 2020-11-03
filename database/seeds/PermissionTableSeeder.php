<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){


      $permissions = [

         //CRUD de Permissions

         // Cliente
         ['name' => 'cria-cliente', 'bloco' => 'cliente'], 
         ['name' => 'lista-cliente','bloco' => 'cliente'], 
         ['name' => 'atualiza-cliente','bloco' => 'cliente'], 
         ['name' => 'deleta-cliente','bloco' => 'cliente'], 

         // Projeto
         ['name' => 'cria-projeto','bloco' => 'projeto'], 
         ['name' => 'lista-projeto','bloco' => 'projeto'],
         ['name' => 'atualiza-projeto','bloco' => 'projeto'],
         ['name' => 'deleta-projeto','bloco' => 'projeto'],

         // Imagem
         ['name' => 'cria-imagem', 'bloco' => 'imagem'],
         ['name' => 'lista-imagem', 'bloco' => 'imagem'],
         ['name' => 'atualiza-imagem', 'bloco' => 'imagem'],
         ['name' => 'deleta-imagem', 'bloco' => 'imagem'],

         // Tipo de Imagem
         ['name' => 'cria-tipo-imagem', 'bloco' => 'imagem'],
         ['name' => 'lista-tipo-imagem', 'bloco' => 'imagem'],
         ['name' => 'atualiza-tipo-imagem', 'bloco' => 'imagem'],
         ['name' => 'deleta-tipo-imagem', 'bloco' => 'imagem'],

         // Job
         ['name' => 'cria-job', 'bloco' => 'job'],
         ['name' => 'lista-job','bloco' => 'job'],
         ['name' => 'executa-job','bloco' => 'job'],
         ['name' => 'atualiza-job','bloco' => 'job'],
         ['name' => 'deleta-job','bloco' => 'job'],
         ['name' => 'delega-job', 'bloco' => 'job'],
         ['name' => 'adiciona-detalhes-adicionais-job', 'bloco' => 'job'],
         ['name' => 'visualiza-consolidado-job', 'bloco' => 'job'],

         // Tipo de Job
         ['name' => 'cria-tipo-job','bloco' => 'job'],
         ['name' => 'lista-tipo-job','bloco' => 'job'],
         ['name' => 'atualiza-tipo-job','bloco' => 'job'],
         ['name' => 'deleta-tipo-job','bloco' => 'job'],

         // Task
         ['name' => 'cria-task','bloco' => 'task'],
         ['name' => 'lista-task','bloco' => 'task'],
         ['name' => 'atualiza-task','bloco' => 'task'],
         ['name' => 'deleta-task','bloco' => 'task'],

         // Financeiro
         ['name' => 'gerencia-financeiro', 'bloco' => 'financeiro'],
         ['name' => 'cria-financeiro', 'bloco' => 'financeiro'],
         ['name' => 'lista-financeiro', 'bloco' => 'financeiro'],
         ['name' => 'atualiza-financeiro', 'bloco' => 'financeiro'],
         ['name' => 'deleta-financeiro', 'bloco' => 'financeiro'],
         
         // Valor
         ['name' => 'insere-valor',  'bloco' => 'valor'],
         ['name' => 'visualiza-valor', 'bloco' => 'valor'],
         
         // Pagamentos
         ['name' => 'faz-pagamento', 'bloco' => 'pagamento'],
         ['name' => 'recebe-pagamento', 'bloco' => 'pagamento'],

         // Revisao
         ['name' => 'cria-revisao', 'bloco' => 'revisao'],
         ['name' => 'lista-revisao', 'bloco' => 'revisao'],
         ['name' => 'atualiza-revisao', 'bloco' => 'revisao'],
         ['name' => 'deleta-revisao', 'bloco' => 'revisao'],
         
         // Coordenador
         ['name' => 'cria-coordenador',  'bloco' => 'coordenador'],
         ['name' => 'lista-coordenador','bloco' => 'coordenador'],
         ['name' => 'atualiza-coordenador','bloco' => 'coordenador'],
         ['name' => 'deleta-coordenador','bloco' => 'coordenador'],
         ['name' => 'visualiza-coordenador','bloco' => 'coordenador'],
         
         // Admin
         ['name' => 'cria-admin', 'bloco' => 'admin'],
         ['name' => 'lista-admin','bloco' => 'admin'],
         ['name' => 'atualiza-admin','bloco' => 'admin'],
         ['name' => 'deleta-admin','bloco' => 'admin'],
         
         // Outros
         ['name' => 'acompanha-progresso', 'bloco' => 'progresso'],
         ['name' => 'faz-download','bloco' => 'download'],
         ['name' => 'gerencia-politicas', 'bloco' => 'politicas'],
         

         //menus
         ['name' => 'menu-projeto', 'bloco' => 'projeto'],
         ['name' => 'menu-job', 'bloco' => 'job'],
         ['name' => 'menu-consolidado-job', 'bloco' => 'job'],
         ['name' => 'menu-tipo-job', 'bloco' => 'job'],
         ['name' => 'menu-market-job', 'bloco' => 'job'],
         ['name' => 'menu-painel-job', 'bloco' => 'job'],
         ['name' => 'menu-abertos-job', 'bloco' => 'job'],
         ['name' => 'menu-imagem', 'bloco' => 'imagem'],
         ['name' => 'menu-grupo-imagem', 'bloco' => 'imagem'],
         ['name' => 'menu-task','bloco' => 'task'],
         ['name' => 'menu-financeiro','bloco' => 'financeiro'],
         ['name' => 'menu-resource', 'bloco' => 'resource'],
         ['name' => 'menu-membro', 'bloco' => 'membro'],
         ['name' => 'menu-cliente', 'bloco' => 'cliente'],
         ['name' => 'menu-coordenador', 'bloco' => 'coordenador'],
         ['name' => 'menu-fullfreela', 'bloco' => 'fullfreela'],

      ];

      foreach ($permissions as $permission) {
         Permission::create($permission);
      }

      ###########################################

      // Selecionando as Permissions
      $admins   = Permission::all();
      $devs     = Permission::all();

      $clientes = Permission::whereIn('name',[
         'acompanha-progresso',
         'faz-download'
      ])->get();

      $coords = Permission::whereIn('name',[

         'cria-cliente', 
         'lista-cliente',
         'atualiza-cliente',
         'deleta-cliente',

         'cria-projeto',
         'lista-projeto',
         'atualiza-projeto',
         'deleta-projeto',

         'cria-imagem',
         'lista-imagem',
         'atualiza-imagem',
         'deleta-imagem',

         'cria-tipo-imagem',
         'lista-tipo-imagem',
         'atualiza-tipo-imagem',
         'deleta-tipo-imagem',

         'cria-job',
         'lista-job',
         'executa-job',
         'atualiza-job',
         'deleta-job',
         'delega-job',
         'adiciona-detalhes-adicionais-job',
         'concluir-job',

         'cria-tipo-job',
         'lista-tipo-job',
         'atualiza-tipo-job',
         'deleta-tipo-job',

         'cria-task',
         'lista-task',
         'atualiza-task',
         'deleta-task',

         'cria-revisao',
         'lista-revisao',
         'atualiza-revisao',
         'deleta-revisao',

         'lista-coordenador',
         'visualiza-coordenador',

         'acompanha-progresso',

         'faz-download',

         'menu-coordenador',
         'menu-job',
         'menu-task',
         'menu-imagem',
         'menu-tipo-job',
         'menu-painel-job',
         'menu-resource'        



      ])->get();

      $avals = Permission::whereIn('name',[

         'lista-cliente',

         'lista-projeto',
   
         'lista-imagem',
      
         'lista-tipo-imagem',

         'lista-job',
      
         'lista-tipo-job',

         'lista-task',

         'lista-revisao',
         'atualiza-revisao',
   
         'lista-coordenador',

         'acompanha-progresso',

         'faz-download',

         'menu-resource'   

      ])->get();

      $equipes = Permission::whereIn('name',[

         'lista-cliente',

         'lista-projeto',
         'atualiza-projeto',

         'lista-imagem',
         'atualiza-imagem',
      
         'lista-tipo-imagem',

         'lista-job',
         'executa-job',
         'atualiza-job',
      
         'lista-tipo-job',

         'lista-task',
         'atualiza-task',

         'cria-revisao',
         'lista-revisao',
         'atualiza-revisao',
   
         'acompanha-progresso',

         'faz-download',
         
         'menu-job',
         'menu-painel-job',
         

      ])->get();

      $frees = Permission::whereIn('name',[

         'lista-imagem',
   
         'lista-job',
         'executa-job',
         'atualiza-job',
         'lista-tipo-job',

         'lista-task',
         'atualiza-task',

         'visualiza-valor',
         'recebe-pagamento',

         'lista-revisao', 
         
         'acompanha-progresso',
         'faz-download',
         
         'menu-job',
         'menu-financeiro',
         'menu-market-job',
         'menu-resource'

      ])->get();

      $visits = Permission::whereIn('name',[
         'lista-job',
         
         'lista-task',
         
         'faz-download'
      ])->get();

      $publics = Permission::whereIn('name',[

         'cria-job',
         'lista-job',
         'executa-job',
         'atualiza-job',
         'deleta-job',
         'adiciona-detalhes-adicionais-job',
         'concluir-job',
         
         'lista-tipo-job',

         'cria-task',
         'lista-task',
         'deleta-task',

         'insere-valor',
         'visualiza-valor',
         'faz-pagamento',

         'cria-revisao',
         'lista-revisao',
         'atualiza-revisao',
         'deleta-revisao',

         'lista-coordenador',
         'atualiza-coordenador',
         'cria-coordenador',
         'deleta-coordenador',
         'visualiza-coordenador',

         'acompanha-progresso',
         'faz-download',

         'menu-job',
         'menu-financeiro',
         'menu-coordenador',
         
         'menu-market-job',
         'menu-abertos-job', 
         'menu-resource'     


      ])->get();

      // Criando as Roles
      $admin    = Role::create(['name' => 'admin']);
      $dev      = Role::create(['name' => 'desenvolvedor']);
      $coord    = Role::create(['name' => 'coordenador']);
      $cliente  = Role::create(['name' => 'cliente']);
      $aval     = Role::create(['name' => 'avaliador']);
      $equipe   = Role::create(['name' => 'equipe']);
      $free     = Role::create(['name' => 'freelancer']);
      $visit    = Role::create(['name' => 'visitante']);
      $public   = Role::create(['name' => 'publicador']);

      // Atribuindo as Permissions às Roles
      $admin->permissions()->sync($admins);
      $dev->permissions()->sync($devs);
      $cliente->permissions()->sync($clientes);
      $coord->permissions()->sync($coords);
      $aval->permissions()->sync($avals);
      $equipe->permissions()->sync($equipes);
      $free->permissions()->sync($frees);
      $visit->permissions()->sync($visits);
      $public->permissions()->sync($publics);

    } 

} // end class
