import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../../config/theme.dart';
import '../../../utils/storage_url.dart';

class AboutHistorySection extends StatelessWidget {
  final String? siteName;
  final String? foundedYear;
  final List<String> descriptions;
  final List<String> historyParagraphs;
  final List<String> logoMeanings;
  final String? aboutImage;

  const AboutHistorySection({
    super.key,
    this.siteName,
    this.foundedYear,
    required this.descriptions,
    required this.historyParagraphs,
    required this.logoMeanings,
    this.aboutImage,
  });

  String get _imageUrl {
    if (aboutImage == null || aboutImage!.isEmpty) {
      return '';
    }
    return StorageUrl.format(aboutImage);
  }

  @override
  Widget build(BuildContext context) {
    // Don't show if no data from API
    final hasContent =
        siteName != null && siteName!.isNotEmpty ||
        descriptions.isNotEmpty ||
        historyParagraphs.isNotEmpty ||
        logoMeanings.isNotEmpty ||
        aboutImage != null;

    if (!hasContent) {
      return const SizedBox.shrink();
    }

    return Container(
      color: const Color(0xFFF8FAFC), // slate-50
      child: Column(
        children: [
          // Main Content Section
          Padding(
            padding: const EdgeInsets.all(24),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Badge
                Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 16,
                    vertical: 8,
                  ),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(20),
                    border: Border.all(color: AppTheme.slate200),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withValues(alpha: 0.03),
                        blurRadius: 4,
                        offset: const Offset(0, 2),
                      ),
                    ],
                  ),
                  child: const Text(
                    'SEJARAH & IDENTITAS',
                    style: TextStyle(
                      fontSize: 11,
                      fontWeight: FontWeight.w800,
                      color: AppTheme.primary,
                      letterSpacing: 0.5,
                    ),
                  ),
                ),
                const SizedBox(height: 20),

                // Title
                if (siteName != null && siteName!.isNotEmpty)
                  RichText(
                    text: TextSpan(
                      style: const TextStyle(
                        fontSize: 28,
                        fontWeight: FontWeight.w900,
                        color: AppTheme.primary,
                        height: 1.2,
                      ),
                      children: [
                        const TextSpan(text: 'Perjalanan '),
                        TextSpan(
                          text: siteName,
                          style: const TextStyle(color: AppTheme.accent),
                        ),
                      ],
                    ),
                  ),
                if (siteName != null && siteName!.isNotEmpty)
                  const SizedBox(height: 24),

