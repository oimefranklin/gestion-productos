@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestión de Productos</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearProducto">
            + Nuevo Producto
        </button>
    </div>

    @if (session('exito'))
        <div class="alert alert-success">{{ session('exito') }}</div>
    @endif

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria->nombre }}</td>
                    <td>{{ $producto->cantidad }}</td>
                    <td>${{ number_format($producto->precio_unitario, 2) }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#modalEditarProducto{{ $producto->id }}">
                            Editar
                        </button>
                        <form action="{{ route('productos.destroy', $producto) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('¿Eliminar este producto?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No hay productos registrados.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $productos->links() }}
</div>

{{-- Modales de edición: van FUERA de la tabla --}}
@foreach ($productos as $producto)
    <div class="modal fade" id="modalEditarProducto{{ $producto->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('productos.update', $producto) }}" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ $producto->nombre }}" required maxlength="150">

                    <label class="form-label mt-2">Descripción</label>
                    <textarea name="descripcion" class="form-control" maxlength="500">{{ $producto->descripcion }}</textarea>

                    <label class="form-label mt-2">Cantidad</label>
                    <input type="number" name="cantidad" class="form-control"
                           value="{{ $producto->cantidad }}" min="0" required>

                    <label class="form-label mt-2">Precio Unitario</label>
                    <input type="number" step="0.01" name="precio_unitario" class="form-control"
                           value="{{ $producto->precio_unitario }}" min="0" required>

                    <label class="form-label mt-2">Categoría</label>
                    <select name="categoria_id" class="form-select" required>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                @selected($categoria->id === $producto->categoria_id)>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- Modal de creación --}}
<div class="modal fade" id="modalCrearProducto" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('productos.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                       value="{{ old('nombre') }}" required maxlength="150">
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror

                <label class="form-label mt-2">Descripción</label>
                <textarea name="descripcion" class="form-control" maxlength="500">{{ old('descripcion') }}</textarea>

                <label class="form-label mt-2">Cantidad</label>
                <input type="number" name="cantidad" class="form-control @error('cantidad') is-invalid @enderror"
                       value="{{ old('cantidad') }}" min="0" required>
                @error('cantidad') <div class="invalid-feedback">{{ $message }}</div> @enderror

                <label class="form-label mt-2">Precio Unitario</label>
                <input type="number" step="0.01" name="precio_unitario"
                       class="form-control @error('precio_unitario') is-invalid @enderror"
                       value="{{ old('precio_unitario') }}" min="0" required>
                @error('precio_unitario') <div class="invalid-feedback">{{ $message }}</div> @enderror

                <label class="form-label mt-2">Categoría</label>
                <select name="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror" required>
                    <option value="">-- Seleccione --</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection