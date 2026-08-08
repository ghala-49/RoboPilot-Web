<?php
// ============================================================
// RoboDog API — كل طلب من الواجهة يمر من هنا ويُسجَّل في قاعدة البيانات
// ============================================================
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require __DIR__ . '/db.php';

$route = $_GET['route'] ?? '';

try {
    $pdo = get_pdo();

    switch ($route) {

        // ---- إرسال أمر جديد وتسجيله ----
        case 'send': {
            $raw = json_decode(file_get_contents('php://input'), true) ?? [];
            $symbol = trim((string)($raw['symbol'] ?? $_POST['symbol'] ?? ''));
            $source = trim((string)($raw['source'] ?? $_POST['source'] ?? 'web'));
            $source = in_array($source, ['web', 'voice'], true) ? $source : 'web';

            if ($symbol === '') {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'الرمز مفقود']);
                break;
            }

            $stmt = $pdo->prepare('SELECT name_en, name_ar FROM actions WHERE symbol = :s LIMIT 1');
            $stmt->execute([':s' => $symbol]);
            $action = $stmt->fetch();

            if (!$action) {
                http_response_code(404);
                echo json_encode(['ok' => false, 'error' => 'رمز غير معروف']);
                break;
            }

            $insert = $pdo->prepare(
                'INSERT INTO commands_log (symbol, action_name, source) VALUES (:s, :n, :src)'
            );
            $insert->execute([
                ':s'   => $symbol,
                ':n'   => $action['name_en'],
                ':src' => $source,
            ]);

            // مكان جاهز لاحقًا لإرسال الأمر فعليًا إلى ESP32
            // (مثلاً عبر HTTP request لعنوان الـ ESP32 على الشبكة المحلية)

            echo json_encode([
                'ok'     => true,
                'symbol' => $symbol,
                'name_ar'=> $action['name_ar'],
                'id'     => (int)$pdo->lastInsertId(),
            ]);
            break;
        }

        // ---- آخر الأوامر المُرسلة (لعرض سجل حي) ----
        case 'log': {
            $limit = isset($_GET['limit']) ? max(1, min(50, (int)$_GET['limit'])) : 15;
            $stmt = $pdo->query(
                "SELECT symbol, action_name, source, created_at
                 FROM commands_log ORDER BY id DESC LIMIT {$limit}"
            );
            echo json_encode(['ok' => true, 'items' => $stmt->fetchAll()]);
            break;
        }

        // ---- قائمة كل الحركات المعرّفة (الأحرف/الرموز) ----
        case 'actions': {
            $stmt = $pdo->query(
                'SELECT symbol, name_en, name_ar, description_ar, is_primary
                 FROM actions ORDER BY sort_order ASC'
            );
            echo json_encode(['ok' => true, 'items' => $stmt->fetchAll()]);
            break;
        }

        default:
            http_response_code(404);
            echo json_encode(['ok' => false, 'error' => 'مسار غير معروف']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'خطأ في الخادم', 'detail' => $e->getMessage()]);
}
