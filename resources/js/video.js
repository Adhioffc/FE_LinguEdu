let player;
let watchedSeconds = 0;
let nextBtn;

function onYouTubeIframeAPIReady() {
    player = new YT.Player("videoFrame", {
        events: { onStateChange: onPlayerStateChange }
    });
}

function onPlayerStateChange(event) {
    if (event.data === YT.PlayerState.PLAYING) startTracking();
}

function startTracking() {
    const interval = setInterval(() => {
        if (player.getPlayerState() !== YT.PlayerState.PLAYING) return;

        watchedSeconds++;

        // tampilkan tombol setelah 5 detik
        if (watchedSeconds >= 5 && nextBtn.classList.contains("show") === false) {
            nextBtn.classList.add("show");
        }

        if (watchedSeconds > 35) clearInterval(interval);
    }, 1000);
}

