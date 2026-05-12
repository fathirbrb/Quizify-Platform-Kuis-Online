


function konfirmasiHapus(nama) {
    return confirm('Hapus "' + nama + '"?\nData yang dihapus tidak bisa dikembalikan.');
}


function togglePassword() {
    var input = document.getElementById('password');
    if (!input) return;

    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}


document.addEventListener('DOMContentLoaded', function () {
    var alerts = document.querySelectorAll('.alert-success');
    alerts.forEach(function (el) {
        setTimeout(function () {
            el.style.opacity = '0';
            el.style.transition = 'opacity 0.4s';
            setTimeout(function () { el.style.display = 'none'; }, 400);
        }, 4000); // 4 detik
    });
});
