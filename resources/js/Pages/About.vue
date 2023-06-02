<script setup>
import Footer from '@/Components/Footer.vue';
import { Link } from '@inertiajs/vue3';
import Button from 'primevue/button';

</script>

<template>
    <main class="container mx-8 lg:mx-24 xl:mx-32 ">
        <div class="mb-8">
            <Link :href="route('home')">
                <Button label="Kembali" icon="fa fa-arrow-left" severity="warning"/>
            </Link>
        </div>

        <div class="flex flex-col justify-center items-center" >
            <h1 class="" style="font-size: 40px; font-weight: bold;">Meidy Busana Muslim</h1>
            <img src="/images/about.jpeg" alt="" style="height: 200px; width: 200px;" class="mt-12 rounded">
            <div class="text-justify mb-24 mt-12">
                <p  class="mt-4">
                        Pasangan suami istri yang tinggal di Sidoarjo ini mengawali usaha produsen baju muslim anak dari bawah.
                        Berkat
                        kegigihan ditambah kepedulian, usahanya berkembang pesat. Bahkan distributornya tersebar di seluruh
                        Indonesia.
                </p>
                <p  class="mt-4">
                        Meidy mengawali usahanya sejak tahun 2000. Ceritanya, setamat SMA Meidya sempat bekerja di sebuah kantor
                        pengacara yang cukup ternama di Surabaya. Dengan pendidikan akhir SMA, tugasnya sehari-hari hanyalah
                        mengetik
                        bahan-bahan pembelaan atau surat gugatan di pengadilan. Karena tergolong rajin, pemimpin di kantornya
                        itu
                        pun
                        sangat senang padanya. Namun setelah lama bekerja, Meidya merasa kariernya tak bakal berkembang kecuali
                        hanya
                        menjadi tukang ketik saja. Ia pun ingin lebih maju dengan kuliah di Fakultas Hukum di salah satu
                        universitas
                        di
                        Surabaya. Waktu itu, ia ingin bisa menapai karier sebagai pengacara. Tapi setelah berhasil mengantongi
                        ijazah
                        sarjana, tiba-tiba saja ia merasa tidak sreg dengan profesi yang pernah diinginkannya itu.
                </p>
                <p  class="mt-4">
                        Bahkan, Meidya mulai berpikir untuk keluar dan mengembangkan usaha yang lebih baik. Meidya ingat, sejak
                        kecil
                        ia
                        hobi jahit menjahit. Bahkan pernah dikursuskan ayahnya di sebuah sekolah mode di Surabaya. Belum lama
                        mengikuti
                        kursus, ia sudah berani mencoba membuat baby doll dan daster sendiri. Jadi, boleh dibilang dunia jahit
                        menjahit
                        memang sudah lama dikenalnya. Agar semangatnya makin terlecut untuk membuka usaha, ia pun segera keluar
                        dari
                        kantor pengacara tempatnya bekerja selama 7 tahun itu, meskipun oleh sang pemimpin diminta untuk tetap
                        bertahan
                </p>
                <p  class="mt-4">
                        Dengan modal Rp 400.000 dari suaminya yang saat itu bekerja di sebuah bank, Meidya kemudian membeli
                        kain.
                        Lembaran kain-kain itu lalu dibuatkan pola, dipotong, kemudian dijahit. Semua itu dilakukannya sendiri
                        di
                        rumahnya, di sela-sela mengasuh anak pertamanya yang masih bayi. Ketika sudah menjadi daster, ia lalu
                        menjual
                        karyanya itu kepada para tetangga rumahnya. Dan ternyata tetangganya sangat berminat dengan produk
                        pertamanya
                        itu. Karena kalau dilihat dari jahitannya, daster buatannya memang terbilang bagus
                </p>
                <p  class="mt-4">
                        Suatu ketika, salah satu kerabat bersama anak lelakinya datang ke rumah Meidya. Anak itu mengenakan
                        setelan
                        busana muslim yang bagus. Meidya sempat bertanya harganya, ternyata cukup mahal. Padahal melihat bahan
                        maupun
                        modelnya, Meidya yakin bisa menjahit baju seperti itu. Bahkan bisa membuatnya lebih bagus dengan harga
                        yang
                        lebih murah. Setelah kerabatnya pulang, Meidya langsung belanja bahan dan membuat baju muslim anak
                        dengan
                        warna
                        kontras. Lagi-lagi untuk tes pasar, ia uji coba pada tetangganya. Dan ternyata semua tetangganya pun
                        suka.
                        Mereka bahkan tidak pernah lagi membeli baju muslim untuk anaknya di toko, tapi langsung memesan kepada
                        Meidya
                </p>
                <p  class="mt-4">
                        Kini usahanya pun terbilang sukses, Dengan memproduksi busana muslim merek Meidy. Meidya dibantu 40
                        karyawan.
                        Meidya melayani pelanggannya yang datang dari berbagai kota di seluruh provinsi di tanah air. Selain
                        kerja
                        keras, Meidya menyebut sukses usahanya ini tak lepas dari kekuatan doa dan rajin sedekah. Bersama sang
                        suami,
                        Krisnardi Hendro Saputro, ia memang mendirikan usaha ini dengan perjuangan yang keras sekali. Ibarat
                        dengan
                        darah dan air mata, mereka benar-benar merintisnya dari nol.
                </p>
            </div>
        </div>
    </main>
    <Footer />
</template>



