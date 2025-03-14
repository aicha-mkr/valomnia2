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
                                    <button class="btn p-0 text-primary" onclick="editRecord('{{ $template->title }}', 'Template', '{{ $template->updated_at->format('Y-m-d') }}', '{{ $template->id }}')">
                                        <i class="bx bx-edit-alt"></i>
                                    </button>
                                    <form action="{{ route('email.destroy', $template->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn p-0 text-danger" onclick="return confirm('Are you sure you want to delete this template?');">
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
        <div class="offcanvas offcanvas-end" tabindex="-1" id="editRecord" aria-labelledby="editRecordLabel">
            <div class="offcanvas-header">
                <h5 id="editRecordLabel">Edit Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="editTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="editTitle" placeholder="Title" required>
                    </div>
                    <div class="mb-3">
                        <label for="editType" class="form-label">Type</label>
                        <input type="text" class="form-control" id="editType" placeholder="Type" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDate" class="form-label">Last Updated</label>
                        <input type="date" class="form-control" id="editDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="rapport-email-footer" class="form-label">Pied de page d'Email</label>
                        <input type="text" class="form-control" id="rapport-email-footer" placeholder="Pied de page de l'email" required>
                    </div>
                    <div class="mb-3">
                        <label for="rapport-available-reports" class="form-label">Rapports Disponibles</label>
                        <select class="form-select" id="rapport-available-reports" aria-label="Rapports Disponibles" required>
                            <option selected>Choisir un rapport</option>
                            <option value="performance">Performance Metrics</option>
                            <option value="financial">Financial Summary</option>
                            <option value="user-analytics">User Analytics</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Créer le Rapport</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editRecord(title, type, lastUpdated, id) {
        // Fill the edit form with current record data
        document.getElementById('editTitle').value = title;
        document.getElementById('editType').value = type;
        document.getElementById('editDate').value = lastUpdated;

        // Set the form action to the correct route for updating the record
        const form = document.getElementById('editForm');
        form.action = `/email/${id}`; // Set the action for the form to the update route

        // Open the offcanvas for editing
        var editOffcanvas = new bootstrap.Offcanvas(document.getElementById('editRecord'));
        editOffcanvas.show();
    }
</script>


@endsection
