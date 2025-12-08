<!DOCTYPE html>
<html>

<head>
    <title>Listado de Productos</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>

<body>
    <h1>Listado de Productos</h1>
    <table>
        <thead>
            <tr>
                <th>Categoría</th>
                <th>Nombre</th>
                <th>descripcion</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Almacen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)

                <tr>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description}}</td>
                    <td>{{ $product->price}}</td>

                    <td class="text-center">
                        @if($selectedWarehouse)
                        {{ $product->warehouses->where('id', $selectedWarehouse)->first()?->pivot->stock ?? 0 }}
                        @else
                            {{ $product->total_stock ?? 0 }}
                        @endif
                    </td>

                    <td>
                        @foreach($product->warehouses as $warehouse)
                            {{ $warehouse->name }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                </tr>

            @endforeach
        </tbody>
    </table>
</body>

</html>
