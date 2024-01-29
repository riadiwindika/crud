@extends('layouts.main')

@section('title')
    Dashboard Admin
@endsection

@section('content')
    <div class="row">
        <div class="col-10">
            <h5>Edit Product</h5>
            <form action="{{ route('product.update', ['product' => $product->id]) }}" id="custom_form" class="mt-5" enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nama_barang">Nama Product</label>
                            <input type="text" name="name" id="nama_barang" value="{{$product->name}}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="harga_product">Harga Product</label>
                            <input type="number" name="price" id="harga_product" value="{{$product->price}}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="total_product">Total Product</label>
                            <input type="number" name="quantity" id="total_product" value="{{$product->quantity}}" class="form-control">
                        </div>
                    </div>
                </div>
                <button type="submit" id="btn_submit" class="btn btn-primary px-4 mt-2">Submit</button>
            </form>
        </div>
    </div>
@endsection

@push('page_js')
<script>
    $(document).on('click', '#btn_submit', function(e) {
            console.log('sss');
            e.preventDefault();
            customFormSubmit();
        });

        function customFormSubmit() {
            $("#btn_submit").prop("disabled", true);

            let myForm = document.getElementById('custom_form');
            let formData = new FormData(myForm);

            const form = $(myForm);
            $.ajax({
                type: "POST",
                url: $('#custom_form').attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-HTTP-Method-Override': 'PATCH'
                },
                success: function (result) {
                    if (result.success) {
                        Swal.fire(result.message, '', 'success').then((res) => {
                            if (result.redirect) {
                                window.location.replace(result.redirect);
                            }
                        });
                    } else {
                        form.find('input, select, textarea').removeClass('is-invalid');
                        form.find('.invalid-feedback').remove();
                        Swal.fire(result.message, '', 'error');
                    }

                    // showLoading(false);
                },
                error: function (xhr, err, thrownError) {
                    var errorsArray = [];

                    $(".invalid-feedback-modal").remove();

                    var data = xhr.responseJSON;
                    $.each(data.errors, function (key, v) {
                        form.find('input[name="' + key + '"]')
                            .addClass('is-invalid')
                            .after(`<div class="invalid-feedback invalid-feedback-modal float-start">` + v[0] + `</div>`);
                        form.find('select[name="' + key + '"]')
                            .addClass('is-invalid')
                            .after(`<div class="invalid-feedback invalid-feedback-modal float-start">` + v[0] + `</div>`);
                        form.find('textarea[name="' + key + '"]')
                            .addClass('is-invalid')
                            .after(`<div class="invalid-feedback invalid-feedback-modal float-start">` + v[0] + `</div>`);

                        var errorObj = {
                            key: key,
                            text: v[0]
                        };
                        errorsArray.push(errorObj);
                    });

                    if (errorsArray.length > 0) {
                        var error_html = '';
                        $.each(errorsArray, function(index, value) {
                            error_html += `
                                <li class="text-start">` + value.text + `</li>
                            `;
                        });

                        Swal.fire({
                            title: '<strong>There is something wrong</strong>',
                            icon: 'warning',
                            html: `
                                <ul class="mb-0">
                                    ` + error_html + `
                                </ul>
                            `,
                            showCloseButton: true,
                        });
                    }

                    // showLoading(false);
                }
            });

            $("#btn_submit").prop("disabled", false);
        }
</script>
@endpush
