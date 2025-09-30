<?php

class NotificationsController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();

        $localization = $this->bootLocalization();
        $locale = $localization->getLocale();

        $userId = $_SESSION['user_id'];
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $conn = Database::connection();

        $stmt = $conn->prepare(
            'SELECT id, title, message, type, action_url, is_read, created_at
             FROM notifications
             WHERE user_id = :user_id
             ORDER BY created_at DESC
             LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $notifications = TranslationService::translateCollection($notifications, ['title', 'message'], $locale);

        foreach ($notifications as &$notification) {
            $notification['category'] = $notification['type'] ?? 'system';
            $notification['is_important'] = in_array($notification['type'] ?? '', ['warning', 'error'], true);
            $notification['action_text'] = TranslationService::translate('عرض', $locale);
        }
        unset($notification);

        $totalNotifications = (int)Database::query(
            'SELECT COUNT(*) FROM notifications WHERE user_id = :user_id',
            [':user_id' => $userId]
        )->fetchColumn();

        $unreadCount = (int)Database::query(
            'SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND is_read = 0',
            [':user_id' => $userId]
        )->fetchColumn();

        $importantCount = (int)Database::query(
            "SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND type IN ('warning','error')",
            [':user_id' => $userId]
        )->fetchColumn();

        $readCount = max(0, $totalNotifications - $unreadCount);
        $totalPages = (int)ceil($totalNotifications / $perPage);

        $this->render('employee/notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'readCount' => $readCount,
            'importantCount' => $importantCount,
            'totalNotifications' => $totalNotifications,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
        ]);
    }

    public function markRead($id): void
    {
        $this->requireLogin();

        $userId = $_SESSION['user_id'];
        $notificationId = (int)$id;

        try {
            Database::query(
                'UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :user_id',
                [':id' => $notificationId, ':user_id' => $userId]
            );
            $this->jsonResponse(['success' => true]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false], 500);
        }
    }

    public function markAllRead(): void
    {
        $this->requireLogin();

        $userId = $_SESSION['user_id'];

        try {
            Database::query(
                'UPDATE notifications SET is_read = 1 WHERE user_id = :user_id',
                [':user_id' => $userId]
            );
            $this->jsonResponse(['success' => true]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false], 500);
        }
    }

    public function delete($id): void
    {
        $this->requireLogin();

        $userId = $_SESSION['user_id'];
        $notificationId = (int)$id;

        try {
            Database::query(
                'DELETE FROM notifications WHERE id = :id AND user_id = :user_id',
                [':id' => $notificationId, ':user_id' => $userId]
            );
            $this->jsonResponse(['success' => true]);
        } catch (Throwable $e) {
            $this->jsonResponse(['success' => false], 500);
        }
    }

    public function checkNew(): void
    {
        $this->requireLogin();

        $userId = $_SESSION['user_id'];

        try {
            $count = (int)Database::query(
                'SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND is_read = 0',
                [':user_id' => $userId]
            )->fetchColumn();

            $this->jsonResponse(['hasNew' => $count > 0]);
        } catch (Throwable $e) {
            $this->jsonResponse(['hasNew' => false], 500);
        }
    }
}
