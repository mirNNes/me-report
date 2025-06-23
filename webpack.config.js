import Encore from '@symfony/webpack-encore';

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // Mappen där de byggda filerna sparas
    .setOutputPath('public/build/')
    // Den offentliga sökvägen i URL:en för assets
    .setPublicPath('/build')
    // Prefix för manifestet
    .setManifestKeyPrefix('/build')
    // Entrypunkter för ditt app-script och project-css
    .addEntry('app', './assets/app.js')
    .addEntry('project', './assets/project.js')
    // Dela upp kod i chunks för bättre caching
    .splitEntryChunks()
    // En "runtime" fil (webpack bootstrap)
    .enableSingleRuntimeChunk()
    // Rensa "build" mappen före ny bygg
    .cleanupOutputBeforeBuild()
    // Notiser vid bygg (fungerar i dev)
    .enableBuildNotifications()
    // Source maps i dev-läge
    .enableSourceMaps(!Encore.isProduction())
    // Filversionering för cachebusting i produktion
    .enableVersioning(Encore.isProduction())
    // Babel-konfiguration för polyfills etc.
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    });

export default Encore.getWebpackConfig();
