<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>روبوت — لوحة التحكم</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{
    --bg-void:#0e1512;
    --panel:#151f1a;
    --panel-2:#1b2822;
    --line:#28362f;
    --lime:#a8cc3d;
    --lime-dim:#5c7328;
    --amber:#e8a33d;
    --cyan:#3dbfcc;
    --text:#edf2e8;
    --muted:#7e8f82;
    --radius:14px;
  }
  *{box-sizing:border-box;}
  html,body{margin:0;padding:0;}
  body{
    background:
      radial-gradient(circle at 15% -10%, #1a2a1f 0%, transparent 45%),
      var(--bg-void);
    color:var(--text);
    font-family:'Tajawal', sans-serif;
    min-height:100vh;
    padding:28px 18px 60px;
  }
  .wrap{max-width:960px;margin:0 auto;}

  header{
    display:flex;align-items:center;justify-content:space-between;
    gap:16px;flex-wrap:wrap;
    border-bottom:1px solid var(--line);
    padding-bottom:18px;margin-bottom:28px;
  }
  .brand{display:flex;align-items:center;gap:12px;}
  .brand .dot{
    width:12px;height:12px;border-radius:50%;
    background:var(--lime);box-shadow:0 0 12px var(--lime);
    animation:pulse 2s ease-in-out infinite;
  }
  @keyframes pulse{0%,100%{opacity:1;}50%{opacity:.35;}}
  h1{font-size:22px;font-weight:900;margin:0;letter-spacing:.3px;}
  .sub{color:var(--muted);font-size:13px;margin-top:2px;}
  nav a{
    color:var(--muted);text-decoration:none;font-size:14px;font-weight:500;
    border:1px solid var(--line);padding:8px 16px;border-radius:100px;
    transition:.15s;
  }
  nav a:hover{color:var(--lime);border-color:var(--lime-dim);}

  .grid{
    display:grid;grid-template-columns:1.1fr .9fr;gap:22px;
  }
  @media (max-width:760px){.grid{grid-template-columns:1fr;}}

  .panel{
    background:var(--panel);
    border:1px solid var(--line);
    border-radius:var(--radius);
    padding:24px;
  }
  .panel h2{
    font-size:12px;text-transform:uppercase;letter-spacing:1.5px;
    color:var(--muted);font-weight:700;margin:0 0 20px;
    display:flex;align-items:center;gap:8px;
  }
  .panel h2::before{content:'';width:5px;height:5px;background:var(--lime);border-radius:1px;}

  /* ---- D-Pad ---- */
  .dpad{
    direction:ltr;
    display:grid;
    grid-template-columns:repeat(3,84px);
    grid-template-rows:repeat(3,84px);
    gap:10px;
    justify-content:center;
    margin:6px auto 28px;
  }
  .dbtn{
    border:1px solid var(--line);
    background:var(--panel-2);
    color:var(--text);
    border-radius:12px;
    font-family:'Tajawal',sans-serif;
    font-weight:700;
    font-size:13px;
    cursor:pointer;
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    gap:4px;
    transition:.12s ease;
    user-select:none;
  }
  .dbtn:hover{border-color:var(--lime-dim);}
  .dbtn:active{transform:scale(.94);background:var(--lime-dim);}
  .dbtn .arrow{font-size:20px;line-height:1;}
  .dbtn .code{font-family:'JetBrains Mono',monospace;color:var(--muted);font-size:10px;}

  .dbtn.fwd{grid-column:2;grid-row:1;}
  .dbtn.left{grid-column:1;grid-row:2;}
  .dbtn.stop{
    grid-column:2;grid-row:2;
    background:transparent;border:2px solid var(--amber);color:var(--amber);
  }
  .dbtn.stop:active{background:var(--amber);color:#1a1305;}
  .dbtn.right{grid-column:3;grid-row:2;}
  .dbtn.back{grid-column:2;grid-row:3;}

  /* ---- special actions ---- */
  .chips{display:flex;gap:10px;flex-wrap:wrap;}
  .chip{
    flex:1;min-width:120px;
    background:var(--panel-2);
    border:1px solid var(--line);
    border-radius:10px;
    padding:14px 12px;
    color:var(--text);
    text-align:center;
    cursor:pointer;
    font-family:'Tajawal',sans-serif;
    transition:.12s ease;
  }
  .chip:hover{border-color:var(--cyan);}
  .chip:active{transform:scale(.96);background:#12303380;}
  .chip .name{display:block;font-weight:700;font-size:13.5px;}
  .chip .code{display:block;font-family:'JetBrains Mono',monospace;color:var(--cyan);font-size:11px;margin-top:5px;}

  /* ---- terminal log ---- */
  .term{
    background:#0a0f0c;
    border:1px solid var(--line);
    border-radius:10px;
    padding:14px 16px;
    height:360px;
    overflow-y:auto;
    font-family:'JetBrains Mono',monospace;
    font-size:12.5px;
    direction:ltr;
    text-align:left;
  }
  .term .row{padding:5px 0;border-bottom:1px dashed #182019;color:var(--lime);}
  .term .row .t{color:var(--muted);margin-right:8px;}
  .term .row .n{color:var(--text);}
  .term .empty{color:var(--muted);}
  .status{margin-top:12px;font-size:12px;color:var(--muted);min-height:16px;}
  .status.ok{color:var(--lime);}
  .status.err{color:#e85d5d;}

  /* ---- voice control ---- */
  .voice-box{
    margin-top:22px;padding-top:20px;border-top:1px dashed var(--line);
    display:flex;align-items:center;gap:16px;flex-wrap:wrap;
  }
  .mic-btn{
    width:56px;height:56px;border-radius:50%;flex:0 0 auto;
    border:2px solid var(--line);background:var(--panel-2);
    color:var(--text);font-size:22px;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    transition:.15s ease;position:relative;
  }
  .mic-btn:hover{border-color:var(--lime-dim);}
  .mic-btn.listening{
    border-color:var(--amber);color:var(--amber);
    box-shadow:0 0 0 0 rgba(232,163,61,.5);
    animation:mic-pulse 1.4s ease-out infinite;
  }
  @keyframes mic-pulse{
    0%{box-shadow:0 0 0 0 rgba(232,163,61,.45);}
    100%{box-shadow:0 0 0 16px rgba(232,163,61,0);}
  }
  .voice-info{flex:1;min-width:180px;}
  .voice-info .label{font-size:12px;color:var(--muted);margin-bottom:4px;}
  .voice-info .transcript{
    font-family:'JetBrains Mono',monospace;font-size:13px;color:var(--text);
    min-height:18px;
  }
  .voice-unsupported{color:var(--muted);font-size:12px;}

  footer{margin-top:28px;text-align:center;color:var(--muted);font-size:12px;}
</style>
</head>
<body>
<div class="wrap">

  <header>
    <div class="brand">
      <span class="dot"></span>
      <div>
        <h1>روبوت — لوحة التحكم</h1>
        <div class="sub">كل أمر يُرسل هنا يُسجَّل مباشرة في قاعدة البيانات</div>
      </div>
    </div>
    <nav><a href="symbols.php">دليل الرموز والحركات ←</a></nav>
  </header>

  <div class="grid">

    <div class="panel">
      <h2>التحكم بالحركة</h2>
      <div class="dpad">
        <button class="dbtn fwd"  onclick="send('f')"><span class="arrow">↑</span>أمام<span class="code">f</span></button>
        <button class="dbtn left" onclick="send('l')"><span class="arrow">←</span>يسار<span class="code">l</span></button>
        <button class="dbtn stop" onclick="send('s')">توقف<span class="code">s</span></button>
        <button class="dbtn right" onclick="send('r')">يمين<span class="arrow">→</span><span class="code">r</span></button>
        <button class="dbtn back" onclick="send('b')"><span class="arrow">↓</span>خلف<span class="code">b</span></button>
      </div>

      <h2>حركات خاصة</h2>
      <div class="chips">
        <button class="chip" onclick="send('t')"><span class="name">دوران</span><span class="code">t</span></button>
        <button class="chip" onclick="send('d')"><span class="name">انبطاح</span><span class="code">d</span></button>
        <button class="chip" onclick="send('g')"><span class="name">رقصة ترحيب</span><span class="code">g</span></button>
      </div>

      <div id="status" class="status"></div>

      <div class="voice-box">
        <button class="mic-btn" id="micBtn" onclick="toggleVoice()" title="تحكم صوتي">🎤</button>
        <div class="voice-info">
          <div class="label" id="voiceLabel">اضغطي على المايك وانطقي أمر مثل: «أمام»، «يمين»، «توقف»</div>
          <div class="transcript" id="voiceTranscript"></div>
        </div>
      </div>
    </div>

    <div class="panel">
      <h2>سجل الأوامر الحي</h2>
      <div class="term" id="term"><div class="empty">جارِ تحميل السجل…</div></div>
    </div>

  </div>

  <footer>متصل بقاعدة البيانات · if0_42428239_db_robodog</footer>
</div>

<script>
const statusEl = document.getElementById('status');
const termEl = document.getElementById('term');

async function send(symbol, source){
  statusEl.textContent = 'جارِ الإرسال…';
  statusEl.className = 'status';
  try{
    const res = await fetch('api.php?route=send', {
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify({symbol, source: source || 'web'})
    });
    const data = await res.json();
    if(data.ok){
      statusEl.textContent = `تم إرسال: ${data.name_ar} (${data.symbol})`;
      statusEl.className = 'status ok';
      loadLog();
    } else {
      statusEl.textContent = 'خطأ: ' + (data.error || 'غير معروف');
      statusEl.className = 'status err';
    }
  }catch(e){
    statusEl.textContent = 'تعذّر الاتصال بالخادم';
    statusEl.className = 'status err';
  }
}

async function loadLog(){
  try{
    const res = await fetch('api.php?route=log&limit=20');
    const data = await res.json();
    if(!data.ok || !data.items.length){
      termEl.innerHTML = '<div class="empty">لا توجد أوامر بعد</div>';
      return;
    }
    termEl.innerHTML = data.items.map(it => `
      <div class="row">
        <span class="t">${it.created_at}</span>
        ${it.source === 'voice' ? '🎤' : '🖱'}
        <span class="n">${it.action_name}</span> (${it.symbol})
      </div>
    `).join('');
  }catch(e){
    termEl.innerHTML = '<div class="empty">تعذّر تحميل السجل</div>';
  }
}

loadLog();
setInterval(loadLog, 4000);

// ============================================================
// التحكم الصوتي (Web Speech API)
// ============================================================
const micBtn = document.getElementById('micBtn');
const voiceLabel = document.getElementById('voiceLabel');
const voiceTranscript = document.getElementById('voiceTranscript');

// كل كلمة عربية محتملة تتحول لرمز الأمر المناسب
const VOICE_MAP = [
  { symbol:'f', words:['امام','أمام','تقدم','قدام'] },
  { symbol:'b', words:['خلف','ارجع','رجوع','الرجوع للخلف','للخلف'] },
  { symbol:'l', words:['يسار','شمال'] },
  { symbol:'r', words:['يمين'] },
  { symbol:'s', words:['توقف','قف','وقف','ايقاف','إيقاف'] },
  { symbol:'t', words:['دور','دوران'] },
  { symbol:'d', words:['انبطح','انبطاح','اجلس'] },
  { symbol:'g', words:['رقص','رقصة','ترحيب','رقصة الترحيب'] },
];

function matchVoiceCommand(text){
  const clean = text.trim();
  for(const entry of VOICE_MAP){
    for(const w of entry.words){
      if(clean.includes(w)) return entry;
    }
  }
  return null;
}

const SpeechRecognitionCtor = window.SpeechRecognition || window.webkitSpeechRecognition;
let recognition = null;
let listening = false;

if(!SpeechRecognitionCtor){
  voiceLabel.textContent = 'متصفحك لا يدعم التحكم الصوتي — جرّبي Google Chrome';
  micBtn.disabled = true;
  micBtn.style.opacity = .4;
} else {
  recognition = new SpeechRecognitionCtor();
  recognition.lang = 'ar-SA';
  recognition.continuous = true;
  recognition.interimResults = true;

  recognition.onresult = (event) => {
    const result = event.results[event.results.length - 1];
    const text = result[0].transcript;
    voiceTranscript.textContent = text;

    if(result.isFinal){
      const match = matchVoiceCommand(text);
      if(match){
        voiceLabel.textContent = `تم التعرف على أمر: ${text}`;
        send(match.symbol, 'voice');
      } else {
        voiceLabel.textContent = `لم أفهم الأمر: "${text}" — جرّبي مرة أخرى`;
      }
    }
  };

  recognition.onerror = (event) => {
    voiceLabel.textContent = 'خطأ في التعرف على الصوت: ' + event.error;
    stopVoice();
  };

  recognition.onend = () => {
    if(listening) recognition.start(); // إعادة تشغيل تلقائي أثناء وضع الاستماع المستمر
  };
}

function toggleVoice(){
  if(!recognition) return;
  listening ? stopVoice() : startVoice();
}

function startVoice(){
  listening = true;
  micBtn.classList.add('listening');
  voiceLabel.textContent = 'أستمع الآن… انطقي أمر الحركة';
  voiceTranscript.textContent = '';
  try{ recognition.start(); }catch(e){ /* قد تكون بدأت مسبقًا */ }
}

function stopVoice(){
  listening = false;
  micBtn.classList.remove('listening');
  voiceLabel.textContent = 'اضغطي على المايك وانطقي أمر مثل: «أمام»، «يمين»، «توقف»';
  try{ recognition.stop(); }catch(e){}
}
</script>
</body>
</html>
