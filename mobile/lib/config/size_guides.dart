class SizeGuides {
  SizeGuides._();

  static const Map<String, dynamic> kaosJerseyData = {
    'title': 'Kaos & Jersey',
    'description':
        'Ukuran standar untuk kaos polos, kaos sablon, dan jersey olahraga.',
    'measurements': ['Panjang (P)', 'Lebar Dada (LD)'],
    'sizes': ['S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL'],
    'values': {
      'Panjang (P)': ['69', '71', '74', '77', '79', '82', '84'],
      'Lebar Dada (LD)': ['48', '51', '54', '56', '59', '62', '64'],
    },
  };

  static const List<Map<String, dynamic>> kemejaCategories = [
    {
      'title': 'TK',
      'sizes': ['S', 'M', 'L', 'XL', 'XXL'],
      'lebar': ['41', '43', '45', '47', '49'],
      'tinggi': ['48', '50', '52', '54', '56'],
    },
    {
      'title': 'SD Kls 1-3',
      'sizes': ['S', 'M', 'L', 'XL', 'XXL'],
      'lebar': ['43', '45', '47', '49', '51'],
      'tinggi': ['52', '54', '56', '58', '60'],
    },
    {
      'title': 'SD Kls 4-6',
      'sizes': ['S', 'M', 'L', 'XL', 'XXL'],
      'lebar': ['45', '47', '49', '51', '53'],
      'tinggi': ['56', '58', '60', '62', '64'],
    },
    {
      'title': 'SMP',
      'sizes': ['S', 'M', 'L', 'XL', 'XXL'],
      'lebar': ['48', '51', '53', '55', '57'],
      'tinggi': ['66', '68', '70', '72', '74'],
    },
    {
      'title': 'Dewasa',
      'sizes': ['S', 'M', 'L', 'XL', 'XXL'],
      'lebar': ['51', '53', '55', '57', '60'],
      'tinggi': ['68', '70', '72', '74', '76'],
    },
  ];

  static const List<Map<String, String>> measureGuide = [
    {
      'title': 'Lebar Dada',
      'desc':
          'Ukur bagian terlebar dari dada secara horizontal, dari sisi kiri ke kanan.',
      'icon': '↔️',
    },
    {
      'title': 'Panjang Badan',
      'desc': 'Ukur dari bahu paling tinggi hingga ujung bawah pakaian.',
      'icon': '↕️',
    },
    {
      'title': 'Lebar (Kemeja)',
      'desc': 'Ukur secara horizontal pada bagian terlebar badan kemeja.',
      'icon': '📏',
    },
    {
      'title': 'Tinggi (Kemeja)',
      'desc': 'Ukur dari bahu sampai ujung bawah kemeja secara vertikal.',
      'icon': '📐',
    },
  ];

  static const List<String> tips = [
    'Gunakan pita ukur (meteran kain) untuk hasil yang akurat.',
    'Ukurlah dalam posisi berdiri tegak dan rileks.',
    'Jika ukuran Anda berada di antara dua ukuran, pilih ukuran yang lebih besar.',
    'Bahan katun combed bisa menyusut ±2-3% setelah pencucian pertama.',
  ];
}
