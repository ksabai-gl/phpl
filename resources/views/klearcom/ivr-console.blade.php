<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Klearcom — IVR Journey Console</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --ink: #07131f;
      --navy: #0b2740;
      --teal: #0f8f8a;
      --teal-bright: #19c3bb;
      --sand: #e8f2f1;
      --mist: #f4f8f8;
      --line: rgba(7, 19, 31, 0.1);
      --ok: #1f9d63;
      --warn: #d4a017;
      --fail: #d64545;
      --text: #132536;
      --muted: #5b6b7a;
      --white: #ffffff;
      --shadow: 0 18px 50px rgba(7, 19, 31, 0.12);
    }

    * { box-sizing: border-box; }
    html, body { margin: 0; min-height: 100%; }
    body {
      font-family: Manrope, system-ui, sans-serif;
      color: var(--text);
      background:
        radial-gradient(1200px 500px at 10% -10%, rgba(25, 195, 187, 0.18), transparent 55%),
        radial-gradient(900px 420px at 100% 0%, rgba(11, 39, 64, 0.16), transparent 50%),
        linear-gradient(180deg, #f7fbfb 0%, #eef5f4 100%);
    }

    .shell {
      max-width: 1280px;
      margin: 0 auto;
      padding: 24px 20px 48px;
    }

    .topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 28px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .mark {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: linear-gradient(145deg, var(--teal-bright), var(--navy));
      display: grid;
      place-items: center;
      color: white;
      font-weight: 800;
      letter-spacing: -0.04em;
      box-shadow: 0 10px 24px rgba(15, 143, 138, 0.35);
    }

    .brand h1 {
      margin: 0;
      font-size: 1.35rem;
      letter-spacing: -0.03em;
      color: var(--ink);
    }

    .brand p {
      margin: 2px 0 0;
      color: var(--muted);
      font-size: 0.85rem;
    }

    .pill-row { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
    .pill {
      border: 1px solid var(--line);
      background: rgba(255,255,255,0.75);
      backdrop-filter: blur(8px);
      border-radius: 999px;
      padding: 8px 12px;
      font-size: 0.8rem;
      color: var(--muted);
    }
    .pill strong { color: var(--ink); font-weight: 700; }

    .nav-ninja {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      border: 0;
      border-radius: 999px;
      padding: 10px 16px;
      font-weight: 700;
      font-size: 0.84rem;
      font-family: inherit;
      color: #fff;
      background: linear-gradient(135deg, #0b2740, #0f8f8a);
      box-shadow: 0 10px 22px rgba(11, 39, 64, 0.22);
      transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .nav-ninja:hover {
      transform: translateY(-1px);
      box-shadow: 0 14px 26px rgba(11, 39, 64, 0.28);
    }
    .nav-ninja span {
      font-size: 0.72rem;
      font-weight: 600;
      opacity: 0.85;
    }

    .hero {
      display: grid;
      grid-template-columns: 1.25fr 0.95fr;
      gap: 22px;
      margin-bottom: 22px;
    }

    .panel {
      background: rgba(255,255,255,0.88);
      border: 1px solid var(--line);
      border-radius: 22px;
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .panel-head {
      padding: 18px 20px 12px;
      display: flex;
      justify-content: space-between;
      gap: 12px;
      align-items: end;
    }

    .panel-head h2 {
      margin: 0;
      font-size: 1.05rem;
      letter-spacing: -0.02em;
    }

    .panel-head span {
      color: var(--muted);
      font-size: 0.8rem;
    }

    .live-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 0.75rem;
      font-weight: 700;
      color: var(--ok);
      text-transform: uppercase;
      letter-spacing: 0.06em;
    }

    .live-badge i {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--ok);
      box-shadow: 0 0 0 0 rgba(31, 157, 99, 0.55);
      animation: pulse 1.6s infinite;
    }

    @keyframes pulse {
      0% { box-shadow: 0 0 0 0 rgba(31, 157, 99, 0.45); }
      70% { box-shadow: 0 0 0 10px rgba(31, 157, 99, 0); }
      100% { box-shadow: 0 0 0 0 rgba(31, 157, 99, 0); }
    }

    .journey {
      padding: 8px 20px 22px;
      display: grid;
      gap: 10px;
    }

    .node {
      display: grid;
      grid-template-columns: 54px 1fr auto;
      gap: 12px;
      align-items: center;
      padding: 12px 14px;
      border-radius: 16px;
      border: 1px solid var(--line);
      background: var(--mist);
      transform: translateY(8px);
      opacity: 0;
      animation: rise 0.55s ease forwards;
    }

    .node:nth-child(1) { animation-delay: 0.05s; }
    .node:nth-child(2) { animation-delay: 0.15s; }
    .node:nth-child(3) { animation-delay: 0.25s; }
    .node:nth-child(4) { animation-delay: 0.35s; }
    .node:nth-child(5) { animation-delay: 0.45s; }
    .node:nth-child(6) { animation-delay: 0.55s; }

    @keyframes rise {
      to { transform: translateY(0); opacity: 1; }
    }

    .node.active {
      background: linear-gradient(120deg, rgba(25,195,187,0.16), rgba(255,255,255,0.9));
      border-color: rgba(15, 143, 138, 0.35);
      box-shadow: inset 0 0 0 1px rgba(15, 143, 138, 0.12);
    }

    .step {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      display: grid;
      place-items: center;
      font-family: "IBM Plex Mono", monospace;
      font-size: 0.78rem;
      font-weight: 500;
      background: var(--navy);
      color: white;
    }

    .node.active .step {
      background: linear-gradient(145deg, var(--teal), var(--navy));
    }

    .node h3 {
      margin: 0 0 3px;
      font-size: 0.95rem;
    }

    .node p {
      margin: 0;
      color: var(--muted);
      font-size: 0.8rem;
    }

    .status {
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      padding: 6px 10px;
      border-radius: 999px;
      background: rgba(31, 157, 99, 0.12);
      color: var(--ok);
    }

    .status.warn {
      background: rgba(212, 160, 23, 0.14);
      color: #b07f00;
    }

    .status.fail {
      background: rgba(214, 69, 69, 0.12);
      color: var(--fail);
    }

    .status.idle,
    .status.pending {
      background: rgba(107, 114, 128, 0.12);
      color: var(--muted);
    }

    .console {
      display: grid;
      grid-template-rows: auto 1fr auto;
      min-height: 520px;
    }

    .wave {
      height: 92px;
      margin: 0 20px;
      border-radius: 16px;
      background:
        linear-gradient(180deg, rgba(11,39,64,0.92), rgba(15,143,138,0.88));
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: end;
      justify-content: center;
      gap: 4px;
      padding: 18px;
    }

    .bar {
      width: 5px;
      border-radius: 999px;
      background: rgba(255,255,255,0.85);
      animation: bounce 1.1s ease-in-out infinite;
      height: 18px;
    }

    .bar:nth-child(odd) { animation-delay: 0.12s; }
    .bar:nth-child(3n) { animation-delay: 0.28s; height: 34px; }
    .bar:nth-child(4n) { height: 48px; }

    @keyframes bounce {
      0%, 100% { transform: scaleY(0.45); opacity: 0.55; }
      50% { transform: scaleY(1); opacity: 1; }
    }

    .wave-label {
      position: absolute;
      top: 14px;
      left: 16px;
      color: rgba(255,255,255,0.88);
      font-size: 0.78rem;
      font-family: "IBM Plex Mono", monospace;
    }

    .dialer {
      padding: 18px 20px 8px;
    }

    .number-row {
      display: flex;
      gap: 10px;
      margin-bottom: 14px;
    }

    .number-row input {
      flex: 1;
      border: 1px solid var(--line);
      border-radius: 14px;
      padding: 12px 14px;
      font-size: 1rem;
      font-family: "IBM Plex Mono", monospace;
      background: var(--mist);
      color: var(--ink);
      outline: none;
    }

    .number-row input:focus {
      border-color: rgba(15, 143, 138, 0.55);
      box-shadow: 0 0 0 4px rgba(25, 195, 187, 0.15);
    }

    .btn {
      border: 0;
      border-radius: 14px;
      padding: 12px 16px;
      font-weight: 700;
      cursor: pointer;
      font-family: inherit;
      transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .btn:active { transform: translateY(1px); }

    .btn-primary {
      background: linear-gradient(135deg, var(--teal-bright), var(--teal));
      color: white;
      box-shadow: 0 10px 22px rgba(15, 143, 138, 0.28);
    }

    .btn-ghost {
      background: white;
      border: 1px solid var(--line);
      color: var(--ink);
    }

    .pad {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      margin-top: 8px;
    }

    .key {
      border: 1px solid var(--line);
      background: white;
      border-radius: 14px;
      padding: 14px 0;
      font-family: "IBM Plex Mono", monospace;
      font-size: 1.05rem;
      cursor: pointer;
    }

    .key:hover { background: var(--sand); }
    .key small {
      display: block;
      color: var(--muted);
      font-size: 0.65rem;
      margin-top: 2px;
      font-family: Manrope, sans-serif;
    }

    .transcript {
      margin: 10px 20px 18px;
      padding: 14px;
      border-radius: 16px;
      background: #07131f;
      color: #d7ecea;
      min-height: 128px;
      font-family: "IBM Plex Mono", monospace;
      font-size: 0.78rem;
      line-height: 1.55;
      overflow: auto;
    }

    .transcript .t-ok { color: #7dffc4; }
    .transcript .t-warn { color: #ffd56a; }
    .transcript .t-info { color: #9ad7ff; }

    .grid-3 {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
    }

    .metric {
      padding: 18px 18px 16px;
    }

    .metric .label {
      color: var(--muted);
      font-size: 0.78rem;
      margin-bottom: 8px;
    }

    .metric .value {
      font-size: 1.7rem;
      font-weight: 800;
      letter-spacing: -0.04em;
      color: var(--ink);
    }

    .metric .hint {
      margin-top: 8px;
      font-size: 0.78rem;
      color: var(--muted);
    }

    .tests {
      padding: 0 18px 18px;
      display: grid;
      gap: 8px;
    }

    .test-row {
      display: grid;
      grid-template-columns: 1fr auto auto;
      gap: 10px;
      align-items: center;
      padding: 12px 12px;
      border-radius: 14px;
      border: 1px solid var(--line);
      background: var(--mist);
      font-size: 0.86rem;
    }

    .mono { font-family: "IBM Plex Mono", monospace; font-size: 0.78rem; color: var(--muted); }

    .map {
      padding: 0 18px 18px;
    }

    .map-canvas {
      border-radius: 16px;
      border: 1px dashed rgba(15, 143, 138, 0.35);
      background:
        linear-gradient(180deg, rgba(232,242,241,0.7), rgba(255,255,255,0.9)),
        repeating-linear-gradient(90deg, transparent, transparent 28px, rgba(15,143,138,0.05) 28px, rgba(15,143,138,0.05) 29px),
        repeating-linear-gradient(0deg, transparent, transparent 28px, rgba(11,39,64,0.04) 28px, rgba(11,39,64,0.04) 29px);
      min-height: 210px;
      position: relative;
      overflow: hidden;
    }

    .bubble {
      position: absolute;
      border-radius: 14px;
      background: white;
      border: 1px solid var(--line);
      padding: 10px 12px;
      font-size: 0.78rem;
      box-shadow: 0 10px 24px rgba(7,19,31,0.08);
      max-width: 180px;
      animation: floaty 3.4s ease-in-out infinite;
    }

    .bubble strong { display: block; margin-bottom: 2px; }
    .bubble:nth-child(1) { left: 8%; top: 18%; animation-delay: 0s; }
    .bubble:nth-child(2) { left: 38%; top: 42%; animation-delay: 0.4s; }
    .bubble:nth-child(3) { left: 66%; top: 20%; animation-delay: 0.8s; }
    .bubble:nth-child(4) { left: 58%; top: 62%; animation-delay: 1.1s; }

    @keyframes floaty {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }

    .footer-note {
      margin-top: 18px;
      color: var(--muted);
      font-size: 0.8rem;
      text-align: center;
    }

    @media (max-width: 980px) {
      .hero, .grid-3 { grid-template-columns: 1fr; }
      .topbar { flex-direction: column; align-items: flex-start; }
    }
  </style>
</head>
<body>
  <div class="shell">
    <header class="topbar">
      <div class="brand">
        <div class="mark">Kc</div>
        <div>
          <h1>Klearcom</h1>
          <p>IVR Journey Console · Demo</p>
        </div>
      </div>
      <div class="pill-row">
        <div class="pill"><strong>100+</strong> countries</div>
        <div class="pill"><strong>Discovery</strong> + Regression</div>
        <div class="pill"><strong>Live network</strong> validation</div>
        <a class="nav-ninja" href="{{ url('/app') }}" title="Open Invoice Ninja admin / API testing UI">
          Open Ninja App
          <span>realtime API</span>
        </a>
      </div>
    </header>

    <section class="hero">
      <div class="panel">
        <div class="panel-head">
          <div>
            <h2>Customer IVR journey map</h2>
            <span>Mapped from live call path · Banking support line</span>
          </div>
          <div class="live-badge"><i></i> Live discovery</div>
        </div>
        <div class="journey" id="journey">
          <div class="node" data-step="0">
            <div class="step">01</div>
            <div>
              <h3>Inbound answer</h3>
              <p>Welcome prompt · language selection</p>
            </div>
            <div class="status idle">Idle</div>
          </div>
          <div class="node" data-step="1">
            <div class="step">02</div>
            <div>
              <h3>Main menu</h3>
              <p>Press 1 Sales · 2 Support · 3 Balance</p>
            </div>
            <div class="status idle">Idle</div>
          </div>
          <div class="node" data-step="2">
            <div class="step">03</div>
            <div>
              <h3>DTMF capture</h3>
              <p>Option 2 Support recognised</p>
            </div>
            <div class="status idle">Idle</div>
          </div>
          <div class="node" data-step="3">
            <div class="step">04</div>
            <div>
              <h3>Identity check</h3>
              <p>Account PIN prompt playing</p>
            </div>
            <div class="status idle">Idle</div>
          </div>
          <div class="node" data-step="4">
            <div class="step">05</div>
            <div>
              <h3>Queue / transfer</h3>
              <p>Route to Tier-1 agent group</p>
            </div>
            <div class="status idle">Idle</div>
          </div>
          <div class="node" data-step="5">
            <div class="step">06</div>
            <div>
              <h3>Voice quality</h3>
              <p>MOS 4.2 · no silence gaps</p>
            </div>
            <div class="status idle">Idle</div>
          </div>
        </div>
      </div>

      <div class="panel console">
        <div class="panel-head">
          <div>
            <h2>IVR test softphone</h2>
            <span>Simulate a real caller · DTMF + prompt validation</span>
          </div>
        </div>
        <div>
          <div class="wave" id="wave" aria-hidden="true">
            <div class="wave-label" id="callState">IDLE · ready to dial</div>
            <span class="bar"></span><span class="bar"></span><span class="bar"></span><span class="bar"></span>
            <span class="bar"></span><span class="bar"></span><span class="bar"></span><span class="bar"></span>
            <span class="bar"></span><span class="bar"></span><span class="bar"></span><span class="bar"></span>
            <span class="bar"></span><span class="bar"></span><span class="bar"></span><span class="bar"></span>
          </div>
          <div class="dialer">
            <div class="number-row">
              <input id="phone" value="+1 800 555 0142" aria-label="IVR number">
              <button class="btn btn-primary" id="dialBtn" type="button">Start test</button>
            </div>
            <div class="pad" id="pad">
              <button class="key" type="button" data-key="1">1<small>Sales</small></button>
              <button class="key" type="button" data-key="2">2<small>Support</small></button>
              <button class="key" type="button" data-key="3">3<small>Balance</small></button>
              <button class="key" type="button" data-key="4">4<small></small></button>
              <button class="key" type="button" data-key="5">5<small></small></button>
              <button class="key" type="button" data-key="6">6<small></small></button>
              <button class="key" type="button" data-key="7">7<small></small></button>
              <button class="key" type="button" data-key="8">8<small></small></button>
              <button class="key" type="button" data-key="9">9<small></small></button>
              <button class="key" type="button" data-key="*">*<small></small></button>
              <button class="key" type="button" data-key="0">0<small>Operator</small></button>
              <button class="key" type="button" data-key="#">#<small></small></button>
            </div>
          </div>
          <div class="transcript" id="log" aria-live="polite"></div>
        </div>
      </div>
    </section>

    <section class="grid-3">
      <div class="panel metric">
        <div class="label">Regression health</div>
        <div class="value" id="healthScore">98.6%</div>
        <div class="hint">Last full sweep 12 min ago · 214 paths</div>
        <div class="tests" id="regressionTests" style="margin-top:14px;padding:0">
          <div class="test-row"><span>Menu prompt match</span><span class="mono">US-East</span><span class="status pending">Pending</span></div>
          <div class="test-row"><span>DTMF routing 2→Support</span><span class="mono">UK</span><span class="status pending">Pending</span></div>
          <div class="test-row"><span>Transfer to agent</span><span class="mono">IN</span><span class="status pending">Pending</span></div>
          <div class="test-row"><span>Toll-free reachability</span><span class="mono">DE</span><span class="status pending">Pending</span></div>
        </div>
      </div>

      <div class="panel">
        <div class="panel-head">
          <div>
            <h2>Discovery map</h2>
            <span>Automated IVR tree · zero integration</span>
          </div>
        </div>
        <div class="map">
          <div class="map-canvas">
            <div class="bubble"><strong>Welcome</strong> EN / ES prompts OK</div>
            <div class="bubble"><strong>Support</strong> DTMF 2 validated</div>
            <div class="bubble"><strong>Balance</strong> Speech + keypad</div>
            <div class="bubble"><strong>Agent</strong> Avg queue 18s</div>
          </div>
        </div>
      </div>

      <div class="panel metric">
        <div class="label">Active alerts</div>
        <div class="value" style="font-size:1.25rem;line-height:1.25">No critical IVR failures</div>
        <div class="hint">Monitoring menus, transfers, MOS, carrier reachability</div>
        <div class="tests" style="margin-top:14px;padding:0">
          <div class="test-row"><span>Unexpected script change</span><span class="mono">Clear</span><span class="status">Pass</span></div>
          <div class="test-row"><span>Long silence &gt; 3s</span><span class="mono">Clear</span><span class="status">Pass</span></div>
          <div class="test-row"><span>Carrier connect fail</span><span class="mono">Clear</span><span class="status">Pass</span></div>
          <div class="test-row"><span>Voice quality MOS</span><span class="mono">4.2</span><span class="status">Pass</span></div>
        </div>
      </div>
    </section>

    <p class="footer-note">Klearcom demo console · IVR discovery, regression, and live journey validation — not an API lab screen.</p>
  </div>

  <script>
    const logEl = document.getElementById('log');
    const callState = document.getElementById('callState');
    const dialBtn = document.getElementById('dialBtn');
    const nodes = [...document.querySelectorAll('.node')];
    const regressionStatuses = [...document.querySelectorAll('#regressionTests .status')];
    let inCall = false;
    let step = 0;

    function line(html) {
      const row = document.createElement('div');
      row.innerHTML = html;
      logEl.appendChild(row);
      logEl.scrollTop = logEl.scrollHeight;
    }

    function setBadge(el, text, kind) {
      if (!el) return;
      el.className = kind ? ('status ' + kind) : 'status';
      el.textContent = text;
    }

    function resetJourneyBadges() {
      nodes.forEach((n) => {
        n.classList.remove('active');
        setBadge(n.querySelector('.status'), 'Idle', 'idle');
      });
      step = 0;
    }

    function resetRegressionBadges() {
      regressionStatuses.forEach((el) => setBadge(el, 'Pending', 'pending'));
    }

    function setActive(index) {
      nodes.forEach((n, i) => {
        n.classList.toggle('active', i === index);
        const badge = n.querySelector('.status');
        if (i < index) setBadge(badge, 'Pass', '');
        else if (i === index) setBadge(badge, index === 3 ? 'Watch' : 'Running', index === 3 ? 'warn' : 'pending');
        else setBadge(badge, 'Idle', 'idle');
      });
      step = index;
    }

    function markRegressionPass(i) {
      if (regressionStatuses[i]) setBadge(regressionStatuses[i], 'Pass', '');
    }

    function markRegressionWatch(i) {
      if (regressionStatuses[i]) setBadge(regressionStatuses[i], 'Watch', 'warn');
    }

    function sleep(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }

    async function startCall() {
      if (inCall) return;
      inCall = true;
      resetJourneyBadges();
      resetRegressionBadges();
      dialBtn.textContent = 'End test';
      logEl.innerHTML = '';
      const number = document.getElementById('phone').value.trim();
      callState.textContent = 'CONNECTING · live network';
      line('<span class="t-info">[' + new Date().toLocaleTimeString() + ']</span> Dialing <span class="t-ok">' + number + '</span>');
      await sleep(700);
      callState.textContent = 'IN CALL · capturing journey';
      line('<span class="t-ok">ANSWERED</span> Welcome prompt detected (EN)');
      markRegressionPass(0);
      setActive(0);
      await sleep(900);
      line('Prompt: “Thank you for calling. For sales press 1, support press 2…”');
      setActive(1);
      line('<span class="t-warn">Awaiting DTMF</span> — use keypad to continue the journey');
    }

    function endCall() {
      inCall = false;
      dialBtn.textContent = 'Start test';
      callState.textContent = 'IDLE · ready to dial';
      line('<span class="t-info">CALL ENDED</span> Journey snapshot saved to Discovery');
      resetJourneyBadges();
      resetRegressionBadges();
    }

    dialBtn.addEventListener('click', () => {
      if (inCall) endCall();
      else startCall();
    });

    document.getElementById('pad').addEventListener('click', async (event) => {
      const key = event.target.closest('.key')?.dataset.key;
      if (!key || !inCall) return;
      line('DTMF <span class="t-ok">' + key + '</span> sent');
      if (key === '2' && step <= 1) {
        setActive(2);
        await sleep(500);
        line('<span class="t-ok">ROUTE OK</span> Support branch selected');
        markRegressionPass(1);
        setActive(3);
        await sleep(700);
        line('Identity prompt playing… PIN requested');
        markRegressionWatch(2);
        setActive(4);
        await sleep(700);
        line('<span class="t-ok">TRANSFER</span> Tier-1 queue · estimated 18s');
        setActive(5);
        line('<span class="t-ok">MOS 4.2</span> Voice quality within threshold');
        markRegressionPass(3);
      } else if (key === '1') {
        setActive(2);
        line('Sales branch selected · demo path continues on Support (2)');
      } else {
        line('<span class="t-warn">Option noted</span> for regression coverage');
      }
    });

    line('<span class="t-info">Klearcom IVR Console ready</span> — start a test to walk the customer journey.');
  </script>
</body>
</html>
