@extends('layouts.web')

@push('styles')
<style>
.roleta-page {
    min-height: calc(100vh - 140px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px 16px;
}

.roleta-card {
    width: 100%;
    max-width: 560px;
    background: linear-gradient(145deg, rgba(26,29,36,0.95), rgba(18,20,26,0.98));
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 24px;
    padding: 32px 24px 28px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    position: relative;
    overflow: hidden;
}

.roleta-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--cor-principal, #e74c3c), gold, var(--cor-principal, #e74c3c), transparent);
}

.roleta-header {
    text-align: center;
    margin-bottom: 20px;
}

.roleta-header h2 {
    font-size: 1.6rem;
    font-weight: 900;
    margin: 0;
    background: linear-gradient(135deg, #fff 40%, gold);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: 0.5px;
}

.roleta-header p {
    margin: 4px 0 0;
    font-size: 0.85rem;
    opacity: 0.5;
}

.roleta-countdown {
    text-align: center;
    padding: 12px 16px;
    margin-top: 28px;
    background: rgba(255,255,255,0.03);
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.05);
}

.roleta-countdown .cd-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    opacity: 0.4;
}

.roleta-countdown .cd-time {
    font-size: 1.3rem;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
    color: gold;
    letter-spacing: 2px;
}

.roleta-countdown .cd-time.expired {
    color: var(--cor-principal, #e74c3c);
}

.roleta-wheel-area {
    position: relative;
    display: flex;
    justify-content: center;
    margin-bottom: 4px;
}

.roleta-wheel-area .outer-ring {
    position: relative;
    width: 420px;
    height: 420px;
}

.roleta-pointer {
    position: absolute;
    top: -6px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 15;
    width: 0;
    height: 0;
    border-left: 14px solid transparent;
    border-right: 14px solid transparent;
    border-top: 26px solid gold;
    filter: drop-shadow(0 0 12px rgba(255,215,0,0.5));
}

.roleta-wheel-wrapper {
    position: relative;
    width: 420px;
    height: 420px;
    margin: 0 auto;
}

.roleta-wheel {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    overflow: hidden;
    box-shadow:
        0 0 0 6px #2a2d35,
        0 0 0 10px gold,
        0 0 0 14px #1a1d24,
        0 0 0 18px #2a2d35,
        0 0 40px rgba(255,215,0,0.15);
    position: relative;
    will-change: transform;
}

.roleta-wheel canvas {
    width: 100%;
    height: 100%;
    display: block;
}

.roleta-center-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 20;
    width: 82px;
    height: 82px;
    border-radius: 50%;
    border: 4px solid gold;
    background: linear-gradient(145deg, #1a1d24, #0d0f14);
    color: #fff;
    font-size: 0.8rem;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 1px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 0 20px rgba(255,215,0,0.15), 0 4px 16px rgba(0,0,0,0.5);
    user-select: none;
    outline: none;
}

.roleta-center-btn:hover:not(:disabled) {
    background: linear-gradient(145deg, var(--cor-principal, #e74c3c), #b71c1c);
    border-color: #fff;
    box-shadow: 0 0 30px rgba(231,76,60,0.4);
}

.roleta-center-btn:disabled {
    cursor: not-allowed;
    opacity: 0.85;
}

.roleta-center-btn .btn-label {
    font-size: 0.5rem;
    opacity: 0.6;
    letter-spacing: 1px;
}

.roleta-center-btn .btn-value {
    font-size: 1.1rem;
    font-weight: 900;
}

.roleta-center-btn.spinning .btn-label {
    display: none;
}

.roleta-center-btn.spinning .btn-value .spinner-dot {
    display: inline-block;
    animation: dotPulse 0.6s infinite alternate;
}

.roleta-center-btn.spinning .btn-value .spinner-dot:nth-child(2) { animation-delay: 0.2s; }
.roleta-center-btn.spinning .btn-value .spinner-dot:nth-child(3) { animation-delay: 0.4s; }

@keyframes dotPulse {
    from { opacity: 0.2; }
    to { opacity: 1; }
}

.roleta-result-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    z-index: 25;
    display: none;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.roleta-result-overlay.show {
    display: flex;
    animation: resultPop 0.5s ease-out;
}

@keyframes resultPop {
    0% { transform: scale(0.2); opacity: 0; }
    60% { transform: scale(1.12); }
    100% { transform: scale(1); opacity: 1; }
}

.roleta-result-overlay .result-inner {
    text-align: center;
    color: #fff;
    text-shadow: 0 2px 20px rgba(0,0,0,0.7);
}

.roleta-result-overlay .result-inner .result-value {
    font-size: 2.6rem;
    font-weight: 900;
    line-height: 1;
}

.roleta-result-overlay .result-inner .result-label {
    font-size: 0.8rem;
    opacity: 0.8;
    margin-top: 4px;
}

.roleta-error {
    display: none;
    margin-top: 12px;
    padding: 10px 16px;
    background: rgba(231,76,60,0.12);
    border: 1px solid rgba(231,76,60,0.25);
    border-radius: 10px;
    color: #e57373;
    font-size: 0.85rem;
    font-weight: 600;
    text-align: center;
}

@media (max-width: 520px) {
    .roleta-card { padding: 24px 14px 20px; border-radius: 18px; }
    .roleta-wheel-area .outer-ring,
    .roleta-wheel-wrapper { width: 310px; height: 310px; }
    .roleta-wheel { box-shadow: 0 0 0 4px #2a2d35, 0 0 0 7px gold, 0 0 0 10px #1a1d24, 0 0 0 13px #2a2d35, 0 0 24px rgba(255,215,0,0.12); }
    .roleta-center-btn { width: 64px; height: 64px; }
    .roleta-center-btn .btn-value { font-size: 0.95rem; }
    .roleta-pointer { top: -4px; border-left-width: 11px; border-right-width: 11px; border-top-width: 20px; }
    .roleta-result-overlay .result-inner .result-value { font-size: 2rem; }
    .roleta-header h2 { font-size: 1.3rem; }
    .roleta-countdown .cd-time { font-size: 1.1rem; }
}
</style>
@endpush

@section('content')
    @include('includes.navbar_top')
    @include('includes.navbar_left')

    <div class="page__content">
        <div class="container">
            <div class="roleta-page">
                <div class="roleta-card">
                    <div class="roleta-header">
                        <h2>🎰 Roleta Diária</h2>
                        <p>Gire uma vez por dia e ganhe prêmios em dinheiro!</p>
                    </div>

                    <div class="roleta-wheel-area">
                        <div class="outer-ring">
                            <div class="roleta-pointer"></div>
                            <div class="roleta-wheel-wrapper">
                                <div class="roleta-wheel" id="roletaWheel">
                                    <canvas id="wheelCanvas" width="800" height="800"></canvas>
                                </div>

                                @auth
                                    @if($lastSpin)
                                        <button class="roleta-center-btn" id="spinBtn" disabled>
                                            <span class="btn-label">RECEBIDO</span>
                                            <span class="btn-value">R$ {{ number_format($lastSpin->value, 2, ',', '.') }}</span>
                                        </button>
                                        <div class="roleta-result-overlay show" style="display:flex;background:radial-gradient(circle, {{ $lastSpin->reward?->color ?? '#e74c3c' }}cc, transparent 70%)">
                                            <div class="result-inner">
                                                <div class="result-value">R$ {{ number_format($lastSpin->value, 2, ',', '.') }}</div>
                                                <div class="result-label">{{ $lastSpin->reward?->label ?? 'Prêmio' }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <button class="roleta-center-btn" id="spinBtn" onclick="spinRoleta()">
                                            <span class="btn-label">GIRAR</span>
                                            <span class="btn-value">GRÁTIS</span>
                                        </button>
                                        <div class="roleta-result-overlay" id="resultOverlay">
                                            <div class="result-inner">
                                                <div class="result-value" id="rewardValue">R$ 0,00</div>
                                                <div class="result-label" id="rewardLabel"></div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <button class="roleta-center-btn" id="spinBtn" onclick="redirectLogin(event)">
                                        <span class="btn-label">ENTRAR</span>
                                        <span class="btn-value">GIRAR</span>
                                    </button>
                                    <div class="roleta-result-overlay" id="resultOverlay">
                                        <div class="result-inner">
                                            <div class="result-value" id="rewardValue">R$ 0,00</div>
                                            <div class="result-label" id="rewardLabel"></div>
                                        </div>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <div class="roleta-error" id="roletaError"></div>

                    <div class="roleta-countdown" id="countdownBox">
                        <div class="cd-label">Próximo giro disponível em</div>
                        <div class="cd-time" id="countdownTimer">--:--:--</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
const rewards = @json($rewards);
const isAuth = @json(auth()->check());

let isSpinning = false;
let preSpinTimer = null;

function redirectLogin(e) {
    if (e) e.preventDefault();
    var loginBtn = document.querySelector('[data-izimodal-open="#login-modal"]');
    if (loginBtn) { loginBtn.click(); return; }
    var anyModal = document.querySelector('[data-izimodal-open]');
    if (anyModal) { anyModal.click(); return; }
    window.location.href = '/login';
}

function drawWheel() {
    const canvas = document.getElementById('wheelCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const center = canvas.width / 2;
    const radius = center - 8;
    const count = rewards.length;
    if (count === 0) return;
    const arc = (2 * Math.PI) / count;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    rewards.forEach(function (reward, i) {
        var sa = i * arc - Math.PI / 2;
        var ea = sa + arc;

        ctx.beginPath();
        ctx.moveTo(center, center);
        ctx.arc(center, center, radius, sa, ea);
        ctx.closePath();
        ctx.fillStyle = reward.color;
        ctx.fill();

        ctx.strokeStyle = 'rgba(255,255,255,0.12)';
        ctx.lineWidth = 2;
        ctx.stroke();

        var ta = sa + arc / 2;
        var tr = radius * 0.62;
        ctx.save();
        ctx.translate(center + Math.cos(ta) * tr, center + Math.sin(ta) * tr);
        ctx.rotate(ta + Math.PI / 2);
        ctx.fillStyle = '#fff';
        ctx.font = 'bold 18px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.shadowColor = 'rgba(0,0,0,0.5)';
        ctx.shadowBlur = 6;
        ctx.fillText(reward.label, 0, 0);
        ctx.shadowBlur = 0;
        ctx.restore();
    });

    ctx.beginPath();
    ctx.arc(center, center, 48, 0, 2 * Math.PI);
    ctx.fillStyle = '#1a1d24';
    ctx.fill();
    ctx.strokeStyle = 'rgba(255,215,0,0.3)';
    ctx.lineWidth = 3;
    ctx.stroke();
}

function spinRoleta() {
    if (isSpinning) return;
    isSpinning = true;

    var btn = document.getElementById('spinBtn');
    var wheel = document.getElementById('roletaWheel');
    var overlay = document.getElementById('resultOverlay');
    var err = document.getElementById('roletaError');

    btn.disabled = true;
    btn.classList.add('spinning');
    btn.innerHTML = '<span class="btn-label">GIRANDO</span><span class="btn-value"><span class="spinner-dot">•</span><span class="spinner-dot">•</span><span class="spinner-dot">•</span></span>';
    if (overlay) overlay.classList.remove('show');
    if (err) err.style.display = 'none';

    var deg = 0;
    preSpinTimer = setInterval(function () {
        deg += 50;
        wheel.style.transform = 'rotate(' + deg + 'deg)';
    }, 10);

    fetch('{{ route('web.roleta.spin') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        if (data.error) {
            clearInterval(preSpinTimer);
            btn.classList.remove('spinning');
            btn.innerHTML = '<span class="btn-label">BLOQUEADO</span><span class="btn-value">—</span>';
            btn.disabled = true;
            isSpinning = false;
            if (err) { err.textContent = data.error; err.style.display = 'block'; }
            return;
        }

        clearInterval(preSpinTimer);

        var extraDeg = 150 * 360;
        var targetAngle = (data.selectedIndex * data.segmentAngle) + (data.segmentAngle / 2);
        var stopAngle = 360 - targetAngle;
        var baseDeg = deg - (deg % 360);
        var finalDeg = baseDeg + extraDeg + stopAngle;
        var startDeg = deg;
        var totalDelta = finalDeg - startDeg;
        var startedAt = Date.now();

        function decel() {
            var elapsed = Date.now() - startedAt;
            var p = Math.min(elapsed / 5000, 1);
            var eased = 1 - Math.pow(1 - p, 4);
            wheel.style.transform = 'rotate(' + (startDeg + totalDelta * eased) + 'deg)';
            if (p < 1) {
                requestAnimationFrame(decel);
            } else {
                finish(data);
            }
        }
        requestAnimationFrame(decel);
    })
    .catch(function () {
        clearInterval(preSpinTimer);
        btn.disabled = false;
        btn.classList.remove('spinning');
        btn.innerHTML = '<span class="btn-label">GIRAR</span><span class="btn-value">GRÁTIS</span>';
        isSpinning = false;
        if (err) { err.textContent = 'Erro ao girar. Tente novamente.'; err.style.display = 'block'; }
    });

    function finish(data) {
        if (overlay) {
            overlay.querySelector('#rewardValue').textContent = 'R$ ' + data.reward.value.toFixed(2).replace('.', ',');
            overlay.querySelector('#rewardLabel').textContent = data.reward.label;
            overlay.style.background = 'radial-gradient(circle, ' + data.reward.color + 'cc, transparent 70%)';
            overlay.classList.add('show');
        }

        btn.innerHTML = '<span class="btn-label">GANHOU</span><span class="btn-value">R$ ' + data.reward.value.toFixed(2).replace('.', ',') + '</span>';
        btn.classList.remove('spinning');
        btn.disabled = true;
        isSpinning = false;

        var balSpan = document.querySelector('.balance-value span');
        if (balSpan) {
            var txt = balSpan.textContent.replace('R$ ', '').replace(/\./g, '').replace(',', '.');
            var curr = parseFloat(txt) || 0;
            var fmt = 'R$ ' + (curr + data.reward.value).toFixed(2).replace('.', ',');
            balSpan.textContent = fmt;
        }
    }
}

function updateCountdown() {
    var el = document.getElementById('countdownTimer');
    if (!el) return;

    var now = new Date();
    var utcMs = now.getTime() + now.getTimezoneOffset() * 60000;
    var brtOffset = -180;
    var brtMs = utcMs + brtOffset * 60000;

    var hojeBR = new Date(brtMs);
    var amanhaBR = new Date(brtMs);
    amanhaBR.setDate(amanhaBR.getDate() + 1);
    amanhaBR.setHours(0, 0, 0, 0);

    // Convert midnight BRT back to local time for countdown
    var midnightLocal = new Date(amanhaBR.getTime() - (brtOffset + now.getTimezoneOffset()) * 60000);
    var diff = midnightLocal - now;

    if (diff <= 0) {
        el.textContent = 'DISPONÍVEL';
        el.classList.add('expired');
        return;
    }

    el.classList.remove('expired');
    var h = Math.floor(diff / 3600000);
    var m = Math.floor((diff % 3600000) / 60000);
    var s = Math.floor((diff % 60000) / 1000);

    el.textContent =
        String(h).padStart(2, '0') + ':' +
        String(m).padStart(2, '0') + ':' +
        String(s).padStart(2, '0');
}

drawWheel();
updateCountdown();
setInterval(updateCountdown, 1000);
</script>
@endpush
