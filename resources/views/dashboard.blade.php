<x-layouts.app>
    <div class="container mt-4">
        <div class="row">
            <div class="col-3 bg-primary text-white p-3">Column 1</div>
            <div class="col-3 bg-secondary text-white p-3">Column 2</div>
            <div class="col-3 bg-success text-white p-3">Column 3</div>
            <div class="col-3 bg-danger text-white p-3">Column 4</div>
        </div>
        <div class="row mt-4">
            <button class="btn btn-success">save</button>
        </div>
    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-3 bg-primary text-white p-3">Column 1</div>
        <div class="col-3 bg-secondary text-white p-3">Column 2</div>
        <div class="col-3 bg-success text-white p-3">Column 3</div>
        @can('manage-equipments')
        <div class="col-3 bg-danger text-white p-3">Column 4</div>
        @endcan
    </div>
</x-layouts.app>
