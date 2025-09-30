<?php
// app/views/admin/users/list.php
$basePath = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($basePath === '/' || $basePath === '\\') { $basePath = ''; }
?>

<!-- Page header with primary action -->
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl md:text-2xl font-bold text-gray-900"><?= __('admin.users.list_title') ?></h2>
    <a href="<?= $basePath ?>/admin/users/add" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium shadow-sm ring-1 ring-blue-700/10">
        <i class="ri-user-add-line"></i>
        <?= __('admin.users.add_user') ?>
    </a>
</div>

<!-- Enhanced Stats cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
    <!-- Total Users Card -->
    <div class="stats-card group bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-lg hover:border-blue-300 transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-500/10 to-blue-600/5 rounded-full -mr-10 -mt-10"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-4">
                <div class="icon-container w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="ri-user-line text-white text-2xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-gray-900 mb-1"><?= isset($counts['total']) ? (int)$counts['total'] : 0 ?></p>
                    <p class="text-sm font-medium text-gray-600"><?= __('admin.users.total_users') ?></p>
                </div>
            </div>
            <div class="flex items-center text-xs text-blue-600 font-medium">
                <i class="ri-arrow-up-line ml-1"></i>
                <span>+2.5% <?= __('admin.users.growth_last_month') ?></span>
            </div>
        </div>
    </div>

    <!-- Active Users Card -->
    <div class="stats-card group bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-lg hover:border-green-300 transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-500/10 to-green-600/5 rounded-full -mr-10 -mt-10"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-4">
                <div class="icon-container w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="ri-user-check-line text-white text-2xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-gray-900 mb-1"><?= isset($counts['active']) ? (int)$counts['active'] : 0 ?></p>
                    <p class="text-sm font-medium text-gray-600"><?= __('admin.users.active_users') ?></p>
                </div>
            </div>
            <div class="flex items-center text-xs text-green-600 font-medium">
                <i class="ri-arrow-up-line ml-1"></i>
                <span><?= isset($counts['total']) && $counts['total'] > 0 ? round((int)($counts['active'] ?? 0) / (int)$counts['total'] * 100, 1) : 0 ?>% <?= __('admin.users.activity_rate') ?></span>
            </div>
        </div>
    </div>

    <!-- New Users Card -->
    <div class="stats-card group bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-lg hover:border-yellow-300 transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-yellow-500/10 to-yellow-600/5 rounded-full -mr-10 -mt-10"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-4">
                <div class="icon-container w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="ri-user-add-line text-white text-2xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-gray-900 mb-1"><?= isset($counts['new30']) ? (int)$counts['new30'] : 0 ?></p>
                    <p class="text-sm font-medium text-gray-600"><?= __('admin.users.new_users_30_days') ?></p>
                </div>
            </div>
            <div class="flex items-center text-xs text-yellow-600 font-medium">
                <i class="ri-calendar-line ml-1"></i>
                <span><?= __('admin.users.last_30_days') ?></span>
            </div>
        </div>
    </div>

    <!-- Banned Users Card -->
    <div class="stats-card group bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-lg hover:border-red-300 transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-red-500/10 to-red-600/5 rounded-full -mr-10 -mt-10"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-4">
                <div class="icon-container w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="ri-user-forbid-line text-white text-2xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-gray-900 mb-1"><?= isset($counts['banned']) ? (int)$counts['banned'] : 0 ?></p>
                    <p class="text-sm font-medium text-gray-600"><?= __('admin.users.banned_users') ?></p>
                </div>
            </div>
            <div class="flex items-center text-xs text-red-600 font-medium">
                <i class="ri-shield-cross-line ml-1"></i>
                <span><?= isset($counts['total']) && $counts['total'] > 0 ? round((int)($counts['banned'] ?? 0) / (int)$counts['total'] * 100, 1) : 0 ?>% <?= __('admin.users.of_total') ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Bar -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="text" placeholder="<?= __('admin.users.search_placeholder') ?>" class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                <i class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                <option value=""><?= __('admin.users.all_roles') ?></option>
                <option value="1"><?= __('admin.users.roles.employee') ?></option>
                <option value="2"><?= __('admin.users.roles.awareness_manager') ?></option>
                <option value="3"><?= __('admin.users.roles.administrator') ?></option>
            </select>
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                <option value=""><?= __('admin.users.all_statuses') ?></option>
                <option value="active"><?= __('admin.users.statuses.active') ?></option>
                <option value="inactive"><?= __('admin.users.statuses.inactive') ?></option>
                <option value="pending"><?= __('admin.users.statuses.pending') ?></option>
                <option value="banned"><?= __('admin.users.statuses.banned') ?></option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors text-sm flex items-center gap-2">
                <i class="ri-download-line"></i>
                <?= __('admin.users.export') ?>
            </button>
            <button class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors text-sm flex items-center gap-2">
                <i class="ri-filter-line"></i>
                <?= __('admin.users.advanced_filter') ?>
            </button>
        </div>
    </div>
