<?php
// app/views/admin/users/add-user.php
$basePath = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($basePath === '/' || $basePath === '\\') { $basePath = ''; }
?>
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4"><?= __('admin.users.add_user') ?></h2>

        <?php if (!empty($errors ?? [])): ?>
            <div class="mb-4 p-4 rounded-lg border border-red-200 bg-red-50 text-red-700 text-sm">
                <ul class="list-disc mr-6">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= $basePath ?>/admin/users" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><?= __('admin.users.full_name') ?></label>
                    <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><?= __('admin.users.email') ?></label>
                    <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><?= __('admin.users.role') ?></label>
                    <select name="role_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value=""><?= __('admin.users.choose_role') ?></option>
                        <?php foreach (($roles ?? []) as $r): ?>
                            <option value="<?= (int)$r['id'] ?>" <?= isset($old['role_id']) && (int)$old['role_id'] === (int)$r['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($r['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><?= __('admin.users.status') ?></label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <?php $st = $old['status'] ?? 'active'; ?>
                        <option value="active" <?= $st==='active'?'selected':''; ?>><?= __('admin.users.statuses.active') ?></option>
                        <option value="inactive" <?= $st==='inactive'?'selected':''; ?>><?= __('admin.users.statuses.inactive') ?></option>
                        <option value="pending" <?= $st==='pending'?'selected':''; ?>><?= __('admin.users.statuses.pending') ?></option>
                        <option value="banned" <?= $st==='banned'?'selected':''; ?>><?= __('admin.users.statuses.banned') ?></option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2"><?= __('admin.users.password') ?></label>
                    <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="<?= __('admin.users.min_password_length') ?>" required>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <label class="flex items-center text-sm text-gray-700">
                    <input type="checkbox" name="force_reset" class="ml-2 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" <?= !empty($old['force_reset']) ? 'checked' : 'checked' ?>>
                    <?= __('admin.users.force_password_reset') ?>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <a href="<?= $basePath ?>/admin/users" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"><?= __('admin.users.cancel') ?></a>
                <div class="flex items-center gap-3">
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"><?= __('admin.users.save_user') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
