<form action="{{ route('email.update', $template->id) }}" method="POST">
    @csrf
    @method('PUT')
    <label for="alertTitle">Titre de l'alerte</label>
    <input type="text" name="title" id="alertTitle" value="{{ $template->title }}" class="form-control">

    <!-- Autres champs spécifiques à Alert -->

    <button type="submit" class="btn btn-primary mt-2">Enregistrer</button>
</form>
