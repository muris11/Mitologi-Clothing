import 'package:flutter/material.dart';
import '../../../config/theme.dart';
import '../../../config/size_guides.dart';
import '../../../utils/responsive_helper.dart';
import '../../../widgets/painter/clothing_painters.dart';

class KaosSizeGuide extends StatelessWidget {
  const KaosSizeGuide({super.key});

  @override
  Widget build(BuildContext context) {
    final data = SizeGuides.kaosJerseyData;
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);

  @override
  Widget build(BuildContext context) {
    final data = SizeGuides.kaosJerseyData;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Illustration
        Container(
          height: 220,
          decoration: BoxDecoration(
            gradient: LinearGradient(
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
              colors: [
                AppTheme.primary.withValues(alpha: 0.02),
                AppTheme.primary.withValues(alpha: 0.08),
              ],
            ),
            borderRadius: BorderRadius.circular(24),
            border: Border.all(color: AppTheme.primary.withValues(alpha: 0.05)),
          ),
          child: Center(
            child: Stack(
              alignment: Alignment.center,
              children: [
                CustomPaint(
                  size: const Size(160, 176),
                  painter: TShirtPainter(),
                ),
                Positioned(
                  top: 60,
                  child: _buildLabel('Lebar Dada'),
                ),
              ],
            ),
          ),
        ),
        const SizedBox(height: 32),
        
        Text(
          'Size Chart — ${data['title']}',
          style: Theme.of(context).textTheme.titleLarge?.copyWith(
                fontWeight: FontWeight.w900,
                color: AppTheme.primary,
                letterSpacing: -0.5,
              ),
        ),
        const SizedBox(height: 8),
        Text(
          data['description'] as String,
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                color: AppTheme.onSurfaceVariant,
                height: 1.6,
              ),
        ),
        const SizedBox(height: 24),

        // Table
        _buildDataTable(context, data),
        
        const SizedBox(height: 16),
        Row(
          children: [
            Icon(Icons.info_outline, size: 14, color: AppTheme.onSurfaceMuted),
            const SizedBox(width: 8),
            const Expanded(
              child: Text(
                'Toleransi ukuran ±1-2 cm. Semua ukuran dalam centimeter (cm).',
                style: TextStyle(
                  fontSize: 12, 
                  color: AppTheme.onSurfaceMuted, 
                  fontStyle: FontStyle.italic,
                ),
              ),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildLabel(String text) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: AppTheme.primary.withValues(alpha: 0.1)),
        boxShadow: AppTheme.shadowSoft,
      ),
      child: Text(
        text.toUpperCase(),
        style: const TextStyle(
          fontSize: 9,
          fontWeight: FontWeight.w900,
          color: AppTheme.primary,
          letterSpacing: 1.2,
        ),
      ),
    );
  }

  Widget _buildDataTable(BuildContext context, Map<String, dynamic> data) {
    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      physics: const BouncingScrollPhysics(),
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: AppTheme.outlineVariant),
          boxShadow: AppTheme.shadowSoft,
        ),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(20),
          child: Table(
            defaultColumnWidth: const FixedColumnWidth(100),
            children: [
              // Header
              TableRow(
                decoration: const BoxDecoration(
                  color: AppTheme.primary,
                ),
                children: [
                  _buildHeaderCell('CATEGORY'),
                  ...(data['sizes'] as List<String>).map((s) => _buildHeaderCell(s)),
                ],
              ),
              // Data rows
              ...(data['measurements'] as List<String>).asMap().entries.map((entry) {
                final measurement = entry.value;
                final values = (data['values'] as Map<String, List<String>>)[measurement]!;
                final isEven = entry.key % 2 == 0;
                
                return TableRow(
                  decoration: BoxDecoration(
                    color: isEven ? Colors.white : AppTheme.primary.withValues(alpha: 0.02),
                  ),
                  children: [
                    _buildDataCell(
                      measurement,
                      isHeader: true,
                      prefix: measurement.contains('Panjang') ? 'P' : 'LD',
                    ),
                    ...values.map((v) => _buildDataCell('$v cm')),
                  ],
                );
              }),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildHeaderCell(String text) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 20, horizontal: 8),
      child: Text(
        text,
        textAlign: TextAlign.center,
        style: const TextStyle(
          color: Colors.white,
          fontSize: 11,
          fontWeight: FontWeight.w900,
          letterSpacing: 1.5,
        ),
      ),
    );
  }

  Widget _buildDataCell(String text, {bool isHeader = false, String? prefix}) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 18, horizontal: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          if (prefix != null)
            Container(
              margin: const EdgeInsets.only(right: 8),
              padding: const EdgeInsets.all(6),
              decoration: BoxDecoration(
                color: AppTheme.primary.withValues(alpha: 0.1),
                borderRadius: BorderRadius.circular(6),
              ),
              child: Text(
                prefix,
                style: const TextStyle(
                  fontSize: 8,
                  fontWeight: FontWeight.w900,
                  color: AppTheme.primary,
                ),
              ),
            ),
          Text(
            text,
            style: TextStyle(
              fontSize: 13,
              fontWeight: isHeader ? FontWeight.w800 : FontWeight.w600,
              color: isHeader ? AppTheme.primary : AppTheme.onSurface,
            ),
          ),
        ],
      ),
    );
  }
}

