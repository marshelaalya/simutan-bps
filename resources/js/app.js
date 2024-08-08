require('./bootstrap');

import Alpine from 'alpinejs';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Alpine = Alpine;

Alpine.start();

// Fungsi untuk menampilkan konfirmasi sebelum menghapus
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Mencegah aksi default

            const url = this.getAttribute('href');
            
            // Menggunakan confirm() untuk konfirmasi penghapusan
            const isConfirmed = window.confirm('Anda yakin ingin membatalkan permintaan?');

            if (isConfirmed) {
                window.location.href = url; // Mengarahkan ke URL penghapusan jika dikonfirmasi
            }
        });
    });
});