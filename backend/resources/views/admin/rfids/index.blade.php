@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manajemen RFID</h1>
        <p class="text-gray-600 mt-2">Pantau dan kelola pemindaian kartu RFID secara langsung</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Freshness Indicator Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Status Data</h2>
            <div id="freshness-indicator" class="text-center">
                <p class="text-2xl font-bold text-blue-600">-</p>
                <p class="text-sm text-gray-500 mt-2">Menunggu pemindaian pertama...</p>
            </div>
        </div>

        <!-- Waiting for Scan Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Status</h2>
            <div id="scan-status" class="flex items-center space-x-3">
                <div class="h-4 w-4 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-green-600 font-medium">Pemantauan Aktif</span>
            </div>
        </div>

        <!-- Error Warning Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Koneksi</h2>
            <div id="poll-warning" class="hidden bg-red-50 border border-red-200 rounded p-4">
                <p class="text-red-700 text-sm font-medium">Koneksi Bermasalah</p>
                <p class="text-red-600 text-xs mt-1">Mencoba menyambung ulang secara otomatis...</p>
            </div>
            <div id="poll-success" class="bg-green-50 border border-green-200 rounded p-4">
                <p class="text-green-700 text-sm font-medium">Terhubung</p>
                <p class="text-green-600 text-xs mt-1">Menerima pembaruan secara langsung</p>
            </div>
        </div>
    </div>

    <!-- RFID Scans Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Riwayat Pemindaian RFID</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">RFID UID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">User ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Terakhir Dipindai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="rfid-table-body" class="divide-y divide-gray-200">
                    <!-- Populated by polling script -->
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 text-sm">
                            <span class="inline-block">Memuat data awal...</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // CSRF Token helper
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    // Update RFID table with latest scans
    function updateRfidTable(scans) {
        const tableBody = document.getElementById('rfid-table-body');
        
        if (!scans || scans.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500 text-sm">No scans recorded yet</td></tr>';
            return;
        }

        tableBody.innerHTML = scans.map(scan => {
            const lastScannedDate = new Date(scan.last_scanned_at);
            const lastScanned = lastScannedDate.toLocaleString();
            const statusColor = scan.status === 'assigned' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
            
            return `
                <tr data-rfid-id="${scan.id}" class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${scan.uid}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium ${statusColor}">
                            ${scan.status.charAt(0).toUpperCase() + scan.status.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${scan.user_id ? scan.user_id : '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${lastScanned}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="/admin/rfids/${scan.id}" class="text-blue-600 hover:text-blue-900 font-medium">View</a>
                    </td>
                </tr>
            `;
        }).join('');

        // Highlight new rows
        document.querySelectorAll('tr[data-rfid-id]').forEach(row => {
            if (!row.classList.contains('highlighted')) {
                row.classList.add('bg-yellow-100', 'animate-pulse', 'highlighted');
                setTimeout(() => {
                    row.classList.remove('bg-yellow-100', 'animate-pulse');
                }, 2000);
            }
        });
    }

    // Update freshness indicator
    function updateFreshnessIndicator(lastScannedAt) {
        const indicator = document.getElementById('freshness-indicator');
        
        if (!lastScannedAt) {
            indicator.innerHTML = '<p class="text-2xl font-bold text-gray-400">-</p><p class="text-sm text-gray-500 mt-2">No scans yet</p>';
            return;
        }

        const lastScannedDate = new Date(lastScannedAt);
        const now = new Date();
        const secondsAgo = Math.floor((now - lastScannedDate) / 1000);

        let timeString = '';
        if (secondsAgo < 60) {
            timeString = `${secondsAgo}s ago`;
        } else if (secondsAgo < 3600) {
            timeString = `${Math.floor(secondsAgo / 60)}m ago`;
        } else {
            timeString = `${Math.floor(secondsAgo / 3600)}h ago`;
        }

        const freshColor = secondsAgo < 30 ? 'text-green-600' : secondsAgo < 120 ? 'text-yellow-600' : 'text-red-600';
        
        indicator.innerHTML = `
            <p class="text-2xl font-bold ${freshColor}">${timeString}</p>
            <p class="text-sm text-gray-500 mt-2">Last scanned: ${lastScannedDate.toLocaleString()}</p>
        `;
    }

    // Poll admin RFID status
    function pollAdminRfidStatus() {
        const csrfToken = getCsrfToken();
        const errorDiv = document.getElementById('poll-warning');
        const successDiv = document.getElementById('poll-success');

        fetch('/api/admin/rfid/status', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Poll failed with status ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Hide error, show success
            if (errorDiv) errorDiv.classList.add('hidden');
            if (successDiv) successDiv.classList.remove('hidden');

            // Update table and indicator
            if (data.scans && data.scans.length > 0) {
                updateRfidTable(data.scans);
                updateFreshnessIndicator(data.scans[0].last_scanned_at);
            }
        })
        .catch(error => {
            console.warn('Poll error:', error);
            
            // Show error div
            if (errorDiv) {
                errorDiv.classList.remove('hidden');
            }
            if (successDiv) {
                successDiv.classList.add('hidden');
            }
        });
    }

    // Initialize polling on page load
    document.addEventListener('DOMContentLoaded', () => {
        // Initial poll
        pollAdminRfidStatus();
        
        // Set up interval for continuous polling (2500ms = 2.5 seconds)
        setInterval(pollAdminRfidStatus, 2500);
    });
</script>
@endpush
