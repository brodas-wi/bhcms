<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <div class="row g-4">
            <!-- Barra lateral (filtros y acciones) -->
            <div class="col-lg-3 mt-0">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Gestión de Páginas</h2>
                        <a href="<?php echo e(route('pages.create')); ?>" class="btn btn-primary w-100 mb-4">
                            <i class="fas fa-plus-circle me-2"></i>Nueva Página
                        </a>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="h5 mb-0">Filtros</h3>
                            <button class="btn btn-sm btn-outline-white d-lg-none" type="button" data-bs-toggle="collapse"
                                data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse"
                                id="filterToggle">
                                <i class="fas fa-chevron-down" id="filterIcon"></i>
                            </button>
                        </div>
                        <div class="d-lg-block collapse" id="filterCollapse">
                            <form action="<?php echo e(route('pages.index')); ?>" method="GET" id="filter-form">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-lg-12">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Estado</label>
                                            <select class="form-select form-select-sm" id="status" name="status">
                                                <option value="">Todos</option>
                                                <option value="published"
                                                    <?php echo e(request('status') == 'published' ? 'selected' : ''); ?>>Publicado
                                                </option>
                                                <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>
                                                    Borrador</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-12">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Categoría</label>
                                            <select class="form-select form-select-sm" id="category" name="category">
                                                <option value="">Todas</option>
                                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category->id); ?>"
                                                        <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                                                        <?php echo e($category->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-12">
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">Fecha inicial</label>
                                            <input type="date" class="form-control form-control-sm" id="start_date"
                                                name="start_date" value="<?php echo e(request('start_date')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-12">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">Fecha final</label>
                                            <input type="date" class="form-control form-control-sm" id="end_date"
                                                name="end_date" value="<?php echo e(request('end_date')); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-filter me-2"></i>Aplicar Filtros
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-filters">
                                        <i class="fas fa-undo me-2"></i>Borrar Filtros
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="col-lg-9 mt-0">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h2 class="h4 mb-0">Páginas Disponibles</h2>
                    </div>
                    <div class="card-body">
                        <?php if($pages->isEmpty()): ?>
                            <div class="py-5 text-center">
                                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                                <h3 class="h5">No hay páginas creadas</h3>
                                <p class="text-muted">Comienza creando tu primera página haciendo clic en "Nueva Página".
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                                <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col">
                                        <div class="card card-small h-100 border-0 shadow-sm">
                                            <img src="<?php echo e($page->thumbnail); ?>" class="card-img-top"
                                                alt="<?php echo e($page->name); ?>" style="height: 160px; object-fit: cover;">
                                            <div class="card-body d-flex flex-column">
                                                <h3 class="h5 card-title mb-2"><?php echo e($page->name); ?></h3>
                                                <div class="w-100 d-flex align-items-center justify-content-between">
                                                    <p class="card-text text-muted small mb-2">Creado:
                                                        <?php echo e($page->created_at->format('d/m/Y')); ?></p>
                                                    <p class="card-text text-muted small mb-2">Versión:
                                                        <?php echo e($page->version); ?>

                                                    </p>
                                                </div>
                                                <div class="mb-2">
                                                    <?php if($page->category): ?>
                                                        <span class="badge bg-primary"><?php echo e($page->category->name); ?></span>
                                                    <?php endif; ?>
                                                    <span
                                                        class="badge <?php echo e($page->status === 'published' ? 'bg-success' : 'bg-secondary'); ?>">
                                                        <?php echo e($page->status === 'published' ? 'Publicado' : 'Borrador'); ?>

                                                    </span>
                                                </div>
                                                <p class="card-text flex-grow-1"><?php echo e(Str::limit($page->description, 80)); ?>

                                                </p>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <a href="<?php echo e(route('pages.show', $page->id)); ?>"
                                                        class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                        <i class="fas fa-eye"></i> Detalles
                                                    </a>
                                                    <div class="btn-group" role="group">
                                                        <a href="<?php echo e(route('pages.display', $page->slug)); ?>"
                                                            class="btn btn-sm btn-outline-primary" title="Vista Previa"
                                                            target="_blank">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                        <a href="<?php echo e(route('pages.edit', $page->id)); ?>"
                                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            title="Eliminar"
                                                            onclick="confirmDelete(<?php echo e($page->id); ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de borrado -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres eliminar esta página?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <form id="delete-form" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .card-small {
            transition: transform 0.2s;
        }

        .card-small:hover {
            transform: translateY(-5px);
        }

        @media (max-width: 575.98px) {
            .card-body {
                padding: 1rem;
            }

            .form-label {
                font-size: 0.9rem;
            }

            .btn-sm {
                font-size: 0.8rem;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clearFiltersBtn = document.getElementById('clear-filters');
            const filterForm = document.getElementById('filter-form');
            const statusSelect = document.getElementById('status');
            const categorySelect = document.getElementById('category');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            function areFiltersApplied() {
                return statusSelect.value !== '' || categorySelect.value !== '' ||
                    startDateInput.value !== '' || endDateInput.value !== '';
            }

            function toggleClearFiltersButton() {
                clearFiltersBtn.style.display = areFiltersApplied() ? 'block' : 'none';
            }

            toggleClearFiltersButton();

            [statusSelect, categorySelect, startDateInput, endDateInput].forEach(el => {
                el.addEventListener('change', toggleClearFiltersButton);
            });

            clearFiltersBtn.addEventListener('click', function() {
                statusSelect.value = '';
                categorySelect.value = '';
                startDateInput.value = '';
                endDateInput.value = '';
                filterForm.submit();
            });

            const filterCollapse = document.getElementById('filterCollapse');
            const filterIcon = document.getElementById('filterIcon');
            const filterToggle = document.getElementById('filterToggle');

            function updateFilterIcon() {
                if (filterCollapse.classList.contains('show')) {
                    filterIcon.classList.remove('fa-chevron-down');
                    filterIcon.classList.add('fa-chevron-up');
                } else {
                    filterIcon.classList.remove('fa-chevron-up');
                    filterIcon.classList.add('fa-chevron-down');
                }
            }

            // Inicializar el estado del icono
            updateFilterIcon();

            // Usar el evento de Bootstrap para detectar cambios en el colapso
            filterCollapse.addEventListener('shown.bs.collapse', updateFilterIcon);
            filterCollapse.addEventListener('hidden.bs.collapse', updateFilterIcon);

            // Manejar el almacenamiento local
            filterCollapse.addEventListener('shown.bs.collapse', function() {
                localStorage.setItem('filterCollapseState', 'shown');
            });
            filterCollapse.addEventListener('hidden.bs.collapse', function() {
                localStorage.setItem('filterCollapseState', 'hidden');
            });

            // Restaurar el estado del colapso al cargar la página
            const savedState = localStorage.getItem('filterCollapseState');
            if (savedState === 'shown') {
                new bootstrap.Collapse(filterCollapse).show();
            }

            // Asegurarse de que el icono se actualice correctamente en dispositivos móviles
            if (window.matchMedia("(max-width: 992px)").matches) {
                filterToggle.addEventListener('click', function() {
                    setTimeout(updateFilterIcon, 0);
                });
            }

            // Manejo del modal de confirmación de borrado
            const deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            let pageIdToDelete = null;

            // Función para mostrar el modal de confirmación
            window.confirmDelete = function(pageId) {
                pageIdToDelete = pageId;
                deleteConfirmModal.show();
            };

            // Acción de confirmación de borrado
            confirmDeleteBtn.addEventListener('click', function() {
                if (pageIdToDelete) {
                    const form = document.getElementById('delete-form');
                    form.action = `/pages/${pageIdToDelete}`;
                    form.submit();
                }
                deleteConfirmModal.hide();
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\brian\Documentos\GitHub\bankhipo\website-institucional\bhcms\resources\views/pages/index.blade.php ENDPATH**/ ?>