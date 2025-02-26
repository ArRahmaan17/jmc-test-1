<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Province</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h1>Province</h1>
            </div>
            <div class="col-6 text-end mt-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Add Province
                </button>
                <a href="{{ route('province.population-report') }}" class="btn btn-success" target="blank">
                    Population report
                </a>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th>Province</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($provinces as $province)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $province->name }}</td>
                        <td>
                            <button class="btn btn-warning edit-province" data-id="{{ $province->id }}">Edit</button>
                            <button class="btn btn-danger delete-province" data-id="{{ $province->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal" tabindex="-1" id="exampleModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Province</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="id" name="id" value="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Province Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save-province">Save changes</button>
                    <button type="button" class="btn btn-warning d-none update-province">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('.save-province').click(function() {
                $.ajax({
                    type: "POST",
                    url: `{{ route('province.store') }}`,
                    data: {
                        _token: `{{ csrf_token() }}`,
                        name: $('[name=name]').val(),
                    },
                    dataType: "json",
                    success: function(response) {
                        window.location = `{{ url()->full() }}`
                    },
                    error: function(error) {
                        $('.modal').find('.modal-body').find('.alert').remove();
                        $('.modal').find('.modal-body').prepend(
                            `<div class="alert alert-danger" role="alert">${error.responseJSON.message}</div>`);
                        Object.keys(error.responseJSON.errors).map((key) => {
                            $('.modal').find('.modal-body').find(`form input[name=${key}]`).addClass('is-invalid')
                        })
                    }
                });
            });
            $('.update-province').click(function() {
                $.ajax({
                    type: "PATCH",
                    url: `{{ route('province.update') }}/${$('input[name=id]').val()}`,
                    data: {
                        _token: `{{ csrf_token() }}`,
                        name: $('[name=name]').val(),
                    },
                    dataType: "json",
                    success: function(response) {
                        window.location = `{{ url()->full() }}`
                    },
                    error: function(error) {
                        $('.modal').find('.modal-body').find('.alert').remove();
                        $('.modal').find('.modal-body').prepend(
                            `<div class="alert alert-danger" role="alert">${error.responseJSON.message}</div>`);
                        Object.keys(error.responseJSON.errors).map((key) => {
                            $('.modal').find('.modal-body').find(`form input[name=${key}]`).addClass('is-invalid')
                        })
                    }
                });
            });
            $('.delete-province').click(function() {
                var userConfirmed = confirm("Are you sure want to delete this province resources?");
                if (userConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: `{{ route('province.destroy') }}/${$(this).data('id')}`,
                        dataType: "JSON",
                        data: {
                            _token: `{{ csrf_token() }}`
                        },
                        success: function(response) {
                            window.location = `{{ url()->full() }}`
                        }
                    });
                }
            })
            $('.edit-province').click(function() {
                $.ajax({
                    type: "GET",
                    url: `{{ route('province.show') }}/${$(this).data('id')}`,
                    dataType: "json",
                    success: function(response) {
                        $('.modal').modal('show');
                        $('.save-province').addClass('d-none');
                        $('.update-province').removeClass('d-none');
                        Object.keys(response.data).map(key => {
                            $('.modal').find('.modal-body').find(`form input[name=${key}]`).val(response.data[key]);
                        })
                    }
                });
            })
            $('.modal').on('hidden.bs.modal', function() {
                $('.modal').find('.modal-body').find('.alert').remove();
                $('.modal').find('.modal-body').find(`form input`).map((index, element) => {
                    $(element).val('').removeClass('is-invalid');
                });
                $('.save-province').removeClass('d-none');
                $('.update-province').addClass('d-none');
            })
        });
    </script>
</body>

</html>
