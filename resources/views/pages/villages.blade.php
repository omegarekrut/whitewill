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
            <table class="table table-striped table-striped-columns" id="villages_table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Area</th>
                        <th>Hotline</th>
                        <th>Youtube URL</th>
                        <th>Photo</th>
                        <th>File</th>
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"
    integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
</script>
<script type="module" src="https://cdn.datatables.net/v/bs5/dt-1.13.3/b-2.3.5/r-2.4.0/sl-1.6.1/datatables.min.js">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/bindings/inputmask.binding.js"></script>
<script type="module" src="{{ asset('js/dataTables.editor.min.js') }}"></script>
<script type="module" src="{{ asset('js/editor.bootstrap5.min.js') }}"></script>
<script type="module">
    $(document).ready(function () {
        const token = "{{ csrf_token() }}";
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
                data: 'address',
                title: 'Address',
                orderable: true
            },
            {
                data: 'area',
                title: 'Area (m²):',
                orderable: true,
                render: $.fn.dataTable.render.number(',', '.', 1)
            },
            {
                data: 'hotline',
                title: 'Hotline',
                orderable: true,
            },
            {
                data: 'youtube_video',
                title: 'YouTube URL',
                orderable: true,
            },
            {
                data: 'photo',
                title: 'Photos',
                orderable: false,
            },
            {
                data: 'presentation_file',
                title: 'File',
                orderable: false,
            }
        ];

        let fields_def = [
            {
                label: 'Name:',
                name: 'name',
                type: 'text',
            },
            {
                label: 'Address',
                name: 'address',
                type: 'text'
            },
            {
                label: 'Area',
                name: 'area',
                type: 'text',
                mask: '#'
            },
            {
                label: 'Hotline:',
                name: 'hotline',
                type: 'text',
                attr: {
                    'maxlength': 12,
                    'data-inputmask': "'mask': '999999999999'"
                }
            },
            {
                label: 'YouTube URL:',
                name: 'youtube_video',
                type: 'text'
            },
            {
                label: 'Photo:',
                name: 'photo',
                type: 'upload',
                folder: 'images/village/photo',
            },
            {
                label: 'File:',
                name: 'presentation_file',
                type: 'upload',
                folder: 'images/village/file',
            },
        ];

        let house_editor = new $.fn.dataTable.Editor({
            ajax: {
                create: {
                    type: "POST",
                    contentType: 'application/json',
                    url: "/api/village/create",
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
                    url: "/api/village/_id_",
                    headers: {
                        "X-CSRF-TOKEN": token,
                    },
                    data: function (d) {
                        return JSON.stringify(d.data[Object.keys(d.data)[0]]);
                    },
                },
                remove: {
                    type: "DELETE",
                    contentType: 'application/json',
                    url: "/api/village/_id_",
                    headers: {
                        "X-CSRF-TOKEN": token,
                    },
                    data: function (d) {
                        return JSON.stringify(d.data[Object.keys(d.data)[0]]);
                    },
                }
            },
            table: "#villages_table",
            idSrc: "id",
            fields: fields_def
        });


        let houses_table = $("#villages_table").DataTable({
            ajax: {
                type: "POST",
                url: "/api/village",
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