<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /** 
     *  @ToDo This is the path of the folder that will be converted to a module.
     */

    $targetFolder = trim(str_replace(['"', "'"], '', $_POST['targetSource']));
    $targetFolder = str_replace('/', '\\', $targetFolder);

    $destinationSource = trim(str_replace(['"', "'"], '', $_POST['destinationSource']));
    $destinationSource = str_replace('/', '\\', $destinationSource);

    // New module name
    $moduleName = ucfirst(basename(rtrim($targetFolder, '/')));
    $moduleNameLower = lcfirst(basename(rtrim($targetFolder, '/')));
    echo "<h2>$moduleName</h2>";

    $sourceBlog = "./Blog/";
    $destinationFolder = $destinationSource . "/{$moduleName}/";

    // Delete destination path if exist
    function deleteFolderRecursively($path)
    {
        if (!is_dir($path)) {
            return;
        }

        $items = array_diff(scandir($path), ['.', '..']);

        foreach ($items as $item) {
            $itemPath = $path . DIRECTORY_SEPARATOR . $item;

            if (is_dir($itemPath)) {
                deleteFolderRecursively($itemPath);
            } else {
                unlink($itemPath);
            }
        }

        rmdir($path);
    }

    deleteFolderRecursively($destinationFolder);

    if (!is_dir($sourceBlog)) {
        echo "Source folder not found!";
        exit;
    }

    $folders = [
        'Config',
        'Console',
        'Database/factories',
        'Database/Migrations',
        'Database/Seeders',
        'Entities',
        'Http/Controllers',
        'Http/Middleware',
        'Http/Requests',
        'Providers',
        'Routes',
        'Tests/Feature',
        'Tests/Unit',
    ];

    $gitkeepSource = $sourceBlog . "Config/.gitkeep";

    foreach ($folders as $folder) {
        $newFolder = $destinationFolder . $folder;

        if (preg_match('/\.[a-z]+$/i', $folder)) {
            continue;
        }

        if (!is_dir($newFolder)) {
            mkdir($newFolder, 0777, true);
            $gitkeepDest = $newFolder . "/.gitkeep";
            copy($gitkeepSource, $gitkeepDest);
        }
    }

    /** 
     *  @ToDo From here the files will be transferred.
     */

    function replaceText($sourceBlog, $destinationFolder, $moduleName, $moduleNameLower)
    {
        echo "<ul>";
        //! Config
        $oldFileConfigPath = $sourceBlog . 'Config\config.php';
        $newFileConfigPath = $destinationFolder . 'Config\config.php';
        copy($oldFileConfigPath, $newFileConfigPath);
        $content = file_get_contents($newFileConfigPath);
        $content = str_replace("'name' => 'Blog'", "'name' => '{$moduleName}'", $content);
        file_put_contents($newFileConfigPath, $content);
        echo "<li><b>Config ✅</b></li>";

        //! Api
        $oldFileApiPath = $sourceBlog . 'Routes\api.php';
        $newFileApiPath = $destinationFolder . 'Routes\api.php';
        copy($oldFileApiPath, $newFileApiPath);
        $content = file_get_contents($newFileApiPath);
        $content = str_replace("get('/blog'", "get('/" . $moduleNameLower . "'", $content);
        file_put_contents($newFileApiPath, $content);
        echo "<li><b>Api ✅</b></li>";

        //! Web
        $oldFileWebPath = $sourceBlog . 'Routes\web.php';
        $newFileWebPath = $destinationFolder . 'Routes\web.php';
        copy($oldFileWebPath, $newFileWebPath);
        $content = file_get_contents($newFileWebPath);
        $content = str_replace("Route::prefix('blog')", "Route::prefix('{$moduleNameLower}')", $content);
        $content = str_replace("BlogController@index", "{$moduleName}Controller@index", $content);
        file_put_contents($newFileWebPath, $content);
        echo "<li><b>Web ✅</b></li>";

        //! Controllers
        $oldFileControllerPath = $sourceBlog . 'Http\Controllers\BlogController.php';
        $newFileControllerPath = $destinationFolder . "Http\Controllers\\" . $moduleName . "Controller.php";
        copy($oldFileControllerPath, $newFileControllerPath);
        $content = file_get_contents($newFileControllerPath);
        $content = str_replace("namespace Modules\Blog\Http\Controllers;", "namespace Modules\\{$moduleName}\\Http\\Controllers;", $content);
        $content = str_replace('class BlogController', "class " . $moduleName . "Controller", $content);
        $content = str_replace('blog::index', $moduleNameLower . "::index", $content);
        $content = str_replace('blog::create', $moduleNameLower . "::create", $content);
        $content = str_replace('blog::show', $moduleNameLower . "::show", $content);
        $content = str_replace('blog::edit', $moduleNameLower . "::edit", $content);
        file_put_contents($newFileControllerPath, $content);
        echo "<li><b>Controllers ✅</b></li>";

        //! Seeders
        $oldFileDatabaseSeederPath = $sourceBlog . 'Database\Seeders\BlogDatabaseSeeder.php';
        $newFileDatabaseSeederPath = $destinationFolder . "Database\Seeders\\" . $moduleName . "DatabaseSeeder.php";
        copy($oldFileDatabaseSeederPath, $newFileDatabaseSeederPath);
        $content = file_get_contents($newFileDatabaseSeederPath);
        $content = str_replace("namespace Modules\Blog\Database\Seeders;", "namespace Modules\\{$moduleName}\\Database\\Seeders;", $content);
        $content = str_replace('class BlogDatabaseSeeder', "class " . $moduleName . "DatabaseSeeder", $content);
        file_put_contents($newFileDatabaseSeederPath, $content);
        echo "<li><b>Seeder ✅</b></li>";

        //! Module Providers
        $oldFileModuleProviderPath = $sourceBlog . 'Providers\BlogServiceProvider.php';
        $newFileModuleProviderPath = $destinationFolder . "Providers\\" . $moduleName . "ServiceProvider.php";
        copy($oldFileModuleProviderPath, $newFileModuleProviderPath);
        $content = file_get_contents($newFileModuleProviderPath);
        $content = str_replace("namespace Modules\Blog\Providers;", "namespace Modules\\{$moduleName}\\Providers;", $content);
        $content = str_replace('class BlogServiceProvider', "class " . $moduleName . "ServiceProvider", $content);
        $content = str_replace("moduleName = 'Blog'", "moduleName = '$moduleName'", $content);
        $content = str_replace("moduleNameLower = 'blog'", "moduleNameLower = '$moduleNameLower'", $content);
        file_put_contents($newFileModuleProviderPath, $content);
        echo "<li><b>Module Providers ✅</b></li>";

        //! Route Providers
        $oldFileRouteProviderPath2 = $sourceBlog . 'Providers\RouteServiceProvider.php';
        $newFileRouteProviderPath2 = $destinationFolder . "Providers\RouteServiceProvider.php";
        copy($oldFileRouteProviderPath2, $newFileRouteProviderPath2);
        $content = file_get_contents($newFileRouteProviderPath2);
        $content = str_replace("namespace Modules\Blog\Providers;", "namespace Modules\\{$moduleName}\\Providers;", $content);
        $content = str_replace('Modules\Blog\Http\Controllers', "Modules\\" . $moduleName . "\\Http\Controllers", $content);
        $content = str_replace('module_path(\'Blog\', \'/Routes/web.php\')', "module_path('" . $moduleName . "', '/Routes/web.php')", $content);
        $content = str_replace('module_path(\'Blog\', \'/Routes/api.php\')', "module_path('" . $moduleName . "', '/Routes/api.php')", $content);
        file_put_contents($newFileRouteProviderPath2, $content);
        echo "<li><b>Route Providers ✅</b></li>";

        //! Resources
        $oldFileResourcesPath = $sourceBlog . 'Resources/';
        $newFileResourcesPath = $destinationFolder . "Resources/";

        function copyDirectory($source, $destination)
        {
            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }
            $files = scandir($source);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $sourceFile = $source . '/' . $file;
                    $destinationFile = $destination . '/' . $file;
                    if (is_dir($sourceFile)) {
                        copyDirectory($sourceFile, $destinationFile);
                    } else {
                        copy($sourceFile, $destinationFile);
                    }
                }
            }
        }
        copyDirectory($oldFileResourcesPath, $newFileResourcesPath);
        echo "<li><b>Resources ✅</b></li>";

        //! Views
        $oldFileViewPath = $sourceBlog . 'Resources/views/index.blade.php';
        $newFileViewPath = $destinationFolder . "Resources/views/index.blade.php";
        copy($oldFileViewPath, $newFileViewPath);
        $content = file_get_contents($newFileViewPath);
        $content = str_replace('Module Blog', "Module " . $moduleNameLower, $content);
        $content = str_replace(('blog.name'), ('' . $moduleNameLower . '.name'), $content);
        file_put_contents($newFileViewPath, $content);
        echo "<li><b>Views ✅</b></li>";

        //! Composer
        $oldFileComposerPath = $sourceBlog . 'composer.json';
        $newFileComposerPath = $destinationFolder . "composer.json";
        copy($oldFileComposerPath, $newFileComposerPath);
        $content = file_get_contents($newFileComposerPath);
        $content = str_replace('"name": "nwidart/blog"', '"name": "nwidart/' . $moduleNameLower . '"', $content);
        $content = str_replace('Modules\\\\Blog', 'Modules\\\\' . $moduleName, $content);
        file_put_contents($newFileComposerPath, $content);
        echo "<li><b>Composer.json ✅</b></li>";

        //! Module
        $oldFileModulePath = $sourceBlog . 'module.json';
        $newFileModulePath = $destinationFolder . "module.json";
        copy($oldFileModulePath, $newFileModulePath);
        $content = file_get_contents($newFileModulePath);
        $content = str_replace('"name": "Blog"', '"name": "' . $moduleName . '"', $content);
        $content = str_replace('"alias": "blog"', '"alias": "' . $moduleNameLower . '"', $content);
        $content = str_replace('"Modules\\\\Blog\\\\Providers\\\\BlogServiceProvider"', '"Modules\\\\' . $moduleName . '\\\\Providers\\\\' . $moduleName . 'ServiceProvider"', $content);
        file_put_contents($newFileModulePath, $content);
        echo "<li><b>Module.json ✅</b></li>";

        //! Package
        $oldFilePackagePath = $sourceBlog . 'package.json';
        $newFilePackagePath = $destinationFolder . "package.json";
        copy($oldFilePackagePath, $newFilePackagePath);
        echo "<li><b>Package.json ✅</b></li>";

        //! ViteConfig
        $oldFileViteConfigPath = $sourceBlog . 'vite.config.js';
        $newFileViteConfigPath = $destinationFolder . "vite.config.js";
        copy($oldFileViteConfigPath, $newFileViteConfigPath);
        $content = file_get_contents($newFileViteConfigPath);
        $content = str_replace('../../public/build-blog', '../../public/build-' . $moduleNameLower, $content);
        $content = str_replace("buildDirectory: 'build-blog'", "buildDirectory: 'build-$moduleNameLower'", $content);
        file_put_contents($newFileViteConfigPath, $content);
        echo "<li><b>vite.config.js ✅</b></li>";
        echo "</ul>";
    }

    replaceText($sourceBlog, $destinationFolder, $moduleName, $moduleNameLower);

    /** 
     *  @ToDo From here the files will modified.
     */

    function copyToModule($sourceFile, $modulePath, $moduleName)
    {
        $filename = basename($sourceFile);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $relativePath = str_replace('\\', '/', $sourceFile);

        $content = '';
        if (in_array($extension, ['php', 'blade.php'])) {
            $content = file_get_contents($sourceFile);
        }

        $dest = null;

        // 1. Migrations
        if (preg_match('/\d{4}_\d{2}_\d{2}_\d{6}_.+\.php$/', $filename)) {
            $dest = "$modulePath/Database/Migrations/$filename";
        }

        // 2. Controllers
        elseif (strpos($content, 'App\\Http\\Controllers') != false || strpos($filename, 'Controller.php') != false) {
            $dest = "$modulePath/Http/Controllers/$filename";
            $content = str_replace('App\\Http\\Controllers', "Modules\\$moduleName\\Http\\Controllers", $content);
        }

        // 3. Middleware
        elseif (strpos($content, 'App\\Http\\Middleware') != false) {
            $dest = "$modulePath/Http/Middleware/$filename";
            $content = str_replace('App\\Http\\Middleware', "Modules\\$moduleName\\Http\\Middleware", $content);
        }

        // 4. Requests
        elseif (strpos($content, 'App\\Http\\Requests') != false) {
            $dest = "$modulePath/Http/Requests/$filename";
            $content = str_replace('App\\Http\\Requests', "Modules\\$moduleName\\Http\\Requests", $content);
        }

        // 5. Controllers
        elseif (strpos($content, 'App\\Http\\Controllers') != false || strpos($filename, 'Controller.php') != false) {
            $dest = "$modulePath/Http/Controllers/$filename";
            $content = str_replace('App\\Http\\Controllers', "Modules\\$moduleName\\Http\\Controllers", $content);
        }

        // 6. Repositories
        elseif (strpos($content, 'App\Repositories') != false) {
            $dest = "$modulePath/Repositories/$filename";
            $content = str_replace('App\\Repositories', "Modules\\$moduleName\\Repositories", $content);
        }

        // 7. Services
        elseif (strpos($content, 'App\Services') != false) {
            $dest = "$modulePath/Services/$filename";
            $content = str_replace('App\\Services', "Modules\\$moduleName\\Services", $content);
        }

        // 8. Events
        elseif (strpos($content, 'App\Events') != false) {
            $dest = "$modulePath/Events/$filename";
            $content = str_replace('App\\Events', "Modules\\$moduleName\\Events", $content);
        }

        // 9. Notifications
        elseif (strpos($content, 'App\\Notifications') != false || preg_match('/class\s+\w+\s+extends\s+Notification/', $content)) {
            $dest = "$modulePath/Notifications/$filename";
            $content = str_replace('App\\Notifications', "Modules\\$moduleName\\Notifications", $content);
        }

        // 10. Mails (Mailable classes)
        elseif (strpos($content, 'App\\Mail') != false || preg_match('/class\s+\w+\s+extends\s+Mailable/', $content)) {
            $dest = "$modulePath/Mail/$filename";
            $content = str_replace('App\\Mail', "Modules\\$moduleName\\Mail", $content);
        }

        // 11. Traits
        elseif (strpos($content, 'App\\Traits') !== false) {
            $dest = "$modulePath/Traits/$filename";
            $content = str_replace('App\\Traits', "Modules\\$moduleName\\Traits", $content);
        }

        // 12. Jobs
        elseif (strpos($content, 'App\\Jobs') !== false) {
            $dest = "$modulePath/Jobs/$filename";
            $content = str_replace('App\\Jobs', "Modules\\$moduleName\\Jobs", $content);
        }

        // 13. Listeners
        elseif (strpos($content, 'App\\Listeners') !== false) {
            $dest = "$modulePath/Listeners/$filename";
            $content = str_replace('App\\Listeners', "Modules\\$moduleName\\Listeners", $content);
        }

        // 14. Exceptions
        elseif (strpos($content, 'App\\Exceptions') !== false) {
            $dest = "$modulePath/Exceptions/$filename";
            $content = str_replace('App\\Exceptions', "Modules\\$moduleName\\Exceptions", $content);
        }

        // 15. Rules (Custom validation rules)
        elseif (strpos($content, 'App\\Rules') !== false) {
            $dest = "$modulePath/Rules/$filename";
            $content = str_replace('App\\Rules', "Modules\\$moduleName\\Rules", $content);
        }

        // 16. Policies
        elseif (strpos($content, 'App\\Policies') !== false) {
            $dest = "$modulePath/Policies/$filename";
            $content = str_replace('App\\Policies', "Modules\\$moduleName\\Policies", $content);
        }

        // 17. Resources
        elseif (strpos($content, 'App\\Http\\Resources') !== false) {
            $dest = "$modulePath/Http/Resources/$filename";
            $content = str_replace('App\\Http\\Resources', "Modules\\$moduleName\\Http\\Resources", $content);
        }

        // 18. View Components
        elseif (strpos($content, 'App\\View\\Components') !== false) {
            $dest = "$modulePath/View/Components/$filename";
            $content = str_replace('App\\View\\Components', "Modules\\$moduleName\\View\\Components", $content);
        }

        // 19.  Commands
        elseif (strpos($content, 'App\\Console\\Commands') !== false) {
            $dest = "$modulePath/Console/Commands/$filename";
            $content = str_replace('App\\Console\\Commands', "Modules\\$moduleName\\Console\\Commands", $content);
        }

        // 20. Providers
        elseif (strpos($content, 'App\\Providers') !== false || preg_match('/class\s+\w+\s+extends\s+ServiceProvider/', $content)) {
            $dest = "$modulePath/Providers/$filename";
            $content = str_replace('App\\Providers', "Modules\\$moduleName\\Providers", $content);
        }

        // 21. Models
        elseif (strpos($content, 'App\\Models') != false || preg_match('/class\s+\w+\s+extends\s+Model/', $content)) {
            $dest = "$modulePath/Entities/$filename";
            $content = str_replace('App\\Models', "Modules\\$moduleName\\Entities", $content);
        }

        // 22. Views (including subfolders)
        elseif (str_contains($filename, '.blade.php') || str_contains($filename, 'blade copy.php')) {
            $viewName = str_replace('\\', '/', $relativePath);
            preg_match('/.*\/([^\/]+)\/(.+)/', $viewName, $matches);
            $subFolder = isset($matches[1]) ? $matches[1] : '';
            $file = isset($matches[2]) ? $matches[2] : $filename;
            $dest = "$modulePath/Resources/views/$subFolder/$file";
        }

        // 23. Assets ['css', 'js', 'scss', 'sass', 'images.*']
        elseif (in_array($extension, ['css'])) {
            $dest = "$modulePath/Resources/assets/css/$filename";
        } elseif (in_array($extension, ['js'])) {
            $dest = "$modulePath/Resources/assets/js/$filename";
        } elseif (in_array($extension, ['scss', 'sass'])) {
            $dest = "$modulePath/Resources/assets/sass/$filename";
        } elseif (in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
            $dest = "$modulePath/Resources/assets/images/$filename";
        }

        // 24. General files (ZIP, JSON, etc)
        elseif (in_array($extension, ['zip', 'json', 'env', 'txt', 'xml'])) {
            $dest = "$modulePath/Other/$filename";
        }

        // 25. Unclassified PHP files
        elseif ($extension == 'php') {
            $dest = "$modulePath/Other/$filename";
            $content = str_replace('App\\', "Modules\\$moduleName\\", $content);
            $content = str_replace('use App\\', "use Modules\\$moduleName\\", $content);
        }

        // 26. fallback
        else {
            echo "⚠️ Unknown file type: $filename\n";
            return;
        }

        // Confirm the existence of the path
        if (!$dest) {
            echo "❌ File path not specified: $filename\n";
            return;
        }

        if (!file_exists(dirname($dest))) {
            mkdir(dirname($dest), 0755, true);
        }

        if (in_array($extension, ['php', 'blade.php'])) {
            // 1. First, convert use App\Models\.. to use Modules\{ModuleName}\Entities\...
            $content = preg_replace(
                '/use\s+App\\\\Models\\\\(\w+);/',
                'use Modules\\' . $moduleName . '\\Entities\\\$1;',
                $content
            );

            // 2. convert App\Models\.. to Modules\{ModuleName}\Entities\...
            $content = preg_replace(
                '/\\\\App\\\\Models\\\\(\w+)/',
                '\\Modules\\' . $moduleName . '\\Entities\\\$1',
                $content
            );

            // 3. convert asset('assets/.. to asset('modules/{ModuleName}/...
            $content = preg_replace(
                '/assets\/(css|js|sass|images)\/([a-zA-Z0-9_\-\.\/]+)/',
                'modules/' . strtolower($moduleName) . '/$1/$2',
                $content
            );

            file_put_contents($dest, $content);
        } else {
            copy($sourceFile, $dest);
        }

        echo "✔️ copies: $filename → $dest" . '<br/>';
    }

    // Scan all files from all subfolders
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($targetFolder));
    $files = [];
    foreach ($rii as $file) {
        if ($file->isFile()) {
            $files[] = $file->getPathname();
        }
    }

    // Copy all files to the module
    foreach ($files as $file) {
        copyToModule($file, $destinationFolder, $moduleName);
    }

    /** 
     *  @ToDo from here is a display the folder and file tree.
     */

    function generateDirectoryStructure($directory, $moduleName, $maxDepth = 10)
    {

        if (!is_dir($directory)) {
            echo "Error: The specified folder does not exist.";
            return;
        }

        echo "<h4>Volume structure: " . htmlspecialchars($moduleName) . "</h4>";

        echo "<pre>";
        printDirectoryTree($directory, 0, $maxDepth);
        echo "</pre>";
    }

    function printDirectoryTree($dir, $indentLevel = 0, $maxDepth = 5, $currentDepth = 0)
    {
        if ($currentDepth > $maxDepth) {
            return;
        }

        $items = scandir($dir);

        foreach ($items as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            $indent = str_repeat("    ", $indentLevel);

            if (is_dir($path)) {
                echo $indent . "📁 " . $item . "/\n";
                printDirectoryTree($path, $indentLevel + 1, $maxDepth, $currentDepth + 1);
            } else {
                echo $indent . "📄 " . $item . "\n";
            }
        }
    }

    generateDirectoryStructure($destinationFolder, $moduleName, 10);

    echo "<p>تم نقل الملفات إلى Modules/<b>$moduleName</b> بنجاح. ✅🎉</p>";
    echo "<a href='./index.php' style='padding-bottom: 50px'>الرجوع!</a>";
} else {
    echo "<a href='./index.php'></a>";
    echo "<script>document.querySelector('a').click();</script>";
}
?>