                // Image with floating badge
                Container(
                  margin: const EdgeInsets.only(bottom: 24),
                  child: Stack(
                    clipBehavior: Clip.none,
                    children: [
                      Container(
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(24),
                          boxShadow: [
                            BoxShadow(
                              color: AppTheme.accent.withValues(alpha: 0.2),
                              blurRadius: 0,
                              offset: const Offset(8, 8),
                            ),
                            BoxShadow(
                              color: AppTheme.slate200.withValues(alpha: 0.5),
                              blurRadius: 0,
                              offset: const Offset(-8, -8),
                            ),
                          ],
                        ),
                        child: ClipRRect(
                          borderRadius: BorderRadius.circular(24),
                          child: Container(
                            color: Colors.white,
                            padding: const EdgeInsets.all(24),
                            child: _imageUrl.isEmpty
                                ? Container(
                                    height: 200,
                                    color: AppTheme.muted,
                                    child: Image.asset(
                                      'assets/images/logo.png',
                                      fit: BoxFit.contain,
                                    ),
                                  )
                                : CachedNetworkImage(
                                    imageUrl: _imageUrl,
                                    height: 200,
                                    width: double.infinity,
                                    fit: BoxFit.contain,
                                    placeholder: (context, url) => Container(
                                      height: 200,
                                      color: AppTheme.muted,
                                      child: const Center(
                                        child: CircularProgressIndicator(
                                          strokeWidth: 2,
                                          color: AppTheme.primary,
                                        ),
                                      ),
                                    ),
                                    errorWidget: (context, url, error) =>
                                        Container(
                                          height: 200,
                                          color: AppTheme.muted,
                                          child: Image.asset(
                                            'assets/images/logo.png',
                                            fit: BoxFit.contain,
                                          ),
                                        ),
                                  ),
                          ),
                        ),
                      ),
                      if (foundedYear != null && foundedYear!.isNotEmpty)
                        Positioned(
                          bottom: -16,
                          left: 16,
                          child: Container(
                            padding: const EdgeInsets.symmetric(
                              horizontal: 16,
                              vertical: 12,
                            ),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(16),
                              border: Border.all(color: AppTheme.muted),
                              boxShadow: [
                                BoxShadow(
                                  color: Colors.black.withValues(alpha: 0.08),
                                  blurRadius: 12,
                                  offset: const Offset(0, 4),
                                ),
                              ],
                            ),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                const Text(
                                  'BERDIRI SEJAK',
                                  style: TextStyle(
                                    fontSize: 9,
                                    fontWeight: FontWeight.w800,
                                    color: AppTheme.slate400,
                                    letterSpacing: 0.5,
                                  ),
                                ),
                                Text(
                                  foundedYear!,
                                  style: const TextStyle(
                                    fontSize: 18,
                                    fontWeight: FontWeight.w900,
                                    color: AppTheme.primary,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ),
                    ],
                  ),
                ),
                const SizedBox(height: 24),

                // Content Timeline
                if (descriptions.isNotEmpty || historyParagraphs.isNotEmpty)
                  Container(
                    padding: const EdgeInsets.only(left: 16),
                    decoration: BoxDecoration(
                      border: Border(
                        left: BorderSide(
                          color: AppTheme.accent.withValues(alpha: 0.3),
                          width: 2,
                        ),
                      ),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Descriptions
                        if (descriptions.isNotEmpty)
                          ...descriptions.map(
                            (desc) => Padding(
                              padding: const EdgeInsets.only(bottom: 16),
                              child: Text(
                                desc,
                                style: const TextStyle(
                                  fontSize: 14,
                                  height: 1.7,
                                  color: AppTheme.slate600,
                                ),
                                textAlign: TextAlign.justify,
                              ),
                            ),
                          ),

                        // History Paragraphs
                        if (historyParagraphs.isNotEmpty)
                          ...historyParagraphs.map(
                            (paragraph) => Padding(
                              padding: const EdgeInsets.only(bottom: 12),
                              child: Text(
                                paragraph,
                                style: TextStyle(
                                  fontSize: 14,
                                  height: 1.7,
                                  color: AppTheme.onSurfaceVariant,
                                ),
                                textAlign: TextAlign.justify,
                              ),
                            ),
                          ),
                      ],
                    ),
                  ),
              ],
            ),
          ),

          // Logo Meanings Section
          if (logoMeanings.isNotEmpty)
            Container(
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                color: Colors.white,
                border: Border(
                  top: BorderSide(
                    color: AppTheme.slate200.withValues(alpha: 0.5),
                  ),
                ),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Section Header
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'FILOSOFI ESTETIKA',
                        style: TextStyle(
                          fontSize: 11,
                          fontWeight: FontWeight.w800,
                          color: AppTheme.accent,
                          letterSpacing: 0.5,
                        ),
                      ),
                      const SizedBox(height: 8),
                      const Text(
                        'Makna Logo Kami',
                        style: TextStyle(
                          fontSize: 24,
                          fontWeight: FontWeight.w900,
                          color: AppTheme.primary,
                        ),
                      ),
                      const SizedBox(height: 12),
                      Text(
                        siteName != null && siteName!.isNotEmpty
                            ? 'Setiap elemen visual dirancang dengan kebanggaan untuk membentuk cerita "$siteName" sebagai identitas seragam yang konsisten dan profesional.'
                            : 'Setiap elemen visual dirancang dengan kebanggaan untuk membentuk identitas seragam yang konsisten dan profesional.',
                        style: TextStyle(
                          fontSize: 14,
                          height: 1.6,
                          color: AppTheme.onSurfaceVariant,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 24),

                  // Logo Meanings Grid
                  GridView.builder(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    gridDelegate:
                        const SliverGridDelegateWithFixedCrossAxisCount(
                          crossAxisCount: 2,
                          crossAxisSpacing: 12,
                          mainAxisSpacing: 12,
                          childAspectRatio: 0.85,
                        ),
                    itemCount: logoMeanings.length,
                    itemBuilder: (context, index) {
                      final meaning = logoMeanings[index].trim().replaceAll(
                        RegExp(r'^-\s*'),
                        '',
                      );
                      final match = RegExp(
                        r'huruf\s+([A-Za-z])',
                        caseSensitive: false,
                      ).firstMatch(meaning);
                      final letter =
                          (match?.group(1) ??
                                  meaning.characters.firstOrNull ??
                                  '${index + 1}')
                              .toUpperCase();
                      final order = (index + 1).toString().padLeft(2, '0');

                      return Container(
                        padding: const EdgeInsets.all(16),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(color: AppTheme.slate200),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withValues(alpha: 0.03),
                              blurRadius: 8,
                              offset: const Offset(0, 2),
                            ),
                          ],
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Container(
                                  width: 40,
                                  height: 40,
                                  decoration: BoxDecoration(
                                    color: AppTheme.primary,
                                    borderRadius: BorderRadius.circular(12),
                                  ),
                                  child: Center(
                                    child: Text(
                                      letter,
                                      style: const TextStyle(
                                        fontSize: 18,
                                        fontWeight: FontWeight.w900,
                                        color: Colors.white,
                                      ),
                                    ),
                                  ),
                                ),
                                Text(
                                  order,
                                  style: TextStyle(
                                    fontSize: 20,
                                    fontWeight: FontWeight.w900,
                                    color: AppTheme.slate200,
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 12),
                            Expanded(
                              child: Text(
                                meaning,
                                style: const TextStyle(
                                  fontSize: 13,
                                  height: 1.5,
                                  color: AppTheme.slate600,
                                  fontWeight: FontWeight.w500,
                                ),
                                maxLines: 4,
                                overflow: TextOverflow.ellipsis,
                              ),
                            ),
                          ],
                        ),
                      );
                    },
                  ),
                ],
              ),
            ),
        ],
      ),
    );
  }
}
