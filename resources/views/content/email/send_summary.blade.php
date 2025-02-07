@extends('layouts.contentNavbarLayout')

@section('title', 'Modifier le Modèle d\'E-mail')

@section('content')
<div>
    <h2>Modifier le Modèle d'E-mail</h2>
    <form action="{{ route('email.update', $template->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="type">Type :</label>
            <select name="type" id="type" onchange="toggleFields()">
                <option value="rapport" {{ $template->type == 'rapport' ? 'selected' : '' }}>Rapport</option>
                <option value="alerte" {{ $template->type == 'alerte' ? 'selected' : '' }}>Alerte</option>
            </select>
        </div>
        <div>
            <label for="title">Titre :</label>
            <input type="text" name="title" id="title" value="{{ $template->title }}" required>
        </div>
        <div id="reportFields" style="{{ $template->type == 'rapport' ? 'display:block;' : 'display:none;' }}">
            <label for="report">Choisir un Rapport :</label>
            <select name="report" id="report">
                <!-- Options de rapport ici -->
            </select>
        </div>
        <div id="alertFields" style="{{ $template->type == 'alerte' ? 'display:block;' : 'display:none;' }}">
            <!-- Options spécifiques aux alertes -->
        </div>
        <div>
            <label for="description">Description :</label>
            <textarea name="description" id="description" required>{{ $template->description }}</textarea>
        </div>
        <div>
            <h3>KPI Sélectionnés :</h3>
            @foreach ($kpis as $kpi)
                <div>
                    <input type="checkbox" id="kpi-{{ $kpi->id }}" name="kpis[]" value="{{ $kpi->id }}" {{ in_array($kpi->id, $template->kpis) ? 'checked' : '' }}>
                    <label for="kpi-{{ $kpi->id }}">{{ $kpi->label }}</label>
                </div>
            @endforeach
        </div>
        <button type="submit">Modifier</button>
    </form>
</div>

@section('scripts')
<script>
    function toggleFields() {
        const type = document.getElementById('type').value;
        document.getElementById('reportFields').style.display = type === 'rapport' ? 'block' : 'none';
        document.getElementById('alertFields').style.display = type === 'alerte' ? 'block' : 'none';
    }
</script>
@endsection
@endsection
