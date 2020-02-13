<table class="table table-striped" id="{{ $id ?? 'datatable_list' }}">
    <thead>
    <tr>
        @foreach($items as $item)
            <th scope="col">{{ __($item['title']?? $item) }}</th>
        @endforeach
        <th scope="col"></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        @foreach($items as $item)
            <th scope="col">{{ __($item['title']?? $item) }}</th>
        @endforeach
        <th scope="col"></th>
    </tr>
    </tfoot>
</table>
@if(isset($slot))
    {{ $slot }}
@endif

<div style="display: none" id="btnsDatatable">
    @if(isset($buttons))
        {{ $buttons }}
    @endif
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            const table = $('#{{ $id ?? 'datatable_list' }}').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ url('api/' . $url . '/datatable?api_token=' . auth()->user()->api_token) }}",
                    "dataType": "json",
                    "data": {
                        'data': $('#{{ $id ?? 'datatable_list' }}FormFilter').serialize()
                    }
                },
                "columns": [
                    @if(isset($columns))
                        {{ $columns }}
                    @else
                        @foreach($items as $item)
                        {
                            "data": "{{ $item['data']?? $item }}",
                            "name": "{{ $item['name']?? $item }}",
                            @isset($item['render'])
                            /*"render": "{{ $item['render'] }}",*/
                            "render": function (data, type, row, meta) {
                                let r = '';
                                let str = '{{ $item['render'] }}';
                                if (str.indexOf("implode") >= 0 && type === 'display' && Array.isArray(data) && data.length > 0) {
                                    //r = str.replace('implode', "[, ]");
                                    r = data.map(function(data) {
                                        return data.name;
                                    }).join(',');
                                }
                                if (str.indexOf("object") >= 0 && type === 'display') {
                                    if(typeof data !== 'undefined'){
                                        r = data
                                    }
                                }
                                return r;
                            },
                            @endisset
                            "title": "{{ $item['title']?? $item }}"
                        },
                        @endforeach
                    @endif
                    {
                        defaultContent: $('#btnsDatatable').html()
                    }
                ],
                "columnDefs":
                    [{
                        "targets": -1,
                        "orderable": false
                    }],
                "language":
                    {
                        "sEmptyTable":
                            "Nenhum registro encontrado",
                        "sInfo":
                            "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                        "sInfoEmpty":
                            "Mostrando 0 até 0 de 0 registros",
                        "sInfoFiltered":
                            "(Filtrados de _MAX_ registros)",
                        "sInfoPostFix":
                            "",
                        "sInfoThousands":
                            ".",
                        "sLengthMenu":
                            "_MENU_ resultados por página",
                        "sLoadingRecords":
                            "Carregando...",
                        "sProcessing":
                            "Processando...",
                        "sZeroRecords":
                            "Nenhum registro encontrado",
                        "sSearch":
                            "Pesquisar",
                        "oPaginate":
                            {
                                "sNext":
                                    "Próximo",
                                "sPrevious":
                                    "Anterior",
                                "sFirst":
                                    "Primeiro",
                                "sLast":
                                    "Último"
                            }
                        ,
                        "oAria":
                            {
                                "sSortAscending":
                                    ": Ordenar colunas de forma ascendente",
                                "sSortDescending":
                                    ": Ordenar colunas de forma descendente"
                            }
                        ,
                        "select":
                            {
                                "rows":
                                    {
                                        "_":
                                            "Selecionado %d linhas",
                                        "0":
                                            "Nenhuma linha selecionada",
                                        "1":
                                            "Selecionado 1 linha"
                                    }
                            }
                    }
                ,
                createdRow: function (row, data, dataIndex) {
                    @if(isset($createdRow))
                    {{ $createdRow }}
                    @else
                    $(row).find('td:eq(-1)').append('' +
                        '<div class="btn-group btn-group-sm float-right" role="group" aria-label="Btns">\n' +
                        '<a href="#" class="btn btn-info show">{{ $btns['show']?? 'show' }}</a>\n' +
                        '<a href="#" class="btn btn-primary edit">{{ $btns['edit']?? 'edit' }}</a>\n' +
                        '<a href="javascript:void(0)" class="btn btn-danger destroy">{{ $btns['destroy']?? 'destroy' }}</a>\n' +
                        '</div>' +
                        '<form action="#" method="POST" class="destroy-user">' +
                        '@method("DELETE")' +
                        '@csrf' +
                        '</form>' +
                        '');
                    $(row).find('td:eq(-1) a.show').attr('href', '/admin/{{ $url }}/' + data.id);
                    $(row).find('td:eq(-1) a.edit').attr('href', '/admin/{{ $url }}/' + data.id + '/edit');
                    $(row).find('td:eq(-1) form.destroy-user').attr('action', '/admin/{{ $url }}/' + data.id);
                    @endif
                }
            });

            @if(isset($script))
            {{ $script }}
            @endif

        })
        ;
    </script>
@endpush
