<div>
    <div class="main-container container-fluid mt-4">
                <div class="row row-sm">
                    <div class="col-lg-4 col-xl-4 col-md-12 col-sm-12 mt-2">

                        <div class="card  box-shadow-0">
                            <div class="card-header">
                                <h5 class="card-title mb-1" style="font-size: 12px">Opciones</h5>
                                <p class="mb-2 text-sm" style="color:#7c7b7b !important;">Selecionar la categoria y Almacen donde realizara el ajuste de inventario</p>
                            </div>
                             {{-- Categoria-Almacen-Botones --}}
                            <div class="card-body pt-0">
                                
                                    {{-- Seleccionar Categoria --}}
                                    <div class="form-group">
                                        <select wire:model.live="category_id" id="category" class="form-control">
                                            <option value="0">Seleciona Categoria</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{$cat->id}}">{{$cat->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                

                                    {{-- Selecionamos el almacen --}}
                                    <div class="form-group">
                                        <select wire:model="selectedWarehouse" name="warehouse_id" id="warehouse" class="form-control">
                                            <option value="">Seleciona Almacen</option>
                                            @foreach ($warehouses as $ware )
                                               <option value="{{$ware->id}} ">{{$ware->name}} </option>                
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    {{-- Botones --}}
                                    <div class="form-group mb-0">
                                        <div class="row">
                                            <div class="col-6 pr-1">
                                                <button type="submit" class="btn btn-outline-warning btn-block">Editar</button>
                                            </div>
                                            <div class="col-6 pl-1">
                                                <button type="submit" class="btn btn-outline-success btn-block">Aplicar</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                               
                            </div>
                        </div>

                    </div>

                    
                    <div class="col-lg-8 col-xl-8 col-md-12 col-sm-12 mt-2">
                        <div class="card  box-shadow-0 ">

                             
                            <div class="card-header">
                               <div class="row">

                               <div class="d-flex justify-content-between items-center">
                                    <h5 class="card-title mb-1" style="font-size: 12px">Selecionar Productos</h5>
                                    <h2 class="card-title mb-1" style="color: rgb(245, 36, 36)">AJUSTE DE INVENTARIO</h2>
                               </div>


                               </div>
                                <p class="mb-2 text-sm" style="color:#7c7b7b !important;">Listado de productos registrados en el sistema y pertenecen a la categoria Selecionada</p>
                            </div>

                           
                            <div class="card-body pt-0">
                                    <div class="">
   
                                        {{-- Busqueda de producto segun categoria --}}
                                        <div class="mb-3" id="buscar_productos">
                                            <label for="searchProduct" class="form-label fw-bold text-lg text-dark">Buscar Productos</label>
                                            <input id="searchProduct" wire:model.live="searchProduct" type="text" 
                                                class="form-control border-secondary bg-light text-dark" 
                                                placeholder="Ingresa el Producto a Buscar">
                                        </div>

                                        {{-- Resultado de Busqueda de Productos --}}
                                        <div class="form-group" id="list_product">
                                            @if(!empty($searchProduct) && !empty($products))
                                                <div class="form-group" id="list_product">
                                                    <ul style="max-height: 100px; overflow-y: auto;">
                                                        @foreach ($products as $prod)
                                                            <li class="mb-1 d-flex justify-content-between align-items-center w-100">
                                                                <span style="font-size:12px;">{{$prod->id}} - {{$prod->name}} - {{number_format($prod->price, 2)}}</span>
                                                                <a wire:click="addProduct({{$prod->id}})" style="color: green; margin-left: 50px; cursor: pointer">Agregar</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div> 

                                    </div>  
                                </div>
                            </div>
                        </div>
                </div>

                <!-- Tabla de Productos para el Ajuste -->
                <div class="row">

                    <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
                        <!--div-->                          
                                
                        <div class="card">
                            {{-- <div class="card-header">
                                <h3 class="card-title">Listado de Productos para Ajustar</h3>
                            </div> --}}

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="basic-datatable">

                                        {{-- Encabezados --}}
                                        <thead>
                                            <tr>
                                                <th class="wd-15p border-bottom-0">ID</th>
                                                <th class="wd-15p border-bottom-0">Nombre</th>
                                                <th class="wd-15p border-bottom-0">Almacen</th>
                                                <th class="wd-20p border-bottom-0 text-center">Stock Actual</th>
                                                <th class="wd-15p border-bottom-0 text-center">Contado</th>
                                                <th class="wd-10p border-bottom-0">Costo</th>
                                                <th class="wd-25p border-bottom-0 text-center">Opciones</th>
                                            </tr>
                                        </thead>

                                        {{-- Productos --}}
                                        <tbody>

                                          @foreach ($inventoryItems as $index => $product)
                                          
                                            <tr>
                                                @foreach ($product->warehouses as $ware)
                                                    
                                                <td>{{$product->id}} </td>
                                                <td>{{$product->name}}</td>
                                                <td>{{$ware->name}}</td>

                                                <td class="text-center">
                                                    {{ $ware->pivot->stock ?? 'N/E' }} 
                                                </td>

                                                <td class="text-center">

                                                    <div class="row">
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <button class="btn btn-danger"> 
                                                                -    
                                                            </button>
                                                            <div class="mx-2">0</div>
                                                            <button class="btn btn-success"> 
                                                                +    
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    {{$product->price}}
                                                </td>

                                                {{-- Botones --}}
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                                        <button class="btn btn-info"> 
                                                            Detalles 
                                                        </button>
                                                        <button class="btn btn-danger"> 
                                                            Borrar 
                                                        </button>
                                                    </div>
                                                </td> 

                                                @endforeach     
                                            </tr>
                                          @endforeach
     
                                            
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                        </div>          
                    </div>   
                </div>
        	<!-- Final Tabla de Productos para el Ajuste closed -->
    </div>
</div>