</div>

<!-- Users table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900"><?= __('admin.users.users_list') ?></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('admin.users.user') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('admin.users.role') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('admin.users.join_date') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('admin.users.status') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('admin.users.actions') ?></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <?php $initial = function_exists('mb_substr') ? mb_substr($u['name'] ?? '؟', 0, 1, 'UTF-8') : substr($u['name'] ?? '?', 0, 1); ?>
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium ml-3"><?= htmlspecialchars($initial) ?></div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($u['name'] ?? '') ?></div>
                                        <div class="text-sm text-gray-500"><?= htmlspecialchars($u['email'] ?? '') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($u['role'] ?? '—') ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars(substr((string)($u['created_at'] ?? ''), 0, 10)) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                    $status = $u['status'] ?? 'inactive';
                                    $map = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'inactive' => 'bg-yellow-100 text-yellow-800',
                                        'banned' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $cls = $map[$status] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $cls ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <a href="<?= $basePath ?>/admin/users/view?id=<?= $u['id'] ?>" class="text-primary-600 hover:text-primary-900 p-1" title="<?= __('admin.users.view') ?>">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="<?= $basePath ?>/admin/users/edit?id=<?= $u['id'] ?>" class="text-gray-600 hover:text-gray-900 p-1" title="<?= __('admin.users.edit') ?>">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    <button onclick="deleteUser(<?= $u['id'] ?>, '<?= htmlspecialchars($u['name'] ?? '', ENT_QUOTES) ?>')" class="text-red-600 hover:text-red-900 p-1" title="<?= __('admin.users.delete') ?>">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="px-6 py-6 text-center text-sm text-gray-500" colspan="6">
                            <?= __('admin.users.no_data') ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                <?= __('admin.users.showing') ?> <span class="font-medium">1</span> <?= __('admin.users.to') ?> <span class="font-medium">10</span> <?= __('admin.users.of') ?> <span class="font-medium"><?= isset($counts['total']) ? (int)$counts['total'] : 0 ?></span> <?= __('admin.users.results') ?>
            </div>
            <div class="flex items-center space-x-2 space-x-reverse">
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50"><?= __('admin.users.previous') ?></button>
                <button class="px-3 py-2 text-sm font-medium text-white bg-primary-600 border border-primary-600 rounded-md">1</button>
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">2</button>
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">3</button>
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50"><?= __('admin.users.next') ?></button>
            </div>
        </div>
    </div>
</div>

<script>
function deleteUser(userId, userName) {
    if (confirm(`<?= __('admin.users.delete_confirm') ?> "${userName}"?\n\n<?= __('admin.users.delete_warning') ?>`)) {
        const formData = new FormData();
        formData.append('id', userId);
        
        fetch('<?= $basePath ?>/admin/users/delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('<?= __('common.error') ?>: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?= __('admin.users.delete_error') ?>');
        });
    }
}
</script>

<style>
/* Enhanced animations and effects */
@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
    50% { box-shadow: 0 0 0 8px rgba(59, 130, 246, 0); }
}

.stats-card:hover .icon-container {
    animation: pulse-glow 2s infinite;
}

/* Smooth gradient animations */
@keyframes gradient-shift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.gradient-animated {
    background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
    background-size: 400% 400%;
    animation: gradient-shift 15s ease infinite;
}

/* Enhanced hover effects */
.stats-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.stats-card:hover {
    transform: translateY(-4px);
}

/* Custom scrollbar for better UX */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Enhanced focus states */
input:focus, select:focus, button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Loading state animation */
@keyframes shimmer {
    0% { background-position: -200px 0; }
    100% { background-position: calc(200px + 100%) 0; }
}

.loading-shimmer {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200px 100%;
    animation: shimmer 1.5s infinite;
}
</style>
