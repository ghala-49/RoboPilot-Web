-- ============================================================
-- RoboDog — قاعدة البيانات
-- استورد هذا الملف من phpMyAdmin داخل استضافة InfinityFree
-- Database: if0_42428239_db_robodog
-- ============================================================

CREATE TABLE IF NOT EXISTS actions (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    symbol          VARCHAR(5)   NOT NULL UNIQUE,
    name_en         VARCHAR(60)  NOT NULL,
    name_ar         VARCHAR(120) NOT NULL,
    description_ar  TEXT         NOT NULL,
    is_primary      TINYINT(1)   NOT NULL DEFAULT 0,
    sort_order      INT          NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS commands_log (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    symbol      VARCHAR(5)  NOT NULL,
    action_name VARCHAR(60) NOT NULL,
    source      VARCHAR(20) NOT NULL DEFAULT 'web',
    created_at  TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- الحركات الأساسية (D-pad)
INSERT INTO actions (symbol, name_en, name_ar, description_ar, is_primary, sort_order) VALUES
('f', 'Move_Forward',  'التقدم للأمام', 'التقدم للأمام بخطوات مستقرة.', 1, 1),
('b', 'Move_Backward', 'الرجوع للخلف', 'الرجوع للخلف بخطوات مستقرة.', 1, 2),
('l', 'Move_Left',     'تحريك يسار',   'تحريك الجسم أو الأرجل باتجاه اليسار.', 1, 3),
('r', 'Move_Right',    'تحريك يمين',   'تحريك الجسم أو الأرجل باتجاه اليمين.', 1, 4),
('s', 'Stop',          'إيقاف',        'إيقاف فوري لجميع حركات الأرجل.', 1, 5)
ON DUPLICATE KEY UPDATE name_en = VALUES(name_en);

-- الحركات الخاصة
INSERT INTO actions (symbol, name_en, name_ar, description_ar, is_primary, sort_order) VALUES
('t', 'Rotate',         'دوران',         'دوران الجسم حول محوره بزاوية محددة.', 0, 6),
('d', 'Lay_Down',       'انبطاح',        'انبطاح كامل للجسم مع ثبات الأرجل.', 0, 7),
('g', 'Greeting_Dance', 'رقصة الترحيب',  'دوران خفيف، ثم رفع اليد الأمامية، ثم إنزالها، ثم دوران خفيف مرة أخرى.', 0, 8)
ON DUPLICATE KEY UPDATE name_en = VALUES(name_en);
