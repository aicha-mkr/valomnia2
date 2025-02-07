import './bootstrap'; // Charger les dépendances
import Vue from 'vue'; // Importer Vue
import WizardForm from './components/WizardForm.vue'; // Importer votre composant

// Initialiser Vue
const app = new Vue({
    el: '#app', // Attacher Vue à l'élément avec l'ID "app"
    components: {
        WizardForm, // Enregistrer le composant
    },
});

// Chargez les ressources statiques
import.meta.glob([
  '../assets/img/**',
  '../assets/vendor/fonts/**'
]);