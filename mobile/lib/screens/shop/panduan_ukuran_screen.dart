import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../config/theme.dart';
import '../../utils/navigation_helper.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/common/mitologi_page_shell.dart';
import '../../widgets/common/mitologi_scaffold.dart';

class PanduanUkuranScreen extends StatefulWidget {
  const PanduanUkuranScreen({super.key});

  @override
  State<PanduanUkuranScreen> createState() => _PanduanUkuranScreenState();
}

class _PanduanUkuranScreenState extends State<PanduanUkuranScreen>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;

  // Data from web: Kaos & Jersey — S to 4XL
  final Map<String, dynamic> kaosJerseyData = {
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

  // Data from web: Kemeja — 5 categories
  final List<Map<String, dynamic>> kemejaCategories = [
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

  final List<Map<String, String>> measureGuide = [
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

  final List<String> tips = [
    'Gunakan pita ukur (meteran kain) untuk hasil yang akurat.',
    'Ukurlah dalam posisi berdiri tegak dan rileks.',
    'Jika ukuran Anda berada di antara dua ukuran, pilih ukuran yang lebih besar.',
    'Bahan katun combed bisa menyusut ±2-3% setelah pencucian pertama.',
  ];

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  Future<void> _openWhatsApp() async {
    final url = Uri.parse(
      'https://wa.me/6281322170902?text=Halo%2C%20saya%20ingin%20konsultasi%20ukuran',
    );
    if (await canLaunchUrl(url)) {
      await launchUrl(url, mode: LaunchMode.externalApplication);
    }
  }

  Widget _buildKaosSection() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // T-shirt illustration + table
        Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Illustration
            Expanded(
              flex: 2,
              child: Container(
                height: 200,
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                    colors: [Color(0xFFF8FAFC), Color(0xFFF1F5F9)],
                  ),
                  borderRadius: BorderRadius.circular(24),
                  border: Border.all(color: const Color(0xFFE2E8F0)),
                ),
                child: Center(
                  child: Stack(
                    alignment: Alignment.center,
                    children: [
                      // SVG T-shirt
                      CustomPaint(
                        size: const Size(160, 176),
                        painter: TShirtPainter(),
                      ),
                      // Labels
                      Positioned(
                        top: 60,
                        child: Container(
                          padding: const EdgeInsets.symmetric(
                            horizontal: 8,
                            vertical: 4,
                          ),
                          decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.9),
                            borderRadius: BorderRadius.circular(4),
                            border: Border.all(color: const Color(0xFFE2E8F0)),
                          ),
                          child: const Text(
                            'Lebar Dada',
                            style: TextStyle(
                              fontSize: 10,
                              fontWeight: FontWeight.bold,
                              color: AppTheme.primary,
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ],
        ),
        const SizedBox(height: 16),
        // Title
        Text(
          'Size Chart — ${kaosJerseyData['title']}',
          style: const TextStyle(
            fontSize: 20,
            fontWeight: FontWeight.bold,
            color: AppTheme.primary,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          kaosJerseyData['description'],
          style: TextStyle(fontSize: 14, color: AppTheme.onSurfaceVariant),
        ),
        const SizedBox(height: 16),
        // Table
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: Container(
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: const Color(0xFFE2E8F0)),
            ),
            child: Table(
              defaultColumnWidth: const FixedColumnWidth(70),
              children: [
                // Header
                TableRow(
                  decoration: const BoxDecoration(
                    color: AppTheme.primary,
                    borderRadius: BorderRadius.vertical(
                      top: Radius.circular(16),
                    ),
                  ),
                  children: [
                    _buildTableHeaderCell('Size Chart'),
                    ...(kaosJerseyData['sizes'] as List<String>).map(
                      (s) => _buildTableHeaderCell(s),
                    ),
                  ],
                ),
                // Data rows
                ...(kaosJerseyData['measurements'] as List<String>)
                    .asMap()
                    .entries
                    .map((entry) {
                      final measurement = entry.value;
                      final values =
                          (kaosJerseyData['values']
                              as Map<String, List<String>>)[measurement]!;
                      return TableRow(
                        decoration: BoxDecoration(
                          color: entry.key % 2 == 0
                              ? Colors.white
                              : const Color(0xFFF8FAFC),
                        ),
                        children: [
                          _buildTableCell(
                            measurement,
                            isHeader: true,
                            prefix: measurement.contains('Panjang')
                                ? 'P'
                                : 'LD',
                          ),
                          ...values.map((v) => _buildTableCell('$v cm')),
                        ],
                      );
                    }),
              ],
            ),
          ),
        ),
        const SizedBox(height: 8),
        Text(
          '* Toleransi ukuran ±1-2 cm. Semua ukuran dalam centimeter (cm).',
          style: TextStyle(fontSize: 12, color: AppTheme.onSurfaceMuted),
        ),
      ],
    );
  }

  Widget _buildKemejaSection() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Illustration
        Container(
          height: 200,
          decoration: BoxDecoration(
            gradient: const LinearGradient(
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
              colors: [Color(0xFFF8FAFC), Color(0xFFF1F5F9)],
            ),
            borderRadius: BorderRadius.circular(24),
            border: Border.all(color: const Color(0xFFE2E8F0)),
          ),
          child: Center(
            child: Stack(
              alignment: Alignment.center,
              children: [
                CustomPaint(
                  size: const Size(160, 192),
                  painter: ShirtPainter(),
                ),
                Positioned(
                  top: 70,
                  child: Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 8,
                      vertical: 4,
                    ),
                    decoration: BoxDecoration(
                      color: Colors.white.withValues(alpha: 0.9),
                      borderRadius: BorderRadius.circular(4),
                      border: Border.all(color: const Color(0xFFE2E8F0)),
                    ),
                    child: const Text(
                      'Lebar',
                      style: TextStyle(
                        fontSize: 10,
                        fontWeight: FontWeight.bold,
                        color: AppTheme.primary,
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
        const SizedBox(height: 16),
        // Title
        const Text(
          'Size Chart — Kemeja',
          style: TextStyle(
            fontSize: 20,
            fontWeight: FontWeight.bold,
            color: AppTheme.primary,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          'Ukuran untuk kemeja seragam dari TK hingga Dewasa.',
          style: TextStyle(fontSize: 14, color: AppTheme.onSurfaceVariant),
        ),
        const SizedBox(height: 16),
        // Category tables in grid
        ...kemejaCategories.map((cat) {
          return Container(
            margin: const EdgeInsets.only(bottom: 12),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: const Color(0xFFE2E8F0)),
            ),
            child: Column(
              children: [
                // Category header
                Container(
                  width: double.infinity,
                  padding: EdgeInsets.symmetric(
                    horizontal: ResponsiveHelper.horizontalPadding(context),
                    vertical: 12,
                  ),
                  decoration: const BoxDecoration(
                    color: AppTheme.primary,
                    borderRadius: BorderRadius.vertical(
                      top: Radius.circular(16),
                    ),
                  ),
                  child: Text(
                    cat['title'],
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 12,
                      fontWeight: FontWeight.bold,
                      letterSpacing: 0.5,
                    ),
                  ),
                ),
                // Table
                Table(
                  children: [
                    // Header row
                    TableRow(
                      decoration: const BoxDecoration(color: Color(0xFFF8FAFC)),
                      children: [
                        _buildSmallTableCell('Size', isHeader: true),
                        _buildSmallTableCell('Lebar', isHeader: true),
                        _buildSmallTableCell('Tinggi', isHeader: true),
                      ],
                    ),
                    // Data rows
                    ...(cat['sizes'] as List<String>).asMap().entries.map((
                      entry,
                    ) {
                      final i = entry.key;
                      final size = entry.value;
                      return TableRow(
                        decoration: BoxDecoration(
                          color: i % 2 == 1
                              ? const Color(0xFFF8FAFC)
                              : Colors.white,
                        ),
                        children: [
                          _buildSmallTableCell(size, isBold: true),
                          _buildSmallTableCell('${cat['lebar'][i]} cm'),
                          _buildSmallTableCell('${cat['tinggi'][i]} cm'),
                        ],
                      );
                    }),
                  ],
                ),
              ],
            ),
          );
        }),
        const SizedBox(height: 8),
        Text(
          '* Toleransi ukuran ±1-2 cm. Semua ukuran dalam centimeter (cm).',
          style: TextStyle(fontSize: 12, color: AppTheme.onSurfaceMuted),
        ),
      ],
    );
  }

  Widget _buildTableHeaderCell(String text) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 8),
      child: Text(
        text,
        textAlign: TextAlign.center,
        style: const TextStyle(
          color: Colors.white,
          fontSize: 12,
          fontWeight: FontWeight.bold,
        ),
      ),
    );
  }

  Widget _buildTableCell(String text, {bool isHeader = false, String? prefix}) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          if (prefix != null)
            Container(
              margin: const EdgeInsets.only(right: 8),
              padding: const EdgeInsets.all(6),
              decoration: BoxDecoration(
                color: AppTheme.primary.withValues(alpha: 0.1),
                shape: BoxShape.circle,
              ),
              child: Text(
                prefix,
                style: const TextStyle(
                  fontSize: 10,
                  fontWeight: FontWeight.bold,
                  color: AppTheme.primary,
                ),
              ),
            ),
          Text(
            text,
            style: TextStyle(
              fontSize: 12,
              fontWeight: isHeader ? FontWeight.bold : FontWeight.normal,
              color: isHeader ? AppTheme.primary : AppTheme.onSurfaceVariant,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSmallTableCell(
    String text, {
    bool isHeader = false,
    bool isBold = false,
  }) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 8),
      child: Text(
        text,
        textAlign: TextAlign.center,
        style: TextStyle(
          fontSize: 12,
          fontWeight: isHeader || isBold ? FontWeight.bold : FontWeight.normal,
          color: isHeader
              ? AppTheme.onSurfaceVariant
              : (isBold ? AppTheme.primary : AppTheme.onSurfaceVariant),
        ),
      ),
    );
  }

  Widget _buildMeasureGuideSection() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Cara Mengukur',
          style: TextStyle(
            fontSize: 20,
            fontWeight: FontWeight.bold,
            color: AppTheme.primary,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          'Ikuti panduan berikut agar ukuran yang dipilih sesuai.',
          style: TextStyle(fontSize: 14, color: AppTheme.onSurfaceVariant),
        ),
        const SizedBox(height: 16),
        GridView.builder(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
            crossAxisCount: 2,
            childAspectRatio: 0.8,
            crossAxisSpacing: 12,
            mainAxisSpacing: 12,
          ),
          itemCount: measureGuide.length,
          itemBuilder: (context, index) {
            final guide = measureGuide[index];
            return Container(
              padding: EdgeInsets.all(
                ResponsiveHelper.horizontalPadding(context),
              ),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  Container(
                    padding: const EdgeInsets.all(8),
                    decoration: const BoxDecoration(
                      color: AppTheme.sectionBackground,
                      shape: BoxShape.circle,
                    ),
                    child: Text(
                      '${index + 1}',
                      style: const TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        color: AppTheme.onSurfaceMuted,
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Text(guide['icon']!, style: const TextStyle(fontSize: 32)),
                  const SizedBox(height: 12),
                  Text(
                    guide['title']!,
                    textAlign: TextAlign.center,
                    style: const TextStyle(
                      fontSize: 14,
                      fontWeight: FontWeight.bold,
                      color: AppTheme.onSurface,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    guide['desc']!,
                    textAlign: TextAlign.center,
                    style: const TextStyle(
                      fontSize: 12,
                      color: AppTheme.onSurfaceVariant,
                      height: 1.5,
                    ),
                  ),
                ],
              ),
            );
          },
        ),
      ],
    );
  }

  Widget _buildTipsSection() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Tips Memilih Ukuran',
          style: TextStyle(
            fontSize: 20,
            fontWeight: FontWeight.bold,
            color: AppTheme.primary,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          'Beberapa hal penting yang perlu diperhatikan.',
          style: TextStyle(fontSize: 14, color: AppTheme.onSurfaceVariant),
        ),
        const SizedBox(height: 16),
        ...tips.asMap().entries.map((entry) {
          return Container(
            margin: const EdgeInsets.only(bottom: 12),
            padding: EdgeInsets.all(
              ResponsiveHelper.horizontalPadding(context),
            ),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: const Color(0xFFE2E8F0)),
            ),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  width: 40,
                  height: 40,
                  decoration: BoxDecoration(
                    color: AppTheme.sectionBackground,
                    shape: BoxShape.circle,
                    border: Border.all(color: const Color(0xFFE2E8F0)),
                  ),
                  child: Center(
                    child: Text(
                      '${entry.key + 1}',
                      style: const TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        color: AppTheme.primary,
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Padding(
                    padding: const EdgeInsets.only(top: 8),
                    child: Text(
                      entry.value,
                      style: const TextStyle(
                        fontSize: 14,
                        color: AppTheme.onSurfaceVariant,
                        height: 1.6,
                      ),
                    ),
                  ),
                ),
              ],
            ),
          );
        }),
      ],
    );
  }

  Widget _buildCTASection() {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [Color(0xFFF8FAFC), Colors.white],
        ),
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Column(
        children: [
          const Icon(Icons.help_outline, size: 40, color: AppTheme.primary),
          const SizedBox(height: 16),
          const Text(
            'Masih ragu dengan ukuran?',
            style: TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: AppTheme.primary,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Konsultasikan langsung dengan tim kami untuk rekomendasi ukuran yang tepat.',
            textAlign: TextAlign.center,
            style: TextStyle(fontSize: 14, color: AppTheme.onSurfaceVariant),
          ),
          const SizedBox(height: 20),
          ElevatedButton.icon(
            onPressed: _openWhatsApp,
            icon: const Icon(Icons.chat, size: 18),
            style: ElevatedButton.styleFrom(
              backgroundColor: AppTheme.primary,
              foregroundColor: Colors.white,
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
              ),
            ),
            label: const Text(
              'Konsultasi via WhatsApp',
              style: TextStyle(fontWeight: FontWeight.bold),
            ),
          ),
          const SizedBox(height: 12),
          TextButton(
            onPressed: () => context.go('/kontak'),
            child: const Text(
              'Hubungi Kami',
              style: TextStyle(
                color: AppTheme.primary,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Panduan Ukuran',
      subtitle: 'Temukan ukuran yang tepat untuk Anda',
      showLogo: false,
      leading: IconButton(
        icon: const Icon(Icons.arrow_back, color: AppTheme.primary),
        onPressed: () => context.popOrGoShop(),
      ),
      body: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        child: MitologiPageShell(
          title: 'Panduan Ukuran',
          eyebrow: 'Panduan',
          subtitle: 'Temukan ukuran yang paling pas untuk Anda.',
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Tab Bar
              Container(
                margin: const EdgeInsets.only(bottom: 24),
                decoration: BoxDecoration(
                  color: const Color(0xFFF1F5F9),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: TabBar(
                  controller: _tabController,
                  indicator: BoxDecoration(
                    color: AppTheme.primary,
                    borderRadius: BorderRadius.circular(10),
                    boxShadow: [
                      BoxShadow(
                        color: AppTheme.primary.withValues(alpha: 0.3),
                        blurRadius: 8,
                        offset: const Offset(0, 2),
                      ),
                    ],
                  ),
                  indicatorSize: TabBarIndicatorSize.tab,
                  labelColor: Colors.white,
                  unselectedLabelColor: AppTheme.onSurfaceVariant,
                  labelStyle: const TextStyle(
                    fontWeight: FontWeight.bold,
                    fontSize: 14,
                  ),
                  padding: const EdgeInsets.all(4),
                  tabs: const [
                    Tab(text: 'Kaos & Jersey'),
                    Tab(text: 'Kemeja'),
                  ],
                ),
              ),

              // Tab Content
              AnimatedBuilder(
                animation: _tabController,
                builder: (context, child) {
                  return IndexedStack(
                    index: _tabController.index,
                    children: [
                      Visibility(
                        visible: _tabController.index == 0,
                        child: _buildKaosSection(),
                      ),
                      Visibility(
                        visible: _tabController.index == 1,
                        child: _buildKemejaSection(),
                      ),
                    ],
                  );
                },
              ),

              const SizedBox(height: 32),

              // Divider
              Container(
                height: 1,
                color: const Color(0xFFE2E8F0),
                margin: const EdgeInsets.symmetric(vertical: 16),
              ),

              // Cara Mengukur
              _buildMeasureGuideSection(),

              const SizedBox(height: 32),

              // Divider
              Container(
                height: 1,
                color: const Color(0xFFE2E8F0),
                margin: const EdgeInsets.symmetric(vertical: 16),
              ),

              // Tips
              _buildTipsSection(),

              const SizedBox(height: 32),

              // CTA
              _buildCTASection(),

              const SizedBox(height: 32),
            ],
          ),
        ),
      ),
    );
  }
}

