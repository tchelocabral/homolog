
@isset($role)
    <input type="hidden" name="role_id" value="{{ $role->id }}">
@endisset

<div class="row div-item-form">
    <div class="col-md-12">
        <p class="detalhe-label"><b>Nome da Política de Acesso</b></p>
        @isset($role)
            <p id="nome" class="margemB20 info-detalhe-maior" >{{ $role['name'] or 'Não Informado' }}</p>
        @else
            <input type="text" name="name" class="form-control" value="{{ $role['name']  or old('name') }}" placeholder="Nome da política de acesso"> 

            <div class="row div-item-form">
                <div class="col-md-12">
                    <p><b>Selecione as Permissões</b></p>
                    <select id="combo-permissao" name="permission[]" class="form-control select2" value="{{ old('permission[]') }}" multiple="multiple">
                        
                        @foreach($permission as $value)
                            <option value="{{ $value->id }}"> {{ $value->name }}</option>
                        @endforeach

                    </select>
                </div>
            </div>
        @endif
    </div>
</div>