class KemejaSizeGuide extends StatelessWidget {
  const KemejaSizeGuide({super.key});

  @override
  Widget build(BuildContext context) {
    final categories = SizeGuides.kemejaCategories;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Illustration
        Container(
          height: 220,
          decoration: BoxDecoration(
            gradient: LinearGradient(
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
              colors: [
                AppTheme.primary.withValues(alpha: 0.02),
                AppTheme.primary.withValues(alpha: 0.08),
              ],
            ),
            borderRadius: BorderRadius.circular(24),
            border: Border.all(color: AppTheme.primary.withValues(alpha: 0.05)),
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
                  child: _buildLabel('LEBAR'),
                ),
              ],
            ),
          ),
        ),
        const SizedBox(height: 32),
        
        Text(
          'Size Chart — Kemeja',
          style: Theme.of(context).textTheme.titleLarge?.copyWith(
                fontWeight: FontWeight.w900,
                color: AppTheme.primary,
                letterSpacing: -0.5,
              ),
        ),
        const SizedBox(height: 8),
        Text(
          'Standar ukuran presisi untuk kemeja seragam koleksi Mitologi, mulai dari ukuran TK hingga Dewasa.',
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                color: AppTheme.onSurfaceVariant,
                height: 1.6,
              ),
        ),
        const SizedBox(height: 24),

        // Grid of category tables
        ListView.separated(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          itemCount: categories.length,
          separatorBuilder: (context, index) => const SizedBox(height: 24),
          itemBuilder: (context, index) {
            final cat = categories[index];
            return _buildCategoryTable(context, cat);
          },
        ),
        
        const SizedBox(height: 16),
        Row(
          children: [
            Icon(Icons.info_outline, size: 14, color: AppTheme.onSurfaceMuted),
            const SizedBox(width: 8),
            const Expanded(
              child: Text(
                'Toleransi ukuran ±1-2 cm. Semua ukuran dalam centimeter (cm).',
                style: TextStyle(
                  fontSize: 12, 
                  color: AppTheme.onSurfaceMuted, 
                  fontStyle: FontStyle.italic,
                ),
              ),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildLabel(String text) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: AppTheme.primary.withValues(alpha: 0.1)),
        boxShadow: AppTheme.shadowSoft,
      ),
      child: Text(
        text.toUpperCase(),
        style: const TextStyle(
          fontSize: 9,
          fontWeight: FontWeight.w900,
          color: AppTheme.primary,
          letterSpacing: 1.2,
        ),
      ),
    );
  }

  Widget _buildCategoryTable(BuildContext context, Map<String, dynamic> cat) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: AppTheme.outlineVariant),
        boxShadow: AppTheme.shadowSoft,
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(20),
        child: Column(
          children: [
            // Header
            Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
              decoration: const BoxDecoration(
                color: AppTheme.primary,
              ),
              child: Text(
                (cat['title'] as String).toUpperCase(),
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 11,
                  fontWeight: FontWeight.w900,
                  letterSpacing: 1.5,
                ),
              ),
            ),
            // Content
            Table(
              children: [
                TableRow(
                  decoration: BoxDecoration(color: AppTheme.primary.withValues(alpha: 0.05)),
                  children: [
                    _buildTableCell('SIZE', isHeader: true),
                    _buildTableCell('LEBAR', isHeader: true),
                    _buildTableCell('TINGGI', isHeader: true),
                  ],
                ),
                ...(cat['sizes'] as List<String>).asMap().entries.map((entry) {
                  final i = entry.key;
                  final size = entry.value;
                  final isEven = i % 2 == 1;
                  return TableRow(
                    decoration: BoxDecoration(
                      color: isEven ? AppTheme.primary.withValues(alpha: 0.02) : Colors.white,
                    ),
                    children: [
                      _buildTableCell(size, isBold: true),
                      _buildTableCell('${cat['lebar'][i]} cm'),
                      _buildTableCell('${cat['tinggi'][i]} cm'),
                    ],
                  );
                }),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildTableCell(String text, {bool isHeader = false, bool isBold = false}) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 8),
      child: Text(
        text,
        textAlign: TextAlign.center,
        style: TextStyle(
          fontSize: 13,
          fontWeight: isHeader || isBold ? FontWeight.w800 : FontWeight.w500,
          color: isHeader 
              ? AppTheme.primary 
              : (isBold ? AppTheme.primary : AppTheme.onSurface),
        ),
      ),
    );
  }
}

