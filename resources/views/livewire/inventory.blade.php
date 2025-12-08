<div>
     
    <!-- container -->
    {{-- Tabla reporte para toma de inventario --}}
    <div class="row row-sm mt-4 mx-auto">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">

                    <div>
                        <h6 class="card-title mb-1">Opciones de Consulta</h6>
                        <p class="text-muted card-sub-title">Seleccíon de Categorias, Almacen y existencia para consultar productos</p>
                    </div>
                    
                    {{-- Listado de Categorias --}}
                    <div class="mb-4">
                        <p class="mg-b-10">Categorias</p>
                        <select wire:model="selectedCategory" class="form-control SlectBox">
                            <!--placeholder-->
                            <option value="#">Seleciones Categoria</option>

                            @foreach ($categories as $category)

                            <option value="{{$category->id}}">{{$category->name}}</option>
                                
                            @endforeach
                           
                        </select>
                    </div>
                                   
                    {{-- Listado de Almacenes --}}
                    <div class="mb-4">
                        <p class="mg-b-10">Almacén</p>
                        <div class="row align-items-md-center">
                        
                            <!-- Select warehouses-->
                            <div class="col-md-8 col-sm-12">

                                <select wire:model="selectedWarehouse"  class="form-control SlectBox">
                                    <option value="">Seleciona Almacen</option>
                                        @foreach ($warehouses as $ware)
                                        <option value="{{$ware->id}}">{{$ware->name}} </option>
                                        @endforeach                                                        
                                </select>

                            </div>
                        
                            <!-- Checkbox -->
                            <div class="mt-2 mt-md-0 col-md-2 col-sm-10">
                                <label class="form-switch">
                                    <span class="form-switch-description tx-15 me-2">Existencia: </span>
                                    <input type="checkbox" name="form-switch-checkbox1" class="form-switch-input">
                                    <span class="form-switch-indicator form-switch-indicator-md"></span>
                                </label>
                            </div>
                        
                            <!-- Button -->
                            <div class="mt-2 mt-md-0 col-md-2 col-sm-12">
                                <button wire:click="searchProducts" class="btn btn-success-gradient btn-block">Buscar</button>
                            </div>
                        
                        </div>
                    </div>
                    
                    
                </div>
            </div>

            <div class="card">

                {{-- Botones --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Toma de inventario</h3>

                    <!-- Botones de Exportación -->
                    <div>
                        <button wire:click="exportToExcel" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Exportar a Excel
                        </button>

                        
                        <button wire:click="exportToExcel" class="btn btn-success">
                            Exportar Excel
                        </button>
                        
                    </div>
                  
                </div>

                 {{-- Tabla de productos --}}
                <div class="card">
                   
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="file-datatable" class="border-top-0  table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0">Categoria</th>
                                        <th class="border-bottom-0">Nombre</th>
                                        <th class="border-bottom-0">Descripción</th>
                                        <th class="border-bottom-0">Precio</th>
                                        <th class="border-bottom-0 text-center">Stock</th>
                                        <th class="border-bottom-0">Almacen</th>
                                        <th class="border-bottom-0">Contado</th>
                                        
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @foreach ($products as $product)
                                        @if($selectedWarehouse)
                                            @php
                                                $filteredWarehouse = $product->warehouses->where('id', $selectedWarehouse)->first();
                                            @endphp
                                
                                            @if($filteredWarehouse)
                                                <tr>
                                                    <td>{{ $product->category->name }}</td>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ $product->description }}</td>
                                                    <td>{{ $product->price }}</td>
                                                    <td class="text-center">
                                                        {{ $filteredWarehouse->pivot->stock ?? 0 }}
                                                    </td>
                                                    <td>{{ $filteredWarehouse->name }}</td>
                                                    <td><p> _____ </p></td>
                                                </tr>
                                            @endif
                                
                                        @else
                                            @foreach($product->warehouses as $warehouse)
                                                <tr>
                                                    <td>{{ $product->category->name }}</td>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ $product->description }}</td>
                                                    <td>{{ $product->price }}</td>
                                                    <td class="text-center">
                                                        {{ $warehouse->pivot->stock ?? 0 }}
                                                    </td>
                                                    <td>{{ $warehouse->name }}</td>
                                                    <td><p> _____ </p></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                                
                            </table>
                           
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
                
@push('scripts')
    <script src="{{asset('admin/plugins/select2/js/select2.min.js')}}"></script> 
    <script src="{{asset('admin/plugins/sumoselect/jquery.sumoselect.js')}}"></script>
    {{-- <script src="{{asset('admin/js/advanced-form-elements.js')}}"></script> --}}
    {{-- <script src="{{asset('admin/plugins/datatable/pdfmake/pdfmake.min.js')}}"></script> --}}
    {{-- <script src="{{asset('admin/plugins/datatable/pdfmake/vfs_fonts.js')}}"></script> --}}
    
@endpush



</div>
