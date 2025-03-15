@extends('layouts.contentNavbarLayout')

@section('title', 'Liste des Modèles d\'E-mail')

@section('content')

@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif




<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5>Email Template Manager</h5>

            </div>
            <a href="{{ url('organisation/email/create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> New Template
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>title</th>
                        <th>Type</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($templates as $template)
                    <tr>
                        <td>{{ $template->title }}</td>
                        <td>
                            @if ($template->type === 'Rapport')
                            <span class="badge bg-label-primary">Report</span>
                            @elseif ($template->type === 'Alert')
                            <span class="badge bg-label-danger">Alert</span>
                            @else
                            <span class="badge bg-label-secondary">Unknown</span>
                            @endif
                        </td>
                        <td>{{ $template->updated_at->format('d/m/Y') }}</td>
                        <td>
                            <button class="btn p-0 text-primary"
                                onclick="editRecord('{{ $template->title }}', 'Template', '{{ $template->updated_at->format('Y-m-d') }}', '{{ $template->id }}')">
                                <i class="bx bx-edit-alt"></i>
                            </button>
                            <form action="{{ route('email.destroy', $template->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn p-0 text-danger"
                                    onclick="return confirm('Are you sure you want to delete this template?');">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Offcanvas pour éditer un enregistrement-->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editRecord" aria-labelledby="editRecordLabel"
    style="width: 75%; max-width: 100%;">
    <div class="offcanvas-header">
        <h5 id="editRecordLabel">Edit Template</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div id="editViewContainer">
            <!-- La vue chargée dynamiquement s'affichera ici -->
        </div>
    </div>
</div>



<script>
function editRecord(title, type, updatedAt, id) {
    const container = document.getElementById('editViewContainer');

    // Faire une requête AJAX pour récupérer le formulaire approprié
    fetch("{{ route('email.edit', ':id') }}".replace(':id', id))
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            container.innerHTML = html; // Insérer le formulaire dans le conteneur

            // Afficher l'Offcanvas
            var editOffcanvas = new bootstrap.Offcanvas(document.getElementById('editRecord'));
            editOffcanvas.show();
        })
        .catch(error => console.error("Erreur lors du chargement de la vue :", error));
}
</script>



@endsection