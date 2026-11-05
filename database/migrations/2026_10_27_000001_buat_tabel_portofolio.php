<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Semua tabel konten portofolio guru. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $t) {
            $t->string('kunci', 60)->primary();
            $t->text('nilai')->nullable();
        });

        Schema::create('pendidikan', function (Blueprint $t) {
            $t->id();
            $t->string('jenjang', 30);
            $t->string('institusi', 150);
            $t->string('jurusan', 150)->nullable();
            $t->unsignedSmallInteger('tahun_mulai')->nullable();
            $t->unsignedSmallInteger('tahun_selesai')->nullable();
            $t->unsignedSmallInteger('urutan')->default(0);
            $t->timestamps();
        });

        Schema::create('pengalaman', function (Blueprint $t) {
            $t->id();
            $t->string('jabatan', 150);
            $t->string('instansi', 150);
            $t->unsignedSmallInteger('tahun_mulai');
            $t->unsignedSmallInteger('tahun_selesai')->nullable();
            $t->text('deskripsi')->nullable();
            $t->unsignedSmallInteger('urutan')->default(0);
            $t->timestamps();
        });

        Schema::create('sertifikat', function (Blueprint $t) {
            $t->id();
            $t->string('judul', 200);
            $t->string('penyelenggara', 150);
            $t->unsignedSmallInteger('tahun');
            $t->unsignedSmallInteger('jam_pelajaran')->nullable();
            $t->string('berkas')->nullable();
            $t->unsignedSmallInteger('urutan')->default(0);
            $t->timestamps();
        });

        Schema::create('keahlian', function (Blueprint $t) {
            $t->id();
            $t->string('nama', 80);
            $t->string('kelompok', 60)->default('Pedagogik');
            $t->unsignedTinyInteger('level')->default(80);
            $t->unsignedSmallInteger('urutan')->default(0);
            $t->timestamps();
        });

        Schema::create('karya', function (Blueprint $t) {
            $t->id();
            $t->string('judul', 200);
            $t->string('slug', 220)->unique();
            $t->string('jenis', 40);
            $t->unsignedSmallInteger('tahun');
            $t->string('ringkasan', 400)->nullable();
            $t->longText('isi')->nullable();
            $t->string('sampul')->nullable();
            $t->string('berkas')->nullable();
            $t->string('tautan')->nullable();
            $t->unsignedInteger('diunduh')->default(0);
            $t->unsignedInteger('dilihat')->default(0);
            $t->boolean('tampil')->default(true);
            $t->timestamps();
        });

        Schema::create('bahan_ajar', function (Blueprint $t) {
            $t->id();
            $t->string('judul', 200);
            $t->string('slug', 220)->unique();
            $t->string('kelas', 30);
            $t->string('mapel', 80);
            $t->string('deskripsi', 400)->nullable();
            $t->string('berkas')->nullable();
            $t->string('tautan_video')->nullable();
            $t->unsignedInteger('diunduh')->default(0);
            $t->boolean('tampil')->default(true);
            $t->timestamps();
        });

        Schema::create('prestasi', function (Blueprint $t) {
            $t->id();
            $t->string('judul', 200);
            $t->enum('kategori', ['guru', 'siswa'])->default('guru');
            $t->string('tingkat', 30);
            $t->string('peringkat', 60);
            $t->unsignedSmallInteger('tahun');
            $t->string('deskripsi', 400)->nullable();
            $t->string('gambar')->nullable();
            $t->boolean('tampil')->default(true);
            $t->timestamps();
        });

        Schema::create('galeri', function (Blueprint $t) {
            $t->id();
            $t->string('judul', 150);
            $t->string('album', 80)->default('Kegiatan');
            $t->string('gambar');
            $t->date('tanggal')->nullable();
            $t->boolean('tampil')->default(true);
            $t->timestamps();
        });

        Schema::create('artikel', function (Blueprint $t) {
            $t->id();
            $t->string('judul', 200);
            $t->string('slug', 220)->unique();
            $t->string('kategori', 60);
            $t->string('ringkasan', 400)->nullable();
            $t->longText('isi');
            $t->string('sampul')->nullable();
            $t->boolean('tampil')->default(true);
            $t->unsignedInteger('dilihat')->default(0);
            $t->timestamp('terbit_pada')->nullable();
            $t->timestamps();
        });

        Schema::create('testimoni', function (Blueprint $t) {
            $t->id();
            $t->string('nama', 100);
            $t->string('peran', 100);
            $t->string('isi', 500);
            $t->boolean('tampil')->default(true);
            $t->timestamps();
        });

        Schema::create('pesan', function (Blueprint $t) {
            $t->id();
            $t->string('nama', 100);
            $t->string('email', 150);
            $t->string('subjek', 150);
            $t->text('isi');
            $t->boolean('dibaca')->default(false);
            $t->string('ip', 45)->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['pesan', 'testimoni', 'artikel', 'galeri', 'prestasi', 'bahan_ajar', 'karya', 'keahlian', 'sertifikat', 'pengalaman', 'pendidikan', 'pengaturan'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
