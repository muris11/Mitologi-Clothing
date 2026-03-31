import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../../config/theme.dart';
import '../../../utils/storage_url.dart';

class ServiceItem {
  final String title;
  final String description;
  final String? imageUrl;

  ServiceItem({required this.title, required this.description, this.imageUrl});
}

class ServicesSection extends StatelessWidget {
  final List<ServiceItem> services;

  const ServicesSection({super.key, required this.services});

  @override
  Widget build(BuildContext context) {
    return Container(
      color: AppTheme.surfaceContainerLow,
      padding: const EdgeInsets.symmetric(vertical: 40, horizontal: 24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Layanan Kami',
            style: TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.w800,
              color: AppTheme.primary,
            ),
          ),
          const SizedBox(height: 24),
          ...services.asMap().entries.map((entry) {
            final index = entry.key;
            final service = entry.value;
            final isEven = index % 2 == 0;

            return Container(
              margin: const EdgeInsets.only(bottom: 20),
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(20),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.03),
                    blurRadius: 10,
                    offset: const Offset(0, 4),
                  ),
                ],
              ),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  if (isEven && service.imageUrl != null) ...[
                    _buildImage(service.imageUrl!),
                    const SizedBox(width: 16),
                  ],
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          service.title,
                          style: const TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.w800,
                            color: AppTheme.slate800,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          service.description,
                          style: const TextStyle(
                            fontSize: 13,
                            color: AppTheme.slate600,
                            height: 1.5,
                          ),
                        ),
                      ],
                    ),
                  ),
                  if (!isEven && service.imageUrl != null) ...[
                    const SizedBox(width: 16),
                    _buildImage(service.imageUrl!),
                  ],
                ],
              ),
            );
          }),
        ],
      ),
    );
  }

  Widget _buildImage(String url) {
    final imageUrl = StorageUrl.format(url);

    return ClipRRect(
      borderRadius: BorderRadius.circular(12),
      child: CachedNetworkImage(
        imageUrl: imageUrl,
        width: 80,
        height: 80,
        fit: BoxFit.cover,
        placeholder: (context, url) => Container(
          width: 80,
          height: 80,
          color: AppTheme.slate100,
          child: const Center(
            child: SizedBox(
              width: 20,
              height: 20,
              child: CircularProgressIndicator(
                strokeWidth: 2,
                color: AppTheme.primary,
              ),
            ),
          ),
        ),
        errorWidget: (context, url, error) => Container(
          width: 80,
          height: 80,
          color: AppTheme.slate100,
          child: const Icon(Icons.image_outlined, color: AppTheme.slate300),
        ),
      ),
    );
  }
}
