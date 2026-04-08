import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../../config/theme.dart';
import '../../../utils/storage_url.dart';

class FounderStorySection extends StatelessWidget {
  final String founderName;
  final String? founderRole;
  final String? founderImageUrl;
  final String? story;

  const FounderStorySection({
    super.key,
    required this.founderName,
    this.founderRole,
    this.founderImageUrl,
    this.story,
  });

  @override
  Widget build(BuildContext context) {
    if (founderName.isEmpty || (story == null || story!.isEmpty)) {
      return const SizedBox.shrink();
    }

    return Container(
      color: AppTheme.cream,
      padding: const EdgeInsets.symmetric(vertical: 48),
      child: Stack(
        children: [
          Positioned(
            right: -100,
            top: 0,
            child: Container(
              width: 300,
              height: 300,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: Colors.white.withValues(alpha: 0.5),
              ),
            ),
          ),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 24),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.center,
                        children: [
                          Stack(
                            clipBehavior: Clip.none,
                            children: [
                              Positioned(
                                right: 0,
                                bottom: 0,
                                child: Container(
                                  width: 160,
                                  height: 200,
                                  decoration: BoxDecoration(
                                    color: AppTheme.primary,
                                    borderRadius: BorderRadius.circular(24),
                                  ),
                                ),
                              ),
                              Container(
                                width: 160,
                                height: 200,
                                margin: const EdgeInsets.only(
                                  bottom: 16,
                                  right: 16,
                                ),
                                decoration: BoxDecoration(
                                  color: Colors.white,
                                  borderRadius: BorderRadius.circular(24),
                                  border: Border.all(
                                    color: Colors.white,
                                    width: 4,
                                  ),
                                  boxShadow: [
                                    BoxShadow(
                                      color: Colors.black.withValues(
                                        alpha: 0.15,
                                      ),
                                      blurRadius: 20,
                                      offset: const Offset(0, 10),
                                    ),
                                  ],
                                ),
                                clipBehavior: Clip.antiAlias,
                                child:
                                    founderImageUrl != null &&
                                        founderImageUrl!.isNotEmpty
                                    ? CachedNetworkImage(
                                        imageUrl: StorageUrl.format(
                                          founderImageUrl,
                                        ),
                                        fit: BoxFit.cover,
                                        placeholder: (context, url) =>
                                            Container(
                                              color: AppTheme.muted,
                                              child: const Center(
                                                child:
                                                    CircularProgressIndicator(
                                                      strokeWidth: 2,
                                                      color: AppTheme.primary,
                                                    ),
                                              ),
                                            ),
                                        errorWidget: (context, url, error) =>
                                            Container(
                                              color: AppTheme.muted,
                                              child: Center(
                                                child: Text(
                                                  founderName
                                                      .split(' ')
                                                      .map((w) => w[0])
                                                      .take(2)
                                                      .join(),
                                                  style: const TextStyle(
                                                    fontSize: 32,
                                                    fontWeight: FontWeight.bold,
                                                    color: AppTheme.slate400,
                                                  ),
                                                ),
                                              ),
                                            ),
                                      )
                                    : Container(
                                        color: AppTheme.muted,
                                        child: Center(
                                          child: Text(
                                            founderName
                                                .split(' ')
                                                .map((w) => w[0])
                                                .take(2)
                                                .join(),
                                            style: const TextStyle(
                                              fontSize: 32,
                                              fontWeight: FontWeight.bold,
                                              color: AppTheme.slate400,
                                            ),
                                          ),
                                        ),
                                      ),
                              ),
                              Positioned(
                                bottom: -8,
                                left: -16,
                                child: Container(
                                  padding: const EdgeInsets.symmetric(
                                    horizontal: 16,
                                    vertical: 12,
                                  ),
                                  decoration: BoxDecoration(
                                    color: Colors.white,
                                    borderRadius: BorderRadius.circular(16),
                                    boxShadow: [
                                      BoxShadow(
                                        color: Colors.black.withValues(
                                          alpha: 0.1,
                                        ),
                                        blurRadius: 12,
                                        offset: const Offset(0, 4),
                                      ),
                                    ],
                                  ),
                                  child: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      const Text(
                                        '10+',
                                        style: TextStyle(
                                          fontSize: 24,
                                          fontWeight: FontWeight.w900,
                                          color: AppTheme.primary,
                                          height: 1,
                                        ),
                                      ),
                                      const SizedBox(height: 4),
                                      Text(
                                        'Tahun\nPengalaman',
                                        style: TextStyle(
                                          fontSize: 10,
                                          fontWeight: FontWeight.w700,
                                          color: AppTheme.onSurfaceVariant,
                                          letterSpacing: 1.5,
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(width: 24),
                    Expanded(
                      flex: 2,
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              Container(
                                width: 32,
                                height: 3,
                                color: AppTheme.accent,
                              ),
                              const SizedBox(width: 12),
                              const Text(
                                'KISAH PENDIRI',
                                style: TextStyle(
                                  fontSize: 10,
                                  fontWeight: FontWeight.w800,
                                  color: AppTheme.primary,
                                  letterSpacing: 2,
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 20),
                          const Text(
                            'Dari Sebuah Ide Kecil Menjadi',
                            style: TextStyle(
                              fontSize: 22,
                              fontWeight: FontWeight.w900,
                              color: AppTheme.primary,
                              height: 1.2,
                            ),
                          ),
                          RichText(
                            text: TextSpan(
                              style: const TextStyle(
                                fontSize: 22,
                                fontWeight: FontWeight.w900,
                                color: AppTheme.primary,
                                height: 1.2,
                              ),
                              children: [
                                const TextSpan(text: 'Standar '),
                                TextSpan(
                                  text: 'Kualitas',
                                  style: TextStyle(color: AppTheme.accent),
                                ),
                                const TextSpan(text: '.'),
                              ],
                            ),
                          ),
                          const SizedBox(height: 20),
                          Stack(
                            children: [
                              Positioned(
                                top: -10,
                                left: -8,
                                child: Text(
                                  '"',
                                  style: TextStyle(
                                    fontSize: 60,
                                    fontWeight: FontWeight.w300,
                                    color: AppTheme.muted,
                                    height: 0.8,
                                  ),
                                ),
                              ),
                              Padding(
                                padding: const EdgeInsets.only(
                                  top: 8,
                                  left: 16,
                                ),
                                child: Column(
                                  children: story!
                                      .split('\n')
                                      .where((p) => p.trim().isNotEmpty)
                                      .map((paragraph) {
                                        return Padding(
                                          padding: const EdgeInsets.only(
                                            bottom: 12,
                                          ),
                                          child: Text(
                                            paragraph.trim(),
                                            style: const TextStyle(
                                              fontSize: 14,
                                              height: 1.7,
                                              color: AppTheme.slate700,
                                            ),
                                          ),
                                        );
                                      })
                                      .toList(),
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 24),
                          Container(
                            padding: const EdgeInsets.only(top: 16),
                            decoration: BoxDecoration(
                              border: Border(
                                top: BorderSide(
                                  color: AppTheme.muted.withValues(
                                    alpha: 0.5,
                                  ),
                                ),
                              ),
                            ),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  founderName,
                                  style: const TextStyle(
                                    fontSize: 18,
                                    fontWeight: FontWeight.w900,
                                    color: AppTheme.primary,
                                  ),
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  founderRole ?? 'Founder & CEO',
                                  style: TextStyle(
                                    fontSize: 11,
                                    fontWeight: FontWeight.w800,
                                    color: AppTheme.accent,
                                    letterSpacing: 2,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
