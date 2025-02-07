@extends('layouts.contentNavbarLayout')

@section('title', 'Créer un Modèle d\'E-mail')

@section('content')


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
