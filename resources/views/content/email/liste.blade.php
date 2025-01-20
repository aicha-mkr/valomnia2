@extends('layouts/contentNavbarLayout')

@section('title', 'Liste des Modèles d\'E-mail')

@section('content')
<div class="col-md-12">
    <div class="nav-align-top">
      <ul class="nav nav-pills flex-column flex-md-row mb-6">
        <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-sm bx-user me-1_5"></i> Account</a></li>
        <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-notifications')}}"><i class="bx bx-sm bx-bell me-1_5"></i> Notifications</a></li>
        <li class="nav-item"><a class="nav-link" href="{{url('email/create')}}"><i class="bx bx-sm bx-envelope me-1_5"></i> Create Email</a></li>
        <li class="nav-item"><a class="nav-link" href="{{url('email/liste')}}"><i class="bx bx-sm bx-envelope me-1_5"></i> Liste</a></li>

      </ul>
        <div class="card mb-6">
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('email.create') }}" class="btn btn-primary">Créer un Modèle</a>
                </div>
                <h5 class="card-header">Liste des Modèles d'E-mail</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Objet</th>
                                <th>Date</th>
                                <th>Acteur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach($emailTemplates as $template)
                            <tr>
                                <td>{{ $template->name }}</td>
                                <td>{{ $template->subject }}</td>
                                <td>{{ $template->created_at->format('Y-m-d') }}</td>
                                <td>{{ $template->user->name ?? 'Inconnu' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('email.edit', $template->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Modifier
                                            </a>
                                            <a class="dropdown-item" href="{{ route('email.delete', $template->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $template->id }}').submit();">
                                                <i class="bx bx-trash me-1"></i> Supprimer
                                            </a>
                                            <form id="delete-form-{{ $template->id }}" action="{{ route('email.delete', $template->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection