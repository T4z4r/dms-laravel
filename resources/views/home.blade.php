@extends('layouts.backend')

@section('content')
    <div class="content">
        <!-- Hero Section -->
        <div class="mb-4">
            <h2 class="fw-bold text-main">Dashboard</h2>
            <p class="text-muted mb-0">Welcome back! Here's an overview of your Document Management System.</p>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                        <h5 class="card-title">Total Files</h5>
                        <h3>{{ App\Models\File::count() }}</h3>
                        <small>Files uploaded</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                        <h5 class="card-title">Departments</h5>
                        <h3>{{ App\Models\Department::count() }}</h3>
                        <small>Active departments</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-tags fa-2x"></i>
                        </div>
                        <h5 class="card-title">Categories</h5>
                        <h3>{{ App\Models\FileCategory::count() }}</h3>
                        <small>File categories</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-signature fa-2x"></i>
                        </div>
                        <h5 class="card-title">Signed Documents</h5>
                        <h3>{{ App\Models\Signature::count() }}</h3>
                        <small>Documents signed</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
                        </h5>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('files.index') }}" class="btn btn-primary btn-main">
                                <i class="fas fa-file-alt me-2"></i>Manage Files
                            </a>
                            <a href="{{ route('departments.index') }}" class="btn btn-success">
                                <i class="fas fa-building me-2"></i>Departments
                            </a>
                            <a href="{{ route('categories.index') }}" class="btn btn-info">
                                <i class="fas fa-tags me-2"></i>Categories
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-chart-bar me-2 text-primary"></i>Recent Activity
                        </h5>
                        <p class="text-muted">Recent file uploads and activities will appear here.</p>
                        <a href="{{ route('files.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>View All Files
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-chart-bar me-2 text-primary"></i>Files per Department
                        </h5>
                        <canvas id="departmentChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-line-chart me-2 text-primary"></i>File Upload Trends
                        </h5>
                        <canvas id="trendChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-pie-chart me-2 text-primary"></i>Files per Category
                        </h5>
                        <canvas id="categoryChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Additional chart or content can go here -->
            </div>
        </div>

        <!-- Recent Files Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-clock me-2 text-primary"></i>Recent Files
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>File Name</th>
                                <th>Uploaded By</th>
                                <th>Department</th>
                                <th>Category</th>
                                <th>Uploaded At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $recentFiles = App\Models\File::with('uploader', 'department', 'category')->latest()->limit(5)->get();
                            @endphp
                            @forelse ($recentFiles as $file)
                                <tr>
                                    <td>{{ $file->original_name }}</td>
                                    <td>{{ $file->uploader->name ?? 'System' }}</td>
                                    <td>{{ $file->department->name ?? 'N/A' }}</td>
                                    <td>{{ $file->category->name ?? 'N/A' }}</td>
                                    <td>{{ $file->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <a href="{{ route('files.view', $file) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No files uploaded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="{{ route('files.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>View All Files
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Files per Department - Bar Chart
        const departmentCtx = document.getElementById('departmentChart').getContext('2d');
        const departmentData = @json($filesPerDepartment);
        const departmentLabels = departmentData.map(item => item.department);
        const departmentCounts = departmentData.map(item => item.count);

        new Chart(departmentCtx, {
            type: 'bar',
            data: {
                labels: departmentLabels,
                datasets: [{
                    label: 'Files',
                    data: departmentCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // File Upload Trends - Line Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const trendData = @json($filesOverTime);
        const trendLabels = trendData.map(item => item.month);
        const trendCounts = trendData.map(item => item.count);

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Uploads',
                    data: trendCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Files per Category - Pie Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryData = @json($filesPerCategory);
        const categoryLabels = categoryData.map(item => item.category);
        const categoryCounts = categoryData.map(item => item.count);

        new Chart(categoryCtx, {
            type: 'pie',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryCounts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 205, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
@endsection