// Custom painter for T-shirt illustration
class TShirtPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = const Color(0xFFCBD5E1)
      ..style = PaintingStyle.stroke
      ..strokeWidth = 2;

    final path = Path()
      ..moveTo(size.width * 0.3, size.height * 0.136)
      ..lineTo(size.width * 0.15, size.height * 0.273)
      ..lineTo(size.width * 0.25, size.height * 0.318)
      ..lineTo(size.width * 0.25, size.height * 0.909)
      ..lineTo(size.width * 0.75, size.height * 0.909)
      ..lineTo(size.width * 0.75, size.height * 0.318)
      ..lineTo(size.width * 0.85, size.height * 0.273)
      ..lineTo(size.width * 0.7, size.height * 0.136)
      ..lineTo(size.width * 0.6, size.height * 0.227)
      ..cubicTo(
        size.width * 0.55,
        size.height * 0.273,
        size.width * 0.45,
        size.height * 0.273,
        size.width * 0.4,
        size.height * 0.227,
      )
      ..lineTo(size.width * 0.3, size.height * 0.136);

    canvas.drawPath(path, paint);

    // Fill
    final fillPaint = Paint()
      ..color = Colors.white
      ..style = PaintingStyle.fill;
    canvas.drawPath(path, fillPaint);
    canvas.drawPath(path, paint);

    // Measurement lines
    final measurePaint = Paint()
      ..color = AppTheme.primary
      ..style = PaintingStyle.stroke
      ..strokeWidth = 1.5;

    // Width line
    canvas.drawLine(
      Offset(size.width * 0.275, size.height * 0.432),
      Offset(size.width * 0.725, size.height * 0.432),
      measurePaint
        ..strokeWidth = 1.5
        ..color = AppTheme.primary,
    );

    // Length line
    canvas.drawLine(
      Offset(size.width * 0.5, size.height * 0.182),
      Offset(size.width * 0.5, size.height * 0.886),
      measurePaint,
    );
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}

