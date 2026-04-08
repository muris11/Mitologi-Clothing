import 'package:flutter/material.dart';
import '../../../config/theme.dart';
import '../../../models/tentang_kami/site_settings_model.dart';

class VisionMissionSection extends StatelessWidget {
  final String vision;
  final String? missionText;
  final List<CompanyValue> companyValues;

  const VisionMissionSection({
    super.key,
    required this.vision,
    this.missionText,
    required this.companyValues,
  });

  List<String> get _missions {
    if (missionText == null || missionText!.isEmpty) return [];
    return missionText!
        .split('\n')
        .map((m) => m.trim())
        .where((m) => m.isNotEmpty)
        .toList();
  }

  @override
  Widget build(BuildContext context) {
    final missions = _missions;
    final hasVision = vision.isNotEmpty;
    final hasMission =
        missions.isNotEmpty || (missionText?.isNotEmpty ?? false);
    final hasValues = companyValues.isNotEmpty;

    // Don't show anything if no content
    if (!hasVision && !hasMission && !hasValues) {
      return const SizedBox.shrink();
    }

    return Container(
      color: Colors.white,
      padding: const EdgeInsets.symmetric(vertical: 48, horizontal: 24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Vision Section - only if has vision
          if (hasVision)
            Container(
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                color: const Color(0xFFF8FAFC), // slate-50
                borderRadius: BorderRadius.circular(24),
              ),
              child: Column(
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
                      border: Border.all(color: AppTheme.muted),
                    ),
                    child: const Text(
                      'VISI KAMI',
                      style: TextStyle(
                        fontSize: 11,
                        fontWeight: FontWeight.w800,
                        color: AppTheme.primary,
                        letterSpacing: 0.5,
                      ),
                    ),
                  ),
                  const SizedBox(height: 24),

                  // Vision Quote
                  Stack(
                    children: [
                      // Opening quote
                      const Positioned(
                        top: 0,
                        left: 0,
                        child: Text(
                          '"',
                          style: TextStyle(
                            fontSize: 60,
                            fontWeight: FontWeight.w300,
                            color: AppTheme.accent,
                            height: 0.8,
                            fontFamily: 'serif',
                          ),
                        ),
                      ),

                      // Vision text
                      Padding(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 24,
                          vertical: 16,
                        ),
                        child: Text(
                          vision,
                          style: const TextStyle(
                            fontSize: 20,
                            fontWeight: FontWeight.w600,
                            color: AppTheme.onSurface,
                            height: 1.5,
                          ),
                          textAlign: TextAlign.center,
                        ),
                      ),

                      // Closing quote
                      const Positioned(
                        bottom: 0,
                        right: 0,
                        child: RotatedBox(
                          quarterTurns: 2,
                          child: Text(
                            '"',
                            style: TextStyle(
                              fontSize: 60,
                              fontWeight: FontWeight.w300,
                              color: AppTheme.accent,
                              height: 0.8,
                              fontFamily: 'serif',
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          if (hasVision) const SizedBox(height: 32),

          // Mission & Values Row - only if has mission or values
          if (hasMission || hasValues)
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Mission Column - only if has mission
                if (hasMission)
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Mission Header
                        Row(
                          children: [
                            Container(
                              padding: const EdgeInsets.all(10),
                              decoration: BoxDecoration(
                                color: AppTheme.primary,
                                borderRadius: BorderRadius.circular(12),
                              ),
                              child: const Icon(
                                Icons.check_circle,
                                color: Colors.white,
                                size: 20,
                              ),
                            ),
                            const SizedBox(width: 12),
                            const Text(
                              'Misi Kami',
                              style: TextStyle(
                                fontSize: 18,
                                fontWeight: FontWeight.w800,
                                color: AppTheme.primary,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 16),

                        // Mission List
                        Container(
                          padding: const EdgeInsets.all(16),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(16),
                            border: Border.all(color: AppTheme.muted),
                          ),
                          child: missions.isEmpty
                              ? Text(
                                  missionText ?? '',
                                  style: const TextStyle(
                                    fontSize: 14,
                                    height: 1.6,
                                    color: AppTheme.onSurfaceVariant,
                                  ),
                                )
                              : Column(
                                  children: missions.asMap().entries.map((
                                    entry,
                                  ) {
                                    final index = entry.key;
                                    final mission = entry.value;
                                    return Padding(
                                      padding: const EdgeInsets.only(
                                        bottom: 12,
                                      ),
                                      child: Row(
                                        crossAxisAlignment:
                                            CrossAxisAlignment.start,
                                        children: [
                                          Container(
                                            width: 28,
                                            height: 28,
                                            decoration: BoxDecoration(
                                              color: AppTheme.accent.withValues(
                                                alpha: 0.1,
                                              ),
                                              borderRadius:
                                                  BorderRadius.circular(8),
                                            ),
                                            child: Center(
                                              child: Text(
                                                '${index + 1}',
                                                style: const TextStyle(
                                                  fontSize: 13,
                                                  fontWeight: FontWeight.w800,
                                                  color: AppTheme.accent,
                                                ),
                                              ),
                                            ),
                                          ),
                                          const SizedBox(width: 12),
                                          Expanded(
                                            child: Text(
                                              mission,
                                              style: const TextStyle(
                                                fontSize: 14,
                                                height: 1.5,
                                                color:
                                                    AppTheme.onSurfaceVariant,
                                              ),
                                            ),
                                          ),
                                        ],
                                      ),
                                    );
                                  }).toList(),
                                ),
                        ),
                      ],
                    ),
                  ),
              ],
            ),
          if (hasMission || hasValues) const SizedBox(height: 32),

          // Values Section - only if has values
          if (hasValues)
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(10),
                  decoration: BoxDecoration(
                    color: AppTheme.accent,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: const Icon(Icons.star, color: Colors.white, size: 20),
                ),
                const SizedBox(width: 12),
                const Text(
                  'Nilai Perusahaan',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.w800,
                    color: AppTheme.primary,
                  ),
                ),
              ],
            ),
          if (hasValues) const SizedBox(height: 16),

          // Values Grid - only if has values
          if (hasValues)
            GridView.builder(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 2,
                crossAxisSpacing: 12,
                mainAxisSpacing: 12,
                childAspectRatio: 1.3,
              ),
              itemCount: companyValues.length,
              itemBuilder: (context, index) {
                final value = companyValues[index];
                return Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: AppTheme.muted),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Container(
                            width: 6,
                            height: 6,
                            decoration: const BoxDecoration(
                              color: AppTheme.accent,
                              shape: BoxShape.circle,
                            ),
                          ),
                          const SizedBox(width: 8),
                          Expanded(
                            child: Text(
                              value.title,
                              style: const TextStyle(
                                fontSize: 14,
                                fontWeight: FontWeight.w800,
                                color: AppTheme.primary,
                              ),
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                            ),
                          ),
                        ],
                      ),
                      if (value.description.isNotEmpty) ...[
                        const SizedBox(height: 8),
                        Expanded(
                          child: Text(
                            value.description,
                            style: const TextStyle(
                              fontSize: 12,
                              height: 1.4,
                              color: AppTheme.onSurfaceVariant,
                            ),
                            maxLines: 3,
                            overflow: TextOverflow.ellipsis,
                          ),
                        ),
                      ],
                    ],
                  ),
                );
              },
            ),
        ],
      ),
    );
  }
}
