<?php
// app/views/admin/surveys/index.php
$basePath = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($basePath === '/' || $basePath === '\\') { $basePath = ''; }
$isAdmin = (session_status() === PHP_SESSION_ACTIVE || @session_start() === null) && (int)($_SESSION['role_id'] ?? 0) === 3;
?>

<div class="max-w-6xl mx-auto">
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-xl md:text-2xl font-bold text-gray-900"><?= __('admin.surveys.list_title') ?></h1>
      <p class="text-sm text-gray-600"><?= __('admin.surveys.description') ?></p>
    </div>
    <a href="<?= $basePath ?>/admin/surveys/create" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2 text-sm">
      <i class="ri-add-line"></i>
      <?= __('admin.surveys.create_survey') ?>
    </a>
  </div>

  <div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
      <h2 class="text-lg font-semibold text-gray-900"><?= __('admin.surveys.surveys_list') ?></h2>
      <span class="text-sm text-gray-500"><?= __('admin.surveys.total') ?>: <?= count($surveys ?? []) ?></span>
    </div>
    <?php if (!empty($surveys)): ?>
      <div class="divide-y divide-gray-200">
        <?php foreach ($surveys as $s): ?>
          <?php
            $total = (int)($s['total_responses'] ?? 0);
            $completed = (int)($s['completed_responses'] ?? 0);
            $last7 = (int)($s['responses_last7'] ?? 0);
            $avg = $s['avg_rating'] !== null ? (float)$s['avg_rating'] : null;
            $rate = $total > 0 ? round(($completed / max($total,1)) * 100) : 0;
            $barColor = $rate >= 70 ? 'bg-green-500' : ($rate >= 40 ? 'bg-yellow-500' : 'bg-red-500');
          ?>
          <div class="p-6">
            <div class="flex items-center justify-between">
              <div>
                <div class="text-base font-semibold text-gray-900 mb-1"><?= htmlspecialchars($s['title']) ?></div>
                <div class="text-sm text-gray-600"><?= __('admin.surveys.category') ?>: <?= htmlspecialchars($s['category'] ?? '-') ?> • <?= __('admin.surveys.status') ?>: <span class="font-medium text-gray-800"><?= htmlspecialchars($s['status'] ?? 'draft') ?></span></div>
              </div>
              <div class="flex items-center gap-2">
                <a href="<?= $basePath ?>/admin/surveys/questions?survey_id=<?= (int)$s['id'] ?>" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50"><?= __('admin.surveys.questions') ?></a>
                <a href="<?= $basePath ?>/admin/surveys/analysis?survey_id=<?= (int)$s['id'] ?>" class="px-3 py-2 border border-purple-300 text-purple-700 rounded-lg text-sm hover:bg-purple-50"><?= __('admin.surveys.analysis') ?></a>
                <?php if ($isAdmin): ?>
                  <a href="<?= $basePath ?>/admin/surveys/edit?id=<?= (int)$s['id'] ?>" class="px-3 py-2 border border-blue-300 text-blue-700 rounded-lg text-sm hover:bg-blue-50"><?= __('admin.surveys.edit') ?></a>
                  <button onclick="deleteSurvey(<?= (int)$s['id'] ?>, '<?= htmlspecialchars($s['title'], ENT_QUOTES) ?>')" class="px-3 py-2 border border-red-300 text-red-700 rounded-lg text-sm hover:bg-red-50"><?= __('admin.surveys.delete') ?></button>
                <?php endif; ?>
              </div>
            </div>

            <!-- Analytics: completion rate, totals, last 7 days, avg rating -->
            <div class="mt-4">
              <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-2 <?= $barColor ?>" style="width: <?= $rate ?>%"></div>
              </div>
              <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 text-gray-700"><?= __('admin.surveys.completion_rate') ?>: <strong><?= $rate ?>%</strong></span>
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-blue-50 text-blue-700"><?= __('admin.surveys.responses') ?>: <strong><?= $total ?></strong></span>
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-green-50 text-green-700"><?= __('admin.surveys.completed') ?>: <strong><?= $completed ?></strong></span>
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-purple-50 text-purple-700"><?= __('admin.surveys.last_7_days') ?>: <strong><?= $last7 ?></strong></span>
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-amber-50 text-amber-700"><?= __('admin.surveys.avg_rating') ?>: <strong><?= $avg !== null ? number_format($avg, 2) : '—' ?></strong></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="p-8 text-center text-gray-500"><?= __('admin.surveys.no_surveys') ?></div>
    <?php endif; ?>
  </div>
</div>

<script>
function deleteSurvey(id, title){
  if(!confirm(`<?= __('admin.surveys.delete_confirm') ?> "${title}"?\n<?= __('admin.surveys.delete_warning') ?>`)) return;
  const form = new FormData();
  form.append('id', id);
  fetch('<?= $basePath ?>/admin/surveys/delete', { method: 'POST', body: form })
    .then(r => r.json())
    .then(data => {
      if(data && data.success){
        alert(data.message || '<?= __('admin.surveys.delete_success') ?>');
        location.reload();
      } else {
        alert('<?= __('admin.surveys.delete_failed') ?>: ' + (data && data.message ? data.message : 'Unknown'));
      }
    })
    .catch(err => { console.error(err); alert('<?= __('admin.surveys.delete_error') ?>'); });
}
</script>
