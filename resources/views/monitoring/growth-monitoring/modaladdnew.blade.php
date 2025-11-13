<div class="modal fade" id="modalAddNew" tabindex="-1" role="dialog" aria-labelledby="modalAddNewLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddNewLabel">{{__('monitoring.add_child_data')}}</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close">
                    <!-- <span aria-hidden="true">&times;</span> -->
                </button>
            </div>
            <div class="modal-body">
                {{-- Alert box inside modal --}}
                <div id="modal-alert-addnew" class="alert alert-danger d-none mb-3" role="alert" style="animation: shake 0.5s;">
                    <strong><i class="icofont-warning"></i> ⚠️ Perhatian!</strong>
                    <div id="modal-error-addnew" class="mt-2"></div>
                </div>
                
                <form action="#" class="" method="POST" id="formAddNew">
                    @csrf
                    <div class="form-row">
                        <div class="mb-3 col-md-12 form-group">
                            <label class="form-label">{{__('monitoring.use_existing_name')}}?</label>
                            <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check choose-check" name="choose" id="choosee-1" autocomplete="off"
                                    value="yes" checked>
                                <label class="btn btn-outline-secondary shadow-none" for="choosee-1"> {{__('monitoring.yes')}}</label>

                                <input type="radio" class="btn-check choose-check" name="choose" id="choosee-2" autocomplete="off"
                                    value="no">
                                <label class="btn btn-outline-secondary shadow-none" for="choosee-2"> {{__('monitoring.no')}}</label>
                            </div>
                        </div>
                        <div class="col-md-12 form-group mb-3" id="inputFullName" style="display: none">
                            <label class="form-label">{{__('monitoring.full_name')}}</label>
                            <div class="input-group">
                                <input placeholder="Example : John Doe" type="text" class="form-control"
                                    id="nameAdd">
                            </div>
                        </div>
                        <div class="col-md-12 form-group mb-3" id="chooseName">
                            <label class="form-label">{{__('monitoring.choose_name')}}</label>
                            <div class="input-group">
                               <select id="chooseAdd" class="form-select form-control">
                                <option value="">{{__('monitoring.choose_name')}}</option>
                                @foreach ($choosename as $value)
                                    <option value="{{$value->name}}|{{$value->gender}}">{{$value->name}}</option>
                                @endforeach
                               </select>
                            </div>
                        </div>
                        <div class="col-md-12 form-group mb-3">
                            <label class="form-label">{{__('monitoring.age_in_months')}}</label>
                            <div class="input-group">
                                <input placeholder="{{__('monitoring.example')}} 12" type="number" class="form-control" min="0"
                                    max="60" id="ageAdd" name="age">
                            </div>
                            <small class="text-danger d-none" id="error-ageAdd">Usia harus antara 0-60 bulan</small>
                            <small class="text-info d-none" id="guide-ageAdd" style="display: block; margin-top: 5px;">
                                <i class="icofont-info-circle"></i> <span id="age-guide-text"></span>
                            </small>
                        </div>
                        <div class="mb-0 col-md-12 form-group" id="formGender" style="display: none">
                            <label class="form-label">{{__('monitoring.gender')}}</label>
                            <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="gender" id="L-1" autocomplete="off"
                                    checked value="L">
                                <label class="btn btn-outline-secondary shadow-none" for="L-1"><img
                                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAABXElEQVR4nO2US0rEQBCGs9KVogtFHU26Q0TwNI44XsLoGeYGEpyuZsATeBAdFz7QhY5XUFCrEl1GKtOGGJI4CSO4mIIioR//V+n6O5Y1jUmHVNSVQHFV/m9APrwAlwTg3Z8AvKy4ooeJArycuNMLVyYG8ArEeXxsgBfEs0LRoQQaSKDQ5IDHNnXUKhIfG7Cuo5ZUeFvqEoWf/BQK712g5ezeXwFceSrO1Snc2Tp9nuN0NLYF0PAb4vY/7EILK+qWAgTQkRF/dI5fF/LzNrwtphBNfuVRFAIUXSabe7hXtkYCdUwRF/UBQMSbs43Lx8ZJtDbqAaHVFGD3w9VKEwDFAui9NsDYkp3QKS1C0745ovPaAON9rm7IDc3PJ01W9GSKOKgNSGwKeJM6SWPbC17mOV2Fu6mDAK+2z+IZq0mMzthAoPCiXfOaRuI/vkSTz1ZMfxX8rslvXPk0rJL4AjgZbZ9odEfVAAAAAElFTkSuQmCC"
                                        alt="male"> {{__('monitoring.male')}}</label>

                                <input type="radio" class="btn-check" name="gender" id="P-1" autocomplete="off"
                                    value="P">
                                <label class="btn btn-outline-secondary shadow-none" for="P-1"><img
                                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAABT0lEQVR4nO2UPUsDQRCGD/wAJaJEbyYhKQQ7WxuxObIzMQixDAgKFtqIpSn8c9qJxliIiJFgI4jYKtoJr9zdkmjiIbcqWmRgimPfnWc+ds7z/trgLYyAdBukh2C5j5zkAGy24AXD3wtOhkHaACs+dZKTUOMWfL42CpJmHEzaYLOGmUoeWVNATjZBehtD9DTUpgdweddmeoficrbv3A9yYH2INL7uOADk2LZhPVFD5Q2bxJEDQJ+iy1lTSNRMBlO2hY/pASQvcflBJlHjBxlb5bML4CzOrryaqGGpWkAzPcDXun0ll5hemug7Ly6OgeXcDrmeHpCvjoPkyg7xBjmzEi4WvNoQ2JQ6T5ikFWpTAyIIV2ZBet1dLnmNvfPdhl+acwr+oRLWfbBedDdYGyDdc848EcYx4EeDvrcBoM8Sf9M97v1bQK8Nhvyl/XqLXOwNmDgZu51kGZIAAAAASUVORK5CYII="
                                        alt="female"> {{__('monitoring.female')}}</label>
                            </div>
                        </div>
                        <div class="col-md-12 form-group mb-3">
                            <label class="form-label">{{__('monitoring.height_cm')}}</label>
                            <div class="input-group">
                                <input placeholder="{{__('monitoring.example')}} 68" type="number" class="form-control" min="40"
                                    max="130" step="0.1" id="heightAdd" name="height">
                            </div>
                            <small class="text-danger d-none" id="error-heightAdd">Tinggi badan harus antara 40-130 cm</small>
                            <small class="text-info" id="guide-heightAdd" style="display: block; margin-top: 5px;">
                                <i class="icofont-info-circle"></i> <span id="height-guide-text">Rentang normal akan muncul setelah usia diisi</span>
                            </small>
                        </div>
                        <div class="col-md-12 form-group mb-3">
                            <label class="form-label">{{__('monitoring.weight_kg')}}</label>
                            <div class="input-group">
                                <input placeholder="{{__('monitoring.example')}} 12" type="number" class="form-control" min="2"
                                    max="50" step="0.1" id="weightAdd" name="weight">
                            </div>
                            <small class="text-danger d-none" id="error-weightAdd">Berat badan harus antara 2-50 kg</small>
                            <small class="text-info" id="guide-weightAdd" style="display: block; margin-top: 5px;">
                                <i class="icofont-info-circle"></i> <span id="weight-guide-text">Rentang normal akan muncul setelah usia diisi</span>
                            </small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer p-0 border-0">
                <div class="col-6 m-0 p-0">
                    <button type="button" class="btn border-top btn-lg w-100" data-bs-dismiss="modal">{{__('monitoring.close')}}</button>
                </div>
                <div class="col-6 m-0 p-0">
                    <button type="button" class="btn btn-success btn-lg w-100" id="btnSubmitAdd">{{__('monitoring.save')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    #modalAddNew .form-control.is-invalid {
        border-color: #dc3545;
        border-width: 2px;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    #modalAddNew .form-control.is-valid {
        border-color: #198754;
        border-width: 2px;
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
        $(document).ready(function() {
            
            // Show alert in modal
            function showModalAlertAddNew(messages) {
                const alertBox = $('#modal-alert-addnew');
                const messageDiv = $('#modal-error-addnew');
                
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
                
                // Auto hide after 8 seconds
                setTimeout(() => {
                    alertBox.addClass('d-none');
                }, 8000);
            }
            
            // Real-time validation
            function validateFieldAddNew(fieldId, min, max, errorMsg) {
                const field = $('#' + fieldId);
                const errorEl = $('#error-' + fieldId);
                
                field.on('input blur', function() {
                    const val = parseFloat($(this).val());
                    
                    if (!$(this).val() || isNaN(val) || val < min || val > max) {
                        field.removeClass('is-valid').addClass('is-invalid');
                        errorEl.removeClass('d-none');
                        
                        // Show alert on blur
                        if (!$(this).is(':focus')) {
                            showModalAlertAddNew(errorMsg);
                        }
                    } else {
                        field.removeClass('is-invalid').addClass('is-valid');
                        errorEl.addClass('d-none');
                    }
                });
            }
            
            // Age-based validation ranges
            const ageRanges = {
                '0-12': {
                    height: { min: 45, max: 80, avg: '50-75 (ideal: 60-70)' },
                    weight: { min: 2.5, max: 12, avg: '3-10 (ideal: 6-9)' },
                    label: '0-12 bulan (Bayi)'
                },
                '13-24': {
                    height: { min: 70, max: 90, avg: '75-87 (ideal: 78-85)' },
                    weight: { min: 8, max: 14, avg: '9-12 (ideal: 10-11)' },
                    label: '13-24 bulan (Batita)'
                },
                '25-36': {
                    height: { min: 85, max: 100, avg: '87-96 (ideal: 90-94)' },
                    weight: { min: 11, max: 16, avg: '12-14 (ideal: 12.5-13.5)' },
                    label: '25-36 bulan (Balita Awal)'
                },
                '37-48': {
                    height: { min: 95, max: 110, avg: '96-103 (ideal: 98-102)' },
                    weight: { min: 13, max: 19, avg: '14-17 (ideal: 15-16)' },
                    label: '37-48 bulan (Balita)'
                },
                '49-60': {
                    height: { min: 100, max: 120, avg: '103-110 (ideal: 105-108)' },
                    weight: { min: 15, max: 22, avg: '16-19 (ideal: 17-18)' },
                    label: '49-60 bulan (Pra-Sekolah)'
                }
            };
            
            // Function to get age range
            function getAgeRange(age) {
                if (age >= 0 && age <= 12) return ageRanges['0-12'];
                if (age >= 13 && age <= 24) return ageRanges['13-24'];
                if (age >= 25 && age <= 36) return ageRanges['25-36'];
                if (age >= 37 && age <= 48) return ageRanges['37-48'];
                if (age >= 49 && age <= 60) return ageRanges['49-60'];
                return null;
            }
            
            // Update guides when age changes
            $('#ageAdd').on('input change', function() {
                const age = parseInt($(this).val());
                const range = getAgeRange(age);
                
                if (range && age >= 0 && age <= 60) {
                    // Update height guide
                    $('#height-guide-text').html(
                        '<strong>' + range.label + ':</strong> Tinggi normal ' + range.height.avg + ' cm'
                    );
                    $('#guide-heightAdd').removeClass('text-muted').addClass('text-info').show();
                    
                    // Update weight guide
                    $('#weight-guide-text').html(
                        '<strong>' + range.label + ':</strong> Berat normal ' + range.weight.avg + ' kg'
                    );
                    $('#guide-weightAdd').removeClass('text-muted').addClass('text-info').show();
                    
                    // Update age guide
                    $('#age-guide-text').html(
                        '<strong>Kategori:</strong> ' + range.label
                    );
                    $('#guide-ageAdd').removeClass('d-none').show();
                    
                    // Update validation ranges dynamically
                    $('#heightAdd').attr('min', range.height.min).attr('max', range.height.max);
                    $('#weightAdd').attr('min', range.weight.min).attr('max', range.weight.max);
                } else {
                    $('#height-guide-text').text('Rentang normal akan muncul setelah usia diisi');
                    $('#weight-guide-text').text('Rentang normal akan muncul setelah usia diisi');
                    $('#guide-ageAdd').addClass('d-none');
                }
            });
            
            // Dynamic validation based on age
            function validateFieldDynamic(fieldId, errorId) {
                const field = $('#' + fieldId);
                const errorEl = $('#' + errorId);
                
                field.on('input blur', function() {
                    const age = parseInt($('#ageAdd').val());
                    const range = getAgeRange(age);
                    const val = parseFloat($(this).val());
                    
                    if (!range || !age) {
                        errorEl.addClass('d-none');
                        field.removeClass('is-invalid is-valid');
                        return;
                    }
                    
                    let min, max, errorMsg;
                    if (fieldId === 'heightAdd') {
                        min = range.height.min;
                        max = range.height.max;
                        errorMsg = `Tinggi untuk ${range.label} harus ${min}-${max} cm`;
                    } else if (fieldId === 'weightAdd') {
                        min = range.weight.min;
                        max = range.weight.max;
                        errorMsg = `Berat untuk ${range.label} harus ${min}-${max} kg`;
                    }
                    
                    if (!$(this).val() || isNaN(val) || val < min || val > max) {
                        field.removeClass('is-valid').addClass('is-invalid');
                        errorEl.removeClass('d-none').text(errorMsg);
                        
                        if (!$(this).is(':focus')) {
                            showModalAlertAddNew(errorMsg);
                        }
                    } else {
                        field.removeClass('is-invalid').addClass('is-valid');
                        errorEl.addClass('d-none');
                    }
                });
            }
            
            // Apply basic age validation
            validateFieldAddNew('ageAdd', 0, 60, 'Usia harus antara 0-60 bulan');
            
            // Apply dynamic validation for height and weight
            validateFieldDynamic('heightAdd', 'error-heightAdd');
            validateFieldDynamic('weightAdd', 'error-weightAdd');

            $(".choose-check").change(function(e) {
                e.preventDefault();
                if (this.value == 'yes') {
                    $("#chooseName").show();
                    $("#inputFullName").hide();
                    $("#nameAdd").val('');
                    $("#formGender").hide();
                } else if (this.value == 'no') {
                    $("#chooseName").hide();
                    $("#chooseAdd").val('');
                    $("#inputFullName").show();
                    $("#formGender").show();
                } else {
                    $("#chooseName").hide();
                    $("#inputFullName").hide();
                    $("#chooseAdd").val('');
                    $("#nameAdd").val('');
                    $("#formGender").hide();
                }
            });

            $('#btnSubmitAdd').on('click', function(e) {
                e.preventDefault();
                
                // Validation
                let age = parseInt($('#ageAdd').val());
                let height = parseFloat($('#heightAdd').val());
                let weight = parseFloat($('#weightAdd').val());
                let errors = [];
                let isValid = true;

                // Validate age first
                if (!age || isNaN(age) || age < 0 || age > 60) {
                    $('#ageAdd').addClass('is-invalid');
                    $('#error-ageAdd').removeClass('d-none');
                    errors.push('Usia harus antara 0-60 bulan');
                    isValid = false;
                } else {
                    // Get age-specific ranges
                    const range = getAgeRange(age);
                    
                    if (range) {
                        // Validate height with age-specific range
                        if (!height || isNaN(height) || height < range.height.min || height > range.height.max) {
                            $('#heightAdd').addClass('is-invalid');
                            $('#error-heightAdd').removeClass('d-none').text(
                                `Tinggi untuk ${range.label} harus ${range.height.min}-${range.height.max} cm`
                            );
                            errors.push(`Tinggi untuk ${range.label} harus ${range.height.min}-${range.height.max} cm`);
                            isValid = false;
                        }

                        // Validate weight with age-specific range
                        if (!weight || isNaN(weight) || weight < range.weight.min || weight > range.weight.max) {
                            $('#weightAdd').addClass('is-invalid');
                            $('#error-weightAdd').removeClass('d-none').text(
                                `Berat untuk ${range.label} harus ${range.weight.min}-${range.weight.max} kg`
                            );
                            errors.push(`Berat untuk ${range.label} harus ${range.weight.min}-${range.weight.max} kg`);
                            isValid = false;
                        }
                    }
                }
                
                if (!isValid) {
                    showModalAlertAddNew(errors);
                    return false;
                }
                var choose = $('input[name="choose"]:checked').val();
                var url = "{{ locale_route('growth-monitoring.store') }}";
                var fd = $('#formAddNew').serializeArray();
                if (choose == "yes") {
                    let text = $("#chooseAdd").val();
                    const ng = text.split("|");
                    fd.push({
                        name: "name", value: ng[0]
                    })
                    fd.push({
                        name: "gender", value: ng[1]
                    })
                } else if (choose == "no") {
                    var nameAdd = $("#nameAdd").val()
                    fd.push({
                        name: "name", value: nameAdd
                    })
                } else if (choose == ''){
                    $(".choose-check").notify(
                        "Choose Use existing name first or Add new Name", {
                            position: "bottom"
                        }
                    );
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: url,
                    data: fd,
                    dataType: "json",
                    beforeSend: function() {
                        $('#btnSubmitAdd').attr('disabled', true);
                        $('#btnSubmitAdd').html(
                            '<i class="icofont-spinner-alt-2 icofont-spin"></i>');
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#modalAddNew').modal('hide');
                            $.notify(response.message, "success");
                            setTimeout(function() {
                                location.replace(response.redirect);
                            }, 1500);
                        } else {
                            $.notify(response.message, "error");
                        }
                    },
                    complete: function() {
                        $('#btnSubmitAdd').attr('disabled', false);
                        $('#btnSubmitAdd').html('Save changes');
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseJSON);
                        
                        if (xhr.status === 422) {
                            // Validation errors
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = [];
                            for (let field in errors) {
                                errorMessages.push(errors[field][0]);
                            }
                            $.notify(errorMessages.join('<br>'), "error");
                        } else if (xhr.status === 500) {
                            let errorMsg = xhr.responseJSON?.message || "Terjadi kesalahan server. Silakan coba lagi.";
                            $.notify(errorMsg, "error");
                            
                            // Show debug info if available
                            if (xhr.responseJSON?.debug) {
                                console.error('Debug Info:', xhr.responseJSON.debug);
                            }
                        } else {
                            $.notify("Terjadi kesalahan. Silakan coba lagi.", "error");
                        }
                    }
                });
            });
        });
    </script>
@endpush
