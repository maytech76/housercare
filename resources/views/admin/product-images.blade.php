
<div>
    
    <div class="container m-5">
        <h2>Crear Producto</h2>
        <form action="{{ route('products.store') }}" method="POST" class="m-5 col-10">
            @csrf
            <div class="form-group">
                <label for="category_id">Categoría</label>
                <select name="category_id" id="category_id" class="form-control">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
    
            <div class="form-group">
                <label for="name">Nombre del Producto</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
    
            <div class="form-group">
                <label for="description">Descripcion del Producto</label>
                <input type="text" name="description" id="description" class="form-control" required>
            </div>
    
            <div class="form-group">
                <label for="price">Precio</label>
                <input type="number" name="price" id="price" class="form-control" required>
            </div>
    
            <div class="form-group">
                <label for="status">Estado</label>
                <select name="status" id="status" class="form-control">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
    
            <button type="submit" class="btn btn-primary">Guardar Producto</button>
    
           <hr class="my-4" style="border-top: 2px solid #ccc; border-radius: 5px;">
    
        </form>
    
        <h3>Subir Imágenes</h3>
        <form action="{{ route('products.upload') }}" method="POST" class="dropzone" id="image-upload" enctype="multipart/form-data">
            @csrf
           
            <input type="hidden" name="prod_id" value="{{ session('prod_id') }}">
        </form>
    </div>
    
    
</div>
