@extends('layouts.contentNavbarLayout')

@section('title', 'Liste des Modèles d\'E-mail')

@section('content')
<div class="col-md-12">
    <div class="nav-align-top">
        <ul class="nav nav-pills flex-column flex-md-row mb-6">
<<<<<<< HEAD
            <li class="nav-item"><a class="nav-link" href="{{ url('pages/account-settings-account') }}"><i class="bx bx-sm bx-user me-1_5"></i> Account</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('pages/account-settings-notifications') }}"><i class="bx bx-sm bx-bell me-1_5"></i> Notifications</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ url('email/liste') }}"><i class="bx bx-sm bx-envelope me-1_5"></i>Email Template Manager</a></li>
=======
            <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-account')}}"><i class="bx bx-sm bx-user me-1_5"></i> Account</a></li>
            <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-notifications')}}"><i class="bx bx-sm bx-bell me-1_5"></i> Notifications</a></li>
            <li class="nav-item"><a class="nav-link" href="{{url('email/create')}}"><i class="bx bx-sm bx-envelope me-1_5"></i> Create Email</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{url('email/liste')}}"><i class="bx bx-sm bx-envelope me-1_5"></i> Liste</a></li>
>>>>>>> ccd1463f87edb18f5acd4c77457074d27323137f
        </ul>

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5>Email Template Manager</h5>
                        <p>A list of all email templates including reports and alerts.</p>
                    </div>
                    <a href="{{ url('email/create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> New Template
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($templates as $template)
                            <tr>
                                <td>{{ $template->name }}</td>
                                <td><span class="badge bg-label-primary">Template</span></td>
                                <td>{{ $template->updated_at->format('d/m/Y') }}</td>
                                <td>
<<<<<<< HEAD
                                    <button class="btn p-0 text-primary" onclick="editRecord('{{ $template->name }}', 'Template', '{{ $template->updated_at->format('Y-m-d') }}', '{{ $template->id }}')">
=======
                                    <button class="btn p-0 text-primary" onclick="editRecord('Monthly Performance Report', 'Report', '10/08/2024', 'Description here', 'Email Header', 'Email Footer')">
>>>>>>> ccd1463f87edb18f5acd4c77457074d27323137f
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
<<<<<<< HEAD
                            @endforeach
=======
                            <tr>
                                <td>Critical System Alert</td>
                                <td><span class="badge bg-label-warning">Alert</span></td>
                                <td>10/08/2024</td>
                                <td>
                                    <button class="btn p-0 text-primary" onclick="editRecord('Critical System Alert', 'Alert', '10/08/2024', 'Description here', 'Email Header', 'Email Footer')">
                                        <i class="bx bx-edit-alt"></i>
                                    </button>
                                    <button class="btn p-0 text-danger" onclick="deleteRecord('Critical System Alert')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <!-- Ajoutez d'autres modèles d'e-mail ici -->
>>>>>>> ccd1463f87edb18f5acd4c77457074d27323137f
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Offcanvas for editing a record -->
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
<<<<<<< HEAD
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
=======
                        <label for="rapport-title" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="rapport-title" placeholder="Titre du rapport" required>
                    </div>
                    <div class="mb-3">
                        <label for="rapport-description" class="form-label">Description</label>
                        <textarea class="form-control" id="rapport-description" rows="3" placeholder="Ajouter des informations supplémentaires" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="rapport-email-header" class="form-label">En-tête d'Email</label>
                        <input type="text" class="form-control" id="rapport-email-header" placeholder="En-tête de l'email" required>
>>>>>>> ccd1463f87edb18f5acd4c77457074d27323137f
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
<<<<<<< HEAD
    function editRecord(title, type, lastUpdated, id) {
        // Fill the edit form with current record data
        document.getElementById('editTitle').value = title;
        document.getElementById('editType').value = type;
        document.getElementById('editDate').value = lastUpdated;
=======
    function editRecord(title, type, lastUpdated, description, emailHeader, emailFooter) {
        // Remplir les champs du formulaire d'édition avec les données de l'enregistrement
        document.getElementById('rapport-title').value = title;
        document.getElementById('rapport-description').value = description;
        document.getElementById('rapport-email-header').value = emailHeader;
        document.getElementById('rapport-email-footer').value = emailFooter;
>>>>>>> ccd1463f87edb18f5acd4c77457074d27323137f

        // Set the form action to the correct route for updating the record
        const form = document.getElementById('editForm');
        form.action = `/email/${id}`; // Set the action for the form to the update route

        // Open the offcanvas for editing
        var editOffcanvas = new bootstrap.Offcanvas(document.getElementById('editRecord'));
        editOffcanvas.show();
    }
</script>

@endsection