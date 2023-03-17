@extends('layouts.app')

@section('style')
<link href="https://cdn.datatables.net/v/bs5/dt-1.13.3/b-2.3.5/date-1.3.1/r-2.4.0/sl-1.6.1/datatables.min.css"
    rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/editor.bootstrap5.min.css') }}">
@endsection
{{-- main content --}}
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <table class="table table-striped table-striped-columns" id="houses_table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price (RUB)</th>
                        <th>Price (USD)</th>
                        <th>Default Currency</th>
                        <th>Floors</th>
                        <th>Bedrooms</th>
                        <th>Area (m²)</th>
                        <th>Object Type</th>
                        <th>Image Gallery</th>
                        <th>Village</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

{{-- scripts --}}
@section('scripts')
<script type="module" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
</script>
<script type="module" src="https://cdn.datatables.net/v/bs5/dt-1.13.3/b-2.3.5/r-2.4.0/sl-1.6.1/datatables.min.js">
</script>
<script type="module" src="{{ asset('js/dataTables.editor.min.js') }}"></script>
<script type="module" src="{{ asset('js/editor.bootstrap5.min.js') }}"></script>
<script type="module">
    $(document).ready(function () {
        const token = "{{ csrf_token() }}";
        var villages_array = [];
        $.ajax({
            type: "POST",
            contentType: 'application/json',
            url: "/api/village/list",
            headers: {
                "X-CSRF-TOKEN": token,
            },
            dataSrc: "data",
            success: function (data) {
                villages_array = $.map(data, function(item) {
                    return { label: item.name, value: item.id };
                });
                house_editor.field('village_id').update(villages_array);
            }
        });

        let column_def = [
            {
                data: 'id',
                title: 'ID',
                orderable: true,
            },
            {
                data: 'name',
                title: 'Name',
                orderable: true,
            },
            {
                data: 'price_rub',
                title: 'Price (RUB)',
                orderable: true,
                render: $.fn.dataTable.render.number(',', '.', 2, '₽'),
            },
            {
                data: 'price_usd',
                title: 'Price (USD)',
                orderable: true,
                render: $.fn.dataTable.render.number(',', '.', 2, '$'),
            },
            {
                data: 'default_currency',
                title: 'Default Currency',
                orderable: true,
            },
            {
                data: 'floors',
                title: 'Floors',
                orderable: true,
            },
            {
                data: 'bedrooms',
                title: 'Bedrooms',
                orderable: true,
            },
            {
                data: 'area',
                title: 'Area (m²)',
                orderable: true,
            },
            {
                data: 'object_type',
                title: 'Object Type',
                orderable: true,
            },
            {
                data: 'image_gallery',
                title: 'Image Gallery',
                orderable: false,
                render: function ( file_id ) {
                    return file_id ?
                        '<img class="img-fluid" src="'+house_editor.file( 'files', file_id ).web_path+'"/>' :
                        null;
                },
                defaultContent: "No image",
                title: "Image"
            },
            {
                data: 'village_id',
                title: 'Village',
                orderable: true,
                render: function(data, type, row, meta) {
                    var village_name = '';
                    $.each(villages_array, function(index, village) {
                        if (village.value == data) {
                            village_name = village.label;
                            return false; // exit the loop
                        }
                    });
                    return village_name;
                }
            },
        ];

        let fields_def = [
            {
                label: 'ID:',
                name: 'id',
                type: 'hidden',
            },
            {
                label: 'Name:',
                name: 'name',
                type: 'text',
                fieldInfo: 'Max 25 characters',
                attr: {
                    maxLength: 25
                }
            },
            {
                label: 'Price (RUB):',
                name: 'price_rub',
                type: 'text',
                mask: "#",
                fieldInfo: 'Price in RUB',
                attr: {
                    maxLength: 25
                }
            },
            {
                label: 'Price (USD):',
                name: 'price_usd',
                type: 'text',
                mask: "#",
                fieldInfo: 'Price in USD',
                attr: {
                    maxLength: 25
                }
            },
            {
                label: 'Default Currency:',
                name: 'default_currency',
                type: 'select',
                options: [
                    { label: 'RUB', value: 'RUB' },
                    { label: 'USD', value: 'USD' },
                ],
                attr: {
                    maxLength: 3
                }
            },
            {
                label: 'Floors:',
                name: 'floors',
                type: 'text',
                mask: "#",
                attr: {
                    maxLength: 3
                }
            },
            {
                label: 'Bedrooms:',
                name: 'bedrooms',
                type: 'text',
                mask: "#",
                attr: {
                    maxLength: 2
                }
            },
            {
                label: 'Area (m²):',
                name: 'area',
                type: 'text',
                mask: "#",
                fieldInfo: 'Area in square meters',
                attr: {
                    maxLength: 3
                }
            },
            {
                label: 'Object Type:',
                name: 'object_type',
                type: 'select',
                options: [
                    { label: 'House', value: 'House' },
                    { label: 'Cottage', value: 'Cottage' },
                    { label: 'Townhouse', value: 'Townhouse' },
                    { label: 'Apartment', value: 'Apartment' },
                ],
            },
            {
                label: 'Image Gallery:',
                name: 'image_gallery',
                type: "upload",
                noImageText: 'No image',
                ajax: {
                    type: "POST",
                    url: "/api/houses/upload-image",
                    headers: {
                        "X-CSRF-TOKEN": token,
                    },
                    data: function (d) {
                        return d;
                    }
                },
                display: function ( file_id ) {
                    return '<img class="img-fluid" src="'+house_editor.file( 'files', file_id ).web_path+'" />';
                },
                noImageText: 'No image'
            },
            {
                label: 'Village:',
                name: 'village_id',
                type: 'select',
                options: villages_array,
                placeholder: 'Select a village',
            },
        ];

        let house_editor = new $.fn.dataTable.Editor({
            ajax: {
                create: {
                    type: "POST",
                    contentType: 'application/json',
                    url: "/api/houses/create",
                    headers: {
                        "X-CSRF-TOKEN": token,
                    },
                    data: function (d) {
                        return JSON.stringify(d.data[0]);
                    },
                },
                edit: {
                    type: "POST",
                    contentType: 'application/json',
                    url: "/api/houses/update",
                    headers: {
                        "X-CSRF-TOKEN": token,
                    },
                    data: function (d) {
                        return JSON.stringify({data: d.data});
                    },
                },
                remove: {
                    type: "POST",
                    contentType: 'application/json',
                    url: "/api/houses/delete",
                    headers: {
                        "X-CSRF-TOKEN": token,
                    },
                    data: function (d) {
                        return JSON.stringify(d.data[Object.keys(d.data)[0]]);
                    },
                }
            },
            table: "#houses_table",
            idSrc: "id",
            fields: fields_def
        });

        let houses_table = $("#houses_table").DataTable({
            ajax: {
                type: "POST",
                url: "/api/houses",
                headers: {
                    "X-CSRF-TOKEN": token,
                }
            },
            dom: "Bfrtip",
            responsive: true,
            select: true,
            columns: column_def,
            buttons: [
                { extend: "create", editor: house_editor },
                { extend: 'edit', editor: house_editor },
                { extend: 'remove', editor: house_editor }
            ],
        });

    });
</script>
@endsection