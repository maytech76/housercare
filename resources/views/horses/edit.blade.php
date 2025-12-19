@extends('admin.layouts.master')


@section('content')

   
<secttion>

 {{--    <div class="container"> --}}
        <div class="row">

            <div class="col-lg-12 col-md-12 m-4">
                <form action="{{route('horses.update', $horse->id)}} " method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="user_id" value="1">
                
                    <div class="card">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                horse registration
                            </div>
                            <p class="mg-b-20">assisted form for the detailed edit of each horse</p>
                            <div id="wizard1">

                                <h3>Page one</h3>
                                    <section>
                                        <h2 class="d-none">Personal Information</h2>

                                        <div class="row align-items-center">
                                            <!-- Columna de la imagen (6 columnas en md+, 12 en sm) -->
                                            <div class="col-md-2 col-12 mb-3 mb-md-0">

                                                <div class="form-group">
                                                    <label class="form-label">Photo Horse</label>
                                                    <div class="main-img-user avatar-xxl text-center text-md-start shadow-xl">
                                                        <img  class="rounded-circle" src="{{ $horse->photo ? asset('storage/' . $horse->photo) : asset('storage/horses/horse.png') }}">
                                                    </div>
                                                </div>

                                            </div>
                                            
                                            <!-- Columna del input file (6 columnas en md+, 12 en sm) -->
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label class="form-label">Upload Image</label>
                                                    <input type="file" name="photo" id="photo" class="form-control border-secondary">
                                                    <div class="invalid-feedback">Este campo es requerido</div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" id="name" class="form-control required border-secondary" value="{{$horse->name}}" placeholder="Add Name">
                                                    <div class="invalid-feedback">Este campo es requerido</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">

                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Breed</label>
                                                    <input type="text" name="breed" id="breed" class="form-control required border-secondary" value="{{$horse->breed}}" placeholder="Email Address">
                                                    <div class="invalid-feedback">Este campo es requerido</div>
                                                </div>
                                            </div>

                                            <div class="col-md-2 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Age</label>
                                                    <select name="age" id="age" class="form-control required border border-secondary">
                                                        @for($i = 1; $i <= 30; $i++)
                                                        <option value="{{ $i }}" {{ $horse->age == $i ? 'selected' : '' }}>
                                                            {{ $i }} @if($i == 1) year @else years @endif
                                                        </option>
                                                        @endfor
                                                    </select>
                                                    <div class="invalid-feedback">Este campo es requerido</div>
                                                </div>
                                            </div>

                                            <div class="col-md-2 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Sex</label>
                                                
                                                    <select name="sex" id="sex" class="form-control required border-secondary">
                                                        <option value="">Select Sex</option>
                                                            @foreach(['male' => 'MALE', 'female' => 'FEMALE'] as $value => $label)
                                                                <option value="{{ $value }}" {{ $horse->sex == $value ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                    </select>

                                                    <div class="invalid-feedback">Este campo es requerido</div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">color</label>
                                                    <input type="text" name="color"  id="color" class="form-control required border-secondary" value="{{$horse->color}}" placeholder="Add color">
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
                                                    <input type="text" name="height" id="height" class="form-control required border-secondary" value="{{$horse->height}} " placeholder="Add Name">
                                                    <div class="invalid-feedback">This field is required</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Weight</label>
                                                    <input type="text" name="weight" id="weight" class="form-control required border-secondary" value="{{$horse->weight}} " placeholder="Add Name">
                                                    <div class="invalid-feedback">This field is required</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Pathology</label>
                                                    <input type="text" name="pathology" id="pathology" class="form-control required border-secondary" value="{{$horse->pathology}}">
                                                    <div class="invalid-feedback">This field is required</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">

                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Vaccination</label>
                                                    <input type="date" name="vaccination" id="vaccination" class="form-control required border-secondary" value="{{$horse->vaccination}}">
                                                    <div class="invalid-feedback">This field is required</div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12">
                                                <div class="control-group form-group">
                                                    <label class="form-label">Last exam</label>
                                                    <input type="date" name="last_exam" id="last_exam" class="form-control required border-secondary" value="{{$horse->last_exam}}">
                                                    <div class="invalid-feedback">This field is required</div>
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
                                                        <textarea name="observation" id="observation" style="min-width: 100%" rows="2" class="form-control required border border-secondary">{{trim($horse->observation)}}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">

                                            <div class="control-group form-group">
                                                <label class="form-label">Condition</label>
                                                
                                                    <select name="condition" id="condition" class="form-control required border border-secondary">
                                                        @foreach(['FINE', 'SICK','INJURED','HORSESHOE', 'MOUNT', 'DOCTOR'] as $option)
                                                            <option value="{{ $option }}" {{ $horse->condition == $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                <div class="invalid-feedback">This field is required</div>
                                            </div>

                                        </div>

                                        <div class="row align-items-center">
                                            <div class="col-md-6 col-sm-12">
                                                <label class="form-label">Property</label>
                                                <select name="user_id" id="user_id" class="form-control required border border-secondary">
                                                    @foreach ($users as $user)   
                                                    <option value="{{ $user->id }}" {{ $horse->user_id == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                    @endforeach
                                                </select>       
                                            </div>

                                            <div class="col-md-6 col-sm-12">
                                                <label class="form-label">Location</label>
                                                <select name="stable_id" id="stable_id" class="form-control required border border-secondary">
                                                    @foreach ($stables as $stable )   
                                                    <option value="{{$stable->id}} "{{ $horse->stable_id == $stable->id ? 'selected' : '' }}>{{$stable->name}}</option>
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
                onFinished: function() {
                    $('form').submit();
                },
                onStepChanging: function(event, currentIndex, newIndex) {
                    if (currentIndex < newIndex) {
                        let isValid = true;
                        const stepFields = [
                            ['name', 'breed', 'age', 'sex', 'color'], // Page 1
                            ['height', 'weight'],                      // Page 2
                            ['condition', 'user_id', 'stable_id']      // Page 3
                        ];

                        stepFields[currentIndex].forEach(field => {
                            const $field = $(`#${field}`);
                            if (!$field.val()) {
                                $field.addClass('is-invalid');
                                isValid = false;
                            } else {
                                $field.removeClass('is-invalid');
                            }
                        });

                        return isValid;
                    }
                    return true;
                }
            });

            // Validación de campos numéricos en tiempo real
            $('#age, #height, #weight').on('input', function() {
                const $this = $(this);
                $this.toggleClass('is-invalid', isNaN($this.val()));
            });

            // Previsualización de imagen
            $('#photo').change(function(e) {
                const file = e.target.files[0];
                if (file && file.type.match('image.*')) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        $('.main-img-user img').attr('src', event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    
    <script src="{{asset('admin/plugins/jquery-steps/jquery.steps.min.js')}}"></script>
@endpush