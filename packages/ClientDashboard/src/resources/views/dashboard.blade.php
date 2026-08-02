@extends('clientdashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-[#2C2416]">Client Overview</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back, {{ session('client_profile.username', 'Client User') }}. Here's a summary of your mental wellness journey.</p>
        </div>
        <div>
            <button onclick="openProfileModal()" class="px-4 py-2 bg-[#C4A840] text-white rounded-lg hover:bg-[#b09638] transition font-bold shadow-sm">
                <i class="bi bi-pencil-square"></i> Edit Profile
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-[#C4A840]/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-[#C4A840]/10 text-[#C4A840]">
                <i class="bi bi-clipboard-check text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Assessments Done</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-[#2C2416] mt-1">0</h3>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-[#C4A840]/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-[#C4A840]/10 text-[#C4A840]">
                <i class="bi bi-book text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Modules Finished</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-[#2C2416] mt-1">0</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-[#C4A840]/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-[#7B6B35]/10 text-[#7B6B35]">
                <i class="bi bi-calendar-check text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Next Appointment</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-[#2C2416] mt-1">0</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-[#C4A840]/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-[#7B6B35]/10 text-[#7B6B35]">
                <i class="bi bi-bell text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Notifications</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-[#2C2416] mt-1">0</h3>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-[#C4A840]/20 shadow-sm">
            <h2 class="text-lg font-bold text-[#2C2416] mb-6">My Assessment Score Trend</h2>
            <div class="h-72 w-full flex items-center justify-center">
                <div class="text-center text-gray-400">
                    <i class="bi bi-bar-chart text-5xl"></i>
                    <p class="text-sm mt-2">No assessment data available yet.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-[#C4A840]/20 shadow-sm">
            <h2 class="text-lg font-bold text-[#2C2416] mb-6">My Platform Activity (Hours / Week)</h2>
            <div class="h-72 w-full flex items-center justify-center">
                <div class="text-center text-gray-400">
                    <i class="bi bi-graph-up text-5xl"></i>
                    <p class="text-sm mt-2">No activity data available yet.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities Table -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-[#C4A840]/20 shadow-sm">
        <h2 class="text-lg font-bold text-[#2C2416] mb-4 sm:mb-6">My Recent Activities</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-[#F5EFE0] text-[#7B6B35] font-bold border-b border-[#C4A840]/20 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">Date</th>
                        <th class="px-4 py-3">Activity Type</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="4" class="px-4 py-12 text-center text-gray-400">
                            <i class="bi bi-inbox text-4xl block mb-2"></i>
                            No activities recorded yet. Start exploring assessments or learning modules.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="profileModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(44,36,22,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter:blur(4px);">
    <div style="background:#fff; width:90%; max-width:400px; border-radius:12px; padding:2rem; position:relative; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
        <button onclick="closeProfileModal()" style="position:absolute; top:15px; right:15px; background:none; border:none; font-size:1.2rem; cursor:pointer; color:#6c757d;"><i class="bi bi-x-lg"></i></button>
        
        <h3 style="font-size:1.25rem; font-weight:700; color:#2c2416; margin-bottom:1.5rem; font-family:'IM Fell English',serif;">Edit Profile</h3>
        
        <form action="/client/profile" method="POST">
            @csrf
            <div style="margin-bottom:1.2rem;">
                <label style="display:block; font-weight:600; font-size:0.9rem; margin-bottom:0.5rem; color:#2c2416;">Username</label>
                <input type="text" name="username" value="{{ session('client_profile.username', 'Client User') }}" required style="width:100%; padding:0.75rem; border:1px solid #ddd; border-radius:6px;">
            </div>
            
            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-weight:600; font-size:0.9rem; margin-bottom:0.5rem; color:#2c2416;">Profile Icon</label>
                <select name="picture" style="width:100%; padding:0.75rem; border:1px solid #ddd; border-radius:6px; background:#fff;">
                    <option value="bi-person-circle" {{ session('client_profile.picture') === 'bi-person-circle' ? 'selected' : '' }}>Circle Person</option>
                    <option value="bi-person-square" {{ session('client_profile.picture') === 'bi-person-square' ? 'selected' : '' }}>Square Person</option>
                    <option value="bi-emoji-smile" {{ session('client_profile.picture') === 'bi-emoji-smile' ? 'selected' : '' }}>Smiley</option>
                    <option value="bi-emoji-sunglasses" {{ session('client_profile.picture') === 'bi-emoji-sunglasses' ? 'selected' : '' }}>Sunglasses</option>
                </select>
            </div>
            
            <button type="submit" style="width:100%; padding:0.75rem; background:#c4a840; color:#fff; border:none; border-radius:6px; font-weight:600; cursor:pointer;">Save Changes</button>
        </form>
    </div>
</div>

<script>
    function openProfileModal() { document.getElementById('profileModal').style.display = 'flex'; }
    function closeProfileModal() { document.getElementById('profileModal').style.display = 'none'; }
</script>
@endsection
