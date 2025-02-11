@extends('layouts.contentNavbarLayout')

@section('title', 'Liste des Modèles d\'E-mail')

@section('content')
<div class="col-md-12">
    <div class="nav-align-top">
        <ul class="nav nav-pills flex-column flex-md-row mb-6">
            <li class="nav-item"><a class="nav-link" href="{{ url('pages/account-settings-account') }}"><i class="bx bx-sm bx-user me-1_5"></i> Account</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('pages/account-settings-notifications') }}"><i class="bx bx-sm bx-bell me-1_5"></i> Notifications</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ url('email/liste') }}"><i class="bx bx-sm bx-envelope me-1_5"></i>Email Template Manager</a></li>
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
                                    <button class="btn p-0 text-primary" onclick="editRecord('{{ $template->name }}', 'Template', '{{ $template->updated_at->format('Y-m-d') }}', '{{ $template->id }}')">
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
                    <button type="submit" class="btn btn-primary">Save Changes</button>
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