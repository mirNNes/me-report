import Encore from '@symfony/webpack-encore';

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}
const devMode = !Encore.isProduction();

Encore
    .setOutputPath('public/build/')

    .setPublicPath(devMode ? '/build/' : './build/')

    .setManifestKeyPrefix('build')
    .addEntry('app', './assets/app.js')
    .addEntry('project', './assets/project.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()

    .enableSourceMaps(devMode)
    .enableVersioning(!devMode)
    .configureBabelPresetEnv(config => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    });

export default Encore.getWebpackConfig();
