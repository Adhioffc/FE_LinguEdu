document.addEventListener("DOMContentLoaded", function () {
    const btnLevel1 = document.getElementById('btnLevel1');
    const btnLevel2 = document.getElementById('btnLevel2');
    const btnLevel3 = document.getElementById('btnLevel3');
    const level1 = document.getElementById('level1');
    const level2 = document.getElementById('level2');
    const completeBtn = document.getElementById('btnCompleteLevel1');

    if (completeBtn) {
        completeBtn.addEventListener('click', () => {
            alert('🎉 Selamat! Level 1 sudah selesai.');
            btnLevel2.disabled = false;
            btnLevel2.classList.remove('btn-outline-secondary');
            btnLevel2.classList.add('btn-primary');
            btnLevel2.innerHTML = "Level 2";
        });
    }

    btnLevel1.addEventListener('click', () => {
        level1.classList.remove('d-none');
        level2.classList.add('d-none');
    });

    btnLevel2.addEventListener('click', () => {
        if (!btnLevel2.disabled) {
            level1.classList.add('d-none');
            level2.classList.remove('d-none');
        }
    });
});
