<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-card {
            margin-top: 50px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .profile-header {
            background-color: #0d6efd;
            color: white;
            padding: 2rem;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .list-group-item {
            border: none;
            padding: 1rem 1.25rem;
        }
        .list-group-item strong {
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card profile-card">
                    <div class="profile-header text-center">
                        <h1 class="display-5">{{ $name }}</h1>
                        <p class="lead">{{ $future_goal }}</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info text-center">
                            <h4 class="alert-heading">Pesan Semangat!</h4>
                            <p class="mb-0">{{ $pesan_semangat }}</p>
                        </div>
                        
                        <ul class="list-group list-group-flush mt-4">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Umur</strong>
                                <span>{{ $my_age }} tahun</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Semester Saat Ini</strong>
                                <span>{{ $current_semester }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Target Wisuda</strong>
                                <span>{{ $tgl_harus_wisuda }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Sisa Waktu Belajar</strong>
                                <span class="badge bg-primary rounded-pill">{{ $time_to_study_left }}</span>
                            </li>
                        </ul>

                        <h4 class="mt-4">Hobi</h4>
                        <ul class="list-group">
                            @foreach ($hobbies as $hobi)
                                <li class="list-group-item">{{ $hobi }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>