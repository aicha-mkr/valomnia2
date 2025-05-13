@extends('layouts/contentNavbarLayout')

@section('title', 'Historique des alertes')

@section('content')
  <h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Historique des alertes</span>
  </h4>

  <!-- Bordered Table -->
  <div class="card">
    <h5 class="card-header">Liste des historiques d'alertes</h5>
    <div class="card-body">
      <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
          <thead>
          <tr>
            <th>ID Alerte</th>
            <th>Utilisateur</th>
            <th>Type</th>
            <th>Organisation</th>
            <th>Tentatives</th>
            <th>Date de création</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          @foreach($historiqueAlerts as $index => $historiqueAlert)
            <tr>
              <td><span class="fw-medium">{{ $historiqueAlert->alert_id }}</span></td>
              <td>{{ $historiqueAlert->iduser }}</td>
              <td>
                @if(isset($historiqueAlert->alert) && isset($historiqueAlert->alert->type))
                  {{ $historiqueAlert->alert->type->name }}
                @else
                  Non défini
                @endif
              </td>
              <td>{{ $historiqueAlert->organisation ?? 'agro' }}</td>
              <td>
                <span class="badge bg-label-info">{{ $historiqueAlert->attempts }}</span>
              </td>
              <td>{{ $historiqueAlert->created_at->format('d/m/Y H:i') }}</td>
              <td>
                @if($historiqueAlert->status == 0)
                  <span class="badge bg-label-warning me-1">En attente</span>
                @elseif($historiqueAlert->status == 1)
                  <span class="badge bg-label-success me-1">Complété</span>
                @elseif($historiqueAlert->status == 2)
                  <span class="badge bg-label-danger me-1">Échoué</span>
                @else
                  <span class="badge bg-label-secondary me-1">Inconnu</span>
                @endif
              </td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                          data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                  <div class="dropdown-menu">
                    @if(isset($historiqueAlert->id))
                      <a class="dropdown-item text-success"
                         href="{{ route('historiqueAlerts.regenerate', ['id' => $historiqueAlert->id]) }}">
                        <i class="bx bx-repeat me-1"></i> Régénérer
                      </a>
                      <a class="dropdown-item text-danger"
                         href="{{ route('historiqueAlerts.destroy', ['id' => $historiqueAlert->id]) }}"
                         onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer cet historique ?')) { document.getElementById('delete-form-{{ $historiqueAlert->id }}').submit(); }">
                        <i class="bx bx-trash me-1"></i> Supprimer
                      </a>
                      <form id="delete-form-{{ $historiqueAlert->id }}"
                            action="{{ route('historiqueAlerts.destroy', ['id' => $historiqueAlert->id]) }}"
                            method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                      </form>
                    @endif
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-3">
        {{ $historiqueAlerts->links() }}
      </div>
    </div>
  </div>
  <!--/ Bordered Table -->

@endsection
