require('./bootstrap');

import Alpine from 'alpinejs';
import 'sweetalert2/dist/sweetalert2.min.css';
import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/bundle';

// Import jQuery dan DataTables
import $ from 'jquery';
import 'datatables.net-dt';

// Inisialisasi Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Inisialisasi DataTables ketika dokumen siap
$(document).ready(function() {
    $('#example').DataTable(); // Ganti '#example' dengan ID tabel Anda
});

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
