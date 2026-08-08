<?php
declare(strict_types=1);
require __DIR__ . '/db.php';

$actions = [];
$dbError = null;
try {
    $stmt = get_pdo()->query(
        'SELECT symbol, name_en, name_ar, description_ar, is_primary
         FROM actions ORDER BY sort_order ASC'
    );
    $actions = $stmt->fetchAll();
} catch (Throwable $e) {
    $dbError = 'تعذّر الاتصال بقاعدة البيانات';
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>روبوت — دليل الرموز</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{
    --bg-void:#0e1512; --panel:#151f1a; --panel-2:#1b2822; --line:#28362f;
    --lime:#a8cc3d; --lime-dim:#5c7328; --amber:#e8a33d; --cyan:#3dbfcc;
    --text:#edf2e8; --muted:#7e8f82; --radius:14px;
  }
  *{box-sizing:border-box;}
  body{
    margin:0;background:radial-gradient(circle at 15% -10%, #1a2a1f 0%, transparent 45%), var(--bg-void);
    color:var(--text);font-family:'Tajawal',sans-serif;min-height:100vh;padding:28px 18px 60px;
  }
  .wrap{max-width:900px;margin:0 auto;}
  header{
    display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;
    border-bottom:1px solid var(--line);padding-bottom:18px;margin-bottom:28px;
  }
  h1{font-size:22px;font-weight:900;margin:0;}
  .sub{color:var(--muted);font-size:13px;margin-top:2px;}
  nav a{
    color:var(--muted);text-decoration:none;font-size:14px;font-weight:500;
    border:1px solid var(--line);padding:8px 16px;border-radius:100px;transition:.15s;
  }
  nav a:hover{color:var(--lime);border-color:var(--lime-dim);}

  .section-label{
    font-size:12px;text-transform:uppercase;letter-spacing:1.5px;color:var(--muted);
    font-weight:700;margin:32px 0 14px;display:flex;align-items:center;gap:8px;
  }
  .section-label::before{content:'';width:5px;height:5px;background:var(--lime);border-radius:1px;}
  .section-label.special::before{background:var(--cyan);}

  .cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;}
  .card{
    background:var(--panel);border:1px solid var(--line);border-radius:var(--radius);
    padding:20px;position:relative;overflow:hidden;
  }
  .card::after{
    content:attr(data-symbol);
    position:absolute;left:14px;top:10px;
    font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted);
    border:1px solid var(--line);border-radius:6px;padding:2px 8px;
  }
  .card .glyph{
    font-family:'JetBrains Mono',monospace;font-weight:700;font-size:34px;
    color:var(--lime);line-height:1;margin-bottom:10px;
  }
  .card.special .glyph{color:var(--cyan);}
  .card .name-en{font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted);margin-bottom:6px;}
  .card .name-ar{font-weight:700;font-size:16px;margin-bottom:8px;}
  .card .desc{color:var(--muted);font-size:13px;line-height:1.7;}

  .error{color:#e85d5d;background:#2a1414;border:1px solid #4a2020;border-radius:10px;padding:14px 16px;font-size:13px;}
  footer{margin-top:32px;text-align:center;color:var(--muted);font-size:12px;}
</style>
</head>
<body>
<div class="wrap">
  <header>
    <div>
      <h1>روبوت — دليل الرموز والحركات</h1>
      <div class="sub">كل حركة يتحكم بها سيرفو مستقل لكل رجل</div>
    </div>
    <nav><a href="index.php">→ لوحة التحكم</a></nav>
  </header>

  <?php if ($dbError): ?>
    <div class="error"><?= htmlspecialchars($dbError) ?></div>
  <?php else: ?>

    <div class="section-label">حركات أساسية (D-Pad)</div>
    <div class="cards">
      <?php foreach ($actions as $a): if (!$a['is_primary']) continue; ?>
        <div class="card" data-symbol="<?= htmlspecialchars($a['symbol']) ?>">
          <div class="glyph"><?= htmlspecialchars($a['symbol']) ?></div>
          <div class="name-en"><?= htmlspecialchars($a['name_en']) ?></div>
          <div class="name-ar"><?= htmlspecialchars($a['name_ar']) ?></div>
          <div class="desc"><?= htmlspecialchars($a['description_ar']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="section-label special">حركات خاصة</div>
    <div class="cards">
      <?php foreach ($actions as $a): if ($a['is_primary']) continue; ?>
        <div class="card special" data-symbol="<?= htmlspecialchars($a['symbol']) ?>">
          <div class="glyph"><?= htmlspecialchars($a['symbol']) ?></div>
          <div class="name-en"><?= htmlspecialchars($a['name_en']) ?></div>
          <div class="name-ar"><?= htmlspecialchars($a['name_ar']) ?></div>
          <div class="desc"><?= htmlspecialchars($a['description_ar']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>

  <?php endif; ?>

  <footer>جميع الحركات تعتمد على تحكم السيرفو لكل رجل — مصممة لأرجل نحيفة بدون تحميل زائد</footer>
</div>
</body>
</html>
