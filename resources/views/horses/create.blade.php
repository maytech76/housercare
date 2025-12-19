@extends('admin.layouts.master')


@section('content')

   
<secttion>

 {{--    <div class="container"> --}}
        <div class="row">

            <div class="col-lg-12 col-md-12 m-4">
                <form action="{{route('horses.store')}} " method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="user_id" value="1">
                
                    <div class="card">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                horse registration
                            </div>
                            <p class="mg-b-20">assisted form for the detailed registration of each horse</p>
                            <div id="wizard1">

                                <h3>Page one</h3>
                                    <section>
                                        <h2 class="d-none">Personal Information</h2>

                                        <div class="row align-items-center">
                                            <!-- Columna de la imagen (6 columnas en md+, 12 en sm) -->
                                            <div class="col-md-2 col-12 mb-3 mb-md-0">

                                                <div class="form-group">
                                                    <label class="form-label">Photo Horse</label>
                                                    <div class="main-img-user avatar-xxl text-center text-md-start">
                                                        <img alt="photo" class="rounded-circle" src="{{asset('admin/img/users/8.jpg')}}">
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            
                                            <!-- Columna del input file (6 columnas en md+, 12 en sm) -->
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label class="form-label">Upload Image</label>
                                                    <input type="file" name="photo" id="photo" class="form-control border-secondary">
                                                    <div class="invalid-feedback">This field is required</div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" id="name" class="form-control required border-secondary" placeholder="Add Name">
                                                    <div class="invalid-feedback">This field is required</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">

                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Breed</label>
                                                    <input type="text" name="breed" id="breed" class="form-control required border-secondary" placeholder="Email Address">
                                                    <div class="invalid-feedback">Este campo es requerido</div>
                                                </div>
                                            </div>

                                            <div class="col-md-2 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Age</label>
                                                    <select name="age" id="age" class="form-control required border border-secondary">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                    </select>
                                                    <div class="invalid-feedback">Este campo es requerido</div>
                                                </div>
                                            </div>

                                            <div class="col-md-2 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Sex</label>
                                                
                                                    <select name="sex" id="sex" class="form-control required border-secondary">
                                                        <option value="male">MALE</option>
                                                        <option value="female">FEMALE</option>
                                                    </select>

                                                    <div class="invalid-feedback">Este campo es requerido</div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">color</label>
                                                    <input type="text" name="color"  id="color" class="form-control required border-secondary" placeholder="Add color">
                                                    <div class="invalid-feedback">Este campo es requerido</div>
                                                </div>
                                            </div>

                                        </div>
                                    </section>

                               
                                <h3>Page Two</h3>
                                    <section>
                                    
                                        <div class="row align-items-center">
                                            
                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Height</label>
                                                    <input type="text" name="height" id="height" class="form-control required border-secondary" placeholder="Add Name">
                                                    <div class="invalid-feedback">Este campo es requerido</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">weight</label>
                                                    <input type="text" name="weight" id="weight" class="form-control required border-secondary" placeholder="Add Name">
                                                    <div class="invalid-feedback">Este campo es requerido</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Pathology</label>
                                                    <input type="text" name="pathology" id="pathology" class="form-control required border-secondary" placeholder="Add Name">
                                                    <div class="invalid-feedback">Este campo es requerido</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">

                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Vaccination</label>
                                                    <input type="date" name="vaccination" id="vaccination" class="form-control required border-secondary" placeholder="Add Name">
                                                    <div class="invalid-feedback">Este campo es requerido</div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Last exam</label>
                                                    <input type="date" name="Last_exam" id="Last_exam" class="form-control required border-secondary" placeholder="Add Name">
                                                    <div class="invalid-feedback">Este campo es requerido</div>
                                                </div>
                                            </div>

                                        </div>
                                    
                                    </section>
                                    

                                <h3>Page three</h3>
                                    <section>

                                        <div class="row align-items-center">
                                            <div class="row align-items-center">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Observation</label>
                                                        <textarea name="observation" id="observation" style="min-width: 100%" rows="2" class="form-control required border border-secondary"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">

                                            <div class="control-group form-group">
                                                <label class="form-label">Condition</label>
                                                {{-- <input type="text" name="condition" id="condition" class="form-control required border-secondary" placeholder="Add Name"> --}}
                                                <select name="condition" id="condition" class="form-control required border border-secondary">

                                                    <option value="FINE">FINE</option>
                                                    <option value="SICK">SICK</option>
                                                    <option value="HORSESHOE">HORSESHOE</option>
                                                    <option value="MOUNT">MOUNT</option>
                                                    <option value="DOCTOR">DOCTOR</option>
                                                   
                                                </select>
                                                <div class="invalid-feedback">This field is required</div>
                                            </div>

                                        </div>

                                        <div class="row align-items-center">
                                            <div class="col-md-6 col-sm-12">
                                                <label class="form-label">Property</label>
                                                <select name="user_id" id="user_id" class="form-control required border border-secondary">
                                                    @foreach ($users as $user )   
                                                    <option value="{{$user->id}} ">{{$user->name}} </option>
                                                    @endforeach
                                                </select>    
                                            </div>

                                            <div class="col-md-6 col-sm-12">
                                                <label class="form-label">Location</label>
                                                <select name="stable_id" id="stable_id" class="form-control required border border-secondary">
                                                    @foreach ($stables as $stable )   
                                                    <option value="{{$stable->id}} ">{{$stable->name}} </option>
                                                    @endforeach
                                                </select>    
                                            </div>
                                        </div>
                                        
                                    </section>

                            </div>
                        </div>
                    </div>

                </form>
            </div> 


        </div>
   {{--  </div> --}}


</secttion>
                   

@endsection

@push('scripts')
    {{-- form wizard add horses --}}
    <script>
        $(function() {
            'use strict';
            
            $('#wizard1').steps({
                headerTag: 'h3',
                bodyTag: 'section',
                autoFocus: true,
                titleTemplate: '<span class="number">#index#<\/span> <span class="title">#title#<\/span>',
                onFinished: function(event, currentIndex) {
                    // Envía el formulario cuando se completa el wizard
                    $('form').submit();
                },
                onStepChanging: function(event, currentIndex, newIndex) {
                    // Validación básica de campos requeridos
                    if (currentIndex < newIndex) {
                        // Validación de la página 1
                        if (currentIndex === 0) {
                            const requiredFields = ['name', 'breed', 'age', 'sex', 'color'];
                            let isValid = true;
                            
                            requiredFields.forEach(field => {
                                const value = $(`#${field}`).val();
                                if (!value) {
                                    $(`#${field}`).addClass('is-invalid');
                                    isValid = false;
                                } else {
                                    $(`#${field}`).removeClass('is-invalid');
                                }
                            });
                            
                            return isValid;
                        }
                        
                        // Validación de la página 2
                        if (currentIndex === 1) {
                            const requiredFields = ['height', 'weight'];
                            let isValid = true;
                            
                            requiredFields.forEach(field => {
                                const value = $(`#${field}`).val();
                                if (!value) {
                                    $(`#${field}`).addClass('is-invalid');
                                    isValid = false;
                                } else {
                                    $(`#${field}`).removeClass('is-invalid');
                                }
                            });
                            
                            return isValid;
                        }
                    }
                    return true;
                }
            });

            // Validación de campos numéricos
            $('#age, #height, #weight').on('input', function() {
                const value = $(this).val();
                if (isNaN(value)) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Previsualización de la imagen seleccionada
            $('#photo').change(function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $('.main-img-user img').attr('src', event.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    
    <script src="{{asset('admin/plugins/jquery-steps/jquery.steps.min.js')}}"></script>
@endpush