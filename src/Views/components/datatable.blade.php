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
                    "data": {
                        "trashed": "{{ $trashed ?? 0 }}"
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
                    $(row).find('td:eq(-1) a.show').attr('href', '/admin/{{ $url }}/' + data.id);
                    $(row).find('td:eq(-1) a.edit').attr('href', '/admin/{{ $url }}/' + data.id + '/edit');
                    $(row).find('td:eq(-1) form.form-destroy').attr('action', '/admin/{{ $url }}/' + data.id);
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
