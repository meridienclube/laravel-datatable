<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                {{ $title ?? trans('meridien.list') }}
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="kt-section">
            <div class="kt-section__content">
                <table class="table table-striped" id="{{ $id ?? 'datatable_list' }}">
                    <thead>
                        <tr>
                            @foreach($items as $item)
                                <th scope="col">{{ __($item) }}</th>
                            @endforeach
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            @foreach($items as $item)
                                <th scope="col">{{ __($item) }}</th>
                            @endforeach
                            <th scope="col"></th>
                        </tr>
                    </tfoot>
                </table>
                @if(isset($slot))
                    {{ $slot }}
                @endif
            </div>
        </div>
    </div>
</div>

<div style="display: none" id="btnsDatatable">
    <span class="dropdown">
        <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
          <i class="la la-ellipsis-h"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right">

            @if(isset($buttons))
                {{ $buttons }}
            @endif

            @permission($slug . '.show')
            <a href="javascript:void(0)" class="dropdown-item show">
                <i class="la la-eye"></i>{{ trans('meridien.show') }}
            </a>
            @endpermission

            @permission($slug . '.edit')
            <a href="javascript:void(0)" class="dropdown-item edit">
                <i class="la la-edit"></i>{{ trans('meridien.edit') }}
            </a>
            @endpermission

            @permission($slug . '.destroy')
                <form action="#" class="form-destroy" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    @csrf
                    <button type="submit" class="dropdown-item destroy" onclick="return confirm('Tem certeza que deseja deletar este item?');">
                        <i class="la la-remove" style="margin-right: .75rem;"></i>{{ trans('meridien.delete') }}
                    </button>
                </form>
            @endpermission

        </div>
    </span>
    <a href="javascript:void(0);" class="btn btn-sm btn-clean btn-icon btn-icon-md edit" title="Edit">
        <i class="la la-edit"></i>
    </a>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            const table = $('#{{ $id ?? 'datatable_list' }}').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ url('api/' . $url . '/datatable?api_token=' . auth()->user()->api_token) }}",
                    "data": {
                        "trashed" : "{{ $trashed ?? 0 }}"
                    }
                },
                "columns": [
                    @if(isset($columns))
                        {{ $columns }}
                    @else
                        @foreach($items as $item)
                        {
                            "data": "{{ $item }}", "name": "{{ $item }}"
                        },
                        @endforeach
                    @endif
                    {
                        defaultContent: $('#btnsDatatable').html()
                    }
                ],
                "language": {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_ resultados por página",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    },
                    "select": {
                        "rows": {
                            "_": "Selecionado %d linhas",
                            "0": "Nenhuma linha selecionada",
                            "1": "Selecionado 1 linha"
                        }
                    }
                },
                createdRow: function (row, data, dataIndex) {
                    $(row).find('td:eq(-1) a.show').attr('href', '/{{ $url }}/' + data.id);
                    $(row).find('td:eq(-1) a.edit').attr('href', '/{{ $url }}/' + data.id + '/edit');
                    $(row).find('td:eq(-1) form.form-destroy').attr('action', '/{{ $url }}/' + data.id);
                    @if(isset($createdRow))
                        {{ $createdRow }}
                    @endif
                }
            });

            @if(isset($script))
                {{ $script }}
            @endif

        });
    </script>
@endpush
