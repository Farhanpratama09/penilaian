    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Models\SubKriteria;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anchors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SubKriteria::class)->constrained()->onDelete('cascade');
            $table->string('anchor');
            $table->string('bobot');
            $table->timestamps();
        });

        DB::table('anchors')->insert(
            array(
                [
                    'sub_kriteria_id' => 1,
                    'anchor' => 'Selalu memahami dan memenuhi kebutuhan masyarakat maupun siswa dengan baik',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 1,
                    'anchor' => 'Mampu memahami dan memenuhi kebutuhan masyarakat maupun siswa',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 1,
                    'anchor' => 'Terkadang memahami dan memenuhi kebutuhan masyarakat maupun siswa',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 1,
                    'anchor' => 'Jarang memahami dan memenuhi kebutuhan masyarakat maupun siswa',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 1,
                    'anchor' => 'Tidak pernah memahami dan memenuhi kebutuhan masyarakat maupun siswa',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 2,
                    'anchor' => 'Selalu bersikap ramah kepada atasan, rekan, dan siswa diluar maupun di lingkungan sekolah, sangat cekatan, solutif, dan dapat diandalkan',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 2,
                    'anchor' => 'Bersikap ramah kepada atasan, rekan, dan siswa diluar maupun di lingkungan sekolah, cekatan, solutif, dan dapat diandalkan',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 2,
                    'anchor' => 'Terkadang bersikap ramah kepada atasan, rekan, dan siswa diluar maupun di lingkungan sekolah, cekatan, solutif, dan dapat diandalkan',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 2,
                    'anchor' => 'Jarang bersikap ramah kepada atasan, rekan, dan siswa diluar maupun di lingkungan sekolah, cekatan, solutif, dan dapat diandalkan',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 2,
                    'anchor' => 'Tidak pernah bersikap ramah kepada atasan, rekan, dan siswa diluar maupun di lingkungan sekolah, cekatan, solutif, dan dapat diandalkan',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 3,
                    'anchor' => 'Selalu melakukan perbaikan tiada henti dalam meningkatkan keterampilan agar dapat memenuhi proses pembelajaran yang layak',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 3,
                    'anchor' => 'Melakukan perbaikan tiada henti dalam meningkatkan keterampilan agar dapat memenuhi proses pembelajaran yang layak',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 3,
                    'anchor' => 'Terkadang melakukan perbaikan tiada henti dalam meningkatkan keterampilan agar dapat memenuhi proses pembelajaran yang layak',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 3,
                    'anchor' => 'Jarang melakukan perbaikan tiada henti dalam meningkatkan keterampilan agar dapat memenuhi proses pembelajaran yang layak',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 3,
                    'anchor' => 'Tidak pernah melakukan perbaikan tiada henti dalam meningkatkan keterampilan agar dapat memenuhi proses pembelajaran yang layak',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 4,
                    'anchor' => 'Selalu melaksanakan tugas yang diberikan dengan jujur, bertanggung jawab, cermat, disipilin saat pengumpulan laporan, selalu hadir tepat waktu dan berintergasi tinggi',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 4,
                    'anchor' => 'Melaksanakan tugas yang diberikan dengan jujur, bertanggung jawab, cermat, disipilin saat pengumpulan laporan, selalu hadir tepat waktu dan berintergasi tinggi',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 4,
                    'anchor' => 'Terkadang melaksanakan tugas yang diberikan dengan jujur, bertanggung jawab, cermat, disipilin saat pengumpulan laporan, selalu hadir tepat waktu dan berintergasi tinggi',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 4,
                    'anchor' => 'Jarang melaksanakan tugas yang diberikan dengan jujur, bertanggung jawab, cermat, disipilin saat pengumpulan laporan, selalu hadir tepat waktu dan berintergasi tinggi',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 4,
                    'anchor' => 'Tidak pernah melaksanakan tugas yang diberikan dengan jujur, bertanggung jawab, cermat, disipilin saat pengumpulan laporan, selalu hadir tepat waktu dan berintergasi tinggi',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 5,
                    'anchor' => 'Selalu menggunakan kekayaan barang milik negara secara bertanggung jawab dalam membantu proses pembelajaran efektif, dan efisien',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 5,
                    'anchor' => 'Menggunakan kekayaan barang milik negara secara bertanggung jawab dalam membantu proses pembelajaran efektif, dan efisien',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 5,
                    'anchor' => 'Terkadang menggunakan kekayaan barang milik negara secara bertanggung jawab dalam membantu proses pembelajaran efektif, dan efisien',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 5,
                    'anchor' => 'Jarang menggunakan kekayaan barang milik negara secara bertanggung jawab dalam membantu proses pembelajaran efektif, dan efisien',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 5,
                    'anchor' => 'Tidak pernah menggunakan kekayaan barang milik negara secara bertanggung jawab dalam membantu proses pembelajaran efektif, dan efisien',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 6,
                    'anchor' => 'Selalu bertanggungjawab atas jabatan yang diterima dan tidak pernah merugikan pihak manapun',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 6,
                    'anchor' => 'bertanggungjawab atas jabatan yang diterima dan tidak pernah merugikan pihak manapun',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 6,
                    'anchor' => 'Terkadang bertanggungjawab atas jabatan yang diterima dan tidak pernah merugikan pihak manapun',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 6,
                    'anchor' => 'Jarang bertanggungjawab atas jabatan yang diterima dan tidak pernah merugikan pihak manapun',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 6,
                    'anchor' => 'Tidak pernah bertanggungjawab atas jabatan yang diterima dan tidak pernah merugikan pihak manapun',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 7,
                    'anchor' => 'Selalu meningkatkan kompetensi diri untuk menjawab tantangan yang selalu berubah',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 7,
                    'anchor' => 'Meningkatkan kompetensi diri untuk menjawab tantangan yang selalu berubah',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 7,
                    'anchor' => 'Terkadang meningkatkan kompetensi diri untuk menjawab tantangan yang selalu berubah',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 7,
                    'anchor' => 'Jarang meningkatkan kompetensi diri untuk menjawab tantangan yang selalu berubah',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 7,
                    'anchor' => 'Tidak pernah meningkatkan kompetensi diri untuk menjawab tantangan yang selalu berubah',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 8,
                    'anchor' => 'Selalu membantu siswa maupun rekan belajar agar dapat mengembangkan potensi dan bakatnya',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 8,
                    'anchor' => 'Membantu siswa maupun rekan belajar agar dapat mengembangkan potensi dan bakatnya',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 8,
                    'anchor' => 'Terkadang membantu siswa maupun rekan belajar agar dapat mengembangkan potensi dan bakatnya',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 8,
                    'anchor' => 'Jarang membantu siswa maupun rekan belajar agar dapat mengembangkan potensi dan bakatnya',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 8,
                    'anchor' => 'Tidak pernah membantu siswa maupun rekan belajar agar dapat mengembangkan potensi dan bakatnya',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 9,
                    'anchor' => 'Selalu melaksanakan tugas dengan kualitas terbaik bahkan kualitasnya melebihi dari target yang ditetapkan sekolah',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 9,
                    'anchor' => 'Melaksanakan tugas dengan kualitas terbaik bahkan kualitasnya melebihi dari target yang ditetapkan sekolah',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 9,
                    'anchor' => 'Terkadang melaksanakan tugas dengan kualitas terbaik bahkan kualitasnya melebihi dari target yang ditetapkan sekolah',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 9,
                    'anchor' => 'Jarang melaksanakan tugas dengan kualitas terbaik bahkan kualitasnya melebihi dari target yang ditetapkan sekolah',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 9,
                    'anchor' => 'Tidak pernah melaksanakan tugas dengan kualitas terbaik bahkan kualitasnya melebihi dari target yang ditetapkan sekolah',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 10,
                    'anchor' => 'Selalu menghargai setiap orang apapun latar belakangnya',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 10,
                    'anchor' => 'Menghargai setiap orang apapun latar belakangnya',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 10,
                    'anchor' => 'Terkadang menghargai setiap orang apapun latar belakangnya',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 10,
                    'anchor' => 'Jarang menghargai setiap orang apapun latar belakangnya',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 10,
                    'anchor' => 'Tidak pernah menghargai setiap orang apapun latar belakangnya',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 11,
                    'anchor' => 'Selalu berinisiatif menolong orang lain baik didalam maupun diluar sekolah',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 11,
                    'anchor' => 'Berinisiatif menolong orang lain baik didalam maupun diluar sekolah',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 11,
                    'anchor' => 'Terkadang berinisiatif menolong orang lain baik didalam maupun diluar sekolah',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 11,
                    'anchor' => 'Jarang berinisiatif menolong orang lain baik didalam maupun diluar sekolah',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 11,
                    'anchor' => 'Tidak pernah berinisiatif menolong orang lain baik didalam maupun diluar sekolah',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 12,
                    'anchor' => 'Selalu membangun lingkungan kerja yang kondusif disekolah',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 12,
                    'anchor' => 'Membangun lingkungan kerja yang kondusif disekolah',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 12,
                    'anchor' => 'Terkadang membangun lingkungan kerja yang kondusif disekolah',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 12,
                    'anchor' => 'Jarang membangun lingkungan kerja yang kondusif disekolah',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 12,
                    'anchor' => 'Tidak pernah membangun lingkungan kerja yang kondusif disekolah',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 13,
                    'anchor' => 'Selalu memegang teguh dan mengamalkan ideologi Pancasila, Undang-Undang Dasar Negara Republik Indonesia Tahun 1945 dalam kehidupan sehari-hari, setia kepada Negara Kesatuan Republik Indonesia serta pemerintahan yang sah',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 13,
                    'anchor' => 'Memegang teguh dan mengamalkan ideologi Pancasila, Undang-Undang Dasar Negara Republik Indonesia Tahun 1945 dalam kehidupan sehari-hari, setia kepada Negara Kesatuan Republik Indonesia serta pemerintahan yang sah',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 13,
                    'anchor' => 'Terkadang memegang teguh dan mengamalkan ideologi Pancasila, Undang-Undang Dasar Negara Republik Indonesia Tahun 1945 dalam kehidupan sehari-hari, setia kepada Negara Kesatuan Republik Indonesia serta pemerintahan yang sah',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 13,
                    'anchor' => 'Jarang memegang teguh dan mengamalkan ideologi Pancasila, Undang-Undang Dasar Negara Republik Indonesia Tahun 1945 dalam kehidupan sehari-hari, setia kepada Negara Kesatuan Republik Indonesia serta pemerintahan yang sah',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 13,
                    'anchor' => 'Tidak pernah memegang teguh dan mengamalkan ideologi Pancasila, Undang-Undang Dasar Negara Republik Indonesia Tahun 1945 dalam kehidupan sehari-hari, setia kepada Negara Kesatuan Republik Indonesia serta pemerintahan yang sah',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 14,
                    'anchor' => 'Selalu menjaga nama baik sesama ASN, Pimpinan, Instansi, dan Negara baik dimanapun',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 14,
                    'anchor' => 'Menjaga nama baik sesama ASN, Pimpinan, Instansi, dan Negara baik dimanapun',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 14,
                    'anchor' => 'Terkadang menjaga nama baik sesama ASN, Pimpinan, Instansi, dan Negara baik dimanapun',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 14,
                    'anchor' => 'Jarang menjaga nama baik sesama ASN, Pimpinan, Instansi, dan Negara baik dimanapun',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 14,
                    'anchor' => 'Tidak pernah menjaga nama baik sesama ASN, Pimpinan, Instansi, dan Negara baik dimanapun',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 15,
                    'anchor' => 'Selalu menjaga rahasia jabatan dan negara',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 15,
                    'anchor' => 'Menjaga rahasia jabatan dan negara',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 15,
                    'anchor' => 'Terkadang menjaga rahasia jabatan dan negara',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 15,
                    'anchor' => 'Jarang menjaga rahasia jabatan dan negara',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 15,
                    'anchor' => 'Tidak pernah menjaga rahasia jabatan dan negara',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 16,
                    'anchor' => 'Selalu cepat menyesuaikan diri mengadapi perubahan yang terjadi disekitar',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 16,
                    'anchor' => 'Cepat menyesuaikan diri mengadapi perubahan yang terjadi disekitar',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 16,
                    'anchor' => 'Terkadang cepat menyesuaikan diri mengadapi perubahan yang terjadi disekitar',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 16,
                    'anchor' => 'Jarang cepat menyesuaikan diri mengadapi perubahan yang terjadi disekitar',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 16,
                    'anchor' => 'Tidak pernah cepat menyesuaikan diri mengadapi perubahan yang terjadi disekitar',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 17,
                    'anchor' => 'Selalu berinovasi dan mengembangkan kreativitas untuk terus membantu menciptakan pembelajaran yang efektif dan efisien',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 17,
                    'anchor' => 'Berinovasi dan mengembangkan kreativitas untuk terus membantu menciptakan pembelajaran yang efektif dan efisien',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 17,
                    'anchor' => 'Terkadang berinovasi dan mengembangkan kreativitas untuk terus membantu menciptakan pembelajaran yang efektif dan efisien',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 17,
                    'anchor' => 'Jarang berinovasi dan mengembangkan kreativitas untuk terus membantu menciptakan pembelajaran yang efektif dan efisien',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 17,
                    'anchor' => 'Tidak pernah berinovasi dan mengembangkan kreativitas untuk terus membantu menciptakan pembelajaran yang efektif dan efisien',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 18,
                    'anchor' => 'Selalu bertindak proaktif untuk terus meningkatkan kualitas pembelajaran bahkan melebihi ekspektasi yang diharapkan',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 18,
                    'anchor' => 'Bertindak proaktif untuk terus meningkatkan kualitas pembelajaran bahkan melebihi ekspektasi yang diharapkan',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 18,
                    'anchor' => 'Terkadang bertindak proaktif untuk terus meningkatkan kualitas pembelajaran bahkan melebihi ekspektasi yang diharapkan',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 18,
                    'anchor' => 'Jarang bertindak proaktif untuk terus meningkatkan kualitas pembelajaran bahkan melebihi ekspektasi yang diharapkan',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 18,
                    'anchor' => 'Tidak pernah bertindak proaktif untuk terus meningkatkan kualitas pembelajaran bahkan melebihi ekspektasi yang diharapkan',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 19,
                    'anchor' => 'Selalu memberi kesempatan kepada berbagai pihak untuk berkontribusi dalam kegiatan baik didalam maupun diluar lingkungan sekolah',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 19,
                    'anchor' => 'Memberi kesempatan kepada berbagai pihak untuk berkontribusi dalam kegiatan baik didalam maupun diluar lingkungan sekolah',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 19,
                    'anchor' => 'Terkadang memberi kesempatan kepada berbagai pihak untuk berkontribusi dalam kegiatan baik didalam maupun diluar lingkungan sekolah',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 19,
                    'anchor' => 'Jarang memberi kesempatan kepada berbagai pihak untuk berkontribusi dalam kegiatan baik didalam maupun diluar lingkungan sekolah',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 19,
                    'anchor' => 'Tidak pernah memberi kesempatan kepada berbagai pihak untuk berkontribusi dalam kegiatan baik didalam maupun diluar lingkungan sekolah',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 20,
                    'anchor' => 'Selalu berkoordinasi dan berkomunikasi dengan baik kepada sesama rekan, mengahrgai pendapat orang lain dan dapat menyelesaikan masalah kerja tim dengan konsisten',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 20,
                    'anchor' => 'Berkoordinasi dan berkomunikasi dengan baik kepada sesama rekan, mengahrgai pendapat orang lain dan dapat menyelesaikan masalah kerja tim dengan konsisten',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 20,
                    'anchor' => 'Terkadang berkoordinasi dan berkomunikasi dengan baik kepada sesama rekan, mengahrgai pendapat orang lain dan dapat menyelesaikan masalah kerja tim dengan konsisten',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 20,
                    'anchor' => 'Jarang berkoordinasi dan berkomunikasi dengan baik kepada sesama rekan, mengahrgai pendapat orang lain dan dapat menyelesaikan masalah kerja tim dengan konsisten',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 20,
                    'anchor' => 'Tidak pernah berkoordinasi dan berkomunikasi dengan baik kepada sesama rekan, mengahrgai pendapat orang lain dan dapat menyelesaikan masalah kerja tim dengan konsisten',
                    'bobot' => 1,
                ],
                [
                    'sub_kriteria_id' => 21,
                    'anchor' => 'Selalu memanfaakan berbagai sumberdaya untuk tujuan bersama',
                    'bobot' => 5,
                ],
                [
                    'sub_kriteria_id' => 21,
                    'anchor' => 'Memanfaakan berbagai sumberdaya untuk tujuan bersama',
                    'bobot' => 4,
                ],
                [
                    'sub_kriteria_id' => 21,
                    'anchor' => 'Terkadang memanfaakan berbagai sumberdaya untuk tujuan bersama',
                    'bobot' => 3,
                ],
                [
                    'sub_kriteria_id' => 21,
                    'anchor' => 'Jarang memanfaakan berbagai sumberdaya untuk tujuan bersama',
                    'bobot' => 2,
                ],
                [
                    'sub_kriteria_id' => 21,
                    'anchor' => 'Tidak pernah memanfaakan berbagai sumberdaya untuk tujuan bersama',
                    'bobot' => 1,
                ],
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anchors');
    }
};
