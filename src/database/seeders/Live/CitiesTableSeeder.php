<?php

namespace Database\Seeders\Live;

use App\Models\Cart\Cart;
use App\Models\Location\Address;
use App\Models\Location\City;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $map = [
            'ΑΘΗΝΑ'                  => ['Athena', 'Athina', 'Athen', 'ATHNA', 'ATINA', 'ΑΧΑΡΝΑΙ', 'Αχαρνες', 'ΑΧΑΡΝΑΙ -ΑΘΗΝΑΙ', 'ΑΧΑΡΝΑΙ - ΑΘΗΝΑ', 'Ηλιουπολη', 'ΗΛΙΟΥΠΟΛΗ / ΑΘΗΝΑ', 'ΗΛΙΟΥΠΟΛΗ ΑΘΗΝΑ', 'ΗΛΙΟΥΠΟΛΗ ΑΤΤΙΚΗ', 'ΗΛΙΟΥΠΟΛΗ ΑΤΤΙΚΗΣ', 'Ηλιούπολη, Αττική', 'ΗΛΙΟΥΠΟΛΗ,ΑΤΤΙΚΗ', 'ΖΩΓΡΑΦΟΥ', 'ζωγραφου', 'Ζωγραφου/Αθηνα', 'Ζωργάφου - Αθηνα'],
            'ΘΕΣΣΑΛΛΟΝΙΚΗ'           => ['θεσσαλονικης', 'Thessaloniki', 'Θεσ/νικη', 'ΘΕΣ/ΝΙΚΗΣ', 'Saloniki', 'THESSALONIKH', 'Tesaloniki', 'Θεσαλονικη', 'Θεσαλονικκι', 'Θεσσαλινίκη', 'ΘΕΣΣΑΛΛΟΝΙΚΗ'],
            'ΗΡΑΚΛΕΙΟ'               => ['ηρακλειο κρητης', 'Heraklion', 'Heraklion Crete', 'Herakleion Crete', 'Heraklio', 'Heraklion Kreta', 'hrakleio', 'iraklio kriti', 'Irakleio kritis', 'Iraklio', 'iraklio kriti', 'ΗΡΑΚΛΕΙΟΥ', 'ΗΡΑΚΛΕΊΟΥ ΚΡΗΤΗΣ', 'Ηρακλειον- Κρητης', 'ΗΡΑΚΛΕΙΟ ΚΡΗΤΗΣ ΑΛΙΚΑΡΝΑΣΣΟΣ', 'Ηράκλειο Κρήτη', 'Ηράκλειο Κρήτης Δήμος Αρχανών Αστερουσίων', 'ΗΑΡΑΚΛΕΙΟ ΚΡΗΤΗΣ', 'Ηράκλειο  Κρητης', 'Κρητη'],
            "ΧΑΝΙΑ"                  => ['χανιων', 'Chania', 'Xania', 'χανια,κρητη', 'χανια-κρητης', 'χανια κρητη', 'χανια κρητης'],
            'ΚΑΒΑΛΑ'                 => ['kavala', 'ΚΑΒΑΛΑΣ', 'Kαβαλα'],
            'ΑΛΕΞΑΝΔΡΟΥΠΟΛΗ'         => ['Alexandroupoli', 'Alexandroupolis', 'Αελεξανδρουπολη', 'ΑΛΕΞΑΝΔΡΟΥΠΟΛΗ - ΑΠΑΛΟΣ', 'Αλεξανδρούπολη,Έβρος'],
            'ΑΛΕΞΆΝΔΡΕΙΑ'            => ['Αλεξάνδρεια Ημαθίας'],
            'ΑΡΓΟΣ'                  => ['argos'],
            'ΑΡΓΟΣΤΟΛΙ'              => ['argostoli'],
            'ΑΡΤΑ'                   => ['Aρτα', 'ARTA', 'Αρτας'],
            'ΙΩΑΝΝΙΝΑ'               => ['Katcikas, Ioaninnon', 'Katcika, Ioaninnon', 'Ανατολή Ιωαννίνων', 'ioannina', 'Katsika, Ioanninon', 'Katcika, Ioanninon', 'Katcikas, Ioannina', 'Katcikas, Ioanninon', 'katsika, ioaninnon'],
            'Ερμιόνη'                => ['ermioni'],
            'ΔΡΑΜΑ'                  => ['drama'],
            'ΔΙΔΥΜΟΤΕΙΧΟ'            => ['Didimoteichon', 'Didimoteixo'],
            'ΚΩΣ'                    => ['Kos dodekanisa', 'Kos', 'Kws', 'Kos dodekanisa', 'kos'],
            'ΚΟΖΑΝΙ'                 => ['KOZANH', 'Kozani'],
            'ΛΑΡΙΣΑ'                 => ['larisa', 'ΛΑΡΙΣΣΑ'],
            'ΛΕΥΚΑΔΑ'                => ['Lefkada'],
            'ΛΙΤΟΧΩΡΟ'               => ['Litochoro'],
            'ΝΕΑ ΜΟΥΔΑΝΙΑ'           => ['NEA MOUDANIA', 'Ν Μουδανια'],
            'ΤΡΙΚΑΛΑ'                => ['Trikala'],
            'ΤΡΙΠΟΛΗ'                => ['Tripoli'],
            'ΒΕΡΟΙΑ'                 => ['Veroia'],
            'ΒΟΝΙΤΣΑ'                => ['Vonitsa', 'Vonitsa'],
            'ΖΑΚΥΝΘΟΣ'               => ['Zakynthos'],
            'ΑΜΦΙΣΣΑ'                => ['ΑΜΦΙΣΣΑΣ'],
            'ΚΕΡΚΥΡΑ'                => ['Marathias/Kerkyra', 'kerkyra', 'Kerkira', 'KERKURA', 'ΚΕΡΚΥΡΑΣ', 'Κέρκyρα', 'Λευκίμμη Κέρκυρα', 'Λευκίμμη Κέρκυρας', 'Λευκίμμη, Κέρκυρα'],
            'ΑΓΡΙΝΙΟ'                => ['AGRINIO'],
            'ΑΛΜΥΡΟΣ'                => ['Almiros', 'ALMYPOS', 'almyros magnesia', 'Almyros-euxeinoupoli', 'ΑΛΜΥPOS', 'Αλμυρό', 'Αλμυρός Βολου', 'Αλμυρός μαγνησιας', 'ΑΛΜΥΡΟΣ-ΒΟΛΟΥ', 'Αλμυρού Μαγνησίας', 'Almyros'],
            'ΑΜΑΛΙΑΔΑ'               => ['amaliada'],
            'ΕΛΑΣΣΟΝΑ'               => ['Elassona'],
            'ΕΠΑΝΟΜΗ'                => ['EPANOMI THES/NIKIS'],
            'ΔΛΩΡΙΝΑ'                => ['Florina'],
            'ΓΡΕΒΕΜΑ'                => ['Grevena', 'ΓΡΕΒΕΜΑ'],
            'ΙΕΡΑΠΕΤΡΑ'              => ['Ierapetra'],
            'Καλύβια Θορικού'        => ['KALIVIA'],
            'ΚΑΛΑΜΑΤΑ'               => ['KALAMATA'],
            'ΚΑΛΥΜΝΟΣ'               => ['Kalymnos'],
            'ΚΑΡΔΙΤΣΑ'               => ['Karditsa'],
            'ΜΕΓΑΡΑ'                 => ['megara'],
            'ΛΥΓΟΥΡΙΟ'               => ['Lygourio'],
            'ΛΟΥΤΡΑΚΙ'               => ['loutraki', 'loytraki'],
            'ΛΕΥΚΙΜΜΗ'               => ['Lefkimmi'],
            'ΛΑΜΙΑ'                  => ['lamia'],
            'ΛΑΓΚΑΔΑΣ'               => ['lagkadas', 'lagadas'],
            'ΞΑΝΘΗ'                  => ['Ksanthi'],
            'ΚΟΜΟΤΗΝΗ'               => ['KOMOTHNH', 'Komotini', 'KOMOTINI'],
            'ΚΟΡΙΝΘΟΣ'               => ['Korinthos'],
            'ΚΙΛΚΙΣ'                 => ['Kilkis'],
            'ΚΑΣΤΟΡΙΑ'               => ['kastoria', 'nestorio/kastoria'],
            'ΚΙΣΑΜΟΣ'                => ['Kasteli Kissamos'],
            'ΚΑΣΣΑΝΔΡΕΙΑ'            => ['KASSANDRA', 'Kassandria', 'Halkidiki/Kassandra/POLIHRONO', 'Halkidiki/Kassandra/POLIHRONO', 'Halkidiki/Kassandra/POLIHRONO/ meta Kassandra Palace'],
            'ΝΑΟΥΣΑ'                 => ['Naousa', 'Νάουσα ημαθιας', 'ΝΑΟΥΣΑ....ΗΜΑΘΙΑΣ', 'Νάουσα-Ημαθιας', 'Ναουσης'],
            'ΝΑΥΠΛΙΟ'                => ['Nauplio', 'Ναύπλιον', 'ναυπλιο- τολο', 'Ναυπλιου'],
            'ΝΑΞΟΣ'                  => ['Naxos', 'Ναχος'],
            'Νεάπολη Λακωνίας'       => ['NEAPOLI', 'Neapolis'],
            'Νεάπολη Κοζάνης'        => ['neapoli-kozanis', 'NEAPOLI KOZANIS GREECE'],
            'ΠΑΤΡΑ'                  => ['patra'],
            'ΠΕΡΙΣΤΕΡΙ'              => ['Peristeri'],
            'ΠΟΛΥΓΥΡΟΣ'              => ['POLIGIROS'],
            'ΠΤΟΛΕΜΑΙΔΑ'             => ['ptolemaida'],
            'ΠΡΕΒΕΖΑ'                => ['PREVEZA'],
            'ΡΕΘΥΜΝΟΣ'               => ['Rethimno', 'Retimno'],
            'ΡΟΔΟΣ'                  => ['Rodos', 'Rhodes', 'rhodos', 'rodos soroni', 'Rodos Ialisos', 'Αφαντου Ροδος', 'ΑΦΑΝΤΟΥ-ΡΟΔΟΣ', 'Αφάντου Ρόδου', 'Αφάντου ΡΟΔΟΔ', 'Κρεμαστή Ρόδος', 'ΚΡΕΜΑΣΤΗ ΡΟΔΟΥ'],
            'ΣΑΛΑΜΙΝΑ'               => ['salamina'],
            'ΣΑΜΟΣ'                  => ['Samos', 'ΜΥΤΙΛΗΝΙΟΙ', 'ΜΥΤΙΛΗΝΙΟΙ ΣΑΜΟΥ'],
            'ΚΑΤΕΡΙΝΗ'               => ['Katerini'],
            'ΜΕΤΣΟΒΟ'                => ['METSOVO'],
            'ΣΧΗΜΑΤΑΡΙ'              => ['sximatari'],
            'ΣΠΑΡΤΗ'                 => ['Sparti'],
            'ΣΚΟΠΕΛΟΣ'               => ['skopelos', 'ΣΚΟΠΕΛΟΣ   ΜΑΓΝΗΣΙΑΣ', 'Σκόπελος Μαγνησίας', 'amintaio'],
            'ΣΚΙΑΘΟΣ'                => ['Skiathos'],
            'ΑΜΥΝΤΑΙΟ'               => ['ΑΜΥΝΤΑΙΟ  ΦΛΩΡΙΝΑΣ', 'Αμυνταιο Φλώρινας'],
            'ΑΥΛΩΝΑΣ'                => ['Αυλώνας Αττικής'],
            'ΒΑΣΙΛΙΚΑ ΘΕΣΣΑΛΛΟΝΙΚΗΣ' => ['ΒΑΣΙΛΙΚΑ ΘΕΣ/ΝΙΚΗΣ'],
            'ΚΡΗΝΙΔΕΣ'               => ['Κρηνίδες Καβάλα', 'Κρηνιδες Καβάλας'],
            'Λευκώνας Σερρών'        => ['ΛΕΥΚΩΝΑΣ'],
            'ΝΕΑ ΠΕΡΑΜΟΣ'            => ['Ν ΠΕΡΑΜΟΣ'],
            'ΧΑΛΑΣΤΡΑ'               => ['Χαλαστρα Θεσσαλονικη'],
            'ΧΑΛΚΗΔΟΝΑ'              => ['Χαλκηδονα'],
            'ΧΑΛΚΙΔΑ'                => ['Χαλκίδα - Εύβοια'],
            'ΑΝΑΦΗ'                  => ['Anafi'],
        ];

        $cities = City::all();
        foreach ($cities as $city) {
            Address::where('temp_city', $city->name)->update(['city_id' => $city->id]);

            if (array_key_exists($city->name, $map)) {
                Address::whereIn('temp_city', $map[$city->name])->update(['city_id' => $city->id]);
            }
        }

        echo Cart::submitted()->whereHas('shippingAddress', function ($q) {
            $q->whereNull('city_id');
        })->count(), PHP_EOL;

//        echo Address::whereNull('city_id')->count() . ' to go', PHP_EOL;
//        $more = Address
//            ::whereNull('city_id')
//            ->whereNotIn('temp_city', ['πειραιας', 'περιστερι', 'νικαια', 'αχαρνας', 'αχαρναι', ' αρτεμις', 'ΑΡΤΕΜΙΣ', 'κερατσινι', 'αττικη', 'μεταμορφωση'])
//            ->selectRaw('temp_city, COUNT(*) as cnt')->groupBy('temp_city')->orderByDesc('cnt')->limit(5)->get();
//        foreach ($more as $m) {
//            echo $m->temp_city . '(' . $m->cnt . ')', PHP_EOL;
//        }
    }
}
