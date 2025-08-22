<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('storage:fix', function () {
    $this->info('🔍 Diagnosing storage issues...');
    
    // Check storage link
    $this->info('📁 Checking storage link...');
    $publicStorage = public_path('storage');
    $storageApp = storage_path('app/public');
    
    if (!file_exists($publicStorage)) {
        $this->warn('❌ Storage link does not exist');
        if ($this->confirm('Create storage link?')) {
            try {
                $this->call('storage:link');
                $this->info('✅ Storage link created successfully');
            } catch (\Exception $e) {
                $this->error('❌ Failed to create storage link: ' . $e->getMessage());
            }
        }
    } else {
        $this->info('✅ Storage link exists');
        if (is_link($publicStorage)) {
            $target = readlink($publicStorage);
            $this->info("   → Points to: {$target}");
        }
    }
    
    // Check directories
    $this->info('📂 Checking storage directories...');
    $directories = [
        storage_path('app'),
        storage_path('app/public'),
        storage_path('app/public/creative-posts'),
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            $this->warn("❌ Directory missing: {$dir}");
            if ($this->confirm("Create directory: {$dir}?")) {
                try {
                    mkdir($dir, 0755, true);
                    $this->info("✅ Created directory: {$dir}");
                } catch (\Exception $e) {
                    $this->error("❌ Failed to create directory: {$dir} - " . $e->getMessage());
                }
            }
        } else {
            $this->info("✅ Directory exists: {$dir}");
        }
    }
    
    // Check permissions
    $this->info('🔐 Checking permissions...');
    foreach ($directories as $path) {
        if (is_dir($path)) {
            $writable = is_writable($path);
            $permissions = substr(sprintf('%o', fileperms($path)), -4);
            
            if ($writable) {
                $this->info("✅ {$path} - Writable (Permissions: {$permissions})");
            } else {
                $this->warn("⚠️ {$path} - Not writable (Permissions: {$permissions})");
                if ($this->confirm("Fix permissions for {$path}?")) {
                    try {
                        chmod($path, 0755);
                        $this->info("✅ Fixed permissions for {$path}");
                    } catch (\Exception $e) {
                        $this->error("❌ Failed to fix permissions: " . $e->getMessage());
                    }
                }
            }
        }
    }
    
    $this->info('✅ Storage diagnosis complete!');
})->purpose('Diagnose and fix common storage issues');

Artisan::command('storage:unlink', function () {
    $this->info('🔗 Removing storage link...');
    
    $publicStorage = public_path('storage');
    
    if (file_exists($publicStorage)) {
        if (is_link($publicStorage)) {
            unlink($publicStorage);
            $this->info('✅ Storage link removed');
        } else {
            $this->warn('⚠️ Storage path exists but is not a link');
            if ($this->confirm('Remove the storage directory?')) {
                $this->call('storage:clear');
                $this->info('✅ Storage directory removed');
            }
        }
    } else {
        $this->info('✅ Storage link does not exist');
    }
})->purpose('Remove storage link');

Artisan::command('storage:clear', function () {
    $this->info('🗑️ Clearing storage directory...');
    
    $publicStorage = public_path('storage');
    
    if (is_dir($publicStorage)) {
        $this->call('storage:unlink');
        $this->info('✅ Storage cleared');
    } else {
        $this->info('✅ Storage directory does not exist');
    }
})->purpose('Clear storage directory');

Artisan::command('upload:setup', function () {
    $this->info('🚀 Setting up bulletproof upload system...');
    
    // Force create storage link
    try {
        $publicStorage = public_path('storage');
        if (file_exists($publicStorage)) {
            if (is_link($publicStorage)) {
                unlink($publicStorage);
            } else {
                $this->call('storage:unlink');
            }
        }
        
        $this->call('storage:link');
        $this->info('✅ Storage link created');
    } catch (\Exception $e) {
        $this->error('❌ Failed to create storage link: ' . $e->getMessage());
    }
    
    // Create upload directory
    $uploadDir = storage_path('app/public/creative-posts');
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
        $this->info('✅ Upload directory created');
    } else {
        $this->info('✅ Upload directory exists');
    }
    
    // Set permissions
    chmod($uploadDir, 0755);
    chmod(storage_path('app/public'), 0755);
    chmod(storage_path('app'), 0755);
    $this->info('✅ Permissions set correctly');
    
    // Test file operations
    $testFile = $uploadDir . '/test.txt';
    $testContent = 'Test file created at ' . now();
    
    try {
        file_put_contents($testFile, $testContent);
        $readContent = file_get_contents($testFile);
        
        if ($readContent === $testContent) {
            $this->info('✅ File write/read test passed');
        } else {
            $this->warn('⚠️ File content mismatch');
        }
        
        unlink($testFile);
        $this->info('✅ File delete test passed');
        
    } catch (\Exception $e) {
        $this->error('❌ File operation test failed: ' . $e->getMessage());
    }
    
    $this->info('🎉 Upload system setup complete!');
    $this->info('📁 Upload directory: ' . $uploadDir);
    $this->info('🔗 Public access: ' . public_path('storage/creative-posts'));
})->purpose('Set up bulletproof upload system');
