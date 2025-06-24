@extends('layouts/contentNavbarLayout')

@section('title', 'Historique des Rapports')

@section('content')
<h4 class="py-3 mb-4">
  Historique des Rapports
</h4>

<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Historique des Rapports Envoyés</h5>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead class="table-light">
        <tr>
          <th>Utilisateur</th>
          <th>Status</th>
          <th>Tentatives</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse ($historiqueReports as $historique)
        <tr>
          <td>{{ $historique->user->name ?? 'N/A' }}</td>
          <td>
            @if($historique->status == 1)
              <span class="badge bg-label-success me-1">Envoyé</span>
            @elseif($historique->status == 2)
               <span class="badge bg-label-danger me-1">Échoué</span>
            @else
              <span class="badge bg-label-warning me-1">En attente</span>
            @endif
          </td>
           <td>
                <span class="badge bg-label-info">{{ $historique->attempts }}</span>
           </td>
          <td>{{ $historique->created_at->format('d/m/Y H:i') }}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('history-details-reports', ['id' => $historique->id]) }}"><i class="bx bx-refresh me-1"></i> Régénérer</a>
                <form action="{{ route('history-reports-destroy', ['id' => $historique->id]) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet historique ?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="dropdown-item"><i class="bx bx-trash me-1"></i> Supprimer</button>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center">Aucun historique de rapport trouvé.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">
    {{ $historiqueReports->links() }}
  </div>
</div>
@endsection
