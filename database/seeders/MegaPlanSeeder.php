<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MegaPlanSeeder extends Seeder
{
    public function run()
    {
        DB::table('daily_plans')->truncate();
        $data = [];

        // 20 Menu Makanan Nyata (Campuran Kalori Rendah & Tinggi)
        $mealData = [
            ['title' => 'Salad Ayam Lemon', 'cal' => 350, 'instr' => 'Bahan: Dada ayam 150g, selada, tomat, lemon. Cara: Panggang ayam dengan sedikit lada. Potong sayuran, campurkan perasan lemon dan 1 sdt olive oil sebagai dressing.'],
            ['title' => 'Oatmeal Pisang Kayu Manis', 'cal' => 320, 'instr' => 'Bahan: 50g Oat, 1 pisang, bubuk kayu manis. Cara: Rebus oat dengan air/susu rendah lemak. Tambahkan irisan pisang dan taburkan bubuk kayu manis di atasnya.'],
            ['title' => 'Nasi Merah Ikan Bakar', 'cal' => 450, 'instr' => 'Bahan: Nasi merah 100g, Ikan kembung, sambal matah. Cara: Bakar ikan dengan bumbu kunyit lada. Sajikan dengan nasi merah dan sambal matah tanpa minyak goreng panas.'],
            ['title' => 'Shirataki Carbonara Fit', 'cal' => 380, 'instr' => 'Bahan: Mie shirataki, susu skim, telur, smoked beef. Cara: Tumis beef, masukkan mie shirataki. Campur kocokan telur dan susu skim, aduk cepat dalam api kecil hingga mengental.'],
            ['title' => 'Pepes Tahu Jamur', 'cal' => 280, 'instr' => 'Bahan: Tahu putih, jamur tiram, kemangi. Cara: Haluskan tahu, campur dengan jamur dan bumbu halus. Bungkus daun pisang, kukus selama 20 menit lalu bakar sebentar.'],
            ['title' => 'Steak Sirloin & Asparagus', 'cal' => 750, 'instr' => 'Bahan: Sirloin 200g, mentega, asparagus. Cara: Grill steak dengan tingkat kematangan medium. Tumis asparagus dengan sisa jus daging dan bawang putih.'],
            ['title' => 'Pasta Bolognese Gandum', 'cal' => 820, 'instr' => 'Bahan: Spaghetti gandum, daging sapi giling, saus tomat. Cara: Rebus pasta al dente. Tumis daging giling dengan bawang bombay dan saus tomat asli. Campurkan.'],
            ['title' => 'Nasi Kebuli Kambing Fit', 'cal' => 880, 'instr' => 'Bahan: Nasi basmati, daging kambing tanpa lemak. Cara: Masak nasi dengan rempah kebuli. Rebus kambing hingga empuk, lalu panggang sebentar sebelum disajikan.'],
            ['title' => 'Grilled Salmon & Quinoa', 'cal' => 680, 'instr' => 'Bahan: Salmon 150g, Quinoa 100g, Alpukat. Cara: Panggang salmon sisi kulit hingga krispi. Sajikan di atas quinoa rebus dengan irisan alpukat segar.'],
            ['title' => 'Beef Teriyaki Bowl', 'cal' => 720, 'instr' => 'Bahan: Beef slice, saus teriyaki low sodium, wijen. Cara: Tumis daging cepat dengan sedikit saus. Sajikan di atas nasi hangat dengan taburan wijen sangrai.'],
            // ... (Tambahkan hingga 20 menu dengan pola serupa)
        ];

        // 20 Menu Olahraga Nyata
        $workoutData = [
            ['title' => 'Yoga Sun Salutation', 'cal' => 150, 'instr' => 'Panduan: Lakukan rangkaian posisi Mountain Pose ke Forward Fold, lalu Plank ke Cobra. Ulangi 5 siklus untuk kelenturan tubuh di pagi hari.'],
            ['title' => 'HIIT Burner', 'cal' => 500, 'instr' => 'Panduan: 40 detik Burpees, 20 detik istirahat. Lanjut Mountain Climbers 40 detik. Ulangi 4 putaran untuk pembakaran kalori maksimal.'],
            ['title' => 'Jogging Outdoor', 'cal' => 350, 'instr' => 'Panduan: Mulai dengan jalan santai 5 menit. Lari dengan kecepatan stabil (pace 7-8) selama 30 menit. Akhiri dengan pendinginan.'],
            ['title' => 'Latihan Beban Dada', 'cal' => 420, 'instr' => 'Panduan: Dumbbell Bench Press 4 set x 12 repetisi. Fokus pada kontraksi otot dada saat mendorong beban ke atas.'],
            ['title' => 'Squat & Lunges Combo', 'cal' => 380, 'instr' => 'Panduan: 15 Squat diikuti 10 Lunges per kaki. Lakukan 3 set. Fokus pada punggung tegak dan lutut tidak melewati jari kaki.'],
            // ... (Tambahkan hingga 20 olahraga dengan pola serupa)
        ];

        // Generate data secara otomatis
        for ($i = 0; $i < 20; $i++) {
            $meal = $mealData[$i % count($mealData)];
            $data[] = [
                'type' => 'nutrition',
                'title' => $meal['title'],
                'description' => 'Menu nutrisi lengkap yang dihitung berdasarkan target energi harian Anda.',
                'calories' => $meal['cal'],
                'instructions' => $meal['instr'],
                'plan_date' => Carbon::today(),
                'created_at' => now()
            ];
        }

        for ($j = 0; $j < 20; $j++) {
            $work = $workoutData[$j % count($workoutData)];
            $data[] = [
                'type' => 'workout',
                'title' => $work['title'],
                'description' => 'Program latihan fisik untuk meningkatkan metabolisme dan kesehatan jantung.',
                'calories' => $work['cal'],
                'instructions' => $work['instr'],
                'plan_date' => Carbon::today(),
                'created_at' => now()
            ];
        }

        DB::table('daily_plans')->insert($data);
    }
}
