@php
    $path = explode('/', request()->path());
    $path[1] = (array_key_exists(1, $path)> 0)?$path[1]:'';
    $path[2] = (array_key_exists(2, $path)> 0)?$path[2]:'';
    $path[0] = ($path[0] === '')?'documents':$path[0];
@endphp

<aside id="sidebar-left" class="sidebar-left">
    <div class="sidebar-header">
        <div class="sidebar-title">
            Menu
        </div>
        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html"
             data-fire-event="sidebar-left-toggle">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>
    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    <li class="{{ ($path[0] === 'documents')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('tenant.documents.index')}}">
                            <i class="fas fa-receipt"></i><span>Comprobantes</span>
                        </a>
                    </li>
                    <li class="{{ ($path[0] === 'items')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('tenant.items.index')}}">
                            <i class="fas fa-shopping-cart"></i><span>Productos</span>
                        </a>
                    </li>
                    {{--<li class="{{ ($path[0] === 'customers')?'nav-active':'' }}">--}}
                        {{--<a class="nav-link" href="{{route('tenant.customers.index')}}">--}}
                            {{--<i class="fas fa-users"></i><span>Clientes</span>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    {{--<li class="{{ ($path[0] === 'suppliers')?'nav-active':'' }}">--}}
                        {{--<a class="nav-link" href="{{route('tenant.suppliers.index')}}">--}}
                            {{--<i class="fas fa-users"></i><span>Proveedores</span>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    <li class="{{ ($path[0] === 'persons' && $path[1] === 'customer')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('tenant.persons.index', ['type' => 'customer'])}}">
                            <i class="fas fa-users"></i><span>Clientes</span>
                        </a>
                    </li>
                    <li class="{{ ($path[0] === 'persons' && $path[1] === 'supplier')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('tenant.persons.index', ['type' => 'supplier'])}}">
                            <i class="fas fa-users"></i><span>Proveedores</span>
                        </a>
                    </li>

                    <li class="{{ ($path[0] === 'purchases')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('tenant.purchases.index')}}">
                            <i class="fas fa-shopping-cart"></i><span>Compras</span>
                        </a>
                    </li>

                    <li class="{{ ($path[0] === 'summaries')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('tenant.summaries.index')}}">
                            <i class="fas fa-list"></i><span>Resúmenes</span>
                        </a>
                    </li>
                    <li class="{{ ($path[0] === 'voided')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('tenant.voided.index')}}">
                            <i class="fas fa-list"></i><span>Anulaciones</span>
                        </a>
                    </li>
                    {{--<li class="{{ ($path[0] === 'voided')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('voided.index')}}">
                            <i class="fas fa-list"></i><span>Anulaciones</span>
                        </a>
                    </li>--}}
                    <li class="{{ ($path[0] === 'companies')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('tenant.companies.create')}}">
                            <i class="fas fa-building"></i><span>Empresa</span>
                        </a>
                    </li>
                    <li class="{{(($path[0] === 'reports') && ($path[1] != 'purchases')) ? 'nav-active' : ''}}">
                        <a class="nav-link" href="{{route('tenant.reports.index')}}">
                            <i class="fas fa-chart-line"></i><span>Reporte Documentos</span>
                        </a>
                    </li>
                    <li class="{{(($path[0] === 'reports') && ($path[1] === 'purchases')) ? 'nav-active' : ''}}">
                        <a class="nav-link" href="{{route('tenant.reports.purchases.index')}}">
                            <i class="fas fa-chart-line"></i><span>Reporte Compras</span>
                        </a>
                    </li>
                    {{--<li class="{{ ($path[0] === 'perceptions')?'nav-active':'' }}">--}}
                        {{--<a class="nav-link" href="{{route('tenant.perceptions.index')}}">--}}
                            {{--<i class="fas fa-receipt"></i><span>Percepciones</span>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    <li class="{{ ($path[0] === 'retentions')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('tenant.retentions.index')}}">
                            <i class="fas fa-receipt"></i><span>Retenciones</span>
                        </a>
                    </li>
                    <li class="{{ ($path[0] === 'dispatches')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('tenant.dispatches.index')}}">
                            <i class="fas fa-receipt"></i><span>Guías de remisión</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');
                    sidebarLeft.scrollTop = initialPosition;
                }
            }
        </script>
    </div>
</aside>