<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Regency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h1>Regency</h1>
            </div>
            <div class="col-6 text-end mt-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Add Regency
                </button>
            </div>
        </div>
        <form action="{{ route('regency.population-report') }}" target="blank">
            <div class="row my-3">
                <div class="col-3">
                    <select class="form-select" name="filter" id="">
                        <option value="">Choose Province</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province->name }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-success text-capitalize col-3" target="blank">Population report </button>
            </div>
        </form>
        <div class="row justify-content-end my-3">
            <div class="col-4">
                <input type="text" aria-describedby="filterHelp" class="form-control filter-regency" placeholder="Filter Regency"
                    value="{{ app('request')->filter }}">
                <div id="filterHelp" class="form-text text-secondary text-end">Press enter to filter table</div>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Regency</th>
                    <th scope="col">Population</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($regencies as $regency)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $regency['name'] }} ({{ $regency['province'] }})</td>
                        <td>{{ $regency['population'] }}</td>
                        <td>
                            <button class="btn btn-warning edit-regency" data-id="{{ $regency['id'] }}">Edit</button>
                            <button class="btn btn-danger delete-regency" data-id="{{ $regency['id'] }}">Delete</button>
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
                    <h5 class="modal-title">Add Regency</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="id">
                        <div class="mb-3">
                            <label for="provinceId" class="form-label">Province</label>
                            <select class="form-select" name="provinceId" id="provinceId">
                                <option value="">Choose One</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Regency Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="population" class="form-label">Regency Population</label>
                            <input type="text" class="form-control" id="population" name="population">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save-regency">Save changes</button>
                    <button type="button" class="btn btn-warning d-none update-regency">Update changes</button>
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
            $('.filter-regency').keyup(function(e) {
                if (e.key === "Enter") {
                    window.location = `{{ url()->current() }}${e.currentTarget.value != ""? `?filter=${e.currentTarget.value}` : ''}`
                }
            });
            $('.save-regency').click(function() {
                $.ajax({
                    type: "POST",
                    url: `{{ route('regency.store') }}`,
                    data: {
                        _token: `{{ csrf_token() }}`,
                        name: $('[name=name]').val(),
                        provinceId: $('[name=provinceId]').val(),
                        population: $('[name=population]').val(),
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
                            $('.modal').find('.modal-body').find(`form [name=${key}]`).addClass('is-invalid')
                        });
                    }
                });
            });
            $('.delete-regency').click(function() {
                var userConfirmed = confirm("Are you sure want to delete this resources?");
                if (userConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: `{{ route('regency.destroy') }}/${$(this).data('id')}`,
                        data: {
                            _token: `{{ csrf_token() }}`
                        },
                        dataType: "JSON",
                        success: function(response) {
                            window.location = `{{ url()->full() }}`
                        }
                    });
                }
            });
            $('.update-regency').click(function() {
                $.ajax({
                    type: "PATCH",
                    url: `{{ route('regency.update') }}/${$('input[name=id]').val()}`,
                    data: {
                        _token: `{{ csrf_token() }}`,
                        name: $('[name=name]').val(),
                        provinceId: $('[name=provinceId]').val(),
                        population: $('[name=population]').val(),
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
            $('.edit-regency').click(function() {
                $.ajax({
                    type: "GET",
                    url: `{{ route('regency.show') }}/${$(this).data('id')}`,
                    dataType: "json",
                    success: function(response) {
                        $('.modal').modal('show');
                        $('.save-regency').addClass('d-none');
                        $('.update-regency').removeClass('d-none');
                        Object.keys(response.data).map(key => {
                            $('.modal').find('.modal-body').find(`form [name=${key}]`).val(response.data[key]).trigger(
                                'change');
                        })
                    }
                });
            })
            $('.modal').on('hidden.bs.modal', function() {
                $('.modal').find('.modal-body').find('.alert').remove();
                $('.modal').find('.modal-body').find(`form`).find('input, select').map((index, element) => {
                    $(element).val('').trigger('change').removeClass('is-invalid');
                });
                $('.save-regency').removeClass('d-none');
                $('.update-regency').addClass('d-none');
            })
        });
    </script>
</body>

</html>
