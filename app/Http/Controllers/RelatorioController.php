<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\UserFinanceiro;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use DB;

class RelatorioController extends Controller{
    


    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:relatorio-dashboard-fullfreela', ['only' => ['dashboard']] );
    }



    public function consolidadoPorUsuario(){

        $all_user =  User::role(['coordenador', 'avaliador', 'equipe', 'freelancer'])
                            ->with(['jobs', 'coordenandoProjetos', 'coordenando'])
                            ->get();
        foreach ($all_user as $key => $value) {
            //novo
            $iduser = $value->id;
            if(!empty($value->jobs)) {
                $jobs[$value->name]['delegado']['novo'] =  $value->jobs->filter(function($item, $iduser){ 
                   return $item->status == 0  ;  
                })->count();
            }
            if(!empty($value->coordenando)) {
                $jobs[$value->name]['coordenando']['novo'] =  
                    $value->coordenando->filter(
                        function($item, $iduser){ 
                            return $item->status == 0  ;  
                        })
                    ->count();
            }
            if(!empty($value->coordenandoProjetos)) {
                $jobs[$value->name]['projeto']['novo'] =  $value->coordenandoProjetos->filter(function($item, $iduser){ 

                   return $item->status == 0  ;  
                })->count();
            }
            //delegado
            if(!empty($value->jobs)) {
                $jobs[$value->name]['delegado']['delegado'] =  $value->jobs->filter(function($item, $iduser){ 

                   return $item->status == 1  ;
                })->count();
            }
            if(!empty($value->coordenando)) {
                $jobs[$value->name]['coordenando']['delegado'] =  $value->coordenando->filter(function($item, $iduser){ 

                   return $item->status == 1  ;
                })->count();
            }
            if(!empty($value->coordenandoProjetos)) {
                $jobs[$value->name]['projeto']['delegado'] =  $value->coordenandoProjetos->filter(function($item, $iduser){ 

                   return $item->status == 1  ;
                })->count();
            }
            //executando
            if(!empty($value->jobs)) {
                $jobs[$value->name]['delegado']['executando'] =  $value->jobs->filter(function($item, $iduser){ 

                   return $item->status == 2  ;
                })->count();
            }
            if(!empty($value->coordenando)) {
                $jobs[$value->name]['coordenando']['executando'] =  $value->coordenando->filter(function($item, $iduser){ 

                   return $item->status == 2  ;
                })->count();
            }
            if(!empty($value->coordenandoProjetos)) {
                $jobs[$value->name]['projeto']['executando'] =  $value->coordenandoProjetos->filter(function($item, $iduser){ 

                   return $item->status == 2  ;
                })->count();
            }          

            
            //revisao
            if(!empty($value->jobs)) {
                $jobs[$value->name]['delegado']['revisao'] =  $value->jobs->filter(function($item, $iduser){ 
                   return $item->status == 3  ;   ;  
                })->count();
            }
            if(!empty($value->coordenando)) {
                $jobs[$value->name]['coordenando']['revisao'] =  $value->coordenando->filter(function($item, $iduser){ 
                   return $item->status == 3  ;
                })->count();
            }
            if(!empty($value->coordenandoProjetos)) {
                $jobs[$value->name]['projeto']['revisao'] =  $value->coordenandoProjetos->filter(function($item, $iduser){ 
                   return $item->status == 3 ;  
                })->count();
            }

            //avaliacao
            if(!empty($value->jobs)) {
                $jobs[$value->name]['delegado']['avaliacao'] =  $value->jobs->filter(function($item, $iduser){ 
                   return $item->status == 4  ;
                })->count();
            }
            if(!empty($value->coordenando)) {
                $jobs[$value->name]['coordenando']['avalicao'] =  $value->coordenando->filter(function($item, $iduser){ 
                   return $item->status == 4  ;
                })->count();
            }
            if(!empty($value->coordenandoProjetos)) {
                $jobs[$value->name]['projeto']['avaliacao'] =  $value->coordenandoProjetos->filter(function($item, $iduser){ 
                   return $item->status == 4  ;
                })->count();
            }

            
            //concluido
            if(!empty($value->jobs)) {
                $jobs[$value->name]['delegado']['concluido'] =  $value->jobs->filter(function($item, $iduser){ 
                   return $item->status == 5  ; 
                })->count();
            }
            if(!empty($value->coordenando)) {
                $jobs[$value->name]['coordenando']['concluido'] =  $value->coordenando->filter(function($item, $iduser){ 
                   return $item->status == 5  ;
                })->count();
            }
            if(!empty($value->coordenandoProjetos)) {
                $jobs[$value->name]['projeto']['concluido'] =  $value->coordenandoProjetos->filter(function($item, $iduser){ 
                   return $item->status == 5  ;  
                })->count();
            }
            
            //recusado
            if(!empty($value->jobs)) {
                $jobs[$value->name]['delegado']['recusado'] =  $value->jobs->filter(function($item, $iduser){ 
                   return $item->status ==6  ;
                })->count();
            }
            if(!empty($value->coordenando)) {
                $jobs[$value->name]['coordenando']['recusado'] =  $value->coordenando->filter(function($item, $iduser){ 
                   return $item->status == 6  ;
                })->count();
            }
            if(!empty($value->coordenandoProjetos)) {
                $jobs[$value->name]['projeto']['recusado'] =  $value->coordenandoProjetos->filter(function($item, $iduser){ 
                   return $item->status == 6  ;
                })->count();
            }
            
            //reaberto
            if(!empty($value->jobs)) {
                $jobs[$value->name]['delegado']['reaberto'] =  $value->jobs->filter(function($item, $iduser){ 
                   return $item->status ==7  ;
                })->count();
            }
            if(!empty($value->coordenando)) {
                $jobs[$value->name]['coordenando']['reaberto'] =  $value->coordenando->filter(function($item, $iduser){ 
                   return $item->status == 7  ;
                })->count();
            }
            if(!empty($value->coordenandoProjetos)) {
                $jobs[$value->name]['projeto']['reaberto'] =  $value->coordenandoProjetos->filter(function($item, $iduser){ 
                   return $item->status == 7  ;
                })->count();
            }

                        
            //parado
            if(!empty($value->jobs)) {
                $jobs[$value->name]['delegado']['parado'] =  $value->jobs->filter(function($item, $iduser){ 
                   return $item->status ==8  ;
                })->count();
            }
            if(!empty($value->coordenando)) {
                $jobs[$value->name]['coordenando']['parado'] =  $value->coordenando->filter(function($item, $iduser){ 
                   return $item->status == 8  ;
                })->count();
            }
            if(!empty($value->coordenandoProjetos)) {
                $jobs[$value->name]['projeto']['parado'] =  $value->coordenandoProjetos->filter(function($item, $iduser){ 
                   return $item->status == 8  ;   ;  
                })->count();
            }
        }

        return view('relatorio.jobs-por-usuario', compact('jobs'));
    }

    public function dashboard(){

        // NPS = MEDIA
        // NPS MEDIA = NOTA 0 A 100 MEDIA ENTRE FF E PUBLISHER
        // GMV = GROW (VALOR MONETARIO DE JOBS NA PLATAFORMA) CADASTRADOS, ANDAMENTOS, CONCLUIDOS)
        // JOBS INCLUIR: Recusados e Em orçamento

        // Retorno
        $rel = array();

        // Datas para buscas
        $hoje             = Carbon::now();
        $amanha           = Carbon::now()->tomorrow();
        $ontem            = Carbon::now()->yesterday();
        $dia              = Carbon::now()->day;
        $mes              = Carbon::now()->month;
        $ano              = Carbon::now()->year;
        $semana_inicio    = Carbon::now()->startOfWeek();
        $semana_fim       = Carbon::now()->copy()->endOfWeek();
        $mes_inicio       = Carbon::now()->firstOfMonth();
        $hoje_formato_br  = Carbon::now()->format('d/m/yy');
        $hoje_formato_en  = Carbon::now()->format('yy-m-d');
        
        // Buscas dos dados
        // $all_jobs    = Job::whereIn('status', [0,2,3,4,5,6,7,8,9])->get();
        // $month_jobs  = Job::whereIn('status', [0,2,3,4,5,6,7,8,9])->whereBetween('created_at', [$mes_inicio, $amanha])->get();
        // $week_jobs   = Job::whereIn('status', [0,2,3,4,5,6,7,8,9])->whereBetween('created_at', [$semana_inicio, $semana_fim])->get();
        // $day_jobs    = Job::whereIn('status', [0,2,3,4,5,6,7,8,9])->whereBetween('created_at', [$hoje, $amanha])->get();
        $all_jobs    = Job::all();
        $month_jobs  = Job::whereBetween('created_at', [$mes_inicio, $amanha])->get();
        $week_jobs   = Job::whereBetween('created_at', [$semana_inicio, $semana_fim])->get();
        $day_jobs    = Job::whereBetween('created_at', [$hoje, $amanha])->get();

        // Jobs sum
        $all_jobs_sum   = DB::table('jobs')->whereIn('status', [0,2,5])->sum('valor_job');
        $month_jobs_sum = DB::table('jobs')->whereBetween('created_at', [$mes_inicio, $amanha])->whereIn('status', [0,2,5])->sum('valor_job');
        $week_jobs_sum  = DB::table('jobs')->whereBetween('created_at', [$semana_inicio, $semana_fim])->whereIn('status', [0,2,5])->sum('valor_job');
        $day_jobs_sum   = DB::table('jobs')->whereBetween('created_at', [$hoje, $amanha])->whereIn('status', [0,2,5])->sum('valor_job');

        // Jobs em candidatura
        // Quote Jobs (sum das propostas)
        
        // Freelas e Publicadores
        $active_freelas = User::role('freelancer')->where('ativo', 1)->get();
        $active_publics = User::role('publicador')->where('ativo', 1)->get();
        $all_freelas = User::role('freelancer')->get();
        $all_publics = User::role('publicador')->get();

        // Receitas
        $receitas         = DB::table('user_financeiros')->where('status', 2)->sum('valor_taxa');
        $receitas_month   = DB::table('user_financeiros')->whereBetween('created_at', [$mes_inicio, $amanha])->where('status', 2)->sum('valor_taxa');
        $receitas_week    = DB::table('user_financeiros')->whereBetween('created_at', [$semana_inicio, $semana_fim])->where('status', 2)->sum('valor_taxa');
        $receitas_day     = DB::table('user_financeiros')->whereBetween('created_at', [$hoje, $amanha])->where('status', 2)->sum('valor_taxa');
        
        

        /////////////////////////////////// Filtros ///////////////////////////////////
        // Freelas
        $month_freelas = 
            $all_freelas->filter(function($freela) use ($mes_inicio, $amanha){
                if($freela->created_at->between($mes_inicio, $amanha)){
                    return $freela;
                }
        });
        $week_freelas = 
            $month_freelas->filter(function($freela) use ($semana_inicio, $semana_fim){
                if($freela->created_at->between($semana_inicio, $semana_fim)){
                    return $freela;
                }
        });
        $day_freelas =
            $week_freelas->filter(function($freela) use ($hoje, $amanha){
                if($freela->created_at->between($hoje, $amanha)){
                    return $freela;
                }
        });
        // Freelas Ativos
        $month_freelas_active = 
            $active_freelas->filter(function($freela) use ($mes_inicio, $amanha){
                if($freela->created_at->between($mes_inicio, $amanha)){
                    return $freela;
                }
        });
        $week_freelas_active = 
            $month_freelas_active->filter(function($freela) use ($semana_inicio, $semana_fim){
                if($freela->created_at->between($semana_inicio, $semana_fim)){
                    return $freela;
                }
        });
        $day_freelas_active =
            $week_freelas_active->filter(function($freela) use ($hoje, $amanha){
                if($freela->created_at->between($hoje, $amanha)){
                    return $freela;
                }
        });
        // Publicadores
        $month_publics = 
            $all_publics->filter(function($public) use ($mes_inicio, $amanha){
                if($public->created_at->between($mes_inicio, $amanha)){
                    return $public;
                }
        });
        $week_publics = 
            $month_publics->filter(function($public) use ($semana_inicio, $semana_fim){
                if($public->created_at->between($semana_inicio, $semana_fim)){
                    return $public;
                }
        });
        $day_publics =
            $week_publics->filter(function($public) use ($hoje, $amanha){
                if($public->created_at->between($hoje, $amanha)){
                    return $public;
                }
        });
        // Publicadores Ativos
        $month_publics_active = 
            $active_publics->filter(function($public) use ($mes_inicio, $amanha){
                if($public->created_at->between($mes_inicio, $amanha)){
                    return $public;
                }
        });
        $week_publics_active = 
            $month_publics_active->filter(function($public) use ($semana_inicio, $semana_fim){
                if($public->created_at->between($semana_inicio, $semana_fim)){
                    return $public;
                }
        });
        $day_publics_active =
            $week_publics_active->filter(function($public) use ($hoje, $amanha){
                if($public->created_at->between($hoje, $amanha)){
                    return $public;
                }
        });

        // Jobs
        $month_jobs = 
            $all_jobs->filter(function($job) use ($mes_inicio, $amanha) {
                if($job->created_at->between($mes_inicio, $amanha)){
                    return $job;
                }
        });
        $week_jobs  = 
            $month_jobs->filter(function($job) use ($semana_inicio, $semana_fim) {
                if($job->created_at->between($semana_inicio, $semana_fim)){
                    return $job;
                }
        });
        $day_jobs = 
            $week_jobs->filter(function($job) use ($hoje, $amanha) {
                if($job->created_at->between($hoje, $amanha)){
                    return $job;
                }
        });
        // Completos
        $comp_jobs = 
            $all_jobs->filter(function($job){
                if($job->status == 5){
                    return $job;
                }
        }); 
        $comp_jobs_mes = 
            $month_jobs->filter(function($job){
                if($job->status == 5){
                    return $job;
                }
        });
        $comp_jobs_week = 
            $week_jobs->filter(function($job){
                if($job->status == 5){
                    return $job;
                }
        }); 
        $comp_jobs_day = 
            $day_jobs->filter(function($job){
                if($job->status == 5){
                    return $job;
                }
        });
        // Executados
        $exec_jobs = 
            $all_jobs->filter(function($job){
                if($job->status == 2){
                    return $job;
                }
        });
        $exec_jobs_mes = 
            $month_jobs->filter(function($job){
                if($job->status == 2){
                    return $job;
                }
        });
        $exec_jobs_week = 
            $week_jobs->filter(function($job){
                if($job->status == 2){
                    return $job;
                }
        }); 
        $exec_jobs_day = 
            $day_jobs->filter(function($job){
                if($job->status == 2){
                    return $job;
                }
        });

        // Refused
        $ref_jobs = 
            $all_jobs->filter(function($job){
                if($job->status == 6){
                    return $job;
                }
        });
        $ref_jobs_mes = 
            $month_jobs->filter(function($job){
                if($job->status == 6){
                    return $job;
                }
        });
        $ref_jobs_week = 
            $week_jobs->filter(function($job){
                if($job->status == 6){
                    return $job;
                }
        }); 
        $ref_jobs_day = 
            $day_jobs->filter(function($job){
                if($job->status == 6){
                    return $job;
                }
        });
        // Em Candidatura
        $cand_jobs = 
            $all_jobs->filter(function($job){
                if($job->status == 9){
                    return $job;
                }
        });
        $cand_jobs_mes = 
            $month_jobs->filter(function($job){
                if($job->status == 9){
                    return $job;
                }
        });
        $cand_jobs_week = 
            $week_jobs->filter(function($job){
                if($job->status == 9){
                    return $job;
                }
        }); 
        $cand_jobs_day = 
            $day_jobs->filter(function($job){
                if($job->status == 9){
                    return $job;
                }
        });
        
        
        // dd($day_freelas);

        $rel['nps-media-total']       = '00';
        $rel['nps-media-mes']         = '00';
        $rel['nps-media-semana']      = '00';
        $rel['nps-media-dia']         = '00';
        $rel['nps-freela-total']      = '00';
        $rel['nps-freela-mes']        = '00';
        $rel['nps-freela-semana']     = '00';
        $rel['nps-freela-dia']        = '00';
        $rel['nps-publisher-total']   = '00';
        $rel['nps-publisher-mes']     = '00';
        $rel['nps-publisher-semana']  = '00';
        $rel['nps-publisher-dia']     = '00';

        // Valor Monetário Total de Jobs na plataforma
        // dd($all_jobs->sum('valor_job'));

        $rel['gmv-total']  = $all_jobs_sum;
        $rel['gmv-mes']    = $month_jobs_sum;
        $rel['gmv-semana'] = $week_jobs_sum;
        $rel['gmv-dia']    = $day_jobs_sum;
        
        // dd($rel['gmv-dia']);

        $rel['receita-total']  = $receitas;
        $rel['receita-mes']    = $receitas_month;
        $rel['receita-semana'] = $receitas_week;
        $rel['receita-dia']    = $receitas_day;
        
        $rel['freelas-total']   = $all_freelas->count();
        $rel['freelas-mes']     = $month_freelas->count();
        $rel['freelas-semana']  = $week_freelas->count();
        $rel['freelas-dia']     = $day_freelas->count();

        $rel['active-freelas-total']   = $active_freelas->count();
        $rel['active-freelas-mes']     = $month_freelas_active->count();
        $rel['active-freelas-semana']  = $week_freelas_active->count();
        $rel['active-freelas-dia']     = $day_freelas_active->count();

        $rel['publishers-total']  = $all_publics->count();
        $rel['publishers-mes']    = $month_publics->count();
        $rel['publishers-semana'] = $week_publics->count();
        $rel['publishers-dia']    = $day_publics->count();

        $rel['active-publishers-total']  = $active_publics->count();
        $rel['active-publishers-mes']    = $month_publics_active->count();
        $rel['active-publishers-semana'] = $week_publics_active->count();
        $rel['active-publishers-dia']    = $day_publics_active->count();
        
        $rel['jobs-total']    = $all_jobs->count();
        $rel['jobs-mes']      = $month_jobs->count();
        $rel['jobs-semana']   = $week_jobs->count();
        $rel['jobs-dia']      = $day_jobs->count();

        $rel['in-progress-total']   = $exec_jobs->count();
        $rel['in-progress-mes']     = $exec_jobs_mes->count();
        $rel['in-progress-semana']  = $exec_jobs_week->count();
        $rel['in-progress-dia']     = $exec_jobs_day->count();
        
        $rel['concluded-total']   = $comp_jobs->count();
        $rel['concluded-mes']     = $comp_jobs_mes->count();
        $rel['concluded-semana']  = $comp_jobs_week->count();
        $rel['concluded-dia']     = $comp_jobs_day->count();

        $rel['refused-total']   = $ref_jobs->count();
        $rel['refused-mes']     = $ref_jobs_mes->count();
        $rel['refused-semana']  = $ref_jobs_week->count();
        $rel['refused-dia']     = $ref_jobs_day->count();

        $rel['quoted-total']   = $cand_jobs->count();
        $rel['quoted-mes']     = $cand_jobs_mes->count();
        $rel['quoted-semana']  = $cand_jobs_week->count();
        $rel['quoted-dia']     = $cand_jobs_day->count();


        return view('relatorio.dashboard', compact('rel'));


    }

}
