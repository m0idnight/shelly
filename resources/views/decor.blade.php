<div class="side-ribbon l"></div>
<div class="side-ribbon r"></div>

<span class="float-emoji" style="left:15%; animation-delay:0s">🩷</span>
<span class="float-emoji" style="left:35%; animation-delay:-4s">✨</span>
<span class="float-emoji" style="left:55%; animation-delay:-8s">🎀</span>
<span class="float-emoji" style="left:75%; animation-delay:-2s">🦋</span>
<span class="float-emoji" style="left:90%; animation-delay:-6s">🌸</span>

<div id="shelly-mascot">
    <svg viewBox="0 0 100 100">
        <path d="M10 70 Q10 20 50 15 Q90 20 90 70 Q50 95 10 70Z" fill="#fbcfe8" stroke="#f4b0cc" stroke-width="3"/>
        <path d="M50 84 L20 38 M50 84 L35 26 M50 84 L50 20 M50 84 L65 26 M50 84 L80 38"
              stroke="#fbcfe0" stroke-width="2.5" fill="none" stroke-linecap="round"/>
        <circle cx="38" cy="60" r="3.5" fill="#9d174d"/>
        <circle cx="62" cy="60" r="3.5" fill="#9d174d"/>
        <ellipse cx="29" cy="67" rx="6" ry="4" fill="#f4b0cc" opacity="0.5"/>
        <ellipse cx="71" cy="67" rx="6" ry="4" fill="#f4b0cc" opacity="0.5"/>
        <path d="M44 67 Q50 74 56 67" stroke="#9d174d" stroke-width="2.5" fill="none" stroke-linecap="round"/>
        <use href="#bow" x="33" y="0" width="34" height="20"/>
    </svg>
    <div id="shelly-bubble">Hi! I'm Shelly 🐚</div>
</div>

<script>
    (function () {
        const msgs = [
            "You can do this! 🦋",
            "Small steps count 🌸",
            "Study time = glow up ✨",
            "Take a sip of water 💧",
            "I believe in you! 🎀",
            "Your brain is amazing 🦋",
            "One page at a time 🐚"
        ];
        let n = 0;
        const el = document.getElementById('shelly-bubble');
        setInterval(function () {
            n = (n + 1) % msgs.length;
            el.textContent = msgs[n];
        }, 6000);
    })();
</script>