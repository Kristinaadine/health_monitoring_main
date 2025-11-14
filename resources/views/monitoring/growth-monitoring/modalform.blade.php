

<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormLabel">{{__('monitoring.add_child_data')}}</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close">
                    <!-- <span aria-hidden="true">&times;</span> -->
                </button>
            </div>
            <div class="modal-body">
                {{-- Alert box inside modal --}}
                <div id="modal-validation-alert" class="alert alert-danger d-none mb-3" role="alert">
                    <strong><i class="icofont-warning"></i> Perhatian!</strong>
                    <div id="modal-error-messages" class="mt-2"></div>
                </div>
                
                <form action="#" class="" method="POST" id="formAdd" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Identitas Section --}}
                    <div class="card mb-3 border-0 bg-light">
                        <div class="card-body p-3">
                            <h6 class="mb-3"><strong>📋 Identitas Anak</strong></h6>
                            
                            {{-- ID User - Tampilkan ID yang sudah ada --}}
                            <div class="form-group mb-3" id="user-id-display">
                                <label class="form-label">🏥 ID Pengenal</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light" id="user_id_display" 
                                           readonly style="font-weight: bold; font-size: 1.1rem;">
                                    <button type="button" class="btn btn-outline-secondary" id="btn-copy-id-form" 
                                            onclick="copyChildIdForm()" title="Copy ID">
                                        <i class="icofont-copy"></i> Copy
                                    </button>
                                </div>
                                <small class="text-muted">ID ini permanen untuk anak ini</small>
                            </div>
                            {{-- JANGAN kirim user_id ke backend, biarkan backend yang ambil dari tabel users --}}
                            
                            {{-- Foto - Tampilkan edit foto jika sudah ada data sebelumnya --}}
                            <div class="form-group mb-0" id="photo-section">
                                <label class="form-label">📸 Foto Anak</label>
                                
                                {{-- Jika belum ada foto (data pertama) --}}
                                <div id="photo-upload-first">
                                    <input type="file" class="form-control" id="photo" name="photo" 
                                           accept="image/jpeg,image/png,image/jpg">
                                    <small class="text-muted">Format: JPG, PNG (Max 2MB) - Opsional</small>
                                </div>
                                
                                {{-- Jika sudah ada foto (data kedua dst) --}}
                                <div id="photo-edit-section" style="display: none;">
                                    <div class="d-flex align-items-center mb-2">
                                        <img id="existing-photo" src="" class="rounded" 
                                             style="max-width: 80px; max-height: 80px; object-fit: cover; border: 2px solid #ddd;">
                                        <div class="ms-3">
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-change-photo">
                                                <i class="icofont-edit"></i> Ubah Foto
                                            </button>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control d-none" id="photo-edit" name="photo" 
                                           accept="image/jpeg,image/png,image/jpg">
                                    <small class="text-muted">Klik "Ubah Foto" jika ingin mengganti foto</small>
                                </div>
                                
                                {{-- Photo Preview --}}
                                <div id="photo-preview-modal" class="mt-2" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <img id="preview-image-modal" src="" class="rounded" 
                                             style="max-width: 100px; max-height: 100px; object-fit: cover; border: 2px solid #ddd;">
                                        <button type="button" class="btn btn-sm btn-danger ms-2" id="remove-photo-modal">
                                            <i class="icofont-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="col-md-12 form-group mb-3">
                            <label class="form-label">{{__('monitoring.full_name')}} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input placeholder="Example : John Doe" type="text" class="form-control"
                                    id="name" name="name" required>
                            </div>
                            <small class="text-danger d-none" id="error-name">Nama wajib diisi</small>
                        </div>
                        <div class="col-md-12 form-group mb-3">
                            <label class="form-label">{{__('monitoring.age')}} ({{__('monitoring.month')}})</label>
                            <div class="input-group">
                                <input placeholder="Example : 12" type="number" class="form-control" min="0"
                                    max="60" id="age" name="age" required>
                            </div>
                            <small class="text-danger d-none" id="error-age">Usia harus antara 0-60 bulan</small>
                        </div>
                        <div class="mb-0 col-md-12 form-group">
                            <label class="form-label">{{__('monitoring.gender')}}</label>
                            <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="gender" id="L" autocomplete="off"
                                    checked value="L">
                                <label class="btn btn-outline-secondary shadow-none" for="L"><img
                                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAABXElEQVR4nO2US0rEQBCGs9KVogtFHU26Q0TwNI44XsLoGeYGEpyuZsATeBAdFz7QhY5XUFCrEl1GKtOGGJI4CSO4mIIioR//V+n6O5Y1jUmHVNSVQHFV/m9APrwAlwTg3Z8AvKy4ooeJArycuNMLVyYG8ArEeXxsgBfEs0LRoQQaSKDQ5IDHNnXUKhIfG7Cuo5ZUeFvqEoWf/BQK712g5ezeXwFceSrO1Snc2Tp9nuN0NLYF0PAb4vY/7EILK+qWAgTQkRF/dI5fF/LzNrwtphBNfuVRFAIUXSabe7hXtkYCdUwRF/UBQMSbs43Lx8ZJtDbqAaHVFGD3w9VKEwDFAui9NsDYkp3QKS1C0745ovPaAON9rm7IDc3PJ01W9GSKOKgNSGwKeJM6SWPbC17mOV2Fu6mDAK+2z+IZq0mMzthAoPCiXfOaRuI/vkSTz1ZMfxX8rslvXPk0rJL4AjgZbZ9odEfVAAAAAElFTkSuQmCC"
                                        alt="male"> {{__('monitoring.male')}}</label>

                                <input type="radio" class="btn-check" name="gender" id="P" autocomplete="off"
                                    value="P">
                                <label class="btn btn-outline-secondary shadow-none" for="P"><img
                                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAABT0lEQVR4nO2UPUsDQRCGD/wAJaJEbyYhKQQ7WxuxObIzMQixDAgKFtqIpSn8c9qJxliIiJFgI4jYKtoJr9zdkmjiIbcqWmRgimPfnWc+ds7z/trgLYyAdBukh2C5j5zkAGy24AXD3wtOhkHaACs+dZKTUOMWfL42CpJmHEzaYLOGmUoeWVNATjZBehtD9DTUpgdweddmeoficrbv3A9yYH2INL7uOADk2LZhPVFD5Q2bxJEDQJ+iy1lTSNRMBlO2hY/pASQvcflBJlHjBxlb5bML4CzOrryaqGGpWkAzPcDXun0ll5hemug7Ly6OgeXcDrmeHpCvjoPkyg7xBjmzEi4WvNoQ2JQ6T5ikFWpTAyIIV2ZBet1dLnmNvfPdhl+acwr+oRLWfbBedDdYGyDdc848EcYx4EeDvrcBoM8Sf9M97v1bQK8Nhvyl/XqLXOwNmDgZu51kGZIAAAAASUVORK5CYII="
                                        alt="female"> {{__('monitoring.female')}}</label>
                            </div>
                        </div>
                        <div class="col-md-12 form-group mb-3">
                            <label class="form-label">{{__('monitoring.height')}} (cm)</label>
                            <div class="input-group">
                                <input placeholder="{{__('monitoring.example')}} : 68" type="number" class="form-control" min="40"
                                    max="130" step="0.1" id="height" name="height" required>
                            </div>
                            <small class="text-danger d-none" id="error-height">Tinggi badan harus antara 40-130 cm</small>
                        </div>
                        <div class="col-md-12 form-group mb-3">
                            <label class="form-label">{{__('monitoring.weight')}} (kg)</label>
                            <div class="input-group">
                                <input placeholder="{{__('monitoring.example')}} : 12" type="number" class="form-control" min="2"
                                    max="50" step="0.1" id="weight" name="weight" required>
                            </div>
                            <small class="text-danger d-none" id="error-weight">Berat badan harus antara 2-50 kg</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer p-0 border-0">
                <div class="col-6 m-0 p-0">
                    <button type="button" class="btn border-top btn-lg w-100" data-bs-dismiss="modal">{{__('monitoring.close')}}</button>
                </div>
                <div class="col-6 m-0 p-0">
                    <button type="button" class="btn btn-success btn-lg w-100" id="btnSubmit">{{__('monitoring.save')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    #modalForm .form-control.is-invalid {
        border-color: #dc3545;
        border-width: 2px;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    #modalForm .form-control.is-valid {
        border-color: #198754;
        border-width: 2px;
    }
    
    #modal-validation-alert {
        animation: shake 0.5s;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
</style>
@endpush

@push('js')
    <script>
        // Copy Child ID for modalForm
        function copyChildIdForm() {
            const childId = $('#user_id_display').val();
            if (!childId || childId === 'Akan dibuat otomatis') {
                $.notify("ID belum tersedia. Simpan data terlebih dahulu.", "info");
                return;
            }
            
            navigator.clipboard.writeText(childId).then(function() {
                $.notify("ID berhasil dicopy: " + childId, "success");
                $('#btn-copy-id-form').html('<i class="icofont-check"></i> Copied!');
                setTimeout(function() {
                    $('#btn-copy-id-form').html('<i class="icofont-copy"></i> Copy');
                }, 2000);
            }).catch(function(err) {
                const tempInput = document.createElement('input');
                tempInput.value = childId;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                $.notify("ID berhasil dicopy: " + childId, "success");
            });
        }
        
        $(document).ready(function() {
            // Load existing child data when modalForm is opened
            $('#modalForm').on('shown.bs.modal', function() {
                // Check if user already has children data
                $.ajax({
                    url: "{{ locale_route('growth-monitoring.get-user-data') }}",
                    type: "GET",
                    success: function(response) {
                        // Tampilkan ID dari backend (HANYA UNTUK DISPLAY, TIDAK DIKIRIM KE BACKEND)
                        $('#user_id_display').val(response.child_id);
                        $('#user-id-display').show();
                        
                        if (response.has_data) {
                            // User sudah pernah input data - tampilkan foto
                            if (response.photo) {
                                $('#photo-upload-first').hide();
                                $('#existing-photo').attr('src', response.photo_url);
                                $('#photo-edit-section').show();
                            } else {
                                $('#photo-upload-first').show();
                                $('#photo-edit-section').hide();
                            }
                        } else {
                            // User belum pernah input data
                            $('#photo-upload-first').show();
                            $('#photo-edit-section').hide();
                        }
                    },
                    error: function() {
                        // Fallback
                        $('#user_id_display').val('Akan dibuat otomatis');
                        $('#user-id-display').show();
                        $('#photo-upload-first').show();
                        $('#photo-edit-section').hide();
                    }
                });
            });
            
            // Button change photo
            $('#btn-change-photo').on('click', function() {
                $('#photo-edit').click();
            });
            
            // Photo Preview for first upload
            $('#photo').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        alert('⚠️ Ukuran file maksimal 2MB');
                        $(this).val('');
                        $('#photo-preview-modal').hide();
                        return;
                    }
                    
                    if (!file.type.match('image/(jpeg|png|jpg)')) {
                        alert('⚠️ Format file harus JPG atau PNG');
                        $(this).val('');
                        $('#photo-preview-modal').hide();
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview-image-modal').attr('src', e.target.result);
                        $('#photo-preview-modal').fadeIn();
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Photo Preview for edit
            $('#photo-edit').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        alert('⚠️ Ukuran file maksimal 2MB');
                        $(this).val('');
                        return;
                    }
                    
                    if (!file.type.match('image/(jpeg|png|jpg)')) {
                        alert('⚠️ Format file harus JPG atau PNG');
                        $(this).val('');
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#existing-photo').attr('src', e.target.result);
                        $.notify("Foto akan diupdate", "info");
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Remove photo
            $('#remove-photo-modal').on('click', function() {
                $('#photo').val('');
                $('#photo-preview-modal').fadeOut();
            });
            
            // Show alert in modal
            function showModalAlert(messages) {
                const alertBox = $('#modal-validation-alert');
                const messageDiv = $('#modal-error-messages');
                
                if (Array.isArray(messages)) {
                    let html = '<ul class="mb-0">';
                    messages.forEach(msg => {
                        html += '<li>' + msg + '</li>';
                    });
                    html += '</ul>';
                    messageDiv.html(html);
                } else {
                    messageDiv.html('<p class="mb-0">' + messages + '</p>');
                }
                
                alertBox.removeClass('d-none');
                
                // Auto hide after 5 seconds
                setTimeout(() => {
                    alertBox.addClass('d-none');
                }, 5000);
            }
            
            // Real-time validation
            function validateField(fieldId, errorId, condition, errorMessage) {
                const field = $('#' + fieldId);
                const error = $('#' + errorId);
                
                field.on('input blur', function() {
                    const isValid = condition($(this).val());
                    
                    if (isValid) {
                        field.removeClass('is-invalid').addClass('is-valid');
                        error.addClass('d-none');
                    } else {
                        field.removeClass('is-valid').addClass('is-invalid');
                        error.removeClass('d-none').text(errorMessage);
                        
                        // Show alert on blur if invalid
                        if (!$(this).is(':focus')) {
                            showModalAlert(errorMessage);
                        }
                    }
                });
            }

            // Validate name
            validateField('name', 'error-name', 
                (val) => val && val.trim().length >= 2,
                'Nama minimal 2 karakter'
            );

            // Validate age
            validateField('age', 'error-age',
                (val) => val >= 0 && val <= 60,
                'Usia harus antara 0-60 bulan'
            );

            // Validate height
            validateField('height', 'error-height',
                (val) => val >= 40 && val <= 130,
                'Tinggi badan harus antara 40-130 cm'
            );

            // Validate weight
            validateField('weight', 'error-weight',
                (val) => val >= 2 && val <= 50,
                'Berat badan harus antara 2-50 kg'
            );

            $('#btnSubmit').on('click', function(e) {
                e.preventDefault();
                
                // Validate all fields before submit
                let isValid = true;
                let firstError = null;

                // Check name
                const name = $('#name').val();
                if (!name || name.trim().length < 2) {
                    $('#name').addClass('is-invalid');
                    $('#error-name').removeClass('d-none');
                    isValid = false;
                    if (!firstError) firstError = $('#name');
                }

                // Check age
                const age = $('#age').val();
                if (!age || age < 0 || age > 60) {
                    $('#age').addClass('is-invalid');
                    $('#error-age').removeClass('d-none').text('Usia harus antara 0-60 bulan');
                    isValid = false;
                    if (!firstError) firstError = $('#age');
                }

                // Check height
                const height = $('#height').val();
                if (!height || height < 40 || height > 130) {
                    $('#height').addClass('is-invalid');
                    $('#error-height').removeClass('d-none');
                    isValid = false;
                    if (!firstError) firstError = $('#height');
                }

                // Check weight
                const weight = $('#weight').val();
                if (!weight || weight < 2 || weight > 50) {
                    $('#weight').addClass('is-invalid');
                    $('#error-weight').removeClass('d-none');
                    isValid = false;
                    if (!firstError) firstError = $('#weight');
                }

                if (!isValid) {
                    // Collect all error messages
                    let errorMessages = [];
                    if (!name || name.trim().length < 2) errorMessages.push('Nama minimal 2 karakter');
                    if (!age || age < 0 || age > 60) errorMessages.push('Usia harus 0-60 bulan');
                    if (!height || height < 40 || height > 130) errorMessages.push('Tinggi harus 40-130 cm');
                    if (!weight || weight < 2 || weight > 50) errorMessages.push('Berat harus 2-50 kg');
                    
                    // Show alert in modal
                    showModalAlert(errorMessages);
                    
                    // Focus on first error field
                    if (firstError) {
                        firstError.focus();
                    }
                    
                    return false;
                }

                var url = "{{ locale_route('growth-monitoring.store') }}";
                
                // Use FormData for file upload
                var formData = new FormData($('#formAdd')[0]);

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    beforeSend: function() {
                        $('#btnSubmit').attr('disabled', true);
                        $('#btnSubmit').html(
                            '<i class="icofont-spinner-alt-2 icofont-spin"></i>');
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            // Reset form and validation
                            $('#formAdd')[0].reset();
                            $('.is-invalid').removeClass('is-invalid');
                            $('.is-valid').removeClass('is-valid');
                            $('.text-danger').addClass('d-none');
                            
                            $('#modalForm').modal('hide');
                            
                            // Tampilkan notifikasi dengan ID
                            if (response.child_id) {
                                $.notify(response.message + " | ID Anda: " + response.child_id, "success");
                            } else {
                                $.notify(response.message, "success");
                            }
                            
                            setTimeout(function() {
                                location.replace(response.redirect);
                            }, 1500);
                        } else {
                            $.notify(response.message, "error");
                        }
                    },
                    complete: function() {
                        $('#btnSubmit').attr('disabled', false);
                        $('#btnSubmit').html('{{__('monitoring.save')}}');
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr);
                        
                        if (xhr.status === 422) {
                            // Show validation errors on respective fields
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                let errors = xhr.responseJSON.errors;
                                
                                for (let field in errors) {
                                    let fieldElement = $('#' + field);
                                    let errorElement = $('#error-' + field);
                                    
                                    if (fieldElement.length && errorElement.length) {
                                        fieldElement.addClass('is-invalid');
                                        errorElement.removeClass('d-none').text(errors[field][0]);
                                    }
                                }
                                
                                $.notify("Mohon periksa kembali data yang diisi", "error");
                            }
                        } else if (xhr.status === 500) {
                            let errorMsg = xhr.responseJSON?.message || "Terjadi kesalahan server. Silakan coba lagi.";
                            $.notify(errorMsg, "error");
                        } else {
                            $.notify("Terjadi kesalahan. Silakan coba lagi.", "error");
                        }
                    }
                });
            });
        });
    </script>
@endpush
