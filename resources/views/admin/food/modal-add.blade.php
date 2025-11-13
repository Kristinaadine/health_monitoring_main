<div class="modal fade" id="modalAdd">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Food</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAdd" action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="" name="name_food" id="name_food">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12">
                            <label>Categories <span class="text-danger">*</span></label>
                            <select class="form-control" name="id_categories" id="id_categories">
                                <option value="">SELECT CATEGORIES</option>
                                @foreach ($categories as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3 col-md-3">
                            <label>Protein <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="protein" id="protein">
                                <div class="input-group-append">
                                    <span class="input-group-text">g</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label>Carbs <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="carbs" id="carbs">
                                <div class="input-group-append">
                                    <span class="input-group-text">g</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label>Fiber <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="fiber" id="fiber">
                                <div class="input-group-append">
                                    <span class="input-group-text">g</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label>Calories <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="" name="calories" id="calories">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12">
                            <label>Description</label>
                            <textarea class="form-control" rows="15" id="editor2" name="description" placeholder="Description">
                                {{--  --}}
                            </textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6 col-md-6">
                            <label>Image</label>
                            <input type="file" class="form-control" name="image" id="image" accept="image/*">
                            <small class="text-danger">Note : Image size 1080*1080 px</small>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <img src="{{ asset('') }}assets-admin/assets/img/noimage.png" alt=""
                                width="200px" id="preview" class="img-thumbnail">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnSubmit">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="{{ asset('') }}assets-admin/assets/vendor/ckeditor/ckeditor.js"></script>
    <script>
        image.onchange = evt => {
            const [file] = image.files
            if (file) {
                preview.src = URL.createObjectURL(file)
            }
        }
        let geteditor;

        function editor2() {
            /* classic CK Editor */
            ClassicEditor
                .create(document.querySelector('#editor2')).then(newEditor => {
                    geteditor = newEditor;
                })
                .catch(error => {
                    console.error(error);
                });

        }
        $(document).ready(function() {
            editor2()
            $('#btnSubmit').on('click', function() {

                let name_food = $('#name_food').val();
                let protein = $('#protein').val();
                let carbs = $('#carbs').val();
                let fiber = $('#fiber').val();
                let id_categories = $('#id_categories').val();

                if (!name_food) {
                    $("#name_food").notify(
                        "Name Food is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!protein) {
                    $("#protein").notify(
                        "Protein is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!carbs) {
                    $("#carbs").notify(
                        "Carbs is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!fiber) {
                    $("#fiber").notify(
                        "Fiber is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!id_categories) {
                    $("#id_categories").notify(
                        "Categories is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!name_food || !protein || !carbs || !fiber || !id_categories) {
                    return false;
                }

                const fd = new FormData($('#formAdd')[0]);
                fd.append('description', geteditor.getData());
                $.ajax({
                    type: "POST",
                    url: "{{ locale_route('administration.food.store') }}",
                    data: fd,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#btnSubmit').attr('disabled', true);
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $.notify(response.message, "success");
                            $('#modalAdd').modal('hide');
                            $('#formAdd')[0].reset();
                            $('#tablefood').DataTable().ajax.reload();
                        } else {
                            $.notify(response.message, "error");
                        }
                    },
                    error: function(xhr) {
                        $.notify("An error occurred", "error");
                    },
                    complete: function() {
                        $('#btnSubmit').attr('disabled', false);
                    }
                })
            });
        });
    </script>
@endpush
