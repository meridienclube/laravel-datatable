# Package Vendor Laravel

Base package for all other laravel packages

## Basic Usage
```PHP
$config = [
        'id' => 'datatable_tasks',
        'items' => ['title', 'date', 'time', 'status', 'priority', 'destinateds', 'responsibles'],
        'url' => 'tasks',
        'slug' => 'tasks'
    ]
@datatable($config)

@enddatatable
```
URL:  url('api/' . $url . '/datatable?api_token=' . auth()->user()->api_token)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