// Custom painter for Shirt illustration
class ShirtPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = const Color(0xFFCBD5E1)
      ..style = PaintingStyle.stroke
      ..strokeWidth = 2;

    final path = Path()
      ..moveTo(size.width * 0.35, size.height * 0.104)
      ..lineTo(size.width * 0.2, size.height * 0.188)
      ..lineTo(size.width * 0.125, size.height * 0.458)
      ..lineTo(size.width * 0.275, size.height * 0.417)
      ..lineTo(size.width * 0.275, size.height * 0.917)
      ..lineTo(size.width * 0.725, size.height * 0.917)
      ..lineTo(size.width * 0.725, size.height * 0.417)
      ..lineTo(size.width * 0.875, size.height * 0.458)
      ..lineTo(size.width * 0.8, size.height * 0.188)
      ..lineTo(size.width * 0.65, size.height * 0.104)
      ..lineTo(size.width * 0.575, size.height * 0.188)
      ..cubicTo(
        size.width * 0.54,
        size.height * 0.229,
        size.width * 0.46,
        size.height * 0.229,
        size.width * 0.425,
        size.height * 0.188,
      )
      ..lineTo(size.width * 0.35, size.height * 0.104);

    // Fill
    final fillPaint = Paint()
      ..color = Colors.white
      ..style = PaintingStyle.fill;
    canvas.drawPath(path, fillPaint);
    canvas.drawPath(path, paint);

    // Placket line
    final placketPaint = Paint()
      ..color = const Color(0xFFCBD5E1)
      ..style = PaintingStyle.stroke
      ..strokeWidth = 1;
    canvas.drawLine(
      Offset(size.width * 0.5, size.height * 0.188),
      Offset(size.width * 0.5, size.height * 0.917),
      placketPaint,
    );

    // Buttons
    final buttonPaint = Paint()
      ..color = const Color(0xFFCBD5E1)
      ..style = PaintingStyle.fill;

    for (var i = 0; i < 4; i++) {
      canvas.drawCircle(
        Offset(size.width * 0.5, size.height * (0.292 + i * 0.125)),
        3,
        buttonPaint,
      );
    }

    // Measurement lines with gold color
    final measurePaint = Paint()
      ..color = AppTheme.accent
      ..style = PaintingStyle.stroke
      ..strokeWidth = 1.5;

    // Width line
    canvas.drawLine(
      Offset(size.width * 0.29, size.height * 0.458),
      Offset(size.width * 0.71, size.height * 0.458),
      measurePaint,
    );

    // Height line
    canvas.drawLine(
      Offset(size.width * 0.775, size.height * 0.125),
      Offset(size.width * 0.775, size.height * 0.9),
      measurePaint,
    );
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
