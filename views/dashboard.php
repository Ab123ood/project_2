<?php
// app/views/dashboard.php - Dashboard موحد لجميع الأدوار
$basePath = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($basePath === '/' || $basePath === '\\') { $basePath = ''; }

// تشخيص الجلسة والدور
if (session_status() === PHP_SESSION_NONE) { @session_start(); }
$roleId = $_SESSION['role_id'] ?? 1;
$userName = $_SESSION['user_name'] ?? $user['user_name'] ?? __('common.user');

// تحديد نوع المحتوى حسب الدور
$isEmployee = ($roleId == 1);
$isAwareness = ($roleId == 2);
$isAdmin = ($roleId == 3);
?>

<section class="bg-gray-50 min-h-screen">
  <div class="container mx-auto px-4 py-8">
    

    <?php if ($isEmployee): ?>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <i class="ri-trophy-line text-blue-600 text-xl"></i>
            </div>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full"><?= __('employee.dashboard.total_points') ?></span>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-1"><?= (int)($stats['points'] ?? 0) ?></div>
          <div class="text-sm text-gray-500"><?= __('employee.dashboard.total_points') ?></div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <i class="ri-book-read-line text-green-600 text-xl"></i>
            </div>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full"><?= __('employee.dashboard.content') ?></span>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-1"><?= (int)($stats['completed'] ?? 0) ?></div>
          <div class="text-sm text-gray-500"><?= __('employee.dashboard.completed_content') ?></div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <i class="ri-medal-line text-purple-600 text-xl"></i>
            </div>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full"><?= __('employee.dashboard.exams') ?></span>
          </div>
          <div class="text-2xl font-bold text-gray-900 mb-1"><?= (int)($stats['exams_passed'] ?? 0) ?></div>
          <div class="text-sm text-gray-500"><?= __('employee.dashboard.passed_exams') ?></div>
        </div>
        
        
      </div>

      <!-- الإجراءات السريعة للموظف -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
          <i class="ri-rocket-line text-blue-600 ml-3"></i>
          <?= __('employee.dashboard.quick_actions') ?>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <a href="<?= $basePath ?>/exams" class="group block p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
              <i class="ri-question-answer-line text-white text-xl"></i>
            </div>
            <h4 class="font-bold text-gray-900 mb-2"><?= __('employee.dashboard.exams') ?></h4>
            <p class="text-sm text-gray-600"><?= __('employee.dashboard.exams_desc') ?></p>
          </a>
          
          <a href="<?= $basePath ?>/surveys" class="group block p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl hover:from-green-100 hover:to-green-200 transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
              <i class="ri-survey-line text-white text-xl"></i>
            </div>
            <h4 class="font-bold text-gray-900 mb-2"><?= __('employee.dashboard.surveys') ?></h4>
            <p class="text-sm text-gray-600"><?= __('employee.dashboard.surveys_desc') ?></p>
          </a>
          
          <a href="<?= $basePath ?>/content" class="group block p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl hover:from-purple-100 hover:to-purple-200 transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
              <i class="ri-book-open-line text-white text-xl"></i>
            </div>
            <h4 class="font-bold text-gray-900 mb-2"><?= __('employee.dashboard.content') ?></h4>
            <p class="text-sm text-gray-600"><?= __('employee.dashboard.content_desc') ?></p>
          </a>
          
          <a href="<?= $basePath ?>/leaderboard" class="group block p-6 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl hover:from-orange-100 hover:to-orange-200 transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-12 h-12 bg-orange-600 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
              <i class="ri-trophy-line text-white text-xl"></i>
            </div>
            <h4 class="font-bold text-gray-900 mb-2"><?= __('employee.dashboard.leaderboard') ?></h4>
            <p class="text-sm text-gray-600"><?= __('employee.dashboard.leaderboard_desc') ?></p>
          </a>
        </div>
      </div>

      <!-- المحتوى المقترح -->
      <div class="grid lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
              <i class="ri-lightbulb-line text-yellow-500 ml-3"></i>
              <?= __('employee.dashboard.suggested_content') ?>
            </h3>
            <a href="<?= $basePath ?>/content" class="text-sm text-blue-600 hover:text-blue-700 font-medium"><?= __('employee.dashboard.browse_all') ?></a>
          </div>
          <?php if (!empty($suggested)): ?>
            <div class="space-y-4">
              <?php foreach (array_slice($suggested, 0, 3) as $item): ?>
                <div class="flex items-start space-x-4 rtl:space-x-reverse p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                  <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="ri-article-line text-blue-600"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-gray-900 mb-1 truncate"><?= htmlspecialchars($item['title'] ?? '') ?></h4>
                    <p class="text-sm text-gray-600 mb-2 line-clamp-2"><?= htmlspecialchars(substr($item['description'] ?? '', 0, 80)) ?>...</p>
                    <a href="<?= $basePath ?>/content/view/<?= $item['id'] ?>" class="text-sm text-blue-600 hover:text-blue-700 font-medium"><?= __('employee.dashboard.read_more') ?></a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center py-8 text-gray-500">
              <i class="ri-inbox-line text-4xl mb-4"></i>
              <p><?= __('employee.dashboard.no_suggested_content') ?></p>
            </div>
          <?php endif; ?>
        </div>

        <!-- الإشعارات الحديثة -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
              <i class="ri-notification-line text-red-500 ml-3"></i>
              <?= __('employee.dashboard.recent_notifications') ?>
            </h3>
            <a href="<?= $basePath ?>/notifications" class="text-sm text-blue-600 hover:text-blue-700 font-medium"><?= __('employee.dashboard.view_all') ?></a>
          </div>
          <?php if (!empty($notifications)): ?>
            <div class="space-y-4">
              <?php foreach (array_slice($notifications, 0, 4) as $notification): ?>
                <div class="flex items-start space-x-3 rtl:space-x-reverse p-3 hover:bg-gray-50 rounded-lg transition-colors">
                  <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 mb-1"><?= htmlspecialchars($notification['title'] ?? '') ?></p>
                    <p class="text-xs text-gray-500"><?= date('Y/m/d H:i', strtotime($notification['created_at'] ?? 'now')) ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center py-8 text-gray-500">
              <i class="ri-notification-off-line text-4xl mb-4"></i>
              <p><?= __('employee.dashboard.no_new_notifications') ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php elseif ($isAwareness): ?>
      <!-- محتوى مسؤول التوعية -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
              <i class="ri-book-line text-white text-xl"></i>
            </div>
            <div class="mr-4">
              <p class="text-sm font-medium text-gray-600"><?= __('employee.dashboard.published_content') ?></p>
              <p class="text-2xl font-bold text-gray-900"><?= (int)($stats['published_content'] ?? 45) ?></p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
              <i class="ri-eye-line text-white text-xl"></i>
            </div>
            <div class="mr-4">
              <p class="text-sm font-medium text-gray-600"><?= __('employee.dashboard.total_views') ?></p>
              <p class="text-2xl font-bold text-gray-900"><?= (int)($stats['total_views'] ?? 1250) ?></p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
              <i class="ri-question-answer-line text-white text-xl"></i>
            </div>
            <div class="mr-4">
              <p class="text-sm font-medium text-gray-600"><?= __('employee.dashboard.active_exams') ?></p>
              <p class="text-2xl font-bold text-gray-900"><?= (int)($stats['active_exams'] ?? 12) ?></p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
              <i class="ri-user-line text-white text-xl"></i>
            </div>
            <div class="mr-4">
              <p class="text-sm font-medium text-gray-600"><?= __('employee.dashboard.participants') ?></p>
              <p class="text-2xl font-bold text-gray-900"><?= (int)($stats['active_users'] ?? 320) ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- شريط الوصول السريع لمسؤول التوعية -->
      <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4"><?= __('employee.dashboard.quick_access') ?></h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <a href="<?= $basePath ?>/content/create" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
            <i class="ri-add-line text-blue-600 text-xl ml-3"></i>
            <div>
              <h4 class="font-medium text-gray-800"><?= __('employee.dashboard.add_content') ?></h4>
              <p class="text-sm text-gray-600"><?= __('employee.dashboard.add_content_desc') ?></p>
            </div>
          </a>
          <a href="<?= $basePath ?>/exams/create" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
            <i class="ri-question-answer-line text-green-600 text-xl ml-3"></i>
            <div>
              <h4 class="font-medium text-gray-800"><?= __('employee.dashboard.create_exam') ?></h4>
              <p class="text-sm text-gray-600"><?= __('employee.dashboard.create_exam_desc') ?></p>
            </div>
          </a>
          <a href="<?= $basePath ?>/surveys/create" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
            <i class="ri-survey-line text-purple-600 text-xl ml-3"></i>
            <div>
              <h4 class="font-medium text-gray-800"><?= __('employee.dashboard.create_survey') ?></h4>
              <p class="text-sm text-gray-600"><?= __('employee.dashboard.create_survey_desc') ?></p>
            </div>
          </a>
        </div>
      </div>

    <?php else: ?>
      <!-- محتوى الأدمن -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="group relative bg-white rounded-2xl shadow-lg border-0 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 overflow-hidden">
          <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
          <div class="absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full opacity-30 group-hover:scale-125 transition-transform duration-300"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-4">
              <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="ri-user-line text-white text-xl"></i>
              </div>
              <div class="text-right">
                <div class="text-3xl font-black bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"><?= (int)($stats['total_users'] ?? 1247) ?></div>
              </div>
            </div>
            <div class="space-y-3">
              <div class="text-lg font-bold text-gray-800"><?= __('employee.dashboard.total_users') ?></div>
              <div class="flex items-center justify-between">
                <div class="flex items-center px-3 py-1 bg-green-100 rounded-full">
                  <i class="ri-arrow-up-line text-green-600 mr-1"></i>
                  <span class="text-sm font-semibold text-green-700">+12%</span>
                </div>
                <span class="text-xs text-gray-500 font-medium"><?= __('employee.dashboard.from_last_month') ?></span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2 mt-4">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full" style="width: 85%"></div>
              </div>
            </div>
          </div>
        </div>
        
        
        

      </div>

      <!-- الرسوم البيانية والنشاطات للأدمن -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900"><?= __('employee.dashboard.performance_stats') ?></h3>
            <select id="rangeSelect" class="text-sm border border-gray-300 rounded-lg px-3 py-2">
              <option value="7"><?= __('employee.dashboard.last_7_days') ?></option>
              <option value="30"><?= __('employee.dashboard.last_30_days') ?></option>
              <option value="90"><?= __('employee.dashboard.last_3_months') ?></option>
            </select>
          </div>
          <div class="h-64">
            <canvas id="adminPerformanceChart"></canvas>
          </div>
        </div>

        <!-- Recent activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-6"><?= __('employee.dashboard.recent_activities') ?></h3>
          <div class="space-y-4">
            <?php if (!empty($recentActivities)): ?>
              <?php foreach ($recentActivities as $activity): ?>
                <div class="flex items-center space-x-3 space-x-reverse">
                  <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="ri-information-line text-blue-600 text-sm"></i>
                  </div>
                  <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($activity['title'] ?? __('employee.dashboard.new_activity')); ?></p>
                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars($activity['time'] ?? __('common.now')); ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="flex items-center space-x-3 space-x-reverse">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                  <i class="ri-user-add-line text-blue-600 text-sm"></i>
                </div>
                <div class="flex-1">
                  <p class="text-sm font-medium text-gray-900"><?= __('employee.dashboard.new_user_joined') ?></p>
                  <p class="text-xs text-gray-500"><?= __('common.minutes_ago', ['count' => 5]) ?></p>
                </div>
              </div>
              <div class="flex items-center space-x-3 space-x-reverse">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                  <i class="ri-book-line text-purple-600 text-sm"></i>
                </div>
                <div class="flex-1">
                  <p class="text-sm font-medium text-gray-900"><?= __('employee.dashboard.new_course_added') ?></p>
                  <p class="text-xs text-gray-500"><?= __('common.hour_ago') ?></p>
                </div>
              </div>
              <div class="flex items-center space-x-3 space-x-reverse">
                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                  <i class="ri-shield-check-line text-orange-600 text-sm"></i>
                </div>
                <div class="flex-1">
                  <p class="text-sm font-medium text-gray-900"><?= __('employee.dashboard.security_update') ?></p>
                  <p class="text-xs text-gray-500"><?= __('common.hours_ago', ['count' => 2]) ?></p>
                </div>
              </div>
            <?php endif; ?>
          </div>
          <div class="mt-6">
            <a href="#" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
              <?= __('employee.dashboard.view_all_activities') ?>
              <i class="ri-arrow-left-line mr-1"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- إجراءات سريعة للأدمن -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6"><?= __('employee.dashboard.quick_actions') ?></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <a href="<?= $basePath ?>/admin/users" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
              <i class="ri-user-add-line text-white"></i>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900"><?= __('employee.dashboard.user_management') ?></p>
              <p class="text-xs text-gray-600"><?= __('employee.dashboard.user_management_desc') ?></p>
            </div>
          </a>
          <a href="<?= $basePath ?>/admin/content" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
              <i class="ri-book-add-line text-white"></i>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900"><?= __('employee.dashboard.add_content_admin') ?></p>
              <p class="text-xs text-gray-600"><?= __('employee.dashboard.content_management') ?></p>
            </div>
          </a>
          <a href="<?= $basePath ?>/admin/exams/create" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group">
            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
              <i class="ri-question-answer-line text-white"></i>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900"><?= __('employee.dashboard.create_exam_admin') ?></p>
              <p class="text-xs text-gray-600"><?= __('employee.dashboard.exams_management') ?></p>
            </div>
          </a>
          <a href="<?= $basePath ?>/admin/reports" class="flex items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors group">
            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
              <i class="ri-file-chart-line text-white"></i>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900"><?= __('employee.dashboard.view_reports') ?></p>
              <p class="text-xs text-gray-600"><?= __('employee.dashboard.analytics') ?></p>
            </div>
          </a>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php if ($isAdmin): ?>
<!-- Chart.js للأدمن فقط -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function generateData(days){
        const labels = [];
        const series1 = [];
        const now = new Date();
        for (let i = days - 1; i >= 0; i--){
            const d = new Date(now);
            d.setDate(now.getDate() - i);
            labels.push(`${d.getMonth()+1}/${d.getDate()}`);
            series1.push(Math.floor(30 + Math.random()*50));
        }
        return { labels, series1 };
    }

    const ctx = document.getElementById('adminPerformanceChart').getContext('2d');
    let currentRange = parseInt(document.getElementById('rangeSelect').value, 10);
    let data = generateData(currentRange);
    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: '<?= __('employee.dashboard.completed_exams') ?>',
                    data: data.series1,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.15)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'bottom' } },
            scales: { x: { grid: { display: false } }, y: { beginAtZero: true } }
        }
    });

    document.getElementById('rangeSelect').addEventListener('change', function(){
        currentRange = parseInt(this.value, 10);
        const nd = generateData(currentRange);
        chart.data.labels = nd.labels;
        chart.data.datasets[0].data = nd.series1;
        chart.update();
    });
</script>
<?php endif; ?>
