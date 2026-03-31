import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../../config/theme.dart';
import '../../../utils/storage_url.dart';
import '../../../models/tentang_kami/facility_model.dart';

class ProductionFacilitiesSection extends StatelessWidget {
  final List<Facility> facilities;

  const ProductionFacilitiesSection({super.key, required this.facilities});

  String _getImageUrl(String? image) {
    return StorageUrl.format(image);
  }

  @override
  Widget build(BuildContext context) {
    if (facilities.isEmpty) return const SizedBox.shrink();

    return Container(
      color: Colors.white,
      padding: const EdgeInsets.symmetric(vertical: 48, horizontal: 24),
      child: Column(
        children: [
          // Header
          Column(
            children: [
              const Text(
                'BENGKEL KREATIVITAS',
                style: TextStyle(
                  fontSize: 11,
                  fontWeight: FontWeight.w800,
                  color: AppTheme.accent,
                  letterSpacing: 0.5,
                ),
              ),
              const SizedBox(height: 12),
              const Text(
                'Fasilitas Produksi Terpadu',
                style: TextStyle(
                  fontSize: 26,
                  fontWeight: FontWeight.w900,
                  color: AppTheme.primary,
                ),
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 12),
              Text(
                'Pusat dari setiap mahakarya. Didukung mesin berteknologi mutakhir dan tenaga ahli berpengalaman untuk memastikan setiap produk memenuhi standar tertinggi.',
                style: TextStyle(
                  fontSize: 14,
                  height: 1.6,
                  color: AppTheme.slate500,
                ),
                textAlign: TextAlign.center,
              ),
            ],
          ),
          const SizedBox(height: 32),

          // Facilities Grid - Bento style
          GridView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            gridDelegate: SliverGridDelegateWithMaxCrossAxisExtent(
              maxCrossAxisExtent: MediaQuery.of(context).size.width > 400
                  ? 200
                  : double.infinity,
              crossAxisSpacing: 12,
              mainAxisSpacing: 12,
              childAspectRatio: 1.0,
            ),
            itemCount: facilities.length,
            itemBuilder: (context, index) {
              final facility = facilities[index];
              final imageUrl = _getImageUrl(facility.image);

              // First and 4th items span 2 columns (large cards) - design reference
              // final isLarge = index == 0 || index == 3;

              return Container(
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: AppTheme.slate200),
                ),
                clipBehavior: Clip.antiAlias,
                child: Stack(
                  fit: StackFit.expand,
                  children: [
                    // Background Image
                    if (imageUrl.isNotEmpty)
                      CachedNetworkImage(
                        imageUrl: imageUrl,
                        fit: BoxFit.cover,
                        placeholder: (context, url) => Container(
                          color: AppTheme.primary.withOpacity(0.05),
                          child: const Center(
                            child: SizedBox(
                              width: 24,
                              height: 24,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                color: AppTheme.primary,
                              ),
                            ),
                          ),
                        ),
                        errorWidget: (context, url, error) => Container(
                          color: AppTheme.primary.withOpacity(0.05),
                          child: Center(
                            child: Icon(
                              Icons.image_outlined,
                              size: 32,
                              color: AppTheme.slate300,
                            ),
                          ),
                        ),
                      )
                    else
                      Container(
                        color: AppTheme.primary.withOpacity(0.05),
                        child: Center(
                          child: Text(
                            'NO IMAGE',
                            style: TextStyle(
                              fontSize: 10,
                              color: AppTheme.slate400,
                              letterSpacing: 2,
                            ),
                          ),
                        ),
                      ),

                    // Gradient Overlay
                    Container(
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          begin: Alignment.topCenter,
                          end: Alignment.bottomCenter,
                          colors: [
                            Colors.transparent,
                            AppTheme.primary.withOpacity(0.7),
                          ],
                          stops: const [0.5, 1.0],
                        ),
                      ),
                    ),

                    // Content
                    Positioned(
                      bottom: 0,
                      left: 0,
                      right: 0,
                      child: Padding(
                        padding: const EdgeInsets.all(16),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            // Badge
                            Row(
                              children: [
                                Container(
                                  width: 20,
                                  height: 2,
                                  color: AppTheme.accent,
                                ),
                                const SizedBox(width: 8),
                                const Text(
                                  'MITOLOGI',
                                  style: TextStyle(
                                    fontSize: 9,
                                    fontWeight: FontWeight.w800,
                                    color: AppTheme.accent,
                                    letterSpacing: 1,
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 8),

                            // Facility Name
                            Text(
                              facility.name,
                              style: const TextStyle(
                                fontSize: 16,
                                fontWeight: FontWeight.w800,
                                color: Colors.white,
                              ),
                              maxLines: 2,
                              overflow: TextOverflow.ellipsis,
                            ),

                            // Description (if available)
                            if (facility.description != null &&
                                facility.description!.isNotEmpty)
                              Padding(
                                padding: const EdgeInsets.only(top: 4),
                                child: Text(
                                  facility.description!,
                                  style: TextStyle(
                                    fontSize: 12,
                                    fontWeight: FontWeight.w500,
                                    color: Colors.white.withOpacity(0.8),
                                  ),
                                  maxLines: 2,
                                  overflow: TextOverflow.ellipsis,
                                ),
                              ),
                          ],
                        ),
                      ),
                    ),
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
