<?php
// app/views/admin/reports/index.php
// المتغيرات المتوقعة: $kpis, $rows, $from, $to, $type
$from = $from ?? '';
$to = $to ?? '';
$type = $type ?? 'overview';
$k_active_users = isset($kpis['active_users']) && $kpis['active_users'] !== null ? (int)$kpis['active_users'] : (int)($kpis['users_count'] ?? 0);
$k_pass = $kpis['avg_exam_score'] ?? null; // متوسط نتائج الاختبارات المكتملة
$k_content = $kpis['content_views'] ?? null; // إجمالي مشاهدات المحتوى
$totalRows = is_array($rows ?? null) ? count($rows) : 0;
?>
<div class="px-6 py-6">
  <!-- العنوان ومسارات التصفح -->
  <div class="mb-6 flex items-center justify-between">
    <div class="flex items-center">
      <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center ml-3">
        <i class="ri-bar-chart-line text-primary text-xl"></i>
      </div>
      <div>
        <h1 class="text-xl md:text-2xl font-bold text-gray-900"><?= __('admin.reports.title') ?></h1>
        <p class="text-sm text-gray-600"><?= __('admin.reports.subtitle') ?></p>
      </div>
    </div>
  </div>

  <!-- فلاتر البحث -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
    <form id="reportsFilter" method="get" class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1"><?= __('admin.reports.filters.from_date') ?></label>
        <input type="date" name="from" value="<?= htmlspecialchars($from) ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1"><?= __('admin.reports.filters.to_date') ?></label>
        <input type="date" name="to" value="<?= htmlspecialchars($to) ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1"><?= __('admin.reports.filters.report_type') ?></label>
        <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
          <?php $types = [
            'overview' => __('admin.reports.types.overview'),
            'exams' => __('admin.reports.types.exams'),
            'exam_attempts' => __('admin.reports.types.exam_attempts'),
            'content' => __('admin.reports.types.content'),
            'content_views' => __('admin.reports.types.content_views'),
            'users' => __('admin.reports.types.users'),
            'surveys' => __('admin.reports.types.surveys'),
            'points' => __('admin.reports.types.points')
          ]; ?>
          <?php foreach($types as $val=>$label): ?>
            <option value="<?= $val ?>" <?= $type===$val?'selected':''; ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="flex items-end gap-2">
        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:opacity-90"><i class="ri-search-line ml-2"></i><?= __('admin.reports.filters.apply') ?></button>
        <button type="button" id="btnExport" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200"><i class="ri-download-2-line ml-2"></i><?= __('admin.reports.filters.export_csv') ?></button>
      </div>
    </form>
  </div>

  <!-- مؤشرات رئيسية -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
      <div class="flex items-center">
        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
          <i class="ri-user-star-line text-2xl"></i>
        </div>
        <div class="mr-4">
          <p class="text-sm text-gray-600"><?= __('admin.reports.kpis.active_users') ?></p>
          <p class="text-2xl font-bold text-gray-900" id="kpiActiveUsers"><?= number_format($k_active_users) ?></p>
        </div>
      </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
      <div class="flex items-center">
        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
          <i class="ri-checkbox-circle-line text-2xl"></i>
        </div>
        <div class="mr-4">
          <p class="text-sm text-gray-600"><?= __('admin.reports.kpis.avg_exam_score') ?></p>
          <p class="text-2xl font-bold text-gray-900" id="kpiAvgExamScore"><?= $k_pass===null?'-':htmlspecialchars((string)$k_pass.'%') ?></p>
        </div>
      </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
      <div class="flex items-center">
        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">
          <i class="ri-play-circle-line text-2xl"></i>
        </div>
        <div class="mr-4">
          <p class="text-sm text-gray-600"><?= __('admin.reports.kpis.total_content_views') ?></p>
          <p class="text-2xl font-bold text-gray-900" id="kpiContentViews"><?= $k_content===null?'-':htmlspecialchars((string)$k_content) ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- شريط مؤشرات ثانوي -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <p class="text-sm text-gray-600"><?= __('admin.reports.kpis.users_count') ?></p>
      <p class="text-xl font-bold text-gray-900"><?= (int)($kpis['users_count'] ?? 0) ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <p class="text-sm text-gray-600"><?= __('admin.reports.kpis.exams_count') ?></p>
      <p class="text-xl font-bold text-gray-900"><?= (int)($kpis['exams_count'] ?? 0) ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <p class="text-sm text-gray-600"><?= __('admin.reports.kpis.exam_attempts') ?></p>
      <p class="text-xl font-bold text-gray-900"><?= (int)($kpis['exam_attempts'] ?? 0) ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <p class="text-sm text-gray-600"><?= __('admin.reports.kpis.completed_attempts') ?></p>
      <p class="text-xl font-bold text-gray-900"><?= (int)($kpis['completed_attempts'] ?? 0) ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <p class="text-sm text-gray-600"><?= __('admin.reports.kpis.surveys_count') ?></p>
      <p class="text-xl font-bold text-gray-900"><?= (int)($kpis['surveys_count'] ?? 0) ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <p class="text-sm text-gray-600"><?= __('admin.reports.kpis.survey_responses') ?></p>
      <p class="text-xl font-bold text-gray-900"><?= (int)($kpis['survey_responses'] ?? 0) ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <p class="text-sm text-gray-600"><?= __('admin.reports.kpis.content_count') ?></p>
      <p class="text-xl font-bold text-gray-900"><?= (int)($kpis['content_count'] ?? 0) ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <p class="text-sm text-gray-600"><?= __('admin.reports.kpis.points_total') ?></p>
      <p class="text-xl font-bold text-gray-900"><?= (int)($kpis['points_total'] ?? 0) ?></p>
    </div>
  </div>

  <!-- تبويب المحتوى: جدول/مخطط (حالياً الجدول حسب البيانات) -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <button class="tab-btn px-4 py-2 text-sm rounded-lg bg-primary text-white" data-tab="table"><?= __('admin.reports.table.table_tab') ?></button>
        <button class="tab-btn px-4 py-2 text-sm rounded-lg bg-gray-100 text-gray-700" data-tab="chart" disabled title="<?= __('admin.reports.table.chart_coming_soon') ?>"><?= __('admin.reports.table.chart_tab') ?></button>
      </div>
      <div class="text-sm text-gray-500" id="resultsInfo"><?= str_replace('{total}', $totalRows, __('admin.reports.table.showing_results')) ?></div>
    </div>

    <!-- جدول النتائج -->
    <div id="tab-table" class="p-5">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-right font-medium text-gray-700"><?= __('admin.reports.table.row_number') ?></th>
              <th class="px-4 py-3 text-right font-medium text-gray-700"><?= __('admin.reports.table.type') ?></th>
              <th class="px-4 py-3 text-right font-medium text-gray-700"><?= __('admin.reports.table.item') ?></th>
              <th class="px-4 py-3 text-right font-medium text-gray-700"><?= __('admin.reports.table.user') ?></th>
              <th class="px-4 py-3 text-right font-medium text-gray-700"><?= __('admin.reports.table.date') ?></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <?php if (!empty($rows)): $i=1; foreach($rows as $r): ?>
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-2 text-gray-700"><?= $i++; ?></td>
              <td class="px-4 py-2 text-gray-700">
                <?php
                  $kind = $r['kind'] ?? '';
                  $typeMap = [
                    'exam' => __('admin.reports.item_types.exam'),
                    'exam_attempt' => __('admin.reports.item_types.exam_attempt'),
                    'content' => __('admin.reports.item_types.content'),
                    'content_view' => __('admin.reports.item_types.content_view'),
                    'user' => __('admin.reports.item_types.user'),
                    'survey' => __('admin.reports.item_types.survey'),
                    'survey_response' => __('admin.reports.item_types.survey_response'),
                    'points' => __('admin.reports.item_types.points')
                  ];
                  echo $typeMap[$kind] ?? '-';
                ?>
              </td>
              <td class="px-4 py-2 text-gray-700"><?= htmlspecialchars($r['item'] ?? '-') ?></td>
              <td class="px-4 py-2 text-gray-700"><?= htmlspecialchars($r['user'] ?? '-') ?></td>
              <td class="px-4 py-2 text-gray-700"><?= htmlspecialchars($r['dt'] ?? '-') ?></td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
              <td colspan="5" class="px-4 py-8 text-center text-gray-500"><?= __('admin.reports.table.no_data') ?></td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- تبويب المخطط (Placeholder) -->
    <div id="tab-chart" class="p-5 hidden">
      <div class="h-64 bg-gray-50 border border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-500">
        <?= __('admin.reports.table.chart_placeholder') ?>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('reportsFilter');
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabTable = document.getElementById('tab-table');
    const tabChart = document.getElementById('tab-chart');

    tabBtns.forEach(btn => btn.addEventListener('click', () => {
      if (btn.hasAttribute('disabled')) return;
      tabBtns.forEach(b => b.classList.remove('bg-primary','text-white'));
      tabBtns.forEach(b => b.classList.add('bg-gray-100','text-gray-700'));
      btn.classList.add('bg-primary','text-white');
      btn.classList.remove('bg-gray-100','text-gray-700');
      const t = btn.dataset.tab;
      if (t === 'table') { tabTable.classList.remove('hidden'); tabChart.classList.add('hidden'); }
      else { tabChart.classList.remove('hidden'); tabTable.classList.add('hidden'); }
    }));

    document.getElementById('btnExport')?.addEventListener('click', function(){
      // حافظ على الفلاتر الحالية وقم بإضافة export=csv
      const params = new URLSearchParams(new FormData(form));
      params.set('export', 'csv');
      const url = window.location.pathname + '?' + params.toString();
      window.location.href = url;
    });
  });
</script>
