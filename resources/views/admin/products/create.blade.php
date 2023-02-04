@extends('admin.layouts.app')
@section('title', 'Create Product')
@section('content')
    <div class="card">
        <h1>Create Product</h1>

        <div>
            <form action="{{ route('products.store') }}" method="post" id="createForm" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class=" input-group-static col-5 mb-4">
                        <label>Image</label>
                        <input type="file" accept="image/*" name="image" id="image-input" class="form-control">

                        @error('image')
                            <span class="text-danger"> {{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-5">
                        <img src="" id="show-image" alt="" width="300px">
                    </div>
                </div>

                <div class="input-group input-group-static mb-4">
                    <label>Name</label>
                    <input type="text" value="{{ old('name') }}" name="name" class="form-control">

                    @error('name')
                        <span class="text-danger"> {{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group input-group-static mb-4">
                    <label>Price</label>
                    <input type="number" step="0.1" value="{{ old('price') }}" name="price" class="form-control">
                    @error('price')
                        <span class="text-danger"> {{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group input-group-static mb-4">
                    <label>Sale</label>
                    <input type="number" value="0" value="{{ old('sale') }}" name="sale" class="form-control">
                    @error('sale')
                        <span class="text-danger"> {{ $message }}</span>
                    @enderror
                </div>



                <div class="form-group">
                    <label>Description</label>
                    <div class="row w-100 h-100">
                        <textarea name="description" id="description" class="form-control" cols="4" rows="5"
                            style="width: 100%">{{ old('description') }} </textarea>
                    </div>
                    @error('description')
                        <span class="text-danger"> {{ $message }}</span>
                    @enderror
                </div>
                <input type="hidden" id="inputSize" name='sizes'>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddSizeModal">
                    Add size
                </button>

                <!-- Modal -->
                <div class="modal fade" id="AddSizeModal" tabindex="-1" aria-labelledby="AddSizeModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="AddSizeModalLabel">Add size</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="AddSizeModalBody">

                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn  btn-primary btn-add-size">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        {{-- <div class="input-group input-group-static mb-4">
            <label name="group" class="ms-0">Category</label>
            <select name="category_ids[]" class="form-control" multiple>
                @foreach ($categories as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>

            @error('category_ids')
                <span class="text-danger"> {{ $message }}</span>
            @enderror
        </div> --}}

        <button type="submit" class="btn btn-submit btn-primary">Submit</button>
        </form>
    </div>
    </div>
@endsection

@section('style')
    <style>
        .w-40 {
            width: 40%;
        }

        .w-20 {
            width: 20%;
        }

        .row {
            justify-content: center;
            align-items: center
        }

        .ck.ck-editor {
            width: 100%;
            height: 100%;
        }

    </style>
@endsection
@section('script')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"
        integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('plugin/ckeditor5-build-classic/ckeditor.js') }}"></script>
    <script>
        let sizes = [{
            id: Date.now(),
            size: 'M',
            quantity: 1
        }];
    </script>

    <script>
        $(() => {
            ClassicEditor.create(document.querySelector("#description"), {
                // toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]
            })
                .then((editor) => {
                    window.editor = editor;
                })
                .catch((err) => {
                    console.error(err.stack);
                });
            renderSizes(sizes);
            appendSizesToForm();

            function renderSizes(sizes) {
                for (let size of sizes) {
                    renderSize(size);
                }
            }

            function getSizeIndex(sizes, id) {
                let index = _.findIndex(sizes, function (o) {
                    return o.id == id;
                });

                return index;
            }

            function removeSize(sizes, id) {
                let index = getSizeIndex(sizes, id);
                if (index >= 0) {
                    $(`#product-size${sizes[index].id}`).remove();
                    sizes.splice(index, 1);
                }
            }

            function addSize() {
                let size = {
                    id: Date.now(),
                    size: "30",
                    quantity: 1,
                };
                sizes = [...sizes, size];
                renderSize(size);
            }

            function appendSizesToForm() {
                $("#inputSize").val(JSON.stringify(sizes));
            }

            $(document).on("click", ".btn-remove-size", function () {
                let id = $(this).data("id");
                removeSize(sizes, id);
                appendSizesToForm();
            });

            $(document).on("click", ".btn-add-size", function () {
                addSize();
                appendSizesToForm();
            });

            $(document).on("keyup", ".input-size", function () {
                let id = $(this).data("id");
                let size = $(this).val();
                let index = getSizeIndex(sizes, id);
                console.log(1);
                if (index >= 0) {
                    sizes[index].size = size;
                }
                appendSizesToForm();
            });

            function renderSize(size) {
                let html = `<div class="product-item-size" id="product-size${size.id}">
                                    <div class="row ">
                                        <div class="input-group input-group-static col-5 w-40">
                                            <label>Size</label>
                                            <input value="${size.size}" type="text" class="form-control input-size" data-id="${size.id}">
                                        </div>

                                        <div class="input-group input-group-static col-5 w-40">
                                            <label>Quantity</label>
                                            <input type="number" value="${size.quantity}" class="form-control input-quantity" data-id="${size.id}">
                                        </div>
                                        <div class="w-20">
                                            <button type="button" class="btn btn-danger btn-remove-size" data-id="${size.id}">X</button>
                                        </div>
                                    </div>`;
                $("#AddSizeModalBody").append(html);
            }

            $(document).on("keyup", ".input-quantity", function () {
                let id = $(this).data("id");
                let quantity = $(this).val();
                let index = getSizeIndex(sizes, id);

                if (index >= 0) {
                    sizes[index].quantity = quantity;
                }
            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $("#show-image").attr("src", e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#image-input").change(function () {
                readURL(this);
            });
        });

    </script>
@endsection
