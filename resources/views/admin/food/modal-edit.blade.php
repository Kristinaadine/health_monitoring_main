<div class="modal fade" id="modalEdit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Food</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit" action="#" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="id">
                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="" name="name_food" id="Editname_food">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12">
                            <label>Categories <span class="text-danger">*</span></label>
                            <select class="form-control" name="id_categories" id="Editid_categories">
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
                                <input type="number" class="form-control" name="protein" id="Editprotein">
                                <div class="input-group-append">
                                    <span class="input-group-text">g</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label>Carbs <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="carbs" id="Editcarbs">
                                <div class="input-group-append">
                                    <span class="input-group-text">g</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label>Fiber <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="fiber" id="Editfiber">
                                <div class="input-group-append">
                                    <span class="input-group-text">g</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label>Calories <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="" name="calories" id="Editcalories">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12">
                            <label>Description</label>
                            <textarea class="form-control" rows="15" id="Editeditor2" name="description" placeholder="Description">
                                {{--  --}}
                            </textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6 col-md-6">
                            <label>Image</label>
                            <input type="file" class="form-control" name="image" id="Editimage" accept="image/*">
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <img src="{{ asset('') }}assets-admin/assets/img/noimage.png" alt=""
                                width="200px" id="Editpreview" class="img-thumbnail">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnUpdate">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        Editimage.onchange = evt => {
            const [file] = Editimage.files
            if (file) {
                Editpreview.src = URL.createObjectURL(file)
            }
        }
        let Editgeteditor;

        function Editeditor2() {
            /* classic CK Editor */
            ClassicEditor
                .create(document.querySelector('#Editeditor2')).then(newEditor => {
                    Editgeteditor = newEditor;
                })
                .catch(error => {
                    console.error(error);
                });

        }
        // SHOW EDIT DATA
        $(document).on('click', ".editBtn", function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            var url = "{{ locale_route('administration.food.edit', ':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 'success') {
                        $('#id').val(response.id);
                        $('#Editname_food').val(response.data.name_food);
                        $('#Editprotein').val(response.data.protein);
                        $('#Editcarbs').val(response.data.carbs);
                        $('#Editfiber').val(response.data.fiber);
                        $('#Editcalories').val(response.data.calories);
                        $('#Editid_categories').val(response.data.id_categories);
                        Editgeteditor.setData(response.data.description);
                        $('#Editpreview').attr('src', response.image);
                        $('#modalEdit').modal('show');
                    } else {
                        $.notify(response.message, "error");
                    }

                }
            });
        });

        $(document).ready(function() {
            Editeditor2()

            $('#btnUpdate').on('click', function() {

                let name_food = $('#Editname_food').val();
                let protein = $('#Editprotein').val();
                let carbs = $('#Editcarbs').val();
                let fiber = $('#Editfiber').val();
                let id_categories = $('#Editid_categories').val();

                if (!name_food) {
                    $("#Editname_food").notify(
                        "Name Food is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!protein) {
                    $("#Editprotein").notify(
                        "Protein is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!carbs) {
                    $("#Editcarbs").notify(
                        "Carbs is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!fiber) {
                    $("#Editfiber").notify(
                        "Fiber is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!id_categories) {
                    $("#Editid_categories").notify(
                        "Categories is required", {
                            position: "bottom"
                        }
                    );
                }

                if (!name_food || !protein || !carbs || !fiber || !id_categories) {
                    return false;
                }

                const fd = new FormData($('#formEdit')[0]);
                fd.append('description', Editgeteditor.getData());
                var id = $('#id').val();
                var url = "{{ locale_route('administration.food.update', ':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: fd,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#btnUpdate').attr('disabled', true);
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $.notify(response.message, "success");
                            $('#modalEdit').modal('hide');
                            $('#formEdit')[0].reset();
                            $('#tablefood').DataTable().ajax.reload();
                        } else {
                            $.notify(response.message, "error");
                        }
                    },
                    error: function(xhr) {
                        $.notify("An error occurred", "error");
                    },
                    complete: function() {
                        $('#btnUpdate').attr('disabled', false);
                    }
                })
            });
        });
    </script>
@endpush
