<?php

namespace Youke\BaseSettings;

use Illuminate\Support\Facades\File;

class Generator
{
    public static function generateDocs()
    {
        $appDir = config('swagger.paths.annotations');
        $docDir = config('swagger.paths.docs');

        if (!File::exists($docDir) || is_writable($docDir)) {
            // delete all existing documentation
            if (File::exists($docDir)) {
                File::deleteDirectory($docDir);
            }

            self::defineConstants(config('swagger.constants') ?: []);

            File::makeDirectory($docDir);
            $excludeDirs = config('swagger.paths.excludes');

            if (version_compare(config('swagger.swagger_version'), '3.0', '>=')) {
                $swagger = \OpenApi\scan($appDir, ['exclude' => $excludeDirs]);
            } else {
                $swagger = \Swagger\scan($appDir, ['exclude' => $excludeDirs]);
            }

            if (config('swagger.paths.base') !== null) {
                $swagger->basePath = config('swagger.paths.base');
            }

            $filename = $docDir . '/' . config('swagger.paths.docs_json');
            $swagger->saveAs($filename);

            $security = new SecurityDefinitions();
            $security->generate($filename);
        }
    }

    protected static function defineConstants(array $constants)
    {
        if (!empty($constants)) {
            foreach ($constants as $key => $value) {
                defined($key) || define($key, $value);
            }
        }
    }
}
