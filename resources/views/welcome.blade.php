<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Upload Task</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            .container {
                margin-top: 5%;
                display: flex;
                justify-content: center;
            }
        
            table {
                width: 100%;
                border-collapse: collapse;
            }
        
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
        
            th {
                background-color: #f2f2f2;
            }
        
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
        
            tr:hover {
                background-color: #ddd;
            }

            .custom-file-upload {
                background-color: #008CBA;
                color: white;
                padding: 10px 15px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                display: inline-block;
    }

    /* Hide the default file input */
    input[type="file"] {
        display: none;
    }
        </style>
        
    </head>
    <body class="antialiased">
        <div class="container mt-3">
            <div class="card" id="drop-area">
                <div class="card-body">
                    <form action="{{ route('upload.file') }}" method="POST" enctype="multipart/form-data" id="upload-form">
                        @csrf
                        <!-- Hide the file input visually -->
                        <input type="file" name="upload_file" id="file-upload" style="display: none;">
        
                        <!-- Style the label like a button -->
                        <label for="file-upload" id="file-drag" >
                            Select file/Drag and Drop
                        </label>
        
                        <!-- Add a submit button -->
                        <button style="float: right;" type="submit">Upload File</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="container mt-5">  
            <div class="card-body">     
        <table>
            <thead>
                <tr>
                    <th>Upload Time</th>
                    <th>File Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($uploadHistory as $upload)
                    <tr>
                        <td>{{ $upload->created_at }} <br>
                            <span id="time-diff">{{ $upload->created_at->diffForHumans() }}</span>
                        </td>
                        <td>{{ $upload->name}}</td>
                        <td>{{ $upload->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updateTimeDifference() {
        var timeDiffElement = $('#time-diff');
        if (timeDiffElement.length > 0) {
            var timestampString = timeDiffElement.data('timestamp');
            var createdTime = new Date(timestampString);
            var currentTime = new Date();

            // Subtract 8 hours from the createdTime
            createdTime.setHours(createdTime.getHours() - 8);

            var timeDifference = Math.floor((currentTime - createdTime) / 1000); // in seconds

            if (timeDifference < 60) {
                timeDiffElement.text('Just now');
            } else if (timeDifference < 3600) {
                timeDiffElement.text(Math.floor(timeDifference / 60) + ' minutes ago');
            } else {
                timeDiffElement.text(Math.floor(timeDifference / 3600) + ' hours ago');
            }
        }
    }

    // Update the time difference initially
    updateTimeDifference();

    // Update the time difference every second (adjust the interval as needed)
    setInterval(updateTimeDifference, 1000);
</script>
        <script>
            const dropArea = document.getElementById('drop-area');
            const fileUpload = document.getElementById('file-upload');
            
            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });
            
            // Highlight drop area when a file is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });
            
            // Remove highlighting when a file is dragged away
            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });
            
            // Handle dropped files
            dropArea.addEventListener('drop', handleDrop, false);
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            function highlight() {
                dropArea.classList.add('highlight');
            }
            
            function unhighlight() {
                dropArea.classList.remove('highlight');
            }
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
            
                handleFiles(files);
            }
            
            function handleFiles(files) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    // You can display the selected file names here
                    console.log('Selected file:', file.name);
                }
            }
            
            // Trigger file input click when card is clicked
            dropArea.addEventListener('click', () => {
                fileUpload.click();
            });
            
            // Handle file selection through the input
            fileUpload.addEventListener('change', () => {
                handleFiles(fileUpload.files);
                
            });
        </script>
    </body>
</html>
