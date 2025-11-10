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
                        <h3>{{ $totalFiles }}</h3>
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
                        <h3>{{ $totalDepartments }}</h3>
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
                        <h3>{{ $totalCategories }}</h3>
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
                        <h3>{{ $totalSignatures }}</h3>
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
                            <a href="{{ route('departments.index') }}" class="btn btn-success text-light">
                                <i class="fas fa-building me-2"></i>Departments
                            </a>
                            <a href="{{ route('categories.index') }}" class="btn btn-info text-light">
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
                        <div id="departmentChart" style="width:100%; height:200px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-line-chart me-2 text-primary"></i>File Upload Trends
                        </h5>
                        <div id="trendChart" style="width:100%; height:200px;"></div>
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
                        <div id="categoryChart" style="width:100%; height:200px;"></div>
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
        const departmentData = @json($filesPerDepartment);
        const departmentLabels = departmentData.map(item => item.department);
        const departmentCounts = departmentData.map(item => item.count);

        Highcharts.chart('departmentChart', {
            chart: { type: 'bar' },
            title: { text: 'Files per Department' },
            xAxis: { categories: departmentLabels },
            yAxis: { title: { text: 'Number of Files' }, min: 0 },
            series: [{ name: 'Files', data: departmentCounts }]
        });

        // File Upload Trends - Line Chart
        const trendData = @json($filesOverTime);
        const trendLabels = trendData.map(item => item.month);
        const trendCounts = trendData.map(item => item.count);

        Highcharts.chart('trendChart', {
            chart: { type: 'line' },
            title: { text: 'File Upload Trends' },
            xAxis: { categories: trendLabels },
            yAxis: { title: { text: 'Uploads' }, min: 0 },
            series: [{ name: 'Uploads', data: trendCounts }]
        });

        // Files per Category - Pie Chart
        const categoryData = @json($filesPerCategory);
        const categoryLabels = categoryData.map(item => item.category);
        const categoryCounts = categoryData.map(item => item.count);

        Highcharts.chart('categoryChart', {
            chart: { type: 'pie' },
            title: { text: 'Files per Category' },
            series: [{
                name: 'Files',
                data: categoryLabels.map((label, index) => ({ name: label, y: categoryCounts[index] }))
            }]
        });
    </script>
@endsection
