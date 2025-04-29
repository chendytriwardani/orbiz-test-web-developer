<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>



    {{-- font awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    {{-- custom css --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- custom datatables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">

    {{-- custom js --}}
    <script src="{{ asset('js/app.js') }}"></script>

    <title>Document</title>
</head>
<body>


    {{-- navbar --}}
    @include('layouts.navbar')

    {{-- Card --}}
    <div class="container">
        {{-- create button --}}
        <div class="row">
            <div class="col-lg-12">
                
                
                <div class="card">
                    <div class="card-header">
                        Tabel Books
                    </div>
                    <div class="card-body">
                        <div class="btn">
                            <button onclick=showModal() type="button" class="btn btn-primary">+ Create Books</button>
                        </div>
                        <table class="table table-bordered table-striped" id="tableBooks" style="width: 100%">
                            <thead>
                                <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>vote_count</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>

    {{-- consume api only title and description --}}
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        Tabel Books
                    </div>
                    <div class="card-body">
                        <div class="card" style="width: 18rem;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                              <h5 class="card-title"></h5>
                              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            </div>
                          </div>
                    </div>
                </div>
                
            </div>
        </div>
    
</div>

@include('component.modal')


    <!-- jQuery (FULL version, not slim) -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\ProductRequest', '#productBook') !!}
 

    <script>
        let save_method;
    
        function resetFormValidation() {
            $('#productBook')[0].reset();
            $('#productBook').find('.is-invalid').removeClass('is-invalid');
            $('#productBook').find('.invalid-feedback').remove();
        }
    
        function booksTable() {
            $('#tableBooks').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('books/dataTable') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'author', name: 'author' },
                    { data: 'genre', name: 'genre' },
                    { data: 'vote_count', name: 'vote_count' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        }
    
        function showModal() {
            $('#productModal').modal('show');
            resetFormValidation(); 
    
            save_method = 'create';
            $('#productModal').find('.modal-title').text('Create Product');
            $('.btnSubmit').text('Save Product');
        }
    
        $('#productBook').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

    
            let url = "{{ url('books') }}";
            let method = 'POST';
    
            if (save_method == 'update') {
                url = "{{ url('books') }}/" + formData.get('id');
                method = 'PUT';
                formData.append('_method', 'PUT');
            }
    
            $.ajax({
                data: formData,
                contentType: false,
                processData: false,
                type: 'POST',
                url: url,
                success: function(response) {
                    $('#productModal').modal('hide');
                    resetFormValidation(); 
                    $('#tableBooks').DataTable().ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '\n';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: errorMessage,
                        });
                    }
                }
            });
        });
    
        $(document).ready(function() {
            booksTable();
            $('#productModal').on('hidden.bs.modal', function () {
                resetFormValidation();
            });
        });
    
        // Edit Modal
        function editModal(e) {
            let id = e.getAttribute('data-id');
    
            resetFormValidation();
            save_method = 'update';
    
            $('#productModal').modal('show');
            $('#productModal').find('.modal-title').text('Edit Product');
            $('.btnSubmit').text('Update Product');
    
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                url: "{{ url('books') }}/" + id,
                success: function(response) {
                    let product = response.data;
    
                    $('#id').val(product.id);
                    $('#title').val(product.title);
                    $('#author').val(product.author);
                    $('#genre').val(product.genre);
                    $('#vote_count').val(product.vote_count);

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                    alert('Failed to fetch product data.');
                }
            });
        }
    
        function deleteModal(e) {
            let id = e.getAttribute('data-id');
    
            Swal.fire({
                title: 'Delete Product',
                text: "Are you sure to delete this product?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'DELETE',
                        url: "{{ url('books') }}/" + id,
                        dataType: 'json',
                        success: function(response) {
                                
                                $('#tableBooks').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON.message,
                            });
                        }
                    });
                }
            });
        }
    </script>
    

</body>
</html>