class MeasurementGuideGrid extends StatelessWidget {
  const MeasurementGuideGrid({super.key});

  @override
  Widget build(BuildContext context) {
    final guides = SizeGuides.measureGuide;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Cara Mengukur',
          style: Theme.of(context).textTheme.titleLarge?.copyWith(
                fontWeight: FontWeight.w800,
                color: AppTheme.primary,
              ),
        ),
        const SizedBox(height: 8),
        Text(
          'Ikuti panduan berikut agar ukuran yang dipilih sesuai.',
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                color: AppTheme.onSurfaceVariant,
              ),
        ),
        const SizedBox(height: 24),

        GridView.builder(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
            crossAxisCount: 2,
            childAspectRatio: 0.75,
            crossAxisSpacing: 16,
            mainAxisSpacing: 16,
          ),
          itemCount: guides.length,
          itemBuilder: (context, index) {
            final guide = guides[index];
            return Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: const Color(0xFFE2E8F0)),
                boxShadow: AppTheme.shadowSoft,
              ),
              child: Column(
                children: [
                  Container(
                    width: 32,
                    height: 32,
                    alignment: Alignment.center,
                    decoration: BoxDecoration(
                      color: AppTheme.primary.withValues(alpha: 0.1),
                      shape: BoxShape.circle,
                    ),
                    child: Text(
                      '${index + 1}',
                      style: const TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.w900,
                        color: AppTheme.primary,
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),
                  Text(guide['icon']!, style: const TextStyle(fontSize: 36)),
                  const SizedBox(height: 16),
                  Text(
                    guide['title']!,
                    textAlign: TextAlign.center,
                    style: const TextStyle(
                      fontSize: 14,
                      fontWeight: FontWeight.w800,
                      color: AppTheme.onSurface,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Expanded(
                    child: Text(
                      guide['desc']!,
                      textAlign: TextAlign.center,
                      style: const TextStyle(
                        fontSize: 11,
                        color: AppTheme.onSurfaceVariant,
                        height: 1.5,
                      ),
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
}

class SizeTipsSection extends StatelessWidget {
  const SizeTipsSection({super.key});

  @override
  Widget build(BuildContext context) {
    final tips = SizeGuides.tips;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Tips Memilih Ukuran',
          style: Theme.of(context).textTheme.titleLarge?.copyWith(
                fontWeight: FontWeight.w800,
                color: AppTheme.primary,
              ),
        ),
        const SizedBox(height: 8),
        Text(
          'Beberapa hal penting yang perlu diperhatikan.',
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                color: AppTheme.onSurfaceVariant,
              ),
        ),
        const SizedBox(height: 20),

        ...tips.asMap().entries.map((entry) {
          return Container(
            margin: const EdgeInsets.only(bottom: 12),
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: AppTheme.surface,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: const Color(0xFFE2E8F0)),
            ),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  margin: const EdgeInsets.only(top: 2),
                  child: const Icon(
                    Icons.info_outline,
                    color: AppTheme.primary,
                    size: 18,
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Text(
                    entry.value,
                    style: const TextStyle(
                      fontSize: 13,
                      height: 1.5,
                      color: AppTheme.onSurface,
                      fontWeight: FontWeight.w500,
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
}
