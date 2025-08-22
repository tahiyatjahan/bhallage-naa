<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">🔍 File Upload Debugging</h1>
        
        <!-- System Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">📋 System Information</h2>
            <div id="system-info" class="space-y-2 text-sm">
                <p>Loading system information...</p>
            </div>
        </div>
        
        <!-- Test Upload Form -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">📤 Test File Upload</h2>
            <form id="test-form" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select a test file:</label>
                    <input type="file" name="test_file" id="test_file" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Test Upload
                </button>
            </form>
            <div id="upload-result" class="mt-4 p-4 bg-gray-50 rounded-lg hidden">
                <h3 class="font-semibold mb-2">Upload Result:</h3>
                <pre id="result-content" class="text-sm text-gray-700"></pre>
            </div>
        </div>
        
        <!-- Manual Directory Check -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">📁 Directory Status</h2>
            <div id="directory-status" class="space-y-2 text-sm">
                <p>Checking directory status...</p>
            </div>
        </div>
    </div>

    <script>
        // Load system information
        fetch('/test-upload-api')
            .then(response => response.json())
            .then(data => {
                const systemInfo = document.getElementById('system-info');
                systemInfo.innerHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p><strong>PHP Version:</strong> ${data.php_version}</p>
                            <p><strong>Upload Max Filesize:</strong> ${data.upload_max_filesize}</p>
                            <p><strong>Post Max Size:</strong> ${data.post_max_size}</p>
                            <p><strong>Max Execution Time:</strong> ${data.max_execution_time}s</p>
                            <p><strong>Memory Limit:</strong> ${data.memory_limit}</p>
                        </div>
                        <div>
                            <p><strong>Storage Path:</strong> ${data.storage_path}</p>
                            <p><strong>Public Path:</strong> ${data.public_path}</p>
                            <p><strong>Storage Link Exists:</strong> ${data.storage_link_exists ? '✅ Yes' : '❌ No'}</p>
                            <p><strong>Storage Writable:</strong> ${data.storage_writable ? '✅ Yes' : '❌ No'}</p>
                            <p><strong>Creative Posts Dir:</strong> ${data.creative_posts_dir_exists ? '✅ Yes' : '❌ No'}</p>
                            <p><strong>Creative Posts Writable:</strong> ${data.creative_posts_dir_writable ? '✅ Yes' : '❌ No'}</p>
                        </div>
                    </div>
                `;
            })
            .catch(error => {
                document.getElementById('system-info').innerHTML = `<p class="text-red-600">Error loading system info: ${error.message}</p>`;
            });

        // Test upload form
        document.getElementById('test-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            const fileInput = document.getElementById('test_file');
            
            if (fileInput.files.length === 0) {
                alert('Please select a file first');
                return;
            }
            
            formData.append('test_file', fileInput.files[0]);
            formData.append('_token', '{{ csrf_token() }}');
            
            const resultDiv = document.getElementById('upload-result');
            const resultContent = document.getElementById('result-content');
            
            resultDiv.classList.remove('hidden');
            resultContent.textContent = 'Uploading...';
            
            fetch('/test-upload', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                resultContent.textContent = JSON.stringify(data, null, 2);
            })
            .catch(error => {
                resultContent.textContent = `Error: ${error.message}`;
            });
        });

        // Check directory status
        function checkDirectories() {
            const statusDiv = document.getElementById('directory-status');
            statusDiv.innerHTML = `
                <p><strong>Checking directories...</strong></p>
                <p>• Storage: ${storage_path}</p>
                <p>• Public: ${public_path}</p>
                <p>• Storage Link: ${storage_link_exists ? '✅' : '❌'}</p>
            `;
        }
    </script>
</body>
</html>
