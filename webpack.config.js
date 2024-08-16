const Encore = require('@symfony/webpack-encore');

Encore
    // Définit le répertoire où seront générés les fichiers compilés
    .setOutputPath('public/build/')

    // Définit le chemin public utilisé par le serveur web pour accéder aux fichiers générés
    .setPublicPath('/build')

    // Ajoute l'entrée principale de votre application (JS et CSS)
    .addEntry('app', './assets/app.js')
    .addStyleEntry('styles', './assets/styles/stylePublic.css')

    // Active un fichier de runtime unique (pour gérer les dépendances partagées entre les fichiers JS)
    .enableSingleRuntimeChunk()

    // Active la génération de source maps en mode développement
    .enableSourceMaps(!Encore.isProduction())

    // Nettoie le répertoire de sortie avant chaque build
    .cleanupOutputBeforeBuild()

    // Active la versioning (ajoute un hash aux noms des fichiers en production pour gérer le cache)
    .enableVersioning(Encore.isProduction())

    // Permet d'utiliser les préprocesseurs CSS comme Sass/SCSS (facultatif)
    .enableSassLoader()

    .copyFiles([
        {from: './node_modules/ckeditor4/', to: 'ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false},
        {from: './node_modules/ckeditor4/adapters', to: 'ckeditor/adapters/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/lang', to: 'ckeditor/lang/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/plugins', to: 'ckeditor/plugins/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/skins', to: 'ckeditor/skins/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/vendor', to: 'ckeditor/vendor/[path][name].[ext]'}
    ])
;

module.exports = Encore.getWebpackConfig();
