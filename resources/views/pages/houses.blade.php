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
            url: "/api/village",
            headers: {
                "X-CSRF-TOKEN": token,
            },
            dataSrc: "",
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
                label: 'Name:',
                name: 'name',
                type: 'text',
            },
            {
                label: 'Price (RUB):',
                name: 'price_rub',
                type: 'text',
                mask: "#",
                fieldInfo: 'Price in RUB',
            },
            {
                label: 'Price (USD):',
                name: 'price_usd',
                type: 'text',
                mask: "#",
                fieldInfo: 'Price in USD',
            },
            {
                label: 'Default Currency:',
                name: 'default_currency',
                type: 'select',
                options: [
                    { label: 'RUB', value: 'RUB' },
                    { label: 'USD', value: 'USD' },
                ],
            },
            {
                label: 'Floors:',
                name: 'floors',
                type: 'text',
                mask: "#"
            },
            {
                label: 'Bedrooms:',
                name: 'bedrooms',
                type: 'text',
                mask: "#"
            },
            {
                label: 'Area (m²):',
                name: 'area',
                type: 'text',
                mask: "#",
                fieldInfo: 'Area in square meters',
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
                type: 'upload'
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
                    url: "/api/houses/_id_",
                    headers: {
                        "X-CSRF-TOKEN": token,
                    },
                    data: function (d) {
                        return JSON.stringify({data: d.data});
                    },
                },
                remove: {
                    type: "DELETE",
                    contentType: 'application/json',
                    url: "/api/houses/_id_",
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
                },
                dataSrc: "",
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